<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
include "../connectdb.php";

if(!isset($_GET['id'])) die("❌ ไม่มีรหัสสินค้า");

$id = intval($_GET['id']);

// ดึงข้อมูลสินค้า
$stmt = $conn->prepare("SELECT * FROM product WHERE p_id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
if(!$product) die("❌ ไม่พบสินค้า");

// อัปเดตเมื่อส่งฟอร์ม
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name = $_POST['name'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $cat = intval($_POST['category_id']);

    $stmt = $conn->prepare("UPDATE product SET p_name=?, p_price=?, p_stock=?, cat_id=? WHERE p_id=?");
    $stmt->bind_param("sdiii",$name,$price,$stock,$cat,$id);

    if($stmt->execute()){
        header("Location: index.php");
        exit();
    }else{
        echo "❌ Error: ".$conn->error;
    }
}

// โหลดหมวดหมู่
$cats = $conn->query("SELECT * FROM category");
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แก้ไขสินค้า</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4" style="max-width:600px;">
<h3>แก้ไขสินค้า</h3>
<form method="post" class="mt-3">
  <div class="mb-3">
    <label>ชื่อสินค้า</label>
    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['p_name']) ?>" required>
  </div>
  <div class="mb-3">
    <label>ราคา</label>
    <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['p_price'] ?>" required>
  </div>
  <div class="mb-3">
    <label>คงเหลือ</label>
    <input type="number" name="stock" class="form-control" value="<?= $product['p_stock'] ?>" required>
  </div>
  <div class="mb-3">
    <label>ประเภท</label>
    <select name="category_id" class="form-select">
      <?php while($c=$cats->fetch_assoc()){ ?>
        <option value="<?= $c['cat_id'] ?>" <?= ($product['cat_id']==$c['cat_id'])?"selected":"" ?>>
          <?= htmlspecialchars($c['cat_name']) ?>
        </option>
      <?php } ?>
    </select>
  </div>
  <button class="btn btn-success">💾 บันทึก</button>
  <a href="index.php" class="btn btn-secondary">↩ ยกเลิก</a>
</form>
</div>
</body>
</html>
