<?php
session_start();
include __DIR__ . "/connectdb.php"; // ใช้ไฟล์ของผู้ใช้
include __DIR__ . "/lib/functions.php";
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/flash.php";

// ดึงหมวดหมู่
$cats = $conn->query("SELECT id,name FROM categories ORDER BY name")->fetchAll();
// ดึงสินค้าใหม่
$stm = $conn->query("SELECT p.*, c.name AS cat_name
                     FROM products p
                     LEFT JOIN categories c ON p.cat_id=c.id
                     ORDER BY p.id DESC LIMIT 12");
$products = $stm->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">สินค้าล่าสุด</h3>
  <a class="btn btn-outline-light" href="categories.php"><i class="bi bi-grid"></i> ดูตามประเภท</a>
</div>

<div class="row g-3 mb-4">
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
</div>

<?php include __DIR__ . "/partials/footer.php"; ?>
