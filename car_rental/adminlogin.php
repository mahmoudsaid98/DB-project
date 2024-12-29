<?php
// Start the session to store session variables
session_start();

// Check if the form has been submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Retrieve data submitted from the form
    $email = $_POST['email']; // Admin email entered in the form
    $password = $_POST['password']; // Admin password entered in the form

    // Database connection details
    $servername = "localhost"; // Database server hostname
    $dbusername = "root";      // Database username for XAMPP
    $dbpassword = "";          // Database password for XAMPP
    $dbname = "car_rental";    // Name of the database

    // Create a connection to the database
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // Exit if connection fails
    }

    // Query to check if the admin exists in the database
    $sql = "SELECT * FROM admin WHERE admin_email = ?"; // Use email to find the admin
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // Bind email to the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the admin is found in the database
        $admin = $result->fetch_assoc();
        // Verify the password entered with the hashed password in the database
        if (password_verify($password, $admin['admin_password'])) {
            // If the password is correct
            $_SESSION['admin_id'] = $admin['admin_id'];  // Store the admin ID in the session
            $_SESSION['success_message'] = "Login successful as Admin."; // Success message
            header("Location: add_car.php");  // Redirect to the add car page
            exit;
        } else {
            $_SESSION['error_message'] = "Invalid email or password."; // Error message if password is incorrect
        }
    } else {
        $_SESSION['error_message'] = "No admin found with that email."; // Error message if no admin found
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="main.css"> <!-- Link to external CSS file -->
</head>
<body>
    <div class="main">
        <h1>Admin Login</h1>
        <form action="adminlogin.php" method="POST">
            <label for="email">Email:</label><br>
            <input type="email" name="email" id="email" placeholder="Enter your email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" placeholder="Enter your password" required><br><br>

            <input type="submit" name="submit" id="submit" value="Log in as Admin"><br><br>
        </form>

        <!-- Display error or success message after login attempt -->
        <?php
        // Check if an error message is set in the session
        if (isset($_SESSION['error_message'])) {
            echo "<p style='color: red;'>".$_SESSION['error_message']."</p>"; // Display error message
            unset($_SESSION['error_message']); // Remove the error message after displaying it
        }

        // Check if a success message is set in the session
        if (isset($_SESSION['success_message'])) {
            echo "<p style='color: green;'>".$_SESSION['success_message']."</p>"; // Display success message
            unset($_SESSION['success_message']); // Remove the success message after displaying it
        }
        ?>
    </div>
</body>
</html>
