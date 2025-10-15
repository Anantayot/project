<?php
session_start();
$totalQty = 0;
if (!empty($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $it) $totalQty += (int)$it['qty'];
}
?>
<!-- ตัวอย่างไอคอน -->
<a href="cart.php" class="header-cart">
  <i class="fa fa-shopping-cart"></i>
  <span id="cart-count"><?= $totalQty ?></span>
</a>

<style>
.header-cart { position: relative; display:inline-flex; align-items:center; gap:6px; }
#cart-count{
  display:inline-block; min-width:20px; padding:2px 6px;
  font-size:12px; line-height:1; text-align:center;
  background:#D10024; color:#fff; border-radius:999px;
}
</style>
