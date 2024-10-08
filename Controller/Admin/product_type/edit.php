<?php
    include("../../../CONFIG/Config.php");
?>

<?php
    // if (!isset($_SESSION['email'])) {
    //     echo json_encode(array("result" => 0, "messages" => "กรุณาเข้าสู่ระบบ"));
    //     exit;
    // }
    
    if ($_SERVER["REQUEST_METHOD"] == "PATCH") { 
        $content = @file_get_contents("php://input");
        $json_data = @json_decode($content, true);

        if (!isset($_GET['type_id']) || empty(trim($_GET['type_id']))) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบ type_id"));
            exit;
        }
        
        $type_id = trim($_GET['type_id']);
        $type_name = isset($json_data["type_name"]) ? trim($json_data["type_name"]) : "";
    } else { 
        echo json_encode(array("result" => 0, "messages" => "ISN'T POST METHOD"));
        exit;
    }
?>

<?php
    if (empty($type_name)) {
        echo json_encode(array("result" => 0, "messages" => "กรุณากรอกข้อมูลให้ครบถ้วน"));
        exit;
    }

    // $role = $_SESSION['user_role'];
    // if ($role != "admin") {
    //     echo json_encode(array("result" => 0, "messages" => "แกไม่มีสิทธิ์"));
    //     exit;
    // }

    $strSQL = "SELECT * FROM product_type WHERE type_name ='" . @$type_name . "' ";
    $query = @mysqli_query($conn, $strSQL);

    if (@mysqli_num_rows($query) > 0) {
        echo json_encode(array("result" => 0, "messages" => "ประเภทสินค้านี้มีอยู่แล้ว"));
        exit;
    }
        $stmt = $conn->prepare("UPDATE product_type SET type_name = ? WHERE type_id = $type_id");
        $stmt->bind_param("s", $type_name);
        $stmt->execute();
        if ($stmt->execute()) {
            echo json_encode(array("result" => 1, "messages" => "แก้ไขประเภทสินค้าสำเร็จ"));
        } else {
            echo json_encode(array("result" => 0, "messages" => "ไม่สามารถแก้ไขประเภทสินค้าได้"));
        }
        $stmt->close();
        mysqli_close($conn);
   
?>