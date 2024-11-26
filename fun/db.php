<?php
// Database Configuration
$servername = "localhost";    // Database server
$username = "root";           // Database username
$password = "";               // Database password (if any)
$dbname = "goal7";            // Database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optionally, you can set the character set to avoid any encoding issues
$conn->set_charset("utf8");

// Check if Username is already taken
function isUsernameTaken($username) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;  // Returns true if the username is already taken
}

// Check if Email is already taken
function isEmailTaken($email) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;  // Returns true if the email is already taken
}

// Insert User Data
function insertUser($username, $email, $password, $first_name, $last_name, $dob, $phone, $address, $current_area) {
    global $conn;
    
    // Check if username or email already exists
    if (isUsernameTaken($username)) {
        echo "Username is already taken.";
        return false;
    }

    if (isEmailTaken($email)) {
        echo "Email is already taken.";
        return false;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, first_name, last_name, dob, phone, address, current_area) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $username, $email, $hashed_password, $first_name, $last_name, $dob, $phone, $address, $current_area);

    return $stmt->execute();
}

// Get User Data by ID
function getUserById($userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

// Get User by Username
function getUserByUsername($username) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

// Update User Data
function updateUser($userId, $email, $first_name, $last_name, $dob, $phone, $address, $current_area, $promo_count, $verified) {
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET email = ?, first_name = ?, last_name = ?, dob = ?, phone = ?, address = ?, 
                            current_area = ?, promo_count = ?, verified = ? WHERE id = ?");
    $stmt->bind_param("ssssssiiii", $email, $first_name, $last_name, $dob, $phone, $address, $current_area, $promo_count, $verified, $userId);
    return $stmt->execute();
}

// Update Password
function updatePassword($userId, $newPassword) {
    global $conn;
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $userId);
    return $stmt->execute();
}

// Delete User
function deleteUser($userId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    return $stmt->execute();
}

// Get All Users
function getAllUsers() {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}

// Verify User (set verified = 1)
function verifyUser($userId) {
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE id = ?");
    $stmt->bind_param("i", $userId);
    return $stmt->execute();
}
?>
