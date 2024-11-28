<?php
session_start();

// Include database configuration
include('../fun/db.php');

// Function to insert the futsal court
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $name = $_POST['name'];
    $location = $_POST['location'];
    $features = $conn->real_escape_string($_POST['features']);
    $price_per_hour = $_POST['price_per_hour'];
    $max_players = $_POST['max_players'];
    $availability_status = $_POST['availability_status'];
    $owner_id = $_SESSION["user_id"];
    $area_id = $_POST['area_id'];
    $start_hour = $_POST['start_hour'];
    $end_hour = $_POST['end_hour'];
    $image = $_POST['image_url'];
    $start_date = $_POST['start_date'];  // Newly added start date
    $end_date = $_POST['end_date'];      // Newly added end date

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
    // Handle success
    $futsalCourtId = $stmt->insert_id;
    insertHourlySlots($conn, $futsalCourtId, $start_date, $end_date, $start_hour, $end_hour);
    $response = ['status' => 'success', 'message' => 'Futsal court added successfully!'];
} else {
    // Handle error
    $response = ['status' => 'error', 'message' => 'Failed to add futsal court. Please try again.'];
    error_log('SQL Error: ' . $stmt->error);  // Log the SQL error for debugging
}

echo json_encode($response);
}

// Function to insert hourly slots into futsal_court_slots for each day between start_date and end_date
function insertHourlySlots($conn, $futsalCourtId, $startDate, $endDate, $startHour, $endHour) {
    // Convert start_date and end_date to timestamps
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    // Loop through each day in the date range
    while ($startDate <= $endDate) {
        $currentDay = date('Y-m-d', $startDate);

        // Insert hourly slots for this day
        insertSlotsForDay($conn, $futsalCourtId, $currentDay, $startHour, $endHour);

        // Move to the next day
        $startDate = strtotime('+1 day', $startDate);
    }
}

// Function to insert hourly slots for a specific day
function insertSlotsForDay($conn, $futsalCourtId, $currentDay, $startHour, $endHour) {
    $start = strtotime($currentDay . ' ' . $startHour);
    $end = strtotime($currentDay . ' ' . $endHour);

    $query = "INSERT INTO futsal_court_slots (futsal_court_id, slot_date, slot_hour, is_booked) VALUES (?, ?, ?, 0)";
    $stmt = $conn->prepare($query);

    // Loop through each hour in the specified range
    while ($start < $end) {
        $slotHour = date('H:i:s', $start);
        $stmt->bind_param("iss", $futsalCourtId, $currentDay, $slotHour);
        $stmt->execute();
        $start = strtotime('+1 hour', $start); // Move to the next hour
    }
}

?>
