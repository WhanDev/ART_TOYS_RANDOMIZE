<?php
session_start();
if (isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>ลงทะเบียน - ART TOYS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" />
    <script data-search-pseudo-elements defer
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous">
    </script>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header justify-content-center">
                        <h3 class="text-center my-4">ลงทะเบียน</h3>
                    </div>
                    <div class="card-body">
                        <form onsubmit="registerUser(event)">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="f_name" class="form-label">ชื่อ</label>
                                    <input type="text" class="form-control" id="f_name" name="f_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="l_name" class="form-label">นามสกุล</label>
                                    <input type="text" class="form-control" id="l_name" name="l_name" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">อีเมล์</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="user_password" class="form-label">รหัสผ่าน</label>
                                <input type="password" class="form-control" id="user_password" name="user_password"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="conf_user_password" class="form-label">ยืนยันรหัสผ่าน</label>
                                <input type="password" class="form-control" id="conf_user_password"
                                    name="conf_user_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="tel" class="form-label">เบอร์โทร</label>
                                <input type="tel" class="form-control" id="tel" name="tel" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">ที่อยู่</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            </div>
                            <div class="form-group d-flex align-items-center justify-content-center mt-4 mb-0">
                                <button type="submit" class="btn btn-primary w-100">ลงทะเบียน</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <div class="small">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="index.php">ไปหน้าแรก</a>
                                </div>
                                <div class="col-md-6">
                                    <a href="login.php">เข้าสู่ระบบ</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
    function registerUser(event) {
        event.preventDefault();

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData);

        fetch('http://localhost/ART_TOYS_RANDOMIZE/Controller/customer/register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.result === 1) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: data.messages,
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                } else {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: data.messages,
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                }
            })
            .catch(error => {
                console.error('เกิดข้อผิดพลาด:', error);
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            });
    }
    </script>
</body>

</html>