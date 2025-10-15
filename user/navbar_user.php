<?php
// ✅ ตรวจสอบ Session
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">🖥 MyCommiss</a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a href="index.php" class="nav-link">หน้าร้าน</a></li>
      <li class="nav-item"><a href="cart.php" class="nav-link">ตะกร้า</a></li>

      <?php if (isset($_SESSION['customer_id'])): ?>
        <li class="nav-item"><a href="orders.php" class="nav-link">คำสั่งซื้อของฉัน</a></li>
        <li class="nav-item">
          <span class="nav-link text-info fw-semibold">
            👤 <?= htmlspecialchars($_SESSION['customer_name']) ?>
          </span>
        </li>
        <li class="nav-item"><a href="logout.php" class="nav-link text-danger">ออกจากระบบ</a></li>
      <?php else: ?>
        <li class="nav-item"><a href="login.php" class="nav-link">เข้าสู่ระบบ</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
