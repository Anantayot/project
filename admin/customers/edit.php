<?php
session_start();
if (!isset($_SESSION['admin'])) { 
    header("Location: ../login.php"); 
    exit(); 
}

// แก้ path ให้ถูกต้อง
include "../connectdb.php";

// รับค่า id อย่างปลอดภัย
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ดึงข้อมูลลูกค้า
$stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$cus = $result->fetch_assoc();

if (!$cus) {
    die("❌ ไม่พบข้อมูลลูกค้า");
}

// เมื่อมีการ submit ฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $phone   = $_POST['phone'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE customers 
                            SET name=?, email=?, phone=?, address=? 
                            WHERE customer_id=?");
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);

    if ($stmt->execute()) {
        header("Location: customers.php");
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
    <title>แก้ไขข้อมูลลูกค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>แก้ไขข้อมูลลูกค้า</h3>
    <a href="index.php" class="btn btn-secondary mb-3">⬅️ กลับไปหน้าลูกค้า</a>

    <div class="card p-3">
        <form method="post">
            <div class="mb-3">
                <label class="form-label">ชื่อ</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($cus['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($cus['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">เบอร์โทร</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($cus['phone']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">ที่อยู่</label>
                <textarea name="address" class="form-control"><?= htmlspecialchars($cus['address']) ?></textarea>
            </div>
            <a href="index.php"><button type="submit" class="btn btn-primary">บันทึก</button></a>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
