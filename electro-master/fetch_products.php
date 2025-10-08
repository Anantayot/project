<?php
include("connectdb.php");

$categories = $_POST['categories'] ?? [];

$sql = "SELECT p.*, c.cat_name 
        FROM product p
        LEFT JOIN category c ON p.cat_id = c.cat_id";

if (!empty($categories)) {
  $placeholders = implode(',', array_fill(0, count($categories), '?'));
  $sql .= " WHERE p.cat_id IN ($placeholders)";
}

$sql .= " ORDER BY p.p_id DESC";

$stmt = $conn->prepare($sql);

if (!empty($categories)) {
  foreach ($categories as $k => $cat) {
    $stmt->bindValue(($k + 1), $cat, PDO::PARAM_INT);
  }
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($products) > 0) {
  foreach ($products as $row) {

    $paths = [
      "../admin/uploads/" . $row['p_image'],
      "admin/uploads/" . $row['p_image'],
      "uploads/" . $row['p_image']
    ];

    $imgPath = "img/product01.png";
    foreach ($paths as $p) {
      if (!empty($row['p_image']) && file_exists($p)) {
        $imgPath = $p;
        break;
      }
    }

    echo "
    <div class='col-md-4 col-xs-6'>
      <div class='product'>
        <a href=\"product.php?id={$row['p_id']}\" style=\"text-decoration:none;color:inherit;\">
          <div class='product-img'>
            <img src='{$imgPath}' alt='{$row['p_name']}' style='height:250px;object-fit:cover;width:100%;border-radius:6px;'>
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
    </div>
    ";
  }
} else {
  echo "<p class='text-center mt-5'>ไม่พบสินค้าตามหมวดหมู่ที่เลือก</p>";
}
?>
