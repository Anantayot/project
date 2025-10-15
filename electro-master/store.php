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

  <!-- CSS -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>
  <link type="text/css" rel="stylesheet" href="css/slick.css"/>
  <link type="text/css" rel="stylesheet" href="css/slick-theme.css"/>
  <link type="text/css" rel="stylesheet" href="css/nouislider.min.css"/>
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link type="text/css" rel="stylesheet" href="css/style.css"/>

  <style>
/* ✅ การ์ดสินค้า */
.product {
  position: relative;
  background: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 10px;
  overflow: hidden;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100%;
}
.product:hover {
  box-shadow: 0 6px 20px rgba(0,0,0,0.15);
  transform: translateY(-5px);
}

/* ✅ รูปภาพสินค้า */
.product .product-img {
  position: relative;
  overflow: hidden;
  border-radius: 10px 10px 0 0;
  height: 300px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.product .product-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}
.product:hover .product-img img {
  transform: scale(1.05);
}

/* ✅ เนื้อหา */
.product .product-body {
  padding: 15px;
  text-align: center;
  flex-grow: 1;
}
.product .product-body p {
  color: #888;
  font-size: 13px;
  margin-bottom: 5px;
}
.product .product-body h3 {
  font-size: 16px;
  color: #333;
  font-weight: 600;
  margin-bottom: 8px;
  min-height: 40px; /* กันชื่อยาวทำให้การ์ดสูงไม่เท่ากัน */
}
.product .product-body h4 {
  font-size: 17px;
  color: #D10024;
  font-weight: 700;
}

/* ✅ ปุ่มหยิบใส่ตะกร้า */
.add-to-cart {
  position: absolute;
  left: 0;
  bottom: -80px;
  width: 100%;
  text-align: center;
  background: rgba(255,255,255,0.97);
  border-top: 1px solid #eee;
  border-radius: 0 0 10px 10px;
  transition: all 0.4s ease-in-out;
  opacity: 0;
}
.product:hover .add-to-cart {
  bottom: 0;
  opacity: 1;
}
.add-to-cart-btn {
  background: #D10024;
  color: #fff;
  border: none;
  width: 90%;
  margin: 10px auto;
  padding: 12px 0;
  font-weight: 600;
  border-radius: 50px;
  transition: all 0.3s ease;
  cursor: pointer;
}
.add-to-cart-btn:hover {
  background: #a3001b;
  transform: scale(1.05);
}

/* ✅ Layout หลัก */
#store .row {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center; /* ให้การ์ดอยู่ตรงกลาง */
  align-items: stretch;    /* ให้ทุกการ์ดสูงเท่ากัน */
}

/* ✅ 3 ช่องต่อแถว (Desktop) */
#store .col-md-4 {
  flex: 1 1 calc(33.333% - 20px);
  max-width: calc(33.333% - 90px);
  display: flex;
}

/* ✅ Tablet (2 ช่อง) */
@media (max-width: 991px) {
  #store .col-md-4 {
    flex: 1 1 calc(50% - 20px);
    max-width: calc(50% - 20px);
  }
}

/* ✅ Mobile (1 ช่อง) */
@media (max-width: 576px) {
  #store .col-md-4 {
    flex: 1 1 100%;
    max-width: 100%;
  }
  .product .product-img {
    height: 220px;
  }
}
</style>

</head>

<body>
  <!-- HEADER -->
  <?php if (file_exists("header.php")) include("header.php"); ?>

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
              $cat_stmt = $conn->query("SELECT * FROM category ORDER BY cat_name ASC");
              while ($cat = $cat_stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "
                <div class='input-checkbox'>
                  <input type='checkbox' class='filter-checkbox' value='{$cat['cat_id']}' id='cat-{$cat['cat_id']}'>
                  <label for='cat-{$cat['cat_id']}'><span></span>{$cat['cat_name']}</label>
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
              <label>Sort By:
                <select class="input-select">
                  <option value="0">Latest</option>
                  <option value="1">Price: Low to High</option>
                  <option value="2">Price: High to Low</option>
                </select>
              </label>
            </div>
          </div>

          <!-- PRODUCTS -->
          <div class="row" id="product-list">
            <?php
            $stmt = $conn->query("
              SELECT p.*, c.cat_name 
              FROM product p
              LEFT JOIN category c ON p.cat_id = c.cat_id
              ORDER BY p.p_id DESC
            ");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $paths = [
                "../admin/uploads/" . $row['p_image'],
                "admin/uploads/" . $row['p_image'],
                "uploads/" . $row['p_image']
              ];
              $imgPath = "img/product01.png";
              foreach ($paths as $p) {
                if (!empty($row['p_image']) && file_exists($p)) {
                  $imgPath = $p; break;
                }
              }
              echo "
              <div class='col-md-4 col-xs-6'>
                <div class='product'>
                  <a href=\"product.php?id={$row['p_id']}\" style=\"text-decoration:none;color:inherit;\">
                    <div class='product-img'>
                      <img src='{$imgPath}' alt='{$row['p_name']}'>
                      <div class='product-label'><span class='new'>NEW</span></div>
                    </div>
                    <div class='product-body'>
                      <p class='product-category'>{$row['cat_name']}</p>
                      <h3 class='product-name'>{$row['p_name']}</h3>
                      <h4 class='product-price text-danger fw-bold'>".number_format($row['p_price'],2)." บาท</h4>
                    </div>
                  </a>
                  <div class='add-to-cart'>
                    <button class='add-to-cart-btn'>
                      <i class='fa fa-shopping-cart'></i> หยิบใส่ตะกร้า
                    </button>
                  </div>
                </div>
              </div>";
            }
            ?>
          </div>
        </div>
        <!-- /STORE -->

      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer id="footer">
    <div id="bottom-footer" class="section">
      <div class="container text-center">
        <span class="copyright">
          Copyright &copy; <script>document.write(new Date().getFullYear());</script> 
          All rights reserved | Template by <a href="https://colorlib.com" target="_blank">Colorlib</a>
        </span>
      </div>
    </div>
  </footer>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  $(document).ready(function(){
    $(".filter-checkbox").on("change", function() {
      let selectedCats = [];
      $(".filter-checkbox:checked").each(function(){
        selectedCats.push($(this).val());
      });

      $.ajax({
        url: "fetch_products.php",
        method: "POST",
        data: {categories: selectedCats},
        beforeSend: function(){
          $("#product-list").html("<p class='text-center mt-5'>⏳ กำลังโหลดสินค้า...</p>");
        },
        success: function(data){
          $("#product-list").html(data);
        },
        error: function(){
          $("#product-list").html("<p class='text-center text-danger'>เกิดข้อผิดพลาดในการโหลดสินค้า</p>");
        }
      });
    });
  });
  </script>
</body>
</html>
