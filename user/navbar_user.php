<?php
// ✅ ตรวจสอบ Session (ป้องกันการเรียกซ้ำ)
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
      🖥 MyCommiss
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a href="index.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
            🏠 หน้าร้าน
          </a>
        </li>

        <li class="nav-item">
          <a href="cart.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : '' ?>">
            🛒 ตะกร้า
          </a>
        </li>

        <?php if (isset($_SESSION['customer_id'])): ?>
          <li class="nav-item">
            <a href="orders.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : '' ?>">
              📦 คำสั่งซื้อของฉัน
            </a>
          </li>

          <!-- แสดงชื่อผู้ใช้ -->
          <li class="nav-item">
            <span class="nav-link text-info fw-semibold">
              👤 <?= htmlspecialchars($_SESSION['customer_name']) ?>
            </span>
          </li>

          <!-- ปุ่มออกจากระบบ -->
          <li class="nav-item">
            <a href="logout.php" class="nav-link text-danger">
              🚪 ออกจากระบบ
            </a>
          </li>

        <?php else: ?>
          <!-- ยังไม่ล็อกอิน -->
          <li class="nav-item">
            <a href="login.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>">
              🔑 เข้าสู่ระบบ
            </a>
          </li>
          <li class="nav-item">
            <a href="register.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active' : '' ?>">
              📝 สมัครสมาชิก
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
