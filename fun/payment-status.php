<?php
// payment-status.php

// Start session to check if user is logged in
session_start();

// Include necessary files (e.g., database connection)
include('./db.php');

// Function to generate a random payment ID
function generateRandomPaymentID($length = 12) {
    $characters = 'A21B1CD212EFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $paymentID = '';
    for ($i = 0; $i < $length; $i++) {
        $paymentID .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return 'Goal7-' . $paymentID;
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the POST data (payment status as JSON)
    $paymentData = json_decode(file_get_contents('php://input'), true);

    if ($paymentData) {
        // Extract the data
        $paymentMethod = $paymentData['method'] ?? null;
        $amount = $paymentData['amount'] ?? null;
        $slots = $paymentData['slots'] ?? [];
        $username = $paymentData['username'] ?? null;
        $futsalId = $paymentData['futsal_id'] ?? null;

        // Check availability status of the futsal court
        $courtQuery = "SELECT availability_status FROM futsal_courts WHERE id = ?";
        if ($courtStmt = $conn->prepare($courtQuery)) {
            $courtStmt->bind_param("i", $futsalId);
            $courtStmt->execute();
            $courtStmt->bind_result($availabilityStatus);
            $courtStmt->fetch();
            $courtStmt->close();

            if ($availabilityStatus != 1) {
                // Court is not available
                $response = [
                    'status' => 'error',
                    'message' => 'The selected futsal court is not available.'
                ];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Error checking court availability.'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Check if any selected slots are already booked
        $slotIds = implode(',', array_map('intval', $slots));
        $slotQuery = "SELECT id FROM futsal_court_slots WHERE id IN ($slotIds) AND is_booked = 1";
        $result = $conn->query($slotQuery);

        if ($result && $result->num_rows > 0) {
            // Some slots are already booked
            $response = [
                'status' => 'error',
                'message' => 'One or more selected slots are already booked.'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Generate a random payment ID
        $paymentID = generateRandomPaymentID();

        // Determine payment status based on the payment method
        $paymentStatus = ($paymentMethod === 'card') ? 'completed' : 'pending';

        // Insert payment data into the database
        $sql = "INSERT INTO payments (payment_id, method, amount, slots, username, futsal_id, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

        if ($stmt = $conn->prepare($sql)) {
            $slotsJson = json_encode($slots); // Convert slots to JSON
            $stmt->bind_param("ssdssss", $paymentID, $paymentMethod, $amount, $slotsJson, $username, $futsalId, $paymentStatus);
            if ($stmt->execute()) {
                // Update the slots in the futsal_court_slots table
                $updateSlotQuery = "UPDATE futsal_court_slots 
                                    SET is_booked = 1, booked_by = ?, payment_id = ? 
                                    WHERE id = ? AND futsal_court_id = ?";
                $updateStmt = $conn->prepare($updateSlotQuery);

                if ($updateStmt) {
                    foreach ($slots as $slotId) {
                        $updateStmt->bind_param("ssii", $username, $paymentID, $slotId, $futsalId);
                        $updateStmt->execute();
                    }
                    $updateStmt->close();
                }

                // Increment the `promo_count` in the users table
                $updatePromoCountQuery = "UPDATE users SET promo_count = promo_count + 1 WHERE username = ?";
                $promoStmt = $conn->prepare($updatePromoCountQuery);



                if ($promoStmt) {
                    $promoStmt->bind_param("s", $username);
                    $promoStmt->execute();
                    $promoStmt->close();
                }

                // Prepare a success response
                $response = [
                    'status' => 'success',
                    'message' => 'Payment status received and stored successfully.',
                    'payment_id' => $paymentID,
                    'data' => [
                        'payment_id' => $paymentID,
                        'method' => $paymentMethod,
                        'amount' => $amount,
                        'slots' => $slots,
                        'username' => $username,
                        'futsal_id' => $futsalId,
                        'status' => $paymentStatus
                    ]
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to store payment data in the database.'
                ];
            }
            $stmt->close();
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Database connection error.'
            ];
        }

        // Send the response
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        $response = [
            'status' => 'error',
            'message' => 'No payment data received.'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'Invalid request method.'
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
