<?php
session_start();
include("connectdb.php");

// ✅ ต้องล็อกอินก่อน
if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

// ✅ ตรวจสอบว่ามี id ออเดอร์ไหม
if (!isset($_GET['id'])) {
  die("<p class='text-center mt-5 text-danger'>❌ ไม่พบรหัสคำสั่งซื้อ</p>");
}

$order_id = intval($_GET['id']);
$customer_id = $_SESSION['customer_id'];

// ✅ ตรวจสอบว่าออเดอร์เป็นของลูกค้าคนนี้จริงไหม และยังไม่ถูกยกเลิก
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND customer_id = ? AND order_status != 'ยกเลิก'");
$stmt->execute([$order_id, $customer_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
  die("<p class='text-center mt-5 text-danger'>❌ ไม่พบคำสั่งซื้อนี้ หรือถูกยกเลิกไปแล้ว</p>");
}

// ✅ อัปเดตสถานะการชำระเงินและคำสั่งซื้อให้เป็น "ยกเลิก"
$update = $conn->prepare("
  UPDATE orders 
  SET payment_status = 'ยกเลิก',
      order_status = 'ยกเลิก'
  WHERE order_id = ? AND customer_id = ?
");
$update->execute([$order_id, $customer_id]);

// ✅ กลับไปหน้ารายละเอียดออเดอร์
header("Location: order_detail.php?id=" . $order_id);
exit;
?>
