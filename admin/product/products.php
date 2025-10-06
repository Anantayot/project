<?php
include __DIR__ . "/../partials/connectdb.php";

$pageTitle = "จัดการสินค้า";
ob_start();

// 🔹 ดึงข้อมูลสินค้าทั้งหมด (JOIN กับหมวดหมู่)
$sql = "SELECT p.*, c.cat_name 
        FROM product p
        LEFT JOIN category c ON p.cat_id = c.cat_id
        ORDER BY p.p_id DESC";
$products = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- 🔹 ส่วนหัว -->
<h3 class="mb-4 text-center fw-bold text-white">
  <i class="bi bi-box-seam"></i> จัดการสินค้า
</h3>

<div class="card shadow-lg border-0" 
     style="background: linear-gradient(145deg, #161b22, #0e1116); border:1px solid #2c313a;">
  <div class="card-body">

    <!-- 🔍 ช่องค้นหาสินค้า -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <h4 class="text-light fw-semibold mb-0"><i class="bi bi-list-ul"></i> รายการสินค้า</h4>
      <div class="d-flex align-items-center gap-2">
        <input type="text" id="customSearch" class="form-control form-control-sm text-light" 
               style="background:#161b22; border:1px solid #2c313a; width:250px;" 
               placeholder="🔍 ค้นหาชื่อสินค้า / หมวดหมู่...">
        <a href="product_add.php" class="btn btn-success btn-sm">
          <i class="bi bi-plus-circle"></i> เพิ่มสินค้า
        </a>
      </div>
    </div>

    <!-- ตารางสินค้า -->
    <div class="table-responsive">
      <table id="dataTable" class="table table-dark table-striped text-center align-middle mb-0" 
             style="border-radius:10px; overflow:hidden;">
        <thead style="background:linear-gradient(90deg,#00d25b,#00b14a); color:#111; font-weight:600;">
          <tr>
            <th>#</th>
            <th>รูปภาพ</th>
            <th>ชื่อสินค้า</th>
            <th>ราคา (฿)</th>
            <th>หมวดหมู่</th>
            <th>สต็อก</th>
            <th>จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($products)): ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">
                <i class="bi bi-info-circle"></i> ยังไม่มีข้อมูลสินค้าในระบบ
              </td>
            </tr>
          <?php else: ?>
            <?php foreach($products as $index => $p): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td>
                <?php 
                  $imagePath = __DIR__ . "/../uploads/" . $p['p_image'];
                  $imageURL  = "../uploads/" . htmlspecialchars($p['p_image']);
                  if (!empty($p['p_image']) && file_exists($imagePath)): 
                ?>
                  <img src="<?= $imageURL ?>" width="60" class="rounded border border-secondary shadow-sm">
                <?php else: ?>
                  <span class="text-muted">ไม่มีรูป</span>
                <?php endif; ?>
              </td>
              <td class="text-start text-white"><?= htmlspecialchars($p['p_name']) ?></td>
              <td class="text-success fw-semibold"><?= number_format($p['p_price'], 2) ?></td>
              <td class="text-info"><?= htmlspecialchars($p['cat_name'] ?? '-') ?></td>
              <td class="text-warning"><?= htmlspecialchars($p['p_stock']) ?></td>
              <td>
                <a href="product_edit.php?id=<?= $p['p_id'] ?>" class="btn btn-warning btn-sm me-1">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <a href="product_delete.php?id=<?= $p['p_id'] ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('ยืนยันการลบสินค้านี้หรือไม่?')">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- 🔹 Vanilla DataTables -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanilla-datatables@1.8.10/dist/vanilla-dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/vanilla-datatables@1.8.10/dist/vanilla-dataTables.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  // ✅ สร้าง DataTable โดยปิด searchable ของระบบ
  const dataTable = new DataTable("#dataTable", {
    searchable: false, // ปิด search เดิม
    sortable: true,
    perPage: 10,
    perPageSelect: [5, 10, 25, 50],
    labels: {
      perPage: "{select} รายการต่อหน้า",
      noRows: "ไม่มีข้อมูลสินค้า",
      info: "แสดง {start} ถึง {end} จากทั้งหมด {rows} รายการ"
    }
  });

  const customSearch = document.querySelector("#customSearch");

  // ✅ ฟังก์ชันกรองข้อมูลด้วย JS
  customSearch.addEventListener("input", (e) => {
    const keyword = e.target.value.toLowerCase();

    // loop ตรวจทุกแถวใน tbody
    document.querySelectorAll("#dataTable tbody tr").forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(keyword) ? "" : "none";
    });
  });
});
</script>


<?php
$pageContent = ob_get_clean();
include __DIR__ . "/../partials/layout.php";
?>
