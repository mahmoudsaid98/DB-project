<?php
// بدء الجلسة
session_start();

// التحقق مما إذا كانت البيانات قد أُرسلت عبر POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // استلام البيانات من النموذج
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $birth_date = $_POST['birth_date'];
    $cust_email = $_POST['cust_email'];
    $cust_password = $_POST['cust_password'];
    $phone = $_POST['phone'];
    $address = isset($_POST['address']) ? $_POST['address'] : null;

    // التحقق من الحقول المطلوبة
    if (empty($fname) || empty($lname) || empty($gender) || empty($birth_date) || empty($cust_email) || empty($cust_password) || empty($phone)) {
        $_SESSION['error_message'] = "All required fields must be filled!";
        header("Location: register.php");
        exit;
    }

    // التحقق من طول كلمة المرور
    if (strlen($cust_password) < 8) {
        $_SESSION['error_message'] = "Password must be at least 8 characters long!";
        header("Location: register.php");
        exit;
    }

    // التحقق من أن كلمة المرور تحتوي على حروف كبيرة، حروف صغيرة، أرقام، ورموز خاصة
    if (!preg_match("/[A-Z]/", $cust_password) || !preg_match("/[a-z]/", $cust_password) || !preg_match("/[0-9]/", $cust_password) ) {
        $_SESSION['error_message'] = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character!";
        header("Location: register.php");
        exit;
    }

    // تشفير كلمة المرور
    $hashed_password = password_hash($cust_password, PASSWORD_BCRYPT);

    // الاتصال بقاعدة البيانات
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "car_rental";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // التحقق من الاتصال
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // إدخال البيانات في الجدول
    $sql = "INSERT INTO customers (fname, lname, gender, birth_date, cust_email, cust_password, phone, address) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $fname, $lname, $gender, $birth_date, $cust_email, $hashed_password, $phone, $address);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Registration successful!";
        header("Location: index.php"); // إعادة التوجيه إلى صفحة تسجيل الدخول
        exit;
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
        header("Location: register.php");
        exit;
    }

    // إغلاق الاتصال
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
        
        <h1>Register</h1>
        <form action="register.php" method="POST">
            <label for="fname">First Name:</label><br>
            <input type="text" name="fname" id="fname" placeholder="First name" required>

            <label for="lname">Last Name:</label><br>
            <input type="text" name="lname" id="lname" placeholder="Last name" required>

            <label for="cust_email">Email:</label>
            <input type="email" name="cust_email" id="cust_email" placeholder="Email" required>

            <label for="cust_password">Password:</label>
            <input type="password" name="cust_password" id="cust_password" placeholder="Password" required>

            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
            <option value=""disabled selected>Select your Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>

            <label for="birth_date">Birth Date:</label>
            <input type="date" name="birth_date" id="birth_date" required>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" placeholder="Phone number" required pattern="\d+" title="Please enter numbers only.">

            <label for="address">Address (optional):</label>
            <input type="text" name="address" id="address" placeholder="Address">

            <input type="submit" value="Register">
        </form>
        <A6>or</A6><br>
        <a href="index.php"> login</a>

        <?php
        // عرض رسائل الخطأ أو النجاح
        if (isset($_SESSION['error_message'])) {
            echo "<p style='color: red;'>".htmlspecialchars($_SESSION['error_message'])."</p>";
            unset($_SESSION['error_message']);
        }
        if (isset($_SESSION['success_message'])) {
            echo "<p style='color: green;'>".htmlspecialchars($_SESSION['success_message'])."</p>";
            unset($_SESSION['success_message']);
        }
        ?>
    </div>
</body>
</html>


