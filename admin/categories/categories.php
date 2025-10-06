<?php
$pageTitle = "จัดการประเภทสินค้า";
ob_start();

include __DIR__ . "/../../admin/partials/connectdb.php"; // ✅ เชื่อมต่อฐานข้อมูล
?>

<div class="card">
  <div class="card-body">
    <h4 class="mb-3"><i class="bi bi-tags"></i> รายการประเภทสินค้า</h4>
    <a href="category_add.php" class="btn btn-success mb-3"><i class="bi bi-plus-circle"></i> เพิ่มประเภทสินค้า</a>

    <table id="dataTable" class="table table-dark table-striped text-center align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>ชื่อประเภท</th>
          <th>รายละเอียด</th>
          <th>จัดการ</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $stmt = $conn->query("SELECT * FROM category ORDER BY cat_id DESC");
        $cats = $stmt->fetchAll();

        if (empty($cats)) {
          echo "<tr><td colspan='4' class='text-center text-muted'>ยังไม่มีข้อมูลประเภทสินค้า</td></tr>";
        } else {
          foreach ($cats as $i => $c): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($c['cat_name']) ?></td>
              <td><?= htmlspecialchars($c['cat_description']) ?></td>
              <td>
                <a href="category_edit.php?id=<?= $c['cat_id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                <a href="category_delete.php?id=<?= $c['cat_id'] ?>" onclick="return confirm('ยืนยันการลบประเภทสินค้านี้?')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
              </td>
            </tr>
          <?php endforeach;
        }
        ?>
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
