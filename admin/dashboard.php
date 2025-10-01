<?php 
include 'includes/connectdb.php';
include 'includes/header.php'; 
// หน้านี้ใช้แสดงสรุปข้อมูลต่างๆ เช่น จำนวนสินค้า, ออเดอร์รอจัดส่ง
?>

<h1 class="mb-4"><i class="bi bi-speedometer2"></i> แดชบอร์ด</h1>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">คำสั่งซื้อรอจัดส่ง</h5>
                        <h2 class="card-text">5 รายการ</h2> 
                    </div>
                    <i class="bi bi-box-seam h1"></i>
                </div>
                <a href="manage_orders.php" class="text-white small stretched-link">ดูรายละเอียดเพิ่มเติม</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">สินค้าคงเหลือทั้งหมด</h5>
                        <h2 class="card-text">1,250 ชิ้น</h2> 
                    </div>
                    <i class="bi bi-box-fill h1"></i>
                </div>
                <a href="manage_products.php" class="text-white small stretched-link">ดูรายละเอียดเพิ่มเติม</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-info shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">ลูกค้ารวม</h5>
                        <h2 class="card-text">320 คน</h2> 
                    </div>
                    <i class="bi bi-people h1"></i>
                </div>
                <a href="manage_users.php" class="text-white small stretched-link">ดูรายละเอียดเพิ่มเติม</a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header card-header-custom">
        <i class="bi bi-clock-history"></i> 10 ออเดอร์ล่าสุด
    </div>
    <div class="card-body">
        <p class="text-muted">ตารางแสดงรายการสั่งซื้อล่าสุด 10 รายการ</p>
    </div>
</div>


<?php include 'includes/footer.php'; ?>