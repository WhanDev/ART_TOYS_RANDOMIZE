<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

define('BASE_DIR', __DIR__ . '/');
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>ตะกร้าสินค้า - ART TOYS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php include BASE_DIR . 'layout/nav.php'; ?>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">ตะกร้าสินค้า</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ชื่อสินค้า</th>
                    <th scope="col">ราคา (บาท)</th>
                    <th scope="col">จำนวน</th>
                    <th scope="col">รวมทั้งหมด (บาท)</th>
                </tr>
            </thead>
            <tbody id="cartItems">
                <!-- สินค้าในตะกร้าจะแสดงที่นี่ -->
            </tbody>
        </table>

        <!-- ฟอร์มสำหรับการชำระเงิน -->
        <form id="paymentForm" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="pamt_amount" class="form-label">ยอดรวมที่ต้องชำระ (บาท)</label>
                <input type="number" class="form-control" id="pamt_amount" name="pamt_amount" readonly />
            </div>
            <div class="mb-3">
                <label for="pamt_net" class="form-label">ยอดสุทธิ (บาท)</label>
                <input type="number" class="form-control" id="pamt_net" name="pamt_net" readonly />
            </div>
            <div class="mb-3">
                <label for="pamt_img" class="form-label">แนบหลักฐานการชำระเงิน</label>
                <input type="file" class="form-control" id="pamt_img" name="pamt_img" accept="image/*" required />
            </div>
            <div class="mt-4 text-end">
                <h5>รวมราคาทั้งหมด: <span id="totalPrice">0</span> บาท</h5>
            </div>
            <div class="form-group d-flex align-items-center justify-content-end mt-4 mb-0">
                <button type="submit" class="btn btn-success">ยืนยันการสั่งซื้อ</button>
            </div>
        </form>
    </div>

    <script>
        function displayCart() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            console.log(cart);
            const cartItems = document.getElementById('cartItems');
            const totalPriceElement = document.getElementById('totalPrice');
            let totalPrice = 0;

            cartItems.innerHTML = ''; // เคลียร์รายการสินค้าเก่า

            cart.forEach(item => {
                const itemTotalPrice = item.price * item.amount; // คำนวณราคา
                totalPrice += itemTotalPrice; // รวมราคาทั้งหมด

                const itemHTML = `
                    <tr>
                        <td>${item.name}</td>
                        <td>${item.price} บาท</td>
                        <td>${item.amount}</td>
                        <td>${new Intl.NumberFormat().format(itemTotalPrice)} บาท</td>
                    </tr>
                `;
                cartItems.innerHTML += itemHTML; // แสดงรายการสินค้า
            });

            // แสดงยอดรวมทั้งหมด
            totalPriceElement.innerText = new Intl.NumberFormat().format(totalPrice); // แสดงยอดรวม

            // ตั้งค่าข้อมูลในฟอร์ม
            document.getElementById('pamt_amount').value = totalPrice;
            document.getElementById('pamt_net').value = totalPrice; // โดยปกติจะเท่ากับยอดรวมทั้งหมด
        }

        // การจัดการการยืนยันการสั่งซื้อ
        document.getElementById('paymentForm').addEventListener('submit', async function(event) {
            event.preventDefault(); // ป้องกันการส่งฟอร์มปกติ

            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const orderData = cart.map(item => ({
                prod_id: item.id,
                ordt_amount: item.amount
            }));

            // ส่งข้อมูลการสั่งซื้อไปยัง API ตัวแรก
            try {
                const response = await fetch('http://localhost/ART_TOYS_RANDOMIZE/Controller/customer/order/add.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ cart: orderData })
                });

                const result = await response.json();
                if (result.result === 1) {
                    // ถ้าการเรียก API ตัวแรกสำเร็จ
                    const or_id = result.or_id; // แก้ไขให้ได้ or_id จาก API

                    // ลบ LocalStorage
                    localStorage.removeItem('cart');
                    console.log('Order ID:', or_id);
                    // เตรียมข้อมูลสำหรับการเรียก API ตัวที่สอง
                    const formData = new FormData(this);
                    // เพิ่ม or_id ไปยัง formData
                    formData.append('or_id', or_id);

                    // เรียกใช้งาน API ตัวที่สอง
                    const paymentResponse = await fetch(`http://localhost/ART_TOYS_RANDOMIZE/Controller/customer/payment/confirm.php?or_id=${or_id}`, {
                        method: 'POST',
                        body: formData
                    });

                    const paymentResult = await paymentResponse.json();
                    if (paymentResult.result === 1) {
                        Swal.fire('ชำระเงินสำเร็จ', paymentResult.message, 'success');
                        window.location.href = 'index.php'; // ไปยังหน้าขอบคุณ
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', paymentResult.messages, 'error');
                    }
                } else {
                    Swal.fire('เกิดข้อผิดพลาด', result.messages, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถส่งข้อมูลได้', 'error');
            }
        });

        // แสดงรายการสินค้าเมื่อโหลดหน้า
        document.addEventListener('DOMContentLoaded', displayCart);
    </script>
</body>

</html>
