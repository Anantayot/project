<?php
include "partials/connectdb.php";
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM customers WHERE email=? AND password=?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['customer'] = $user;
        echo "<script>alert('เข้าสู่ระบบสำเร็จ!');window.location='index.php';</script>";
        exit;
    } else {
        $error = "❌ อีเมลหรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>เข้าสู่ระบบ | MyCommiss</title>

	<!-- CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

	<style>
		body {
			background-color: #f5f5f5;
			font-family: 'Montserrat', sans-serif;
		}
		.login-box {
			background: #fff;
			border-radius: 10px;
			box-shadow: 0 4px 20px rgba(0,0,0,0.1);
			padding: 30px;
			max-width: 480px;
			margin: 70px auto;
		}
		.login-box h3 {
			font-weight: 700;
			color: #D10024;
		}
		.btn-login {
			background-color: #D10024;
			border: none;
			color: #fff;
			font-weight: bold;
			padding: 10px 0;
			border-radius: 6px;
			transition: 0.3s;
		}
		.btn-login:hover {
			background-color: #b50020;
			transform: translateY(-1px);
		}
		a.text-red {
			color: #D10024;
			font-weight: 600;
			text-decoration: none;
		}
		a.text-red:hover {
			text-decoration: underline;
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
					<li><a href="register.php"><i class="fa fa-user-o"></i> สมัครสมาชิก</a></li>
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

	<!-- LOGIN FORM -->
	<div class="login-box">
		<h3 class="text-center mb-4"><i class="fa fa-sign-in"></i> เข้าสู่ระบบ</h3>

		<?php if($error): ?>
			<div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
		<?php endif; ?>

		<form method="post">
			<div class="form-group mb-3">
				<label>อีเมล</label>
				<input type="email" name="email" class="form-control" required>
			</div>
			<div class="form-group mb-3">
				<label>รหัสผ่าน</label>
				<input type="password" name="password" class="form-control" required>
			</div>
			<button type="submit" class="btn btn-login w-100">เข้าสู่ระบบ</button>

			<p class="text-center mt-3">
				ยังไม่มีบัญชี? <a href="register.php" class="text-red">สมัครสมาชิก</a>
			</p>
		</form>
	</div>
	<!-- /LOGIN FORM -->

	<!-- FOOTER -->
	<footer id="footer" class="text-center mt-5">
		<div class="container">
			<p class="text-muted">© <?= date("Y") ?> MyCommiss | All Rights Reserved</p>
		</div>
	</footer>

	<!-- JS -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
