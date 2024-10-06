<?php
    include("../../../CONFIG/Config.php");

    if ($_SERVER["REQUEST_METHOD"] == "GET") {  
            $type_id = trim($_GET['type_id']);
            $strSQL = "SELECT * FROM product_type WHERE type_id = '" . @$type_id . "'";
            $query = @mysqli_query($conn, $strSQL);
            
            if (@mysqli_num_rows($query) > 0) {
                $resultObj = @mysqli_fetch_array($query, MYSQLI_ASSOC);
                $datalist = array(
                    "type_id" => $resultObj['type_id'], 
                    "type_name" => $resultObj['type_name']
                );
                echo json_encode(array("result" => 1, "message" => "Success", "data" => $datalist));
            } else {
                echo json_encode(array("result" => 0, "message" => "ไม่พบข้อมูลประเภทสินค้า"));
            }
    } else {
        echo json_encode(array("result" => 0, "message" => "ISN'T GET METHOD"));
    }

    mysqli_close($conn);
?>
