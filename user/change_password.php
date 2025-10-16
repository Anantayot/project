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

$toast_type = ""; // สำหรับเก็บประเภทแจ้งเตือน
$toast_message = "";

// ✅ เมื่อกดเปลี่ยนรหัสผ่าน
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $old_pass = $_POST['old_password'];
  $new_pass = $_POST['new_password'];
  $confirm_pass = $_POST['confirm_password'];

  if (empty($old_pass) || empty($new_pass) || empty($confirm_pass)) {
    $toast_type = "danger";
    $toast_message = "❌ กรุณากรอกข้อมูลให้ครบทุกช่อง";
  } elseif ($new_pass !== $confirm_pass) {
    $toast_type = "danger";
    $toast_message = "❌ รหัสผ่านใหม่และยืนยันไม่ตรงกัน";
  } else {
    // ✅ ตรวจสอบรหัสผ่านเดิม
    if (password_verify($old_pass, $user['password'])) {
      // ✅ อัปเดตรหัสผ่านใหม่
      $hash_new = password_hash($new_pass, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("UPDATE customers SET password = ? WHERE customer_id = ?");
      $stmt->execute([$hash_new, $customer_id]);

      // ✅ redirect ไปหน้า profile พร้อมแจ้งเตือน
      $_SESSION['toast_success'] = "✅ เปลี่ยนรหัสผ่านเรียบร้อยแล้ว";
      header("Location: profile.php");
      exit;
    } else {
      $toast_type = "danger";
      $toast_message = "❌ รหัสผ่านเดิมไม่ถูกต้อง";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เปลี่ยนรหัสผ่าน | MyCommiss</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .password-card {
      max-width: 600px;
      margin: 50px auto;
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
    /* 🔔 Toast Notification */
    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1055;
    }
  </style>
</head>
<body>

<?php include("navbar_user.php"); ?>

<!-- 🔔 Toast Container -->
<div class="toast-container">
  <?php if (!empty($toast_message)): ?>
    <div class="toast align-items-center text-bg-<?= $toast_type ?> border-0 show" role="alert">
      <div class="d-flex">
        <div class="toast-body">
          <?= $toast_message ?>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  <?php endif; ?>
</div>

<div class="container">
  <div class="password-card">
    <div class="card-header text-center py-3">
      🔑 เปลี่ยนรหัสผ่าน
    </div>
    <div class="card-body p-4">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label fw-semibold">รหัสผ่านเดิม</label>
          <input type="password" name="old_password" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">รหัสผ่านใหม่</label>
          <input type="password" name="new_password" class="form-control" minlength="6" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">ยืนยันรหัสผ่านใหม่</label>
          <input type="password" name="confirm_password" class="form-control" minlength="6" required>
        </div>

        <div class="d-flex justify-content-center align-items-center gap-3 mt-4 flex-wrap">
          <a href="profile.php" class="btn btn-secondary">
            ⬅️ กลับโปรไฟล์
          </a>
          <button type="submit" class="btn btn-success">
            💾 เปลี่ยนรหัสผ่าน
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<footer class="text-center py-3 mt-5 bg-dark text-white">
  © <?= date('Y') ?> MyCommiss | เปลี่ยนรหัสผ่าน
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
