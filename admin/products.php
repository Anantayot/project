<?php include 'templates/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">จัดการสินค้า</h1>
    <a href="product_add.php" class="btn btn-success">
        <i class="bi bi-plus-lg"></i> เพิ่มสินค้า
    </a>
</div>

<form method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" class="form-control" name="search" placeholder="ค้นหาชื่อสินค้า..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button class="btn btn-outline-primary" type="submit">ค้นหา</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>รูปภาพ</th>
                <th>ชื่อสินค้า</th>
                <th>ประเภท</th>
                <th>ราคา</th>
                <th>จำนวนคงคลัง</th>
                <th>สถานะ</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // โค้ดสำหรับดึงข้อมูลสินค้าจากฐานข้อมูล
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $sql = "SELECT p.*, c.name as category_name FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.category_id 
                    WHERE p.name LIKE ? 
                    ORDER BY p.product_id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['%' . $search . '%']);
            $products = $stmt->fetchAll();

            foreach ($products as $product) :
            ?>
                <tr>
                    <td><?php echo $product['product_id']; ?></td>
                    <td><img src="../<?php echo htmlspecialchars($product['image_url']); ?>" alt="" width="50"></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                    <td><?php echo number_format($product['price'], 2); ?></td>
                    <td><?php echo $product['stock_quantity']; ?></td>
                    <td>
                        <?php if ($product['is_active']) : ?>
                            <span class="badge bg-success">ใช้งาน</span>
                        <?php else : ?>
                            <span class="badge bg-danger">ไม่ใช้งาน</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="product_edit.php?id=<?php echo $product['product_id']; ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i> แก้ไข
                        </a>
                        <a href="product_delete.php?id=<?php echo $product['product_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณต้องการลบสินค้านี้ใช่หรือไม่?');">
                            <i class="bi bi-trash"></i> ลบ
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'templates/footer.php'; ?>