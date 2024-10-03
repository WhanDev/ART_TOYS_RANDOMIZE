<?php
    include("../../../CONFIG/Config.php");
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "GET") {   
        $strSQL = "SELECT * FROM art_user";
        $query = @mysqli_query($conn, $strSQL);
        $datalist = array();
        while($resultObj = @mysqli_fetch_array($query, MYSQLI_ASSOC) ){
            $datalist[] = array("user_id"=>$resultObj['user_id'], "f_name"=>$resultObj['f_name'], "l_name"=>$resultObj['l_name'], 
                            "email"=>$resultObj['email'], "tel"=>$resultObj['tel'], "address"=>$resultObj['address'],
                            "user_password"=>$resultObj['user_password'], "user_role"=>$resultObj['user_role']);
        }
        echo json_encode(array("result" => 1, "message" => "Success", "dataList" => $datalist));

    } else {
        echo json_encode(array("result" => 0, "messages" => "ISN'T GET METHOD"));
        exit;
    }

    mysqli_close($conn);
?>

