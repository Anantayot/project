<?php
session_start();
include("connectdb.php");

// 🔒 ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$customer_id = $_SESSION['customer_id'];

// ✅ ดึงข้อมูลผู้ใช้
$stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id = ?");
$stmt->execute([$customer_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  die("<p class='text-center text-danger mt-5'>❌ ไม่พบข้อมูลผู้ใช้</p>");
}

// ✅ อัปเดตข้อมูลเมื่อกดบันทึก
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $address = trim($_POST['address']);

  $stmt = $conn->prepare("UPDATE customers 
                          SET name = ?, email = ?, phone = ?, address = ? 
                          WHERE customer_id = ?");
  $stmt->execute([$name, $email, $phone, $address, $customer_id]);

  $msg = "✅ บันทึกข้อมูลเรียบร้อยแล้ว";
  
  // อัปเดต session เผื่อมีการเปลี่ยนชื่อ
  $_SESSION['customer_name'] = $name;

  // โหลดข้อมูลใหม่
  $stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id = ?");
  $stmt->execute([$customer_id]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>โปรไฟล์ของฉัน | MyCommiss</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .profile-card {
      max-width: 700px;
      margin: 40px auto;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .card-header {
      background: #212529;
      color: #fff;
      font-weight: bold;
      border-radius: 15px 15px 0 0;
    }
    .btn:hover { transform: scale(1.05); transition: 0.2s; }
  </style>
</head>
<body>

<?php include("navbar_user.php"); ?>

<div class="container">
  <div class="profile-card">
    <div class="card-header text-center py-3">
      👤 โปรไฟล์ของฉัน
    </div>
    <div class="card-body p-4">
      <?php if (!empty($msg)): ?>
        <div class="alert alert-success text-center"><?= $msg ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label fw-semibold">ชื่อ - นามสกุล</label>
          <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">อีเมล</label>
          <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">เบอร์โทรศัพท์</label>
          <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">ที่อยู่จัดส่ง</label>
          <textarea name="address" rows="3" class="form-control"><?= htmlspecialchars($user['address']) ?></textarea>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
          <a href="index.php" class="btn btn-secondary">⬅️ กลับหน้าหลัก</a>
          <button type="submit" class="btn btn-primary">💾 บันทึกข้อมูล</button>
        </div>
      </form>

      <hr class="my-4">

      <div class="text-center">
        <a href="change_password.php" class="btn btn-outline-danger btn-sm">🔑 เปลี่ยนรหัสผ่าน</a>
      </div>
    </div>
  </div>
</div>

<footer class="text-center py-3 mt-5 bg-dark text-white">
  © <?= date('Y') ?> MyCommiss | โปรไฟล์ผู้ใช้
</footer>

</body>
</html>
