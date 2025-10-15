<?php
session_start();
include("connectdb.php");

// ดึงข้อมูลคำสั่งซื้อทั้งหมด
$sql = "SELECT * FROM orders ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>MyCommiss | ประวัติคำสั่งซื้อ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- 🔹 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">🖥 MyCommiss</a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a href="cart.php" class="nav-link">ตะกร้า</a></li>
      <li class="nav-item"><a href="orders.php" class="nav-link active">คำสั่งซื้อ</a></li>
      <li class="nav-item"><a href="login.php" class="nav-link">เข้าสู่ระบบ</a></li>
    </ul>
  </div>
</nav>

<div class="container mt-4">
  <h3 class="fw-bold mb-4 text-center">📦 ประวัติคำสั่งซื้อของคุณ</h3>

  <?php if (empty($orders)): ?>
    <div class="alert alert-info text-center">
      ยังไม่มีคำสั่งซื้อในระบบ<br>
      <a href="index.php" class="btn btn-primary mt-3">⬅️ กลับไปเลือกซื้อสินค้า</a>
    </div>
  <?php else: ?>
    <div class="table-responsive shadow-sm">
      <table class="table align-middle table-bordered bg-white">
        <thead class="table-dark text-center">
          <tr>
            <th>รหัสคำสั่งซื้อ</th>
            <th>ชื่อผู้รับ</th>
            <th>วันที่สั่งซื้อ</th>
            <th>วิธีชำระเงิน</th>
            <th>ยอดรวม</th>
            <th>สถานะ</th>
            <th>รายละเอียด</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <?php foreach ($orders as $o): ?>
            <tr>
              <td>#<?= $o['order_id'] ?></td>
              <td><?= htmlspecialchars($o['customer_name']) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($o['order_date'])) ?></td>
              <td><?= htmlspecialchars($o['payment_method']) ?></td>
              <td><?= number_format($o['total_price'], 2) ?> บาท</td>
              <td>
                <?php
                  // แสดงสถานะด้วยสี
                  $status = $o['order_status'] ?? 'รอดำเนินการ';
                  $badgeClass = match($status) {
                    'จัดส่งแล้ว' => 'success',
                    'ยกเลิก' => 'danger',
                    default => 'warning'
                  };
                ?>
                <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
              </td>
              <td>
                <a href="order_detail.php?id=<?= $o['order_id'] ?>" class="btn btn-sm btn-outline-primary">ดูรายละเอียด</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="index.php" class="btn btn-secondary">⬅️ กลับหน้าหลัก</a>
  </div>
</div>

<footer class="text-center py-3 mt-5 bg-dark text-white">
  © <?= date('Y') ?> MyCommiss | ประวัติคำสั่งซื้อ
</footer>

</body>
</html>
