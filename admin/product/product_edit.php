<?php
$pageTitle = "แก้ไขสินค้า";
ob_start();

include __DIR__ . "/../../admin/partials/connectdb.php";

$id = $_GET['id'] ?? null;
if(!$id) die("ไม่พบสินค้า");

$product = $conn->prepare("SELECT * FROM product WHERE p_id=?");
$product->execute([$id]);
$p = $product->fetch();

$cats = $conn->query("SELECT * FROM category")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $cat_id = $_POST['cat_id'];
  $desc = $_POST['description'];

  $image = $p['p_image'];
  if (!empty($_FILES['image']['name'])) {
    $image = basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], "upload/".$image);
  }

  $stmt = $conn->prepare("UPDATE product SET p_name=?, p_price=?, p_stock=?, p_description=?, p_image=?, cat_id=? WHERE p_id=?");
  $stmt->execute([$name,$price,$stock,$desc,$image,$cat_id,$id]);
  header("Location: products.php");
  exit;
}
?>

<h3 class="text-center mb-4"><i class="bi bi-pencil-square"></i> แก้ไขสินค้า</h3>

<form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm">
  <div class="mb-3">
    <label class="form-label">ชื่อสินค้า</label>
    <input type="text" name="name" value="<?= htmlspecialchars($p['p_name']) ?>" class="form-control" required>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">ราคา (฿)</label>
      <input type="number" name="price" value="<?= $p['p_price'] ?>" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">สต็อก</label>
      <input type="number" name="stock" value="<?= $p['p_stock'] ?>" class="form-control" required>
    </div>
  </div>

  <div class="mb-3">
    <label class="form-label">หมวดหมู่</label>
    <select name="cat_id" class="form-select" required>
      <?php foreach($cats as $c): ?>
        <option value="<?= $c['cat_id'] ?>" <?= $p['cat_id']==$c['cat_id']?'selected':'' ?>>
          <?= htmlspecialchars($c['cat_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">รายละเอียด</label>
    <textarea name="description" rows="3" class="form-control"><?= htmlspecialchars($p['p_description']) ?></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">รูปภาพ</label><br>
    <?php if($p['p_image']): ?>
      <img src="../../assets/img/<?= $p['p_image'] ?>" width="100" class="rounded mb-2"><br>
    <?php endif; ?>
    <input type="file" name="image" class="form-control">
  </div>

  <div class="text-end">
    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> บันทึกการแก้ไข</button>
    <a href="products.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> กลับ</a>
  </div>
</form>

<?php
$pageContent = ob_get_clean();
include __DIR__ . "/../partials/layout.php";
?>
