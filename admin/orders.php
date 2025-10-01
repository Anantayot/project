<?php include 'templates/header.php'; ?>

<h1 class="h2">จัดการออเดอร์</h1>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>ลูกค้า</th>
                <th>วันที่สั่งซื้อ</th>
                <th>ยอดรวม</th>
                <th>สถานะ</th>
                <th>รายละเอียด</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT o.*, u.first_name, u.last_name FROM orders o
                    JOIN users u ON o.user_id = u.user_id
                    ORDER BY o.order_date DESC";
            $stmt = $pdo->query($sql);
            $orders = $stmt->fetchAll();

            foreach ($orders as $order) :
            ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                    <td><?php echo number_format($order['total_amount'], 2); ?></td>
                    <td>
                        <span class="badge 
                            <?php
                            switch ($order['order_status']) {
                                case 'Pending': echo 'bg-warning text-dark'; break;
                                case 'Processing': echo 'bg-info text-dark'; break;
                                case 'Shipped': echo 'bg-primary'; break;
                                case 'Delivered': echo 'bg-success'; break;
                                case 'Cancelled': echo 'bg-danger'; break;
                            }
                            ?>">
                            <?php echo $order['order_status']; ?>
                        </span>
                    </td>
                    <td>
                        <a href="order_details.php?id=<?php echo $order['order_id']; ?>" class="btn btn-info btn-sm">
                           <i class="bi bi-eye"></i> ดูรายละเอียด
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'templates/footer.php'; ?>