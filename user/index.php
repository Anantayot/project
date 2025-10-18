<?php
session_start();
include("connectdb.php");

// 🔹 ดึงข้อมูลสินค้า 3 ประเภท
$newProducts = $conn->query("
  SELECT p.*, c.cat_name FROM product p 
  LEFT JOIN category c ON p.cat_id = c.cat_id 
  ORDER BY p_id DESC LIMIT 10
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
  SELECT p.*, c.cat_name FROM product p 
  LEFT JOIN category c ON p.cat_id = c.cat_id 
  ORDER BY RAND() LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>🖥️ MyCommiss | หน้าร้าน</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #fff; font-family: "Prompt", sans-serif; color: #212529; }

    /* Navbar */
    .navbar { background: #fff; border-bottom: 3px solid #D10024; }
    .navbar-brand { color: #D10024 !important; font-weight: 700; font-size: 1.6rem; }
    .nav-link:hover, .nav-link.active { color: #D10024 !important; }

    /* Section Title */
    .section-title { font-weight: 700; color: #D10024; margin: 30px 0 20px; }

    /* Card */
    .product-card {
      border: 1px solid #eee;
      border-radius: 10px;
      overflow: hidden;
      transition: 0.3s;
    }
    .product-card:hover {
      transform: translateY(-5px);
      border-color: #D10024;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .product-card img { height: 180px; object-fit: cover; }
    .product-card .card-body { text-align: center; }
    .btn-primary {
      background-color: #D10024; border: none; border-radius: 8px;
    }
    .btn-primary:hover { background-color: #a5001b; }

    footer {
      background: #f8f9fa; border-top: 3px solid #D10024;
      color: #666; padding: 20px; margin-top: 50px;
    }

    /* Carousel */
    .carousel-inner {
      display: flex;
      flex-wrap: nowrap;
      overflow-x: auto;
      scroll-behavior: smooth;
    }
    .carousel-item {
      flex: 0 0 auto;
      width: 250px;
      margin-right: 15px;
    }
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
      filter: invert(35%) sepia(91%) saturate(2091%) hue-rotate(341deg) brightness(90%) contrast(97%);
    }
  </style>
</head>
<body>

<?php include("navbar_user.php"); ?>

<div class="container mt-4">

  <!-- 🆕 สินค้าใหม่ล่าสุด -->
  <h3 class="section-title text-center">🆕 สินค้าใหม่ล่าสุด</h3>
  <div id="carouselNew" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner px-3">
      <?php foreach ($newProducts as $index => $p): 
        $img = "../admin/uploads/" . $p['p_image'];
        if (!file_exists($img) || empty($p['p_image'])) $img = "img/default.png";
      ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
          <div class="card product-card">
            <img src="<?= $img ?>" class="card-img-top">
            <div class="card-body">
              <h6 class="card-title text-truncate"><?= htmlspecialchars($p['p_name']) ?></h6>
              <p class="text-danger fw-bold"><?= number_format($p['p_price'], 2) ?> บาท</p>
              <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-sm btn-primary w-100">ดูรายละเอียด</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselNew" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselNew" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

  <!-- 🔥 สินค้าขายดีที่สุด -->
  <h3 class="section-title text-center mt-5">🔥 สินค้าขายดีที่สุด</h3>
  <div id="carouselBest" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner px-3">
      <?php foreach ($bestSellers as $index => $p): 
        $img = "../admin/uploads/" . $p['p_image'];
        if (!file_exists($img) || empty($p['p_image'])) $img = "img/default.png";
      ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
          <div class="card product-card border-warning">
            <img src="<?= $img ?>" class="card-img-top">
            <div class="card-body">
              <h6 class="card-title text-truncate"><?= htmlspecialchars($p['p_name']) ?></h6>
              <span class="badge bg-warning text-dark mb-2">ขายแล้ว <?= $p['total_sold'] ?> ชิ้น</span>
              <p class="text-danger fw-bold"><?= number_format($p['p_price'], 2) ?> บาท</p>
              <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-sm btn-primary w-100">ดูรายละเอียด</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselBest" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselBest" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

  <!-- 🎲 สินค้าแนะนำ -->
  <h3 class="section-title text-center mt-5">🎲 สินค้าแนะนำ</h3>
  <div id="carouselRandom" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner px-3">
      <?php foreach ($randomProducts as $index => $p): 
        $img = "../admin/uploads/" . $p['p_image'];
        if (!file_exists($img) || empty($p['p_image'])) $img = "img/default.png";
      ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
          <div class="card product-card">
            <img src="<?= $img ?>" class="card-img-top">
            <div class="card-body">
              <h6 class="card-title text-truncate"><?= htmlspecialchars($p['p_name']) ?></h6>
              <p class="text-danger fw-bold"><?= number_format($p['p_price'], 2) ?> บาท</p>
              <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-sm btn-primary w-100">ดูรายละเอียด</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselRandom" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselRandom" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

  <!-- 🛍️ ปุ่มดูสินค้าทั้งหมด -->
  <div class="text-center mt-5">
    <a href="products_all.php" class="btn btn-lg btn-primary px-4 py-2">
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
