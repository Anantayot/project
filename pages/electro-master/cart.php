<?php
session_start();
include "partials/connectdb.php";
include "partials/header.php";

// เพิ่มสินค้าเข้าตะกร้า
if (isset($_GET['add'])) {
  $id = $_GET['add'];
  $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
  header("Location: cart.php");
  exit;
}

// ลบสินค้าออกจากตะกร้า
if (isset($_GET['remove'])) {
  $id = $_GET['remove'];
  unset($_SESSION['cart'][$id]);
  header("Location: cart.php");
  exit;
}

// ดึงข้อมูลสินค้าที่อยู่ในตะกร้า
$cartItems = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
  $ids = implode(',', array_keys($_SESSION['cart']));
  $stmt = $conn->query("SELECT * FROM product WHERE p_id IN ($ids)");
  $cartItems = $stmt->fetchAll();

  foreach ($cartItems as $item) {
    $total += $item['p_price'] * $_SESSION['cart'][$item['p_id']];
  }
}
?>

<div class="section">
  <div class="container">
    <h3 class="title text-center mb-4">ตะกร้าสินค้า</h3>

    <?php if (empty($cartItems)): ?>
      <div class="alert alert-warning text-center">ยังไม่มีสินค้าในตะกร้า</div>
    <?php else: ?>
      <table class="table table-bordered text-center">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>สินค้า</th>
            <th>จำนวน</th>
            <th>ราคา (฿)</th>
            <th>รวม (฿)</th>
            <th>ลบ</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cartItems as $i => $item): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($item['p_name']) ?></td>
            <td><?= $_SESSION['cart'][$item['p_id']] ?></td>
            <td><?= number_format($item['p_price'], 2) ?></td>
            <td><?= number_format($item['p_price'] * $_SESSION['cart'][$item['p_id']], 2) ?></td>
            <td>
              <a href="?remove=<?= $item['p_id'] ?>" class="btn btn-danger btn-sm">
                <i class="fa fa-trash"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <h4 class="text-end text-success">
        <i class="fa fa-money"></i> ยอดรวมทั้งหมด: <?= number_format($total, 2) ?> ฿
      </h4>

      <div class="text-end mt-3">
        <a href="checkout.php" class="btn btn-primary">
          <i class="fa fa-check"></i> ดำเนินการสั่งซื้อ
        </a>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include "partials/footer.php"; ?>
