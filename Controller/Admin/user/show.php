<?php
    include("../../../CONFIG/Config.php");

    if ($_SERVER["REQUEST_METHOD"] == "GET") {  
            $user_id = trim($_GET['user_id']);
            $strSQL = "SELECT * FROM art_user WHERE user_id = '" . @$user_id . "'";
            $query = @mysqli_query($conn, $strSQL);
            
            if (@mysqli_num_rows($query) > 0) {
                $resultObj = @mysqli_fetch_array($query, MYSQLI_ASSOC);
                $datalist = array(
                    "user_id" => $resultObj['user_id'], 
                    "f_name" => $resultObj['f_name'], 
                    "l_name" => $resultObj['l_name'], 
                    "email" => $resultObj['email'], 
                    "tel" => $resultObj['tel'], 
                    "address" => $resultObj['address'], 
                    "user_password" => $resultObj['user_password'], 
                    "user_role" => $resultObj['user_role']
                );
                echo json_encode(array("result" => 1, "message" => "Success", "data" => $datalist));
            } else {
                echo json_encode(array("result" => 0, "message" => "ไม่พบข้อมูลผู้ใช้ที่ระบบ"));
            }
    } else {
        echo json_encode(array("result" => 0, "message" => "ISN'T GET METHOD"));
    }

    mysqli_close($conn);
?>
