<?php
    include("../../../CONFIG/Config.php");
    session_start();
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $content = @file_get_contents("php://input");
        $json_data = @json_decode($content, true);
        $prod_id = isset($json_data["prod_id"]) ? trim($json_data["prod_id"]) : '';
        $prod_name = isset($json_data["prod_name"]) ? trim($json_data["prod_name"]) : '';
        $prod_size = isset($json_data["prod_size"]) ? trim($json_data["prod_size"]) : '';
        $prod_amount = isset($json_data["prod_amount"]) ? trim($json_data["prod_amount"]) : '';
        $prod_price = isset($json_data["prod_price"]) ? trim($json_data["prod_price"]) : '';
        $prod_img = isset($json_data["prod_img"]) ? trim($json_data["prod_img"]) : '';
        $type_id = isset($json_data["type_id"]) ? trim($json_data["type_id"]) : '';
    } else { 
        echo json_encode(array("result" => 0, "messages" => "ISN'T POST METHOD"));
        exit;
    }
?>

<?php
    if (empty($prod_name) || empty($prod_size) || empty($prod_amount) || empty($prod_price) || empty($prod_img) || empty($type_id)) {
        echo json_encode(array("result" => 0, "messages" => "ข้อมูลไม่ครบถ้วน"));
        exit;
    }

    $strSQL = "SELECT * FROM product WHERE prod_id = '" . @$prod_id . "'";
    $query = @mysqli_query($conn, $strSQL);

    if (@mysqli_num_rows($query) == 0) {
        echo json_encode(array("result" => 0, "messages" => "ไม่พบสินค้า"));
        exit;
    }else{
        $chk = "SELECT * FROM product WHERE prod_name = '" . @$prod_name . "'";
        $result1 = @mysqli_query($conn, $chk);

        if (@mysqli_num_rows($result1) > 0) {
            echo json_encode(array("result" => 0, "messages" => "มีสินค้าชื่อนี้แล้ว"));
            exit;
        }else{
            $sql = "UPDATE product SET prod_name = '" . @$prod_name . "', prod_size = '" . @$prod_size . "', prod_amount = '" . @$prod_amount . "', prod_price = '" . @$prod_price . "', prod_img = '" . @$prod_img . "', type_id = '" . @$type_id . "' WHERE prod_id = '" . @$prod_id . "'";
            @mysqli_query($conn, $sql);
            echo json_encode(array("result" => 1, "message" => "Success"));
            mysqli_close($conn);
        }
    }

    
?>