<?php
$pageTitle = "จัดการคำสั่งซื้อ";
include __DIR__ . "/../partials/connectdb.php";
ob_start();

$sql = "SELECT o.order_id, o.order_date, o.total_price, o.order_status, c.name AS customer_name 
        FROM orders o 
        LEFT JOIN customers c ON o.customer_id = c.customer_id 
        ORDER BY o.order_id DESC";

$result = $conn->query($sql);
$orders = [];
if ($result) {
    $orders = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<h3 class="mb-4 fw-bold text-white text-center">
  <i class="bi bi-bag-check"></i> รายการคำสั่งซื้อ
</h3>

<div class="card shadow-lg border-0" style="background:linear-gradient(145deg,#161b22,#0e1116);border:1px solid #2c313a;">
  <div class="card-body">
    <?php if(empty($orders)): ?>
      <div class="alert alert-warning text-center mb-0">ยังไม่มีคำสั่งซื้อในระบบ</div>
    <?php else: ?>
    <div class="table-responsive">
      <table id="dataTable" class="table table-dark table-hover text-center align-middle mb-0" style="border-radius:10px;overflow:hidden;">
        <thead style="background:linear-gradient(90deg,#00d25b,#00b14a);color:#111;">
          <tr>
            <th>#</th>
            <th>รหัสคำสั่งซื้อ</th>
            <th>ชื่อลูกค้า</th>
            <th>วันที่สั่งซื้อ</th>
            <th>ราคารวม</th>
            <th>สถานะ</th>
            <th>จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($orders as $i => $o): ?>
          <tr>
            <td><?= $i+1 ?></td>
            <td class="fw-bold text-info">#<?= $o['order_id'] ?></td>
            <td><?= htmlspecialchars($o['customer_name'] ?? 'ไม่ระบุ') ?></td>
            <td><?= date("d/m/Y", strtotime($o['order_date'])) ?></td>
            <td class="fw-semibold text-success"><?= number_format($o['total_price'], 2) ?> ฿</td>
            <td>
              <?php
                $status = $o['order_status'] ?? 'รอดำเนินการ';
                if ($status == 'เสร็จสิ้น') {
                    $badge = 'success';
                } elseif ($status == 'กำลังดำเนินการ') {
                    $badge = 'warning text-dark';
                } elseif ($status == 'ยกเลิก') {
                    $badge = 'danger';
                } else {
                    $badge = 'secondary';
                }
              ?>
              <span class="badge bg-<?= $badge ?> px-3 py-2 rounded-pill"><?= htmlspecialchars($status) ?></span>
            </td>
            <td>
              <a href="order_view.php?id=<?= $o['order_id'] ?>" class="btn btn-outline-light btn-sm" style="border-color:#00d25b;color:#00d25b;">
                <i class="bi bi-eye"></i> ดู
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>

<script>
$(document).ready(() => {
  $('#dataTable').DataTable({
    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json' },
    pageLength: 10,
    responsive: true
  });
});
</script>

<?php
$pageContent = ob_get_clean();
include __DIR__ . "/../partials/layout.php";
?>
