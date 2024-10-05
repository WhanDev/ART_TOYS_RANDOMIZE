<?php
session_start();
?>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>


<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="index.php">หน้าแรก</a>
    </li>
    <?php if (!isset($_SESSION['email'])) { ?>
    <li class="nav-item">
        <a class="nav-link" href="register.php">ลงทะเบียน</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="login.php">เข้าสู่ระบบ</a>
    </li>
    <?php } else { ?>
    <li class="nav-item">
        <a class="nav-link" href="javascript:void(0);" onClick="confirmLogout()">
            <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
        </a>
    </li>
    <?php } ?>
</ul>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
function confirmLogout() {
    Swal.fire({
        title: 'ยืนยันการออกจากระบบ?',
        text: "คุณแน่ใจหรือไม่ว่าต้องการออกจากระบบ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ใช่, ต้องการออกจากระบบ',
        cancelButtonText: 'ไม่, ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'http://localhost/ART_TOYS_RANDOMIZE/Controller/logout.php';
        }
    });
}
</script>