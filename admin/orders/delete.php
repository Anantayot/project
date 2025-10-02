<?php
session_start();
if (!isset($_SESSION['admin'])) { 
    header("Location: ../login.php"); 
    exit(); 
}
include "../connectdb.php";

$id = intval($_GET['id']);

// ลบรายการ order_details ด้วย (ถ้ามี)
$conn->query("DELETE FROM order_details WHERE order_id=$id");

// ลบออเดอร์
$sql = "DELETE FROM orders WHERE order_id=$id";
if ($conn->query($sql)) {
    header("Location: index.php");
    exit();
} else {
    echo "เกิดข้อผิดพลาด: " . $conn->error;
}
?>