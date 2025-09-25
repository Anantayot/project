<?php
include "connectdb.php";

// เมื่อกดปุ่ม submit
if (isset($_POST['submit'])) {
    $p_name   = $_POST['p_name'];
    $p_price  = $_POST['p_price'];
    $p_stock  = $_POST['p_stock'];
    $p_detail = $_POST['p_detail'];
    $cat_id   = $_POST['cat_id'];

    // อัปโหลดรูป
    $p_img = NULL;
    if (!empty($_FILES['p_img']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES["p_img"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["p_img"]["tmp_name"], $targetFilePath)) {
            $p_img = $fileName;
        }
    }

    // SQL Insert
    $sql = "INSERT INTO product (p_name, p_price, p_stock, p_detail, p_img, cat_id) 
            VALUES ('$p_name', '$p_price', '$p_stock', '$p_detail', '$p_img', '$cat_id')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<div class='alert alert-success text-center'>เพิ่มสินค้าสำเร็จ!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>เกิดข้อผิดพลาด: " . mysqli_error($conn) . "</div>";
    }
}

// ดึง category
$cat_sql = "SELECT * FROM category";
$cat_result = mysqli_query($conn, $cat_sql);
?>

<!doctype html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เพิ่มข้อมูลรองเท้า</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-lg rounded-4">
    <div class="card-header bg-primary text-white text-center">
      <h4>ฟอร์มเพิ่มข้อมูลรองเท้า</h4>
    </div>
    <div class="card-body p-4">
      <form action="" method="post" enctype="multipart/form-data">
        
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
          <label class="form-label">หมวดหมู่</label>
          <select name="cat_id" class="form-select" required>
            <option value="">-- เลือกหมวดหมู่ --</option>
            <?php while ($row = mysqli_fetch_assoc($cat_result)) { ?>
              <option value="<?= $row['cat_id'] ?>"><?= $row['cat_name'] ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">รูปภาพ</label>
          <input type="file" name="p_img" class="form-control">
        </div>

        <div class="text-center">
          <button type="submit" name="submit" class="btn btn-success px-4">บันทึก</button>
          <button type="reset" class="btn btn-secondary px-4">ล้าง</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
