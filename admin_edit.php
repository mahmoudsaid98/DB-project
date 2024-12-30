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

ob_start(); // Start output buffering AFTER the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Table</title>
    <link rel="stylesheet" href="admin_edit.css">
</head>
<body>

    <div class="table-container">
      
        <h1>Car Rental Information</h1>
        <input type="text" id="search-bar" placeholder="Search by Car ID...">
        <table class="car-table" id="carTable">
            <thead>
                <tr>
                    <th>Car ID</th>
                    <th>Company</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Transmission</th>
                    <th>Fuel Type</th>
                    <th>No. of Seats</th>
                    <th>Price per Day</th>
                    <th>Status</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT car_id, company, model, year, transmission, fuel_type, no_seats, price_per_day, status FROM cars";
                $result = $conn->query($sql); // Now $conn is defined

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["car_id"] . "</td>
                                <td>" . $row["company"] . "</td>
                                <td>" . $row["model"] . "</td>
                                <td>" . $row["year"] . "</td>
                                <td>" . $row["transmission"] . "</td>
                                <td>" . $row["fuel_type"] . "</td>
                                <td>" . $row["no_seats"] . "</td>
                                <td>" . $row["price_per_day"] . "</td>
                                <td>" . $row["status"] . "</td>
                                <td><a href='edit.php?car_id=" . $row["car_id"] . "'>Edit Status</a></td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No cars found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="admin_edit.js"></script>

</body>
</html>

<?php
$html = ob_get_clean();
$conn->close(); // Close the connection
echo $html;
?>
