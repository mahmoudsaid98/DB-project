<?php
// Database connection details
$servername = "localhost"; // Hostname
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP
$dbname = "car_rental"; // Name of the database

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $company = $_POST['company']; // The manufacturing company
    $model = $_POST['model']; // Car model
    $year = $_POST['year']; // Manufacturing year
    $transmission = $_POST['transmission']; // Transmission type (Manual/Automatic)
    $no_seats = $_POST['no_seats']; // Number of seats
    $plate_id = $_POST['plate_id']; // Unique plate ID
    $fuel_type = $_POST['fuel_type']; // Fuel type (Gasoline/Diesel/Electric/Hybrid)
    $price_per_day = $_POST['price_per_day']; // Rental price per day
    $status = $_POST['status']; // Car status (active, out of service, rented)
    $office_id = $_POST['office_id']; // Office ID where the car is located

    // SQL query to insert data into the 'cars' table
    $sql = "INSERT INTO cars (company, model, year, transmission, fuel_type, no_seats, plate_id, status, price_per_day, office_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters to the SQL query
    $stmt->bind_param(
        "ssissssssd", // Data types: s = string, i = integer, d = double
        $company,
        $model,
        $year,
        $transmission,
        $fuel_type,
        $no_seats,
        $plate_id,
        $status,
        $price_per_day,
        $office_id
    );

    // Execute the SQL query and check if successful
    if ($stmt->execute()) {
        echo "New car added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a new car</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add a new car</h1>
        <form action="add_car.php" method="POST">
            <input type="text" id="make" name="make" placeholder="The manufacturing company" required><br>
            <input type="text" id="model" name="model" placeholder="Model" required><br>
            <input type="number" id="year" name="year" min="1980" max="2024" placeholder="Year" required><br>
            
            <label for="transmission">Transmission:</label><br>
            <select id="transmission" name="transmission" required>
                <option value="Manual">Manual</option>
                <option value="Automatic">Automatic</option>
            </select><br>
            
            <label for="no_seats">Seats:</label><br>
            <select id="no_seats" name="no_seats" required>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="7">7</option>
            </select><br>
            
            <input type="text" id="plate_id" name="plate_id" placeholder="Plate number" pattern="[A-Za-z0-9]+" minlength="7" maxlength="7" required title="Enter exactly 7 letters and numbers"><br>
            
            <label for="fuel_type">Fuel type:</label><br>
            <select id="fuel_type" name="fuel_type" required>
                <option value="Gasoline">Gasoline</option>
                <option value="Diesel">Diesel</option>
                <option value="Electric">Electric</option>
                <option value="Hybrid">Hybrid</option>
            </select><br>

            <input type="number" id="price_per_day" name="price_per_day" placeholder="Price per day" step="0.01" required><br>
            
            <label for="status">Status:</label><br>
            <select id="status" name="status" required>
                <option value="active">Active</option>
                <option value="out of service">Out of Service</option>
                <option value="rented">Rented</option>
            </select><br>
            
            <input type="number" id="office_id" name="office_id" placeholder="Office ID" required><br>
            <input type="submit" value="Add car">
        </form>
    </div>
</body>
</html>
