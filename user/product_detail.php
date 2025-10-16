<?php
session_start();
include("connectdb.php");

// ✅ ตรวจสอบว่ามี id สินค้าหรือไม่
if (!isset($_GET['id'])) {
  die("<p class='text-center mt-5 text-danger'>❌ ไม่พบรหัสสินค้า</p>");
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT p.*, c.cat_name 
                        FROM product p 
                        LEFT JOIN category c ON p.cat_id = c.cat_id 
                        WHERE p_id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
  die("<p class='text-center mt-5 text-danger'>❌ ไม่พบสินค้านี้</p>");
}

// ✅ ตั้ง path รูปสินค้า
$imgPath = "../admin/uploads/" . $product['p_image'];
if (!file_exists($imgPath) || empty($product['p_image'])) {
  $imgPath = "img/default.png"; // รูปสำรอง
}

// ✅ เมื่อกดเพิ่มลงตะกร้า (เฉพาะคนล็อกอินเท่านั้น)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!isset($_SESSION['customer_id'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อนสั่งซื้อสินค้า'); window.location='login.php';</script>";
    exit;
  }

  $pid = $product['p_id'];
  $qty = intval($_POST['qty'] ?? 1);

  if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

  // ถ้ามีสินค้าในตะกร้าอยู่แล้ว → บวกจำนวนเพิ่ม
  if (isset($_SESSION['cart'][$pid])) {
    $_SESSION['cart'][$pid]['qty'] += $qty;
  } else {
    $_SESSION['cart'][$pid] = [
      'id' => $pid,
      'name' => $product['p_name'],
      'price' => $product['p_price'],
      'image' => $product['p_image'],
      'qty' => $qty
    ];
  }

  echo "<script>
    alert('✅ เพิ่มสินค้าในตะกร้าเรียบร้อย!');
    window.location='cart.php';
  </script>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($product['p_name']) ?> | รายละเอียดสินค้า</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- ✅ Navbar ส่วนกลาง -->
<?php include("navbar_user.php"); ?>

<div class="container mt-4">
  <div class="card shadow border-0 p-4">
    <div class="row g-4 align-items-center">
      <div class="col-md-5 text-center">
        <img src="<?= $imgPath ?>" class="img-fluid rounded shadow-sm" 
             alt="<?= htmlspecialchars($product['p_name']) ?>">
      </div>

      <div class="col-md-7">
        <h3 class="fw-bold"><?= htmlspecialchars($product['p_name']) ?></h3>
        <p class="text-muted mb-1">
          หมวดหมู่: <?= htmlspecialchars($product['cat_name'] ?? '-') ?>
        </p>
        <h4 class="text-danger mb-3">
          <?= number_format($product['p_price'], 2) ?> บาท
        </h4>
        <p><?= nl2br(htmlspecialchars($product['p_description'])) ?></p>

        <div class="mt-3">
          <?php if (isset($_SESSION['customer_id'])): ?>
            <!-- ✅ ถ้าล็อกอินแล้ว -->
            <form method="post">
              <div class="d-flex align-items-center gap-2 mb-3">
                <label for="qty" class="fw-semibold">จำนวน:</label>
                <input type="number" name="qty" id="qty" min="1" value="1" class="form-control w-25">
              </div>
              <button type="submit" class="btn btn-success">
                🛒 เพิ่มลงตะกร้า
              </button>
              <a href="index.php" class="btn btn-secondary">
                ⬅️ กลับหน้าร้าน
              </a>
            </form>
          <?php else: ?>
            <!-- 🚫 ถ้ายังไม่ล็อกอิน -->
            <div class="alert alert-warning">
              🔑 กรุณาเข้าสู่ระบบก่อนเพื่อทำการสั่งซื้อ
            </div>
            <a href="login.php" class="btn btn-primary">เข้าสู่ระบบ</a>
            <a href="index.php" class="btn btn-secondary">⬅️ กลับหน้าร้าน</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="text-center py-3 mt-5 bg-dark text-white">
  © <?= date('Y') ?> MyCommiss | รายละเอียดสินค้า
</footer>

</body>
</html>
