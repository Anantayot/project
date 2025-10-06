<?php
include __DIR__ . "/../partials/connectdb.php";

$pageTitle = "จัดการสินค้า";
ob_start();

// ดึงข้อมูลสินค้าทั้งหมด (JOIN กับหมวดหมู่)
$sql = "SELECT p.*, c.cat_name 
        FROM product p
        LEFT JOIN category c ON p.cat_id = c.cat_id
        ORDER BY p.p_id DESC";
$products = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
  <div class="card-body">
    <h4 class="mb-3"><i class="bi bi-box-seam"></i> รายการสินค้า</h4>
    <a href="product_add.php" class="btn btn-success mb-3">
      <i class="bi bi-plus-circle"></i> เพิ่มสินค้า
    </a>

    <table id="dataTable" class="table table-dark table-striped text-center align-middle">
      <thead>
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
          <tr><td colspan="7" class="text-center text-muted">ไม่มีข้อมูลสินค้า</td></tr>
        <?php else: ?>
          <?php foreach($products as $index => $p): ?>
          <tr>
            <td><?= $index+1 ?></td>
            <td>
              <?php if(!empty($p['p_image'])): ?>
                <img src="../../assets/img/<?= htmlspecialchars($p['p_image']) ?>" width="60" class="rounded">
              <?php else: ?>
                <span class="text-muted">ไม่มีรูป</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($p['p_name']) ?></td>
            <td><?= number_format($p['p_price'], 2) ?></td>
            <td><?= htmlspecialchars($p['cat_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($p['p_stock']) ?></td>
            <td>
              <a href="product_edit.php?id=<?= $p['p_id'] ?>" class="btn btn-warning btn-sm">
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

<script>
$(document).ready(()=>$('#dataTable').DataTable());
</script>

<?php
$pageContent = ob_get_clean();
include "../partials/layout.php";
?>
