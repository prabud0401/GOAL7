<?php
// Include database configuration
include('../fun/db.php');

// Function to insert the futsal court
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $name = $_POST['name'];
    $location = $_POST['location'];
    $features = $_POST['features'];
    $price_per_hour = $_POST['price_per_hour'];
    $max_players = $_POST['max_players'];
    $availability_status = $_POST['availability_status'];
    $owner_id = $_POST['owner_id']; // Assuming the owner ID is passed from the session or authentication
    $area_id = $_POST['area_id'];
    $start_hour = $_POST['start_hour'];
    $end_hour = $_POST['end_hour'];
    $image = $_POST['image'];


    // Prepare the SQL query to insert data into the futsal_courts table
    $query = "INSERT INTO futsal_courts (name, location, image, features, price_per_hour, max_players, availability_status, owner_id, area_id, start_hour, end_hour) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sssdiiissss",
        $name,
        $location,
        $image,
        $features,
        $price_per_hour,
        $max_players,
        $availability_status,
        $owner_id,
        $area_id,
        $start_hour,
        $end_hour
    );

    if ($stmt->execute()) {
        // Get the ID of the inserted futsal court
        $futsalCourtId = $stmt->insert_id;

        // Insert hourly slots for the futsal court
        insertHourlySlots($conn, $futsalCourtId, $start_hour, $end_hour);

        // Return a success response
        $response = ['status' => 'success', 'message' => 'Futsal court added successfully!'];
    } else {
        // Return an error response
        $response = ['status' => 'error', 'message' => 'Failed to add futsal court. Please try again.'];
    }

    // Return the response as JSON
    echo json_encode($response);
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
?>
