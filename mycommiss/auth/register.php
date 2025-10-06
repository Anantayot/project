<?php
session_start();
include __DIR__ . "/../connectdb.php";
include __DIR__ . "/../lib/functions.php";
include __DIR__ . "/../partials/header.php";
include __DIR__ . "/../partials/flash.php";

if (!empty($_SESSION['user'])) { header("Location: ../index.php"); exit(); }

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $email = trim($_POST['email'] ?? '');
  $fullname = trim($_POST['fullname'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm  = $_POST['confirm'] ?? '';

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash('danger','อีเมลไม่ถูกต้อง');
  } elseif ($password !== $confirm) {
    flash('danger','รหัสผ่านไม่ตรงกัน');
  } elseif (strlen($password) < 6) {
    flash('danger','รหัสผ่านอย่างน้อย 6 ตัว');
  } else {
    // check exists
    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->execute([$email]);
    if ($check->fetch()) {
      flash('danger','อีเมลนี้มีบัญชีอยู่แล้ว');
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stm = $conn->prepare("INSERT INTO users(email,password_hash,fullname) VALUES (?,?,?)");
      $stm->execute([$email, $hash, $fullname ?: $email]);
      flash('success','สมัครสมาชิกสำเร็จ! เข้าสู่ระบบได้เลย');
      header("Location: login.php"); exit();
    }
  }
}
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h3 class="mb-3">สมัครสมาชิก</h3>
    <form method="post" class="card p-3">
      <div class="mb-3">
        <label class="form-label">อีเมล</label>
        <input name="email" type="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">ชื่อ-สกุล</label>
        <input name="fullname" class="form-control" placeholder="ไม่ระบุได้">
      </div>
      <div class="mb-3">
        <label class="form-label">รหัสผ่าน</label>
        <input name="password" type="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">ยืนยันรหัสผ่าน</label>
        <input name="confirm" type="password" class="form-control" required>
      </div>
      <button class="btn btn-primary">สมัครสมาชิก</button>
      <a href="login.php" class="btn btn-link">มีบัญชีอยู่แล้ว? เข้าสู่ระบบ</a>
    </form>
  </div>
</div>
<?php include __DIR__ . "/../partials/footer.php"; ?>
