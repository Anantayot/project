<?php
include "partials/connectdb.php";
include "functions.php";

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // ตรวจสอบว่าอีเมลนี้ถูกใช้แล้วหรือไม่
    $chk = $conn->prepare("SELECT * FROM customers WHERE email=?");
    $chk->execute([$email]);
    if ($chk->fetch()) {
        $err = "❌ อีเมลนี้ถูกใช้แล้ว!";
    } else {
        $stmt = $conn->prepare("INSERT INTO customers (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $phone, $address]);
        echo "<script>alert('สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ');window.location='login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>สมัครสมาชิก | MyCommiss</title>

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/slick.css">
	<link rel="stylesheet" href="css/slick-theme.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">

	<style>
		body {
			background-color: #f5f5f5;
			font-family: 'Montserrat', sans-serif;
		}
		.register-box {
			background: #fff;
			border-radius: 10px;
			box-shadow: 0 4px 20px rgba(0,0,0,0.1);
			padding: 30px;
			max-width: 500px;
			margin: 60px auto;
		}
		.register-box h3 {
			font-weight: 700;
			color: #D10024;
		}
		.btn-register {
			background-color: #D10024;
			border: none;
			color: #fff;
			font-weight: bold;
			padding: 10px 0;
			border-radius: 6px;
			transition: 0.3s;
		}
		.btn-register:hover {
			background-color: #b50020;
			transform: translateY(-1px);
		}
	</style>
</head>

<body>
	<!-- HEADER -->
	<header>
		<div id="top-header">
			<div class="container d-flex justify-content-between">
				<ul class="header-links">
					<li><a href="#"><i class="fa fa-phone"></i> 093-370-5611</a></li>
					<li><a href="#"><i class="fa fa-envelope-o"></i> support@mycommiss.com</a></li>
				</ul>
				<ul class="header-links">
					<li><a href="login.php"><i class="fa fa-user-o"></i> เข้าสู่ระบบ</a></li>
				</ul>
			</div>
		</div>

		<div id="header">
			<div class="container text-center">
				<a href="index.php"><img src="img/logo.png" alt="Logo" style="height:60px;margin:15px auto;"></a>
			</div>
		</div>
	</header>
	<!-- /HEADER -->

	<!-- REGISTER FORM -->
	<div class="register-box">
		<h3 class="text-center mb-4"><i class="fa fa-user-plus"></i> สมัครสมาชิก</h3>

		<?php if($err): ?>
			<div class="alert alert-danger text-center"><?= htmlspecialchars($err) ?></div>
		<?php endif; ?>

		<form method="post">
			<div class="form-group mb-3">
				<label>ชื่อ-นามสกุล</label>
				<input type="text" name="name" class="form-control" required>
			</div>
			<div class="form-group mb-3">
				<label>อีเมล</label>
				<input type="email" name="email" class="form-control" required>
			</div>
			<div class="form-group mb-3">
				<label>รหัสผ่าน</label>
				<input type="password" name="password" class="form-control" required>
			</div>
			<div class="form-group mb-3">
				<label>เบอร์โทรศัพท์</label>
				<input type="text" name="phone" class="form-control">
			</div>
			<div class="form-group mb-3">
				<label>ที่อยู่</label>
				<textarea name="address" rows="2" class="form-control"></textarea>
			</div>
			<button type="submit" class="btn btn-register w-100">สมัครสมาชิก</button>
			<p class="text-center mt-3">มีบัญชีอยู่แล้ว? <a href="login.php" style="color:#D10024;font-weight:600;">เข้าสู่ระบบ</a></p>
		</form>
	</div>
	<!-- /REGISTER FORM -->

	<!-- FOOTER -->
	<footer id="footer" class="text-center mt-5">
		<div class="container">
			<p class="text-muted">© <?= date("Y") ?> MyCommiss | All Rights Reserved</p>
		</div>
	</footer>

	<!-- JS -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/slick.min.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/main.js"></script>
</body>
</html>
