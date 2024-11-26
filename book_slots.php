<?php
session_start();
include('./fun/db.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $slotIds = $_POST['slot_ids'];
    $futsalId = $_POST['futsal_id'];
    $totalDuration = $_POST['total_duration'];
    $totalPrice = $_POST['total_price'];
    $username = $_POST['username'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update slots as booked
        foreach ($slotIds as $slotId) {
            $stmt = $conn->prepare("UPDATE futsal_court_slots SET is_booked = 1 WHERE id = ?");
            $stmt->bind_param("i", $slotId);
            $stmt->execute();
        }

        // Insert booking record into bookings table
        $stmt = $conn->prepare("INSERT INTO bookings (futsal_court_id, username, total_duration, total_price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isid", $futsalId, $username, $totalDuration, $totalPrice);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        echo json_encode(['status' => 'success', 'message' => 'Booking confirmed']);
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Booking failed. Please try again.']);
    }
}
?>
