<?php
// ตัวอย่างไฟล์เชื่อมต่อฐานข้อมูล (ถ้าผู้ใช้มี connectdb.php อยู่แล้ว ให้ใช้ไฟล์ของผู้ใช้เอง)
// แก้ค่าตามเครื่องของคุณ
$host = "localhost";
$db   = "mycommiss";
$user = "root";
$pass = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
