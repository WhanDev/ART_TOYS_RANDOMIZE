<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Set BASE_DIR to the absolute path of the root directory
define('BASE_DIR', __DIR__ . '/'); 
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>คำสั่งซื้อ - ART TOYS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" />
    <script data-search-pseudo-elements defer
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php include BASE_DIR . 'layout/nav.php'; ?> <!-- Using BASE_DIR to include nav.php -->
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-<?php echo isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? '9' : '12'; ?> ms-sm-auto col-lg-<?php echo isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? '10' : '12'; ?> px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h3>คำสั่งซื้อ</h3>
                </div>
                <!-- Content Area for Orders -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>เลขที่คำสั่งซื้อ</th>
                                <th>ชื่อผู้สั่งซื้อ</th>

                                <th>วันที่สั่งซื้อ</th>
                                <th>สถานะ</th>
                                <th>รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody id="orderTableBody">
                            <!-- Dynamic content will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
    function fetchOrders() {
        fetch('http://localhost/ART_TOYS_RANDOMIZE/Controller/customer/order/index.php') // ตรวจสอบ URL ให้ถูกต้อง
            .then(response => response.json())
            .then(data => {
                if (data.result === 1) {
                    const tableBody = document.getElementById('orderTableBody');
                    tableBody.innerHTML = '';

                    data.dataList.forEach((order, index) => {
                        const row = `
                            <tr>
                                <th scope="row">${order.or_id}</th>
                                <td><?php echo isset($_SESSION['f_name']) ? $_SESSION['f_name'] : 'ไม่ระบุ'; ?></td>
                                <td>${order.or_date}</td>
                                <td>${order.or_status}</td>
                                <td><button class="btn btn-info" onclick="viewOrderDetails(${order.or_id})">ดูรายละเอียด</button></td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });
                } else {
                    console.error('Failed to fetch data:', data.message);
                }
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    function viewOrderDetails(orderId) {
        console.log('Viewing details for order:', orderId);
        window.location.href = `http://localhost/ART_TOYS_RANDOMIZE/orderDetail.php?or_id=${orderId}`;
    }

    window.onload = fetchOrders;

</script>
</body>

</html>
