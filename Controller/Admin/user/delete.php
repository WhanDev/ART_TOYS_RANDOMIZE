<?php
    include("../../../CONFIG/Config.php");
    session_start();
?>

<?php
    // if (!isset($_SESSION['email'])) {
    //     echo json_encode(array("result" => 0, "messages" => "กรุณาเข้าสู่ระบบ"));
    //     exit;
    // }

    $content = @file_get_contents("php://input");
    $json_data = @json_decode($content, true);

    // $role = $_SESSION['user_role'];
    // if ($role != "admin") {
    //     echo json_encode(array("result" => 0, "messages" => "แกไม่มีสิทธิ์"));
    //     exit;
    // }

    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {   

        if (!isset($_GET['user_id']) || empty(trim($_GET['user_id']))) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบ user_id"));
            exit;
        }

        $user_id = trim($_GET['user_id']);
        $strSQL = "DELETE FROM art_user WHERE user_id = '" . @$user_id . "' ";
        $query = @mysqli_query($conn, $strSQL);
        echo json_encode(array("result" => 1, "messages" => "ลบข้อผู้ใช้งานสำเร็จ"));
        mysqli_close($conn);
    } else {
        echo json_encode(array("result" => 0, "messages" => "METHOD NOT CORRECT"));
        exit;
    }

    
?>