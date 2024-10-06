<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: http://localhost/ART_TOYS_RANDOMIZE/index.php");
    exit();
}
define('BASE_DIR', realpath(__DIR__ . '/../../') . '/'); // Set BASE_DIR
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลสินค้า - ART TOYS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php include BASE_DIR . 'layout/nav.php'; ?>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="height: 100vh; overflow-y: auto;">
                    <?php include BASE_DIR . 'layout/sidebar.php'; ?>
                </nav>
            <?php endif; ?>

            <div class="col-md-9 ms-sm-auto col-lg-10">
                <div class="row">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h3>แก้ไขข้อมูลสินค้า</h3>
                    </div>
                    <div class="border border-rounded border-rounded-lg">
                        <form id="productForm" class="p-3" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="prod_id" class="form-label">รหัสสินค้า</label>
                                <input type="text" class="form-control" id="prod_id" required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="prod_name" class="form-label">ชื่อสินค้า</label>
                                <input type="text" class="form-control" id="prod_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="prod_size" class="form-label">ขนาดสินค้า</label>
                                <input type="text" class="form-control" id="prod_size" required>
                            </div>
                            <div class="mb-3">
                                <label for="prod_amount" class="form-label">จำนวนสินค้า</label>
                                <input type="number" class="form-control" id="prod_amount" required>
                            </div>
                            <div class="mb-3">
                                <label for="prod_price" class="form-label">ราคาสินค้า</label>
                                <input type="number" class="form-control" id="prod_price" required>
                            </div>
                            <div class="mb-3">
                                <label for="type_id" class="form-label">ประเภทสินค้า</label>
                                <select class="form-select" id="type_id" required>
                                    <option value="" disabled selected>เลือกประเภทสินค้า</option>
                                    <!-- Options will be populated by JavaScript -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="prod_img" class="form-label">อัปโหลดรูปภาพ</label>
                                <input type="file" class="form-control" id="prod_img" accept="image/*">
                            </div>
                            <div class="mb-3 text-end">
                                <button type="button" class="btn btn-danger" onclick="window.history.back();">ย้อนกลับ</button>
                                <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const prod_id = urlParams.get('prod_id');

        function fetchSelectedProductType() {
            fetch('http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/product_type/index.php')
                .then(response => response.json())
                .then(data => {
                    if (data.result === 1) {
                        const typeSelect = document.getElementById('type_id');
                        data.dataList.forEach(type => {
                            const option = document.createElement('option');
                            option.value = type.type_id;
                            option.textContent = type.type_name;
                            typeSelect.appendChild(option);
                        });
                    } else {
                        alert('ไม่พบข้อมูลประเภทสินค้า');
                    }
                })
                .catch(error => {
                    console.error('เกิดข้อผิดพลาด:', error);
                    alert('เกิดข้อผิดพลาดในการโหลดข้อมูลประเภทสินค้า');
                });
        }

        function fetchProductData(prod_id) {
            fetch(`http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/product/detail.php?prod_id=${prod_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.result === 1) {
                        const productData = data.dataList;
                        document.getElementById('prod_id').value = productData.prod_id;
                        document.getElementById('prod_name').value = productData.prod_name;
                        document.getElementById('prod_size').value = productData.prod_size;
                        document.getElementById('prod_amount').value = productData.prod_amount;
                        document.getElementById('prod_price').value = productData.prod_price;
                        document.getElementById('type_id').value = productData.type_id; // Set selected type directly
                    } else {
                        alert('ไม่พบข้อมูลสินค้า');
                    }
                })
                .catch(error => {
                    console.error('เกิดข้อผิดพลาด:', error);
                    alert('เกิดข้อผิดพลาดในการโหลดข้อมูล');
                });
        }

        window.onload = function () {
            fetchSelectedProductType(); // Fetch types first to populate the select options
            fetchProductData(prod_id); // Then fetch product data
        };

        document.getElementById('productForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('prod_id', document.getElementById('prod_id').value);
            formData.append('prod_name', document.getElementById('prod_name').value);
            formData.append('prod_size', document.getElementById('prod_size').value);
            formData.append('prod_amount', document.getElementById('prod_amount').value);
            formData.append('prod_price', document.getElementById('prod_price').value);
            formData.append('type_id', document.getElementById('type_id').value);

            // Check if an image file is selected and append to formData
            const prod_img = document.getElementById('prod_img').files[0];
            if (prod_img) {
                formData.append('prod_img', prod_img);
            }

            fetch(`http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/product/edit.php?prod_id=${prod_id}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.result === 1) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'ข้อมูลสินค้าได้รับการอัปเดตเรียบร้อยแล้ว',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        window.location.href = 'index.php'; // Redirect to index.php
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
        });
    </script>

</body>

</html>
