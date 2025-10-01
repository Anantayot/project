// โครงสร้าง PHP
// 1. รับ $_GET['id'] (order_id)
// 2. ดึงข้อมูลหลักออเดอร์ (orders) และข้อมูลลูกค้า (users)
// 3. ดึงรายการสินค้าที่สั่ง (order_items) โดย INNER JOIN กับ products
// 4. ส่วนอัปเดตสถานะ:
//    - มีฟอร์ม/Dropdown สำหรับเปลี่ยนค่า `order_status` และใส่ `tracking_number`
//    - เมื่อ POST ให้ทำการ UPDATE ตาราง orders

/* โครงสร้าง HTML */
// <h1>รายละเอียดออเดอร์ #<?= $order_id ?></h1>
// <div class="row">
//     <div class="col-md-6">
//         <h4>ข้อมูลจัดส่ง</h4>
//         <p>ชื่อลูกค้า: ...</p>
//         <p>ที่อยู่: <?= $order['shipping_address'] ?></p>
//     </div>
//     <div class="col-md-6">
//         <h4>อัปเดตสถานะ</h4>
//         <form>
//             <select name="status" class="form-select">
//                 <option value="Processing">กำลังดำเนินการ</option>
//                 <option value="Shipped">จัดส่งแล้ว</option>
//                 ...
//             </select>
//             <input type="text" name="tracking_number" placeholder="เลขติดตามพัสดุ">
//             <button type="submit" class="btn btn-info">อัปเดต</button>
//         </form>
//     </div>
// </div>
// <h4>รายการสินค้า</h4>
// ```