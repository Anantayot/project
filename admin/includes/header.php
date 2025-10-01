<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Snekker Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Snekker Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-collapse="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="products.php">จัดการสินค้า</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="categories.php">จัดการประเภทสินค้า</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="customers.php">จัดการลูกค้า</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="orders.php">จัดการออเดอร์</a>
        </li>
      </ul>
      <a class="btn btn-outline-danger" href="logout.php">ออกจากระบบ</a>
    </div>
  </div>
</nav>
<div class="container mt-4">
    ```

### admin/includes/footer.php (ส่วนท้าย, Bootstrap & DataTables Scripts)

ไฟล์นี้จะถูก **`include`** ที่ท้ายหน้า HTML

```php
    </div> 

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Initializing DataTables on all tables with class 'datatable'
    $(document).ready(function () {
        $('.datatable').DataTable({
            // ตัวเลือกเพิ่มเติม (Optional settings)
            "language": {
                "url": "//cdn.datatables.net/plug-ins/2.0.8/i18n/th.json" // ภาษาไทย
            }
        });
    });
</script>

</body>
</html>