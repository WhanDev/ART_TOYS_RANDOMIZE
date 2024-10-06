<?php
    include("../../../CONFIG/Config.php");
    session_start();
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "GET") {   
        $stmt = $conn->prepare("SELECT *,art_user.f_name,art_user.l_name FROM toy_order JOIN art_user ON toy_order.user_id = art_user.user_id");
        $stmt->execute();
        $resultObj = $stmt->get_result();
        if ($resultObj->num_rows == 0) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบสินค้า"));
            exit;
        }   
        $row = $resultObj->fetch_all(MYSQLI_ASSOC);
        echo json_encode(array("result" => 1, "message" => "Success", "dataList" => $row));
    } else {
        echo json_encode(array("result" => 0, "messages" => "METHOD NOT CORRECT"));
        exit;       
    }
    $stmt->close();
    mysqli_close($conn);
?>