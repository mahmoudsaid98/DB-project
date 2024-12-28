<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_rental";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Fetch car details using prepared statements
$car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : 1;

$stmt = $conn->prepare("SELECT c.company, c.model, c.year, c.transmission, c.fuel_type, c.no_seats, c.plate_id, o.location 
                        FROM cars c
                        INNER JOIN office o ON c.office_id = o.office_id
                        WHERE c.car_id = ?");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $car = $result->fetch_assoc();
    echo json_encode($car);
} else {
    echo json_encode(['error' => 'Car not found']);
}

$stmt->close();
$conn->close();
?>
