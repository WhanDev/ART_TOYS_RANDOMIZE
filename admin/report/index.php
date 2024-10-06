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
                    <?php include BASE_DIR . 'layout/sidebar.php'; ?> <!-- ใช้ BASE_DIR -->
                </nav>
            <?php endif; ?>
            <div class="col-md-<?php echo isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? '9' : '12'; ?> ms-sm-auto col-lg-<?php echo isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? '10' : '12'; ?>">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">รายงานยอดขายตามเดือน</h1>
                </div>

                <form id="reportForm" class="mb-3" method="GET">
                    <div class="form-group">
                        <label for="month">เดือน:</label>
                        <input type="number" class="form-control" name="month" value="<?php echo date('m'); ?>" min="1" max="12">
                    </div>
                    <div class="form-group">
                        <label for="year">ปี:</label>
                        <input type="number" class="form-control" name="year" value="<?php echo date('Y'); ?>" min="2020">
                    </div>
                    <button type="submit" class="btn btn-primary">ดูรายงาน</button>
                </form>

                <div id="reportResult" class="mt-4">

                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#reportForm').submit(function (event) {
                event.preventDefault();
                let month = $('input[name="month"]').val();
                let year = $('input[name="year"]').val();

                $.ajax({
                    url: 'http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/report/report.php',
                    method: 'GET',
                    data: { month: month, year: year },
                    success: function (response) {
                        console.log(response); // ดูข้อมูลที่ตอบกลับ
                        if (response.result === 1) {
                            let table = `
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ชื่อสินค้า</th>
                                            <th>จำนวนที่ขายได้</th>
                                            <th>ยอดขายรวม (บาท)</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                            response.data.forEach(function (item) {
                                table += `
                                    <tr>
                                        <td>${item.prod_name}</td>
                                        <td>${item.total_sold}</td>
                                        <td>${parseFloat(item.total_revenue).toFixed(2)}</td>
                                    </tr>`;
                            });
                            table += `</tbody></table>`;
                            $('#reportResult').html(table);
                        } else {
                            $('#reportResult').html('<p>' + response.messages + '</p>');
                        }
                    },
                    error: function () {
                        $('#reportResult').html('<p>เกิดข้อผิดพลาดในการดึงข้อมูล</p>');
                    }
                });
            });
        });
    </script>