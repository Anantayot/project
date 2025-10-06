<?php
include __DIR__ . "/partials/connectdb.php";

// ดึงสินค้าจากฐานข้อมูล
$stmt = $conn->query("SELECT * FROM product ORDER BY p_id DESC LIMIT 8");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>MyCommiss Store</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>
    <link type="text/css" rel="stylesheet" href="css/slick.css"/>
    <link type="text/css" rel="stylesheet" href="css/slick-theme.css"/>
    <link type="text/css" rel="stylesheet" href="css/nouislider.min.css"/>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="css/style.css"/>
</head>

<body>
    <?php include "partials/header.php"; ?>

    <!-- SECTION: สินค้าใหม่ล่าสุด -->
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">สินค้าใหม่ล่าสุด</h3>
                    </div>
                </div>

                <?php if (empty($products)): ?>
                    <div class="col-12 text-center">
                        <div class="alert alert-warning">ยังไม่มีสินค้าในระบบ</div>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <div class="col-md-3 col-xs-6">
                            <div class="product">
                                <div class="product-img">
                                    <img src="../../admin/assets/img/<?= htmlspecialchars($p['p_image']) ?>" alt="<?= htmlspecialchars($p['p_name']) ?>" style="height:220px;object-fit:cover;">
                                    <div class="product-label"><span class="new">NEW</span></div>
                                </div>
                                <div class="product-body">
                                    <p class="product-category"><?= htmlspecialchars($p['p_description']) ?></p>
                                    <h3 class="product-name"><a href="#"><?= htmlspecialchars($p['p_name']) ?></a></h3>
                                    <h4 class="product-price"><?= number_format($p['p_price'], 2) ?> ฿</h4>
                                </div>
                                <div class="add-to-cart">
                                    <button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> เพิ่มลงตะกร้า</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include "partials/footer.php"; ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/nouislider.min.js"></script>
    <script src="js/jquery.zoom.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
