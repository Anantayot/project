<?php
include("connectdb.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $confirm = trim($_POST['confirm']);
  $phone = trim($_POST['phone']);
  $address = trim($_POST['address']);

  // ✅ ตรวจสอบเบอร์โทรศัพท์ (ต้องเป็นตัวเลข 10 หลัก)
  if (!preg_match('/^[0-9]{10}$/', $phone)) {
    $_SESSION['toast_error'] = "⚠️ กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง (10 หลัก)";
    header("Location: register.php");
    exit;
  }

  if ($password !== $confirm) {
    $_SESSION['toast_error'] = "❌ รหัสผ่านไม่ตรงกัน";
    header("Location: register.php");
    exit;
  }

  // ✅ ตรวจสอบอีเมลซ้ำ
  $check = $conn->prepare("SELECT * FROM customers WHERE email = ?");
  $check->execute([$email]);
  if ($check->rowCount() > 0) {
    $_SESSION['toast_error'] = "⚠️ อีเมลนี้ถูกใช้ไปแล้ว";
    header("Location: register.php");
    exit;
  }

  // ✅ เข้ารหัสรหัสผ่าน
  $hashed = password_hash($password, PASSWORD_DEFAULT);

  // ✅ บันทึกลงฐานข้อมูล
  $stmt = $conn->prepare("
    INSERT INTO customers (name, email, password, phone, address)
    VALUES (:name, :email, :password, :phone, :address)
  ");
  $stmt->execute([
    ':name' => $name,
    ':email' => $email,
    ':password' => $hashed,
    ':phone' => $phone,
    ':address' => $address
  ]);

  // ✅ Toast สำเร็จ
  $_SESSION['toast_success'] = "✅ สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ";
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>สมัครสมาชิก | MyCommiss</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- 🔔 Toast แจ้งเตือน -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:3000;">
  <?php if (isset($_SESSION['toast_success'])): ?>
    <div class="toast align-items-center text-bg-success border-0 show" role="alert">
      <div class="d-flex">
        <div class="toast-body"><?= $_SESSION['toast_success'] ?></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
    <?php unset($_SESSION['toast_success']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['toast_error'])): ?>
    <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
      <div class="d-flex">
        <div class="toast-body"><?= $_SESSION['toast_error'] ?></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
    <?php unset($_SESSION['toast_error']); ?>
  <?php endif; ?>
</div>

<div class="container mt-5" style="max-width:600px;">
  <div class="card shadow border-0">
    <div class="card-header bg-dark text-white text-center fw-bold">สมัครสมาชิก</div>
    <div class="card-body">
      <form method="post">
        <div class="mb-3">
          <label class="form-label">ชื่อ-นามสกุล</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">อีเมล</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">รหัสผ่าน</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">ยืนยันรหัสผ่าน</label>
            <input type="password" name="confirm" class="form-control" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">เบอร์โทรศัพท์</label>
          <input type="text" name="phone" class="form-control" maxlength="10" pattern="\d{10}" 
                 title="กรุณากรอกเบอร์โทรศัพท์ 10 หลัก" required>
        </div>

        <div class="mb-3">
          <label class="form-label">ที่อยู่</label>
          <textarea name="address" class="form-control" rows="3"></textarea>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-success">✅ สมัครสมาชิก</button>
        </div>
      </form>
    </div>
  </div>

  <div class="text-center mt-3">
    <a href="login.php" class="text-decoration-none">มีบัญชีอยู่แล้ว? เข้าสู่ระบบ</a><br>
    <a href="index.php" class="btn btn-secondary btn-sm mt-2">⬅️ กลับหน้าหลัก</a>
  </div>
</div>

<footer class="text-center py-3 mt-5 bg-dark text-white">
  © <?= date('Y') ?> MyCommiss | สมัครสมาชิก
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const toastElList = [].slice.call(document.querySelectorAll('.toast'));
  toastElList.forEach(toastEl => {
    const toast = new bootstrap.Toast(toastEl, { delay: 5000, autohide: true });
    toast.show();
  });
});
</script>

</body>
</html>
