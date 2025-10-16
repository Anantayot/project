<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("connectdb.php");

// ✅ ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$customer_id = $_SESSION['customer_id'];

// ✅ ตรวจสอบว่ามี id หรือไม่
if (!isset($_GET['id'])) {
  die("<p class='text-center mt-5 text-danger'>❌ ไม่พบรหัสคำสั่งซื้อ</p>");
}

$order_id = intval($_GET['id']);

// ✅ ดึงข้อมูลคำสั่งซื้อของลูกค้าคนนี้
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND customer_id = ?");
$stmt->execute([$order_id, $customer_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
  die("<p class='text-center mt-5 text-danger'>❌ ไม่พบคำสั่งซื้อนี้ หรือคุณไม่มีสิทธิ์ดู</p>");
}

// ✅ ดึงรายการสินค้าในคำสั่งซื้อ
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
    .toast-container { position: fixed; top: 20px; right: 20px; z-index: 3000; }
  </style>
</head>
<body class="bg-light">

<?php include("navbar_user.php"); ?>

<!-- ✅ Toast แจ้งเตือน -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:3000;">
  <?php if (isset($_SESSION['toast_success'])): ?>
    <div class="toast align-items-center text-bg-success border-0 show" role="alert">
      <div class="d-flex">
        <div class="toast-body"><?= $_SESSION['toast_success'] ?></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
    <?php unset($_SESSION['toast_success']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['toast_error'])): ?>
    <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
      <div class="d-flex">
        <div class="toast-body"><?= $_SESSION['toast_error'] ?></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
    <?php unset($_SESSION['toast_error']); ?>
  <?php endif; ?>
</div>

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
          // ✅ แปลงชื่อวิธีชำระเงิน
          $methodText = ($order['payment_method'] === 'QR') ? 'ชำระด้วย QR Code' :
                        (($order['payment_method'] === 'COD') ? 'เก็บเงินปลายทาง' :
                        htmlspecialchars($order['payment_method']));

          // ✅ สีของสถานะ
          $payment_status = $order['payment_status'] ?? 'รอดำเนินการ';
          $order_status = $order['order_status'] ?? 'รอดำเนินการ';
          $admin_verified = $order['admin_verified'] ?? 'รอตรวจสอบ';

          $paymentBadge = ($payment_status === 'ชำระเงินแล้ว') ? 'success' :
                          (($payment_status === 'ยกเลิก') ? 'danger' : 'warning');
          $orderBadge = ($order_status === 'จัดส่งแล้ว') ? 'success' :
                        (($order_status === 'กำลังจัดเตรียม') ? 'info' :
                        (($order_status === 'ยกเลิก') ? 'danger' : 'secondary'));

          // ✅ สีของ admin_verified
          $adminBadge = ($admin_verified === 'อนุมัติ') ? 'success' :
                        (($admin_verified === 'ปฏิเสธ') ? 'danger' : 'warning text-dark');
          ?>

          <p><strong>วิธีชำระเงิน:</strong> <?= $methodText ?></p>
          <p><strong>สถานะการชำระเงิน:</strong>
            <span class="badge bg-<?= $paymentBadge ?>"><?= htmlspecialchars($payment_status) ?></span>
          </p>
          <p><strong>สถานะคำสั่งซื้อ:</strong>
            <span class="badge bg-<?= $orderBadge ?>"><?= htmlspecialchars($order_status) ?></span>
          </p>
          <p><strong>สถานะตรวจสอบโดยแอดมิน:</strong>
            <span class="badge bg-<?= $adminBadge ?>"><?= htmlspecialchars($admin_verified) ?></span>
          </p>

          <?php if (!empty($order['shipped_date'])): ?>
            <p><strong>วันที่จัดส่ง:</strong> <?= date('d/m/Y H:i', strtotime($order['shipped_date'])) ?></p>
          <?php endif; ?>

          <?php if (!empty($order['tracking_number'])): ?>
            <p><strong>หมายเลขพัสดุ:</strong> 📦 <?= htmlspecialchars($order['tracking_number']) ?></p>
          <?php endif; ?>

          <?php if ($payment_status === 'รอดำเนินการ' && $order['payment_method'] === 'QR'): ?>
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

  <!-- 🔹 ปุ่ม -->
  <div class="d-flex justify-content-between mt-4">
    <a href="orders.php" class="btn btn-secondary">⬅️ กลับไปหน้าคำสั่งซื้อ</a>
    <?php if ($order_status === 'รอดำเนินการ' && $payment_status !== 'ยกเลิก'): ?>
      <a href="order_cancel.php?id=<?= $order_id ?>" 
         class="btn btn-danger"
         onclick="return confirm('แน่ใจหรือไม่ว่าต้องการยกเลิกคำสั่งซื้อนี้?');">
         ❌ ยกเลิกคำสั่งซื้อ
      </a>
    <?php endif; ?>
  </div>
</div>

<footer class="text-center py-3 mt-5 bg-dark text-white">
  © <?= date('Y') ?> MyCommiss | รายละเอียดคำสั่งซื้อ
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const toastElList = [].slice.call(document.querySelectorAll('.toast'));
  toastElList.forEach(toastEl => {
    const toast = new bootstrap.Toast(toastEl, { delay: 5000, autohide: true });
    toast.show();
  });
});
</script>

</body>
</html>
