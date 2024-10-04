<?php
    include("../../../CONFIG/Config.php");
    // session_start();
    $_SESSION["user_id"] = 9;
?>  

<?php
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
        $or_id = trim($_GET['or_id']);
    } else { 
        echo json_encode(array("result" => 0, "messages" => "METHOD NOT CORRECT"));
        exit;
    }
?>
<?php
        $stmt = $conn->prepare("UPDATE toy_order SET or_status = 'ชำระเงินเสร็จสิ้น' WHERE or_id = ?");
        $stmt->bind_param("i", $or_id);
        if ($stmt->execute()) {
            echo json_encode(array("result" => 1, "messages" => "ยืนยันชำระเงินสำเร็จ"));
        } else {
            echo json_encode(array("result" => 0, "messages" => "ไม่สามารถยืนยันชำระเงินได้"));
        }
        $stmt->close();
        mysqli_close($conn);
    
?>