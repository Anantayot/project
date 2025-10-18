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
      background-color: #fff;
      border-bottom: 3px solid #D10024;
    }
    .navbar-brand {
      color: #D10024 !important;
      font-weight: 700;
      font-size: 1.6rem;
      letter-spacing: 0.5px;
    }
    .navbar-brand:hover { color: #a5001b !important; }

    .nav-link {
      color: #333 !important;
      font-weight: 500;
      transition: 0.2s ease;
    }
    .nav-link:hover,
    .nav-link.active {
      color: #D10024 !important;
      font-weight: 600;
    }

    /* 🔹 ปุ่มหลัก */
    .btn-primary {
      background-color: #D10024;
      border: none;
      border-radius: 8px;
    }
    .btn-primary:hover {
      background-color: #a5001b;
    }
    .btn-outline-primary {
      color: #D10024;
      border-color: #D10024;
    }
    .btn-outline-primary:hover {
      background-color: #D10024;
      color: #fff;
    }

    /* 🔹 การ์ดสินค้า */
    .card {
      border: 1px solid #e6e6e6;
      border-radius: 12px;
      transition: all 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      border-color: #D10024;
    }
    .card-title {
      font-weight: 600;
      color: #000;
    }
    .card p { color: #555; }

    /* 🔹 ชื่อผู้ใช้ */
    .user-link {
      color: #D10024 !important;
      transition: 0.2s ease;
      text-decoration: none !important;
    }
    .user-link:hover {
      color: #a5001b !important;
    }
    .user-link.active {
      color: #D10024 !important;
      text-shadow: 0 0 6px rgba(209, 0, 36, 0.4);
    }

    /* 🔹 ส่วนหัวและ Footer */
    .section-title {
      font-weight: 700;
      color: #D10024;
    }
    footer {
      background: #f8f9fa;
      color: #666;
      padding: 20px;
      margin-top: 50px;
      border-top: 3px solid #D10024;
    }
  </style>
</head>
<body>

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php"><i class="bi bi-cpu"></i> MyCommiss</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a href="index.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='index.php'?'active':'' ?>">🏠 หน้าร้าน</a></li>
        <li class="nav-item"><a href="cart.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='cart.php'?'active':'' ?>">🛒 ตะกร้า</a></li>

        <?php if (isset($_SESSION['customer_id'])): ?>
          <li class="nav-item"><a href="orders.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='orders.php'?'active':'' ?>">📦 คำสั่งซื้อของฉัน</a></li>
          <li class="nav-item"><a href="profile.php" class="nav-link user-link <?= basename($_SERVER['PHP_SELF'])=='profile.php'?'active':'' ?>">👤 <?= htmlspecialchars($_SESSION['customer_name']) ?></a></li>
          <li class="nav-item"><a href="#" class="nav-link text-danger fw-semibold" onclick="confirmLogout(event)">🚪 ออกจากระบบ</a></li>
        <?php else: ?>
          <li class="nav-item"><a href="login.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='login.php'?'active':'' ?>">🔑 เข้าสู่ระบบ</a></li>
          <li class="nav-item"><a href="register.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='register.php'?'active':'' ?>">📝 สมัครสมาชิก</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- ✅ Confirm Logout -->
<script>
function confirmLogout(e) {
  e.preventDefault();
  if (confirm("คุณแน่ใจหรือไม่ว่าต้องการออกจากระบบ?")) {
    window.location = "logout.php";
  }
}
</script>

<!-- 🔍 ส่วนค้นหา -->
<div class="container mt-4">
  <div class="text-center mb-4">
    <h2 class="section-title">💻 สินค้าทั้งหมด</h2>
    <p class="text-muted">ค้นหาและเลือกซื้ออุปกรณ์คอมพิวเตอร์ได้ง่าย ๆ</p>
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
            <img src="<?= $imagePath ?>" class="card-img-top" style="height:200px;object-fit:cover;">
            <div class="card-body">
              <h6 class="card-title text-truncate" title="<?= htmlspecialchars($p['p_name']) ?>">
                <?= htmlspecialchars($p['p_name']) ?>
              </h6>
              <p class="mb-1"><i class="bi bi-tags"></i> <?= htmlspecialchars($p['cat_name']) ?></p>
              <p class="fw-bold" style="color:#D10024;"><?= number_format($p['p_price'], 2) ?> บาท</p>

              <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-outline-primary btn-sm w-100 mb-2">
                🔎 ดูรายละเอียด
              </a>

              <?php if (isset($_SESSION['customer_id'])): ?>
                <form method="post" action="cart_add.php">
                  <input type="hidden" name="id" value="<?= $p['p_id'] ?>">
                  <button type="submit" class="btn btn-primary btn-sm w-100">🛒 หยิบใส่ตะกร้า</button>
                </form>
              <?php else: ?>
                <a href="login.php" class="btn btn-outline-secondary btn-sm w-100">🔑 เข้าสู่ระบบเพื่อสั่งซื้อ</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col text-center text-muted"><p>😢 ไม่พบสินค้าที่ค้นหา</p></div>
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
