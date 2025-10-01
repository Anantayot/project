<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Snekker Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Snekker Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="products.php"><i class="fas fa-box"></i> สินค้า</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="categories.php"><i class="fas fa-tags"></i> ประเภทสินค้า</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="customers.php"><i class="fas fa-users"></i> ลูกค้า</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="orders.php"><i class="fas fa-shopping-cart"></i> ออเดอร์</a>
        </li>
      </ul>
      <a class="btn btn-outline-light" href="logout.php"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
    </div>
  </div>
</nav>
<div class="container mt-4">
    ```

---

## 3. หน้า Login: `admin/index.php`

ไฟล์นี้มี **Logic การเข้าสู่ระบบ** ที่แก้ไขแล้ว พร้อมการสร้าง **Session** และแสดงข้อความ **Error**

```php
<?php
session_start();
include('../connectdb.php');

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['a_user']);
    $password = mysqli_real_escape_string($conn, $_POST['a_pass']);
    
    // SQL Query สำหรับตรวจสอบผู้ใช้และรหัสผ่าน (แบบ Plain Text)
    $sql = "SELECT a_id FROM admin WHERE a_user = '$username' AND a_pass = '$password'"; 
    
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        
        // Login สำเร็จ: สร้าง Session และ Redirect ไปหน้า Dashboard
        $_SESSION['a_id'] = $admin['a_id']; 
        header('Location: dashboard.php'); 
        exit;
    } else {
        // Login ล้มเหลว: ตั้งค่าข้อความแจ้งเตือน
        $error_message = "ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง โปรดลองอีกครั้ง";
    }
}
// หากมีการ Login อยู่แล้ว ให้ส่งไป Dashboard เลย
if (isset($_SESSION['a_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-lg" style="width: 100%; max-width: 400px;">
            <div class="card-header bg-dark text-white text-center">
                <h4>เข้าสู่ระบบผู้ดูแลระบบ</h4>
            </div>
            <div class="card-body">
                <?php if ($error_message): // แสดงข้อความ Error ถ้า Login ล้มเหลว ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form action="index.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">ชื่อผู้ใช้งาน</label>
                        <input type="text" class="form-control" id="username" name="a_user" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">รหัสผ่าน</label>
                        <input type="password" class="form-control" id="password" name="a_pass" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>