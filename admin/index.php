<?php
session_start(); // 1. ต้องเริ่ม Session ก่อนเสมอ
include('../connectdb.php'); // เชื่อมต่อ DB ที่เช็คแล้วว่าใช้ได้

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ป้องกัน SQL Injection
    $username = mysqli_real_escape_string($conn, $_POST['a_user']);
    $password = mysqli_real_escape_string($conn, $_POST['a_pass']); 
    
    // 2. สร้าง SQL Query เพื่อตรวจสอบผู้ใช้และรหัสผ่าน
    $sql = "SELECT a_id FROM admin WHERE a_user = '$username' AND a_pass = '$password'"; 
    
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        
        // 3. Login สำเร็จ: สร้าง Session และ Redirect
        $_SESSION['a_id'] = $admin['a_id']; 
        header('Location: dashboard.php'); 
        exit;
    } else {
        // 4. Login ล้มเหลว: ตั้งค่าข้อความแจ้งเตือน
        $error_message = "ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง โปรดลองอีกครั้ง";
    }
}
// หาก Login ล้มเหลว หรือเข้ามาครั้งแรก จะแสดงหน้า Login 
?>

<div class="card-body">
    <?php if ($error_message): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    <form action="index.php" method="POST">
        </form>
</div>