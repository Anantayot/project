<?php
session_start();
include __DIR__ . "/partials/connectdb.php";

// ดึงข้อมูลสรุปจากฐานข้อมูล
$total_products   = $conn->query("SELECT COUNT(*) FROM product")->fetchColumn();
$total_categories = $conn->query("SELECT COUNT(*) FROM category")->fetchColumn();
$total_customers  = $conn->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$total_orders     = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_income     = $conn->query("SELECT SUM(total_price) FROM orders")->fetchColumn() ?? 0;
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - MyCommiss Admin</title>
  <link rel="stylesheet" href="template_corona/assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="template_corona/assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #0d1117;
      font-family: "Prompt", sans-serif;
      margin: 0;
      overflow-x: hidden;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 260px;
      background: #11161d;
      border-right: 1px solid #1f252e;
      box-shadow: 3px 0 10px rgba(0,0,0,0.5);
      z-index: 1000;
    }

    /* Main area */
    .page-body-wrapper {
      margin-left: 260px;
      background: #0d1117;
      min-height: 100vh;
      transition: all 0.3s ease;
    }

    .content-wrapper {
      padding: 40px 30px;
      background: transparent;
    }

    /* Section title */
    .section-title {
      color: #fff;
      font-weight: 700;
      border-left: 5px solid #00d25b;
      padding-left: 10px;
      margin-bottom: 1.5rem;
    }

    /* Cards */
    .card-custom {
      background: linear-gradient(145deg, #161b22, #0e1116);
      border: 1px solid #2c313a;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      transition: transform .3s, box-shadow .3s;
    }
    .card-custom:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.5);
    }

    .card-custom h4 {
      color: #c9d1d9;
      font-weight: 600;
    }
    .card-custom h2 {
      color: #00d25b;
      font-size: 2.2rem;
      font-weight: bold;
    }

    /* Not-clickable card (รายได้รวม) */
    .not-clickable {
      cursor: default !important;
      pointer-events: none !important;
      background: linear-gradient(145deg, #161b22, #101318);
      border: 1px solid #2c313a;
      box-shadow: inset 0 0 12px rgba(255, 50, 50, 0.2);
      transition: all 0.3s ease;
    }
    .not-clickable:hover {
      transform: none !important;
      box-shadow: 0 0 25px rgba(255, 70, 70, 0.5),
                  inset 0 0 15px rgba(255, 80, 80, 0.3);
      border-color: #e74c3c;
    }

    /* Responsive */
    @media (max-width: 991px) {
      .sidebar { left: -260px; }
      .page-body-wrapper { margin-left: 0; }
    }
  </style>
</head>

<body class="sidebar-dark">
  <div class="container-scroller">

    <?php include "partials/sidebar.php"; ?>

    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <h3 class="section-title"><i class="bi bi-speedometer2"></i> แผงควบคุมระบบหลังบ้าน</h3>

          <!-- 🔹 แถวสรุปข้อมูล -->
          <div class="row g-3">
            <div class="col-md-3 col-sm-6">
              <a href="product/products.php" class="text-decoration-none">
                <div class="card card-custom text-center p-3">
                  <div class="card-body">
                    <i class="bi bi-box-seam display-5 text-primary"></i>
                    <h4 class="mt-3">สินค้า</h4>
                    <h2><?= $total_products ?></h2>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-md-3 col-sm-6">
              <a href="categories/categories.php" class="text-decoration-none">
                <div class="card card-custom text-center p-3">
                  <div class="card-body">
                    <i class="bi bi-tags display-5 text-info"></i>
                    <h4 class="mt-3">ประเภทสินค้า</h4>
                    <h2><?= $total_categories ?></h2>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-md-3 col-sm-6">
              <a href="customer/customers.php" class="text-decoration-none">
                <div class="card card-custom text-center p-3">
                  <div class="card-body">
                    <i class="bi bi-people display-5 text-success"></i>
                    <h4 class="mt-3">ลูกค้า</h4>
                    <h2><?= $total_customers ?></h2>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-md-3 col-sm-6">
              <a href="order/orders.php" class="text-decoration-none">
                <div class="card card-custom text-center p-3">
                  <div class="card-body">
                    <i class="bi bi-bag-check display-5 text-warning"></i>
                    <h4 class="mt-3">คำสั่งซื้อ</h4>
                    <h2><?= $total_orders ?></h2>
                  </div>
                </div>
              </a>
            </div>
          </div>

          <!-- 🔹 การ์ดรายได้รวม -->
          <div class="row mt-4">
            <div class="col-md-12">
              <div class="card card-custom text-center p-4 not-clickable">
                <div class="card-body">
                  <i class="bi bi-cash-stack display-5 text-danger"></i>
                  <h4 class="mt-3 text-white">รายได้รวมทั้งหมด</h4>
                  <h2 class="text-success"><?= number_format($total_income, 2) ?> ฿</h2>
                </div>
              </div>
            </div>
          </div>

        </div> <!-- /content-wrapper -->
      </div> <!-- /main-panel -->
    </div> <!-- /page-body-wrapper -->
  </div> <!-- /container-scroller -->

  <script src="template_corona/assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="template_corona/assets/js/off-canvas.js"></script>
  <script src="template_corona/assets/js/template.js"></script>
</body>
</html>
