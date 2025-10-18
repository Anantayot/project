<?php
include __DIR__ . "/../partials/connectdb.php";
require __DIR__ . '/../../vendor/autoload.php'; // โหลด SDK ของ Cloudinary

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

// ✅ ตั้งค่า Cloudinary
Configuration::instance([
  'cloud' => [
    'cloud_name' => 'YOUR_CLOUD_NAME',
    'api_key'    => 'YOUR_API_KEY',
    'api_secret' => 'YOUR_API_SECRET'
  ],
  'url' => [
    'secure' => true
  ]
]);

$id = $_GET['id'] ?? null;
if (!$id) {
  die("<script>alert('ไม่พบสินค้า!');history.back();</script>");
}

// ✅ ดึงชื่อไฟล์ก่อนลบ
$stmt = $conn->prepare("SELECT p_image FROM product WHERE p_id = ?");
$stmt->execute([$id]);
$imageData = $stmt->fetch(PDO::FETCH_ASSOC);

// ✅ ถ้ามีรูปให้ลบออกจาก Cloud
if ($imageData && !empty($imageData['p_image'])) {
  // สมมุติว่า p_image เก็บเป็น public_id ของ Cloudinary เช่น "products/laptop123"
  try {
    $publicId = pathinfo($imageData['p_image'], PATHINFO_FILENAME);
    (new UploadApi())->destroy($publicId);
  } catch (Exception $e) {
    error_log("❌ ลบรูปจาก Cloud ล้มเหลว: " . $e->getMessage());
  }
}

// ✅ ลบข้อมูลในฐานข้อมูล
$stmt = $conn->prepare("DELETE FROM product WHERE p_id = ?");
$stmt->execute([$id]);

header("Location: products.php");
exit;
?>
