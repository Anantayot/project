<?php
$pageTitle = "จัดการลูกค้า";
include __DIR__ . "/../partials/connectdb.php";

$customers = $conn->query("SELECT * FROM customers")->fetchAll();

ob_start();
?>
<div class="card">
  <div class="card-body">
    <h4 class="mb-3"><i class="bi bi-people"></i> รายชื่อลูกค้า</h4>
    <a href="customer_add.php" class="btn btn-success mb-3">
      <i class="bi bi-plus-circle"></i> เพิ่มลูกค้า
    </a>

    <table id="dataTable" class="table table-dark table-striped text-center align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>ชื่อ-นามสกุล</th>
          <th>อีเมล</th>
          <th>เบอร์โทร</th>
          <th>ที่อยู่</th>
          <th>จัดการ</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($customers as $index => $c): ?>
          <tr>
            <td><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($c['name']) ?></td>
            <td><?= htmlspecialchars($c['email']) ?></td>
            <td><?= htmlspecialchars($c['phone']) ?></td>
            <td><?= htmlspecialchars($c['address']) ?></td>
            <td>
              <a href="customer_edit.php?id=<?= $c['customer_id'] ?>" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-square"></i>
              </a>
              <a href="customer_delete.php?id=<?= $c['customer_id'] ?>" 
                 onclick="return confirm('ต้องการลบลูกค้าคนนี้หรือไม่?')" 
                 class="btn btn-danger btn-sm">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
$(document).ready(()=>$('#dataTable').DataTable());
</script>

<?php
$pageContent = ob_get_clean();
include __DIR__ . "/../partials/layout.php";
?>
