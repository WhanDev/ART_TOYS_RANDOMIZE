<?php
session_start();
define('BASE_DIR', realpath(__DIR__ . '/../') . '/'); // ตั้งค่า BASE_DIR
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
                <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="height: 100vh; overflow-y: auto;">
                    <?php include BASE_DIR . 'layout/sidebar.php'; ?> <!-- ใช้ BASE_DIR -->
                </nav>
            <?php endif; ?>
            
            <!-- Content Area -->
        </div>
    </div>

    <!-- Scripts -->
</body>

</html>
