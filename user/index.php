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

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Swiper -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>

  <style>
    body { background: #fff; font-family: "Prompt", sans-serif; }

    /* Navbar */
    .navbar { background: #fff; border-bottom: 3px solid #D10024; }
    .navbar-brand { color: #D10024 !important; font-weight: 700; font-size: 1.6rem; }
    .nav-link:hover, .nav-link.active { color: #D10024 !important; }

    /* Section Title */
    .section-title { font-weight: 700; color: #D10024; margin: 30px 0 20px; text-align:center; }

    /* Product Card */
    .product-card {
      border: 1px solid #eee;
      border-radius: 12px;
      transition: all 0.3s ease;
      overflow: hidden;
      background: #fff;
    }
    .product-card:hover {
      transform: translateY(-4px);
      border-color: #D10024;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .product-card img { height: 180px; object-fit: cover; width: 100%; }
    .product-card .card-body { text-align: center; }
    .product-card .btn {
      background-color: #D10024;
      border: none;
      border-radius: 8px;
    }
    .product-card .btn:hover { background-color: #a5001b; }

    /* Swiper */
    .swiper {
      width: 100%;
      padding-bottom: 40px;
    }
    .swiper-slide {
      width: 220px;
    }
    .swiper-button-next, .swiper-button-prev {
      color: #D10024;
    }

    footer {
      background: #f8f9fa;
      color: #666;
      border-top: 3px solid #D10024;
      padding: 20px;
      margin-top: 50px;
    }
  </style>
</head>
<body>

<?php include("navbar_user.php"); ?>

<div class="container mt-4">

  <!-- 🆕 สินค้าใหม่ล่าสุด -->
  <h3 class="section-title">🆕 สินค้าใหม่ล่าสุด</h3>
  <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <?php foreach ($newProducts as $p):
        $img = "../admin/uploads/" . $p['p_image'];
        if (!file_exists($img) || empty($p['p_image'])) $img = "img/default.png";
      ?>
        <div class="swiper-slide">
          <div class="product-card card h-100">
            <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['p_name']) ?>">
            <div class="card-body">
              <h6 class="text-truncate"><?= htmlspecialchars($p['p_name']) ?></h6>
              <p class="fw-bold text-danger"><?= number_format($p['p_price'], 2) ?> บาท</p>
              <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-sm w-100 text-white">ดูรายละเอียด</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>

  <!-- 🔥 สินค้าขายดีที่สุด -->
  <h3 class="section-title">🔥 สินค้าขายดีที่สุด</h3>
  <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <?php foreach ($bestSellers as $p):
        $img = "../admin/uploads/" . $p['p_image'];
        if (!file_exists($img) || empty($p['p_image'])) $img = "img/default.png";
      ?>
        <div class="swiper-slide">
          <div class="product-card card h-100 border-warning">
            <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['p_name']) ?>">
            <div class="card-body">
              <h6 class="text-truncate"><?= htmlspecialchars($p['p_name']) ?></h6>
              <span class="badge bg-warning text-dark mb-2">ขายแล้ว <?= $p['total_sold'] ?> ชิ้น</span>
              <p class="fw-bold text-danger"><?= number_format($p['p_price'], 2) ?> บาท</p>
              <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-sm w-100 text-white">ดูรายละเอียด</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>

  <!-- 🎲 สินค้าแนะนำ -->
  <h3 class="section-title">🎲 สินค้าแนะนำ</h3>
  <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <?php foreach ($randomProducts as $p):
        $img = "../admin/uploads/" . $p['p_image'];
        if (!file_exists($img) || empty($p['p_image'])) $img = "img/default.png";
      ?>
        <div class="swiper-slide">
          <div class="product-card card h-100">
            <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['p_name']) ?>">
            <div class="card-body">
              <h6 class="text-truncate"><?= htmlspecialchars($p['p_name']) ?></h6>
              <p class="fw-bold text-danger"><?= number_format($p['p_price'], 2) ?> บาท</p>
              <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-sm w-100 text-white">ดูรายละเอียด</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>

  <div class="text-center mt-5">
    <a href="products_all.php" class="btn btn-lg text-white" style="background:#D10024;">🛍️ ดูสินค้าทั้งหมด</a>
  </div>

</div>

<footer class="text-center mt-5">
  <p>© <?= date('Y') ?> MyCommiss | ระบบร้านค้าออนไลน์คอมพิวเตอร์</p>
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
  // ✅ กำหนด Swiper ทุก Section ให้สไลด์ได้
  document.querySelectorAll('.mySwiper').forEach(swiperEl => {
    new Swiper(swiperEl, {
      slidesPerView: 5,
      spaceBetween: 20,
      navigation: {
        nextEl: swiperEl.querySelector('.swiper-button-next'),
        prevEl: swiperEl.querySelector('.swiper-button-prev'),
      },
      breakpoints: {
        320: { slidesPerView: 2 },
        768: { slidesPerView: 3 },
        992: { slidesPerView: 4 },
        1200: { slidesPerView: 5 },
      },
    });
  });
</script>

</body>
</html>
