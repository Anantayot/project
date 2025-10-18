<?php
session_start();
include("connectdb.php");

// 🔹 ดึงหมวดหมู่ทั้งหมด
$cats = $conn->query("SELECT * FROM category ORDER BY cat_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// รับค่าค้นหา
$search = $_GET['search'] ?? '';
$cat_id = $_GET['cat'] ?? [];
if (!is_array($cat_id)) $cat_id = [$cat_id];

// 🔹 ดึงข้อมูลสินค้า
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
  // 🔍 ค้นหาตามชื่อหรือหมวดหมู่ (หลายหมวดได้)
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
  <style>
    body { font-family: "Prompt", sans-serif; background:#fff; }
    .navbar { background:#fff; border-bottom:3px solid #D10024; }
    .navbar-brand { color:#D10024 !important; font-weight:700; }
    .search-bar { border:2px solid #D10024; border-radius:50px; padding:15px 25px; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    .dropdown-menu label { display:flex; align-items:center; padding:5px 10px; cursor:pointer; }
    .dropdown-menu label:hover { background:#f8f9fa; }
    .dropdown-toggle { border:1px solid #ccc; border-radius:50px; padding:8px 16px; background:#fff; color:#333; }
    .dropdown-toggle::after { margin-left:8px; }
    .btn-search { background:#D10024; color:#fff; border-radius:50px; padding:10px 20px; border:none; }
    .btn-search:hover { background:#a5001b; }
  </style>
</head>
<body>

<?php include("navbar_user.php"); ?>

<div class="container mt-4">

  <!-- 🔍 กล่องค้นหา -->
  <form method="get" class="search-bar d-flex justify-content-center align-items-center flex-wrap gap-3">

    <!-- ✅ Dropdown เลือกหลายหมวดหมู่ -->
    <div class="dropdown">
      <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        เลือกหมวดหมู่ (<?= count(array_filter($cat_id)) ?: 'ทั้งหมด' ?>)
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="max-height:300px;overflow:auto;">
        <?php foreach ($cats as $c): ?>
        <li>
          <label>
            <input type="checkbox" name="cat[]" value="<?= $c['cat_id'] ?>" 
              <?= in_array($c['cat_id'], $cat_id) ? 'checked' : '' ?>> 
            <span class="ms-2"><?= htmlspecialchars($c['cat_name']) ?></span>
          </label>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- 🔍 ช่องค้นหา -->
    <input type="text" name="search" class="form-control w-50" 
           placeholder="🔍 ค้นหาสินค้า..." value="<?= htmlspecialchars($search) ?>">

    <button type="submit" class="btn-search"><i class="bi bi-search"></i></button>
  </form>

  <?php if (!empty($search) || (!empty($cat_id) && !in_array('', $cat_id))): ?>
    <h3 class="text-center text-danger fw-bold my-4">ผลการค้นหา</h3>
    <div class="row row-cols-1 row-cols-md-4 g-4">
      <?php if (count($searchResults) > 0): ?>
        <?php foreach ($searchResults as $p): 
          $img = "../admin/uploads/" . $p['p_image'];
          if (!file_exists($img) || empty($p['p_image'])) $img = "img/default.png";
        ?>
          <div class="col">
            <div class="card h-100 border-0 shadow-sm">
              <img src="<?= $img ?>" class="card-img-top" style="height:260px;object-fit:cover;">
              <div class="card-body text-center">
                <h6 class="text-truncate"><?= htmlspecialchars($p['p_name']) ?></h6>
                <p class="fw-bold text-danger"><?= number_format($p['p_price'], 2) ?> ฿</p>
                <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-danger w-100">ดูรายละเอียด</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center text-muted mt-4">😢 ไม่พบสินค้าที่ค้นหา</p>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <!-- (เหมือนเดิม: สินค้าใหม่ / ขายดี / แนะนำ) -->
    <h3 class="text-center text-danger fw-bold my-4">🆕 สินค้าใหม่ล่าสุด</h3>
    <div class="swiper mySwiper mb-5">
      <div class="swiper-wrapper">
        <?php foreach ($newProducts as $p):
          $img = "../admin/uploads/" . $p['p_image'];
          if (!file_exists($img) || empty($p['p_image'])) $img = "img/default.png";
        ?>
          <div class="swiper-slide">
            <div class="card border-0 shadow-sm">
              <img src="<?= $img ?>" class="card-img-top" style="height:260px;object-fit:cover;">
              <div class="card-body text-center">
                <h6 class="text-truncate"><?= htmlspecialchars($p['p_name']) ?></h6>
                <p class="fw-bold text-danger"><?= number_format($p['p_price'], 2) ?> ฿</p>
                <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-danger btn-sm w-100">ดูรายละเอียด</a>
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

<footer class="text-center mt-5 border-top pt-3">
  <p>© <?= date('Y') ?> MyCommiss | ระบบร้านค้าออนไลน์คอมพิวเตอร์</p>
</footer>

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
