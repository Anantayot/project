<?php
$host = 'localhost';        // หรือ IP ของ Server ฐานข้อมูล
$dbname = 'ihavejib';   // <<-- แก้เป็นชื่อฐานข้อมูลของคุณ
$username = 'root';  // <<-- แก้เป็นชื่อผู้ใช้ฐานข้อมูล
$password = 'note071245';  // <<-- แก้เป็นรหัสผ่าน
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // บรรทัดนี้คือหัวใจสำคัญ ที่จะสร้างตัวแปร $pdo
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    // หากเชื่อมต่อไม่ได้ จะแสดงข้อความ Error แล้วหยุดการทำงานทันที
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>