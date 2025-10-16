<?php
session_start();
include("connectdb.php");

// 🔒 ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$customer_id = $_SESSION['customer_id'];

// ✅ ดึงข้อมูลผู้ใช้
$stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id = ?");
$stmt->execute([$customer_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  die("<p class='text-center text-danger mt-5'>❌ ไม่พบข้อมูลผู้ใช้</p>");
}

// ✅ อัปเดตข้อมูลเมื่อกดบันทึก
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $address = trim($_POST['address']);

  // ✅ ตรวจสอบความถูกต้องของเบอร์โทรศัพท์ (10 หลัก ตัวเลขเท่านั้น)
  if (!preg_match('/^[0-9]{10}$/', $phone)) {
    $_SESSION['toast_error'] = "❌ กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง (10 หลัก)";
    header("Location: profile.php");
    exit;
  } else {
    // ✅ บันทึกข้อมูลลงฐานข้อมูล
    $stmt = $conn->prepare("UPDATE customers 
                            SET name = ?, email = ?, phone = ?, address = ? 
                            WHERE customer_id = ?");
    $stmt->execute([$name, $email, $phone, $address, $customer_id]);

    // ✅ อัปเดต session เผื่อมีการเปลี่ยนชื่อ
    $_SESSION['customer_name'] = $name;

    // ✅ บันทึก Toast ข้อความไว้ใน session
    $_SESSION['toast_success'] = "✅ บันทึกข้อมูลเรียบร้อยแล้ว";

    // ✅ กลับไปหน้า index.php พร้อม Toast
    header("Location: index.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>โปรไฟล์ของฉัน | MyCommiss</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .profile-card {
      max-width: 700px;
      margin: 40px auto;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .card-header {
      background: #212529;
      color: #fff;
      font-weight: bold;
      border-radius: 15px 15px 0 0;
    }
    .btn:hover { transform: scale(1.05); transition: 0.2s; }
    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1055;
    }
  </style>
</head>
<body>

<?php include("navbar_user.php"); ?>

<!-- 🔔 Toast แจ้งเตือน -->
<div class="toast-container">
  <?php if (isset($_SESSION['toast_error'])): ?>
    <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
      <div class="d-flex">
        <div class="toast-body">
          <?= $_SESSION['toast_error'] ?>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
    <?php unset($_SESSION['toast_error']); ?>
  <?php endif; ?>
</div>

<div class="container">
  <div class="profile-card">
    <div class="card-header text-center py-3">
      👤 โปรไฟล์ของฉัน
    </div>
    <div class="card-body p-4">

      <form method="POST">
        <div class="mb-3">
          <label class="form-label fw-semibold">ชื่อ - นามสกุล</label>
          <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">อีเมล</label>
          <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">เบอร์โทรศัพท์</label>
          <input 
            type="text" 
            name="phone" 
            value="<?= htmlspecialchars($user['phone']) ?>" 
            class="form-control"
            maxlength="10"
            pattern="[0-9]{10}"
            title="กรุณากรอกหมายเลขโทรศัพท์ 10 หลัก"
            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);"
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">ที่อยู่จัดส่ง</label>
          <textarea name="address" rows="3" class="form-control"><?= htmlspecialchars($user['address']) ?></textarea>
        </div>

        <!-- ✅ ปุ่มอยู่ในแถวเดียวกันและจัดตรงกลาง -->
        <div class="d-flex justify-content-center align-items-center gap-3 mt-4 flex-wrap">
          <a href="index.php" class="btn btn-secondary">
            ⬅️ กลับหน้าหลัก
          </a>

          <a href="change_password.php" class="btn btn-success text-white">
            🔑 เปลี่ยนรหัสผ่าน
          </a>

          <button type="submit" class="btn btn-primary">
            💾 บันทึกข้อมูล
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<footer class="text-center py-3 mt-5 bg-dark text-white">
  © <?= date('Y') ?> MyCommiss | โปรไฟล์ผู้ใช้
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // ✅ ตั้งเวลาให้ Toast แสดง 5 วิ แล้วปิดอัตโนมัติ
  const toastElList = [].slice.call(document.querySelectorAll('.toast'));
  const toastList = toastElList.map(function (toastEl) {
    return new bootstrap.Toast(toastEl, { delay: 5000, autohide: true });
  });
  toastList.forEach(toast => toast.show());
</script>

</body>
</html>
