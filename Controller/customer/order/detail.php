<?php
    include("../../../CONFIG/Config.php");
    session_start();
?>

<?php
    
    if ($_SERVER["REQUEST_METHOD"] == "GET") { 
        $content = @file_get_contents("php://input");
        $json_data = @json_decode($content, true);
        if (!isset($_GET['or_id']) || empty(trim($_GET['or_id']))) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบ or_id"));
            exit;
        }
        $or_id = isset($_GET['or_id']) ? trim($_GET['or_id']) : '';
        $user_id = isset($_SESSION["user_id"]) ? trim($_SESSION["user_id"]) : '';
        
    } else { 
        echo json_encode(array("result" => 0, "messages" => "METHOD NOT CORRECT"));
        exit;
    }
?>

<?php
    $stmt = $conn->prepare(
        "SELECT toy_order.or_id, toy_order.or_date, toy_order.or_status, toy_order_details.prod_id, toy_order_details.ordt_amount, product.prod_name, product.prod_price , product.prod_img
        FROM toy_order 
        JOIN toy_order_details ON toy_order.or_id = toy_order_details.or_id
        JOIN product ON toy_order_details.prod_id = product.prod_id 
        WHERE toy_order.or_id = ? AND toy_order.user_id = ?"
    );
    $stmt->bind_param("ii", $or_id, $user_id);
    $stmt->execute();
    $resultObj = $stmt->get_result();

    if ($resultObj->num_rows == 0) {
        echo json_encode(array("result" => 0, "messages" => "ไม่พบสินค้าในคำสั่งซื้อนี้"));
        exit;
    }

    $row = $resultObj->fetch_all(MYSQLI_ASSOC);
    echo json_encode(array("result" => 1, "message" => "Success", "dataList" => $row));

    $stmt->close();
    mysqli_close($conn);
?>