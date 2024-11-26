<?php
// Start session to check if user is logged in
session_start();

// Include necessary files (header, nav, footer, etc.)
include('./includes/header.php');
include('./includes/nav.php');
include('./fun/db.php');  // Include the database connection

// Check if the required query parameters are set in the URL
if (isset($_GET['futsal_id']) && isset($_GET['slot_ids']) && isset($_GET['total_duration']) && isset($_GET['total_price']) && isset($_GET['username'])) {
    // Retrieve the parameters from the URL
    $futsalId = $_GET['futsal_id'];
    $slotIds = explode(',', $_GET['slot_ids']); // Convert the comma-separated string back into an array
    $totalDuration = $_GET['total_duration'];
    $totalPrice = $_GET['total_price'];
    $username = $_GET['username'];
} else {
    // If URL parameters are missing, show an error message
    echo "<p>Error: Missing booking details.</p>";
    exit;
}

// Fetch futsal court details
$query = "SELECT name, location, price_per_hour, image FROM futsal_courts WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $futsalId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Bind the result to variables
    $stmt->bind_result($futsalName, $futsalLocation, $pricePerHour, $futsalImage);
    $stmt->fetch();
} else {
    echo "<p>Error: Futsal court not found.</p>";
    exit;
}
?>

<main class="w-full h-auto flex justify-center p-4 text-black">
    <section class="bg-slate-500 rounded-3xl md:w-3/4 w-full h-full p-4 justify-center items-center flex flex-col">
        <h2 class="text-2xl font-bold text-yellow-500 mb-8 text-center"><?= htmlspecialchars($futsalName); ?> Booking Details</h2>

        <!-- Display Booking Details -->
        <div class="bg-zinc-800 rounded-lg overflow-hidden shadow-md p-4 md:w-[400px]">
            <?php if ($futsalImage) { ?>
                <img src="<?= htmlspecialchars($futsalImage); ?>" alt="Futsal Court" class="w-full h-[180px] object-cover">
            <?php } else { ?>
                <img src="placeholder.jpg" alt="Futsal Court" class="w-full h-[180px] object-cover">
            <?php } ?>
            <h3 class="font-bold text-lg mt-4"><?= htmlspecialchars($futsalName); ?></h3>
            <p class="text-gray-300">Location: <?= htmlspecialchars($futsalLocation); ?></p>
            <p class="text-gray-300">Price per Hour: LKR <?= number_format($pricePerHour, 2); ?></p>
        </div>

        <!-- Selected Time Slots -->
        <h3 class="text-xl font-bold text-yellow-500 mt-6">Selected Time Slots</h3>
        <ul class="list-disc pl-6">
            <?php foreach ($slotIds as $slotId) { ?>
                <li class="text-gray-300">Slot ID: <?= htmlspecialchars($slotId); ?></li>
            <?php } ?>
        </ul>

        <!-- Booking Summary -->
        <div class="mt-4">
            <p class="text-white">Total Duration: <?= $totalDuration; ?> hour(s)</p>
            <p class="text-white">Total Price: LKR <?= number_format($totalPrice, 2); ?></p>
            <p class="text-white">Username: <?= htmlspecialchars($username); ?></p>
        </div>

        <!-- Payment Method Selection -->
        <div class="flex md:flex-row flex-col w-full p-4 md:space-x-16 md:w-3/4 justify-center items-center">
            <div class="mt-6 text-center text-black">
                <label for="paymentMethod" class="text-white">Select Payment Method</label>
                <select id="paymentMethod" class="w-full p-2 mb-4">
                    <option value="card">Card Payment</option>
                    <option value="bank">Bank Transfer</option>
                </select>
            </div>

            <!-- Card Payment Form -->
            <div id="cardPaymentForm" class="mt-8 hidden">
                <h3 class="text-xl font-bold text-yellow-500">Card Payment</h3>
                <form id="cardPaymentFormDetails">
                    <div class="mb-4">
                        <label for="cardNumber" class="text-white">Card Number</label>
                        <input type="text" id="cardNumber" class="w-full p-2" placeholder="1234 5678 9876 5432" required>
                    </div>
                    <div class="mb-4">
                        <label for="expiryDate" class="text-white">Expiry Date</label>
                        <input type="text" id="expiryDate" class="w-full p-2" placeholder="MM/YY" required>
                    </div>
                    <div class="mb-4">
                        <label for="cvv" class="text-white">CVV</label>
                        <input type="text" id="cvv" class="w-full p-2" placeholder="123" required>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                        Simulate Payment (Card)
                    </button>
                </form>
            </div>

            <!-- Bank Transfer Instructions -->
            <div id="bankTransferForm" class="mt-8 hidden">
                <h3 class="text-xl font-bold text-yellow-500">Bank Transfer Instructions</h3>
                <p class="text-white">Please transfer the total amount to the following bank account:</p>
                <ul class="text-white">
                    <li>Bank Name: ABC Bank</li>
                    <li>Account Number: 123456789</li>
                    <li>Account Name: Futsal Booking</li>
                </ul>
                <button id="confirmBankTransfer" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                    Mark as Not Paid (Bank)
                </button>
            </div>
        </div>

        <!-- Modals -->
        <div id="bookingModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold">Processing Booking...</h2>
                <p class="mt-4">Please wait while we process your payment...</p>
            </div>
        </div>

        <div id="successModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold text-green-500">Payment Successful!</h2>
                <p class="mt-4">Your booking has been processed successfully.</p>
                <button class="mt-4 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600" id="successClose">
                    Close
                </button>
            </div>
        </div>

        <div id="errorModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold text-red-500">Payment Failed!</h2>
                <p class="mt-4">There was an error processing your payment. Please try again.</p>
                <button class="mt-4 bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600" id="errorClose">
                    Close
                </button>
            </div>
        </div>
    </section>
