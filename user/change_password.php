<?php
session_start();
include("connectdb.php");

// 🔒 ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$customer_id = $_SESSION['customer_id'];
$msg = "";

// ✅ เมื่อผู้ใช้กดเปลี่ยนรหัสผ่าน
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $current = $_POST['current_password'];
  $new = $_POST['new_password'];
  $confirm = $_POST['confirm_password'];

  // ✅ ดึงข้อมูลผู้ใช้
  $stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id = ?");
  $stmt->execute([$customer_id]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    $msg = "❌ ไม่พบข้อมูลผู้ใช้ในระบบ";
  } elseif (!password_verify($current, $user['password'])) {
    $msg = "❌ รหัสผ่านเดิมไม่ถูกต้อง";
  } elseif ($new !== $confirm) {
    $msg = "❌ รหัสผ่านใหม่และยืนยันไม่ตรงกัน";
  } elseif (strlen($new) < 6) {
    $msg = "❌ รหัสผ่านใหม่ต้องมีอย่างน้อย 6 ตัวอักษร";
  } else {
    // ✅ เปลี่ยนรหัสผ่านใหม่
    $newHash = password_hash($new, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE customers SET password = ? WHERE customer_id = ?");
    $update->execute([$newHash, $customer_id]);

    // ✅ เก็บข้อความ Toast แล้วกลับไปหน้าโปรไฟล์
    $_SESSION['toast_success'] = "🔐 เปลี่ยนรหัสผ่านเรียบร้อยแล้ว";
    header("Location: profile.php");
    exit;
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
    .card {
      max-width: 500px;
      margin: 50px auto;
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .card-header {
      background: #198754;
      color: #fff;
      font-weight: bold;
      text-align: center;
      border-radius: 15px 15px 0 0;
    }
    .btn:hover { transform: scale(1.05); transition: 0.2s; }
  </style>
</head>
<body>

<?php include("navbar_user.php"); ?>

<div class="container">
  <div class="card">
    <div class="card-header">
      🔑 เปลี่ยนรหัสผ่าน
    </div>
    <div class="card-body p-4">
      <?php if (!empty($msg)): ?>
        <div class="alert text-center <?= strpos($msg, '❌') !== false ? 'alert-danger' : 'alert-success' ?>">
          <?= $msg ?>
        </div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label fw-semibold">รหัสผ่านเดิม</label>
          <input type="password" name="current_password" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">รหัสผ่านใหม่</label>
          <input type="password" name="new_password" class="form-control" required minlength="6">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">ยืนยันรหัสผ่านใหม่</label>
          <input type="password" name="confirm_password" class="form-control" required minlength="6">
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
          <a href="profile.php" class="btn btn-secondary">⬅️ กลับโปรไฟล์</a>
          <button type="submit" class="btn btn-success">💾 บันทึกรหัสผ่านใหม่</button>
        </div>
      </form>
    </div>
  </div>
</div>

<footer class="text-center py-3 mt-5 bg-dark text-white">
  © <?= date('Y') ?> MyCommiss | เปลี่ยนรหัสผ่าน
</footer>

<!-- ✅ Toast Script 5 วินาที -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const toastElList = [].slice.call(document.querySelectorAll('.toast'));
  const toastList = toastElList.map(function (toastEl) {
    return new bootstrap.Toast(toastEl, { delay: 5000, autohide: true });
  });
  toastList.forEach(toast => toast.show());
</script>

</body>
</html>
