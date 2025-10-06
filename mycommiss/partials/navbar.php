<?php
// แยก path สำหรับการลิงก์
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$home = $base === "" ? "/" : $base . "/";
?>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?php echo $home; ?>">MyCommiss</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <form class="d-flex ms-lg-3 my-3 my-lg-0" action="<?php echo $home; ?>search.php" method="get" role="search">
        <input class="form-control me-2" name="q" type="search" placeholder="ค้นหาสินค้า..." required>
        <button class="btn btn-outline-light" type="submit"><i class="bi bi-search"></i></button>
      </form>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="<?php echo $home; ?>">หน้าร้าน</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $home; ?>categories.php">ประเภทสินค้า</a></li>
        <?php if (!empty($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo $home; ?>orders.php">ออเดอร์ของฉัน</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $home; ?>profile.php">โปรไฟล์</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $home; ?>auth/logout.php">ออกจากระบบ</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo $home; ?>auth/login.php">เข้าสู่ระบบ</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $home; ?>auth/register.php">สมัครสมาชิก</a></li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="btn btn-primary ms-lg-3" href="<?php echo $home; ?>cart.php">
            <i class="bi bi-cart"></i>
            ตะกร้า
            <?php
              $count = 0;
              if (!empty($_SESSION['cart'])) { foreach ($_SESSION['cart'] as $pid => $qty) { $count += $qty; } }
              if ($count) echo '<span class="badge bg-soft ms-1">'.$count.'</span>';
            ?>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
