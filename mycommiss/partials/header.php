<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MyCommiss</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background: #0d1117; color: #c9d1d9; }
    .navbar, .dropdown-menu { background: #161b22; }
    .card { background: #161b22; border-color: #30363d; }
    a, a:hover { color: #58a6ff; }
    .btn-primary { background: #238636; border-color: #238636; }
    .btn-outline-light { border-color:#c9d1d9; color:#c9d1d9; }
    .form-control, .form-select { background:#0d1117; border-color:#30363d; color:#c9d1d9; }
    .form-control::placeholder { color:#8b949e; }
    .badge.bg-soft { background: #21262d; border: 1px solid #30363d; color: #c9d1d9; }
  </style>
</head>
<body>
<?php include __DIR__ . "/navbar.php"; ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="javascript:history.back()" class="btn btn-outline-light"><i class="bi bi-arrow-left"></i> กลับ</a>
    <div></div>
    <div></div>
  </div>
