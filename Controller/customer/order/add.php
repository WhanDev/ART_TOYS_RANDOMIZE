<?php
include("../../../CONFIG/Config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = @file_get_contents("php://input");
    $json_data = @json_decode($content, true);

    if (isset($json_data['cart']) && is_array($json_data['cart'])) {
        $cart = $json_data['cart'];
    } else {
        echo json_encode(array("result" => 0, "messages" => "Invalid cart data"));
        exit;
    }

    $or_status = "รอชำระเงิน";
    $user_id = isset($_SESSION["user_id"]) ? trim($_SESSION["user_id"]) : '';
} else {
    echo json_encode(array("result" => 0, "messages" => "ISN'T POST METHOD"));
    exit;
}

$date = date("Y-m-d");
$stmt = $conn->prepare("INSERT INTO toy_order(or_date, or_status, user_id) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $date, $or_status, $user_id);

if ($stmt->execute()) {
    $or_id = $conn->insert_id;
    foreach ($cart as $item) {
        $stmt_detail = $conn->prepare("INSERT INTO toy_order_details(ordt_amount, prod_id, or_id) VALUES (?, ?, ?)");
        $stmt_detail->bind_param("iii", $item['ordt_amount'], $item['prod_id'], $or_id);

        if (!$stmt_detail->execute()) {
            echo json_encode(array("result" => 0, "messages" => "Failed to insert order details: " . $stmt_detail->error));
            exit;
        }

        $stmt_detail->close();
    }

    echo json_encode(array("result" => 1, "messages" => "สั่งซื้อสำเร็จ", "or_id" => $or_id));
} else {
    echo json_encode(array("result" => 0, "messages" => "คำสั่งซื้อไม่สำเร็จ: " . $stmt->error));
}

$stmt->close();
mysqli_close($conn);
?>