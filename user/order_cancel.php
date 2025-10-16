<?php
session_start();
include("connectdb.php");

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

if (!isset($_GET['id'])) {
  die("❌ ไม่พบรหัสคำสั่งซื้อ");
}

$order_id = intval($_GET['id']);
$customer_id = $_SESSION['customer_id'];

// ✅ ตรวจสอบว่าออเดอร์นี้เป็นของลูกค้าคนนี้จริงและยังไม่ถูกยกเลิก
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND customer_id = ? AND payment_status != 'ยกเลิก'");
$stmt->execute([$order_id, $customer_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
  die("<script>alert('ไม่พบคำสั่งซื้อนี้ หรือไม่สามารถยกเลิกได้');window.location='orders.php';</script>");
}

// ✅ อัปเดตสถานะเป็น “ยกเลิก”
$update = $conn->prepare("UPDATE orders SET payment_status = 'ยกเลิก' WHERE order_id = ?");
$update->execute([$order_id]);

echo "<script>alert('✅ ยกเลิกคำสั่งซื้อเรียบร้อยแล้ว');window.location='orders.php';</script>";
exit;
?>
