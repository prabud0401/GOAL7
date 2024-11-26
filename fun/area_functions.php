<?php

// Function to Get All Areas
function getAreas() {
    global $conn;
    $query = "SELECT * FROM areas";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Function to Insert a New Area
function insertArea($name, $slug) {
    global $conn;
    $query = "INSERT INTO areas (name, slug) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $name, $slug);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Function to Delete an Area
function deleteArea($id) {
    global $conn;
    $query = "DELETE FROM areas WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}
?>
