<?php
include("../connectdb.php"); // ✅ แก้ path ให้ถูกต้อง

$search = $_GET['search'] ?? '';
$cat = $_GET['cat'] ?? '';

$sql = "SELECT p.*, c.cat_name 
        FROM product p 
        LEFT JOIN category c ON p.cat_id = c.cat_id 
        WHERE 1";

if (!empty($search)) {
  $sql .= " AND p.p_name LIKE :search";
}
if (!empty($cat)) {
  $sql .= " AND p.cat_id = :cat";
}

$stmt = $conn->prepare($sql);
if (!empty($search)) $stmt->bindValue(':search', "%$search%");
if (!empty($cat)) $stmt->bindValue(':cat', $cat);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$cats = $conn->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>MyCommiss | หน้าร้าน</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- 🔹 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">🖥 MyCommiss</a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a href="cart.php" class="nav-link">ตะกร้า</a></li>
      <li class="nav-item"><a href="login.php" class="nav-link">เข้าสู่ระบบ</a></li>
    </ul>
  </div>
</nav>

<div class="container mt-4">
  <!-- 🔍 ค้นหา -->
  <form class="row mb-4" method="get">
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
      <input type="text" name="search" class="form-control" placeholder="ค้นหาสินค้า..." value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-md-2 d-grid">
      <button class="btn btn-primary">ค้นหา</button>
    </div>
  </form>

  <!-- 🛍 สินค้า -->
  <div class="row row-cols-1 row-cols-md-4 g-4">
    <?php if (count($products) > 0): ?>
      <?php foreach ($products as $p): ?>
        <?php
          // ✅ กำหนด path รูปภาพให้ถูกต้อง
          $imagePath = "../admin/uploads/" . $p['p_image'];
          if (!file_exists($imagePath) || empty($p['p_image'])) {
            $imagePath = "img/default.png"; // รูปสำรอง (สร้างโฟลเดอร์ user/img/ และใส่ default.png)
          }
        ?>
        <div class="col">
          <div class="card h-100 shadow-sm border-0">
            <img src="<?= $imagePath ?>" class="card-img-top" style="height:200px;object-fit:cover;">
            <div class="card-body">
              <h6 class="card-title text-truncate" title="<?= htmlspecialchars($p['p_name']) ?>">
                <?= htmlspecialchars($p['p_name']) ?>
              </h6>
              <p class="text-muted mb-2"><?= number_format($p['p_price'], 2) ?> บาท</p>
              <a href="product_detail.php?id=<?= $p['p_id'] ?>" class="btn btn-sm btn-outline-primary w-100">ดูรายละเอียด</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-muted">ไม่พบสินค้าที่ค้นหา</p>
    <?php endif; ?>
  </div>
</div>

<footer class="text-center py-3 mt-5 bg-dark text-white">
  © <?= date('Y') ?> MyCommiss | หน้าร้าน
</footer>

</body>
</html>
