<?php
// ... PHP logic for fetching order detail, customer info, and handling status update ...
include('includes/header.php');
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// Fetch order details ($order), customer details ($customer), and product list ($items)
?>

<h2><i class="fas fa-clipboard-list"></i> รายละเอียดออเดอร์ #<?php echo $order_id; ?></h2>
<hr>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">ข้อมูลลูกค้าและที่อยู่จัดส่ง</div>
            <div class="card-body">
                <p><strong>ลูกค้า:</strong> [ชื่อลูกค้า]</p>
                <p><strong>เบอร์โทร:</strong> [เบอร์โทรลูกค้า]</p>
                <p><strong>ที่อยู่จัดส่ง:</strong> [ที่อยู่จัดส่งจาก $order->o_address]</p>
                <p><strong>วันที่สั่ง:</strong> [วันที่สั่ง]</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">อัปเดตสถานะ</div>
            <div class="card-body">
                <form action="order_detail.php?id=<?php echo $order_id; ?>" method="POST">
                    <div class="mb-3">
                        <label for="o_status" class="form-label">สถานะปัจจุบัน: **[สถานะปัจจุบัน]**</label>
                        <select class="form-select" id="o_status" name="o_status" required>
                            <option value="pending">รอดำเนินการ (Pending)</option>
                            <option value="shipping">กำลังจัดส่ง (Shipping)</option>
                            <option value="completed">จัดส่งเสร็จสิ้น (Completed)</option>
                            <option value="cancelled">ยกเลิก (Cancelled)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">อัปเดตสถานะ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-dark text-white">รายการสินค้าในออเดอร์</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>สินค้า</th>
                    <th>ราคา/หน่วย</th>
                    <th>จำนวน</th>
                    <th>รวม</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>[ชื่อสินค้า]</td>
                    <td>[ราคา]</td>
                    <td>[จำนวน]</td>
                    <td>[รวมเงิน]</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end"><strong>รวมทั้งสิ้น:</strong></td>
                    <td><strong>[ราคารวมทั้งหมด]</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    <a href="orders.php" class="btn btn-secondary"><i class="fas fa-arrow-circle-left"></i> กลับหน้ารายการออเดอร์</a>
</div>

<?php
include('includes/footer.php');
?>