<?php
include("connectdb.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Electro - Store</title>

  <!-- Google font -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
  <!-- Bootstrap -->
  <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>
  <!-- Slick -->
  <link type="text/css" rel="stylesheet" href="css/slick.css"/>
  <link type="text/css" rel="stylesheet" href="css/slick-theme.css"/>
  <!-- nouislider -->
  <link type="text/css" rel="stylesheet" href="css/nouislider.min.css"/>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <!-- Custom stylesheet -->
  <link type="text/css" rel="stylesheet" href="css/style.css"/>
</head>
<body>

  <!-- HEADER -->
  <?php include("header.php"); ?> <!-- ถ้ามี header แยก, ถ้าไม่มีก็ปล่อยไว้ -->
  <!-- /HEADER -->

  <!-- NAVIGATION -->
  <nav id="navigation">
    <div class="container">
      <div id="responsive-nav">
        <ul class="main-nav nav navbar-nav">
          <li class="active"><a href="index.php">Home</a></li>
          <li><a href="#">Hot Deals</a></li>
          <li><a href="#">Categories</a></li>
          <li><a href="#">Laptops</a></li>
          <li><a href="#">Smartphones</a></li>
          <li><a href="#">Cameras</a></li>
          <li><a href="#">Accessories</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- /NAVIGATION -->

  <!-- BREADCRUMB -->
  <div id="breadcrumb" class="section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <ul class="breadcrumb-tree">
            <li><a href="index.php">Home</a></li>
            <li class="active">All Products</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- /BREADCRUMB -->

  <!-- SECTION -->
  <div class="section">
    <div class="container">
      <div class="row">
        <!-- ASIDE -->
        <div id="aside" class="col-md-3">
          <div class="aside">
            <h3 class="aside-title">Categories</h3>
            <div class="checkbox-filter">
              <?php
              $cat_stmt = $conn->query("SELECT * FROM category");
              while ($cat = $cat_stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "
                <div class='input-checkbox'>
                  <input type='checkbox' id='cat-{$cat['cat_id']}'>
                  <label for='cat-{$cat['cat_id']}'>
                    <span></span>
                    {$cat['cat_name']}
                  </label>
                </div>
                ";
              }
              ?>
            </div>
          </div>
        </div>
        <!-- /ASIDE -->

        <!-- STORE -->
        <div id="store" class="col-md-9">
          <div class="store-filter clearfix">
            <div class="store-sort">
              <label>
                Sort By:
                <select class="input-select">
                  <option value="0">Latest</option>
                  <option value="1">Price: Low to High</option>
                  <option value="2">Price: High to Low</option>
                </select>
              </label>
            </div>
          </div>

          <!-- PRODUCTS -->
          <div class="row">
            <?php
            try {
              $stmt = $conn->query("SELECT * FROM product ORDER BY p_id DESC");
              if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $img = !empty($row['p_image']) ? "uploads/{$row['p_image']}" : "img/product01.png";
                  echo "
                  <div class='col-md-4 col-xs-6'>
                    <div class='product'>
                      <div class='product-img'>
                        <img src='{$img}' alt='{$row['p_name']}' style='height:250px;object-fit:cover;'>
                        <div class='product-label'>
                          <span class='new'>NEW</span>
                        </div>
                      </div>
                      <div class='product-body'>
                        <p class='product-category'>{$row['cat_id']}</p>
                        <h3 class='product-name'><a href='#'>{$row['p_name']}</a></h3>
                        <h4 class='product-price'>{$row['p_price']} บาท</h4>
                        <div class='product-btns'>
                          <button class='add-to-wishlist'><i class='fa fa-heart-o'></i><span class='tooltipp'>เพิ่มในรายการโปรด</span></button>
                          <button class='quick-view'><i class='fa fa-eye'></i><span class='tooltipp'>ดูสินค้า</span></button>
                        </div>
                      </div>
                      <div class='add-to-cart'>
                        <button class='add-to-cart-btn'><i class='fa fa-shopping-cart'></i> หยิบใส่ตะกร้า</button>
                      </div>
                    </div>
                  </div>
                  ";
                }
              } else {
                echo "<p class='text-center'>ไม่มีสินค้าในระบบ</p>";
              }
            } catch (PDOException $e) {
              echo "<p>❌ เกิดข้อผิดพลาด: {$e->getMessage()}</p>";
            }
            ?>
          </div>
          <!-- /PRODUCTS -->

          <div class="store-filter clearfix">
            <span class="store-qty">สินค้าทั้งหมด</span>
          </div>
        </div>
        <!-- /STORE -->
      </div>
    </div>
  </div>
  <!-- /SECTION -->

  <!-- FOOTER -->
  <footer id="footer">
    <div id="bottom-footer" class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            <span class="copyright">
              Copyright &copy; <script>document.write(new Date().getFullYear());</script> 
              All rights reserved | Template by <a href="https://colorlib.com" target="_blank">Colorlib</a>
            </span>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- /FOOTER -->

  <!-- jQuery Plugins -->
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/slick.min.js"></script>
  <script src="js/nouislider.min.js"></script>
  <script src="js/jquery.zoom.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
