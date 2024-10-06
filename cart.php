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
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php include BASE_DIR . 'layout/nav.php'; ?>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">ตะกร้าสินค้า</h1>
        <div id="cartItems" class="row">
            <!-- สินค้าในตะกร้าจะแสดงที่นี่ -->
        </div>

        <div class="mt-4">
            <button class="btn btn-success" id="checkoutBtn">ยืนยันการสั่งซื้อ</button>
        </div>
    </div>

    <script>
        function displayCart() {
            console.log(JSON.parse(localStorage.getItem('cart')));

            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartItems = document.getElementById('cartItems');
            let totalPrice = 0;

            cartItems.innerHTML = '';

            cart.forEach(item => {
                const itemTotalPrice = item.price * item.amount;
                totalPrice += itemTotalPrice;

                const itemHTML = `
                    <div class="col-md-4" id="item-${item.id}">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">${item.name}</h5>
                                <p class="card-text">ราคา: ${item.price} บาท</p>
                                <p class="card-text">จำนวน: ${item.amount}</p>
                                <p class="card-text">รวมทั้งหมด: ${new Intl.NumberFormat().format(itemTotalPrice)} บาท</p>
                                <button class="btn btn-danger" onclick="removeItem('${item.id}')">ลบ</button>
                            </div>
                        </div>
                    </div>
                `;
                cartItems.innerHTML += itemHTML;
            });

            const totalPriceHTML = `
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">รวมราคาทั้งหมด</h5>
                            <p class="card-text">รวม: ${new Intl.NumberFormat().format(totalPrice)} บาท</p>
                        </div>
                    </div>
                </div>
            `;
            cartItems.innerHTML += totalPriceHTML;
        }

        function removeItem(itemId) {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const updatedCart = cart.filter(item => item.id !== itemId);
            localStorage.setItem('cart', JSON.stringify(updatedCart));
            displayCart();
        }

        document.getElementById('checkoutBtn').addEventListener('click', function() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            if (cart.length > 0) {
                window.location.href = 'http://localhost/ART_TOYS_RANDOMIZE/payment.php';
            } else {
                Swal.fire('ตะกร้าว่างเปล่า', 'กรุณาเลือกสินค้า', 'warning');
            }
        });

        document.addEventListener('DOMContentLoaded', displayCart);
    </script>
</body>

</html>