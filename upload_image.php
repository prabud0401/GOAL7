<?php
// Ensure the session is started to access user information if needed
session_start();

// Set upload directory
$uploadDir = 'uploads/';

// Check if a file is uploaded
if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
    // Get file details
    $fileTmpPath = $_FILES['image_file']['tmp_name'];
    $fileName = $_FILES['image_file']['name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    // Allowed file types
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type.']);
        exit();
    }

    // Generate a unique file name
    $newFileName = uniqid() . '.' . $fileExtension;

    // Move the file to the upload directory
    $destPath = $uploadDir . $newFileName;
    if (move_uploaded_file($fileTmpPath, $destPath)) {
        // Respond with the image URL
        $imageUrl = 'http://localhost/goal7/'.$destPath;
        echo json_encode(['status' => 'success', 'url' => $imageUrl]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error uploading file.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded or error occurred.']);
}
?>
