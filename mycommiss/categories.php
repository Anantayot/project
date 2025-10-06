<?php
session_start();
include __DIR__ . "/connectdb.php";
include __DIR__ . "/lib/functions.php";
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/flash.php";

$cats = $conn->query("SELECT id,name FROM categories ORDER BY name")->fetchAll();
?>
<h3 class="mb-3">ประเภทสินค้า</h3>
<div class="row g-3">
  <?php foreach ($cats as $c): ?>
    <div class="col-6 col-md-4 col-lg-3">
      <a class="card text-decoration-none" href="category.php?id=<?php echo $c['id']; ?>">
        <div class="card-body">
          <h5 class="card-title"><?php echo htmlspecialchars($c['name']); ?></h5>
          <p class="text-secondary mb-0"><i class="bi bi-box-seam"></i> ดูสินค้าในหมวดนี้</p>
        </div>
      </a>
    </div>
  <?php endforeach; ?>
</div>
<?php include __DIR__ . "/partials/footer.php"; ?>
