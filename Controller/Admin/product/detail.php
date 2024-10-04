<?php
    include("../../../CONFIG/Config.php");
    session_start();
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] ==  "GET"){
        if (!isset($_GET['prod_id']) || empty(trim($_GET['prod_id']))) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบ prod_id"));
            exit;
        }
        $prod_id = trim($_GET['prod_id']);
        $stmt = $conn->prepare("SELECT * FROM product WHERE prod_id = ?");
        $stmt->bind_param("i", $prod_id);
        $stmt->execute();
        $resultObj = $stmt->get_result();
        $row = $resultObj->fetch_assoc();
        if ($resultObj->num_rows == 0) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบสินค้า"));
            exit;
        }
        echo json_encode(array("result" => 1, "messages" => "Success", "dataList" => $row));
        $stmt->close();
        mysqli_close($conn);
    }
?>