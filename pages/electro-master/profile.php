<?php
session_start();
include "partials/connectdb.php";

if (!isset($_SESSION['customer'])) {
  header("Location: login.php");
  exit;
}

$customer = $_SESSION['customer'];

// ดึงคำสั่งซื้อของลูกค้าจากฐานข้อมูล
$stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY order_date DESC");
$stmt->execute([$customer['customer_id']]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="th">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>โปรไฟล์ลูกค้า | MyCommiss</title>

	<!-- CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

	<style>
		body {
			background-color: #f6f6f6;
			font-family: 'Montserrat', sans-serif;
		}
		.profile-box {
			background: #fff;
			border-radius: 10px;
			box-shadow: 0 4px 20px rgba(0,0,0,0.1);
			padding: 30px;
			max-width: 900px;
			margin: 40px auto;
		}
		.profile-header {
			text-align: center;
			margin-bottom: 30px;
		}
		.profile-header h3 {
			font-weight: 700;
			color: #D10024;
		}
		.table thead {
			background-color: #D10024;
			color: #fff;
		}
		.badge-success { background-color: #28a745; }
		.badge-warning { background-color: #ffc107; color: #000; }
		.badge-danger { background-color: #dc3545; }
		.btn-red {
			background: #D10024;
			color: #fff;
			border-radius: 6px;
			padding: 8px 15px;
			border: none;
			transition: 0.3s;
		}
		.btn-red:hover {
			background: #b8001f;
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
					<li><a href="logout.php"><i class="fa fa-sign-out"></i> ออกจากระบบ</a></li>
				</ul>
			</div>
		</div>

		<div id="header" class="text-center">
			<div class="container">
				<a href="index.php"><img src="img/logo.png" alt="Logo" style="height:60px;margin:15px auto;"></a>
			</div>
		</div>
	</header>
	<!-- /HEADER -->

	<div class="profile-box">
		<div class="profile-header">
			<h3><i class="fa fa-user-circle"></i> โปรไฟล์ของฉัน</h3>
			<p class="text-muted">ยินดีต้อนรับ, <?= htmlspecialchars($customer['name']) ?></p>
			<hr>
		</div>

		<div class="row mb-4">
			<div class="col-md-6">
				<h5><i class="fa fa-info-circle text-danger"></i> ข้อมูลส่วนตัว</h5>
				<p><b>ชื่อ-นามสกุล:</b> <?= htmlspecialchars($customer['name']) ?></p>
				<p><b>อีเมล:</b> <?= htmlspecialchars($customer['email']) ?></p>
				<p><b>เบอร์โทร:</b> <?= htmlspecialchars($customer['phone']) ?></p>
				<p><b>ที่อยู่:</b> <?= htmlspecialchars($customer['address']) ?></p>
			</div>
			<div class="col-md-6 text-end">
				<a href="logout.php" class="btn btn-red"><i class="fa fa-sign-out"></i> ออกจากระบบ</a>
			</div>
		</div>

		<h5><i class="fa fa-shopping-bag text-danger"></i> ประวัติคำสั่งซื้อ</h5>
		<div class="table-responsive">
			<table class="table table-bordered text-center align-middle">
				<thead>
					<tr>
						<th>#</th>
						<th>รหัสคำสั่งซื้อ</th>
						<th>วันที่สั่งซื้อ</th>
						<th>ราคารวม (฿)</th>
						<th>สถานะ</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($orders)): ?>
						<tr><td colspan="5" class="text-muted">ยังไม่มีคำสั่งซื้อ</td></tr>
					<?php else: ?>
						<?php foreach ($orders as $i => $o): ?>
						<tr>
							<td><?= $i+1 ?></td>
							<td>#<?= $o['order_id'] ?></td>
							<td><?= date("d/m/Y", strtotime($o['order_date'])) ?></td>
							<td><?= number_format($o['total_price'],2) ?></td>
							<td>
								<?php
									if ($o['order_status'] === 'เสร็จสิ้น')
										echo '<span class="badge badge-success">เสร็จสิ้น</span>';
									elseif ($o['order_status'] === 'กำลังดำเนินการ')
										echo '<span class="badge badge-warning">กำลังดำเนินการ</span>';
									else
										echo '<span class="badge badge-danger">ยกเลิก</span>';
								?>
							</td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>

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
