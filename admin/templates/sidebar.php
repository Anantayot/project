<a href="dashboard.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
    <i class="bi bi-person-circle fs-4 me-2"></i>
    <span class="fs-4">ผู้ดูแลระบบ</span>
</a>
<hr>
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="dashboard.php" class="nav-link text-white" aria-current="page">
            <i class="bi bi-speedometer2 me-2"></i> แดชบอร์ด
        </a>
    </li>
    <li>
        <a href="orders.php" class="nav-link text-white">
            <i class="bi bi-table me-2"></i> จัดการออเดอร์
        </a>
    </li>
    <li>
        <a href="products.php" class="nav-link text-white">
            <i class="bi bi-grid me-2"></i> จัดการสินค้า
        </a>
    </li>
    <li>
        <a href="categories.php" class="nav-link text-white">
            <i class="bi bi-tags me-2"></i> จัดการประเภทสินค้า
        </a>
    </li>
    <li>
        <a href="customers.php" class="nav-link text-white">
            <i class="bi bi-people me-2"></i> จัดการลูกค้า
        </a>
    </li>
</ul>
<hr>
<div class="dropdown">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
        <strong><?php echo $_SESSION['admin_login']; ?></strong>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
        <li><a class="dropdown-item" href="logout.php">ออกจากระบบ</a></li>
    </ul>
</div>