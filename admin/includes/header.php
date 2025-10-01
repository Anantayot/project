<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | ระบบร้านค้าออนไลน์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* CSS เพิ่มเติมเพื่อให้สวยงาม */
        body {
            background-color: #f8f9fa; /* สีพื้นหลังอ่อนๆ */
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 56px; /* เว้นพื้นที่สำหรับ Navbar ด้านบน */
            background-color: #343a40; /* Dark Sidebar */
            color: white;
        }
        .main-content {
            margin-left: 250px;
            padding-top: 20px;
        }
    </style>
</head>
<body>

<?php 
    // ตรวจสอบว่าผู้ใช้ล็อกอินแล้ว (ในระบบจริงต้องมี Session Check)
    // หากยังไม่ล็อกอิน ควร Redirect ไปที่ index.php
    // if (!isset($_SESSION['admin_logged_in'])) { header('Location: index.php'); exit(); }

    // ตัวแปรสำหรับระบุหน้าปัจจุบันเพื่อเน้นใน Sidebar
    $currentPage = basename($_SERVER['PHP_SELF']); 
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">🛍️ Admin Dashboard</a>
        <span class="navbar-text ms-auto text-light">
            ยินดีต้อนรับ, แอดมิน
            <a href="index.php?logout=true" class="btn btn-sm btn-outline-light ms-2">
                <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
            </a>
        </span>
    </div>
</nav>

<div class="sidebar d-flex flex-column p-3">
    <ul class="nav nav-pills flex-column mb-auto mt-3">
        <li class="nav-item mb-2">
            <a href="dashboard.php" class="nav-link <?= ($currentPage == 'dashboard.php') ? 'active' : 'text-white' ?>" aria-current="page">
                <i class="bi bi-speedometer2"></i> แดชบอร์ด
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="manage_products.php" class="nav-link <?= ($currentPage == 'manage_products.php') ? 'active' : 'text-white' ?>">
                <i class="bi bi-box-seam"></i> จัดการสินค้า
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="manage_categories.php" class="nav-link <?= ($currentPage == 'manage_categories.php') ? 'active' : 'text-white' ?>">
                <i class="bi bi-tags"></i> จัดการประเภทสินค้า
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="manage_orders.php" class="nav-link <?= ($currentPage == 'manage_orders.php') ? 'active' : 'text-white' ?>">
                <i class="bi bi-bag-check"></i> จัดการออเดอร์
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="manage_users.php" class="nav-link <?= ($currentPage == 'manage_users.php') ? 'active' : 'text-white' ?>">
                <i class="bi bi-people"></i> จัดการลูกค้า
            </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <span class="text-white">Admin Version 1.0</span>
    </div>
</div>

<div class="main-content">
    <div class="container-fluid">