<?php
include "connectdb.php";

// ลบสินค้า
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $img_q = mysqli_query($conn, "SELECT p_img FROM product WHERE p_id='$delete_id'");
    $img_row = mysqli_fetch_assoc($img_q);
    if ($img_row && !empty($img_row['p_img'])) {
        @unlink("uploads/" . $img_row['p_img']);
    }
    mysqli_query($conn, "DELETE FROM product WHERE p_id='$delete_id'");
    header("Location: list_product.php?deleted=1");
    exit();
}

// ดึงสินค้า
$product_result = mysqli_query($conn, 
    "SELECT p.*, c.cat_name 
     FROM product p 
     LEFT JOIN category c ON p.cat_id=c.cat_id
     ORDER BY p.p_id DESC"
);
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
  <?php if (isset($_GET['success'])) { ?>
    <div class="alert alert-success text-center">เพิ่มสินค้าสำเร็จ!</div>
  <?php } elseif (isset($_GET['deleted'])) { ?>
    <div class="alert alert-warning text-center">ลบสินค้าสำเร็จ!</div>
  <?php } ?>

  <div class="card shadow-lg rounded-4">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">รายการสินค้า</h4>
      <a href="add_product.php" class="btn btn-success btn-sm">+ เพิ่มสินค้า</a>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-light text-center">
          <tr>
            <th>รหัส</th>
            <th>รูป</th>
            <th>ชื่อสินค้า</th>
            <th>ราคา</th>
            <th>สต็อก</th>
            <th>หมวดหมู่</th>
            <th>จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($product_result)) { ?>
          <tr>
            <td class="text-center"><?= $row['p_id'] ?></td>
            <td class="text-center">
              <?php if (!empty($row['p_img'])) { ?>
                <img src="uploads/<?= $row['p_img'] ?>" width="60" class="rounded">
              <?php } else { echo "-"; } ?>
            </td>
            <td><?= $row['p_name'] ?></td>
            <td class="text-end"><?= number_format($row['p_price'], 2) ?> บาท</td>
            <td class="text-center"><?= $row['p_stock'] ?></td>
            <td><?= $row['cat_name'] ?></td>
            <td class="text-center">
              <a href="?delete=<?= $row['p_id'] ?>" class="btn btn-danger btn-sm"
                 onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสินค้า?')">ลบ</a>
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
