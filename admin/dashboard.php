<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// เรียกไฟล์เชื่อมต่อฐานข้อมูล
include "connectdb.php";   // ปรับ path ให้ตรงกับโครงสร้างจริง

// ดึงข้อมูลสรุป
$totalProducts   = $conn->query("SELECT COUNT(*) AS total FROM product")->fetch_assoc()['total'];
$totalCategories = $conn->query("SELECT COUNT(*) AS total FROM category")->fetch_assoc()['total'];
$totalCustomers  = $conn->query("SELECT COUNT(*) AS total FROM customers")->fetch_assoc()['total'];
$totalOrders     = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แดชบอร์ดแอดมิน</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>ยินดีต้อนรับ <?= htmlspecialchars($_SESSION['admin']) ?></h2>
        <a href="logout.php" class="btn btn-danger">ออกจากระบบ</a>
    </div>

    <!-- เมนู -->
    <div class="mb-4">
        <div class="btn-group" role="group">
            <a href="products/index.php" class="btn btn-primary">สินค้า</a>
            <a href="categories/index.php" class="btn btn-primary">ประเภทสินค้า</a>
            <a href="customers/index.php" class="btn btn-primary">ลูกค้า</a>
            <a href="orders/index.php" class="btn btn-primary">ออเดอร์</a>
        </div>
    </div>

    <!-- Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">สินค้า</h5>
                    <p class="card-text fs-3"><?= $totalProducts ?></p>
                    <a href="products/index.php" class="btn btn-outline-primary btn-sm">ดูรายละเอียด</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">ประเภท</h5>
                    <p class="card-text fs-3"><?= $totalCategories ?></p>
                    <a href="categories/index.php" class="btn btn-outline-primary btn-sm">ดูรายละเอียด</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">ลูกค้า</h5>
                    <p class="card-text fs-3"><?= $totalCustomers ?></p>
                    <a href="customers/index.php" class="btn btn-outline-primary btn-sm">ดูรายละเอียด</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">ออเดอร์</h5>
                    <p class="card-text fs-3"><?= $totalOrders ?></p>
                    <a href="orders/index.php" class="btn btn-outline-primary btn-sm">ดูรายละเอียด</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ตารางสินค้า 5 รายการล่าสุด -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">สินค้า 5 รายการล่าสุด</h5>
            <table id="myTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>รูปภาพ</th>
                        <th>ชื่อสินค้า</th>
                        <th>รายละเอียด</th>
                        <th>ราคา</th>
                        <th>สต็อก</th>
                        <th>ประเภท</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = $conn->query("SELECT p.p_id, p.p_name, p.p_price, p.p_stock, 
                                                p.p_image, p.p_description, 
                                                c.cat_name AS category
                                         FROM product p 
                                         LEFT JOIN category c ON p.cat_id=c.cat_id
                                         ORDER BY p.p_id DESC LIMIT 5");
                    while($row = $res->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $row['p_id'] ?></td>
                        <td>
                            <?php if (!empty($row['p_image'])): ?>
                                <img src="../admin/uploads/<?= htmlspecialchars($row['p_image']) ?>" width="60" height="60" class="rounded">
                            <?php else: ?>
                                <span class="text-muted">ไม่มีรูป</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['p_name']) ?></td>
                        <td><?= mb_strimwidth(strip_tags($row['p_description']), 0, 50, "...") ?></td>
                        <td><?= number_format($row['p_price'],2) ?></td>
                        <td><?= $row['p_stock'] ?></td>
                        <td><?= $row['category'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
$(document).ready(function() {
    $('#myTable').DataTable({
        responsive: true,
        paging: true,           // เปิดการแบ่งหน้า
        info: true,             // แสดงข้อมูลจำนวนรายการ
        lengthChange: false,    // ปิด select เปลี่ยนจำนวนรายการต่อหน้า
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json"
        },
        dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row mt-3"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>', 
        // dom แบบนี้จะแยกแถว pagination ให้ชัดเจน
    });
});
</script>

</body>
</html>
