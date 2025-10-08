<?php
include("connectdb.php");
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Electro - Store</title>

  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>
  <link type="text/css" rel="stylesheet" href="css/slick.css"/>
  <link type="text/css" rel="stylesheet" href="css/slick-theme.css"/>
  <link type="text/css" rel="stylesheet" href="css/nouislider.min.css"/>
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link type="text/css" rel="stylesheet" href="css/style.css"/>
</head>
<body>

<?php if (file_exists("header.php")) include("header.php"); ?>

<nav id="navigation">
  <div class="container">
    <div id="responsive-nav">
      <ul class="main-nav nav navbar-nav">
        <li class="active"><a href="index.php">Home</a></li>
        <li><a href="#">Hot Deals</a></li>
        <li><a href="#">Categories</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="section">
  <div class="container">
    <div class="row">
      <div id="aside" class="col-md-3">
        <div class="aside">
          <h3 class="aside-title">CATEGORIES</h3>
          <div class="checkbox-filter">
            <?php
            $cat_stmt = $conn->query("SELECT * FROM category");
            while ($cat = $cat_stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "
              <div class='input-checkbox'>
                <input type='checkbox' id='cat-{$cat['cat_id']}'>
                <label for='cat-{$cat['cat_id']}'>
                  <span></span>{$cat['cat_name']}
                </label>
              </div>";
            }
            ?>
          </div>
        </div>
      </div>

      <div id="store" class="col-md-9">
        <div class="row">
          <?php
          try {
            $stmt = $conn->query("
              SELECT p.*, c.cat_name 
              FROM product p 
              LEFT JOIN category c ON p.cat_id = c.cat_id
              ORDER BY p.p_id DESC
            ");
            if ($stmt->rowCount() > 0) {
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                // 🔍 ตรวจสอบ path รูปภาพจริง
                $fileName = trim($row['p_image']);
                $possiblePaths = [
                  "admin/uploads/$fileName",
                  "uploads/$fileName",
                  "admin/product/uploads/$fileName"
                ];

                $img = "img/product01.png"; // ค่าเริ่มต้น
                foreach ($possiblePaths as $path) {
                  if (!empty($fileName) && file_exists($path)) {
                    $img = $path;
                    break;
                  }
                }

                // 🔗 แสดงสินค้า
                echo "
                <div class='col-md-4 col-xs-6'>
                  <div class='product'>
                    <a href='product.php?id={$row['p_id']}' style='text-decoration:none;color:inherit;'>
                      <div class='product-img'>
                        <img src='{$img}' alt='{$row['p_name']}' style='width:100%;height:250px;object-fit:cover;border-radius:8px;'>
                        <div class='product-label'><span class='new'>NEW</span></div>
                      </div>
                      <div class='product-body'>
                        <p class='product-category'>{$row['cat_name']}</p>
                        <h3 class='product-name'>{$row['p_name']}</h3>
                        <h4 class='product-price text-danger fw-bold'>" . number_format($row['p_price'], 2) . " บาท</h4>
                      </div>
                    </a>
                    <div class='product-btns text-center mb-2'>
                      <button class='add-to-wishlist'><i class='fa fa-heart-o'></i></button>
                      <a href='product.php?id={$row['p_id']}' class='quick-view'><i class='fa fa-eye'></i> ดูสินค้า</a>
                    </div>
                    <div class='add-to-cart'>
                      <button class='add-to-cart-btn'><i class='fa fa-shopping-cart'></i> หยิบใส่ตะกร้า</button>
                    </div>
                  </div>
                </div>";
              }
            } else {
              echo "<p class='text-center'>ไม่มีสินค้าในระบบ</p>";
            }
          } catch (PDOException $e) {
            echo "<p>❌ Error: {$e->getMessage()}</p>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<footer id="footer">
  <div id="bottom-footer" class="section">
    <div class="container text-center">
      <span>Copyright &copy; <script>document.write(new Date().getFullYear());</script> Electro Store</span>
    </div>
  </div>
</footer>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/slick.min.js"></script>
<script src="js/nouislider.min.js"></script>
<script src="js/jquery.zoom.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
