<?php
// ต้องมี session_start() และ include('connectdb.php') ในไฟล์ที่เรียก header
$sql_categories = "SELECT * FROM categories";
$result_categories = mysqli_query($conn, $sql_categories);

// นับสินค้าในตะกร้า (สมมติว่าใช้ Session)
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Snekker Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .product-img { height: 200px; object-fit: cover; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">SNEKKER SHOP</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            ประเภทสินค้า
          </a>
          <ul class="dropdown-menu">
            <?php while($row_cat = mysqli_fetch_assoc($result_categories)): ?>
                <li><a class="dropdown-item" href="category.php?cat_id=<?php echo $row_cat['category_id']; ?>">
                    <?php echo $row_cat['category_name']; ?>
                </a></li>
            <?php endwhile; ?>
          </ul>
        </li>
      </ul>
      
      <form class="d-flex me-3" action="search.php" method="GET">
        <input class="form-control me-2" type="search" placeholder="ค้นหาสินค้า..." name="keyword" required>
        <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
      </form>

      <ul class="navbar-nav">
        <li class="nav-item">
            <a class="btn btn-warning me-2" href="cart/cart.php">
                <i class="fas fa-shopping-cart"></i> ตะกร้า (<?php echo $cart_count; ?>)
            </a>
        </li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle btn btn-info text-white" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['first_name'] ?? 'สมาชิก'; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="member/profile.php">แก้ไขข้อมูลส่วนตัว</a></li>
                    <li><a class="dropdown-item" href="member/order_history.php">ประวัติการสั่งซื้อ</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="auth/logout.php">ออกจากระบบ</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li class="nav-item"><a class="btn btn-outline-light me-2" href="auth/login.php">เข้าสู่ระบบ</a></li>
            <li class="nav-item"><a class="btn btn-primary" href="auth/register.php">สมัครสมาชิก</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">