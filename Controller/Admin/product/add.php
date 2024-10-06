<?php
include("../../../CONFIG/Config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prod_name = isset($_POST["prod_name"]) ? trim($_POST["prod_name"]) : "";
    $prod_size = isset($_POST["prod_size"]) ? trim($_POST["prod_size"]) : "";
    $prod_amount = isset($_POST["prod_amount"]) ? trim($_POST["prod_amount"]) : "";
    $prod_price = isset($_POST["prod_price"]) ? trim($_POST["prod_price"]) : "";
    $type_id = isset($_POST["type_id"]) ? trim($_POST["type_id"]) : "";

    if (empty($prod_name) || empty($prod_size) || empty($prod_amount) || empty($prod_price) || empty($type_id) || !isset($_FILES["prod_img"])) {
        echo json_encode(array(
            "result" => 0,
            "messages" => "ข้อมูลไม่ครบถ้วน",
            "data" => array(
                "prod_name" => $prod_name,
                "prod_size" => $prod_size,
                "prod_amount" => $prod_amount,
                "prod_price" => $prod_price,
                "type_id" => $type_id,
                "prod_img" => isset($_FILES["prod_img"]) ? $_FILES["prod_img"] : null 
            )
        ));  
        exit;
    }

    $type_check_query = "SELECT * FROM product_type WHERE type_id = ?";
    $stmt = $conn->prepare($type_check_query);
    $stmt->bind_param("i", $type_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(array("result" => 0, "messages" => "type_id ที่ระบุไม่มีอยู่ในระบบ"));
        exit;
    }

    $target_dir = "../../../Controller/admin/product/uploads/";
    $target_file = $target_dir . basename($_FILES["prod_img"]["name"]);
    $prod_img = basename($_FILES["prod_img"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($_FILES["prod_img"]["error"] !== UPLOAD_ERR_OK) {
        echo json_encode(array("result" => 0, "messages" => "มีข้อผิดพลาดในการอัปโหลดไฟล์."));
        exit;
    }

    $check = getimagesize($_FILES["prod_img"]["tmp_name"]);
    if ($check === false) {
        echo json_encode(array("result" => 0, "messages" => "ไฟล์ไม่ใช่รูปภาพ."));
        exit;
    }

    if ($_FILES["prod_img"]["size"] > 5000000) {
        echo json_encode(array("result" => 0, "messages" => "ไฟล์มีขนาดใหญ่เกินไป."));
        exit;
    }

    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        echo json_encode(array("result" => 0, "messages" => "อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG & GIF เท่านั้น."));
        exit;
    }

    if (move_uploaded_file($_FILES["prod_img"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO product (prod_name, prod_size, prod_amount, prod_price, prod_img, type_id) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissi", $prod_name, $prod_size, $prod_amount, $prod_price, $prod_img, $type_id);

        if ($stmt->execute()) {
            echo json_encode(array("result" => 1, "message" => "เพิ่มสินค้าสำเร็จ"));
        } else {
            echo json_encode(array("result" => 0, "messages" => "ไม่สามารถเพิ่มสินค้าได้: " . $stmt->error));
        }
        $stmt->close();
    } else {
        echo json_encode(array("result" => 0, "messages" => "มีข้อผิดพลาดในการอัปโหลดไฟล์."));
    }

    mysqli_close($conn);
} else {
    echo json_encode(array("result" => 0, "messages" => "METHOD NOT CORRECT"));
}
?>