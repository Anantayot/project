<?php
session_start();
include __DIR__ . "/connectdb.php";
include __DIR__ . "/lib/functions.php";
require_login();
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/flash.php";

$id = (int)($_GET['id'] ?? 0);
$stm = $conn->prepare("SELECT * FROM orders WHERE id=? AND user_id=?");
$stm->execute([$id, $_SESSION['user']['id']]);
$o = $stm->fetch();
if (!$o) { flash('danger','ไม่พบออเดอร์'); header("Location: orders.php"); exit(); }

$stm2 = $conn->prepare("SELECT oi.*, p.name FROM order_items oi 
                        JOIN products p ON oi.product_id=p.id WHERE order_id=?");
$stm2->execute([$o['id']]);
$items = $stm2->fetchAll();
?>
<h3 class="mb-3">ออเดอร์ #<?php echo $o['id']; ?></h3>
<p class="mb-1">สถานะ: <span class="badge bg-soft text-uppercase"><?php echo htmlspecialchars($o['status']); ?></span></p>
<p class="text-secondary">สร้างเมื่อ: <?php echo htmlspecialchars($o['created_at']); ?></p>

<div class="table-responsive mb-3">
  <table class="table table-dark table-striped">
    <thead><tr><th>สินค้า</th><th>จำนวน</th><th class="text-end">ราคา/ชิ้น</th></tr></thead>
    <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td><?php echo htmlspecialchars($it['name']); ?></td>
          <td><?php echo (int)$it['qty']; ?></td>
          <td class="text-end">฿<?php echo format_price($it['unit_price']); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<h5 class="text-end">รวมทั้งหมด: ฿<?php echo format_price($o['total_price']); ?></h5>
<?php include __DIR__ . "/partials/footer.php"; ?>
