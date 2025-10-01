<?php
session_start();
include('connectdb.php'); // เชื่อมต่อฐานข้อมูล

// ดึงข้อมูลสินค้าทั้งหมด
$sql_products = "SELECT * FROM products ORDER BY added_date DESC LIMIT 8";
$result_products = mysqli_query($conn, $sql_products);

// ดึงข้อมูลประเภทสินค้าสำหรับเมนู
$sql_categories = "SELECT * FROM categories";
$result_categories = mysqli_query($conn, $sql_categories);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>หน้าแรก - IHAVEJIB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">IHAVEJIB</a>
    <div class="collapse navbar-collapse">
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
        <input class="form-control me-2" type="search" placeholder="ค้นหาสินค้า..." name="keyword">
        <button class="btn btn-outline-light" type="submit">ค้นหา</button>
      </form>

      <ul class="navbar-nav">
        <li class="nav-item">
            <a class="btn btn-warning me-2" href="cart/cart.php">ตะกร้า (0)</a>
        </li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    ยินดีต้อนรับ, <?php echo $_SESSION['first_name']; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="member/profile.php">แก้ไขข้อมูลส่วนตัว</a></li>
                    <li><a class="dropdown-item" href="member/order_history.php">ประวัติการสั่งซื้อ</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="auth/logout.php">ออกจากระบบ</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="auth/login.php">เข้าสู่ระบบ</a></li>
            <li class="nav-item"><a class="btn btn-primary" href="auth/register.php">สมัครสมาชิก</a></li>
        <?php endif; ?>
      </ul>

    </div>
  </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">สินค้าแนะนำ</h2>
    <div class="row">
        <?php while($row = mysqli_fetch_assoc($result_products)): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="<?php echo $row['product_name']; ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                    <p class="card-text text-danger fs-4 mt-auto"><?php echo number_format($row['price'], 2); ?> บาท</p>
                    <div class="d-flex justify-content-between">
                        <a href="product_detail.php?id=<?php echo $row['product_id']; ?>" class="btn btn-outline-primary btn-sm">รายละเอียด</a>
                        <a href="cart/cart.php?action=add&id=<?php echo $row['product_id']; ?>" class="btn btn-success btn-sm">หยิบลงตะกร้า</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    
    <div class="text-center mt-4">
        <a href="category.php" class="btn btn-lg btn-secondary">ดูสินค้าทั้งหมด</a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>