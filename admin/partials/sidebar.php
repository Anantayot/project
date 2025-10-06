<nav class="sidebar sidebar-offcanvas" id="sidebar" style="background:#0d1117; min-height:100vh;">
  <div class="sidebar-brand-wrapper text-center py-4 border-bottom border-secondary">
    <h4 class="text-white fw-bold mb-0"><i class="bi bi-laptop"></i> MyCommiss</h4>
  </div>

  <ul class="nav flex-column mt-3">
  <li class="nav-item mb-1">
    <a href="index.php" class="nav-link d-flex align-items-center <?= basename($_SERVER['PHP_SELF'])=='index.php'?'active':'' ?>">
      <i class="bi bi-speedometer2 me-2"></i> <span>Dashboard</span>
    </a>
  </li>

  <li class="nav-item mb-1">
    <a href="product/products.php" class="nav-link d-flex align-items-center <?= strpos($_SERVER['PHP_SELF'],'product')?'active':'' ?>">
      <i class="bi bi-box-seam me-2"></i> <span>จัดการสินค้า</span>
    </a>
  </li>

  <li class="nav-item mb-1">
    <a href="categories/categories.php" class="nav-link d-flex align-items-center <?= strpos($_SERVER['PHP_SELF'],'categories')?'active':'' ?>">
      <i class="bi bi-tags me-2"></i> <span>ประเภทสินค้า</span>
    </a>
  </li>

  <li class="nav-item mb-1">
    <a href="customer/customers.php" class="nav-link d-flex align-items-center <?= strpos($_SERVER['PHP_SELF'],'customer')?'active':'' ?>">
      <i class="bi bi-people me-2"></i> <span>ลูกค้า</span>
    </a>
  </li>

  <li class="nav-item mb-1">
    <a href="order/orders.php" class="nav-link d-flex align-items-center <?= strpos($_SERVER['PHP_SELF'],'order')?'active':'' ?>">
      <i class="bi bi-bag-check me-2"></i> <span>คำสั่งซื้อ</span>
    </a>
  </li>
</ul>
    <li class="nav-item mt-4 border-top border-secondary">
      <a href="logout.php" class="nav-link text-danger d-flex align-items-center">
        <i class="bi bi-box-arrow-right me-2"></i> <span>ออกจากระบบ</span>
      </a>
    </li>
  </ul>
</nav>
