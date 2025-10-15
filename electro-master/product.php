<?php
include("connectdb.php");

// ตรวจสอบว่ามี id ที่ส่งมาหรือไม่
if (!isset($_GET['id'])) {
  die("<p>❌ ไม่พบรหัสสินค้า</p>");
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT p.*, c.cat_name 
                        FROM product p 
                        LEFT JOIN category c ON p.cat_id = c.cat_id 
                        WHERE p_id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
  die("<p>❌ ไม่พบสินค้านี้</p>");
}

// กำหนด path รูปสินค้า
$imgPath = "../admin/uploads/" . $product['p_image'];
if (!file_exists($imgPath) || empty($product['p_image'])) {
  $imgPath = "img/product01.png";
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($product['p_name']); ?> | Electro Store</title>

  <!-- Google font -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
  <!-- Bootstrap -->
  <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>
  <!-- Slick -->
  <link type="text/css" rel="stylesheet" href="css/slick.css"/>
  <link type="text/css" rel="stylesheet" href="css/slick-theme.css"/>
  <!-- nouislider -->
  <link type="text/css" rel="stylesheet" href="css/nouislider.min.css"/>
  <!-- Font Awesome Icon -->
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <!-- Custom stylesheet -->
  <link type="text/css" rel="stylesheet" href="css/style.css"/>
</head>
<body>
<script>
$(document).ready(function(){
  // เมื่อกดปุ่มเพิ่มลงตะกร้า
  $('.add-to-cart-btn').on('click', function(e){
    e.preventDefault();

    const qty = parseInt($('.input-number input').val()) || 1;
    const productId = <?php echo $product['p_id']; ?>;

    $.ajax({
      url: 'add_to_cart.php',
      type: 'POST',
      dataType: 'json',
      data: { id: productId, qty: qty },
      success: function(res){
        if(res.ok){
          $('#cart-count').text(res.count);
          showToast('✅ ' + res.message);
        } else {
          showToast('⚠️ ' + res.message);
        }
      },
      error: function(){
        showToast('❌ ไม่สามารถเพิ่มสินค้าได้');
      }
    });
  });

  // ฟังก์ชัน Toast แจ้งเตือนเล็กๆ
  function showToast(msg){
    let toast = $('#toast-box');
    if(!toast.length){
      $('body').append(`
        <div id="toast-box" style="
          position:fixed;
          left:50%;
          bottom:20px;
          transform:translateX(-50%);
          background:#333;
          color:#fff;
          padding:10px 16px;
          border-radius:8px;
          z-index:9999;
          opacity:0;
          transition:opacity .2s, bottom .2s;
        "></div>
      `);
      toast = $('#toast-box');
    }
    toast.text(msg).css({opacity:1, bottom:'30px'});
    setTimeout(()=> toast.css({opacity:0, bottom:'20px'}), 1600);
  }
});
</script>

  <!-- HEADER -->
  <?php if (file_exists("header.php")) include("header.php"); ?>
  <!-- /HEADER -->

  <!-- BREADCRUMB -->
  <div id="breadcrumb" class="section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <ul class="breadcrumb-tree">
            <li><a href="index.php">Home</a></li>
            <li><a href="store.php">All Products</a></li>
            <li class="active"><?php echo htmlspecialchars($product['p_name']); ?></li>
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
        <!-- Product main img -->
        <div class="col-md-5 col-md-push-2">
          <div id="product-main-img">
            <div class="product-preview">
              <img src="<?php echo $imgPath; ?>" alt="<?php echo htmlspecialchars($product['p_name']); ?>">
            </div>
          </div>
        </div>
        <!-- /Product main img -->

        <!-- Product details -->
        <div class="col-md-5">
          <div class="product-details">
            <h2 class="product-name"><?php echo htmlspecialchars($product['p_name']); ?></h2>
            <div>
              <span class="product-available">In Stock</span>
            </div>
            <div>
              <h3 class="product-price text-danger fw-bold"><?php echo number_format($product['p_price'], 2); ?> บาท</h3>
            </div>
            <p><?php echo nl2br(htmlspecialchars($product['p_description'])); ?></p>

            <div class="add-to-cart">
              <div class="qty-label">
                จำนวน
                <div class="input-number">
                  <input type="number" value="1" min="1">
                  <span class="qty-up">+</span>
                  <span class="qty-down">-</span>
                </div>
              </div>
              <button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> เพิ่มลงตะกร้า</button>
            </div>

            <ul class="product-links">
              <li>หมวดหมู่: </li>
              <li><a href="#"><?php echo htmlspecialchars($product['cat_name']); ?></a></li>
            </ul>
          </div>
        </div>
        <!-- /Product details -->

        <!-- Product tab -->
        <div class="col-md-12">
          <div id="product-tab">
            <ul class="tab-nav">
              <li class="active"><a data-toggle="tab" href="#tab1">รายละเอียดสินค้า</a></li>
              <li><a data-toggle="tab" href="#tab2">รีวิว (ยังไม่มี)</a></li>
            </ul>

            <div class="tab-content">
              <div id="tab1" class="tab-pane fade in active">
                <div class="row">
                  <div class="col-md-12">
                    <p><?php echo nl2br(htmlspecialchars($product['p_description'])); ?></p>
                  </div>
                </div>
              </div>
              <div id="tab2" class="tab-pane fade in">
                <p>ยังไม่มีรีวิวสำหรับสินค้านี้</p>
              </div>
            </div>
          </div>
        </div>
        <!-- /Product tab -->
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
