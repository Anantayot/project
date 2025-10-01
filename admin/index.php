<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include '../connectdb.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ควรมีการเข้ารหัส Password ในฐานข้อมูลจริง เช่น password_hash() และ password_verify()
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ? AND role = 'admin'");
    $stmt->execute([$email, $password]);
    $admin = $stmt->fetch();

    if ($admin) {
        $_SESSION['admin_login'] = $admin['email'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "อีเมลหรือรหัสผ่านไม่ถูกต้อง!";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบผู้ดูแล</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 100px;">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">เข้าสู่ระบบหลังร้าน</h3>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">อีเมล</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">รหัสผ่าน</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>