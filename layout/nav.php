<?php
if (!defined('BASE_DIR')) {
    define('BASE_DIR', 'http://localhost/ART_TOYS_RANDOMIZE/');
}

?>
<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'customer'): ?>
            <a class="navbar-brand" href="product.php">ART TOYS</a>
        <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <a class="navbar-brand" href="index.php">ART TOYS</a>
        <?php else: ?>
            <a class="navbar-brand" href="index.php">ART TOYS</a>
        <?php endif; ?>
    </nav>

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
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'customer'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="product.php">สินค้า</a>
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
                    <li>
                        <a href="cart.php" class="btn btn-outline-secondary position-relative">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                id="cartCount">
                                0
                            </span>
                        </a>
                    </li>
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

    function updateCartCount() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let totalItems = cart.reduce((total, product) => total + parseInt(product.amount), 0); // นับจำนวนสินค้าทั้งหมด

        document.getElementById('cartCount').textContent = totalItems; // อัปเดตจำนวนที่แสดงใน Badge
    }

    // เรียกใช้งานฟังก์ชันนี้ทุกครั้งที่โหลดหน้าเว็บ
    document.addEventListener('DOMContentLoaded', function () {
        updateCartCount(); // โหลดจำนวนสินค้าในตะกร้าทันทีเมื่อหน้าเว็บโหลดเสร็จ

        // ฟังก์ชันนี้จะถูกเรียกทุกครั้งที่เพิ่มสินค้าลงตะกร้า
        function addToCart(product) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            let existingProduct = cart.find(item => item.id === product.id);
            if (existingProduct) {
                existingProduct.amount = parseInt(existingProduct.amount) + parseInt(product.amount);
            } else {
                cart.push(product);
            }

            localStorage.setItem('cart', JSON.stringify(cart));

            // อัปเดตจำนวนสินค้าในไอคอนตะกร้าทันทีหลังเพิ่มสินค้า
            updateCartCount();

            Swal.fire({
                title: 'เพิ่มลงตะกร้าสำเร็จ',
                text: product.name + ' ถูกเพิ่มลงในตะกร้า!',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            });
        }
    });
</script>