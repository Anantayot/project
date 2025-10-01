<?php
session_start();
include('../connectdb.php');

// ถ้าเข้าสู่ระบบแล้วให้เด้งไป Dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT admin_id, password, full_name FROM admins WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['password'])) {
        // ล็อกอินสำเร็จ
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        header('Location: index.php'); // ไปหน้า Admin Dashboard
        exit();
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Login</title>
</head>
<body class="bg-light">
<div class="container" style="max-width: 400px; margin-top: 100px;">
    <div class="card shadow-lg">
        <div class="card-header bg-dark text-white text-center">
            <h3>เข้าสู่ระบบผู้ดูแลระบบ</h3>
        </div>
        <div class="card-body">
            <a href="../index.php" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left"></i> กลับหน้าร้าน</a>
            <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">ชื่อผู้ใช้</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">รหัสผ่าน</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">เข้าสู่ระบบ</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<?php mysqli_close($conn); ?>