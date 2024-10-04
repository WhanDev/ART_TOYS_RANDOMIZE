<?php
    include("../../../CONFIG/Config.php");
    // session_start();
    $_SESSION["user_id"] = 12;
?>

<?php
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(array("result" => 0, "messages" => "กรุณาเข้าสู่ระบบ"));
        exit;
    }
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") { 
        $content = @file_get_contents("php://input");
        $json_data = @json_decode($content, true);
        if (!isset($_GET['or_id']) || empty(trim($_GET['or_id']))) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบ or_id"));
            exit;
        }
        $or_id = isset($_GET['or_id']) ? trim($_GET['or_id']) : '';
        $user_id = isset($_SESSION["user_id"]) ? trim($_SESSION["user_id"]) : '';
        $or_status = "ชำระเงินเสร็จสิ้น";
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
        echo json_encode(array("result" => 0, "messages" => "ไม่สามารถลบได้: ไม่พบคำสั่งซื้อหรือสถานะไม่ถูกต้อง"));
        exit;
    }else{
        $stmt = $conn->prepare("DELETE FROM toy_order_details WHERE or_id = ?");
        $stmt->bind_param("i", $or_id);
        $stmt->execute();
        $stmt = $conn->prepare("DELETE FROM toy_order WHERE or_id = ?");
        $stmt->bind_param("i", $or_id);
        $stmt->execute();
        echo json_encode(array("result" => 1, "messages" => "ยกเลิกคำสั่งซื้อสำเร็จ"));
        $stmt->close();
        mysqli_close($conn);
    }
    
    
?>