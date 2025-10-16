<?php
session_start();
include("connectdb.php");

// ✅ ต้องเข้าสู่ระบบก่อน
if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$customer_id = $_SESSION['customer_id'];

// ✅ ตรวจสอบว่ามี id คำสั่งซื้อหรือไม่
if (!isset($_GET['id'])) {
  die("<p class='text-center mt-5 text-danger'>❌ ไม่พบรหัสคำสั่งซื้อ</p>");
}

$order_id = intval($_GET['id']);

// ✅ ดึงข้อมูลคำสั่งซื้อ (เฉพาะของลูกค้าคนนั้น)
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND customer_id = ?");
$stmt->execute([$order_id, $customer_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
  die("<p class='text-center mt-5 text-danger'>❌ ไม่พบคำสั่งซื้อนี้ หรือคุณไม่มีสิทธิ์ดู</p>");
}

// ✅ ดึงข้อมูลสินค้าในคำสั่งซื้อนี้
$stmt2 = $conn->prepare("SELECT d.*, p.p_name, p.p_image 
                         FROM order_details d 
                         LEFT JOIN product p ON d.p_id = p.p_id 
                         WHERE d.order_id = ?");
$stmt2->execute([$order_id]);
$details = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายละเอียดคำสั่งซื้อ #<?= $order_id ?> | MyCommiss</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include("navbar_user.php"); ?>

<div class="container mt-4">
  <h3 class="fw-bold text-center mb-4">📦 รายละเอียดคำสั่งซื้อ #<?= $order_id ?></h3>

  <!-- 🔹 ข้อมูลคำสั่งซื้อ -->
  <div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-dark text-white fw-semibold">ข้อมูลคำสั่งซื้อ</div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <p><strong>วันที่สั่งซื้อ:</strong> <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></p>
          <p><strong>วิธีชำระเงิน:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
          <p><strong>สถานะการชำระเงิน:</strong> 
            <?php
              $status = $order['payment_status'] ?? 'รอดำเนินการ';
              if ($status === 'ชำระเงินแล้ว') {
                $badgeClass = 'success';
              } elseif ($status === 'ยกเลิก') {
                $badgeClass = 'danger';
              } else {
                $badgeClass = 'warning';
              }
            ?>
            <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
          </p>

          <?php if ($status === 'รอดำเนินการ' && in_array($order['payment_method'], ['BANK_TRANSFER', 'QR'])): ?>
            <a href="payment_confirm.php?id=<?= $order_id ?>" class="btn btn-warning mt-2">
              💰 แจ้งชำระเงิน
            </a>
          <?php endif; ?>
        </div>
        <div class="col-md-6">
          <p><strong>ที่อยู่จัดส่ง:</strong><br><?= nl2br(htmlspecialchars($order['shipping_address'] ?? '-')) ?></p>
          <p><strong>ยอดรวมทั้งหมด:</strong> <span class="text-danger fw-bold"><?= number_format($order['total_price'], 2) ?> บาท</span></p>
        </div>
      </div>
    </div>
  </div>

  <!-- 🔹 รายการสินค้า -->
  <div class="card shadow-sm border-0">
    <div class="card-header bg-dark text-white fw-semibold">รายการสินค้า</div>
    <div class="card-body table-responsive">
      <table class="table align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>ภาพสินค้า</th>
            <th>ชื่อสินค้า</th>
            <th>จำนวน</th>
            <th>ราคาต่อหน่วย</th>
            <th>รวม</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($details as $d): ?>
            <?php
              $sum = $d['price'] * $d['quantity'];
              $imgPath = "../admin/uploads/" . $d['p_image'];
              if (!file_exists($imgPath) || empty($d['p_image'])) {
                $imgPath = "img/default.png";
              }
            ?>
            <tr>
              <td><img src="<?= $imgPath ?>" width="80" height="80" class="rounded shadow-sm"></td>
              <td><?= htmlspecialchars($d['p_name']) ?></td>
              <td><?= $d['quantity'] ?></td>
              <td><?= number_format($d['price'], 2) ?> บาท</td>
              <td><?= number_format($sum, 2) ?> บาท</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="text-center mt-4">
    <a href="orders.php" class="btn btn-secondary">⬅️ กลับไปหน้าคำสั่งซื้อ</a>
  </div>
</div>

<footer class="text-center py-3 mt-5 bg-dark text-white">
  © <?= date('Y') ?> MyCommiss | รายละเอียดคำสั่งซื้อ
</footer>

</body>
</html>
