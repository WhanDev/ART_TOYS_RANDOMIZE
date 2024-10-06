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
    <title>เพิ่มข้อมูลประเภทสินค้า - ART TOYS</title>
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
                        <h3>เพิ่มข้อมูลประเภทสินค้า</h3>
                    </div>
                    <div class="border border-rounded border-rounded-lg">
                        <form onsubmit="addProductType(event)" class="p-3">
                            <div class="mb-3">
                                <label for="type_name" class="form-label">ชื่อประเภทสินค้า</label>
                                <input type="text" class="form-control" id="type_name" required>
                            </div>
                            <div class="mb-3 text-end">
                                <button type="button" class="btn btn-danger" onclick="window.history.back();">ย้อนกลับ</button>
                                <button type="submit" class="btn btn-primary">เพิ่มประเภทสินค้า</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addProductType(event) {
            event.preventDefault(); // หยุดการส่งฟอร์มตามปกติ

            const formData = {
                type_name: $('#type_name').val()
            };

            fetch('http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/product_type/add.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData) // เปลี่ยนข้อมูลเป็น JSON
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
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
    </script>
</body>

</html>
