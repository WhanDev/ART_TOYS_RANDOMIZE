<?php
    include("../../../CONFIG/Config.php");
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $content = file_get_contents("php://input");
        $json_data = json_decode($content, true);

        $prod_name = isset($json_data["prod_name"]) ? trim($json_data["prod_name"]) : "";
        $prod_size = isset($json_data["prod_size"]) ? trim($json_data["prod_size"]) : "";
        $prod_amount = isset($json_data["prod_amount"]) ? trim($json_data["prod_amount"]) : "";
        $prod_price = isset($json_data["prod_price"]) ? trim($json_data["prod_price"]) : "";
        $type_id = isset($json_data["type_id"]) ? trim($json_data["type_id"]) : "";

        if (empty($prod_name) || empty($prod_size) || empty($prod_amount) || empty($prod_price) || empty($type_id)) {
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
        if (!isset($_FILES["prod_img"])) {
            echo json_encode(array("result" => 0, "messages" => "ไม่มีไฟล์ภาพสำหรับอัปโหลด."));
            exit;
        }

        $target_file = $target_dir . basename($_FILES["prod_img"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["prod_img"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo json_encode(array("result" => 0, "messages" => "ไฟล์ไม่ใช่รูปภาพ."));
            $uploadOk = 0;
        }

        if ($_FILES["prod_img"]["size"] > 5000000) {
            echo json_encode(array("result" => 0, "messages" => "ไฟล์มีขนาดใหญ่เกินไป."));
            $uploadOk = 0;
        }

        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            echo json_encode(array("result" => 0, "messages" => "อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG & GIF เท่านั้น."));
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo json_encode(array("result" => 0, "messages" => "ไม่สามารถอัปโหลดไฟล์ได้."));
        } else {
            if (move_uploaded_file($_FILES["prod_img"]["tmp_name"], $target_file)) {
                $sql = "INSERT INTO product (prod_name, prod_size, prod_amount, prod_price, prod_img, type_id) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssissi", $prod_name, $prod_size, $prod_amount, $prod_price, $target_file, $type_id);

                if ($stmt->execute()) {
                    echo json_encode(array("result" => 1, "message" => "เพิ่มสินค้าสำเร็จ"));
                } else {
                    echo json_encode(array("result" => 0, "messages" => "ไม่สามารถเพิ่มสินค้าได้"));
                }
                $stmt->close();
            } else {
                echo json_encode(array("result" => 0, "messages" => "มีข้อผิดพลาดในการอัปโหลดไฟล์."));
            }
        }

        mysqli_close($conn);
    } else {
        echo json_encode(array("result" => 0, "messages" => "METHOD NOT CORRECT"));
    }
?>
