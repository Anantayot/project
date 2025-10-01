<?php
session_start();
include('../connectdb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // 1. ตรวจสอบรหัสผ่าน
    if ($password !== $confirm_password) {
        $error = "รหัสผ่านไม่ตรงกัน";
    } else {
        // 2. เข้ารหัสรหัสผ่าน (สำคัญมาก!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // 3. ตรวจสอบชื่อผู้ใช้ซ้ำ
        $check_sql = "SELECT user_id FROM users WHERE username = '$username' OR email = '$email'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้งานแล้ว";
        } else {
            // 4. เพิ่มข้อมูลลงในฐานข้อมูล
            $insert_sql = "INSERT INTO users (username, password, first_name, last_name, email, phone, address) 
                           VALUES ('$username', '$hashed_password', '$first_name', '$last_name', '$email', '$phone', '$address')";
            
            if (mysqli_query($conn, $insert_sql)) {
                $success = "สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ";
                // ส่งไปหน้า Login
                header('refresh:2; url=login.php');
            } else {
                $error = "เกิดข้อผิดพลาดในการสมัคร: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3>สมัครสมาชิกใหม่</h3>
                </div>
                <div class="card-body">
                    <a href="../index.php" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left"></i> กลับหน้าแรก</a>
                    
                    <?php if(isset($success)): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
                    <?php if(isset($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
                    
                    <form method="POST">
                        <h5 class="mt-3">ข้อมูลสำหรับเข้าสู่ระบบ</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ชื่อผู้ใช้ (Username)</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">อีเมล</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">รหัสผ่าน</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ยืนยันรหัสผ่าน</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                        </div>

                        <h5 class="mt-3">ข้อมูลส่วนตัว</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ชื่อจริง</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">นามสกุล</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">เบอร์โทรศัพท์</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">ที่อยู่สำหรับจัดส่ง</label>
                                <textarea name="address" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">ลงทะเบียน</button>
                        </div>
                        <p class="text-center mt-3">เป็นสมาชิกอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php mysqli_close($conn); ?>