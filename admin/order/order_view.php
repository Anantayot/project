<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$pageTitle = "รายละเอียดคำสั่งซื้อ";
ob_start();

include __DIR__ . "/../partials/connectdb.php"; // ✅ ปรับ path ให้ถูกกับโครงสร้างของคุณ

$id = $_GET['id'] ?? null;
if(!$id) die("❌ ไม่พบคำสั่งซื้อ");

// ✅ อนุมัติ / ปฏิเสธ
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $action = $_POST['action'] ?? '';
  if ($action === 'approve') {
    $stmt = $conn->prepare("UPDATE orders 
                            SET payment_status='ชำระเงินแล้ว', 
                                admin_verified='อนุมัติ',
                                order_status='กำลังดำเนินการ'
                            WHERE order_id=?");
    $stmt->execute([$id]);
    echo "<script>alert('✅ อนุมัติการชำระเงินเรียบร้อยแล้ว');window.location='order_view.php?id=$id';</script>";
    exit;
  } elseif ($action === 'reject') {
    $stmt = $conn->prepare("UPDATE orders 
                            SET payment_status='ยกเลิก', 
                                admin_verified='ปฏิเสธ',
                                order_status='ยกเลิก'
                            WHERE order_id=?");
    $stmt->execute([$id]);
    echo "<script>alert('❌ ปฏิเสธคำสั่งซื้อนี้แล้ว');window.location='order_view.php?id=$id';</script>";
    exit;
  }
}

// ✅ ดึงข้อมูลคำสั่งซื้อ
$sql = "SELECT o.*, c.name AS customer_name, c.phone, c.address
        FROM orders o
        LEFT JOIN customers c ON o.customer_id = c.customer_id
        WHERE o.order_id=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$order) die("❌ ไม่พบข้อมูลคำสั่งซื้อในฐานข้อมูล");

// ✅ ดึงรายละเอียดสินค้า
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

<div class="card p-4 shadow-lg border-0 mb-4" style="background:linear-gradient(145deg,#161b22,#0e1116);color:#fff;">
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

      <!-- ✅ ช่องทางการชำระเงิน -->
      <?php 
        $method = $order['payment_method'];
        $methodText = ($method === 'QR') ? 'ชำระด้วย QR Code' :
                      (($method === 'COD') ? 'เก็บเงินปลายทาง' : htmlspecialchars($method));
      ?>
      <p><b>ช่องทางการชำระเงิน:</b> <?= $methodText ?></p>

      <p><b>สถานะชำระเงิน:</b>
        <span class="badge bg-<?= ($order['payment_status']=='ชำระเงินแล้ว'?'success':($order['payment_status']=='ยกเลิก'?'danger':'warning')) ?>">
          <?= htmlspecialchars($order['payment_status']) ?>
        </span>
      </p>

      <!-- ✅ ตรวจสอบโดยแอดมิน (เฉพาะถ้าไม่ใช่ COD) -->
      <?php if ($order['payment_method'] !== 'COD'): ?>
      <p><b>ตรวจสอบโดยแอดมิน:</b>
        <span class="badge bg-<?= ($order['admin_verified']=='อนุมัติ'?'success':($order['admin_verified']=='ปฏิเสธ'?'danger':($order['admin_verified']=='กำลังตรวจสอบ'?'info':'secondary'))) ?>">
          <?= htmlspecialchars($order['admin_verified'] ?? 'รอตรวจสอบ') ?>
        </span>
      </p>
      <?php endif; ?>

      <p><b>สถานะคำสั่งซื้อ:</b>
        <?php 
          $status = $order['order_status'] ?? 'รอดำเนินการ';
          if ($status == 'เสร็จสิ้น') $statusColor = 'success';
          elseif ($status == 'กำลังดำเนินการ') $statusColor = 'warning';
          elseif ($status == 'ยกเลิก') $statusColor = 'danger';
          else $statusColor = 'secondary';
        ?>
        <span class="badge bg-<?= $statusColor ?>"><?= htmlspecialchars($status) ?></span>
      </p>

      <!-- 🔹 สลิป (เฉพาะ QR) -->
      <?php if (!empty($order['slip_image']) && $order['payment_method'] !== 'COD'): ?>
        <p><b>หลักฐานการชำระเงิน:</b></p>
        <a href="../../user/uploads/slips/<?= htmlspecialchars($order['slip_image']) ?>" 
           target="_blank" class="btn btn-outline-light btn-sm">
          🧾 ดูรูปสลิป
        </a>
      <?php elseif ($order['payment_method'] !== 'COD'): ?>
        <p class="text-muted"><i>ยังไม่มีสลิปอัปโหลด</i></p>
      <?php endif; ?>
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
          <td><img src="../../user/uploads/<?= htmlspecialchars($it['p_image'] ?? 'noimg.png') ?>" width="50" class="rounded"></td>
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

<!-- 🔹 ยอดรวม + ปุ่มจัดการ -->
<div class="text-end mt-4">
  <h4 class="fw-bold text-success">
    <i class="bi bi-cash-stack"></i> ยอดรวมทั้งหมด: <?= number_format($totalSum, 2) ?> ฿
  </h4>

  <?php if (trim($order['admin_verified']) === 'กำลังตรวจสอบ' && $order['payment_method'] !== 'COD'): ?>
    <form method="post" class="mt-3 d-inline">
      <button type="submit" name="action" value="approve" class="btn btn-success"
              onclick="return confirm('ยืนยันการอนุมัติคำสั่งซื้อนี้หรือไม่?');">
        ✅ อนุมัติการชำระเงิน
      </button>
    </form>

    <form method="post" class="mt-3 d-inline">
      <button type="submit" name="action" value="reject" class="btn btn-danger"
              onclick="return confirm('ต้องการปฏิเสธคำสั่งซื้อนี้หรือไม่?');">
        ❌ ปฏิเสธคำสั่งซื้อ
      </button>
    </form>
  <?php endif; ?>

  <a href="orders.php" class="btn btn-secondary mt-3">
    <i class="bi bi-arrow-left-circle"></i> กลับ
  </a>
</div>

<?php
$pageContent = ob_get_clean();
include __DIR__ . "/../partials/layout.php";
?>
