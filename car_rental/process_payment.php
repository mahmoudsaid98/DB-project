<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_rental";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read input
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Validate input
if (empty($data['car_id']) || empty($data['pickup_date']) || empty($data['return_date']) || empty($data['total_payment']) || empty($data['payment_method'])) {
    echo json_encode(["error" => "Invalid input data"]);
    exit;
}

// Insert reservation
$stmt = $conn->prepare("INSERT INTO reservations (customer_id, car_id, pickup_date, return_date, total_payment) VALUES (1, ?, ?, ?, ?)");
$stmt->bind_param("issd", $data['car_id'], $data['pickup_date'], $data['return_date'], $data['total_payment']);
$stmt->execute();
$reservationId = $stmt->insert_id;

// Insert payment
$stmt = $conn->prepare("INSERT INTO payments (reservation_id, cash, PaymentMethod, PaymentStatus) VALUES (?, ?, ?, 'Completed')");
$stmt->bind_param("ids", $reservationId, $data['total_payment'], $data['payment_method']);
$stmt->execute();

echo json_encode(["success" => true]);
$conn->close();
?>
