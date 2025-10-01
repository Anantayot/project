<?php 
    include 'includes/connectdb.php'; // ไฟล์เชื่อมต่อฐานข้อมูล
    include 'includes/header.php'; 

    // ในระบบจริง จะต้องมีการดึงข้อมูลสินค้าจากฐานข้อมูล
    // ตัวอย่าง:
    // $stmt = $conn->prepare("SELECT * FROM products INNER JOIN categories ON products.category_id = categories.category_id");
    // $stmt->execute();
    // $products = $stmt->fetchAll();

    // ข้อมูลตัวอย่างสำหรับแสดงผล (แทนการเชื่อมต่อ DB จริง)
    $products = [
        ['id' => 1, 'name' => 'เสื้อยืดคอกลมสีขาว', 'category' => 'เสื้อผ้า', 'price' => 250.00, 'stock' => 50],
        ['id' => 2, 'name' => 'กางเกงยีนส์ขายาว', 'category' => 'เสื้อผ้า', 'price' => 890.00, 'stock' => 20],
        ['id' => 3, 'name' => 'นาฬิกาข้อมือ', 'category' => 'เครื่องประดับ', 'price' => 1500.00, 'stock' => 10],
    ];

    // โค้ดสำหรับรับค่าค้นหา (ถ้ามี)
    $search_term = $_GET['search'] ?? ''; 
?>

<h1 class="mb-4"><i class="bi bi-box-seam"></i> จัดการสินค้า</h1>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <form class="d-flex" action="manage_products.php" method="GET">
                <input class="form-control me-2" type="search" placeholder="ค้นหาสินค้า" name="search" value="<?= htmlspecialchars($search_term) ?>">
                <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i> ค้นหา</button>
            </form>
            <a href="product_form.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> เพิ่มสินค้าใหม่
            </a>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        รายการสินค้าทั้งหมด
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
                                <td><?= $product['id'] ?></td>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['category']) ?></td>
                                <td><?= number_format($product['price'], 2) ?> บาท</td>
                                <td><span class="badge <?= ($product['stock'] > 0) ? 'bg-success' : 'bg-danger' ?>"><?= $product['stock'] ?></span></td>
                                <td>
                                    <a href="product_form.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil-square"></i> แก้ไข
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>')">
                                        <i class="bi bi-trash"></i> ลบ
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">ไม่พบข้อมูลสินค้า</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    if (confirm(`คุณต้องการลบสินค้า "${name}" ใช่หรือไม่?`)) {
        // ในระบบจริง: เชื่อมไปยังไฟล์ PHP ที่ทำการลบสินค้า (เช่น delete_product.php?id=...)
        console.log('ลบสินค้า ID:', id); 
        // window.location.href = 'delete_product.php?id=' + id;
    }
}
</script>

<?php include 'includes/footer.php'; ?>