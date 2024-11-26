<?php
// Include the database configuration
include('./db.php');

// Function to fetch all futsal courts
function getFutsalCourts($conn) {
    $query = "SELECT * FROM futsal_courts";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}

// Function to fetch a specific futsal court by ID
function getFutsalCourtById($conn, $id) {
    $query = "SELECT * FROM futsal_courts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Function to insert hourly slots into futsal_court_slots
function insertHourlySlots($conn, $futsalCourtId, $startHour, $endHour) {
    $start = strtotime($startHour);
    $end = strtotime($endHour);

    $query = "INSERT INTO futsal_court_slots (futsal_court_id, slot_hour, is_booked) VALUES (?, ?, 0)";
    $stmt = $conn->prepare($query);

    while ($start < $end) {
        $slotHour = date('H:i:s', $start);
        $stmt->bind_param("is", $futsalCourtId, $slotHour);
        $stmt->execute();
        $start = strtotime('+1 hour', $start);
    }
}

// Function to insert a new futsal court
function insertFutsalCourt($conn, $data) {
    // Insert into futsal_courts table
    $query = "INSERT INTO futsal_courts (name, location, image, features, price_per_hour, max_players, availability_status, owner_id, area_id, start_hour, end_hour) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sssdiiissss",
        $data['name'],
        $data['location'],
        $data['image'],
        $data['features'],
        $data['price_per_hour'],
        $data['max_players'],
        $data['availability_status'],
        $data['owner_id'],
        $data['area_id'],
        $data['start_hour'],
        $data['end_hour']
    );

    if ($stmt->execute()) {
        // Get the ID of the inserted court
        $futsalCourtId = $stmt->insert_id;

        // Insert hourly slots for the court
        insertHourlySlots($conn, $futsalCourtId, $data['start_hour'], $data['end_hour']);

        return true;
    }
    return false;
}

// Function to update an existing futsal court
function updateFutsalCourt($conn, $id, $data) {
    $query = "UPDATE futsal_courts SET name = ?, location = ?, image = ?, features = ?, price_per_hour = ?, max_players = ?, availability_status = ?, owner_id = ?, area_id = ?, start_hour = ?, end_hour = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sssdiiissssi",
        $data['name'],
        $data['location'],
        $data['image'],
        $data['features'],
        $data['price_per_hour'],
        $data['max_players'],
        $data['availability_status'],
        $data['owner_id'],
        $data['area_id'],
        $data['start_hour'],
        $data['end_hour'],
        $id
    );

    if ($stmt->execute()) {
        return true;
    }
    return false;
}

// Function to delete a futsal court and its slots
function deleteFutsalCourt($conn, $id) {
    // Delete slots first
    $querySlots = "DELETE FROM futsal_court_slots WHERE futsal_court_id = ?";
    $stmtSlots = $conn->prepare($querySlots);
    $stmtSlots->bind_param("i", $id);
    $stmtSlots->execute();

    // Delete the court
    $query = "DELETE FROM futsal_courts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        return true;
    }
    return false;
}
?>
