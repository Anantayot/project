<?php
session_start();
include __DIR__ . "/../connectdb.php";
include __DIR__ . "/../lib/functions.php";
include __DIR__ . "/../partials/header.php";
include __DIR__ . "/../partials/flash.php";

if (!empty($_SESSION['user'])) { header("Location: ../index.php"); exit(); }

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $stm = $conn->prepare("SELECT * FROM users WHERE email=?");
  $stm->execute([$email]);
  $u = $stm->fetch();
  if ($u && password_verify($password, $u['password_hash'])) {
    $_SESSION['user'] = [
      'id' => $u['id'],
      'email' => $u['email'],
      'fullname' => $u['fullname'],
      'phone' => $u['phone']
    ];
    flash('success','เข้าสู่ระบบสำเร็จ');
    header("Location: ../index.php"); exit();
  } else {
    flash('danger','อีเมลหรือรหัสผ่านไม่ถูกต้อง');
  }
}
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <h3 class="mb-3">เข้าสู่ระบบ</h3>
    <form method="post" class="card p-3">
      <div class="mb-3">
        <label class="form-label">อีเมล</label>
        <input name="email" type="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">รหัสผ่าน</label>
        <input name="password" type="password" class="form-control" required>
      </div>
      <button class="btn btn-primary">เข้าสู่ระบบ</button>
      <a href="register.php" class="btn btn-link">ยังไม่มีบัญชี? สมัครสมาชิก</a>
    </form>
  </div>
</div>
<?php include __DIR__ . "/../partials/footer.php"; ?>
