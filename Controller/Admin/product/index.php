<?php
    include("../../../CONFIG/Config.php");
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "GET") {   
        $stmt = $conn->prepare("SELECT * FROM product");
        $stmt->execute();
        $resultObj = $stmt->get_result();
        if ($resultObj->num_rows == 0) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบสินค้า"));
            exit;
        }
        $row = $resultObj->fetch_all(MYSQLI_ASSOC);
        echo json_encode(array("result" => 1, "message" => "Success", "dataList" => $row));
    } else {
        echo json_encode(array("result" => 0, "messages" => "ISN'T GET METHOD"));
        exit;
    }
    $stmt->close();
    mysqli_close($conn);
?>

