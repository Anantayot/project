<?php
$pageTitle = "รายละเอียดคำสั่งซื้อ";
ob_start();

include __DIR__ . "/../partials/connectdb.php";

$id = $_GET['id'] ?? null;
if(!$id) die("❌ ไม่พบคำสั่งซื้อ");

// ดึงข้อมูลคำสั่งซื้อ + ลูกค้า
$sql = "SELECT o.*, c.name AS customer_name, c.phone, c.address
        FROM orders o
        LEFT JOIN customers c ON o.customer_id = c.customer_id
        WHERE o.order_id=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$order) die("❌ ไม่พบข้อมูลคำสั่งซื้อในฐานข้อมูล");

// ดึงรายละเอียดสินค้าในออเดอร์
$details = $conn->prepare("SELECT d.*, p.p_name, p.p_image 
                           FROM order_details d
                           LEFT JOIN product p ON d.p_id = p.p_id
                           WHERE d.order_id=?");
$details->execute([$id]);
$items = $details->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 class="mb-4 text-center fw-bold text-white">
  <i class="bi bi-receipt"></i> รายละเอียดคำสั่งซื้อ #<?= htmlspecialchars($order['order_id']) ?>
</h3>

<!-- 🔹 ข้อมูลลูกค้า -->
<div class="card p-4 shadow-lg border-0 mb-4" style="background: linear-gradient(145deg,#161b22,#0e1116);color:#fff;">
  <div class="row">
    <div class="col-md-6">
      <h5 class="fw-bold text-success"><i class="bi bi-person-circle"></i> ข้อมูลลูกค้า</h5>
      <p><b>ชื่อ:</b> <?= htmlspecialchars($order['customer_name']) ?></p>
      <p><b>เบอร์โทร:</b> <?= htmlspecialchars($order['phone']) ?></p>
      <p><b>ที่อยู่:</b> <?= htmlspecialchars($order['address']) ?></p>
    </div>
    <div class="col-md-6">
      <h5 class="fw-bold text-info"><i class="bi bi-clipboard-data"></i> ข้อมูลคำสั่งซื้อ</h5>
      <p><b>วันที่สั่งซื้อ:</b> <?= date("d/m/Y", strtotime($order['order_date'])) ?></p>
      <p><b>สถานะชำระเงิน:</b>
        <span class="badge bg-<?= ($order['payment_status']=='ชำระแล้ว'?'success':'warning') ?>">
          <?= htmlspecialchars($order['payment_status']) ?>
        </span>
      </p>
      <p><b>สถานะคำสั่งซื้อ:</b>
        <?php 
          $status = $order['order_status'] ?? 'รอดำเนินการ';
          if ($status == 'เสร็จสิ้น') {
              $statusColor = 'success';
          } elseif ($status == 'กำลังดำเนินการ') {
              $statusColor = 'warning';
          } elseif ($status == 'ยกเลิก') {
              $statusColor = 'danger';
          } else {
              $statusColor = 'secondary';
          }
        ?>
        <span class="badge bg-<?= $statusColor ?>">
          <?= htmlspecialchars($status) ?>
        </span>
      </p>
    </div>
  </div>
</div>

<!-- 🔹 รายการสินค้า -->
<div class="card p-3 shadow-lg border-0" style="background:#161b22;">
  <h5 class="fw-bold text-white mb-3"><i class="bi bi-basket2"></i> รายการสินค้า</h5>
  <div class="table-responsive">
    <table class="table table-dark table-striped align-middle text-center mb-0">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>รูป</th>
          <th>สินค้า</th>
          <th>จำนวน</th>
          <th>ราคา (฿)</th>
          <th>รวม (฿)</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $totalSum = 0;
        foreach ($items as $i => $it): 
          $totalSum += $it['subtotal'];
        ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td>
            <img src="/../uploads/<?= htmlspecialchars($it['p_image'] ?? 'noimg.png') ?>" 
                 width="50" class="rounded">
          </td>
          <td class="text-start"><?= htmlspecialchars($it['p_name']) ?></td>
          <td><?= (int)$it['quantity'] ?></td>
          <td><?= number_format($it['price'], 2) ?></td>
          <td><?= number_format($it['subtotal'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- 🔹 ยอดรวม -->
<div class="text-end mt-4">
  <h4 class="fw-bold text-success">
    <i class="bi bi-cash-stack"></i> ยอดรวมทั้งหมด: <?= number_format($totalSum, 2) ?> ฿
  </h4>
  <a href="orders.php" class="btn btn-secondary mt-3">
    <i class="bi bi-arrow-left-circle"></i> กลับ
  </a>
</div>

<?php
$pageContent = ob_get_clean();
include __DIR__ . "/../partials/layout.php";
?>
