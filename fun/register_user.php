<?php
// Include the database connection file
include('./db.php');

// Initialize response array
$response = array('status' => '', 'message' => '');

// Check if form data is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $current_area = $_POST['area_id'] ?? '';
    $role = $_POST['role'] ?? '';
    $profile_image_url = $_POST['profile_image_url'] ?? '';  // New profile image field

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $response['status'] = 'error';
        $response['message'] = 'Please fill in all required fields.';
    } elseif ($password !== $confirm_password) {
        $response['status'] = 'error';
        $response['message'] = 'Passwords do not match.';
    } else {
        // Check if the username or email already exists
        $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
        if ($stmt = $conn->prepare($check_query)) {
            // Bind parameters
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // If the username or email already exists
                $response['status'] = 'error';
                $response['message'] = 'The username or email is already in use. Please choose another one.';
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Prepare SQL query to insert data into the database
                $query = "INSERT INTO users (username, email, password, name, phone, address, profile_image_url, current_area, role) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                if ($stmt = $conn->prepare($query)) {
                    // Bind parameters
                    $stmt->bind_param("sssssssss", $username, $email, $hashed_password, $full_name, $phone, $address, $profile_image_url, $current_area, $role);
                    
                    // Execute the query
                    if ($stmt->execute()) {
                        $response['status'] = 'success';
                        $response['message'] = 'Registration successful. Please log in.';
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = 'Error occurred during registration, please try again.';
                    }

                    $stmt->close();
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Failed to prepare the database query.';
                }
            }

            $stmt->close();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to prepare the database check query.';
        }
    }

    // Close the database connection
    $conn->close();

    // Return the response as JSON
    echo json_encode($response);
}
?>
