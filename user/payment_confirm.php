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

// ✅ ตรวจสอบว่ามี id คำสั่งซื้อหรือไม่
if (!isset($_GET['id'])) {
  die("<p class='text-center mt-5 text-danger'>❌ ไม่พบรหัสคำสั่งซื้อ</p>");
}

$order_id = intval($_GET['id']);

// ✅ ดึงข้อมูลคำสั่งซื้อ
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND customer_id = ?");
$stmt->execute([$order_id, $customer_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
  die("<p class='text-center mt-5 text-danger'>❌ ไม่พบคำสั่งซื้อนี้ หรือคุณไม่มีสิทธิ์ดู</p>");
}

/* =======================================================
   ✅ อัปโหลดสลิปและอัปเดตสถานะ
   ======================================================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $uploadDir = __DIR__ . "/../admin/uploads/slips/"; // ✅ เก็บใน admin/uploads/slips
  if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

  $fileName = "";
  if (!empty($_FILES['slip']['name'])) {
    $ext = pathinfo($_FILES['slip']['name'], PATHINFO_EXTENSION);
    $fileName = "slip_" . time() . "_" . rand(1000,9999) . "." . $ext;
    $targetFile = $uploadDir . $fileName;
    move_uploaded_file($_FILES['slip']['tmp_name'], $targetFile);
  }

  // ✅ ตั้งค่าสถานะใหม่
  $stmt = $conn->prepare("UPDATE orders 
                          SET payment_status = 'รอดำเนินการ', 
                              admin_verified = 'กำลังตรวจสอบ',
                              slip_image = :slip,
                              payment_date = NOW()
                          WHERE order_id = :oid AND customer_id = :cid");
  $stmt->execute([
    ':slip' => $fileName,
    ':oid' => $order_id,
    ':cid' => $customer_id
  ]);

  echo "<script>
    alert('📤 ส่งสลิปเรียบร้อยแล้ว! รอแอดมินตรวจสอบการชำระเงิน');
    window.location='orders.php';
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
    <div class="card-body text-center">
      <p><strong>วิธีชำระ:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>

      <form method="post" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3 text-start">
          <label for="slip" class="form-label">แนบสลิปการชำระเงิน</label>
          <input type="file" name="slip" id="slip" class="form-control" accept="image/*" required>
          <small class="text-muted">* ต้องแนบสลิปก่อนยืนยัน</small>
        </div>

        <div class="d-grid gap-2 mt-4">
          <button type="submit" class="btn btn-success">✅ ส่งสลิปเพื่อรอตรวจสอบ</button>
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
