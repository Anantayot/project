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
  <style>
    body {
      background: linear-gradient(135deg, #0d1117, #1b2735);
      color: #eaeaea;
      min-height: 100vh;
    }
    .navbar {
      background: linear-gradient(90deg, #0d6efd, #6610f2);
    }
    .navbar-brand {
      font-weight: bold;
      color: #fff !important;
      font-size: 1.5rem;
      letter-spacing: 1px;
    }
    .form-select, .form-control {
      border-radius: 10px;
    }
    .btn-primary {
      background: linear-gradient(90deg, #0d6efd, #6610f2);
      border: none;
      border-radius: 10px;
    }
    .btn-primary:hover {
      background: linear-gradient(90deg, #6610f2, #0d6efd);
    }
    .card {
      background-color: #161b22;
      border: 1px solid #2d313a;
      border-radius: 15px;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.5);
    }
    .card-title {
      font-weight: 600;
      color: #fff;
    }
    .card p {
      color: #a0a0a0;
    }
    footer {
      background: #0d1117;
      color: #bbb;
      padding: 20px;
      margin-top: 50px;
    }
    .toast {
      opacity: 0;
      transition: opacity 0.5s ease-in-out;
    }
    .toast.show {
      opacity: 1;
    }
  </style>
</head>
<body>

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php"><i class="bi bi-cpu"></i> MyCommiss</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>

<!-- 🔍 ส่วนค้นหา -->
<div class="container mt-5">
  <div class="text-center mb-4">
    <h2 class="fw-bold text-light">💻 สินค้าทั้งหมด</h2>
    <p class="text-secondary">ค้นหาและเลือกซื้ออุปกรณ์คอมพิวเตอร์ที่คุณต้องการได้ที่นี่</p>
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
          <div class="card h-100 shadow-lg">
            <img src="<?= $imagePath ?>" class="card-img-top rounded-top" style="height:200px;object-fit:cover;">
            <div class="card-body">
              <h6 class="card-title text-truncate" title="<?= htmlspecialchars($p['p_name']) ?>">
                <?= htmlspecialchars($p['p_name']) ?>
              </h6>
              <p class="mb-1">หมวดหมู่: <?= htmlspecialchars($p['cat_name']) ?></p>
              <p class="text-info fw-bold"><?= number_format($p['p_price'], 2) ?> บาท</p>

              <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-outline-light btn-sm w-100 mb-2">
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

<!-- ✅ Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>
</html>
