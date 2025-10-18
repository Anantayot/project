<?php
session_start();
include("connectdb.php");

// 🔹 ดึงข้อมูลหมวดหมู่ทั้งหมด
$cats = $conn->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);

// 🔹 ดึงสินค้า 3 แบบ
$newProducts = $conn->query("
  SELECT p.*, c.cat_name 
  FROM product p 
  LEFT JOIN category c ON p.cat_id = c.cat_id 
  ORDER BY p.p_id DESC 
  LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

$bestSellers = $conn->query("
  SELECT p.*, c.cat_name, SUM(d.quantity) AS total_sold
  FROM order_details d
  JOIN product p ON d.p_id = p.p_id
  LEFT JOIN category c ON p.cat_id = c.cat_id
  GROUP BY p.p_id
  ORDER BY total_sold DESC
  LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

$randomProducts = $conn->query("
  SELECT p.*, c.cat_name 
  FROM product p 
  LEFT JOIN category c ON p.cat_id = c.cat_id 
  ORDER BY RAND() 
  LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>🖥️ MyCommiss | หน้าร้าน</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body { background-color: #ffffff; font-family: "Prompt", sans-serif; color: #212529; }

    /* Navbar */
    .navbar { background-color: #fff; border-bottom: 3px solid #D10024; }
    .navbar-brand { color: #D10024 !important; font-weight: 700; font-size: 1.6rem; }
    .nav-link { color: #333 !important; font-weight: 500; }
    .nav-link:hover, .nav-link.active { color: #D10024 !important; }

    /* Card */
    .card { border: 1px solid #e6e6e6; border-radius: 12px; transition: all 0.3s ease; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-color: #D10024; }
    .card-title { font-weight: 600; }

    /* Button */
    .btn-primary { background-color: #D10024; border: none; border-radius: 8px; }
    .btn-primary:hover { background-color: #a5001b; }
    .btn-outline-primary { border-color: #D10024; color: #D10024; }
    .btn-outline-primary:hover { background-color: #D10024; color: #fff; }

    /* Section */
    .section-title { font-weight: 700; color: #D10024; margin-bottom: 1rem; }
    footer { background: #f8f9fa; color: #666; padding: 20px; border-top: 3px solid #D10024; margin-top: 50px; }
  </style>
</head>
<body>

<?php include("navbar_user.php"); ?>

<div class="container mt-4">

  <!-- 🔹 สินค้าใหม่ล่าสุด -->
  <div class="text-center mb-4">
    <h2 class="section-title">🆕 สินค้าใหม่ล่าสุด</h2>
  </div>
  <div class="row row-cols-1 row-cols-md-5 g-4 mb-5">
    <?php foreach ($newProducts as $p): 
      $img = "../admin/uploads/" . $p['p_image'];
      if (!file_exists($img) || empty($p['p_image'])) $img = "img/default.png";
    ?>
      <div class="col">
        <div class="card h-100">
          <img src="<?= $img ?>" class="card-img-top" style="height:180px;object-fit:cover;">
          <div class="card-body text-center">
            <h6 class="card-title text-truncate"><?= htmlspecialchars($p['p_name']) ?></h6>
            <p class="fw-bold text-danger mb-2"><?= number_format($p['p_price'], 2) ?> บาท</p>
            <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-outline-primary btn-sm w-100">ดูรายละเอียด</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- 🔥 สินค้าขายดีที่สุด -->
  <div class="text-center mb-4">
    <h2 class="section-title">🔥 สินค้าขายดีที่สุด</h2>
  </div>
  <div class="row row-cols-1 row-cols-md-5 g-4 mb-5">
    <?php foreach ($bestSellers as $p): 
      $img = "../admin/uploads/" . $p['p_image'];
      if (!file_exists($img) || empty($p['p_image'])) $img = "img/default.png";
    ?>
      <div class="col">
        <div class="card h-100 border-warning">
          <img src="<?= $img ?>" class="card-img-top" style="height:180px;object-fit:cover;">
          <div class="card-body text-center">
            <h6 class="card-title text-truncate"><?= htmlspecialchars($p['p_name']) ?></h6>
            <p class="fw-bold text-danger mb-2"><?= number_format($p['p_price'], 2) ?> บาท</p>
            <span class="badge bg-warning text-dark mb-2">ขายแล้ว <?= $p['total_sold'] ?> ชิ้น</span>
            <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-outline-primary btn-sm w-100">ดูรายละเอียด</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- 🎲 สินค้าแนะนำ (สุ่ม) -->
  <div class="text-center mb-4">
    <h2 class="section-title">🎲 สินค้าแนะนำ</h2>
  </div>
  <div class="row row-cols-1 row-cols-md-5 g-4 mb-5">
    <?php foreach ($randomProducts as $p): 
      $img = "../admin/uploads/" . $p['p_image'];
      if (!file_exists($img) || empty($p['p_image'])) $img = "img/default.png";
    ?>
      <div class="col">
        <div class="card h-100">
          <img src="<?= $img ?>" class="card-img-top" style="height:180px;object-fit:cover;">
          <div class="card-body text-center">
            <h6 class="card-title text-truncate"><?= htmlspecialchars($p['p_name']) ?></h6>
            <p class="fw-bold text-danger mb-2"><?= number_format($p['p_price'], 2) ?> บาท</p>
            <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-outline-primary btn-sm w-100">ดูรายละเอียด</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- 🛍️ ปุ่มดูสินค้าทั้งหมด -->
  <div class="text-center mt-4">
    <a href="products_all.php" class="btn btn-primary px-4 py-2">
      🛍️ ดูสินค้าทั้งหมด
    </a>
  </div>

</div>

<footer class="text-center mt-5">
  <p>© <?= date('Y') ?> MyCommiss | ระบบร้านค้าออนไลน์คอมพิวเตอร์</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
