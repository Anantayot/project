<?php
include "connectdb.php";

if (isset($_POST['submit'])) {
    $p_name   = $_POST['p_name'];
    $p_price  = $_POST['p_price'];
    $p_stock  = $_POST['p_stock'];
    $p_detail = $_POST['p_detail'];
    $cat_id   = $_POST['cat_id'];

    // อัปโหลดรูป
    $p_img = NULL;
    if (!empty($_FILES['p_img']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES["p_img"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["p_img"]["tmp_name"], $targetFilePath)) {
            $p_img = $fileName;
        }
    }

    $sql = "INSERT INTO product (p_name, p_price, p_stock, p_detail, p_img, cat_id) 
            VALUES ('$p_name', '$p_price', '$p_stock', '$p_detail', '$p_img', '$cat_id')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: list_product.php?success=1");
    } else {
        header("Location: add_product.php?error=" . mysqli_error($conn));
    }
    exit();
}
?>
