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
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">สถิติผู้ใช้งาน</h5>
                                <p class="card-text">ข้อมูลเกี่ยวกับจำนวนผู้ใช้งานในระบบ</p>
                                <!-- แสดงสถิติต่างๆ ที่นี่ -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">รายงานการขาย</h5>
                                <p class="card-text">ข้อมูลเกี่ยวกับรายงานการขายสินค้า</p>
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
</body>

</html>
