<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (empty($data['Payment_method']) || empty($data['cash'])) {
    echo json_encode(["error" => "Invalid input data"]);
    exit;
}

// Extract data
$paymentMethod = $data['Payment_method'];
$cash = floatval($data['cash']);

// Perform database operations (if needed)

// Dummy response for now
$response = ["success" => true, "message" => "Payment processed successfully"];
echo json_encode($response);
?>
