<?php
session_start();
include "partials/connectdb.php";

if (!isset($_SESSION['customer'])) {
  header("Location: login.php");
  exit;
}

$id = $_GET['id'] ?? null;
if (!$id) die("❌ ไม่พบคำสั่งซื้อ");

$customer = $_SESSION['customer'];

// ตรวจสอบว่าออเดอร์นี้เป็นของลูกค้าคนนี้จริง
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND customer_id = ?");
$stmt->execute([$id, $customer['customer_id']]);
$order = $stmt->fetch();

if (!$order) {
  die("❌ ไม่พบคำสั่งซื้อของคุณ");
}

// ดึงรายละเอียดสินค้าในคำสั่งซื้อนี้
$details = $conn->prepare("SELECT d.*, p.p_name, p.p_image 
                           FROM order_details d
                           LEFT JOIN product p ON d.p_id = p.p_id
                           WHERE d.order_id = ?");
$details->execute([$id]);
$items = $details->fetchAll();
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>รายละเอียดคำสั่งซื้อ #<?= $order['order_id'] ?> | MyCommiss</title>

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="css/style.css">

<style>
body { background: #f8f8f8; font-family: 'Montserrat', sans-serif; }
.order-box {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  padding: 30px;
  max-width: 900px;
  margin: 40px auto;
}
.order-header h3 {
  font-weight: 700;
  color: #D10024;
}
.table thead { background: #D10024; color: #fff; }
.badge-success { background: #28a745; }
.badge-warning { background: #ffc107; color: #000; }
.badge-danger { background: #dc3545; }
.btn-red {
  background: #D10024;
  color: #fff;
  border-radius: 6px;
  padding: 8px 15px;
  border: none;
  transition: 0.3s;
}
.btn-red:hover { background: #b8001f; transform: translateY(-1px); }
.product-img {
  width: 60px; height: 60px;
  object-fit: cover; border-radius: 8px;
}
</style>
</head>

<body>
<header class="text-center py-3 bg-dark text-white">
  <h4><i class="fa fa-receipt"></i> รายละเอียดคำสั่งซื้อ</h4>
</header>

<div class="order-box">
  <div class="order-header text-center mb-4">
    <h3>คำสั่งซื้อ #<?= $order['order_id'] ?></h3>
    <p>วันที่สั่งซื้อ: <?= date("d/m/Y", strtotime($order['order_date'])) ?></p>
    <p>
      <b>สถานะคำสั่งซื้อ:</b>
      <?php
        if ($order['order_status'] === 'เสร็จสิ้น')
          echo '<span class="badge badge-success">เสร็จสิ้น</span>';
        elseif ($order['order_status'] === 'กำลังดำเนินการ')
          echo '<span class="badge badge-warning">กำลังดำเนินการ</span>';
        else
          echo '<span class="badge badge-danger">ยกเลิก</span>';
      ?>
    </p>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered align-middle text-center">
      <thead>
        <tr>
          <th>#</th>
          <th>สินค้า</th>
          <th>จำนวน</th>
          <th>ราคา (฿)</th>
          <th>รวม (฿)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $i => $it): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td class="text-start">
            <img src="assets/img/<?= htmlspecialchars($it['p_image'] ?? 'noimage.jpg') ?>" class="product-img me-2">
            <?= htmlspecialchars($it['p_name']) ?>
          </td>
          <td><?= $it['quantity'] ?></td>
          <td><?= number_format($it['price'], 2) ?></td>
          <td><?= number_format($it['subtotal'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="text-end mt-4">
    <h4 class="text-success">ยอดรวมทั้งหมด: <?= number_format($order['total_price'], 2) ?> ฿</h4>
    <a href="profile.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> กลับ</a>
  </div>
</div>

<footer class="text-center mt-5 text-muted">
  <p>© <?= date("Y") ?> MyCommiss | All Rights Reserved</p>
</footer>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
