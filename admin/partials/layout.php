<nav id="sidebar" class="sidebar sidebar-offcanvas" style="background:#0d1117; min-height:100vh; border-right:1px solid #2c313a;">
  <div class="sidebar-brand-wrapper text-center py-4 border-bottom border-secondary d-flex justify-content-between align-items-center px-3">
    <h4 class="text-white fw-bold mb-0">
      <i class="bi bi-laptop"></i> MyCommiss
    </h4>
    <button class="btn btn-sm text-light d-lg-none" id="sidebarToggle">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>

  <ul class="nav flex-column mt-3">
    <li class="nav-item mb-1">
      <a href="/project/admin/index.php" class="nav-link d-flex align-items-center <?= basename($_SERVER['PHP_SELF'])=='index.php'?'active':'' ?>">
        <i class="bi bi-speedometer2 me-2"></i> <span>Dashboard</span>
      </a>
    </li>

    <li class="nav-item mb-1">
      <a href="/project/admin/product/products.php" class="nav-link d-flex align-items-center <?= strpos($_SERVER['PHP_SELF'],'product')?'active':'' ?>">
        <i class="bi bi-box-seam me-2"></i> <span>จัดการสินค้า</span>
      </a>
    </li>

    <li class="nav-item mb-1">
      <a href="/project/admin/categories/categories.php" class="nav-link d-flex align-items-center <?= strpos($_SERVER['PHP_SELF'],'categories')?'active':'' ?>">
        <i class="bi bi-tags me-2"></i> <span>ประเภทสินค้า</span>
      </a>
    </li>

    <li class="nav-item mb-1">
      <a href="/project/admin/customer/customers.php" class="nav-link d-flex align-items-center <?= strpos($_SERVER['PHP_SELF'],'customer')?'active':'' ?>">
        <i class="bi bi-people me-2"></i> <span>ลูกค้า</span>
      </a>
    </li>

    <li class="nav-item mb-1">
      <a href="/project/admin/order/orders.php" class="nav-link d-flex align-items-center <?= strpos($_SERVER['PHP_SELF'],'order')?'active':'' ?>">
        <i class="bi bi-bag-check me-2"></i> <span>คำสั่งซื้อ</span>
      </a>
    </li>

    <li class="nav-item mt-4 border-top border-secondary pt-2">
      <a href="/project/admin/logout.php" class="nav-link text-danger d-flex align-items-center">
        <i class="bi bi-box-arrow-right me-2"></i> <span>ออกจากระบบ</span>
      </a>
    </li>
  </ul>
</nav>

<style>
/* ✅ Sidebar Theme */
#sidebar {
  background: #0d1117 !important;
  min-height: 100vh;
  box-shadow: 0 0 20px rgba(0,0,0,0.6);
  width: 250px;
  transition: all 0.3s ease;
  z-index: 1000;
}
#sidebar .nav-link {
  color: #b0b9c4 !important;
  font-weight: 500;
  border-radius: 10px;
  margin: 4px 10px;
  transition: 0.3s ease;
  padding: 10px 18px;
}
#sidebar .nav-link:hover {
  background: linear-gradient(145deg, rgba(0,210,91,0.25), rgba(0,210,91,0.15));
  color: #00d25b !important;
  transform: translateX(4px);
}
#sidebar .nav-link.active {
  background: linear-gradient(145deg, #00d25b, #00b14a);
  color: #fff !important;
  box-shadow: 0 0 12px rgba(0,210,91,0.4);
}
#sidebar .nav-link i { font-size: 1.1rem; }
#sidebar .text-danger { font-weight: 600; transition: 0.3s; }
#sidebar .text-danger:hover { color: #ff4d4d !important; }

/* 📱 Responsive Toggle */
#sidebar.hidden {
  transform: translateX(-100%);
}
#sidebarToggle {
  background: transparent;
  border: none;
}
@media (max-width: 991px) {
  #sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    transform: translateX(-100%);
  }
  #sidebar.show {
    transform: translateX(0);
  }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const sidebar = document.getElementById("sidebar");
  const toggle = document.getElementById("sidebarToggle");
  if (toggle) {
    toggle.addEventListener("click", () => {
      sidebar.classList.toggle("show");
    });
  }
});
</script>
