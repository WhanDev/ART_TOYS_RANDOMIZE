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
    <title>เพิ่มข้อมูลสินค้า - ART TOYS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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
                    <div
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h3>เพิ่มข้อมูลสินค้า</h3>
                    </div>
                    <div class="border border-rounded border-rounded-lg">
                        <form onsubmit="addProduct(event)" class="p-3" enctype="multipart/form-data">
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
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="prod_img" class="form-label">อัปโหลดรูปภาพ</label>
                                <input type="file" class="form-control" id="prod_img" accept="image/*" required>
                            </div>
                            <div class="mb-3 text-end">
                                <button type="button" class="btn btn-danger"
                                    onclick="window.history.back();">ย้อนกลับ</button>
                                <button type="submit" class="btn btn-primary">เพิ่มสินค้า</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addProduct(event) {
            event.preventDefault(); // หยุดการส่งฟอร์มตามปกติ

            const formData = new FormData();
            formData.append('prod_name', $('#prod_name').val());
            formData.append('prod_size', $('#prod_size').val());
            formData.append('prod_amount', $('#prod_amount').val());
            formData.append('prod_price', $('#prod_price').val());
            formData.append('type_id', $('#type_id').val());
            formData.append('prod_img', $('#prod_img')[0].files[0]);

            fetch('http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/product/add.php', {
                method: 'POST',
                body: formData // ส่งข้อมูลเป็น FormData
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
                            window.location.href = 'index.php'; // เปลี่ยนไปยังหน้า index.php
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

        // ฟังก์ชันดึงข้อมูลประเภทสินค้าและแสดงใน select
        function fetchSelectedProductType() {
            fetch('http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/product_type/index.php')
                .then(response => response.json())
                .then(data => {
                    if (data.result === 1) {
                        const typeSelect = document.getElementById('type_id');
                        typeSelect.innerHTML = '<option value="" disabled selected>เลือกประเภทสินค้า</option>';
                        
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

        // เรียกฟังก์ชันเมื่อโหลดหน้า
        window.onload = function () {
            fetchSelectedProductType();
        };
    </script>
</body>

</html>
