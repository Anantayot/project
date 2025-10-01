// โครงสร้าง PHP
// 1. ดึงรายการประเภทสินค้าทั้งหมดจากตาราง categories
// 2. ตรวจสอบว่ามี $_GET['id'] หรือไม่:
//    - ถ้ามี: ดึงข้อมูลสินค้าเดิมมาใส่ในฟอร์ม (โหมดแก้ไข)
//    - ถ้าไม่มี: ฟอร์มว่างเปล่า (โหมดเพิ่มใหม่)
// 3. เมื่อมีการ POST: ทำการ INSERT (เพิ่ม) หรือ UPDATE (แก้ไข) ข้อมูลลงในตาราง products
// 4. Redirect กลับไปที่ manage_products.php เมื่อเสร็จสิ้น

/* โครงสร้าง HTML */
// <form method="POST" action="product_form.php">
//    <div class="mb-3">
//        <label class="form-label">ชื่อสินค้า</label>
//        <input type="text" name="name" class="form-control" value="<?= $product['name'] ?? '' ?>">
//    </div>
//    <div class="mb-3">
//        <label class="form-label">ประเภทสินค้า</label>
//        <select name="category_id" class="form-select">...</select>
//    </div>
//    ...
//    <button type="submit" class="btn btn-primary">บันทึก</button>
//    <a href="manage_products.php" class="btn btn-secondary">ยกเลิก/กลับ</a>
// </form>