<?php
include('../fun/db.php');

// Check if the futsal ID is provided
if (isset($_GET['id'])) {
    $futsalId = (int)$_GET['id'];

    // Delete the futsal court from the database
    $stmt = $conn->prepare("DELETE FROM futsal_courts WHERE id = ?");
    $stmt->bind_param("i", $futsalId);
    $stmt->execute();

    // Redirect back to the futsals page
    header("Location: futsals.php");  // Update with the actual page name
    exit();
} else {
    echo "Invalid request.";
}
?>
