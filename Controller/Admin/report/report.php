<?php
session_start();
include("../../../CONFIG/Config.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("result" => 0, "messages" => "กรุณาเข้าสู่ระบบ"));
    exit;
}

header('Content-Type: application/json');

if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = intval($_GET['month']);
    $year = intval($_GET['year']);

    $stmt = $conn->prepare("
        SELECT 
            product.prod_name, 
            SUM(toy_order_details.ordt_amount) AS total_sold, 
            SUM(toy_order_details.ordt_amount * product.prod_price) AS total_revenue
        FROM toy_order 
        JOIN toy_order_details ON toy_order.or_id = toy_order_details.or_id
        JOIN product ON toy_order_details.prod_id = product.prod_id
        WHERE MONTH(toy_order.or_date) = ? AND YEAR(toy_order.or_date) = ?
        GROUP BY product.prod_name
    ");
    $stmt->bind_param("ii", $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $sales_data = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();

    if (!empty($sales_data)) {
        echo json_encode(array("result" => 1, "data" => $sales_data));
    } else {
        echo json_encode(array("result" => 0, "messages" => "ไม่พบข้อมูลยอดขายสำหรับเดือนและปีที่เลือก"));
    }
} else {
    echo json_encode(array("result" => 0, "messages" => "กรุณาเลือกเดือนและปี"));
}
?>
