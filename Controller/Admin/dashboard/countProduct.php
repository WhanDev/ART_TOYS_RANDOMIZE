<?php 
    include '../../../config/config.php';
    $sql = "SELECT * FROM product";
    $result = $conn->query($sql);
    $count = $result->num_rows;
    if ($count > 0) {
        echo json_encode(array("result" => 1, "message" => "Success", "data" => $count));
    } else {
        echo json_encode(array("result" => 0, "message" => "ไม่พบข้อมูล"));
    }
    $conn->close();
?>