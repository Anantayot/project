<?php
session_start();
include('../../connectdb.php');
include('../check_admin.php');

// ดึงรายการสั่งซื้อทั้งหมด พร้อมชื่อลูกค้า
$sql_orders = "SELECT 
                o.*, 
                u.first_name, 
                u.last_name 
               FROM orders o 
               JOIN users u ON o.user_id = u.user_id 
               ORDER BY o.order_date DESC";
$result_orders = mysqli_query($conn, $sql_orders);

include('../layout/admin_header.php');
?>

<h1 class="mt-4">จัดการรายการสั่งซื้อ</h1>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#ID</th>
                <th>วันที่สั่งซื้อ</th>
                <th>ลูกค้า</th>
                <th>ยอดรวม</th>
                <th>สถานะ</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php while($order = mysqli_fetch_assoc($result_orders)): ?>
            <tr>
                <td><?php echo $order['order_id']; ?></td>
                <td><?php echo date('Y-m-d H:i', strtotime($order['order_date'])); ?></td>
                <td><?php echo $order['first_name'] . ' ' . $order['last_name']; ?></td>
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
                    <a href="detail.php?id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-info text-white">ดูรายละเอียด</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php 
include('../layout/admin_footer.php');
mysqli_close($conn); 
?>