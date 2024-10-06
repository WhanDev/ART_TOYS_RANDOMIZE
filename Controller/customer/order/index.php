<?php
    include("../../../CONFIG/Config.php");
    session_start();
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "GET") { 
        $user_id = $_SESSION["user_id"];  
        $stmt = $conn->prepare("SELECT * FROM toy_order WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
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

