<?php
// بدء الجلسة
session_start();

// التحقق مما إذا كانت البيانات قد أُرسلت عبر POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // استلام البيانات من النموذج
    $email = $_POST['email'];
    $password = $_POST['password'];

    // الاتصال بقاعدة البيانات
    $servername = "localhost"; // اسم السيرفر
    $dbusername = "root";      // اسم المستخدم لقاعدة البيانات
    $dbpassword = "";          // كلمة المرور لقاعدة البيانات
    $dbname = "car_rental";    // اسم قاعدة البيانات

    // إنشاء الاتصال
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // التحقق من الاتصال
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // الاستعلام للتحقق من وجود البريد الإلكتروني وكلمة المرور في جدول "customers"
    $sql = "SELECT * FROM customers WHERE cust_email = ?";

    // تحضير الاستعلام
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // ربط البريد الإلكتروني مع الاستعلام
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // إذا تم العثور على المستخدم، نتحقق من كلمة المرور
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['cust_password'])) {
            // تسجيل الدخول ناجح
            $_SESSION['user_id'] = $row['customer_id'];  // تخزين معرف العميل في الجلسة
            $_SESSION['success_message'] = "Login successful!";
            header("Location: show.html");  // إعادة التوجيه إلى لوحة التحكم
            exit;
        } else {
            $_SESSION['error_message'] = "Incorrect password.";
        }
    } else {
        $_SESSION['error_message'] = "No user found with that email.";
    }

    // إغلاق الاتصال بقاعدة البيانات
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

        <!-- نموذج تسجيل الدخول -->
        <form action="index.php" method="POST">
            <label for="email">Email:</label><br>
            <input type="email" name="email" id="email" placeholder="Enter your email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" placeholder="Enter your password" required><br><br>

            <input type="submit" name="submit" id="submit" value="Log in"><br>
        </form>

        <!-- عرض رسالة النجاح أو الخطأ -->
        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<p style='color: red;'>".$_SESSION['error_message']."</p>";
            unset($_SESSION['error_message']);  // إزالة الرسالة بعد عرضها
        }
        if (isset($_SESSION['success_message'])) {
            echo "<p style='color: green;'>".$_SESSION['success_message']."</p>";
            unset($_SESSION['success_message']);  // إزالة الرسالة بعد عرضها
        }
        ?>

        <h3>or</h3><br>

        <!-- روابط إضافية -->
        <a id="register" href="register.php">Register</a><br><br>
        <a id="login-admin" href="adminlogin.php">Login as Admin</a>
    </div>
</body>
</html>
