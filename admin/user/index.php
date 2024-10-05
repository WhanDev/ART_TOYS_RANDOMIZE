<?php
session_start();
define('BASE_DIR', realpath(__DIR__ . '/../../') . '/'); // ตั้งค่า BASE_DIR
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <!-- ส่วนของ head -->
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
                    <?php include BASE_DIR . 'layout/sidebar.php'; ?> <!-- ใช้ BASE_URL -->
                </nav>
            <?php endif; ?>

            <div class="col-md-9 ms-sm-auto col-lg-10">
                <div class="row">
                    <div
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h5>จัดการข้อมูลผู้ใช้งาน</h5>
                        <button class="btn btn-primary">เพิ่มผู้ใช้งาน</button>
                    </div>
                    <div class="border border-rounded border-rounded-lg">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">ลำดับ</th>
                                    <th scope="col">รหัสผู้ใช้</th>
                                    <th scope="col">ชื่อ</th>
                                    <th scope="col">นามสกุล</th>
                                    <th scope="col">อีเมล์</th>
                                    <th scope="col">เบอร์โทร</th>
                                    <th scope="col">ที่อยู่</th>
                                    <th scope="col">สิทธิ์ผู้ใช้</th>
                                    <th scope="col">ลบผู้ใช้</th>
                                    <th scope="col">แก้ไขผู้ใช้</th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>

</body>
<script>
    function fetchUsers() {
        fetch('http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/user/index.php')
            .then(response => response.json())
            .then(data => {
                if (data.result === 1) {
                    const tableBody = document.getElementById('userTableBody');
                    tableBody.innerHTML = '';

                    data.dataList.forEach((user, index) => {
                        const row = `
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td>${user.user_id}</td>
                                <td>${user.f_name}</td>
                                <td>${user.l_name}</td>
                                <td>${user.email}</td>
                                <td>${user.tel}</td>
                                <td>${user.address}</td>
                                <td>${user.user_role}</td>
                                <td><button class="btn btn-danger" onclick="deleteUser(${user.user_id})">ลบ</button></td>
                                <td><button class="btn btn-warning" onclick="editUser(${user.user_id})">แก้ไข</button></td>
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

    window.onload = fetchUsers();

    function deleteUser(userId) {
        console.log('Deleting user with ID:', userId);
        fetchUsers();
    }

    function editUser(userId) {
        console.log('Editing user with ID:', userId);
    }
</script>
</html>