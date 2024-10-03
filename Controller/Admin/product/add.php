<?php
    include("../../../CONFIG/Config.php");
    session_start();
?>

<?php
    // if (!isset($_SESSION['email'])) {
    //     echo json_encode(array("result" => 0, "messages" => "กรุณาเข้าสู่ระบบ"));
    //     exit;
    // }

    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $content = @file_get_contents("php://input");
        $json_data = @json_decode($content, true);
        $prod_name = trim($json_data["prod_name"]);
        $prod_size = trim($json_data["prod_size"]);
        $prod_amount = trim($json_data["prod_amount"]);
        $prod_price = trim($json_data["prod_price"]);
        $prod_img = trim($json_data["prod_img"]);
        $type_id = trim($json_data["type_id"]);
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

    $chk = "SELECT * FROM product WHERE prod_name = '" . @$prod_name . "'";
    $result1 = @mysqli_query($conn, $chk);

    if (@mysqli_num_rows($result1) > 0) {
        echo json_encode(array("result" => 0, "messages" => "มีสินค้าชื่อนี้แล้ว"));
        exit;
    }else{
        $sql = "INSERT INTO product ( prod_name, prod_size, prod_amount, prod_price, prod_img, type_id) 
                VALUES ('" . @$prod_name . "', '" . @$prod_size . "', '" . @$prod_amount . "', '" . @$prod_price . "', '" . @$prod_img . "', '" . @$type_id . "')";
        @mysqli_query($conn, $sql);

        $strSQL = "SELECT * FROM product WHERE prod_name = '" . @$prod_name . "'";
        $query = @mysqli_query($conn, $strSQL);

        $datalist = array();
        while($resultObj = @mysqli_fetch_array($query, MYSQLI_ASSOC) ){
            $datalist[] = array("prod_id"=>$resultObj['prod_id'], "prod_name"=>$resultObj['prod_name'], "prod_size"=>$resultObj['prod_size'], 
                            "prod_amount"=>$resultObj['prod_amount'], "prod_price"=>$resultObj['prod_price'], "prod_img"=>$resultObj['prod_img'],
                            "type_id"=>$resultObj['type_id']);
        }
        echo json_encode(array("result" => 1, "message" => "Success", "dataList" => $datalist));
        mysqli_close($conn);
    }
?>