<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "hotel_test"; // تم التعديل هنا إلى اسم قاعدة بياناتكِ الصحيح

// الاتصال بقاعدة البيانات
$conn = new mysqli($host, $user, $pass, $db_name);

// التأكد من أن الاتصال ناجح
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>