<?php
include("../../../CONFIG/Config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $prod_id = isset($_POST["prod_id"]) ? trim($_POST["prod_id"]) : '';
    $prod_name = isset($_POST["prod_name"]) ? trim($_POST["prod_name"]) : '';
    $prod_size = isset($_POST["prod_size"]) ? trim($_POST["prod_size"]) : '';
    $prod_amount = isset($_POST["prod_amount"]) ? trim($_POST["prod_amount"]) : '';
    $prod_price = isset($_POST["prod_price"]) ? trim($_POST["prod_price"]) : '';
    $type_id = isset($_POST["type_id"]) ? trim($_POST["type_id"]) : '';

    // ตรวจสอบว่ามีสินค้าในฐานข้อมูลหรือไม่
    $strSQL = "SELECT * FROM product WHERE prod_id = ?";
    $stmt = $conn->prepare($strSQL);
    $stmt->bind_param("i", $prod_id);
    $stmt->execute();
    $query = $stmt->get_result();

    if ($query->num_rows == 0) {
        echo json_encode(array("result" => 0, "messages" => "ไม่พบสินค้า"));
        exit;
    }

    // ตรวจสอบชื่อสินค้าซ้ำ
    $stmt = $conn->prepare("SELECT prod_name FROM product WHERE prod_name = ? AND prod_id != ?");
    $stmt->bind_param("si", $prod_name, $prod_id);
    $stmt->execute();
    $resultObj = $stmt->get_result();
    
    if ($resultObj->num_rows > 0) {
        echo json_encode(array("result" => 0, "messages" => "ชื่อสินค้าซ้ำ"));
        exit;
    }

    // ตรวจสอบว่าประเภทสินค้าถูกต้อง
    $type_check_query = "SELECT * FROM product_type WHERE type_id = ?";
    $type_stmt = $conn->prepare($type_check_query);
    $type_stmt->bind_param("i", $type_id);
    $type_stmt->execute();
    $type_result = $type_stmt->get_result();

    if ($type_result->num_rows == 0) {
        echo json_encode(array("result" => 0, "messages" => "ไม่พบประเภทสินค้าที่เลือก"));
        exit;
    }

    // อัปเดตข้อมูลสินค้า
    $sql_update = "UPDATE product SET prod_name = ?, prod_size = ?, prod_amount = ?, prod_price = ?, type_id = ? WHERE prod_id = ?";
    
    if (isset($_FILES["prod_img"]) && $_FILES["prod_img"]["error"] === UPLOAD_ERR_OK) {
        // จัดการไฟล์อัปโหลด
        $target_dir = "../../../Controller/admin/product/uploads/";
        $prod_img = basename($_FILES["prod_img"]["name"]);
        $target_file = $target_dir . $prod_img;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // ตรวจสอบว่าไฟล์เป็นภาพ
        $check = getimagesize($_FILES["prod_img"]["tmp_name"]);
        if ($check === false) {
            echo json_encode(array("result" => 0, "messages" => "ไฟล์ไม่ใช่รูปภาพ."));
            exit;
        }

        // ตรวจสอบขนาดไฟล์
        if ($_FILES["prod_img"]["size"] > 5000000) {
            echo json_encode(array("result" => 0, "messages" => "ไฟล์มีขนาดใหญ่เกินไป."));
            exit;
        }

        // ตรวจสอบประเภทไฟล์
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            echo json_encode(array("result" => 0, "messages" => "อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG & GIF เท่านั้น."));
            exit;
        }

        // อัปโหลดไฟล์
        if (!move_uploaded_file($_FILES["prod_img"]["tmp_name"], $target_file)) {
            echo json_encode(array("result" => 0, "messages" => "มีข้อผิดพลาดในการอัปโหลดไฟล์."));
            exit;
        }
        
        // อัปเดตคำสั่ง SQL รวมถึง prod_img
        $sql_update .= ", prod_img = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ssiisi", $prod_name, $prod_size, $prod_amount, $prod_price, $type_id, $prod_img, $prod_id);
    } else {
        // อัปเดตคำสั่ง SQL โดยไม่รวม prod_img
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ssiisi", $prod_name, $prod_size, $prod_amount, $prod_price, $type_id, $prod_id);
    }

    if ($stmt->execute()) {
        echo json_encode(array("result" => 1, "messages" => "แก้ไขข้อมูลสินค้าสำเร็จ"));
    } else {
        echo json_encode(array("result" => 0, "messages" => "ไม่สามารถแก้ไขข้อมูลได้: " . $stmt->error));
    }
    
    $stmt->close();
    mysqli_close($conn);
} else { 
    echo json_encode(array("result" => 0, "messages" => "METHOD NOT CORRECT"));
}
?>
