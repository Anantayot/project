<?php
session_start();
include "connectdb.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // แบบไม่ปลอดภัย: เปรียบเทียบตรงกับฐานข้อมูล (plain text)
    $sql = "SELECT * FROM admins WHERE username='$user' AND password='$pass' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $_SESSION['admin'] = $user;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "❌ Username/Password ไม่ถูกต้อง";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Login แอดมิน</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg,#6C63FF,#00C4FF);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.card {
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0px 5px 20px rgba(0,0,0,0.2);
    background-color: #ffffff;
    width: 100%;
    max-width: 400px;
}

.card h3 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}

.btn-primary {
    width: 100%;
}

.error-message {
    color: #dc3545;
    text-align: center;
    margin-bottom: 15px;
}
</style>
</head>
<body>

<div class="card">
    <h3>เข้าสู่ระบบแอดมิน</h3>

    <?php if(isset($error)) echo "<div class='error-message'>$error</div>"; ?>

    <form method="post">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
    </form>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
