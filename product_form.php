<?php
include "connectdb.php";

// เช็คว่าเป็นแก้ไขหรือเพิ่มใหม่
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_q = mysqli_query($conn, "SELECT * FROM product WHERE p_id='$edit_id'");
    $edit_data = mysqli_fetch_assoc($edit_q);
}

// เพิ่มสินค้า
if (isset($_POST['submit'])) {
    $p_name   = $_POST['p_name'];
    $p_price  = $_POST['p_price'];
    $p_stock  = $_POST['p_stock'];
    $p_detail = $_POST['p_detail'];
    $cat_id   = $_POST['cat_id'];

    $p_img = NULL;
    if (!empty($_FILES['p_img']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . "_" . basename($_FILES["p_img"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["p_img"]["tmp_name"], $targetFilePath)) $p_img = $fileName;
    }

    $sql = "INSERT INTO product (p_name,p_price,p_stock,p_detail,p_img,cat_id)
            VALUES ('$p_name','$p_price','$p_stock','$p_detail','$p_img','$cat_id')";
    mysqli_query($conn, $sql);
    header("Location: product_list.php"); // กลับไปหน้ารายการ
    exit;
}

// แก้ไขสินค้า
if (isset($_POST['update'])) {
    $p_id     = $_POST['p_id'];
    $p_name   = $_POST['p_name'];
    $p_price  = $_POST['p_price'];
    $p_stock  = $_POST['p_stock'];
    $p_detail = $_POST['p_detail'];
    $cat_id   = $_POST['cat_id'];

    // อัปโหลดรูปใหม่ถ้ามี
    if (!empty($_FILES['p_img']['name'])) {
        $old_img_q = mysqli_query($conn, "SELECT p_img FROM product WHERE p_id='$p_id'");
        $old_img = mysqli_fetch_assoc($old_img_q);
        if ($old_img && $old_img['p_img']) @unlink("uploads/".$old_img['p_img']);
        $fileName = time() . "_" . basename($_FILES["p_img"]["name"]);
        move_uploaded_file($_FILES["p_img"]["tmp_name"], "uploads/".$fileName);
        $img_sql = ", p_img='$fileName'";
    } else {
        $img_sql = "";
    }

    $sql = "UPDATE product SET 
                p_name='$p_name',
                p_price='$p_price',
                p_stock='$p_stock',
                p_detail='$p_detail',
                cat_id='$cat_id'
                $img_sql
            WHERE p_id='$p_id'";
    mysqli_query($conn, $sql);
    header("Location: product_list.php");
    exit;
}

// ดึง category
$cat_sql = "SELECT * FROM category";
$cat_result = mysqli_query($conn, $cat_sql);
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title><?= $edit_data ? "แก้ไขสินค้า" : "เพิ่มสินค้า" ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow-lg rounded-4">
    <div class="card-header bg-primary text-white text-center">
      <h4><?= $edit_data ? "แก้ไขสินค้า" : "เพิ่มสินค้า" ?></h4>
    </div>
    <div class="card-body p-4">
      <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="p_id" value="<?= $edit_data['p_id'] ?? '' ?>">
        <div class="mb-3">
          <label class="form-label">ชื่อสินค้า</label>
          <input type="text" name="p_name" class="form-control" value="<?= $edit_data['p_name'] ?? '' ?>" required>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">ราคา</label>
            <input type="number" step="0.01" name="p_price" class="form-control" value="<?= $edit_data['p_price'] ?? '' ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">จำนวนในสต็อก</label>
            <input type="number" name="p_stock" class="form-control" value="<?= $edit_data['p_stock'] ?? '' ?>" required>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">รายละเอียดสินค้า</label>
          <textarea name="p_detail" class="form-control" rows="3"><?= $edit_data['p_detail'] ?? '' ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">หมวดหมู่</label>
          <select name="cat_id" class="form-select" required>
            <option value="">-- เลือกหมวดหมู่ --</option>
            <?php while($row = mysqli_fetch_assoc($cat_result)) {
              $sel = ($edit_data && $edit_data['cat_id']==$row['cat_id'])?"selected":"";
            ?>
            <option value="<?= $row['cat_id'] ?>" <?= $sel ?>><?= $row['cat_name'] ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">รูปภาพ</label>
          <?php if($edit_data && $edit_data['p_img']){ ?>
            <div class="mb-2"><img src="uploads/<?= $edit_data['p_img'] ?>" width="80"></div>
          <?php } ?>
          <input type="file" name="p_img" class="form-control">
        </div>
        <div class="text-center">
          <?php if($edit_data){ ?>
            <button type="submit" name="update" class="btn btn-warning px-4">อัปเดต</button>
            <a href="product_form.php" class="btn btn-secondary px-4">ยกเลิก</a>
          <?php } else { ?>
            <button type="submit" name="submit" class="btn btn-success px-4">บันทึก</button>
            <button type="reset" class="btn btn-secondary px-4">ล้าง</button>
          <?php } ?>
          <a href="list_category.php" class="btn btn-info px-4">ไปที่หมวดหมู่</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
