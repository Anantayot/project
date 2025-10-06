<?php
session_start();
include "partials/connectdb.php";
include "partials/header.php";

if (empty($_SESSION['cart'])) {
  echo "<script>alert('ยังไม่มีสินค้าในตะกร้า');window.location='index.php';</script>";
  exit;
}

// เมื่อผู้ใช้กดสั่งซื้อ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $phone = trim($_POST['phone']);
  $address = trim($_POST['address']);
  $payment = $_POST['payment'];

  $total = 0;
  $ids = implode(',', array_keys($_SESSION['cart']));
  $stmt = $conn->query("SELECT * FROM product WHERE p_id IN ($ids)");
  $items = $stmt->fetchAll();

  foreach ($items as $item) {
    $total += $item['p_price'] * $_SESSION['cart'][$item['p_id']];
  }

  // เพิ่มคำสั่งซื้อ
  $conn->prepare("INSERT INTO orders (customer_id, order_date, total_price, payment_status, order_status) 
                  VALUES (NULL, NOW(), ?, ?, ?)")
        ->execute([$total, $payment == 'cod' ? 'รอชำระเงิน' : 'ชำระแล้ว', 'กำลังดำเนินการ']);
  $order_id = $conn->lastInsertId();

  // เพิ่มรายละเอียดคำสั่งซื้อ
  $detail = $conn->prepare("INSERT INTO order_details (order_id, p_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
  foreach ($items as $item) {
    $qty = $_SESSION['cart'][$item['p_id']];
    $subtotal = $item['p_price'] * $qty;
    $detail->execute([$order_id, $item['p_id'], $qty, $item['p_price'], $subtotal]);
  }

  unset($_SESSION['cart']);
  echo "<script>alert('สั่งซื้อสำเร็จแล้ว!');window.location='index.php';</script>";
  exit;
}
?>

<div class="section">
  <div class="container">
    <h3 class="title text-center mb-4">ข้อมูลการสั่งซื้อ</h3>
    <form method="post">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label>ชื่อ-นามสกุล</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label>เบอร์โทรศัพท์</label>
          <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="col-md-12 mb-3">
          <label>ที่อยู่จัดส่ง</label>
          <textarea name="address" class="form-control" required></textarea>
        </div>
        <div class="col-md-6 mb-3">
          <label>วิธีชำระเงิน</label>
          <select name="payment" class="form-control">
            <option value="cod">ชำระเงินปลายทาง</option>
            <option value="bank">โอนผ่านธนาคาร</option>
          </select>
        </div>
      </div>

      <div class="text-end mt-4">
        <button class="btn btn-success">
          <i class="fa fa-check"></i> ยืนยันคำสั่งซื้อ
        </button>
      </div>
    </form>
  </div>
</div>

<?php include "partials/footer.php"; ?>
