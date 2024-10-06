<?php
session_start();
define('BASE_DIR', realpath(__DIR__ . '/../../') . '/'); // ตั้งค่า BASE_DIR
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลสินค้า - ART TOYS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                        <h3>แก้ไขข้อมูลสินค้า</h3>
                    </div>
                    <div class="border border-rounded border-rounded-lg">
                        <form id="userForm" class="p-3">
                            <div class="mb-3">
                                <label for="f_name" class="form-label">ชื่อ</label>
                                <input type="text" class="form-control" id="f_name" name="f_name">
                            </div>
                            <div class="mb-3">
                                <label for="l_name" class="form-label">นามสกุล</label>
                                <input type="text" class="form-control" id="l_name" name="l_name">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">อีเมล์</label>
                                <input type="email" class="form-control" id="email" name="email" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="tel" class="form-label">เบอร์โทร</label>
                                <input type="text" class="form-control" id="tel" name="tel">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">ที่อยู่</label>
                                <input type="text" class="form-control" id="address" name="address">
                            </div>
                            <div class="mb-3">
                                <label for="user_role" class="form-label">สิทธิ์ผู้ใช้</label>
                                <input type="text" class="form-control" id="user_role" name="user_role">
                            </div>
                            <div class="mb-3 text-end">
                                <button type="button" class="btn btn-danger"
                                    onclick="window.history.back();">ย้อนกลับ</button>
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

        function fetchUserData(userId) {
            fetch(`http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/user/show.php?prod_id=${prod_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.result === 1) {
                        const userData = data.data;
                        document.getElementById('f_name').value = userData.f_name;
                        document.getElementById('l_name').value = userData.l_name;
                        document.getElementById('email').value = userData.email;
                        document.getElementById('tel').value = userData.tel;
                        document.getElementById('address').value = userData.address;
                        document.getElementById('user_role').value = userData.user_role;
                    } else {
                        alert('ไม่พบข้อมูลผู้ใช้');
                    }
                })
                .catch(error => {
                    console.error('เกิดข้อผิดพลาด:', error);
                    alert('เกิดข้อผิดพลาดในการโหลดข้อมูล');
                });
        }

        window.onload = function() {
            fetchUserData(userId);
        };

        document.getElementById('userForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // ตรวจสอบค่าของแต่ละช่อง input
            const f_name = document.getElementById('f_name').value.trim();
            const l_name = document.getElementById('l_name').value.trim();
            const tel = document.getElementById('tel').value.trim();
            const address = document.getElementById('address').value.trim();
            const user_role = document.getElementById('user_role').value.trim();

            if (!f_name || !l_name || !tel || !address || !user_role) {
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'กรุณากรอกข้อมูลในช่องที่จำเป็นให้ครบถ้วน',
                    icon: 'warning',
                    confirmButtonText: 'ตกลง'
                });
                return;
            }

            const formData = {
                f_name,
                l_name,
                email: document.getElementById('email').value,
                tel,
                address,
                user_role
            };

            fetch(`http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/user/edit.php?user_id=${userId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.result === 1) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'ข้อมูลผู้ใช้ได้รับการอัพเดทเรียบร้อยแล้ว',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        window.location.href = 'index.php';
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
