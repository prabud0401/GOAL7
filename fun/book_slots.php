<?php
// Start session to check if user is logged in
session_start();

// Include necessary files
include('./fun/db.php'); // Make sure the database connection file is included

// Check if required POST data is received
if (isset($_POST['slot_ids'], $_POST['futsal_id'], $_POST['total_duration'], $_POST['total_price'], $_POST['username'])) {
    // Fetch data from POST request
    $slotIds = $_POST['slot_ids']; // Array of selected slot IDs
    $futsalId = $_POST['futsal_id']; // Futsal court ID
    $totalDuration = $_POST['total_duration']; // Total duration of booking
    $totalPrice = $_POST['total_price']; // Total price of booking
    $username = $_POST['username']; // Username of the logged-in user

    // Start a database transaction to ensure atomic updates
    $conn->begin_transaction();

    try {
        // Update the slots as booked in the database
        foreach ($slotIds as $slotId) {
            $updateSlotQuery = "UPDATE futsal_court_slots SET is_booked = 1 WHERE id = ? AND futsal_court_id = ?";
            $stmt = $conn->prepare($updateSlotQuery);
            $stmt->bind_param("ii", $slotId, $futsalId);
            $stmt->execute();
        }

        // Optionally, you can also store the booking details in a booking table
        $insertBookingQuery = "INSERT INTO bookings (futsal_court_id, user_id, total_duration, total_price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertBookingQuery);
        $stmt->bind_param("isii", $futsalId, $username, $totalDuration, $totalPrice);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Respond with a success message
        echo json_encode([
            'status' => 'success',
            'message' => 'Booking confirmed successfully.'
        ]);
    } catch (Exception $e) {
        // Rollback the transaction in case of any error
        $conn->rollback();

        // Respond with an error message
        echo json_encode([
            'status' => 'error',
            'message' => 'An error occurred. Please try again.'
        ]);
    }
} else {
    // Respond with an error if the required data is missing
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required data.'
    ]);
}
?>
