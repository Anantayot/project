<?php
session_start();
require 'connectdb.php';
if(isset($_SESSION['admin'])) header('Location: dashboard.php');
$err = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
$user = $_POST['user'];
$pass = $_POST['pass'];
$sql = $conn->prepare("SELECT a_id,a_pass FROM admin WHERE a_user=? LIMIT 1");
$sql->bind_param('s',$user);
$sql->execute();
$res = $sql->get_result();
if($row = $res->fetch_assoc()){
if(password_verify($pass,$row['a_pass'])){
$_SESSION['admin'] = $row['a_id'];
header('Location: dashboard.php'); exit;
} else $err = 'รหัสผ่านไม่ถูกต้อง';
} else $err = 'ไม่พบผู้ใช้';
}
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login - Snekker Shop</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
<div class="row justify-content-center">
<div class="col-md-5">
<div class="card shadow-sm">
<div class="card-body">
<h3 class="card-title mb-3">เข้าสู่ระบบผู้ดูแล</h3>
<?php if($err): ?>
<div class="alert alert-danger"><?php echo $err;?></div>
<?php endif; ?>
<form method="post">
<div class="mb-3">
<label class="form-label">ผู้ใช้</label>
<input required name="user" class="form-control">
</div>
<div class="mb-3">
<label class="form-label">รหัสผ่าน</label>
<input required name="pass" type="password" class="form-control">
</div>
<button class="btn btn-primary w-100">เข้าสู่ระบบ</button>
</form>
</div>
</div>
</div>
</div>
</div>
</body>
</html>