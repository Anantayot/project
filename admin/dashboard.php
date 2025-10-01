<?php
// include('../connectdb.php'); // เชื่อมต่อฐานข้อมูล
// session_start();
// if(!isset($_SESSION['a_id'])) { header('location: index.php'); } // ตรวจสอบ session
include('includes/header.php');
?>
<h2><i class="fas fa-tachometer-alt"></i> แดชบอร์ดผู้ดูแลระบบ</h2>
<hr>
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">จำนวนสินค้า</h5>
                <p class="card-text fs-2">XX ชิ้น</p> <a href="products.php" class="btn btn-outline-light btn-sm">จัดการสินค้า <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    </div>
<?php
include('includes/footer.php');
?>