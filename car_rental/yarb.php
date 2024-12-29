<?php
// Database connection
$servername = "localhost"; // Your server
$username = "root";        // Your username
$password = "";            // Your password
$dbname = "car_rental";    // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch active cars only
$sql = "SELECT car_id, company, model, year, fuel_type, no_seats, price_per_day FROM cars WHERE status='active'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental</title>
    <link rel="stylesheet" href="yarb.css"> <!-- Link to external CSS for styles -->
</head>
<body>
    <div class="container">
        <!-- Car Table Section -->
        <div class="table-section">
            <h1>Car Rental Table</h1>

            <!-- Search Bar -->
            <div class="search-container">
                <input type="text" id="searchBar" placeholder="Search...">
            </div>

            <!-- Filter Button -->
            <button id="filterButton">Apply Filters</button>

            <!-- Car Table -->
            <table id="carTable">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Fuel Type</th>
                        <th>No. Seats</th>
                        <th>Price per Day</th>
                        <th>Book</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["company"] . "</td>";
                            echo "<td>" . $row["model"] . "</td>";
                            echo "<td>" . $row["year"] . "</td>";
                            echo "<td>" . $row["fuel_type"] . "</td>";
                            echo "<td>" . $row["no_seats"] . "</td>";
                            echo "<td>" . $row["price_per_day"] . "</td>";
                            echo "<td><a href='index.html?company=" . urlencode($row["company"]) . 
                                "&model=" . urlencode($row["model"]) . 
                                "&year=" . $row["year"] . 
                                "&fuel_type=" . urlencode($row["fuel_type"]) . 
                                "&no_seats=" . $row["no_seats"] . 
                                "&price_per_day=" . $row["price_per_day"] . 
                                "&car_id=" . urlencode($row["car_id"]) . "'>
                                <button class='book-btn'>Book</button></a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No cars available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="yarb.js"></script> <!-- Link to external JS -->
</body>
</html>

<?php
$conn->close();
?>
