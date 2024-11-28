<?php 
// confirmation.php

// Start session to check if user is logged in
session_start();

// Include necessary files (e.g., database connection)
include('./fun/db.php');

// Check if `payment_id` is provided in the query string
if (isset($_GET['payment_id'])) {
    $paymentId = $_GET['payment_id'];

    // Fetch payment details
    $query = "
        SELECT 
            p.payment_id, 
            p.method, 
            p.amount, 
            p.slots, 
            p.username, 
            p.status, 
            p.created_at AS payment_date, 
            fc.name AS futsal_name, 
            fc.location AS futsal_location, 
            fc.price_per_hour, 
            fc.features
        FROM 
            payments p
        JOIN 
            futsal_courts fc 
        ON 
            p.futsal_id = fc.id
        WHERE 
            p.payment_id = ?
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $paymentId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $paymentDetails = $result->fetch_assoc();

            // Decode slots to display them properly
            $slots = json_decode($paymentDetails['slots'], true);

            // Prepare to display the slot details
            $slotDetails = [];

            if (!empty($slots)) {
                foreach ($slots as $slotId) {
                    // Fetch slot details based on slot ID from futsal_court_slots table
                    $slotQuery = "
                        SELECT 
                            s.id AS slot_id, 
                            s.slot_hour, 
                            s.is_booked, 
                            s.booked_by
                        FROM 
                            futsal_court_slots s
                        WHERE 
                            s.id = ?
                    ";

                    if ($slotStmt = $conn->prepare($slotQuery)) {
                        $slotStmt->bind_param("i", $slotId); // Use "i" for integer type (slot ID)
                        $slotStmt->execute();
                        $slotResult = $slotStmt->get_result();

                        if ($slotResult->num_rows > 0) {
                            $slotDetails[] = $slotResult->fetch_assoc();
                        }
                        $slotStmt->close();
                    }
                }
            }

            // Display the receipt page
            ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .receipt {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .icon {
            text-align: center;
            font-size: 9rem; 
            color: green; 
            margin-bottom: 20px;
        }
        .receipt h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .receipt p {
            line-height: 1.6;
            margin: 10px 0;
        }
        .receipt .slots {
            background-color: #eee;
            padding: 10px;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="icon">
            <i class="ri-check-double-fill"></i>
        </div>
        
        <h2>Payment Confirmation</h2>
        <p><strong>Payment ID:</strong> <?php echo htmlspecialchars($paymentDetails['payment_id']); ?></p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($paymentDetails['username']); ?></p>
        <p><strong>Payment Method:</strong> <?php echo htmlspecialchars(ucfirst($paymentDetails['method'])); ?></p>
        <p><strong>Payment Status:</strong> <?php echo htmlspecialchars(ucfirst($paymentDetails['status'])); ?></p>
        <p><strong>Amount Paid:</strong> $<?php echo number_format($paymentDetails['amount'], 2); ?></p>
        <p><strong>Payment Date:</strong> <?php echo htmlspecialchars($paymentDetails['payment_date']); ?></p>

        <hr>
        <h3>Futsal Court Details</h3>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($paymentDetails['futsal_name']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($paymentDetails['futsal_location']); ?></p>
        <p><strong>Price Per Hour:</strong> $<?php echo number_format($paymentDetails['price_per_hour'], 2); ?></p>
        <p><strong>Features:</strong> <?php echo htmlspecialchars($paymentDetails['features'] ?: 'N/A'); ?></p>

        <hr>
        <h3>Booked Slots</h3>
        <div class="slots grid grid-cols-3 md:grid-cols-5 gap-4">
    <?php 
    if (!empty($slotDetails)) {
        foreach ($slotDetails as $slot) {
            $buttonClass = $slot['is_booked'] ? 'bg-yellow-500 text-white cursor-not-allowed' : 'bg-yellow-500 text-black';

            echo "
                <button class='p-4 rounded-md $buttonClass w-full'>
                    <p class='text-center font-bold'>" . htmlspecialchars($slot['slot_hour']) . "</p>
                </button>
            ";
        }
    } else {
        echo "<p class='text-center'>No slots available.</p>";
    }
    ?>
</div>

    </div>

    <div class="footer">
        Thank you for booking with us!
    </div>
</body>
</html>

            <?php
        } else {
            // If no payment details found
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Payment not found.'
            ]);
        }
        $stmt->close();
    } else {
        // Handle database error
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Database query error.'
        ]);
    }
} else {
    // If payment_id is not provided
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'No payment ID provided.'
    ]);
}
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Capture the HTML content of the receipt
    var receiptContent = $(".receipt").html();

    // Send the content via AJAX to the send_mail.php script
    $.ajax({
        url: './otp/send_mail.php',
        type: 'POST',
        data: {
            receipt_content: receiptContent
        },
        success: function(response) {
            console.log('Email sent successfully:', response);
        },
        error: function(xhr, status, error) {
            console.error('Error sending email:', error);
        }
    });
});
</script>
