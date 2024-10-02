<?php
    include("../CONFIG/Config.php");
    session_start();
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $content = @file_get_contents("php://input");
        $json_data = @json_decode($content, true);
        $email = trim($json_data["email"]);    
        $user_password = trim($json_data["user_password"]);
    } else {
        echo json_encode(array("result" => 0, "messages" => "ISN'T POST METHOD"));
        exit;
    }
?>

<?php
    if (empty($email) || empty($user_password)) {
        echo json_encode(array("result" => 0, "messages" => "กรุณากรอกข้อมูลให้ครบถ้วน"));
        exit;
    }

    $strSQL = "SELECT * FROM art_user WHERE email ='" . @$email . "' ";
    $query = @mysqli_query($conn, $strSQL);

    if (@mysqli_num_rows($query) == 0) {
        echo json_encode(array("result" => 0, "messages" => "ไม่พบอีเมลล์นี้ในระบบ"));
        exit;
    } else {
        $result = mysqli_fetch_array($query);
        if (!password_verify($user_password, $result['user_password'])) {
            echo json_encode(array("result" => 0, "messages" => "รหัสผ่านไม่ถูกต้อง"));
            exit;
        } else {
            $_SESSION['email'] = $result['email'];
            $_SESSION['f_name'] = $result['f_name'];
            $_SESSION['user_role'] = $result['user_role'];

            $datalist = array(
                "f_name" => $result["f_name"],
                "l_name" => $result['l_name'],
                "email" => $result['email'],
                "tel" => $result['tel'],
                "address" => $result['address'],
                "user_role" => $result['user_role']
            );

            echo json_encode(array("result" => 1, "messages" => "เข้าสู่ระบบสําเร็จ", "datalist" => $datalist));
            exit;
        }
    }

    mysqli_close($conn);
?>
