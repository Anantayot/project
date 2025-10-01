<?php
session_start();
include('connectdb.php');
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT p.*, c.category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.category_id 
        WHERE p.product_id = $product_id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    // โค้ดแสดง Not Found
    header('Location: index.php'); exit; 
}
include('layout/header.php');
?>
<a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left"></i> กลับ</a>
<div class="row">
    <div class="col-md-6">
        <img src="<?php echo $product['image_url']; ?>" class="img-fluid rounded shadow-lg" alt="<?php echo $product['product_name']; ?>">
    </div>
    <div class="col-md-6">
        <h1><?php echo $product['product_name']; ?></h1>
        <p class="text-muted">ประเภท: **<?php echo $product['category_name']; ?>**</p>
        <hr>
        
        <h2 class="text-danger mb-4"><?php echo number_format($product['price'], 2); ?> บาท</h2>
        
        <form action="cart/cart_action.php?action=add" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <div class="mb-3 d-flex align-items-center">
                <label for="quantity" class="form-label me-3 fs-5">จำนวน:</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" style="width: 100px;" required>
                <span class="ms-3 text-muted">คงเหลือ: <?php echo $product['stock_quantity']; ?> ชิ้น</span>
            </div>
            <?php if ($product['stock_quantity'] > 0): ?>
                <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-cart-plus"></i> หยิบลงตะกร้า</button>
            <?php else: ?>
                <button type="button" class="btn btn-secondary btn-lg" disabled>สินค้าหมด</button>
            <?php endif; ?>
        </form>

        <h4 class="mt-5">รายละเอียดสินค้า</h4>
        <p><?php echo nl2br($product['description']); ?></p>

    </div>
</div>

<?php 
include('layout/footer.php'); 
mysqli_close($conn); 
?>