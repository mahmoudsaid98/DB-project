<?php
include 'db_connection.php';

// Fetch car details
function getCarDetails($car_id) {
    global $conn;

    $sql = "SELECT model, no_seats, transmission, year, status, type FROM cars WHERE car_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Get car_id from the request
if (isset($_GET['car_id'])) {
    $car_id = intval($_GET['car_id']);
    $car = getCarDetails($car_id);

    if (!$car) {
        die("Car not found.");
    }
} else {
    die("No car selected.");
}
?>
