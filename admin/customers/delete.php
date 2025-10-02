<?php
session_start();
if (!isset($_SESSION['admin'])) { 
    header("Location: ../login.php"); 
    exit(); 
}

// ปรับ path ให้ถูกต้อง
include "../connectdb.php";

// รับค่า id อย่างปลอดภัย
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM customers WHERE customer_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "❌ Error: " . $stmt->error;
    }
} else {
    echo "❌ Invalid ID";
}
