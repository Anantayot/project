<?php
include "connectdb.php";

// ลบสินค้า
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $img_q = mysqli_query($conn,"SELECT p_img FROM product WHERE p_id='$id'");
    $img_row = mysqli_fetch_assoc($img_q);
    if($img_row && $img_row['p_img']) @unlink("uploads/".$img_row['p_img']);
    mysqli_query($conn,"DELETE FROM product WHERE p_id='$id'");
    header("Location: product_list.php");
    exit;
}

// ดึงสินค้า
$prod_sql = "SELECT p.*, c.cat_name FROM product p LEFT JOIN category c ON p.cat_id=c.cat_id ORDER BY p.p_id DESC";
$prod_result = mysqli_query($conn,$prod_sql);
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>รายการสินค้า</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow-lg rounded-4">
    <div class="card-header bg-dark text-white text-center">
      <h4>รายการสินค้า</h4>
      <a href="product_form.php" class="btn btn-success btn-sm mt-2">เพิ่มสินค้าใหม่</a>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-striped">
        <thead class="table-light text-center">
          <tr>
            <th>รหัส</th><th>ชื่อสินค้า</th><th>ราคา</th><th>สต็อก</th><th>หมวดหมู่</th><th>รูปภาพ</th><th>จัดการ</th>
          </tr>
        </thead>
        <tbody>
        <?php while($prod=mysqli_fetch_assoc($prod_result)){ ?>
          <tr class="align-middle text-center">
            <td><?= $prod['p_id'] ?></td>
            <td><?= $prod['p_name'] ?></td>
            <td><?= number_format($prod['p_price'],2) ?></td>
            <td><?= $prod['p_stock'] ?></td>
            <td><?= $prod['cat_name'] ?></td>
            <td><?php if($prod['p_img']){ ?><img src="uploads/<?= $prod['p_img'] ?>" width="60"><?php } ?></td>
            <td>
              <a href="product_form.php?edit=<?= $prod['p_id'] ?>" class="btn btn-warning btn-sm">แก้ไข</a>
              <a href="?delete=<?= $prod['p_id'] ?>" onclick="return confirm('แน่ใจ?')" class="btn btn-danger btn-sm">ลบ</a>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
