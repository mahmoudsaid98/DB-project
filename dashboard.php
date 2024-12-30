<?php
session_start();

// التحقق من أن المستخدم قد سجل الدخول
if (!isset($_SESSION['user_id'])) {
    // إذا لم يكن المستخدم قد سجل الدخول، إعادة التوجيه إلى صفحة تسجيل الدخول
    header("Location: index.php");
    exit;
}

echo "Welcome to your dashboard!<br>";
echo "You are logged in as user with ID: " . $_SESSION['user_id'] . "<br>";
?>
<!-- رابط للتوجه إلى صفحة لوحة التحكم -->
<a href="dashboard.php">Go to Dashboard</a>
