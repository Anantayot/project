<?php
include "connectdb.php";

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM product WHERE p_id=$id";
    mysqli_query($conn, $sql);
}
header("Location: index.php");
exit;