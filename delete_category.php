<?php
include "connectdb.php";

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $sql = "DELETE FROM category WHERE cat_id = $delete_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: list_category.php?msg=ลบหมวดหมู่เรียบร้อยแล้ว");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: list_category.php");
    exit;
}
