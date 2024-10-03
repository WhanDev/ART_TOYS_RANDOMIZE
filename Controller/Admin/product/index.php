<?php
    include("../../../CONFIG/Config.php");
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "GET") {   
        $strSQL = "SELECT * FROM product";
        $query = @mysqli_query($conn, $strSQL);
        $datalist = array();
        while($resultObj = @mysqli_fetch_array($query, MYSQLI_ASSOC) ){
            $datalist[] = array("prod_id"=>$resultObj['prod_id'], "prod_name"=>$resultObj['prod_name']);
        }
        echo json_encode(array("result" => 1, "message" => "Success", "dataList" => $datalist));

    } else {
        echo json_encode(array("result" => 0, "messages" => "ISN'T GET METHOD"));
        exit;
    }

    mysqli_close($conn);
?>

