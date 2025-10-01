<?php
include('../connectdb.php');
// ตรวจสอบ session/login ก่อน
include('includes/header.php');

// ดึงข้อมูลสินค้าจากตาราง product
$sql = "SELECT p.*, c.cat_name FROM product p JOIN category c ON p.cat_id = c.cat_id";
$result = $conn->query($sql);
?>

<h2><i class="fas fa-box"></i> จัดการสินค้า</h2>
<div class="d-flex justify-content-between mb-3">
    <a href="product_add.php" class="btn btn-success"><i class="fas fa-plus-circle"></i> เพิ่มสินค้าใหม่</a>
    <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-circle-left"></i> กลับแดชบอร์ด</a>
</div>
<hr>

<table id="productTable" class="table table-striped table-hover datatable" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>รูป</th>
            <th>ชื่อสินค้า</th>
            <th>ราคา</th>
            <th>สต็อก</th>
            <th>ประเภท</th>
            <th>จัดการ</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["p_id"] . "</td>";
                echo "<td><img src='../images/" . $row["p_img"] . "' alt='รูปสินค้า' style='width:50px;'></td>";
                echo "<td>" . $row["p_name"] . "</td>";
                echo "<td>" . number_format($row["p_price"], 2) . "</td>";
                echo "<td>" . $row["p_stock"] . "</td>";
                echo "<td>" . $row["cat_name"] . "</td>";
                echo "<td>
                    <a href='product_edit.php?id=" . $row["p_id"] . "' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i> แก้ไข</a>
                    <a href='product_delete.php?id=" . $row["p_id"] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"ยืนยันการลบ?\")'><i class='fas fa-trash-alt'></i> ลบ</a>
                </td>";
                echo "</tr>";
            }
        } else {
            // echo "<tr><td colspan='7' class='text-center'>ไม่พบข้อมูลสินค้า</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
$conn->close();
include('includes/footer.php');
?>