<?php
session_start();

// Include database connection
include('./db.php'); // Assuming 'db.php' contains the $conn variable

// Get form data
$user_input = $_POST['username_or_email'];
$input_password = $_POST['password'];

// Prepare SQL query to check if user exists by username or email
$sql = "SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user_input, $user_input); // Binding both username and email as strings
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($input_password, $user['password'])) {
        // Password is correct, create session and respond with success
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Set the redirect URL based on user role
        $redirect_url = ($user['role'] === 'client') ? './client/' : './index.php';

        // Return success response with redirect URL
        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful! Redirecting...',
            'redirect_url' => $redirect_url // Redirect based on user role
        ]);
    } else {
        // Password is incorrect
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid username or password.'
        ]);
    }
} else {
    // User does not exist
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid username or password.'
    ]);
}

$stmt->close();
$conn->close();
?>
