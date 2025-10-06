<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $pageTitle ?? 'MyCommiss Admin' ?></title>

  <!-- CSS -->
  <link rel="stylesheet" href="../template_corona/assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../template_corona/assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
  @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600&display=swap');
  body {
    background: #0d1117;
    color: #fff;
    overflow-x: hidden;
    font-family: 'Prompt', sans-serif;
  }
  #sidebar {
    background: #0d1117;
    position: fixed;
    top: 0; left: 0;
    width: 250px;
    height: 100vh;
    transition: all 0.3s ease;
    box-shadow: 0 0 25px rgba(0,0,0,0.6);
    z-index: 1050;
  }
  #sidebar .brand {
    font-weight: 700;
    font-size: 1.25rem;
    text-align: center;
    padding: 22px 0;
    border-bottom: 1px solid #1f1f1f;
    color: #fff;
  }
  #sidebar .brand i { color: #00d25b; }
  #sidebar ul { list-style: none; padding: 0; margin: 25px 0; }
  #sidebar ul li a {
    display: flex; align-items: center;
    padding: 12px 20px;
    color: #b0b9c4; text-decoration: none;
    border-radius: 10px; transition: 0.3s;
    font-weight: 500; margin: 6px 10px;
  }
  #sidebar ul li a:hover {
    background: linear-gradient(145deg, rgba(0,210,91,0.25), rgba(0,210,91,0.15));
    color: #00d25b; transform: translateX(5px);
  }
  #sidebar ul li a.active {
    background: linear-gradient(145deg, #00d25b, #00b14a);
    color: #fff; box-shadow: 0 0 15px rgba(0,210,91,0.5);
  }
  .logout-btn {
    display: block; width: 85%; margin: 25px auto;
    padding: 12px 0; background: linear-gradient(145deg, #e74c3c, #c0392b);
    border-radius: 10px; color: #fff; font-weight: 600;
    text-align: center; transition: 0.3s;
  }
  .logout-btn:hover {
    background: linear-gradient(145deg, #ff5240, #e74c3c);
    box-shadow: 0 0 18px rgba(231,76,60,0.6);
    transform: translateY(-2px);
  }
  .main-content { margin-left: 250px; padding: 30px; }
  </style>
</head>
<body>

<aside id="sidebar">
  <div class="brand"><i class="bi bi-laptop"></i> MyCommiss</div>
  <ul>
    <li><a href="../index.php" class="<?= basename($_SERVER['PHP_SELF'])=='index.php'?'active':'' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
    <li><a href="../product/products.php" class="<?= strpos($_SERVER['PHP_SELF'],'product')?'active':'' ?>"><i class="bi bi-box-seam"></i> จัดการสินค้า</a></li>
    <li><a href="../categories/categories.php" class="<?= strpos($_SERVER['PHP_SELF'],'categories')?'active':'' ?>"><i class="bi bi-tags"></i> ประเภทสินค้า</a></li>
    <li><a href="../customer/customers.php" class="<?= strpos($_SERVER['PHP_SELF'],'customer')?'active':'' ?>"><i class="bi bi-people"></i> ลูกค้า</a></li>
    <li><a href="../order/orders.php" class="<?= strpos($_SERVER['PHP_SELF'],'order')?'active':'' ?>"><i class="bi bi-bag-check"></i> คำสั่งซื้อ</a></li>
  </ul>
  <a href="../logout.php" class="logout-btn"><i class="bi bi-box-arrow-right me-2"></i> ออกจากระบบ</a>
</aside>

<!-- ✅ โหลด jQuery ก่อน $pageContent -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- เนื้อหาแต่ละหน้า -->
<div class="main-content">
  <?= $pageContent ?? '' ?>
</div>

<script>
document.getElementById('menuToggle')?.addEventListener('click', function(){
  document.getElementById('sidebar').classList.toggle('collapsed');
});
</script>
</body>
</html>
