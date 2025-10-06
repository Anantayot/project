<?php
session_start();
include __DIR__ . "/connectdb.php";
include __DIR__ . "/lib/functions.php";
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/flash.php";

$q = trim($_GET['q'] ?? '');
$products = [];
if ($q !== '') {
  $stm = $conn->prepare("SELECT p.*, c.name AS cat_name
                         FROM products p
                         LEFT JOIN categories c ON p.cat_id=c.id
                         WHERE p.name LIKE ? OR p.description LIKE ?
                         ORDER BY p.id DESC");
  $like = "%$q%";
  $stm->execute([$like, $like]);
  $products = $stm->fetchAll();
}
?>
<h3 class="mb-3">ผลการค้นหา: <?php echo htmlspecialchars($q); ?></h3>
<div class="row g-3">
  <?php foreach ($products as $p): ?>
    <div class="col-6 col-md-4 col-lg-3">
      <div class="card h-100">
        <img src="uploads/<?php echo htmlspecialchars($p['image'] ?: 'noimage.png'); ?>" class="card-img-top" alt="">
        <div class="card-body d-flex flex-column">
          <span class="badge bg-soft mb-2"><?php echo htmlspecialchars($p['cat_name'] ?: 'ทั่วไป'); ?></span>
          <h6 class="card-title flex-grow-1"><?php echo htmlspecialchars($p['name']); ?></h6>
          <div class="d-flex justify-content-between align-items-center">
            <strong>฿<?php echo format_price($p['price']); ?></strong>
            <a href="product.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-primary">ดูรายละเอียด</a>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <?php if (!$products): ?>
    <div class="col-12"><div class="alert alert-secondary">ไม่พบสินค้า</div></div>
  <?php endif; ?>
</div>
<?php include __DIR__ . "/partials/footer.php"; ?>
