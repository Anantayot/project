<?php
session_start();
include("../connectdb.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['customer_id'] = $user['customer_id'];
    $_SESSION['customer_name'] = $user['name'];
    echo "<script>alert('✅ เข้าสู่ระบบสำเร็จ'); window.location='index.php';</script>";
    exit;
  } else {
    $error = "❌ อีเมลหรือรหัสผ่านไม่ถูกต้อง";
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เข้าสู่ระบบ | MyCommiss</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width:500px;">
  <div class="card shadow border-0">
    <div class="card-header bg-dark text-white text-center fw-bold">เข้าสู่ระบบ</div>
    <div class="card-body">
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">อีเมล</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">รหัสผ่าน</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="d-grid">
          <button class="btn btn-primary">🔓 เข้าสู่ระบบ</button>
        </div>
      </form>
    </div>
  </div>
  <div class="text-center mt-3">
    <a href="register.php" class="text-decoration-none">ยังไม่มีบัญชี? สมัครสมาชิก</a><br>
    <a href="index.php" class="btn btn-secondary btn-sm mt-2">⬅️ กลับหน้าหลัก</a>
  </div>
</div>

</body>
</html>
