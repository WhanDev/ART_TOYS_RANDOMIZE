<?php 
    include '../../../config/config.php';
    $user_status = "customer";
    $sql = "SELECT * FROM art_user";
    $result = $conn->query($sql);
    $count = $result->num_rows;
    if ($count > 0) {
        echo json_encode(array("result" => 1, "message" => "Success", "data" => $count));
    } else {
        echo json_encode(array("result" => 0, "message" => "ไม่พบข้อมูล"));
    }
    $conn->close();
?>