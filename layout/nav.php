<?php
if (!defined('BASE_DIR')) {
    define('BASE_DIR', 'http://localhost/ART_TOYS_RANDOMIZE/'); 
}
?>
<div class="container-fluid">
    <a class="navbar-brand" href="index.php">ART TOYS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <div class="d-md-none">
            <?php include BASE_DIR . '/layout/sidebar.php'; ?>
        </div>
    <?php endif; ?>

        <ul class="navbar-nav me-auto">
            <?php if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] === 'customer'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">สินค้า</a>
                </li>
            <?php endif; ?>
        </ul>

        <ul class="navbar-nav ms-auto">
            <?php if (!isset($_SESSION['email'])) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">สมัครสมาชิก</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">เข้าสู่ระบบ</a>
                </li>
            <?php } else { ?>

                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'customer'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">ข้อมูลส่วนตัว</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="order.php">คำสั่งซื้อ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" onClick="logout()">
                            <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
                        </a>
                    </li>
                <?php endif; ?>

            <?php } ?>
        </ul>
    </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    function logout() {
        Swal.fire({
            title: 'ยืนยันการออกจากระบบ?',
            text: "คุณแน่ใจหรือไม่ว่าต้องการออกจากระบบ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ใช่, ต้องการออกจากระบบ',
            cancelButtonText: 'ไม่, ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('http://localhost/ART_TOYS_RANDOMIZE/Controller/logout.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.result === 1) {
                            Swal.fire({
                                title: 'ออกจากระบบสำเร็จ!',
                                text: data.messages,
                                icon: 'success',
                                confirmButtonText: 'ตกลง'
                            }).then(() => {
                                // เปลี่ยนไปยังหน้า index.php
                                window.location.href = 'http://localhost/ART_TOYS_RANDOMIZE/index.php';
                            });
                        } else {
                            Swal.fire({
                                title: 'เกิดข้อผิดพลาด!',
                                text: 'ไม่สามารถออกจากระบบได้ กรุณาลองอีกครั้ง',
                                icon: 'error',
                                confirmButtonText: 'ตกลง'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('เกิดข้อผิดพลาด:', error);
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถออกจากระบบได้ กรุณาลองอีกครั้ง',
                            icon: 'error',
                            confirmButtonText: 'ตกลง'
                        });
                    });
            }
        });
    }
</script>