<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connectdb.php");

try {
  $sql = "SHOW TABLES";
  $stmt = $conn->query($sql);

  if ($stmt->rowCount() > 0) {
    echo "<h3>✅ เชื่อมต่อฐานข้อมูลสำเร็จ</h3>";
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
      echo "<li>{$row[0]}</li>";
    }
    echo "</ul>";
  } else {
    echo "❌ ไม่พบตารางในฐานข้อมูล";
  }
} catch (PDOException $e) {
  echo "❌ Query failed: " . $e->getMessage();
}
?>
