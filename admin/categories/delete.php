<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("location:../dashboard.php");
    exit();
}
include("../connectdb.php");

// รับค่า id จาก URL
if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $sql = "DELETE FROM category WHERE cat_id=$id";
    if($conn->query($sql)){
        // ลบสำเร็จ กลับไปหน้า index
        header("Location: index.php");
        exit();
    } else {
        die("❌ เกิดข้อผิดพลาด: " . $conn->error);
    }
} else {
    die("❌ ไม่มี ID ที่ระบุ");
}
?>
