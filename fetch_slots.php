<?php
include('./fun/db.php');

// Check if required parameters are passed
if (isset($_GET['futsal_id']) && isset($_GET['slot_date'])) {
    $futsalId = $_GET['futsal_id'];
    $slotDate = $_GET['slot_date'];

    // Query to fetch available slots for the given futsal court and date
    $query = "
        SELECT id, slot_hour, is_booked 
        FROM futsal_court_slots 
        WHERE futsal_court_id = ? AND slot_date = ? 
        ORDER BY slot_hour
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $futsalId, $slotDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Prepare the response in a JSON format
    $slots = [];
    while ($row = $result->fetch_assoc()) {
        $slots[] = $row;
    }

    // Return slots as JSON
    echo json_encode(['slots' => $slots]);
}
?>
