<?php 
include 'includes/connectdb.php';
include 'includes/header.php'; 

// --- ส่วน PHP สำหรับดึงข้อมูล ---
$products = [];
$search_term = $_GET['search'] ?? '';

try {
    $sql = "SELECT p.*, c.name AS category_name 
            FROM products p
            INNER JOIN categories c ON p.category_id = c.category_id
            WHERE p.name LIKE :search_term OR c.name LIKE :search_term 
            ORDER BY p.product_id DESC";
    
    $stmt = $conn->prepare($sql);
    $search_param = '%' . $search_term . '%';
    $stmt->bindParam(':search_term', $search_param);
    $stmt->execute();
    $products = $stmt->fetchAll();

} catch (PDOException $e) {
    // จัดการข้อผิดพลาดในการดึงข้อมูล
    // echo "Error: " . $e->getMessage();
}

// โค้ดสำหรับจัดการการลบสินค้า (ต้องทำในไฟล์แยก เช่น delete_product.php)
// ... 
?>

<h1 class="mb-4"><i class="bi bi-box-seam"></i> จัดการสินค้า</h1>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <form class="d-flex" action="manage_products.php" method="GET">
                <input class="form-control me-2" type="search" placeholder="ค้นหาสินค้าหรือประเภท..." name="search" value="<?= htmlspecialchars($search_term) ?>">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i> ค้นหา</button>
            </form>
            <a href="product_form.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> เพิ่มสินค้าใหม่
            </a>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header card-header-custom">
        รายการสินค้าทั้งหมด (<?= count($products) ?> รายการ)
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ชื่อสินค้า</th>
                        <th>ประเภท</th>
                        <th>ราคา</th>
                        <th>สต็อก</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product['product_id'] ?></td>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['category_name']) ?></td>
                                <td><?= number_format($product['price'], 2) ?></td>
                                <td><span class="badge <?= ($product['stock_quantity'] > 0) ? 'bg-success' : 'bg-danger' ?>"><?= $product['stock_quantity'] ?></span></td>
                                <td>
                                    <a href="product_form.php?id=<?= $product['product_id'] ?>" class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil-square"></i> แก้ไข
                                    </a>
                                    <a href="delete_product.php?id=<?= $product['product_id'] ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('คุณต้องการลบสินค้า <?= htmlspecialchars($product['name']) ?> ใช่หรือไม่?');">
                                        <i class="bi bi-trash"></i> ลบ
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">ไม่พบข้อมูลสินค้า<?= $search_term ? " สำหรับการค้นหา: **$search_term**" : "" ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>