<?php
session_start();
include('connectdb.php');
include('layout/header.php'); // ใช้ Header ที่เราสร้างไว้

// ดึงสินค้าแนะนำ (ตัวอย่าง 8 ชิ้น)
$sql_products = "SELECT * FROM products ORDER BY added_date DESC LIMIT 8";
$result_products = mysqli_query($conn, $sql_products);
?>

<h2 class="mb-4">สินค้ามาใหม่ 🔥</h2>
<div class="row">
    <?php while($row = mysqli_fetch_assoc($result_products)): ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div class="card h-100 shadow-sm">
            <img src="<?php echo $row['image_url']; ?>" class="card-img-top product-img" alt="<?php echo $row['product_name']; ?>">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                <p class="card-text text-danger fs-4 mt-auto"><?php echo number_format($row['price'], 2); ?> บาท</p>
                <div class="d-flex justify-content-between">
                    <a href="product_detail.php?id=<?php echo $row['product_id']; ?>" class="btn btn-outline-primary btn-sm">รายละเอียด</a>
                    <a href="cart/cart.php?action=add&id=<?php echo $row['product_id']; ?>" class="btn btn-success btn-sm">หยิบลงตะกร้า</a>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<div class="text-center my-5">
    <a href="category.php" class="btn btn-lg btn-secondary">ดูสินค้าทั้งหมด</a>
</div>

<?php 
include('layout/footer.php'); 
mysqli_close($conn); 
?>