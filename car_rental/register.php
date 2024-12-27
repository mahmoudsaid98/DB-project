<?php
// Start the session
session_start();

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Receive form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $birth_date = $_POST['birthday'];

    // Database connection details
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "car_rental";

    // Create connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user already exists
    $sql = "SELECT * FROM customers WHERE cust_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // Bind email to the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Email already exists.";
    } else {
        // If no user with the same email, add the new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password

        $sql = "INSERT INTO customers (fname, lname, cust_email, cust_password, gender, birth_date) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $hashed_password, $gender, $birth_date);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Registration successful. You can log in now.";
            header("Location: index.php"); // Redirect to the login page
            exit;
        } else {
            $_SESSION['error_message'] = "Something went wrong. Please try again.";
        }
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
    <title>Register</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="main">
        <h1>Register</h1><br>

        <?php 
        if (isset($_SESSION['error_message'])) {
            echo "<p style='color: red;'>".$_SESSION['error_message']."</p>";
            unset($_SESSION['error_message']);
        }
        if (isset($_SESSION['success_message'])) {
            echo "<p style='color: green;'>".$_SESSION['success_message']."</p>";
            unset($_SESSION['success_message']);
        }
        ?>

        <form action="register.php" method="POST">
            <label for="first_name">First Name:</label><br>
            <input type="text" id="first_name" name="first_name" required><br><br>

            <label for="last_name">Last Name:</label><br>
            <input type="text" id="last_name" name="last_name" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}" title="Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, and one number." required><br><br>

            <label for="gender">Gender:</label><br>
            <select id="gender" name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select><br><br>

            <label for="birthday">Birthday:</label><br>
              <input type="date" id="birthday" name="birthday" required><br><br>

            <input type="submit" name="submit" id="submit" value="Register"><br>
        </form>

        <h3>or</h3>
        <a href="index.php">Log in</a>

    </div>
</body>
</html>
