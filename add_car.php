<?php
// اتصال بقاعدة البيانات
$servername = "localhost"; // اسم المضيف
$username = "root"; // اسم المستخدم الافتراضي لـ XAMPP
$password = ""; // كلمة المرور الافتراضية لـ XAMPP
$dbname = "car_rental"; // اسم قاعدة البيانات

// إنشاء اتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التحقق مما إذا كان الطلب من النوع POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // جلب البيانات من النموذج
    $company = $_POST['company'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $transmission = $_POST['transmission'];
    $no_seats = $_POST['no_seats'];
    $plate_id = $_POST['plate_id'];
    $fuel_type = $_POST['fuel_type'];
    $price_per_day = $_POST['price_per_day'];
    $office_id = $_POST['office_id'];

    // استعلام الإدخال
    $sql = "INSERT INTO cars (company, model, year, transmission, fuel_type, no_seats, plate_id, price_per_day, office_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // إعداد الاستعلام
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssisssssd",
        $company,
        $model,
        $year,
        $transmission,
        $fuel_type,
        $no_seats,
        $plate_id,
        $price_per_day,
        $office_id
    );

    // تنفيذ الاستعلام والتحقق
    if ($stmt->execute()) {
        echo "New car added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // إغلاق البيان والاتصال
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a new car</title>
    <link rel="stylesheet" href="addcar.css">
</head>
<body>
    <div class="container">
        <h1>Add a new car</h1>
        <form action="add_car.php" method="POST">
            <input type="text" id="company" name="company" placeholder="The manufacturing company" required><br>
            <input type="text" id="model" name="model" placeholder="Model" required><br>
            <input type="number" id="year" name="year" min="1980" max="2024" placeholder="Year" required><br>
            
            <label for="transmission">Transmission:</label><br>
            <select id="transmission" name="transmission" required>
                <option value="Manual">Manual</option>
                <option value="Automatic">Automatic</option>
            </select><br>
            
            <label for="no_seats">Seats:</label><br>
            <select id="no_seats" name="no_seats" required>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="7">7</option>
            </select><br>
            
            <input type="text" id="plate_id" name="plate_id" placeholder="Plate number" pattern="[A-Za-z0-9]+" minlength="7" maxlength="7" required title="Enter exactly 7 letters and numbers"><br>
            
            <label for="fuel_type">Fuel type:</label><br>
            <select id="fuel_type" name="fuel_type" required>
                <option value="Diesel">Diesel</option>
                <option value="Electric">Electric</option>
                <option value="Hybrid">Hybrid</option>
            </select><br>

            <!-- حقل السعر بدون جافا سكربت -->
            <input type="number" id="price_per_day" name="price_per_day" placeholder="Price per day" step="1" min="0" required><br>
            
            
            
            <input type="number" id="office_id" name="office_id" placeholder="Office ID" required><br>
            <input type="submit" value="Add car">
        </form>
    </div>
</body>
</html>