<?php
session_start();
include("connectdb.php");

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $order_id = intval($_POST['order_id']);
  $method = $_POST['payment_method'];
  $customer_id = $_SESSION['customer_id'];

  // ✅ ตรวจสอบสิทธิ์และอัปเดต
  $stmt = $conn->prepare("UPDATE orders 
                          SET payment_method = :method 
                          WHERE order_id = :oid AND customer_id = :cid");
  $stmt->execute([
    ':method' => $method,
    ':oid' => $order_id,
    ':cid' => $customer_id
  ]);

  header("Location: order_detail.php?id=" . $order_id);
  exit;
}
?>
