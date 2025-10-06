<?php
include "connectdb.php";
include "functions.php";

$orders = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $oid = (int)$_POST['order_id'];

  $stmt = $conn->prepare("
    SELECT o.*, c.email 
    FROM orders o 
    LEFT JOIN customers c ON o.customer_id = c.customer_id 
    WHERE c.email = ? AND o.order_id = ?
  ");
  $stmt->execute([$email, $oid]);
  $orders = $stmt->fetchAll();
}

include "partials/header.php";
?>

<div class="card shadow-sm mb-5">
  <div class="card-body">
    <h4 class="mb-4 text-center"><i class="fa fa-search me-2"></i> ติดตามคำสั่งซื้อ</h4>

    <form method="post" class="row g-2 mb-4 justify-content-center">
      <div class="col-md-3">
        <input type="number" name="order_id" class="form-control" placeholder="เลขคำสั่งซื้อ" required>
      </div>
      <div class="col-md-4">
        <input type="email" name="email" class="form-control" placeholder="อีเมลลูกค้า" required>
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary w-100"><i class="fa fa-search me-1"></i> ค้นหา</button>
      </div>
    </form>

    <?php if($_SERVER['REQUEST_METHOD']==='POST' && !$orders): ?>
      <div class="alert alert-warning text-center">ไม่พบคำสั่งซื้อที่ค้นหา</div>
    <?php endif; ?>

    <?php if($orders): ?>
      <div class="table-responsive">
        <table id="tbl" class="table table-striped align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th>เลขคำสั่งซื้อ</th>
              <th>วันที่</th>
              <th>ยอดรวม</th>
              <th>สถานะชำระเงิน</th>
              <th>สถานะคำสั่งซื้อ</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($orders as $o): ?>
              <tr>
                <td><?=$o['order_id']?></td>
                <td><?=formatDateThai($o['order_date'])?></td>
                <td><?=formatBaht($o['total_price'])?></td>
                <td><?=$o['payment_status'] ?: '-'?></td>
                <td>
                  <?php
                    $st = $o['order_status'] ?: 'รอดำเนินการ';
                    $badge = [
                      'สำเร็จ' => 'bg-success',
                      'ยกเลิก' => 'bg-danger',
                      'รอดำเนินการ' => 'bg-warning text-dark'
                    ][$st] ?? 'bg-secondary';
                  ?>
                  <span class="badge <?=$badge?>"><?=$st?></span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include "partials/footer.php"; ?>
