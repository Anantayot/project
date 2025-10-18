<?php
// ✅ ตรวจสอบ Session (ป้องกันการเรียกซ้ำ)
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom sticky-top">
  <div class="container">
    <!-- 🔹 โลโก้ร้าน -->
    <a class="navbar-brand fw-bold text-primary" href="index.php">
      <i class="bi bi-cpu"></i> MyCommiss
    </a>

    <!-- 🔹 ปุ่มแสดงเมนูในมือถือ -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- 🔹 เมนูด้านขวา -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a href="index.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active text-primary fw-semibold' : '' ?>">
            🏠 หน้าร้าน
          </a>
        </li>

        <li class="nav-item">
          <a href="cart.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active text-primary fw-semibold' : '' ?>">
            🛒 ตะกร้า
          </a>
        </li>

        <?php if (isset($_SESSION['customer_id'])): ?>
          <li class="nav-item">
            <a href="orders.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active text-primary fw-semibold' : '' ?>">
              📦 คำสั่งซื้อของฉัน
            </a>
          </li>

          <!-- 🔹 ชื่อผู้ใช้ -->
          <li class="nav-item">
            <a href="profile.php" 
               class="nav-link user-link <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">
              👤 <?= htmlspecialchars($_SESSION['customer_name']) ?>
            </a>
          </li>

          <!-- 🔹 ปุ่มออกจากระบบ -->
          <li class="nav-item">
            <a href="#" class="nav-link text-danger fw-semibold" onclick="confirmLogout(event)">
              🚪 ออกจากระบบ
            </a>
          </li>

        <?php else: ?>
          <!-- 🔹 ยังไม่ล็อกอิน -->
          <li class="nav-item">
            <a href="login.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active text-primary fw-semibold' : '' ?>">
              🔑 เข้าสู่ระบบ
            </a>
          </li>
          <li class="nav-item">
            <a href="register.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active text-primary fw-semibold' : '' ?>">
              📝 สมัครสมาชิก
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- ✅ Confirm Logout -->
<script>
function confirmLogout(e) {
  e.preventDefault();
  if (confirm("คุณแน่ใจหรือไม่ว่าต้องการออกจากระบบ?")) {
    window.location = "logout.php";
  }
}
</script>

<!-- ✅ สไตล์ตกแต่ง Navbar -->
<style>
.navbar-brand {
  letter-spacing: 0.5px;
  font-size: 1.5rem;
}
.nav-link {
  color: #333 !important;
  transition: 0.2s ease-in-out;
  font-weight: 500;
}
.nav-link:hover {
  color: #0d6efd !important;
}
.nav-link.active {
  color: #0d6efd !important;
  border-bottom: 2px solid #0d6efd;
}

/* 💎 ปรับสีชื่อผู้ใช้ */
.user-link {
  color: #0d6efd !important;
  transition: 0.2s ease;
  text-decoration: none !important;
}
.user-link:hover {
  color: #0040ff !important;
  text-decoration: none !important;
}
.user-link.active {
  color: #0d6efd !important;
  text-shadow: 0 0 6px rgba(13, 110, 253, 0.4);
  text-decoration: none !important;
}
</style>
