<?php
session_start();
include('../connectdb.php');

// ตรวจสอบการเข้าสู่ระบบลูกค้า
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึงรายการสั่งซื้อของลูกค้าคนนี้
$sql_history = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC";
$result_history = mysqli_query($conn, $sql_history);

include('../layout/header.php');
?>

<a href="../index.php" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left"></i> กลับหน้าแรก</a>
<h2 class="mb-4">ประวัติการสั่งซื้อของคุณ</h2>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#ออเดอร์</th>
                <th>วันที่สั่งซื้อ</th>
                <th>ยอดรวม</th>
                <th>สถานะ</th>
                <th>รายละเอียด</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result_history) > 0): ?>
                <?php while($order = mysqli_fetch_assoc($result_history)): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo date('Y-m-d H:i', strtotime($order['order_date'])); ?></td>
                    <td><?php echo number_format($order['total_amount'], 2); ?> บาท</td>
                    <td>
                        <span class="badge 
                            <?php 
                            if ($order['order_status'] == 'Delivered') echo 'bg-success'; 
                            else if ($order['order_status'] == 'Shipped') echo 'bg-info';
                            else if ($order['order_status'] == 'Canceled') echo 'bg-danger';
                            else echo 'bg-warning text-dark';
                            ?>">
                            <?php echo $order['order_status']; ?>
                        </span>
                    </td>
                    <td>
                        <a href="order_status.php?id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-primary">เช็คสถานะ / รายละเอียด</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">ไม่มีประวัติการสั่งซื้อ</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php 
include('../layout/footer.php'); 
mysqli_close($conn); 
?>