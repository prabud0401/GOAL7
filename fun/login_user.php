<?php
// Include the database connection file
include('./db.php');

// Start the session
session_start();

// Initialize response array
$response = array('status' => '', 'message' => '', 'redirect_url' => '', 'username' => '');

// Check if form data is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username_or_email = $_POST['username_or_email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($username_or_email) || empty($password)) {
        $response['status'] = 'error';
        $response['message'] = 'Please fill in all fields.';
    } else {
        // Check if the input is an email or username
        if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
            // It's an email
            $query = "SELECT * FROM users WHERE email = ?";
        } else {
            // It's a username
            $query = "SELECT * FROM users WHERE username = ?";
        }

        // Prepare the query to fetch the user based on username or email
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $username_or_email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Fetch user data
                $user = $result->fetch_assoc();

                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Successful login, check user role
                    $role = $user['role'];

                    // Store the username in the session
                    $_SESSION['username'] = $user['username'];

                    // Set redirect URL based on role
                    if ($role === 'admin') {
                        $response['status'] = 'success';
                        $response['message'] = 'Login successful!';
                        $response['redirect_url'] = './admin_dashboard.php'; // Admin dashboard
                    } elseif ($role === 'client') {
                        $response['status'] = 'success';
                        $response['message'] = 'Login successful!';
                        $response['redirect_url'] = './client_dashboard.php'; // Client dashboard
                    } elseif ($role === 'customer') {
                        $response['status'] = 'success';
                        $response['message'] = 'Login successful!';
                        $response['redirect_url'] = './customer_dashboard.php'; // Customer dashboard
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = 'User role not recognized.';
                    }
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Invalid password.';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'User not found.';
            }

            $stmt->close();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to prepare database query.';
        }
    }

    // Close the database connection
    $conn->close();

    // Return the response as JSON
    echo json_encode($response);
}
?>
