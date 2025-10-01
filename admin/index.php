<?php
include 'includes/connectdb.php'; // เชื่อมต่อ DB และเริ่ม Session

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // 1. ค้นหาผู้ใช้ด้วย Email
    $stmt = $conn->prepare("SELECT user_id, password, first_name, role FROM users WHERE email = :email AND role = 'admin'");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // 2. ตรวจสอบรหัสผ่านและ Role
        // ในระบบจริง คุณควรใช้ password_verify($password, $user['password'])
        // ตัวอย่างนี้สมมติว่ารหัสผ่านถูก Hash มาแล้ว

        // 3. ตั้งค่า Session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_first_name'] = $user['first_name'];

        // Redirect ไปหน้า Dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง หรือคุณไม่ใช่ผู้ดูแลระบบ";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #0d6efd; /* Primary color background */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 15px;
        }
    </style>
</head>
<body>

<div class="card login-card shadow-lg">
    <div class="card-body">
        <h3 class="card-title text-center mb-4"><i class="bi bi-shield-lock-fill"></i> ผู้ดูแลระบบ</h3>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php">
            <div class="mb-3">
                <label for="email" class="form-label">อีเมลผู้ดูแล</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">เข้าสู่ระบบ</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>