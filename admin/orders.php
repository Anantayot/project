<?php
include('../connectdb.php');
// ตรวจสอบ session/login ก่อน
include('includes/header.php');

// ดึงข้อมูลออเดอร์และลูกค้า
$sql = "SELECT o.*, c.c_name, c.c_phone FROM orders o JOIN customer c ON o.c_id = c.c_id ORDER BY o.o_date DESC";
$result = $conn->query($sql);
?>

<h2><i class="fas fa-shopping-cart"></i> จัดการออเดอร์</h2>
<div class="d-flex justify-content-end mb-3">
    <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-circle-left"></i> กลับแดชบอร์ด</a>
</div>
<hr>

<table id="orderTable" class="table table-striped table-hover datatable" style="width:100%">
    <thead>
        <tr>
            <th>ID ออเดอร์</th>
            <th>ลูกค้า</th>
            <th>วันที่สั่ง</th>
            <th>สถานะ</th>
            <th>จัดการ</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["o_id"] . "</td>";
                echo "<td>" . $row["c_name"] . " (" . $row["c_phone"] . ")</td>";
                echo "<td>" . date('Y-m-d H:i', strtotime($row["o_date"])) . "</td>";
                // ใช้ Badge ของ Bootstrap เพื่อแสดงสถานะ
                $badge_class = match($row["o_status"]) {
                    'pending' => 'bg-warning text-dark',
                    'shipping' => 'bg-info text-dark',
                    'completed' => 'bg-success',
                    default => 'bg-secondary',
                };
                echo "<td><span class='badge {$badge_class}'>" . ucfirst($row["o_status"]) . "</span></td>";
                echo "<td>
                    <a href='order_detail.php?id=" . $row["o_id"] . "' class='btn btn-sm btn-info'><i class='fas fa-search'></i> รายละเอียด</a>
                </td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>

<?php
$conn->close();
include('includes/footer.php');
?>