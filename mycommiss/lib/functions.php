<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function flash($type, $message) {
  $_SESSION['flash'][$type][] = $message;
}

function require_login() {
  if (empty($_SESSION['user'])) {
    flash('warning', 'กรุณาเข้าสู่ระบบก่อน');
    header("Location: auth/login.php");
    exit();
  }
}

function format_price($n) { return number_format((float)$n, 2); }
?>
