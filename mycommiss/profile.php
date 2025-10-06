<?php
session_start();
include __DIR__ . "/connectdb.php";
include __DIR__ . "/lib/functions.php";
require_login();
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/flash.php";

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $fullname = trim($_POST['fullname'] ?? '');
  $phone    = trim($_POST['phone'] ?? '');
  if ($fullname === '') {
    flash('danger','กรอกชื่อให้ครบ');
  } else {
    $stm = $conn->prepare("UPDATE users SET fullname=?, phone=? WHERE id=?");
    $stm->execute([$fullname, $phone, $user['id']]);
    $_SESSION['user']['fullname'] = $fullname;
    $_SESSION['user']['phone']    = $phone;
    flash('success','บันทึกข้อมูลแล้ว');
    header("Location: profile.php"); exit();
  }
}
?>
<h3 class="mb-3">โปรไฟล์ของฉัน</h3>
<form method="post" class="row g-3" style="max-width:520px;">
  <div class="col-12">
    <label class="form-label">อีเมล</label>
    <input class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
  </div>
  <div class="col-12">
    <label class="form-label">ชื่อ-สกุล</label>
    <input name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
  </div>
  <div class="col-12">
    <label class="form-label">เบอร์โทร</label>
    <input name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
  </div>
  <div class="col-12">
    <button class="btn btn-primary">บันทึก</button>
  </div>
</form>
<?php include __DIR__ . "/partials/footer.php"; ?>
