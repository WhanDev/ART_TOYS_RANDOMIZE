<?php
session_start();
define('BASE_DIR', realpath(__DIR__ . '/../') . '/'); // ตั้งค่า BASE_DIR

// ตรวจสอบการเข้าถึง
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ด - ART TOYS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php include BASE_DIR . 'layout/nav.php'; ?>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar" style="height: 100vh; overflow-y: auto;">
                <?php include BASE_DIR . 'layout/sidebar.php'; ?>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1>แดชบอร์ด</h1>
                </div>
                <!-- Dynamic Content goes here -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">สถิติผู้ใช้งาน</h5>
                                <p class="card-text">ข้อมูลเกี่ยวกับจำนวนผู้ใช้งานในระบบ</p>
                                <hr>
                                <p class="card-text  text-end fs-2" id="userStatistics">กำลังโหลด...</p>
                                <!-- แสดงสถิติต่างๆ ที่นี่ -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">จำนวนสินค้าที่ขาย</h5>
                                <p class="card-text">ข้อมูลเกี่ยวกับจำนวนที่มีขายในระบบ</p>
                                <hr>
                                <p class="card-text text-end fs-2" id="productStatistics">
                                    กำลังโหลด...
                                </p>
                                <!-- แสดงรายงานการขายที่นี่ -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">รายงานการขาย</h5>
                                <p class="card-text">ข้อมูลเกี่ยวกับจำนวนการสั่งสินค้าในระบบ</p>
                                <hr>
                                <p class="card-text text-end fs-2" id="orderStatistics">
                                    กำลังโหลด...
                                </p>
                                <!-- แสดงรายงานการขายที่นี่ -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">สถิติการขายเสร็จสิ้น</h5>
                                    <p class="card-text">ข้อมูลเกี่ยวกับการขายที่เสร็จสิ้น</p>
                                    <hr>
                                    <p class="card-text  text-end fs-2" id="ComOrderStatistics">กำลังโหลด...</p>
                                    <!-- แสดงสถิติต่างๆ ที่นี่ -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">สถิติการขายที่ยังไม่เสร็จ</h5>
                                    <p class="card-text">ข้อมูลเกี่ยวกับการขายที่ยังไม่เสร็จ</p>
                                    <hr>
                                    <p class="card-text text-end fs-2" id="QueOrderStatistics">
                                        กำลังโหลด...
                                    </p>
                                    <!-- แสดงรายงานการขายที่นี่ -->
                                </div>
                            </div>
                        </div>
                    </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

</body>
<script>
    $(document).ready(function() {
        // ดึงข้อมูลยอดขายรายเดือน
        $.ajax({
            url: 'http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/dashboard/countUser.php',
            method: 'GET',
            success: function(response) {
                if (response.result === 1) {
                    $('#userStatistics').text(response.data + " คน");
                } else {
                    $('#userStatistics').text("ยังไม่มีข้อมูล");
                }
            },
            error: function() {
                $('#userStatistics').text("เกิดข้อผิดพลาดในการดึงข้อมูล");
            }
        });

        $.ajax({
            url: 'http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/dashboard/countProduct.php',
            method: 'GET',
            success: function(response) {
                if (response.result === 1) {
                    $('#productStatistics').text(response.data + " ชิ้น");
                } else {
                    $('#productStatistics').text("ยังไม่มีข้อมูล");
                }
            },
            error: function() {
                $('#productStatistics').text("เกิดข้อผิดพลาดในการดึงข้อมูล");
            }
        });

        $.ajax({
            url: 'http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/dashboard/countOrder.php',
            method: 'GET',
            success: function(response) {
                if (response.result === 1) {
                    $('#orderStatistics').text(response.data + " รายการ");
                } else {
                    $('#orderStatistics').text("ยังไม่มีข้อมูล");
                }
            },
            error: function() {
                $('#orderStatistics').text("เกิดข้อผิดพลาดในการดึงข้อมูล");
            }
        });

        $.ajax({
            url: 'http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/dashboard/countOrderCom.php',
            method: 'GET',
            success: function(response) {
                if (response.result === 1) {
                    $('#ComOrderStatistics').text(response.data + " รายการ");
                } else {
                    $('#ComOrderStatistics').text("ยังไม่มีข้อมูล");
                }
            },
            error: function() {
                $('#ComOrderStatistics').text("เกิดข้อผิดพลาดในการดึงข้อมูล");
            }
        });

        $.ajax({
            url: 'http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/dashboard/countOrderQue.php',
            method: 'GET',
            success: function(response) {
                if (response.result === 1) {
                    $('#QueOrderStatistics').text(response.data + " รายการ");
                } else {
                    $('#QueOrderStatistics').text("ยังไม่มีข้อมูล");
                }
            },
            error: function() {
                $('#QueOrderStatistics').text("เกิดข้อผิดพลาดในการดึงข้อมูล");
            }
        });

    });
</script>

</html>