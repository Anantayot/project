<?php
// 1. เริ่ม session เสมอเมื่อต้องการใช้งาน
session_start();

// 2. ตรวจสอบสิทธิ์การเข้าถึง (ส่วนที่สำคัญที่สุด)
// คุณต้องแน่ใจว่ามีการสร้าง $_SESSION['admin'] จากหน้า login ของคุณแล้ว
if (!isset($_SESSION['admin'])) { 
    // ถ้ายังไม่ได้ login ให้ส่งกลับไปหน้าอื่น (เช่น หน้า login)
    header("Location: ../login.php"); // แนะนำให้ส่งไปหน้า login แทน dashboard
    exit(); 
}

// 3. เชื่อมต่อฐานข้อมูล
include "../connectdb.php";

// 4. ดึงข้อมูล
// ตรวจสอบว่า $conn ทำงานได้หรือไม่ก่อน query
if ($conn) {
    $result = $conn->query("SELECT * FROM customers ORDER BY customer_id DESC");
} else {
    // ถ้าเชื่อมต่อไม่ได้ ให้หยุดทำงานและแสดงข้อผิดพลาด
    die("ไม่สามารถเชื่อมต่อฐานข้อมูลได้");
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการลูกค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>จัดการลูกค้า</h3>
        <div>
            <a href="../dashboard.php" class="btn btn-secondary">⬅️ กลับไปหน้าแดชบอร์ด</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover display responsive nowrap" id="myTable" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>ชื่อ</th>
                    <th>Email</th>
                    <th>เบอร์โทร</th>
                    <th>ที่อยู่</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // ตรวจสอบว่ามีข้อมูลที่ดึงมาหรือไม่
                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) { 
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['customer_id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['address'])) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['customer_id'] ?>" class="btn btn-warning btn-sm">✏️ แก้ไข</a>
                        <a href="delete.php?id=<?= $row['customer_id'] ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบลูกค้าคนนี้?');">🗑️ ลบ</a>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                    // กรณีไม่มีข้อมูลในตาราง
                    echo '<tr><td colspan="6" class="text-center">ไม่พบข้อมูลลูกค้า</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
// 5. ปิดการเชื่อมต่อหลังจากใช้งานเสร็จสิ้น
if ($conn) {
    $conn->close();
}
?>

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
            { "orderable": false, "targets": 5 } // ปิด sorting คอลัมน์จัดการ
        ]
    });
});
</script>

</body>
</html>