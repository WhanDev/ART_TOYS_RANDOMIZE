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
    <title>ข้อมูลส่วนตัว - ART TOYS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" />
    <script data-search-pseudo-elements defer
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js"
        crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php include BASE_DIR . 'layout/nav.php'; ?> <!-- Using BASE_DIR to include nav.php -->
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse"
                    style="height: 100vh; overflow-y: auto;">
                    <?php include BASE_DIR . 'layout/sidebar.php'; ?> <!-- Using BASE_DIR to include sidebar.php -->
                </nav>
            <?php endif; ?>

            <div
                class="col-md-<?php echo isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? '9' : '12'; ?> ms-sm-auto col-lg-<?php echo isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? '10' : '12'; ?> px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h3>ข้อมูลส่วนตัว</h3>
                </div>

                <!-- Profile Information -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">ข้อมูลผู้ใช้</h5>
                        <p class="card-text">ชื่อ: <?php echo $_SESSION['f_name'] ?? 'ไม่มีข้อมูล'; ?>
                            <?php echo $_SESSION['l_name'] ?? ''; ?></p>
                        <p class="card-text">อีเมล์: <?php echo $_SESSION['email']; ?></p>
                        <p class="card-text">เบอร์โทร: <?php echo $_SESSION['tel'] ?? 'ไม่มีข้อมูล'; ?></p>
                        <p class="card-text">ที่อยู่: <?php echo $_SESSION['address'] ?? 'ไม่มีข้อมูล'; ?></p>
                        <p class="card-text">บทบาท: <?php echo $_SESSION['user_role'] ?? 'ผู้ใช้'; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</body>

</html>