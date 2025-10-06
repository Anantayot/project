<!-- 🔹 sidebar.php -->
<nav class="sidebar sidebar-offcanvas" id="sidebar" style="
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 260px;
  background: #11161d;
  border-right: 1px solid #1f252e;
  box-shadow: 3px 0 10px rgba(0,0,0,0.5);
  z-index: 1000;
">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    <h4 class="text-light mt-3">💻 MyCommiss</h4>
  </div>

  <ul class="nav flex-column mt-5">
    <li class="nav-item">
      <a class="nav-link <?php if(basename($_SERVER['PHP_SELF'])=='index.php') echo 'active'; ?>" href="/project/admin/index.php">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php if(str_contains($_SERVER['PHP_SELF'], 'product')) echo 'active'; ?>" href="/project/admin/product/products.php">
        <i class="bi bi-box-seam me-2"></i> จัดการสินค้า
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php if(str_contains($_SERVER['PHP_SELF'], 'categories')) echo 'active'; ?>" href="/project/admin/categories/categories.php">
        <i class="bi bi-tags me-2"></i> ประเภทสินค้า
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php if(str_contains($_SERVER['PHP_SELF'], 'customer')) echo 'active'; ?>" href="/project/admin/customer/customers.php">
        <i class="bi bi-people me-2"></i> ลูกค้า
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php if(str_contains($_SERVER['PHP_SELF'], 'order')) echo 'active'; ?>" href="/project/admin/order/orders.php">
        <i class="bi bi-bag-check me-2"></i> คำสั่งซื้อ
      </a>
    </li>
  </ul>

  <div class="mt-auto p-3">
    <a href="/project/admin/logout.php" class="btn btn-danger w-100">
      <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
    </a>
  </div>
</nav>
