<?php
include "connectdb.php";

if (isset($_POST['submit'])) {
    $cat_name = $_POST['cat_name'];

    $sql = "INSERT INTO category (cat_name) VALUES ('$cat_name')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: list_category.php?msg=เพิ่มหมวดหมู่สำเร็จ");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!doctype html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เพิ่มหมวดหมู่สินค้า</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-lg rounded-4">
    <div class="card-header bg-info text-white text-center">
      <h4>ฟอร์มเพิ่มหมวดหมู่สินค้า</h4>
    </div>
    <div class="card-body p-4">
      <form action="" method="post">
        <div class="mb-3">
          <label class="form-label">ชื่อหมวดหมู่</label>
          <input type="text" name="cat_name" class="form-control" required>
        </div>
        <div class="text-center">
          <button type="submit" name="submit" class="btn btn-success px-4">บันทึก</button>
          <a href="list_category.php" class="btn btn-secondary px-4">กลับไปหน้ารายการ</a>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
