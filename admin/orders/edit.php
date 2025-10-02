<?php
session_start();
if (!isset($_SESSION['admin'])) { 
    header("Location: ../login.php"); 
    exit(); 
}
include "../connectdb.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("❌ รหัสคำสั่งซื้อไม่ถูกต้อง");
}

// ดึงข้อมูล order
$stmt = $conn->prepare("SELECT order_id, status FROM orders WHERE order_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("❌ ไม่พบคำสั่งซื้อนี้");
}

// เมื่อ submit ฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE order_id=?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        header("Location: order_detail.php?id=$id");
        exit();
    } else {
        echo "❌ Error: " . $stmt->error;
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
            <label class="form-label">สถานะ</label>
            <select name="status" class="form-select" required>
                <option value="รอดำเนินการ" <?= $order['status']=="รอดำเนินการ"?"selected":"" ?>>รอดำเนินการ</option>
                <option value="จัดส่งแล้ว" <?= $order['status']=="จัดส่งแล้ว"?"selected":"" ?>>จัดส่งแล้ว</option>
                <option value="เสร็จสิ้น" <?= $order['status']=="เสร็จสิ้น"?"selected":"" ?>>เสร็จสิ้น</option>
                <option value="ยกเลิก" <?= $order['status']=="ยกเลิก"?"selected":"" ?>>ยกเลิก</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">💾 บันทึก</button>
        <a href="order_detail.php?id=<?= $order['order_id'] ?>" class="btn btn-secondary">⬅️ ยกเลิก</a>
    </form>
</div>
</body>
</html>