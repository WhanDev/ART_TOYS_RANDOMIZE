<?php
    include("../../../CONFIG/Config.php");
    session_start();
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] ==  "GET") {
        if (!isset($_GET['or_id']) || empty(trim($_GET['or_id']))) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบ or_id"));
            exit;
        }
    
        $or_id = trim($_GET['or_id']);
        $stmt = $conn->prepare("SELECT toy_order.or_id, toy_order.or_date, toy_order.or_status, toy_order_details.prod_id, 
                                toy_order_details.ordt_amount, product.prod_name, product.prod_price , product.prod_img
                                FROM toy_order 
                                JOIN toy_order_details ON toy_order.or_id = toy_order_details.or_id
                                JOIN product ON toy_order_details.prod_id = product.prod_id 
                                WHERE toy_order.or_id = ?");
        $stmt->bind_param("i", $or_id);
        $stmt->execute();
        $resultObj = $stmt->get_result();
    
        if ($resultObj->num_rows == 0) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบสินค้าในคำสั่งซื้อนี้"));
            exit;
        }
    
        $orderItems = [];
        while ($row = $resultObj->fetch_assoc()) {
            $orderItems[] = $row;
        }
    
        echo json_encode(array("result" => 1, "messages" => "Success", "dataList" => $orderItems));
        $stmt->close();
        mysqli_close($conn);
    }
    
?>