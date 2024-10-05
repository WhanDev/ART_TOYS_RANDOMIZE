<?php
session_start();
if (isset($_SESSION['email'])) {
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
    <title>เข้าสู่ระบบ - ART TOYS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" />
    <script data-search-pseudo-elements defer
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php include BASE_DIR . 'layout/nav.php'; ?> <!-- Using BASE_DIR to include nav.php -->
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-3">
                    <div class="card-header justify-content-center">
                        <h3 class="text-center my-4">เข้าสู่ระบบ</h3>
                    </div>
                    <div class="card-body">
                        <form onsubmit="loginUser(event)">
                            <div class="form-group mb-3">
                                <label class="middle mb-1" for="inputEmailAddress">อีเมล์</label>
                                <input class="form-control py-2" id="email" name="email" type="email"
                                    placeholder="กรอกอีเมล์" required />
                            </div>
                            <div class="form-group">
                                <label class="middle mb-1" for="inputPassword">รหัสผ่าน</label>
                                <input class="form-control py-2" id="user_password" name="user_password" type="password"
                                    placeholder="กรอกรหัสผ่าน" required />
                            </div>
                            <div class="form-group d-flex align-items-center justify-content-center mt-4 mb-0">
                                <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <div class="small">
                            <a href="register.php">สมัครสมาชิก</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        function loginUser(event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);

            fetch('http://localhost/ART_TOYS_RANDOMIZE/Controller/login.php', {
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
                            window.location.href = 'index.php';
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
