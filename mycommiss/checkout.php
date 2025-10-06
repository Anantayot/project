<?php
session_start();
include __DIR__ . "/connectdb.php";
include __DIR__ . "/lib/functions.php";
require_login();
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/flash.php";

// ตระเตรียมตะกร้า
$items = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
  $ids = array_keys($_SESSION['cart']);
  $ph  = implode(",", array_fill(0, count($ids), "?"));
  $stm = $conn->prepare("SELECT * FROM products WHERE id IN ($ph)");
  $stm->execute($ids);
  $rows = $stm->fetchAll();
  foreach ($rows as $r) {
    $qty = (int)$_SESSION['cart'][$r['id']];
    $sum = $qty * (float)$r['price'];
    $total += $sum;
    $items[] = ['p'=>$r, 'qty'=>$qty, 'sum'=>$sum];
  }
}

if ($_SERVER['REQUEST_METHOD']==='POST' && $items) {
  $conn->beginTransaction();
  try {
    $ins = $conn->prepare("INSERT INTO orders(user_id,total_price,status) VALUES (?,?, 'pending')");
    $ins->execute([$_SESSION['user']['id'], $total]);
    $order_id = $conn->lastInsertId();

    $insItem = $conn->prepare("INSERT INTO order_items(order_id,product_id,qty,unit_price) VALUES (?,?,?,?)");
    foreach ($items as $it) {
      $insItem->execute([$order_id, $it['p']['id'], $it['qty'], $it['p']['price']]);
    }
    $conn->commit();
    unset($_SESSION['cart']);
    flash('success','สั่งซื้อสำเร็จ!');
    header("Location: order_view.php?id=".$order_id);
    exit();
  } catch (Exception $e) {
    $conn->rollBack();
    flash('danger','เกิดข้อผิดพลาด: '.$e->getMessage());
  }
}
?>
<h3 class="mb-3">ยืนยันคำสั่งซื้อ</h3>
<?php if (!$items): ?>
  <div class="alert alert-secondary">ตะกร้าว่าง</div>
<?php else: ?>
  <div class="table-responsive mb-3">
    <table class="table table-dark table-striped align-middle">
      <thead><tr><th>สินค้า</th><th>จำนวน</th><th class="text-end">รวม</th></tr></thead>
      <tbody>
        <?php foreach ($items as $it): ?>
          <tr>
            <td><?php echo htmlspecialchars($it['p']['name']); ?> <small class="text-secondary">฿<?php echo format_price($it['p']['price']); ?></small></td>
            <td><?php echo $it['qty']; ?></td>
            <td class="text-end">฿<?php echo format_price($it['sum']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="2" class="text-end">รวมทั้งหมด</th>
          <th class="text-end">฿<?php echo format_price($total); ?></th>
        </tr>
      </tfoot>
    </table>
  </div>
  <form method="post">
    <button class="btn btn-primary"><i class="bi bi-bag-check"></i> ยืนยันสั่งซื้อ</button>
  </form>
<?php endif; ?>
<?php include __DIR__ . "/partials/footer.php"; ?>
