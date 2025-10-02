<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("location:../dashboard.php");
    exit();
}
include("../connectdb.php");

// รับค่า id จาก URL
$id = intval($_GET['id']);
$cat = $conn->query("SELECT * FROM category WHERE cat_id=$id")->fetch_assoc();

if(!$cat){
    die("❌ ไม่พบประเภทสินค้านี้");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $cat_name = $_POST['cat_name'];
    if(!empty($cat_name)){
        $sql = "UPDATE category SET cat_name='$cat_name' WHERE cat_id=$id";
        if($conn->query($sql)){
            header("Location: index.php");
            exit();
        } else {
            $error = "❌ เกิดข้อผิดพลาด: " . $conn->error;
        }
    } else {
        $error = "❌ กรุณากรอกชื่อประเภทสินค้า";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขประเภทสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>✏️ แก้ไขประเภทสินค้า</h3>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post" class="mt-3">
        <div class="mb-3">
            <label>ชื่อประเภทสินค้า:</label>
            <input type="text" name="cat_name" class="form-control" value="<?= htmlspecialchars($cat['cat_name']) ?>" required>
        </div>
        <button type="submit" class="btn btn-warning">💾 บันทึก</button>
