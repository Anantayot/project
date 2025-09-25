<?php
include "connectdb.php";

// ดึงหมวดหมู่
$cat_result = mysqli_query($conn, "SELECT * FROM category");
?>

<!doctype html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เพิ่มสินค้า</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-lg rounded-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">ฟอร์มเพิ่มสินค้า</h4>
      <a href="add_category.php" class="btn btn-warning btn-sm">+ เพิ่มหมวดหมู่</a>
    </div>
    <div class="card-body p-4">
      <form action="save_product.php" method="post" enctype="multipart/form-data">
        
        <div class="mb-3">
          <label class="form-label">ชื่อสินค้า</label>
          <input type="text" name="p_name" class="form-control" required>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">ราคา</label>
            <input type="number" step="0.01" name="p_price" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">จำนวนในสต็อก</label>
            <input type="number" name="p_stock" class="form-control" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">รายละเอียดสินค้า</label>
          <textarea name="p_detail" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">ห
