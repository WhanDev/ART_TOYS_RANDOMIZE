<?php
    include("../../../CONFIG/Config.php");
?>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $content = @file_get_contents("php://input");
        $json_data = @json_decode($content, true);
        $f_name = trim($json_data["f_name"]);
        $l_name = trim($json_data["l_name"]);
        $email = trim($json_data["email"]);
        $tel = trim($json_data["tel"]);
        $address = trim($json_data["address"]);
        $user_password = trim($json_data["user_password"]);
        $user_role = "admin";
    } else { 
        echo json_encode(array("result" => 0, "messages" => "ISN'T POST METHOD"));
        exit;
    }
?>
<?php
    if ( empty($f_name) || empty($l_name) || empty($email) || empty($tel) || empty($address) || empty($user_password)) {
        echo json_encode(array("result" => 0, "messages" => "กรุณากรอกข้อมูลให้ครบถ้วน"));
        exit;
    } else {
        
        $strSQL = "SELECT * FROM art_user WHERE email ='" . @$email . "' ";
        $query = @mysqli_query($conn, $strSQL);

        if (@mysqli_num_rows($query) > 0) {
            echo json_encode(array("result" => 0, "messages" => "อีเมลล์นี้มีอยู่แล้ว"));
            exit;
        }else{
            $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO art_user ( f_name, l_name, email, tel, address, user_password, user_role) 
                    VALUES ('" . @$f_name . "', '" . @$l_name . "', '" . @$email . "', '" . @$tel . "', '" . @$address . "', '" . @$hashed_password . "', '" . @$user_role . "')";
            @mysqli_query($conn, $sql);

            $result = 1;
            $message = "เพิ่มผู้ใช้งานสําเร็จ";

            $strSQL = "SELECT * FROM art_user WHERE email ='" . @$email . "' ";
            $query = @mysqli_query($conn, $strSQL);
            $datalist = array();
            while ($resultObj = @mysqli_fetch_array($query)) {
                $datalist[] = array(
                    "f_name" => $resultObj["f_name"],
                    "l_name" => $resultObj['l_name'],
                    "email" => $resultObj['email'],
                    "tel" => $resultObj['tel'],
                    "address" => $resultObj['address'],
                    "user_password" => $resultObj['user_password'],
                    "user_role" => $resultObj['user_role']
                );
            };

            echo json_encode(array("result" => @$result, "messages" => @$message, "datalist" => @$datalist));
            mysqli_close($conn);
        }
    }
?>