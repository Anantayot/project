<?php
session_start();
include("connectdb.php");

// 🔹 รับค่าค้นหา
$search = $_GET['search'] ?? '';
$cat = $_GET['cat'] ?? '';

// 🔹 ดึงข้อมูลหมวดหมู่
$cats = $conn->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);

// 🔹 ดึงข้อมูลสินค้า
$sql = "SELECT p.*, c.cat_name 
        FROM product p 
        LEFT JOIN category c ON p.cat_id = c.cat_id 
        WHERE 1";

if (!empty($search)) $sql .= " AND p.p_name LIKE :search";
if (!empty($cat)) $sql .= " AND p.cat_id = :cat";

$stmt = $conn->prepare($sql);
if (!empty($search)) $stmt->bindValue(':search', "%$search%");
if (!empty($cat)) $stmt->bindValue(':cat', $cat);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>🖥️ MyCommiss | หน้าร้าน</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #ffffff;
      color: #212529;
      font-family: "Prompt", sans-serif;
    }

    /* 🔹 Navbar */
    .navbar {
      background: linear-gradient(90deg, #0066ff, #0040ff);
    }
    .navbar-brand {
      font-weight: bold;
      color: #fff !important;
      font-size: 1.6rem;
      letter-spacing: 0.5px;
    }

    /* 🔹 Search bar */
    .form-select, .form-control {
      border-radius: 8px;
    }
    .btn-primary {
      background: linear-gradient(90deg, #0066ff, #0040ff);
      border: none;
      border-radius: 8px;
    }
    .btn-primary:hover {
      background: linear-gradient(90deg, #0040ff, #0066ff);
    }

    /* 🔹 Card สินค้า */
    .card {
      border: 1px solid #e6e6e6;
      border-radius: 12px;
      transition: all 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .card-title {
      font-weight: 600;
      color: #000;
    }
    .card p {
      color: #555;
    }

    /* 🔹 Footer */
    footer {
      background: #f8f9fa;
      color: #666;
      padding: 20px;
      margin-top: 50px;
      border-top: 1px solid #e0e0e0;
    }

    /* 🔹 Heading */
    .section-title {
      font-weight: 700;
      color: #0040ff;
    }
  </style>
</head>
<body>

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php"><i class="bi bi-cpu"></i> MyCommiss</a>
  </div>
</nav>

<!-- 🔍 ส่วนค้นหา -->
<div class="container mt-5">
  <div class="text-center mb-4">
    <h2 class="section-title">💻 สินค้าทั้งหมด</h2>
    <p class="text-muted">ค้นหาและเลือกซื้ออุปกรณ์คอมพิวเตอร์ได้ง่ายๆ</p>
  </div>

  <form class="row g-3 mb-4" method="get">
    <div class="col-md-4">
      <select name="cat" class="form-select">
        <option value="">-- เลือกหมวดหมู่ --</option>
        <?php foreach ($cats as $c): ?>
          <option value="<?= $c['cat_id'] ?>" <?= $cat == $c['cat_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['cat_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-6">
      <input type="text" name="search" class="form-control" placeholder="🔍 ค้นหาสินค้า..." value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-md-2 d-grid">
      <button class="btn btn-primary"><i class="bi bi-search"></i> ค้นหา</button>
    </div>
  </form>

  <!-- 🛒 แสดงสินค้า -->
  <div class="row row-cols-1 row-cols-md-4 g-4">
    <?php if (count($products) > 0): ?>
      <?php foreach ($products as $p): ?>
        <?php
          $imagePath = "../admin/uploads/" . $p['p_image'];
          if (!file_exists($imagePath) || empty($p['p_image'])) {
            $imagePath = "img/default.png";
          }
        ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <img src="<?= $imagePath ?>" class="card-img-top rounded-top" style="height:200px;object-fit:cover;">
            <div class="card-body">
              <h6 class="card-title text-truncate" title="<?= htmlspecialchars($p['p_name']) ?>">
                <?= htmlspecialchars($p['p_name']) ?>
              </h6>
              <p class="mb-1"><i class="bi bi-tags"></i> <?= htmlspecialchars($p['cat_name']) ?></p>
              <p class="text-primary fw-bold"><?= number_format($p['p_price'], 2) ?> บาท</p>

              <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-outline-primary btn-sm w-100 mb-2">
                🔎 ดูรายละเอียด
              </a>

              <?php if (isset($_SESSION['customer_id'])): ?>
                <form method="post" action="cart_add.php">
                  <input type="hidden" name="id" value="<?= $p['p_id'] ?>">
                  <button type="submit" class="btn btn-success btn-sm w-100">🛒 หยิบใส่ตะกร้า</button>
                </form>
              <?php else: ?>
                <a href="login.php" class="btn btn-outline-secondary btn-sm w-100">🔑 เข้าสู่ระบบเพื่อสั่งซื้อ</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col text-center text-muted">
        <p>😢 ไม่พบสินค้าที่ค้นหา</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- ✅ Footer -->
<footer class="text-center mt-5">
  <p>© <?= date('Y') ?> MyCommiss | ระบบร้านค้าออนไลน์คอมพิวเตอร์</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
