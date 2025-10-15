<?php
session_start();
include("connectdb.php");

// ✅ ต้องเข้าสู่ระบบก่อน
if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$cid = $_SESSION['customer_id'];

// ✅ ดึงข้อมูลลูกค้าจากฐานข้อมูล
$stmtUser = $conn->prepare("SELECT * FROM customers WHERE customer_id = ?");
$stmtUser->execute([$cid]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

// ✅ ตรวจสอบตะกร้า
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
  echo "<script>alert('ตะกร้าสินค้าว่าง'); window.location='cart.php';</script>";
  exit;
}

// ✅ เมื่อผู้ใช้กดยืนยันคำสั่งซื้อ
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = trim($_POST['name']);
  $address = trim($_POST['address']);
  $phone = trim($_POST['phone']);
  $payment = $_POST['payment'];

  if (empty($name) || empty($address) || empty($phone)) {
    echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน');</script>";
  } else {
    try {
      $conn->beginTransaction();

      // ✅ เพิ่มคำสั่งซื้อ
      $stmt = $conn->prepare("INSERT INTO orders 
        (customer_id, customer_name, customer_address, customer_phone, payment_method, total_price, order_date)
        VALUES (:cid, :name, :address, :phone, :payment, :total, NOW())");

      $totalPrice = 0;
      foreach ($cart as $item) {
        $totalPrice += $item['price'] * $item['qty'];
      }

      $stmt->execute([
        ':cid' => $cid,
        ':name' => $name,
        ':address' => $address,
        ':phone' => $phone,
        ':payment' => $payment,
        ':total' => $totalPrice
      ]);

      $orderId = $conn->lastInsertId();

      // ✅ เพิ่มสินค้าใน order_details
      $stmtDetail = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price)
                                    VALUES (:oid, :pid, :qty, :price)");

      foreach ($cart as $item) {
        $stmtDetail->execute([
          ':oid' => $orderId,
          ':pid' => $item['id'],
          ':qty' => $item['qty'],
          ':price' => $item['price']
        ]);
      }

      $conn->commit();
      unset($_SESSION['cart']); // ล้างตะกร้า

      echo "<script>
        alert('✅ สั่งซื้อสำเร็จ! ขอบคุณที่ใช้บริการ');
        window.location='orders.php';
      </script>";
      exit;
    } catch (Exception $e) {
      $conn->rollBack();
      echo "<script>alert('❌ เกิดข้อผิดพลาด: " . addslashes($e->getMessage()) . "');</script>";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>MyCommiss | ชำระเงิน</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- ✅ Navbar ส่วนกลาง -->
<?php include("navbar_user.php"); ?>

<div class="container mt-4">
  <h3 class="fw-bold mb-4 text-center">💳 ยืนยันคำสั่งซื้อ</h3>

  <div class="row">
    <!-- 🔹 สินค้าในตะกร้า -->
    <div class="col-md-7 mb-4">
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white fw-semibold">สินค้าในตะกร้า</div>
        <div class="card-body">
          <table class="table table-borderless align-middle">
            <thead>
              <tr class="text-center">
                <th>สินค้า</th>
                <th>จำนวน</th>
                <th>ราคา</th>
                <th>รวม</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $total = 0;
              foreach ($cart as $item):
                $sum = $item['price'] * $item['qty'];
                $total += $sum;
              ?>
              <tr class="text-center">
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['qty'] ?></td>
                <td><?= number_format($item['price'], 2) ?></td>
                <td><?= number_format($sum, 2) ?></td>
              </tr>
              <?php endforeach; ?>
              <tr class="fw-bold text-danger text-end">
                <td colspan="3">ราคารวมทั้งหมด</td>
                <td><?= number_format($total, 2) ?> บาท</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- 🔹 ฟอร์มกรอกข้อมูล -->
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white fw-semibold">ข้อมูลการจัดส่ง</div>
        <div class="card-body">
          <form method="post">
            <div class="mb-3">
              <label class="form-label">ชื่อผู้รับ</label>
              <input type="text" name="name" class="form-control" 
                     value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">ที่อยู่จัดส่ง</label>
              <textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($user['address']) ?></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">เบอร์โทรศัพท์</label>
              <input type="text" name="phone" class="form-control" 
                     value="<?= htmlspecialchars($user['phone']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">วิธีชำระเงิน</label>
              <select name="payment" class="form-select" required>
                <option value="เก็บเงินปลายทาง">เก็บเงินปลายทาง</option>
                <option value="โอนผ่านธนาคาร">โอนผ่านธนาคาร</option>
                <option value="ชำระด้วย QR Code">ชำระด้วย QR Code</option>
              </select>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-success">✅ ยืนยันคำสั่งซื้อ</button>
              <a href="cart.php" class="btn btn-secondary mt-2">⬅️ กลับไปแก้ไขตะกร้า</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="text-center py-3 mt-5 bg-dark text-white">
  © <?= date('Y') ?> MyCommiss | ชำระเงิน
</footer>

</body>
</html>
