<?php
session_start();
if (!isset($_SESSION['admin'])) { 
    header("Location: ../login.php"); 
    exit(); 
}
include "../connectdb.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) die("❌ ไม่พบรหัสคำสั่งซื้อ");

// ดึงข้อมูลออเดอร์
$sql = "SELECT o.order_id, o.status, o.shipping_address, c.name AS customer
        FROM orders o
        LEFT JOIN customers c ON o.customer_id = c.customer_id
        WHERE o.order_id = $id";
$order = $conn->query($sql)->fetch_assoc();
if (!$order) die("❌ ไม่พบคำสั่งซื้อนี้");

// เมื่อกดบันทึก
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'] ?? '';
    $shipping_address = $_POST['shipping_address'] ?? '';

    $status = $conn->real_escape_string($status);
    $shipping_address = $conn->real_escape_string($shipping_address);

    $update = "UPDATE orders SET status='$status', shipping_address='$shipping_address' WHERE order_id=$id";

    if ($conn->query($update)) {
        header("Location: order_detail.php?id=$id"); // กลับไปดูรายละเอียด
        exit();
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แก้ไขคำสั่งซื้อ #<?= $order['order_id'] ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>แก้ไขคำสั่งซื้อ #<?= $order['order_id'] ?></h2>

    <form method="post" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">ลูกค้า</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($order['customer']) ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">สถานะ</label>
            <select name="status" class="form-select" required>
                <option value="รอดำเนินการ" <?= $order['status']=="รอดำเนินการ"?"selected":"" ?>>รอดำเนินการ</option>
                <option value="จัดส่งแล้ว" <?= $order['status']=="จัดส่งแล้ว"?"selected":"" ?>>จัดส่งแล้ว</option>
                <option value="เสร็จสิ้น" <?= $order['status']=="เสร็จสิ้น"?"selected":"" ?>>เสร็จสิ้น</option>
                <option value="ยกเลิก" <?= $order['status']=="ยกเลิก"?"selected":"" ?>>ยกเลิก</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">ที่อยู่จัดส่ง</label>
            <textarea name="shipping_address" class="form-control" rows="3"><?= htmlspecialchars($order['shipping_address']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-success">💾 บันทึก</button>
        <a href="index.php" class="btn btn-secondary">⬅️ ยกเลิก</a>
    </form>
</div>
</body>
</html>