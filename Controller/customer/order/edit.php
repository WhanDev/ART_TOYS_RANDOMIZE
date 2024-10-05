<?php
    include("../../../CONFIG/Config.php");
    // session_start();
    $_SESSION["user_id"] = 12;

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(array("result" => 0, "messages" => "กรุณาเข้าสู่ระบบ"));
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "PATCH") { 
        $content = @file_get_contents("php://input");
        $json_data = @json_decode($content, true);

        if (!isset($_GET['or_id']) || empty(trim($_GET['or_id']))) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบ or_id"));
            exit;
        }

        $or_id = isset($_GET['or_id']) ? trim($_GET['or_id']) : '';
        $user_id = isset($_SESSION["user_id"]) ? trim($_SESSION["user_id"]) : '';
        $or_status = "ชำระเงินเสร็จสิ้น";
        $cart = $json_data['cart'];
    } else { 
        echo json_encode(array("result" => 0, "messages" => "METHOD NOT CORRECT"));
        exit;
    }
?>

<?php
    $stmt = $conn->prepare("SELECT * FROM toy_order WHERE or_id = ? AND user_id = ? AND or_status = ?");
    $stmt->bind_param("iis", $or_id, $user_id, $or_status);
    $stmt->execute();
    $resultObj = $stmt->get_result();

    if ($resultObj->num_rows > 0) {
        echo json_encode(array("result" => 0, "messages" => "ไม่สามารถแก้ไขได้"));
        exit;
    } else {
        $stmt_delete = $conn->prepare("DELETE FROM toy_order_details WHERE or_id = ?");
        $stmt_delete->bind_param("i", $or_id);
        if ($stmt_delete->execute()) {
            foreach ($cart as $item) {
                $prod_id = $item['prod_id'];
                $ordt_amount = $item['ordt_amount'];
                $stmt_insert = $conn->prepare("INSERT INTO toy_order_details (or_id, prod_id, ordt_amount) VALUES (?, ?, ?)");
                $stmt_insert->bind_param("iii", $or_id, $prod_id, $ordt_amount);
                $stmt_insert->execute();
            }
            
            echo json_encode(array("result" => 1, "messages" => "แก้ไขรายการสินค้าสำเร็จ"));
        } else {
            echo json_encode(array("result" => 0, "messages" => "ไม่สามารถลบรายการสินค้าเดิมได้"));
        }

        $stmt_delete->close();
        $stmt_insert->close();
    }
    $stmt->close();
    mysqli_close($conn);
?>
