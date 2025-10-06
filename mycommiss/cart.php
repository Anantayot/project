<?php
session_start();
include __DIR__ . "/connectdb.php";
include __DIR__ . "/lib/functions.php";
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/flash.php";

// อัปเดต/ลบ
if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (isset($_POST['update'])) {
    foreach ($_POST['qty'] as $pid => $qty) {
      $qty = max(0, (int)$qty);
      if ($qty == 0) unset($_SESSION['cart'][$pid]);
      else $_SESSION['cart'][$pid] = $qty;
    }
    flash('success','อัปเดตตะกร้าแล้ว');
    header("Location: cart.php"); exit();
  }
}

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
?>
<h3 class="mb-3">ตะกร้าสินค้า</h3>
<form method="post">
  <div class="table-responsive">
    <table class="table table-dark table-striped align-middle">
      <thead><tr><th>สินค้า</th><th width="120">จำนวน</th><th class="text-end">ราคา</th></tr></thead>
      <tbody>
        <?php foreach ($items as $it): ?>
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <img src="uploads/<?php echo htmlspecialchars($it['p']['image'] ?: 'noimage.png'); ?>" width="60" class="rounded me-2" alt="">
                <div>
                  <div class="fw-semibold"><?php echo htmlspecialchars($it['p']['name']); ?></div>
                  <small class="text-secondary">฿<?php echo format_price($it['p']['price']); ?></small>
                </div>
              </div>
            </td>
            <td><input type="number" class="form-control" name="qty[<?php echo $it['p']['id']; ?>]" min="0" value="<?php echo $it['qty']; ?>"></td>
            <td class="text-end">฿<?php echo format_price($it['sum']); ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$items): ?>
          <tr><td colspan="3" class="text-center text-secondary">ตะกร้าว่าง</td></tr>
        <?php endif; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="2" class="text-end">รวม</th>
          <th class="text-end">฿<?php echo format_price($total); ?></th>
        </tr>
      </tfoot>
    </table>
  </div>

  <div class="d-flex justify-content-between">
    <a href="index.php" class="btn btn-outline-light"><i class="bi bi-arrow-left"></i> เลือกซื้อสินค้าต่อ</a>
    <div>
      <button class="btn btn-outline-light me-2" name="update" value="1"><i class="bi bi-arrow-repeat"></i> อัปเดตตะกร้า</button>
      <a href="<?php echo $items ? 'checkout.php' : '#'; ?>" class="btn btn-primary <?php echo $items ? '' : 'disabled'; ?>"><i class="bi bi-credit-card"></i> ดำเนินการสั่งซื้อ</a>
    </div>
  </div>
</form>
<?php include __DIR__ . "/partials/footer.php"; ?>
