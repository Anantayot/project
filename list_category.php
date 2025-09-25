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
    <div class="card-header bg-secondary text-white d-flex justify-content-between">
      <h4 class="mb-0">รายการหมวดหมู่</h4>
      <a href="add_category.php" class="btn btn-success btn-sm">+ เพิ่มหมวดหมู่</a>
    </div>
    <div class="card-body p-3">
      <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>ชื่อหมวดหมู่</th>
            <th>การจัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
              <td><?= $row['cat_id'] ?></td>
              <td><?= $row['cat_name'] ?></td>
              <td>
                <a href="delete_category.php?delete_id=<?= $row['cat_id'] ?>" 
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบหมวดหมู่นี้?');">
                   ลบ
                </a>
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
