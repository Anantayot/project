<?php
session_start();
include __DIR__ . "/connectdb.php";
include __DIR__ . "/lib/functions.php";
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/flash.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stm = $conn->prepare("SELECT p.*, c.name AS cat_name FROM products p LEFT JOIN categories c ON p.cat_id=c.id WHERE p.id=?");
$stm->execute([$id]);
$p = $stm->fetch();
if (!$p) { flash('danger','ไม่พบสินค้า'); header("Location: index.php"); exit(); }

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $qty = max(1, (int)($_POST['qty'] ?? 1));
  $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
  flash('success','เพิ่มลงตะกร้าแล้ว');
  header("Location: cart.php");
  exit();
}
?>
<div class="row g-3">
  <div class="col-md-5">
    <img src="uploads/<?php echo htmlspecialchars($p['image'] ?: 'noimage.png'); ?>" class="img-fluid rounded" alt="">
  </div>
  <div class="col-md-7">
    <h3 class="mb-1"><?php echo htmlspecialchars($p['name']); ?></h3>
    <p class="text-secondary mb-2">หมวด: <?php echo htmlspecialchars($p['cat_name'] ?: 'ทั่วไป'); ?></p>
    <h4 class="text-success mb-3">฿<?php echo format_price($p['price']); ?></h4>
    <p><?php echo nl2br(htmlspecialchars($p['description'] ?: '')); ?></p>

    <form method="post" class="mt-3">
      <div class="input-group" style="max-width: 240px;">
        <input type="number" name="qty" min="1" value="1" class="form-control">
        <button class="btn btn-primary" type="submit"><i class="bi bi-cart-plus"></i> หยิบใส่ตะกร้า</button>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . "/partials/footer.php"; ?>
