
<?php
$servername = "localhost";
$dbuser = "root";
$dbpass = "note1234";
$dbname = "ihavejib";
$conn = new mysqli($servername, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("DB connect error: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
