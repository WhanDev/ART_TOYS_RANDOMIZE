<?php
session_start();

// ตรวจสอบว่า user_role เป็น 'admin' หรือไม่
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header("Location: http://localhost/ART_TOYS_RANDOMIZE/admin/index.php");
    exit(); // หยุดการทำงานหลังจากเปลี่ยนเส้นทาง
} elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'customer') {
    header("Location: http://localhost/ART_TOYS_RANDOMIZE/product.php");
    exit(); // หยุดการทำงานหลังจากเปลี่ยนเส้นทาง
}

// ถ้าไม่มีการเข้าสู่ระบบให้แสดงเนื้อหานี้
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าแรก - ART TOYS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php include 'layout/nav.php'; ?> <!-- Using BASE_DIR to include files -->
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar for admin only -->
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="height: 100vh; overflow-y: auto;">
                    <?php include 'layout/sidebar.php'; ?> <!-- Using BASE_DIR to include files -->
                </nav>
            <?php endif; ?>

            <!-- Main Content Area -->
            <main class="col-md-<?php echo (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') ? '9' : '12'; ?> ms-sm-auto col-lg-<?php echo (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') ? '10' : '12'; ?> px-md-4">
                <?php include 'content.php'; ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>
