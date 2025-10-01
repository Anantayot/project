<meta charset="utf-8">

<?php
	$host = "localhost";
	$user = "root";
	$pwd = "note071245";
	$dbname = "snekker_shop";
	$conn = mysqli_connect($host,$user,$pwd,$dbname);
	
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	mysqli_query($conn, "SET NAMES utf8");
?>