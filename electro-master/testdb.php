<?php
include("connectdb.php");

$sql = "SHOW TABLES";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<h3>เชื่อมต่อฐานข้อมูลสำเร็จ ✅</h3>";
  echo "<ul>";
  while ($row = $result->fetch_row()) {
    echo "<li>$row[0]</li>";
  }
  echo "</ul>";
} else {
  echo "❌ ไม่พบตารางในฐานข้อมูล";
}
?>
