<?php include 'templates/header.php'; ?>

<h1 class="h2">เพิ่มสินค้าใหม่</h1>

<form action="product_save.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">ชื่อสินค้า</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">รายละเอียด</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="price" class="form-label">ราคา</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="stock_quantity" class="form-label">จำนวนคงคลัง</label>
            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
        </div>
    </div>
    <div class="mb-3">
        <label for="category_id" class="form-label">ประเภทสินค้า</label>
        <select class="form-select" id="category_id" name="category_id" required>
            <option selected disabled value="">เลือกประเภท...</option>
            <?php
            // ดึงข้อมูลประเภทสินค้า
            $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
            while ($row = $stmt->fetch()) {
                echo "<option value='{$row['category_id']}'>" . htmlspecialchars($row['name']) . "</option>";
            }
            ?>
        </select>
    </div>
     <div class="mb-3">
        <label for="image" class="form-label">รูปภาพสินค้า</label>
        <input class="form-control" type="file" id="image" name="image">
    </div>
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" checked>
        <label class="form-check-label" for="is_active">
            เปิดใช้งาน
        </label>
    </div>

    <button type="submit" class="btn btn-primary">บันทึก</button>
    <a href="products.php" class="btn btn-secondary">ย้อนกลับ</a>
</form>

<?php include 'templates/footer.php'; ?>