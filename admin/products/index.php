<?php
session_start(); 
if(!isset($_SESSION['admin'])){
    header("location:../index.php");
    exit();
}
include("../connectdb.php");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการสินค้า</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>จัดการสินค้า</h3>
        <div>
        <a href="../dashboard.php" class="btn btn-secondary ">กลับไปหน้าแดชบอร์ด</a>
        <a href="add.php" class="btn btn-success">+ เพิ่มสินค้า</a> </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover display responsive nowrap" id="myTable" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>รูปภาพ</th>
                    <th>ชื่อสินค้า</th>
                    <th>รายละเอียด</th>
                    <th>ราคา</th>
                    <th>สต๊อก</th>
                    <th>ประเภท</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT p.*, c.cat_name 
                        FROM product p 
                        LEFT JOIN category c ON p.cat_id = c.cat_id
                        ORDER BY p_id DESC";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                    echo "<tr>
                            <td>".htmlspecialchars($row['p_id'])."</td>
                            <td>";
                                if(!empty($row['p_image'])){
                                    echo "<img src='../uploads/".htmlspecialchars($row['p_image'])."' width='60' class='img-thumbnail'>";
                                } else {
                                    echo "<span class='text-muted'>ไม่มีรูป</span>";
                                }
                    echo    "</td>
                            <td>".htmlspecialchars($row['p_name'])."</td>
                            <td>".nl2br(htmlspecialchars($row['p_description']))."</td>
                            <td>".number_format($row['p_price'],2)."</td>
                            <td>".htmlspecialchars($row['p_stock'])."</td>
                            <td>".htmlspecialchars($row['cat_name'])."</td>
                            <td>
                                <a href='edit.php?id={$row['p_id']}' class='btn btn-warning btn-sm'>แก้ไข</a>
                                <a href='delete.php?id={$row['p_id']}' class='btn btn-danger btn-sm' 
                                   onclick=\"return confirm('ลบสินค้า?');\">ลบ</a>
                            </td>
                          </tr>";
                }
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
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
        { "orderable": false, "targets": [1,7] }, // ปิด sort ที่คอลัมน์รูปภาพ + จัดการ
        { responsivePriority: 1, targets: 2 }     // ✅ บังคับให้ "ชื่อสินค้า" โชว์ตลอด
    ]
});

</script>

</body>
</html>
