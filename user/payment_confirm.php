<?php
session_start();
include("connectdb.php");

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$cid = $_SESSION['customer_id'];

if (!isset($_GET['id'])) {
  die("<p class='text-center mt-5 text-danger'>❌ ไม่พบรหัสคำสั่งซื้อ</p>");
}

$orderId = intval($_GET['id']);

// ✅ ดึงข้อมูลคำสั่งซื้อของลูกค้าคนนี้
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND customer_id = ?");
$stmt->execute([$orderId, $cid]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
  die("<p class='text-center mt-5 text-danger'>❌ ไม่พบคำสั่งซื้อนี้</p>");
}

// ✅ เมื่อกดยืนยันการชำระเงิน
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $stmtUpdate = $conn->prepare("UPDATE orders SET payment_status = 'ชำระเงินแล้ว' WHERE order_id = ?");
    $stmtUpdate->execute([$orderId]);
    echo "<script>alert('✅ ยืนยันการชำระเงินเรียบร้อยแล้ว'); window.location='orders.php';</script>";
    exit;
  } catch (Exception $e) {
    echo "<script>alert('❌ เกิดข้อผิดพลาด: " . addslashes($e->getMessage()) . "');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>แจ้งชำระเงิน | MyCommiss</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include("navbar_user.php"); ?>

<div class="container mt-5">
  <div class="card shadow-sm mx-auto" style="max-width:600px;">
    <div class="card-header bg-dark text-white fw-semibold">💰 แจ้งชำระเงินคำสั่งซื้อ #<?= $orderId ?></div>
    <div class="card-body">
      <p><strong>ยอดที่ต้องชำระ:</strong> <?= number_format($order['total_price'], 2) ?> บาท</p>
      <p><strong>วิธีชำระ:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>

      <form method="post">
        <div class="mb-3">
          <label class="form-label">แนบสลิปชำระเงิน (ถ้ามี)</label>
          <input type="file" class="form-control" name="slip" accept="image/*" disabled>
          <small class="text-muted">* ฟีเจอร์แนบไฟล์สามารถเพิ่มภายหลังได้</small>
        </div>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-success">✅ ยืนยันการชำระเงิน</button>
          <a href="orders.php" class="btn btn-secondary">⬅️ กลับไปหน้าคำสั่งซื้อ</a>
        </div>
      </form>
    </div>
  </div>
</div>

<footer class="text-center py-3 mt-5 bg-dark text-white">
  © <?= date('Y') ?> MyCommiss | แจ้งชำระเงิน
</footer>

</body>
</html>
