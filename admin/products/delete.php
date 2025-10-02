<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

// path ถูกต้อง: connectdb.php อยู่ใน admin/
include "../connectdb.php";

// ตรวจสอบว่ามี id ส่งมาหรือไม่
if (!isset($_GET['id'])) {
    die("❌ ไม่พบรหัสสินค้า");
}

$id = intval($_GET['id']);

// ตรวจสอบสินค้าว่ามีจริง
$chk = $conn->prepare("SELECT p_name FROM product WHERE p_id = ?");
$chk->bind_param("i", $id);
$chk->execute();
$chkRes = $chk->get_result();
if ($chkRes->num_rows === 0) {
    die("❌ ไม่พบสินค้าที่ต้องการลบ");
}
$chk->close();

// ลบสินค้า
$stmt = $conn->prepare("DELETE FROM product WHERE p_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php"); // กลับไปหน้ารายการสินค้า
    exit();
} else {
    echo "❌ Error: " . $conn->error;
    $stmt->close();
}
?>
