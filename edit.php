<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "car_rental";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get car_id from URL
$car_id = $_GET['car_id'];

// Fetch current car details
$sql = "SELECT * FROM cars WHERE car_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the new status from the form
    $status = $_POST['status'];

    // Update the status in the database
    $update_sql = "UPDATE cars SET status = ? WHERE car_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $status, $car_id);

    if ($update_stmt->execute()) {
        // After successful update, redirect to the car list page
        header('Location: admin_edit.php');  // Redirect back to the car list
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car Status</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #212121;
            font-family: Arial, sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #333;
            border-radius: 10px;
            padding: 20px;
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        h1 {
            text-align: center;
            color: #fff;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-size: 16px;
        }

        select, button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
        }

        select {
            background-color: #444;
            color: #fff;
            cursor: pointer;
        }

        button {
            background-color: #0ff;
            color: #212121;
            font-weight: bold;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        button:hover {
            background-color: #33ccff;
        }

        a {
            text-decoration: none;
            color: #0ff;
            font-size: 16px;
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        a:hover {
            color: #33ccff;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Edit Status for Car ID: <?php echo $car['car_id']; ?></h1>
        <form action="edit.php?car_id=<?php echo $car['car_id']; ?>" method="POST">
            <label for="status">Select Status:</label>
            <select name="status" id="status">
                <option value="" disabled selected>Choose</option>
                <option value="active" <?php echo ($car['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="rented" <?php echo ($car['status'] == 'rented') ? 'selected' : ''; ?>>Rented</option>
                <option value="out of service" <?php echo ($car['status'] == 'out of service') ? 'selected' : ''; ?>>Out of Service</option>
            </select>
            <button type="submit">Update Status</button>
        </form>

        <p><a href="admin_edit.php">Back to Car List</a></p>
    </div>

</body>
</html>
