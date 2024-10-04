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

        if (!isset($_GET['type_id']) || empty(trim($_GET['type_id']))) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบ type_id"));
            exit;
        }
        $type_id = trim($_GET['type_id']);
        $sql = "SELECT * FROM product_type WHERE type_id = '" . @$type_id . "' ";
        $result = @mysqli_query($conn, $sql);
        $row = @mysqli_fetch_array($result);
        if (@mysqli_num_rows($result) == 0) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบประเภทสินค้า"));
            exit;
        }

        $sql2 = "SELECT * FROM product WHERE type_id = '" . @$type_id . "' ";
        $result2 = @mysqli_query($conn, $sql2);
        if (@mysqli_num_rows($result2) > 0) {
            echo json_encode(array("result" => 0, "messages" => "มีสินค้าในประเภทนี้ ไม่สามารถลบได้"));
            exit;
        }else{
            $strSQL = "DELETE FROM product_type WHERE type_id = '" . @$type_id . "' ";
            $query = @mysqli_query($conn, $strSQL);
            echo json_encode(array("result" => 1, "messages" => "ลบประเภทสินค้าสำเร็จ"));
            mysqli_close($conn);
        }
        
    } else {
        echo json_encode(array("result" => 0, "messages" => "ISN'T POST METHOD"));
        exit;
    }
?>