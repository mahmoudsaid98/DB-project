<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "car_rental";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Query to fetch the distinct payment methods
$query = "SELECT DISTINCT PaymentMethod FROM payments";
$result = $conn->query($query);

// Handle query errors
if (!$result) {
    die(json_encode(["error" => "Query failed: " . $conn->error]));
}

// Prepare the JSON response
if ($result->num_rows > 0) {
    $methods = array();
    while ($row = $result->fetch_assoc()) {
        $methods[] = ["PaymentMethod" => $row['PaymentMethod']];
    }
    echo json_encode($methods); // Return JSON array of payment methods
} else {
    echo json_encode([]); // Return an empty JSON array if no results
}

// Close the connection
$conn->close();
?>
