<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Get the order ID from the URL parameter
$order_id = isset($_GET['or_id']) ? intval($_GET['or_id']) : 0;

// If order ID is invalid, redirect or show an error
if ($order_id <= 0) {
    echo "Invalid Order ID.";
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
    <title>รายละเอียดคำสั่งซื้อ - ART TOYS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php include BASE_DIR . 'layout/nav.php'; ?>
    </nav>

    <div class="container mt-5">
        <h1>รายละเอียดคำสั่งซื้อ</h1>
        <div id="orderDetails"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            const orderId = <?php echo json_encode($order_id); ?>;
            console.log('Order ID:', orderId);
            fetch(`http://localhost/ART_TOYS_RANDOMIZE/Controller/customer/order/detail.php?or_id=${orderId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.result === 1) {
                        let totalPrice = 0;
                        const order = data.dataList; // Assuming the response contains dataList
                        let detailsHTML = `
                            <h3>เลขที่คำสั่งซื้อ: ${order[0].or_id}</h3>
                            <p>วันที่สั่งซื้อ: ${order[0].or_date}</p>
                            <p>สถานะ: ${order[0].or_status}</p>
                            <h4>รายการสินค้า</h4>
                            <ul class="list-group">`;

                        order.forEach(item => {
                            const imageUrl = item.prod_img ?
                                `http://localhost/ART_TOYS_RANDOMIZE/Controller/admin/product/uploads/${item.prod_img}` :
                                'path/to/default-image.jpg'; // Provide a default image path
                                const itemTotalPrice = item.prod_price * item.ordt_amount;
                                totalPrice += itemTotalPrice;
                            detailsHTML += `
                                <li class="list-group-item d-flex align-items-center">
                                    <img src="${imageUrl}" class="img-fluid me-3" alt="${item.prod_name}" style="height: 80px; width: 80px; object-fit: cover;">
                                    <div class="flex-grow-1  text-end">
                                        <h5 class="mb-1">${item.prod_name}</h5>
                                        <p class="mb-1">จำนวน: ${item.ordt_amount}</p>
                                        <small class="text-muted">ราคา: ${item.prod_price} บาท</small>
                                        <p class="mb-1">รวมทั้งหมด: ${new Intl.NumberFormat().format(itemTotalPrice)} บาท</p>
                                        
                                    </div>
                                </li>`;

                        });

                        const totalPriceHTML = `
                            <div class="col-md-12 text-end">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">รวมราคาทั้งหมด</h5>
                                        <p class="card-text">รวม: ${new Intl.NumberFormat().format(totalPrice)} บาท</p>
                                    </div>
                                </div>
                            </div>
                        `;

                        detailsHTML += totalPriceHTML;

                        detailsHTML += `</ul>`;
                        $('#orderDetails').html(detailsHTML);
                    } else {
                        $('#orderDetails').html('<p>ไม่สามารถโหลดรายละเอียดคำสั่งซื้อได้</p>');
                        console.error('Failed to fetch order details:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching order details:', error);
                    $('#orderDetails').html('<p>เกิดข้อผิดพลาดในการโหลดรายละเอียด</p>');
                });
        });
    </script>
</body>

</html>