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
     style="background: linear-gradient(145deg,#161b22,#0e1116); border:1px solid #2c313a;">
  <div class="card-body">

    <!-- ปุ่มเพิ่มสินค้า -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <h4 class="text-light fw-semibold mb-0">
        <i class="bi bi-list-ul"></i> รายการสินค้า
      </h4>
      <a href="product_add.php" class="btn btn-success btn-sm">
        <i class="bi bi-plus-circle"></i> เพิ่มสินค้า
      </a>
    </div>

    <!-- ตารางสินค้า -->
    <div class="table-responsive">
      <table id="dataTable" class="table table-dark table-striped text-center align-middle mb-0"
             style="border-radius:10px; overflow:hidden;">
        <thead style="background:linear-gradient(90deg,#00d25b,#00b14a); color:#111; font-weight:600;">
          <tr>
            <th style="width:50px;">#</th>
            <th>รูปภาพ</th>
            <th>ชื่อสินค้า</th>
            <th>ราคา (฿)</th>
            <th>หมวดหมู่</th>
            <th>สต็อก</th>
            <th style="width:120px;">จัดการ</th>
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
              <td class="text-start text-white truncate-text" title="<?= htmlspecialchars($p['p_name']) ?>">
                <?= htmlspecialchars($p['p_name']) ?>
              </td>
              <td class="text-success fw-semibold"><?= number_format($p['p_price'], 2) ?></td>
              <td class="text-info"><?= htmlspecialchars($p['cat_name'] ?? '-') ?></td>
              <td class="text-warning"><?= htmlspecialchars($p['p_stock']) ?></td>
              <td>
                <button type="button" 
                        class="btn btn-warning btn-sm"
                        data-bs-toggle="tooltip"
                        data-bs-title="แก้ไขสินค้า"
                        onclick="window.location='product_edit.php?id=<?= $p['p_id'] ?>'">
                  <i class="bi bi-pencil-square"></i>
                </button>
                <button type="button" 
                        class="btn btn-danger btn-sm"
                        data-bs-toggle="tooltip"
                        data-bs-title="ลบสินค้า"
                        onclick="if(confirm('ยืนยันการลบสินค้านี้หรือไม่?')) window.location='product_delete.php?id=<?= $p['p_id'] ?>'">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ✅ CSS -->
<style>
.truncate-text {
  max-width: 260px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.truncate-text:hover {
  background: rgba(255, 255, 255, 0.05);
}

.table-responsive {
  overflow-x: auto;
  scrollbar-width: thin;
  scrollbar-color: #2c313a #0d1117;
}

/* ปุ่มจัดการให้ชิดกัน */
.table td:last-child {
  display: flex;
  justify-content: center;
  gap: 6px;
}
.btn-sm i { font-size: 1rem; vertical-align: middle; }
.table td, .table th {
  vertical-align: middle !important;
  padding: 10px 8px !important;
}

@media (max-width: 991px) {
  .truncate-text { max-width: 150px; }
  .table td, .table th { font-size: 0.85rem; }
  .table td:last-child { flex-wrap: wrap; gap: 4px; }
}
</style>

<?php
$pageContent = ob_get_clean();
include __DIR__ . "/../partials/layout.php";
?>

<!-- ✅ DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- ✅ ตั้งค่า DataTables -->
<script>
$(document).ready(function() {
  const table = $('#dataTable').DataTable({
    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json',
      searchPlaceholder: "🔍 ค้นหาชื่อสินค้า / หมวดหมู่ / ราคา...",
      lengthMenu: "แสดง _MENU_ รายการต่อหน้า",
      zeroRecords: "ไม่พบข้อมูลที่ค้นหา",
      info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
      infoEmpty: "ไม่มีข้อมูล",
      infoFiltered: "(กรองจากทั้งหมด _MAX_ รายการ)"
    },
    pageLength: 10,
    responsive: true,
    order: [[0, "asc"]],
    columnDefs: [
      { orderable: false, targets: [1, 6] }
    ]
  });

  // 🎨 ปรับสไตล์ช่องค้นหาและ dropdown ให้เหมือนหน้า category
  $(".dataTables_filter input")
    .addClass("form-control form-control-sm ms-2")
    .css({
      "width": "250px",
      "background": "#161b22",
      "color": "#fff",
      "border": "1px solid #2c313a"
    });

  $(".dataTables_length select")
    .addClass("form-select form-select-sm bg-dark text-light border-secondary");

  // 🧩 เปิดใช้งาน tooltip
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
});
</script>
