<?php
session_start();
if (!isset($_SESSION['admin'])) { 
    header("Location: ../login.php"); 
    exit(); 
}

include "../connectdb.php";

// ดึงข้อมูลออเดอร์ + ชื่อลูกค้า
$sql = "SELECT o.order_id, o.order_date, o.status, c.name AS customer, c.address 
        FROM orders o
        LEFT JOIN customers c ON o.customer_id = c.customer_id
        ORDER BY o.order_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการคำสั่งซื้อ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>จัดการคำสั่งซื้อ</h3>
        <a href="../dashboard.php" class="btn btn-secondary">⬅️ กลับไปหน้าแดชบอร์ด</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover display responsive nowrap" id="myTable" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>วันที่สั่ง</th>
                    <th>สถานะ</th>
                    <th>ลูกค้า</th>
                    <th>ที่อยู่จัดส่ง</th>
                    <th>รายละเอียด</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                    <td><?= htmlspecialchars($row['order_date']) ?></td>
                    <td>
                        <?php 
                        switch($row['status']) {
                            case 'pending': echo "<span class='text-warning'>รอดำเนินการ</span>"; break;
                            case 'shipped': echo "<span class='text-primary'>จัดส่งแล้ว</span>"; break;
                            case 'completed': echo "<span class='text-success'>เสร็จสิ้น</span>"; break;
                            default: echo htmlspecialchars($row['status']);
                        }
                        ?>
                    </td>
                    <td><?= htmlspecialchars($row['customer']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td><a href="order_detail.php?id=<?= $row['order_id'] ?>" class="btn btn-info btn-sm">🔍 ดู</a></td>
                    <td>
                        <a href="edit_order.php?id=<?= $row['order_id'] ?>" class="btn btn-warning btn-sm">✏️ แก้ไข</a>
                        <a href="delete_order.php?id=<?= $row['order_id'] ?>" class="btn btn-danger btn-sm" 
                           onclick="return confirm('ลบคำสั่งซื้อนี้หรือไม่?')">🗑️ ลบ</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#myTable').DataTable({
        responsive: true,
        "order": [[0, "desc"]],
        "pageLength": 10,
        "language": {
            "lengthMenu": "แสดง _MENU_ แถวต่อหน้า",
            "zeroRecords": "ไม่พบข้อมูล",
            "info": "แสดงหน้า _PAGE_ จาก _PAGES_",
            "infoEmpty": "ไม่มีข้อมูล",
            "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
            "search": "ค้นหา:",
            "paginate": {
                "first": "หน้าแรก",
                "last": "หน้าสุดท้าย",
                "next": "ถัดไป",
                "previous": "ก่อนหน้า"
            }
        },
        "columnDefs": [
            { "orderable": false, "targets": [5,6] } // ปิด sorting คอลัมน์รายละเอียดและจัดการ
        ]
    });
});
</script>
</body>
</html>
