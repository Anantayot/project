<?php
session_start();
session_destroy();
session_start();
$_SESSION['flash']['success'][] = 'ออกจากระบบแล้ว';
header("Location: ../index.php");
