<?php
    include("../../../CONFIG/Config.php");
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $content = @file_get_contents("php://input");
        $json_data = @json_decode($content, true);
        $type_name = trim($json_data["type_name"]);
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
    $strSQL = "INSERT INTO product_type (type_name) VALUES ('" . @$type_name . "')";
    $query =  @mysqli_query($conn, $strSQL);
    echo json_encode(array("result" => 1, "messages" => "เพิ่มประเภทสินค้าสําเร็จ"));
    mysqli_close($conn);
?>