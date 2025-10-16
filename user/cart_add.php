<?php
session_start();
include("connectdb.php");

// ✅ ตรวจสอบการส่งข้อมูล
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['id'])) {
  header("Location: index.php");
  exit;
}

$productId = intval($_POST['id']);
$qty = intval($_POST['qty'] ?? 1);

// ✅ ถ้ายังไม่ล็อกอิน → ให้ไปหน้า login
if (!isset($_SESSION['customer_id'])) {
  echo "<script>alert('กรุณาเข้าสู่ระบบก่อนสั่งซื้อสินค้า'); window.location='login.php';</script>";
  exit;
}

// ✅ ดึงข้อมูลสินค้า
$stmt = $conn->prepare("SELECT * FROM product WHERE p_id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
  echo "<script>alert('❌ ไม่พบสินค้านี้'); window.location='index.php';</script>";
  exit;
}

// ✅ เพิ่มสินค้าลงตะกร้าใน session
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if (isset($_SESSION['cart'][$productId])) {
  $_SESSION['cart'][$productId]['qty'] += $qty;
} else {
  $_SESSION['cart'][$productId] = [
    'id' => $product['p_id'],
    'name' => $product['p_name'],
    'price' => $product['p_price'],
    'image' => $product['p_image'],
    'qty' => $qty
  ];
}

// ✅ แจ้งเตือนและกลับไปที่ตะกร้า
echo "<script>
  alert('✅ เพิ่มสินค้าในตะกร้าเรียบร้อย!');
  window.location='cart.php';
</script>";
exit;
?>
