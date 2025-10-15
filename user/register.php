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

  if ($password !== $confirm) {
    $error = "❌ รหัสผ่านไม่ตรงกัน";
  } else {
    // ตรวจสอบอีเมลซ้ำ
    $check = $conn->prepare("SELECT * FROM customers WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
      $error = "⚠️ อีเมลนี้ถูกใช้ไปแล้ว";
    } else {
      // เข้ารหัสรหัสผ่าน
      $hashed = password_hash($password, PASSWORD_DEFAULT);
      
      // ✅ ตรวจสอบว่ามีฟิลด์ตรงกับฐานข้อมูลจริง
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

      echo "<script>
        alert('✅ สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ');
        window.location.href = 'login.php';
      </script>";
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>สมัครสมาชิก | MyCommiss</title>
  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width:600px;">
  <div class="card shadow border-0">
    <div class="card-header bg-dark text-white text-center fw-bold">สมัครสมาชิก</div>
    <div class="card-body">
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
      <?php endif; ?>

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
          <input type="text" name="phone" class="form-control">
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

</body>
</html>
