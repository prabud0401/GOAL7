<?php
session_start(); // Ensure session is started

// Include necessary files
include('../fun/db.php');

// Check if it's an AJAX request to send OTP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'sendOtp') {
    include('send_otp.php');
}

// Handle OTP verification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'verifyOtp') {
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $_POST['otp']) {
        // Update user as verified
        $username = $_POST['username']; // Assume username is also passed for verification
        $updateQuery = "UPDATE users SET verified = 1 WHERE username = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['success' => true, 'message' => 'OTP verified successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid OTP!']);
    }
    exit;
}
?>
