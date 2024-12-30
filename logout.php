<?php
session_start();

// تدمير الجلسة
session_unset();
session_destroy();

// إعادة التوجيه إلى صفحة تسجيل الدخول
header("Location: index.php");
exit;
?>
