<?php
session_start();
include __DIR__ . "/connectdb.php";
include __DIR__ . "/lib/functions.php";
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/flash.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$cat = $conn->prepare("SELECT * FROM categories WHERE id=?");
$cat->execute([$id]);
$cat = $cat->fetch();
if (!$cat) { flash('danger','ไม่พบหมวดหมู่'); header("Location: categories.php"); exit(); }

$stm = $conn->prepare("SELECT * FROM products WHERE cat_id=? ORDER BY id DESC");
$stm->execute([$id]);
$products = $stm->fetchAll();
?>
<h3 class="mb-3">หมวด: <?php echo htmlspecialchars($cat['name']); ?></h3>
<div class="row g-3">
  <?php foreach ($products as $p): ?>
    <div class="col-6 col-md-4 col-lg-3">
      <div class="card h-100">
        <img src="uploads/<?php echo htmlspecialchars($p['image'] ?: 'noimage.png'); ?>" class="card-img-top" alt="">
        <div class="card-body d-flex flex-column">
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
    <div class="col-12"><div class="alert alert-secondary">หมวดนี้ยังไม่มีสินค้า</div></div>
  <?php endif; ?>
</div>
<?php include __DIR__ . "/partials/footer.php"; ?>
