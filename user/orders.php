<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("connectdb.php");

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$customer_id = $_SESSION['customer_id'];

// ✅ ดึงเฉพาะออเดอร์ของลูกค้าคนนี้ (เรียงจากใหม่ -> เก่า)
$sql = "SELECT * FROM orders WHERE customer_id = :cid ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':cid', $customer_id, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>MyCommiss | ประวัติคำสั่งซื้อ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff;
      font-family: "Prompt", sans-serif;
    }

    h3 {
      color: #D10024;
    }

    .table thead {
      background-color: #D10024;
      color: #fff;
    }

    .table th, .table td {
      vertical-align: middle !important;
    }

    .btn {
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.2s ease-in-out;
    }

    .btn:hover {
      transform: scale(1.05);
    }

    .btn-outline-primary {
      border-color: #D10024;
      color: #D10024;
    }

    .btn-outline-primary:hover {
      background-color: #D10024;
      color: #fff;
    }

    .btn-warning {
      background-color: #fbc02d;
      color: #000;
      border: none;
    }

    .btn-warning:hover {
      background-color: #f9a825;
    }

    .badge {
      font-size: 0.9rem;
      padding: 6px 10px;
    }

    .table-danger td {
      background-color: #ffe5e5 !important;
    }

    footer {
      background-color: #D10024;
      color: #fff;
      margin-top: 50px;
      padding: 15px;
      font-size: 0.9rem;
    }

    .alert-info {
      background-color: #fff5f5;
      border: 1px solid #D10024;
      color: #D10024;
    }

    .btn-primary {
      background-color: #D10024;
      border: none;
    }

    .btn-primary:hover {
      background-color: #a5001b;
    }
  </style>
</head>
<body>

<!-- ✅ Navbar -->
<?php include("navbar_user.php"); ?>

<!-- ✅ Toast แจ้งเตือน -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:3000;">
  <?php foreach (['success' => 'success', 'error' => 'danger'] as $key => $color): ?>
    <?php if (isset($_SESSION["toast_{$key}"])): ?>
      <div class="toast align-items-center text-bg-<?= $color ?> border-0 show" role="alert">
        <div class="d-flex">
          <div class="toast-body"><?= $_SESSION["toast_{$key}"] ?></div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>
      <?php unset($_SESSION["toast_{$key}"]); ?>
    <?php endif; ?>
  <?php endforeach; ?>
</div>

<!-- ✅ ส่วนเนื้อหา -->
<div class="container mt-4">
  <h3 class="fw-bold mb-4 text-center">📦 ประวัติคำสั่งซื้อของคุณ</h3>

  <?php if (empty($orders)): ?>
    <div class="alert alert-info text-center shadow-sm">
      😕 ยังไม่มีคำสั่งซื้อในระบบ<br>
      <a href="index.php" class="btn btn-primary mt-3">⬅️ กลับไปเลือกซื้อสินค้า</a>
    </div>
  <?php else: ?>
    <div class="table-responsive shadow-sm rounded">
      <table class="table align-middle table-bordered bg-white text-center">
        <thead>
          <tr>
            <th>ลำดับ</th>
            <th>วันที่สั่งซื้อ</th>
            <th>วิธีชำระเงิน</th>
            <th>ยอดรวม</th>
            <th>สถานะการชำระเงิน</th>
            <th>สถานะคำสั่งซื้อ</th>
            <th>จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $index = 1; 
          foreach ($orders as $o): 
            $status = $o['payment_status'] ?? 'รอดำเนินการ';
            $order_status = $o['order_status'] ?? 'รอดำเนินการ';
            $admin_verified = $o['admin_verified'] ?? 'รอตรวจสอบ';

            // ✅ สีของ payment_status
            if ($status === 'ชำระเงินแล้ว') {
              $badgeClass = 'success';
            } elseif ($status === 'ยกเลิก') {
              $badgeClass = 'danger';
            } else {
              $badgeClass = 'warning';
            }

            // ✅ สีของ order_status
            if ($order_status === 'จัดส่งแล้ว') {
              $orderBadge = 'success';
            } elseif ($order_status === 'กำลังจัดเตรียม') {
              $orderBadge = 'info';
            } elseif ($order_status === 'ยกเลิก') {
              $orderBadge = 'danger';
            } else {
              $orderBadge = 'secondary';
            }

            // ✅ แปลง payment_method
            $methodText = match ($o['payment_method']) {
              'QR' => '📱 ชำระด้วย QR Code',
              'COD' => '💵 เก็บเงินปลายทาง',
              default => htmlspecialchars($o['payment_method'])
            };

            // ✅ ถ้ายกเลิก → แถวแดงอ่อน
            $rowClass = ($order_status === 'ยกเลิก') ? 'table-danger' : '';
          ?>
            <tr class="<?= $rowClass ?>">
              <td>#<?= $index ?></td>
              <td><?= date('d/m/Y H:i', strtotime($o['order_date'])) ?></td>
              <td><?= $methodText ?></td>
              <td class="fw-semibold text-danger"><?= number_format($o['total_price'], 2) ?> บาท</td>
              <td><span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span></td>
              <td><span class="badge bg-<?= $orderBadge ?>"><?= htmlspecialchars($order_status) ?></span></td>
              <td>
                <div class="d-flex justify-content-center flex-wrap gap-2">
                  <?php
                  if (
                    $o['payment_method'] === 'QR' &&
                    $status === 'รอดำเนินการ' &&
                    !in_array($admin_verified, ['กำลังตรวจสอบ', 'อนุมัติ'])
                  ):
                  ?>
                    <a href="payment_confirm.php?id=<?= $o['order_id'] ?>" class="btn btn-sm btn-warning">
                      💰 แจ้งชำระเงิน
                    </a>
                  <?php endif; ?>

                  <a href="order_detail.php?id=<?= $o['order_id'] ?>" class="btn btn-sm btn-outline-primary">
                    🔍 ดูรายละเอียด
                  </a>
                </div>
              </td>
            </tr>
          <?php 
          $index++; 
          endforeach; 
          ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<!-- ✅ Footer -->
<footer class="text-center">
  © <?= date('Y') ?> MyCommiss | ประวัติคำสั่งซื้อ
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const toastElList = [].slice.call(document.querySelectorAll('.toast'));
  toastElList.forEach(toastEl => {
    const toast = new bootstrap.Toast(toastEl, { delay: 4000, autohide: true });
    toast.show();
  });
});
</script>

</body>
</html>
