<?php
// بدء الجلسة
session_start();

// التحقق من أنه تم إرسال النموذج عبر POST
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

    // التحقق من وجود المدير في قاعدة البيانات
    $sql = "SELECT * FROM admin WHERE admin_email = ?"; // استخدام البريد الإلكتروني للتحقق
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // ربط البريد الإلكتروني بالاستعلام
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // إذا تم العثور على المدير
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['admin_password'])) {
            // إذا كانت كلمة المرور صحيحة
            $_SESSION['admin_id'] = $admin['admin_id'];  // تخزين معرف المدير في الجلسة
            $_SESSION['success_message'] = "Login successful as Admin.";
            header("Location: add_car.php");  // إعادة التوجيه إلى صفحة إضافة السيارات
            exit;
        } else {
            $_SESSION['error_message'] = "Invalid email or password.";
        }
    } else {
        $_SESSION['error_message'] = "No admin found with that email.";
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
    <title>Admin Login</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="main">
        <h1>Admin Login</h1>
        <form action="Adminchoise.html" method="POST">
            <label for="email">Email:</label><br>
            <input type="email" name="email" id="email" placeholder="Enter your email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" placeholder="Enter your password" required><br><br>

            <input type="submit" name="submit" id="submit" value="Log in as Admin"><br><br>
        </form>

        <!-- عرض رسالة الخطأ أو النجاح بعد محاولة تسجيل الدخول -->
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
    </div>
</body>
</html>