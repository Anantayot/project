<?php
session_start();
// ตรวจสอบว่ามีการล็อกอินหรือยัง ถ้ายังให้กลับไปหน้า index.php
if (!isset($_SESSION['admin_login'])) {
    header('Location: index.php');
    exit;
}
include '../connectdb.php'; // เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการหลังร้าน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { display: flex; }
        .sidebar { width: 280px; min-height: 100vh; }
        .content { flex-grow: 1; padding: 20px; }
    </style>
</head>
<body>
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar">
        <?php include 'sidebar.php'; ?>
    </div>
    <div class="content">