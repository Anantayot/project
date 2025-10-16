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

// ✅ ดึงข้อมูลคำสั่งซื้อของลูกค้าคนนั้น
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND customer_id = ?");
$stmt->execute([$order_id, $customer_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
  die("<p class='text-center mt-5 text-danger'>❌ ไม่พบคำสั่งซื้อนี้ หรือคุณไม่มีสิทธิ์ดู</p>");
}

// ✅ เมื่อกดปุ่มยืนยันการชำระเงิน
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $uploadDir = "uploads/slips/";
  if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

  $fileName = "";
  if (!empty($_FILES['slip']['name'])) {
    $ext = pathinfo($_FILES['slip']['name'], PATHINFO_EXTENSION);
    $fileName = "slip_" . time() . "_" . rand(1000,9999) . "." . $ext;
    $targetFile = $uploadDir . $fileName;
    move_uploaded_file($_FILES['slip']['tmp_name'], $targetFile);
  }

  // ✅ อัปเดตสถานะการชำระเงิน (แม้ไม่มีสลิป)
  $stmt = $conn->prepare("UPDATE orders 
                          SET payment_status = 'ชำระเงินแล้ว', 
                              slip_image = :slip,
                              payment_date = NOW()
                          WHERE order_id = :oid AND customer_id = :cid");
  $stmt->execute([
    ':slip' => $fileName,
    ':oid' => $order_id,
    ':cid' => $customer_id
  ]);

  echo "<script>
    alert('✅ ยืนยันการชำระเงินเรียบร้อยแล้ว!');
    window.location='order_detail.php?id=$order_id';
  </script>";
  exit;
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

<div class="container mt-4">
  <div class="card shadow-lg border-0 mx-auto" style="max-width:600px;">
    <div class="card-header bg-dark text-white text-center fw-bold">
      💰 แจ้งชำระเงินคำสั่งซื้อ #<?= $order_id ?>
    </div>
    <div class="card-body">
      <p><strong>ยอดที่ต้องชำระ:</strong> <?= number_format($order['total_price'], 2) ?> บาท</p>
      <p><strong>วิธีชำระ:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>

      <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="slip" class="form-label">แนบสลิปการชำระเงิน (ถ้ามี)</label>
          <input type="file" name="slip" id="slip" class="form-control" accept="image/*">
          <small class="text-muted">* สามารถกดยืนยันโดยไม่ต้องแนบสลิปได้</small>
        </div>

        <div class="d-grid gap-2 mt-4">
          <button type="submit" class="btn btn-success">✅ ยืนยันการชำระเงิน</button>
          <a href="orders.php" class="btn btn-secondary">⬅️ กลับไปหน้าคำสั่งซื้อ</a>
          <a href="order_detail.php?id=<?= $order_id ?>" class="btn btn-outline-primary">🔍 ดูรายละเอียดสินค้า</a>
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
