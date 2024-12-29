<?php
// Start the session
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Database connection details
    $servername = "localhost"; // Server name
    $dbusername = "root";      // Database username
    $dbpassword = "";          // Database password
    $dbname = "car_rental";    // Database name

    // Create a connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to check if the email exists in the "customers" table
    $sql = "SELECT * FROM customers WHERE cust_email = ?";

    // Prepare the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // Bind the email to the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If a user is found, verify the password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['cust_password'])) {
            // Login successful
            $_SESSION['user_id'] = $row['customer_id'];  // Store customer ID in the session
            $_SESSION['success_message'] = "Login successful!";
            header("Location: dashboard.php");  // Redirect to the dashboard
            exit;
        } else {
            $_SESSION['error_message'] = "Incorrect password.";
        }
    } else {
        $_SESSION['error_message'] = "No user found with that email.";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="main">
        <h1>Log in</h1>
        <i>Welcome</i><br><br>

        <!-- Login form -->
        <form action="index.php" method="POST">
            <label for="email">Email:</label><br>
            <input type="email" name="email" id="email" placeholder="Enter your email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" placeholder="Enter your password" required><br><br>

            <input type="submit" name="submit" id="submit" value="Log in"><br>
        </form>

        <!-- Display success or error messages -->
        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<p style='color: red;'>".$_SESSION['error_message']."</p>";
            unset($_SESSION['error_message']);  // Clear the message after displaying it
        }
        if (isset($_SESSION['success_message'])) {
            echo "<p style='color: green;'>".$_SESSION['success_message']."</p>";
            unset($_SESSION['success_message']);  // Clear the message after displaying it
        }
        ?>

        <h3>or</h3><br>

        <!-- Additional links -->
        <a id="register" href="register.php">Register</a><br><br>
        <a id="login-admin" href="adminlogin.php">Login as Admin</a>
    </div>
</body>
</html>
