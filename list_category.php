<?php
include "connectdb.php";

$sql = "SELECT * FROM category ORDER BY cat_id DESC";
$result = mysqli_query($conn, $sql);
?>

<!doctype html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายการหมวดหมู่สินค้า</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

  <?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success text-center"><?= $_GET['msg'] ?></div>
  <?php endif; ?>

  <div class="card shadow-lg rounded-4">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">รายการหมวดหมู่</h4>
      <div>
        <a href="list_product.php" class="btn btn-primary btn-sm me-2">📦 รายการสินค้า</a>
        <a href="add_category.php" class="btn btn-success btn-sm">+ เพิ่มหมวดหมู่</a>
      </div>
    </div>
    <div class="card-body p-3">
      <table class="table table-bordered table-striped text-center align-middle">
        <thead cl
