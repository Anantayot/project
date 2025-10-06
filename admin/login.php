<?php
session_start();
include __DIR__ . "/partials/connectdb.php";


// ถ้ามีการล็อกอินอยู่แล้ว → กลับไปหน้า Dashboard
if (isset($_SESSION['admin'])) {
  header("Location: index.php");
  exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  // 🌟 แบบทดสอบชั่วคราว (ในอนาคตควรใช้ฐานข้อมูล)
  if ($username === "admin" && $password === "1234") {
    $_SESSION['admin'] = $username;
    header("Location: index.php");
    exit;
  } else {
    $error = "❌ ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
  }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เข้าสู่ระบบ - MyCommiss Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Corona Template + Bootstrap -->
  <link rel="stylesheet" href="template_corona/assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="template_corona/assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #141E30, #243B55);
      height: 100vh;
    }
    .login-card {
      max-width: 400px;
      background: #1E293B;
      color: #fff;
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 4px 25px rgba(0,0,0,0.3);
    }
    .form-control {
      text-align: center;
      background-color: #334155;
      color: #fff;
      border: none;
    }
    .form-control:focus {
      background-color: #475569;
      outline: none;
      box-shadow: none;
    }
    .btn-success {
      background-color: #22c55e;
      border: none;
    }
    .btn-success:hover {
      background-color: #16a34a;
    }
  </style>
</head>

<body class="d-flex align-items-center justify-content-center">
  <div class="login-card text-center">
    <h3 class="mb-4 fw-bold">🖥️ MyCommiss Admin</h3>

    <form method="post">
      <div class="form-group mb-3">
        <input type="text" name="username" class="form-control" placeholder="ชื่อผู้ใช้" required>
      </div>
      <div class="form-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="รหัสผ่าน" required>
      </div>

      <?php if ($error): ?>
        <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <button type="submit" class="btn btn-success w-100 fw-bold">
        <i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ
      </button>
    </form>
  </div>

  <script src="template_corona/assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="template_corona/assets/js/template.js"></script>
</body>
</html>
