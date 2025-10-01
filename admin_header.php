<?php
if(session_status()===PHP_SESSION_NONE) session_start();
require 'connectdb.php';
if(!isset($_SESSION['admin'])){ header('Location: admin_login.php'); exit; }
// fetch admin info optional
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<title>Admin - Snekker Shop</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
<div class="container-fluid">
<a class="navbar-brand" href="dashboard.php">Snekker Admin</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="nav">
<ul class="navbar-nav me-auto">
<li class="nav-item"><a class="nav-link" href="products.php">สินค้า</a></li>
<li class="nav-item"><a class="nav-link" href="categories.php">ประเภท</a></li>
<li class="nav-item"><a class="nav-link" href="customers.php">ลูกค้า</a></li>
<li class="nav-item"><a class="nav-link" href="orders.php">ออเดอร์</a></li>
</ul>
<ul class="navbar-nav">
<li class="nav-item"><a class="nav-link" href="admin_logout.php">ออกจากระบบ</a></li>
</ul>
</div>
</div>
</nav>
<div class="container my-4">
<a class="btn btn-secondary mb-3" href="dashboard.php">กลับ</a>