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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
</head>
<body class="bg-dark text-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h3><i class="bi bi-box-seam"></i> จัดการสินค้า</h3>
        <div>
            <a href="../dashboard.php" class="btn btn-secondary">กลับ</a>
            <a href="add.php" class="btn btn-success">+ เพิ่มสินค้า</a>
        </div>
    </div>

    <!-- 🔍 ช่องค้นหา + หมวดหมู่ -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <input type="text" id="searchBox" class="form-control text-light" 
               style="background:#161b22;border:1px solid #2c313a;width:250px;" 
               placeholder="🔍 ค้นหาสินค้า...">
        <select id="categoryFilter" class="form-select bg-dark text-light border-secondary" style="width:200px;">
            <option value="">ทั้งหมด</option>
            <?php
            $catQuery = mysqli_query($conn, "SELECT * FROM category ORDER BY cat_name ASC");
            while($cat = mysqli_fetch_assoc($catQuery)){
                echo "<option value='".htmlspecialchars($cat['cat_name'])."'>".htmlspecialchars($cat['cat_name'])."</option>";
            }
            ?>
        </select>
    </div>

    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover display responsive nowrap" id="myTable" style="width:100%">
            <thead style="background:#00b14a;color:#111;">
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
                            <td class='text-success'>".number_format($row['p_price'],2)."</td>
                            <td class='text-warning'>".htmlspecialchars($row['p_stock'])."</td>
                            <td class='text-info'>".htmlspecialchars($row['cat_name'])."</td>
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
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function(){
    const table = $('#myTable').DataTable({
        responsive: true,
        language: {
            lengthMenu: "แสดง _MENU_ แถวต่อหน้า",
            zeroRecords: "ไม่พบข้อมูล",
            info: "แสดงหน้า _PAGE_ จาก _PAGES_",
            infoEmpty: "ไม่มีข้อมูล",
            infoFiltered: "(กรองจากทั้งหมด _MAX_ รายการ)",
            search: "ค้นหา:",
            paginate: {
                first: "หน้าแรก",
                last: "หน้าสุดท้าย",
                next: "ถัดไป",
                previous: "ก่อนหน้า"
            }
        },
        columnDefs: [
            { orderable: false, targets: [1,7] },
            { responsivePriority: 1, targets: 2 }
        ]
    });

    // 🔍 ช่องค้นหาสินค้า
    $('#searchBox').on('keyup', function(){
        table.search(this.value).draw();
    });

    // 🏷️ กรองตามหมวดหมู่
    $('#categoryFilter').on('change', function(){
        const cat = this.value;
        if (cat) {
            table.column(6).search('^' + cat + '$', true, false).draw();
        } else {
            table.column(6).search('').draw();
        }
    });
});
</script>

</body>
</html>
