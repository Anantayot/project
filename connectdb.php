<?php
// ตั้งค่าเพื่อแสดง Error หากมีปัญหา
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pwd = "note071245"; // ตรวจสอบรหัสผ่านนี้ว่าถูกต้อง
$dbname = "snekker_shop";
$conn = mysqli_connect($host, $user, $pwd, $dbname);

// *** ส่วนตรวจสอบสถานะการเชื่อมต่อ ***
if (!$conn) {
    // ถ้าเชื่อมต่อล้มเหลว
    die("❌ การเชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());
}

// ถ้าเชื่อมต่อสำเร็จ
echo "✅ **เชื่อมต่อฐานข้อมูล** `{$dbname}` **สำเร็จ!**";
echo "<br>ตั้งค่า Character Set เป็น UTF-8 เรียบร้อยแล้ว";

mysqli_query($conn, "SET NAMES utf8");

// **คำเตือน:** เมื่อใช้งานจริง (Production) ให้ลบโค้ด echo/die ออก หรือคอมเมนต์ไว้
// เพื่อไม่ให้แสดงข้อมูลที่ละเอียดอ่อนแก่ผู้ใช้ภายนอก

?>