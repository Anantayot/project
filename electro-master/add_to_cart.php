<?php
// add_to_cart.php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once 'connectdb.php';

$id  = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
if ($qty < 1) $qty = 1;

if ($id <= 0) {
  echo json_encode(['ok' => false, 'message' => 'รหัสสินค้าไม่ถูกต้อง', 'count' => 0]);
  exit;
}

// ตรวจว่ามีสินค้าจริงในฐานข้อมูล (กันยิงมั่ว)
$stmt = $conn->prepare("SELECT p_id, p_name, p_price, p_image FROM product WHERE p_id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
  echo json_encode(['ok' => false, 'message' => 'ไม่พบสินค้า', 'count' => 0]);
  exit;
}

// สร้าง/อัปเดตตะกร้าใน session
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if (!isset($_SESSION['cart'][$id])) {
  $_SESSION['cart'][$id] = [
    'id'    => $product['p_id'],
    'name'  => $product['p_name'],
    'price' => (float)$product['p_price'],
    'image' => $product['p_image'],
    'qty'   => 0,
  ];
}
$_SESSION['cart'][$id]['qty'] += $qty;

// นับจำนวนรวมในตะกร้า
$totalQty = 0;
foreach ($_SESSION['cart'] as $it) $totalQty += (int)$it['qty'];

echo json_encode([
  'ok'      => true,
  'message' => 'เพิ่มสินค้าลงตะกร้าแล้ว',
  'count'   => $totalQty
]);
