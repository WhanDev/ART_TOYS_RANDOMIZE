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
    <title>หน้าแรก - ART TOYS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php include BASE_DIR . 'layout/nav.php'; ?>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse"
                    style="height: 100vh; overflow-y: auto;">
                    <?php include BASE_DIR . 'layout/sidebar.php'; ?>
                </nav>
            <?php endif; ?>

            <div class="col-md-9 ms-sm-auto col-lg-10">
                <div class="row">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h3>จัดการข้อมูลสินค้า</h3>
                        <a class="btn btn-primary" href="add.php">เพิ่มสินค้า</a>
                    </div>
                    <div class="border border-rounded border-rounded-lg">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">ลำดับ</th>
                                    <th scope="col">รหัสสินค้า</th>
                                    <th scope="col">ชื่อสินค้า</th>
                                    <th scope="col">ขนาด</th>
                                    <th scope="col">จำนวน</th>
                                    <th scope="col">ราคา</th>
                                    <th scope="col">รูปภาพ</th>
                                    <th scope="col">ประเภทสินค้า</th>
                                    <th scope="col">ลบสินค้า</th>
                                    <th scope="col">แก้ไขสินค้า</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
    function fetchProducts() {
        fetch('http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/product/index.php')
            .then(response => response.json())
            .then(data => {
                if (data.result === 1) {
                    const tableBody = document.getElementById('productTableBody');
                    tableBody.innerHTML = '';

                    data.dataList.forEach((product, index) => {
                        console.log(product);
                        const row = `
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td>${product.prod_id}</td>
                                <td>${product.prod_name}</td>
                                <td>${product.prod_size}</td>
                                <td>${product.prod_amount}</td>
                                <td>${product.prod_price}</td>
                                <td><img src="http://localhost/ART_TOYS_RANDOMIZE/Controller/admin/product/uploads/${product.prod_img}" alt="${product.prod_name}" style="width: 150px; height: auto;"></td>
                                <td>${product.type_name}</td>
                                <td><button class="btn btn-danger" onclick="deleteProduct(${product.prod_id})">ลบ</button></td>
                                <td><a class="btn btn-warning" href="edit.php?prod_id=${product.prod_id}">แก้ไข</a></td>
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

    window.onload = fetchProducts;

    function deleteProduct(prod_id) {
        Swal.fire({
            title: 'ยืนยันการลบ',
            text: 'คุณแน่ใจว่าต้องการลบสินค้านี้?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/product/delete.php?prod_id=${prod_id}`, {
                    method: 'DELETE'
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
                                fetchProducts(); // เรียกใช้อัปเดตข้อมูลสินค้า
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

</html>
