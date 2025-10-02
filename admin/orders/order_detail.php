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

// ดึงข้อมูลคำสั่งซื้อ
$order = $conn->query("
    SELECT o.order_id, o.order_date, o.status, c.name, c.email, c.phone, o.shipping_address
    FROM orders o
    LEFT JOIN customers c ON o.customer_id = c.customer_id
    WHERE o.order_id = $id
")->fetch_assoc();

if (!$order) {
    die("❌ ไม่พบคำสั่งซื้อนี้");
}

// ดึงรายละเอียดสินค้าในคำสั่งซื้อ
$items = $conn->query("
    SELECT od.p_id, p.p_name AS name, od.quantity AS qty, od.price
    FROM order_details od
    LEFT JOIN product p ON od.p_id = p.p_id
    WHERE od.order_id = $id
");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>รายละเอียดคำสั่งซื้อ #<?= $order['order_id'] ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>รายละเอียดคำสั่งซื้อ #<?= $order['order_id'] ?></h2>

    <p><b>วันที่สั่ง:</b> <?= $order['order_date'] ?></p>
    <p><b>สถานะ:</b> 
<?php 
switch($order['status']) {
    case 'รอดำเนินการ': echo "<span class='text-warning'>รอดำเนินการ</span>"; break;
    case 'จัดส่งแล้ว': echo "<span class='text-primary'>จัดส่งแล้ว</span>"; break;
    case 'เสร็จสิ้น': echo "<span class='text-success'>เสร็จสิ้น</span>"; break;
    default: echo htmlspecialchars($order['status']);
}
?>
<a href="order_edit.php?id=<?= $order['order_id'] ?>" class="btn btn-sm btn-outline-primary ms-2">✏️ แก้ไข</a>
</p>
    </p>
    <p><b>ลูกค้า:</b> <?= htmlspecialchars($order['name']) ?> (<?= htmlspecialchars($order['email']) ?> / <?= htmlspecialchars($order['phone']) ?>)</p>
    <p><b>ที่อยู่จัดส่ง:</b> <?= htmlspecialchars($order['shipping_address']) ?></p>

    <h3>สินค้าในคำสั่งซื้อ</h3>
    <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover display" id="orderTable">
        <thead class="table-dark">
            <tr>
                <th>สินค้า</th>
                <th>จำนวน</th>
                <th>ราคา/ชิ้น</th>
                <th>รวม</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $total = 0;
        while($row = $items->fetch_assoc()) { 
            $sum = $row['qty'] * $row['price'];
            $total += $sum;
        ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $row['qty'] ?></td>
                <td><?= number_format($row['price'],2) ?></td>
                <td><?= number_format($sum,2) ?></td>
            </tr>
        <?php } ?>
            <tr>
                <td colspan="3" class="text-end"><b>ยอดรวมทั้งหมด</b></td>
                <td><b><?= number_format($total,2) ?></b></td>
            </tr>
        </tbody>
    </table>
    </div>

    <a href="index.php" class="btn btn-secondary mt-3">⬅️ กลับ</a>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#orderTable').DataTable({
        paging: false,
        searching: false,
        info: false,
        ordering: false
    });
});
</script>
</body>
</html>
