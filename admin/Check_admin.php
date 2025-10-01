<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit();
}
// ใช้ในหน้า Admin ทุกหน้า ยกเว้นหน้า Login
?>