<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
include "../connectdb.php";

// ประมวลผลการเพิ่มสินค้า
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $name  = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $cat   = intval($_POST['category_id']);
    $desc  = trim($_POST['description']);

    // อัปโหลดรูปภาพ
    $image = null; // กำหนดค่าเริ่มต้นเป็น null
    if(!empty($_FILES['image']['name'])){
        $targetDir = "../uploads/";
        if(!is_dir($targetDir)) mkdir($targetDir,0777,true);

        $fileName = time()."_".basename($_FILES['image']['name']);
        $targetFile = $targetDir.$fileName;

        if(move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)){
            $image = $fileName;
        } else {
            echo "❌ ไม่สามารถอัปโหลดรูปภาพได้";
            exit();
        }
    }

    $stmt = $conn->prepare("INSERT INTO product (p_name,p_price,p_stock,cat_id,p_description,p_image) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("sdiiss", $name, $price, $stock, $cat, $desc, $image);

    if($stmt->execute()){
        header("Location: index.php");
        exit();
    } else {
        echo "❌ Error: ".$conn->error;
    }
}

// ดึงหมวดหมู่
$cats = $conn->query("SELECT * FROM category");
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>เพิ่มสินค้า</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4" style="max-width:600px;">
<h3>เพ
