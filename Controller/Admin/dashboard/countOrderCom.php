<?php 
    include '../../../config/config.php';
    $order_status = "ชำระเงินเสร็จสิ้น";
    $sql = "SELECT * FROM toy_order WHERE or_status = '$order_status'";
    $result = $conn->query($sql);
    $count = $result->num_rows;
    if ($count > 0) {
        echo json_encode(array("result" => 1, "message" => "Success", "data" => $count));
    } else {
        echo json_encode(array("result" => 0, "message" => "ไม่พบข้อมูล"));
    }
    $conn->close();
?>