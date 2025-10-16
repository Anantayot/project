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

// ✅ ฟังก์ชันสร้าง QR พร้อมเพย์ (ไม่ต้องใช้ Composer)
function generatePromptPay($promptPayID, $amount) {
  $mobile = preg_replace('/[^0-9]/', '', $promptPayID);
  $payload = "00020101021129370016A00000067701011101130066{$mobile}5802TH5303764540" . number_format($amount, 2, '.', '') . "6304";
  $crc = strtoupper(dechex(crc16($payload)));
  return $payload . $crc;
}

function crc16($data) {
  $crc = 0xFFFF;
  for ($i = 0; $i < strlen($data); $i++) {
    $crc ^= ord($data[$i]) << 8;
    for ($j = 0; $j < 8; $j++) {
      if ($crc & 0x8000)
        $crc = ($crc << 1) ^ 0x1021;
      else
        $crc <<= 1;
      $crc &= 0xFFFF;
    }
  }
  return $crc;
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

  // ✅ อัปเดตสถานะการชำระเงิน
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
  <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
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

      <?php if ($order['payment_method'] === 'QR'): ?>
        <div class="text-center my-4">
          <h5>📱 สแกน QR พร้อมเพย์ เพื่อชำระเงิน</h5>
          <div id="qrcode" class="border p-3 rounded d-inline-block bg-white"></div>
          <p class="mt-2 text-muted">ยอดชำระ <?= number_format($order['total_price'], 2) ?> บาท</p>
        </div>
        <script>
          const payload = "<?= generatePromptPay('0903262100', $order['total_price']) ?>";
          new QRCode(document.getElementById("qrcode"), {
            text: payload,
            width: 200,
            height: 200
          });
        </script>
      <?php endif; ?>

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
