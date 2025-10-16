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

$sql = "SELECT * FROM orders WHERE customer_id = :cid ORDER BY order_date ASC";
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
</head>
<body class="bg-light">

<?php include("navbar_user.php"); ?>

<div class="container mt-4">
  <h3 class="fw-bold mb-4 text-center">📦 ประวัติคำสั่งซื้อของคุณ</h3>

  <?php if (empty($orders)): ?>
    <div class="alert alert-info text-center shadow-sm">
      😕 ยังไม่มีคำสั่งซื้อในระบบ<br>
      <a href="index.php" class="btn btn-primary mt-3">⬅️ กลับไปเลือกซื้อสินค้า</a>
    </div>
  <?php else: ?>
    <div class="table-responsive shadow-sm rounded">
      <table class="table align-middle table-bordered bg-white">
        <thead class="table-dark text-center">
          <tr>
            <th>รหัสคำสั่งซื้อ</th>
            <th>วันที่สั่งซื้อ</th>
            <th>วิธีชำระเงิน</th>
            <th>ยอดรวม</th>
            <th>สถานะการชำระเงิน</th>
            <th>การจัดการ</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <?php 
            $index = 1; // ✅ ลำดับคำสั่งซื้อของลูกค้าคนนี้
            foreach ($orders as $o): 
              $status = $o['payment_status'] ?? 'รอดำเนินการ';
              if ($status === 'ชำระเงินแล้ว') {
                $badgeClass = 'success';
              } elseif ($status === 'ยกเลิก') {
                $badgeClass = 'danger';
              } else {
                $badgeClass = 'warning';
              }

              // ✅ แปลง payment_method เป็นภาษาไทย
              if ($o['payment_method'] === 'QR') {
                $methodText = '💳 ชำระด้วยคิวอาร์โค้ด';
              } elseif ($o['payment_method'] === 'COD') {
                $methodText = '💵 เก็บเงินปลายทาง';
              } else {
                $methodText = htmlspecialchars($o['payment_method']);
              }
              
          ?>
            <tr>
              <td>#<?= $index ?></td>
              <td><?= date('d/m/Y H:i', strtotime($o['order_date'])) ?></td>
              <td><?= $methodText ?></td>
              <td><?= number_format($o['total_price'], 2) ?> บาท</td>
              <td><span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span></td>
              <td>
                <?php if ($status === 'รอดำเนินการ' && $o['payment_method'] === 'QR'): ?>
                  <a href="payment_confirm.php?id=<?= $o['order_id'] ?>" class="btn btn-sm btn-warning">
                    💰 แจ้งชำระเงิน
                  </a>
                <?php else: ?>
                  <a href="order_detail.php?id=<?= $o['order_id'] ?>" class="btn btn-sm btn-outline-primary">
                    🔍 ดูรายละเอียด
                  </a>
                <?php endif; ?>
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

  <div class="text-center mt-4">
    <a href="index.php" class="btn btn-secondary">⬅️ กลับหน้าหลัก</a>
  </div>
</div>

<footer class="text-center py-3 mt-5 bg-dark text-white">
  © <?= date('Y') ?> MyCommiss | ประวัติคำสั่งซื้อ
</footer>

</body>
</html>
