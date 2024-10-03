<?php
    include("../../../CONFIG/Config.php");
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "GET") {   
        $strSQL = "SELECT * FROM product_type";
        $query = @mysqli_query($conn, $strSQL);
        $datalist = array();
        while($resultObj = @mysqli_fetch_array($query, MYSQLI_ASSOC) ){
            $datalist[] = array("type_id"=>$resultObj['type_id'], "type_name"=>$resultObj['type_name']);
        }
        echo json_encode(array("result" => 1, "message" => "Success", "dataList" => $datalist));

    } else {
        echo json_encode(array("result" => 0, "messages" => "ISN'T GET METHOD"));
        exit;
    }

    mysqli_close($conn);
?>

