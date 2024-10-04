<?php
    include("../../../CONFIG/Config.php");
    session_start();
?>

<?php
    // if (!isset($_SESSION['email'])) {
    //     echo json_encode(array("result" => 0, "messages" => "กรุณาเข้าสู่ระบบ"));
    //     exit;
    // }

    if ($_SERVER["REQUEST_METHOD"] == "PATCH") {
        $content = @file_get_contents("php://input");
        $json_data = @json_decode($content, true);

        if (!isset($_GET['user_id']) || empty(trim($_GET['user_id']))) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบ user_id"));
            exit;
        }
        
        $user_id = trim($_GET['user_id']);
        $f_name = isset($json_data["f_name"]) ? trim($json_data["f_name"]) : '';
        $l_name = isset($json_data["l_name"]) ? trim($json_data["l_name"]) : '';
        $tel = isset($json_data["tel"]) ? trim($json_data["tel"]) : '';
        $address = isset($json_data["address"]) ? trim($json_data["address"]) : '';
        $user_role = isset($json_data["user_role"]) ? trim($json_data["user_role"]) : '';

        // $role = $_SESSION['user_role'];
        // if ($role != "admin") {
        //     echo json_encode(array("result" => 0, "messages" => "แกไม่มีสิทธิ์"));
        //     exit;
        // }

        // $email = $_SESSION['email'];
        $stmt = $conn->prepare("UPDATE art_user SET f_name = ?, l_name = ?, tel = ?, address = ?, user_role = ? WHERE user_id = $user_id");
        $stmt->bind_param("sssss", $f_name, $l_name, $tel, $address, $user_role);

        if ($stmt->execute()) {
            echo json_encode(array("result" => 1, "messages" => "แก้ไขข้อมูลส่วนตัวสำเร็จ"));
        } else {
            echo json_encode(array("result" => 0, "messages" => "ไม่สามารถแก้ไขข้อมูลได้"));
        }

        $stmt->close();
        mysqli_close($conn);
    } else {
        echo json_encode(array("result" => 0, "messages" => "METHOD NOT CORRECT"));
        exit;
    }
?>
