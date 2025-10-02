<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
include "../connectdb.php";

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $name  = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $cat   = intval($_POST['category_id']);

    $stmt = $conn->prepare("INSERT INTO product (p_name,p_price,p_stock,cat_id) VALUES (?,?,?,?)");
    $stmt->bind_param("sdii",$name,$price,$stock,$cat);

    if($stmt->execute()){
        header("Location: index.php");
        exit();
    } else {
        echo "❌ Error: ".$conn->error;
    }
}

$cats = $conn->query("SELECT * FROM category");
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>เพิ่มสินค้า</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4" style="max-width:600px;">
<h3>เพิ่มสินค้า</h3>
<form method="post" class="mt-3">
  <div class="mb-3">
    <label>ชื่อสินค้า</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>ราคา</label>
    <input type="number" step="0.01" name="price" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>คงเหลือ</label>
    <input type="number" name="stock" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>ประเภท</label>
    <select name="category_id" class="form-select" required>
      <?php while($c=$cats->fetch_assoc()){ ?>
        <option value="<?= $c['cat_id'] ?>"><?= htmlspecialchars($c['cat_name']) ?></option>
      <?php } ?>
    </select>
  </div>
  <button class="btn btn-success">💾 บันทึก</button>
  <a href="index.php" class="btn btn-secondary">↩ ยกเลิก</a>
</form>
</div>
</body>
</html>
