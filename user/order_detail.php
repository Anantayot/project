<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
  <style>
    body { background-color: #f8f9fa; }
    .btn { border-radius: 8px; font-weight: 500; transition: all 0.2s ease-in-out; }
    .btn:hover { transform: scale(1.05); }
    .badge { font-size: 0.9rem; padding: 6px 10px; }
    .card-header { background: #212529 !important; color: #fff; }
  </style>
</head>
<body>

<?php include("navbar_user.php"); ?>

<div class="container mt-4 mb-5">
  <h3 class="fw-bold text-center mb-4">📦 รายละเอียดคำสั่งซื้อ #<?= $order_id ?></h3>

  <!-- 🔹 ข้อมูลคำสั่งซื้อ -->
  <div class="card mb-4 shadow-sm border-0">
    <div class="card-header fw-semibold">ข้อมูลคำสั่งซื้อ</div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <p><strong>วันที่สั่งซื้อ:</strong> <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></p>

          <?php
          // ✅ แปลงชื่อวิธีชำระเงินเป็นไทย
          if ($order['payment_method'] === 'QR') {
            $methodText = '💳 ชำระด้วย QR Code';
          } elseif ($order['payment_method'] === 'COD') {
            $methodText = '💵 เก็บเงินปลายทาง';
          } else {
            $methodText = htmlspecialchars($order['payment_method']);
          }

          // ✅ แปลงสถานะการชำระเงิน
          $status = $order['payment_status'] ?? 'รอดำเนินการ';
          if ($status === 'ชำระเงินแล้ว') {
            $badgeClass = 'success';
          } elseif ($status === 'ยกเลิก') {
            $badgeClass = 'danger';
          } else {
            $badgeClass = 'warning';
          }
          ?>

          <p><strong>วิธีชำระเงิน:</strong> <?= $methodText ?></p>
          <p><strong>สถานะการชำระเงิน:</strong>
            <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
          </p>

          <?php if ($status === 'รอดำเนินการ' && $order['payment_method'] === 'QR'): ?>
            <a href="payment_confirm.php?id=<?= $order_id ?>" class="btn btn-warning mt-2">
              💰 แจ้งชำระเงิน
            </a>
          <?php endif; ?>
        </div>

        <div class="col-md-6">
          <p><strong>ที่อยู่จัดส่ง:</strong><br><?= nl2br(htmlspecialchars($order['shipping_address'] ?? '-')) ?></p>
          <p><strong>ยอดรวมทั้งหมด:</strong> 
            <span class="text-danger fw-bold"><?= number_format($order['total_price'], 2) ?> บาท</span>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- 🔹 รายการสินค้า -->
  <div class="card shadow-sm border-0">
    <div class="card-header fw-semibold">รายการสินค้า</div>
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
              if (empty($d['p_image']) || !file_exists($imgPath)) {
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

  <!-- 🔹 ปุ่มยกเลิกคำสั่งซื้อ -->
  <div class="mt-5 d-flex justify-content-between align-items-center flex-wrap">
    <a href="orders.php" class="btn btn-secondary px-4 py-2 fw-semibold">
      ⬅️ กลับไปหน้าคำสั่งซื้อ
    </a>

    <?php if ($status === 'รอดำเนินการ'): ?>
      <a href="order_cancel.php?id=<?= $order_id ?>" 
         class="btn btn-danger px-4 py-2 fw-semibold"
         onclick="return confirm('แน่ใจหรือไม่ว่าต้องการยกเลิกคำสั่งซื้อนี้?');">
         ❌ ยกเลิกคำสั่งซื้อ
      </a>
    <?php endif; ?>
  </div>



<footer class="text-center py-3 mt-5 bg-dark text-white">
  © <?= date('Y') ?> MyCommiss | รายละเอียดคำสั่งซื้อ
</footer>

</body>
</html>
