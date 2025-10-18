<?php
session_start();
include("connectdb.php");

// 🔹 ดึงหมวดหมู่ทั้งหมดมาแสดงใน dropdown
$cats = $conn->query("SELECT * FROM category ORDER BY cat_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// รับค่าค้นหา
$search = $_GET['search'] ?? '';
$cat_id = $_GET['cat'] ?? [];
if (!is_array($cat_id)) $cat_id = [$cat_id];

// 🔹 ดึงข้อมูลสินค้า 3 ประเภท (เมื่อไม่มีการค้นหา)
if (empty($search) && (empty($cat_id) || in_array('', $cat_id))) {
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
} else {
  // 🔍 ดึงข้อมูลตามการค้นหา + หมวดหมู่ (หลายค่าได้)
  $sql = "
    SELECT p.*, c.cat_name 
    FROM product p
    LEFT JOIN category c ON p.cat_id = c.cat_id
    WHERE 1
  ";
  $params = [];

  if (!empty($search)) {
    $sql .= " AND (p.p_name LIKE ? OR c.cat_name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
  }

  if (!empty($cat_id) && !in_array('', $cat_id)) {
    $in = str_repeat('?,', count($cat_id) - 1) . '?';
    $sql .= " AND p.cat_id IN ($in)";
    $params = array_merge($params, $cat_id);
  }

  $sql .= " ORDER BY p.p_id DESC";
  $stmt = $conn->prepare($sql);
  $stmt->execute($params);
  $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
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

    /* Search Bar */
    .search-bar {
      background: #fff;
      border: 2px solid #D10024;
      border-radius: 50px;
      padding: 15px 25px;
      margin: 30px auto;
      max-width: 900px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .search-bar input, .search-bar select {
      border: none;
      outline: none;
      background: none;
      font-size: 1rem;
    }
    .search-bar select {
      color: #333;
      width: 30%;
      cursor: pointer;
      height: 100px;
    }
    .search-bar input {
      width: 50%;
    }
    .search-bar button {
      background: #D10024;
      border: none;
      color: #fff;
      border-radius: 50px;
      padding: 10px 18px;
    }
    .search-bar button:hover { background: #a5001b; }

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
    .product-card img {
      height: 300px;
      object-fit: cover;
      width: 100%;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .product-card .card-body { text-align: center; }
    .product-card .btn {
      background-color: #D10024;
      border: none;
      border-radius: 8px;
    }
    .product-card .btn:hover { background-color: #a5001b; }

    /* Swiper */
    .swiper { width: 100%; padding-bottom: 40px; }
    .swiper-slide { width: 220px; }
    .swiper-button-next, .swiper-button-prev { color: #D10024; }

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

  <!-- 🔍 กล่องค้นหา -->
  <form method="get" class="search-bar d-flex justify-content-between align-items-center flex-wrap">
    <select name="cat[]" multiple class="form-select me-2" style="border:none;width:30%;height:100px;">
      <option value="">-- ประเภทสินค้า --</option>
      <?php foreach ($cats as $c): ?>
        <option value="<?= $c['cat_id'] ?>" 
          <?= (is_array($cat_id) && in_array($c['cat_id'], $cat_id)) ? 'selected' : '' ?>>
          <?= htmlspecialchars($c['cat_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <input type="text" name="search" placeholder="🔍 ค้นหาสินค้า..." value="<?= htmlspecialchars($search) ?>" class="flex-grow-1 me-2">
    <button type="submit"><i class="bi bi-search"></i></button>
  </form>

  <?php if (!empty($search) || (!empty($cat_id) && !in_array('', $cat_id))): ?>
    <!-- 🔍 ผลลัพธ์การค้นหา -->
    <h3 class="section-title">ผลการค้นหา</h3>
    <div class="row row-cols-1 row-cols-md-4 g-4">
      <?php if (count($searchResults) > 0): ?>
        <?php foreach ($searchResults as $p): 
          $img = "../admin/uploads/" . $p['p_image'];
          if (!file_exists($img) || empty($p['p_image'])) $img = "img/default.png";
        ?>
          <div class="col">
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
      <?php else: ?>
        <p class="text-center text-muted mt-4">😢 ไม่พบสินค้าที่ค้นหา</p>
      <?php endif; ?>
    </div>
  <?php else: ?>

    <!-- 🆕 สินค้าใหม่ล่าสุด -->
    <h3 class="section-title">สินค้าใหม่ล่าสุด</h3>
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
    <h3 class="section-title">สินค้าขายดีที่สุด</h3>
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
    <h3 class="section-title">สินค้าแนะนำ</h3>
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

  <?php endif; ?>
</div>

<footer class="text-center mt-5">
  <p>© <?= date('Y') ?> MyCommiss | ระบบร้านค้าออนไลน์คอมพิวเตอร์</p>
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
  document.querySelectorAll('.mySwiper').forEach(swiperEl => {
    new Swiper(swiperEl, {
      slidesPerView: 5,
      spaceBetween: 20,
      navigation: {
        nextEl: swiperEl.querySelector('.swiper-button-next'),
        prevEl: swiperEl.querySelector('.swiper-button-prev'),
      },
      autoplay: {
        delay: 3000, // ⏱ 7 วินาทีต่อสไลด์
        disableOnInteraction: false // ให้เลื่อนต่อแม้ผู้ใช้จะคลิกเอง
      },
      loop: true, // 🔁 สไลด์วนลูป
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
