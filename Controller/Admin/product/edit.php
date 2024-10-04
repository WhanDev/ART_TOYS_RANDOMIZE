<?php
    include("../../../CONFIG/Config.php");
    session_start();
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "PATCH") { 
        $content = @file_get_contents("php://input");
        $json_data = @json_decode($content, true);
        
        if (!isset($_GET['prod_id']) || empty(trim($_GET['prod_id']))) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบ prod_id"));
            exit;
        }

        $prod_id = trim($_GET['prod_id']);
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

    $strSQL = "SELECT * FROM product WHERE prod_id = '" . @$prod_id . "'";
    $query = @mysqli_query($conn, $strSQL);

    if (@mysqli_num_rows($query) == 0) {
        echo json_encode(array("result" => 0, "messages" => "ไม่พบสินค้า"));
        exit;
    }else{

        $stmt = $conn->prepare("SELECT prod_name FROM product WHERE prod_name = ? AND prod_id != ?");
        $stmt->bind_param("si", $prod_name, $prod_id);
        $stmt->execute();
        $resultObj = $stmt->get_result();
        $row = $resultObj->fetch_assoc();
        if ($resultObj->num_rows > 0) {
            echo json_encode(array("result" => 0, "messages" => "ชื่อสินค้าซ้ำ", "dataList" => $row));
            exit;
        } else {
            $stmt = $conn->prepare("UPDATE product SET prod_name = ?, prod_size = ?, prod_amount = ?, prod_price = ?, prod_img = ?, type_id = ? WHERE prod_id = $prod_id");
            $stmt->bind_param("siidsi", $prod_name, $prod_size, $prod_amount, $prod_price, $prod_img, $type_id);
            if ($stmt->execute()) {
                echo json_encode(array("result" => 1, "messages" => "แก้ไขข้อมูลส่วนตัวสำเร็จ"));
            } else {
                echo json_encode(array("result" => 0, "messages" => "ไม่สามารถแก้ไขข้อมูลได้"));
            }
            $stmt->close(); 
            mysqli_close($conn);
        }
    }
?>