</main>

<script>
function processPayment(paymentStatus) {
    document.getElementById('bookingModal').classList.remove('hidden');

    $.ajax({
        url: './fun/payment-status.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(paymentStatus),
        success: function(response) {
            document.getElementById('bookingModal').classList.add('hidden');

            // Assume the response contains the payment_id
            if (response && response.payment_id) {
                const paymentId = response.payment_id;

                // Redirect to confirmation.php with payment_id in the URL
                window.location.href = `recipt.php?payment_id=${encodeURIComponent(paymentId)}`;
            } else {
                document.getElementById('errorModal').classList.remove('hidden');
            }
        },
        error: function(error) {
            document.getElementById('bookingModal').classList.add('hidden');
            document.getElementById('errorModal').classList.remove('hidden');
        }
    });
}


document.getElementById('paymentMethod').addEventListener('change', function() {
    const paymentMethod = this.value;
    document.getElementById('cardPaymentForm').classList.toggle('hidden', paymentMethod !== 'card');
    document.getElementById('bankTransferForm').classList.toggle('hidden', paymentMethod !== 'bank');
});

document.getElementById('cardPaymentFormDetails').addEventListener('submit', function(event) {
    event.preventDefault();
    const paymentStatus = {
        method: 'card',
        amount: <?= $totalPrice ?>,
        slots: <?= json_encode($slotIds) ?>,
        futsal_id: <?= $futsalId ?>,
        totalDuration: <?= $totalDuration ?>,
        username: '<?= $username ?>'
    };
    processPayment(paymentStatus);
});

document.getElementById('confirmBankTransfer').addEventListener('click', function() {
    const paymentStatus = {
        method: 'bank',
        amount: <?= $totalPrice ?>,
        slots: <?= json_encode($slotIds) ?>,
        futsal_id: <?= $futsalId ?>,
        totalDuration: <?= $totalDuration ?>,
        username: '<?= $username ?>'
    };
    processPayment(paymentStatus);
});

document.getElementById('successClose').addEventListener('click', function() {
    document.getElementById('successModal').classList.add('hidden');
    window.location.href = 'recipt.php?';
});

document.getElementById('errorClose').addEventListener('click', function() {
    document.getElementById('errorModal').classList.add('hidden');
});
</script>
