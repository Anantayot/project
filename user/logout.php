<?php
session_start();

// ✅ ลบข้อมูล session ทั้งหมด
session_unset();
session_destroy();

// ✅ เด้งกลับไปหน้า login
header("Location: login.php");
exit;
?>
