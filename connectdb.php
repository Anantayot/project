<?php
	$host = "localhost";
	$user = "root";
	$pwd = "note071245";
	$dbname = "ihavejib";
	$conn = mysqli_connect($host,$user,$pwd,$dbname);
	// ตรวจสอบการเชื่อมต่อ
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
	mysqli_query($conn, "SET NAMES utf8");
?>