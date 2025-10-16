<?php
require_once __DIR__ . '/vendor/autoload.php';
use chillerlan\QRCode\{QRCode, QROptions};

// ✅ รับค่าเบอร์พร้อมเพย์และยอดจาก URL
$promptpay = $_GET['pp'] ?? '0812345678'; // เบอร์พร้อมเพย์ของร้าน
$amount = floatval($_GET['amount'] ?? 0.00);

// ✅ สร้าง payload สำหรับ QR PromptPay (พร้อมเพย์)
function generatePromptPay($promptpayID, $amount){
    $mobile = preg_replace('/[^0-9]/', '', $promptpayID);

    // สร้าง payload ตามมาตรฐาน Thai QR Payment (EMVCo)
    $payload = "000201"; // Payload Format Indicator
    $payload .= "010211"; // Point of Initiation Method (11 = static)

    $payload .= "29370016A000000677010111"; // AID (PromptPay)
    $payload .= sprintf("01130066%s", $mobile); // เบอร์โทร

    $payload .= "5802TH"; // ประเทศไทย
    $payload .= "5303764"; // สกุลเงิน (764 = THB)

    if($amount > 0){
        $payload .= sprintf("540%.2f", $amount);
    }

    $payload .= "6304"; // CRC placeholder
    $crc = strtoupper(dechex(crc16($payload)));
    $payload .= $crc;

    return $payload;
}

// ✅ ฟังก์ชันคำนวณ CRC16 (ตามมาตรฐาน EMVCo)
function crc16($data){
    $crc = 0xFFFF;
    for ($i = 0; $i < strlen($data); $i++) {
        $crc ^= ord($data[$i]) << 8;
        for ($j = 0; $j < 8; $j++) {
            if ($crc & 0x8000) {
                $crc = ($crc << 1) ^ 0x1021;
            } else {
                $crc <<= 1;
            }
            $crc &= 0xFFFF;
        }
    }
    return $crc;
}

$qrData = generatePromptPay($promptpay, $amount);

// ✅ ตั้งค่าและแสดง QR เป็นภาพ PNG
$options = new QROptions([
  'version' => 5,
  'outputType' => QRCode::OUTPUT_IMAGE_PNG,
  'eccLevel' => QRCode::ECC_L,
]);

header('Content-type: image/png');
echo (new QRCode($options))->render($qrData);
exit;
?>
