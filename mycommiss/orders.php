<?php
session_start();
include __DIR__ . "/connectdb.php";
include __DIR__ . "/lib/functions.php";
require_login();
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/flash.php";

$stm = $conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY id DESC");
$stm->execute([$_SESSION['user']['id']]);
$orders = $stm->fetchAll();
?>
<h3 class="mb-3">ออเดอร์ของฉัน</h3>
<div class="table-responsive">
  <table class="table table-dark table-striped">
    <thead><tr><th>#</th><th>สถานะ</th><th class="text-end">ราคารวม</th><th>วันที่</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td><?php echo $o['id']; ?></td>
          <td><span class="badge bg-soft text-uppercase"><?php echo htmlspecialchars($o['status']); ?></span></td>
          <td class="text-end">฿<?php echo format_price($o['total_price']); ?></td>
          <td><?php echo htmlspecialchars($o['created_at']); ?></td>
          <td><a class="btn btn-sm btn-outline-light" href="order_view.php?id=<?php echo $o['id']; ?>">รายละเอียด</a></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$orders): ?>
        <tr><td colspan="5" class="text-center text-secondary">ยังไม่มีออเดอร์</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . "/partials/footer.php"; ?>
