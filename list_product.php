<?php
include "connectdb.php";

// -------------------- เพิ่มสินค้า --------------------
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

// -------------------- แก้ไขสินค้า --------------------
if (isset($_POST['update'])) {
    $p_id     = $_POST['p_id'];
    $p_name   = $_POST['p_name'];
    $p_price  = $_POST['p_price'];
    $p_stock  = $_POST['p_stock'];
    $p_detail = $_POST['p_detail'];
    $cat_id   = $_POST['cat_id'];

    // อัปโหลดรูปใหม่ถ้ามี
    $p_img = NULL;
    if (!empty($_FILES['p_img']['name'])) {
        // ลบรูปเก่า
        $old_img_q = mysqli_query($conn, "SELECT p_img FROM product WHERE p_id='$p_id'");
        $old_img = mysqli_fetch_assoc($old_img_q);
        if ($old_img && !empty($old_img['p_img'])) {
            @unlink("uploads/" . $old_img['p_img']);
        }

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

    // SQL Update
    if ($p_img) {
        $sql = "UPDATE product SET 
                    p_name='$p_name',
                    p_price='$p_price',
                    p_stock='$p_stock',
                    p_detail='$p_detail',
                    cat_id='$cat_id',
                    p_img='$p_img'
                WHERE p_id='$p_id'";
    } else {
        $sql = "UPDATE product SET 
                    p_name='$p_name',
                    p_price='$p_price',
                    p_stock='$p_stock',
                    p_detail='$p_detail',
                    cat_id='$cat_id'
                WHERE p_id='$p_id'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success text-center'>แก้ไขสินค้าสำเร็จ!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>แก้ไขสินค้าไม่สำเร็จ</div>";
    }
}

// -------------------- ลบสินค้า --------------------
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // ลบรูปก่อน
    $img_q = mysqli_query($conn, "SELECT p_img FROM product WHERE p_id='$delete_id'");
    $img_row = mysqli_fetch_assoc($img_q);
    if ($img_row && !empty($img_row['p_img'])) {
        @unlink("uploads/" . $img_row['p_img']);
    }

    // ลบข้อมูลสินค้า
    $del_sql = "DELETE FROM product WHERE p_id='$delete_id'";
    if (mysqli_query($conn, $del_sql)) {
        echo "<div class='alert alert-success text-center'>ลบสินค้าสำเร็จ!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>ลบสินค้าไม่สำเร็จ</div>";
    }
}

// -------------------- ดึง category --------------------
$cat_sql = "SELECT * FROM category";
$cat_result = mysqli_query($conn, $cat_sql);

// -------------------- ดึงสินค้า --------------------
$prod_sql = "SELECT p.*, c.cat_name 
             FROM product p 
             LEFT JOIN category c ON p.cat_id = c.cat_id 
             ORDER BY p.p_id DESC";
$prod_result = mysqli_query($conn, $prod_sql);

// -------------------- ดึงข้อมูลมาแก้ไข --------------------
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_q = mysqli_query($conn, "SELECT * FROM product WHERE p_id='$edit_id'");
    $edit_data = mysqli_fetch_assoc($edit_q);
}
?>

<!doctype html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จัดการข้อมูลรองเท้า</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

  <!-- ฟอร์มเพิ่ม/แก้ไขสินค้า -->
  <div class="card shadow-lg rounded-4 mb-4">
    <div class="card-header bg-primary text-white text-center">
      <h4><?= $edit_data ? "แก้ไขสินค้า" : "เพิ่มสินค้าใหม่" ?></h4>
    </div>
    <div class="card-body p-4">
      <form action="" method="post" enctype="multipart/form-data">
        
        <input type="hidden" name="p_id" value="<?= $edit_data['p_id'] ?? '' ?>">

        <div class="mb-3">
          <label class="form-label">ชื่อสินค้า</label>
          <input type="text" name="p_name" class="form-control" 
                 value="<?= $edit_data['p_name'] ?? '' ?>" required>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">ราคา</label>
            <input type="number" step="0.01" name="p_price" class="form-control" 
                   value="<?= $edit_data['p_price'] ?? '' ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">จำนวนในสต็อก</label>
            <input type="number" name="p_stock" class="form-control" 
                   value="<?= $edit_data['p_stock'] ?? '' ?>" required>
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
            <?php 
            $cat_result2 = mysqli_query($conn, "SELECT * FROM category");
            while ($row = mysqli_fetch_assoc($cat_result2)) { 
              $sel = ($edit_data && $edit_data['cat_id'] == $row['cat_id']) ? "selected" : "";
            ?>
              <option value="<?= $row['cat_id'] ?>" <?= $sel ?>><?= $row['cat_name'] ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">รูปภาพ</label>
          <?php if ($edit_data && $edit_data['p_img']) { ?>
            <div class="mb-2">
              <img src="uploads/<?= $edit_data['p_img'] ?>" width="80" class="rounded">
            </div>
          <?php } ?>
          <input type="file" name="p_img" class="form-control">
        </div>

        <div class="text-center">
          <?php if ($edit_data) { ?>
            <button type="submit" name="update" class="btn btn-warning px-4">อัปเดต</button>
            <a href="?" class="btn btn-secondary px-4">ยกเลิก</a>
          <?php } else { ?>
            <button type="submit" name="submit" class="btn btn-success px-4">บันทึก</button>
            <button type="reset" class="btn btn-secondary px-4">ล้าง</button>
          <?php } ?>
          <a href="list_category.php" class="btn btn-info px-4">ไปที่หมวดหมู่</a>
        </div>
      </form>
    </div>
  </div>

  <!-- ตารางสินค้า -->
  <div class="card shadow-lg rounded-4">
    <div class="card-header bg-dark text-white text-center">
      <h4>รายการสินค้า</h4>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr class="text-center">
            <th>รหัส</th>
            <th>ชื่อสินค้า</th>
            <th>ราคา</th>
            <th>สต็อก</th>
            <th>หมวดหมู่</th>
            <th>รูปภาพ</th>
            <th>จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($prod = mysqli_fetch_assoc($prod_result)) { ?>
          <tr class="align-middle text-center">
            <td><?= $prod['p_id'] ?></td>
            <td><?= $prod['p_name'] ?></td>
            <td><?= number_format($prod['p_price'],2) ?></td>
            <td><?= $prod['p_stock'] ?></td>
            <td><?= $prod['cat_name'] ?></td>
            <td>
              <?php if ($prod['p_img']) { ?>
                <img src="uploads/<?= $prod['p_img'] ?>" width="60">
              <?php } else { ?>
                <span class="text-muted">ไม่มีรูป</span>
              <?php } ?>
            </td>
            <td>
              <a href="?edit=<?= $prod['p_id'] ?>" class="btn btn-warning btn-sm">แก้ไข</a>
              <a href="?delete=<?= $prod['p_id'] ?>" 
                 onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบสินค้า?')" 
                 class="btn btn-danger btn-sm">ลบ</a>
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
