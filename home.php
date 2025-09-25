<?php
include "connectdb.php";

// ---------------------------
// เพิ่มสินค้า
if (isset($_POST['submit_product'])) {
    $p_name   = $_POST['p_name'];
    $p_price  = $_POST['p_price'];
    $p_stock  = $_POST['p_stock'];
    $p_detail = $_POST['p_detail'];
    $cat_id   = $_POST['cat_id'];

    // อัปโหลดรูป
    $p_img = NULL;
    if (!empty($_FILES['p_img']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . "_" . basename($_FILES["p_img"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["p_img"]["tmp_name"], $targetFilePath)) {
            $p_img = $fileName;
        }
    }

    $sql = "INSERT INTO product (p_name, p_price, p_stock, p_detail, p_img, cat_id) 
            VALUES ('$p_name', '$p_price', '$p_stock', '$p_detail', '$p_img', '$cat_id')";
    $result = mysqli_query($conn, $sql);
    $msg_product = $result ? "เพิ่มสินค้าสำเร็จ!" : "Error: " . mysqli_error($conn);
}

// ---------------------------
// เพิ่มหมวดหมู่
if (isset($_POST['submit_category'])) {
    $cat_name = $_POST['cat_name'];
    $sql = "INSERT INTO category (cat_name) VALUES ('$cat_name')";
    $result = mysqli_query($conn, $sql);
    $msg_category = $result ? "เพิ่มหมวดหมู่สำเร็จ!" : "Error: " . mysqli_error($conn);
}

// ---------------------------
// ดึงข้อมูลสินค้าและหมวดหมู่
$products = mysqli_query($conn, "SELECT p.*, c.cat_name FROM product p LEFT JOIN category c ON p.cat_id=c.cat_id ORDER BY p.p_id DESC");
$categories = mysqli_query($conn, "SELECT * FROM category ORDER BY cat_id DESC");
?>

<!doctype html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Dashboard ร้านรองเท้า</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
  <h2 class="text-center mb-4">ระบบจัดการร้านรองเท้า</h2>

  <!-- ------------------- ข้อความแจ้งเตือน ------------------- -->
  <?php if(isset($msg_product)) echo "<div class='alert alert-success text-center'>$msg_product</div>"; ?>
  <?php if(isset($msg_category)) echo "<div class='alert alert-success text-center'>$msg_category</div>"; ?>

  <div class="row">
    <!-- ------------------- ฟอร์มเพิ่มสินค้า ------------------- -->
    <div class="col-md-6">
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white text-center">
          <h5>เพิ่มสินค้า</h5>
        </div>
        <div class="card-body">
          <form method="post" enctype="multipart/form-data">
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
                <label class="form-label">จำนวน</label>
                <input type="number" name="p_stock" class="form-control" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">รายละเอียด</label>
              <textarea name="p_detail" class="form-control" rows="2"></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">หมวดหมู่</label>
              <select name="cat_id" class="form-select" required>
                <option value="">-- เลือกหมว
