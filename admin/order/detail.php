<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: http://localhost/ART_TOYS_RANDOMIZE/index.php");
    exit();
}
define('BASE_DIR', realpath(__DIR__ . '/../../') . '/'); // ตั้งค่า BASE_DIR
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดคำสั่งซื้อ - ART TOYS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert2 -->
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php include BASE_DIR . 'layout/nav.php'; ?> <!-- ใช้ BASE_DIR -->
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse"
                    style="height: 100vh; overflow-y: auto;">
                    <?php include BASE_DIR . 'layout/sidebar.php'; ?> <!-- ใช้ BASE_DIR -->
                </nav>
            <?php endif; ?>

            <div class="col-md-9 ms-sm-auto col-lg-10">
                <div class="row">
                    <div
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h3>จัดการข้อมูลคำสั่งซื้อ</h3>
                        <button type="button" class="btn btn-primary" onclick="confirmOrder(or_id)">ยืนยันชำระเงิน</button>
                    </div>
                    <div id="orderDetails"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const or_id = urlParams.get('or_id') || 9; // Change to dynamic order ID or default to 9
        fetchOrderDetails(or_id);

        function fetchOrderDetails(or_id) {
            fetch(`http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/order/detail.php?or_id=${or_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.result === 1) {
                        let totalPrice = 0;
                        const orderItems = data.dataList;

                        let detailsHTML = `
                            <h4>เลขที่คำสั่งซื้อ: ${orderItems[0].or_id}</h4>
                            <p>วันที่สั่งซื้อ: ${orderItems[0].or_date}</p>
                            <p>สถานะ: ${orderItems[0].or_status}</p>
                            <h5>รายการสินค้า</h5>
                            <ul class="list-group">`;

                        orderItems.forEach(item => {
                            const imageUrl = item.prod_img ? 
                                `http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/product/uploads/${item.prod_img}` : 
                                'path/to/default-image.jpg'; // Replace with a valid default image path
                            const itemTotalPrice = item.prod_price * item.ordt_amount;
                            totalPrice += itemTotalPrice;

                            detailsHTML += `
                                <li class="list-group-item d-flex align-items-center">
                                    <img src="${imageUrl}" class="img-fluid me-3" alt="${item.prod_name}" style="height: 80px; width: 80px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">${item.prod_name}</h6>
                                        <p class="mb-1">จำนวน: ${item.ordt_amount}</p>
                                        <small class="text-muted">ราคา: ${new Intl.NumberFormat().format(item.prod_price)} บาท</small>
                                        <p class="mb-1">รวมทั้งหมด: ${new Intl.NumberFormat().format(itemTotalPrice)} บาท</p>
                                    </div>
                                </li>`;
                        });

                        detailsHTML += `
                            </ul>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">รวมราคาทั้งหมด</h5>
                                    <p class="card-text">รวม: ${new Intl.NumberFormat().format(totalPrice)} บาท</p>
                                </div>
                            </div>`;

                        $('#orderDetails').html(detailsHTML);
                    } else {
                        $('#orderDetails').html('<p>ไม่สามารถโหลดรายละเอียดคำสั่งซื้อได้</p>');
                        console.error('Failed to fetch order details:', data.messages);
                    }
                })
                .catch(error => {
                    console.error('Error fetching order details:', error);
                    $('#orderDetails').html('<p>เกิดข้อผิดพลาดในการโหลดรายละเอียด</p>');
                });
        }

        function confirmOrder(or_id) {
            Swal.fire({
                title: 'ยืนยันการชำระเงิน',
                text: 'คุณแน่ใจว่าต้องการยืนยันการชำระเงินนี้?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ยืนยันเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/order/confirm.php?or_id=${or_id}`, {
                        method: 'PATCH'
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.result === 1) {
                                Swal.fire({
                                    title: 'สำเร็จ!',
                                    text: data.messages,
                                    icon: 'success',
                                    confirmButtonText: 'ตกลง'
                                }).then(() => {
                                    fetchOrderDetails(or_id);
                                    window.location.back();
                                });
                            } else {
                                Swal.fire({
                                    title: 'เกิดข้อผิดพลาด!',
                                    text: data.messages,
                                    icon: 'error',
                                    confirmButtonText: 'ตกลง'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('เกิดข้อผิดพลาด:', error);
                            Swal.fire({
                                title: 'เกิดข้อผิดพลาด!',
                                text: 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้',
                                icon: 'error',
                                confirmButtonText: 'ตกลง'
                            });
                        });
                }
            });
        }
    </script>
</body>

</html>
