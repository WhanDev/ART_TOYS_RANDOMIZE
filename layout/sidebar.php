<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/ART_TOYS_RANDOMIZE/'); 
}
?>
<div class="position-sticky">
    <ul class="nav navbar-nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL . 'admin/user/index.php'; ?>">
                จัดการข้อมูลผู้ใช้ระบบ
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL . 'admin/productType/index.php'; ?>">
                จัดการข้อมูลประเภทสินค้า
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL . 'admin/product/index.php'; ?>">
                จัดการข้อมูลสินค้า
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL . 'admin/order/index.php'; ?>">
                จัดการข้อมูลคำสั่งซื้อ
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL . 'admin/report/index.php'; ?>">
                รายงาน
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL . 'profile.php'; ?>">
                ข้อมูลส่วนตัว
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="javascript:void(0);" onClick="logout()">
                ออกจากระบบ
            </a>
        </li>
    </ul>
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
                                window.location.href = '<?php echo BASE_URL . "index.php"; ?>'; // ใช้ BASE_URL เพื่อเปลี่ยนหน้า
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
