<?php
session_start();
include('../../connectdb.php');
include('../check_admin.php'); // ไฟล์ตรวจสอบสิทธิ์แอดมิน (Admin Guard)

// โค้ดจัดการลบสินค้า (Delete)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $product_id_to_delete = (int)$_GET['id'];
    // ต้องตรวจสอบว่าสินค้านี้ไม่มีใน order_details ก่อน ถึงจะลบได้
    $delete_sql = "DELETE FROM products WHERE product_id = $product_id_to_delete";
    if (mysqli_query($conn, $delete_sql)) {
        $_SESSION['msg_success'] = "ลบสินค้าสำเร็จ!";
        header("Location: manage.php");
        exit();
    } else {
        $_SESSION['msg_error'] = "ลบสินค้าไม่สำเร็จ (อาจมีในรายการสั่งซื้อ): " . mysqli_error($conn);
        header("Location: manage.php");
        exit();
    }
}

// โค้ดสำหรับค้นหาสินค้า
$search_condition = '';
if (isset($_GET['keyword_admin']) && !empty($_GET['keyword_admin'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword_admin']);
    $search_condition = " WHERE p.product_name LIKE '%$keyword%' OR p.product_id LIKE '%$keyword%' ";
}

// ดึงข้อมูลสินค้า
$sql_products = "SELECT p.*, c.category_name 
                 FROM products p 
                 JOIN categories c ON p.category_id = c.category_id 
                 $search_condition
                 ORDER BY p.product_id DESC";
$result_products = mysqli_query($conn, $sql_products);

include('../layout/admin_header.php'); // ใช้ Header สำหรับ Admin
?>

<h1 class="mt-4">จัดการสินค้า</h1>

<?php if(isset($_SESSION['msg_success'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['msg_success']; unset($_SESSION['msg_success']); ?></div>
<?php endif; ?>
<?php if(isset($_SESSION['msg_error'])): ?>
    <div class="alert alert-danger"><?php echo $_SESSION['msg_error']; unset($_SESSION['msg_error']); ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between mb-3">
    <a href="add.php" class="btn btn-primary">+ เพิ่มสินค้าใหม่</a>
    <form class="d-flex" method="GET">
        <input class="form-control me-2" type="search" placeholder="ค้นหาชื่อ/รหัสสินค้า..." name="keyword_admin" value="<?php echo $_GET['keyword_admin'] ?? ''; ?>">
        <button class="btn btn-outline-success" type="submit">ค้นหา</button>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>รูปภาพ</th>
                <th>ชื่อสินค้า</th>
                <th>ประเภท</th>
                <th>ราคา</th>
                <th>คงคลัง</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result_products)): ?>
            <tr>
                <td><?php echo $row['product_id']; ?></td>
                <td><img src="<?php echo $row['image_url']; ?>" style="width: 50px; height: 50px; object-fit: cover;"></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['category_name']; ?></td>
                <td><?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo $row['stock_quantity']; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-warning">แก้ไข</a>
                    <a href="?action=delete&id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-danger" 
                       onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบสินค้านี้?');">ลบ</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php 
include('../layout/admin_footer.php');
mysqli_close($conn); 
?>