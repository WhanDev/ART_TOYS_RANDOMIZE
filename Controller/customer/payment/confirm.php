<?php
    include("../../../CONFIG/Config.php");
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $content = @file_get_contents("php://input");
        $json_data = @json_decode($content, true);

        if (!isset($_GET['or_id']) || empty(trim($_GET['or_id']))) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบ or_id"));
            exit;
        }
        $or_id = $_GET["or_id"];
        $pamt_amount = isset($_POST["pamt_amount"]) ? trim($_POST["pamt_amount"]) : "" ;
        $pamt_net = isset($_POST["pamt_net"]) ? trim($_POST["pamt_net"]) : "" ;
        $or_status = "ชำระเงินเสร็จสิ้น";

        if ($pamt_amount === null ||  $pamt_net === null || !isset($_FILES["pamt_img"])) {
            echo json_encode(array(
                "result" => 0, 
                "messages" => "ข้อมูลไม่ครบถ้วน", 
                "data" => array(
                    "pamt_amount" => $pamt_amount,
                    "pamt_net" => $pamt_net,
                    "pamt_img" => isset($_FILES["pamt_img"]) ? $_FILES["pamt_img"] : null
                )
            ));
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM toy_order WHERE or_id = ? AND user_id = ? AND or_status = ?");
        $stmt->bind_param("iis", $or_id, $_SESSION["user_id"], $or_status);
        $stmt->execute();
        $resultObj = $stmt->get_result();

        if ($resultObj->num_rows > 0) {
            echo json_encode(array("result" => 0, "messages" => "ไม่สามารถยืนยันได้ หรือสิ้นสุดคำสั่งซื้อไปแล้ว"));
            exit;
        }

        $target_dir = "../../../Controller/customer/payment/uploads/"; 
        $target_file = $target_dir . basename($_FILES["pamt_img"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["pamt_img"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo json_encode(array("result" => 0, "messages" => "ไฟล์ไม่ใช่รูปภาพ."));
            $uploadOk = 0;
        }

        if ($_FILES["pamt_img"]["size"] > 5000000) {
            echo json_encode(array("result" => 0, "messages" => "ไฟล์มีขนาดใหญ่เกินไป."));
            $uploadOk = 0;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo json_encode(array("result" => 0, "messages" => "อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG & GIF เท่านั้น."));
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo json_encode(array("result" => 0, "messages" => "ไม่สามารถอัพโหลดไฟล์ได้"));
            exit;
        } else {
            if (move_uploaded_file($_FILES["pamt_img"]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO payment(pamt_amount, pamt_discount, pamt_net, pamt_img, or_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ddisi", $pamt_amount, $pamt_discount, $pamt_net, $target_file, $or_id);
                if ($stmt->execute()) {
                    $stmt_details = $conn->prepare("SELECT prod_id, ordt_amount FROM toy_order_details WHERE or_id = ?");
                    $stmt_details->bind_param("i", $or_id);
                    $stmt_details->execute();
                    $detailsResult = $stmt_details->get_result();
                    $dataList = $detailsResult->fetch_all(MYSQLI_ASSOC);
        
                    foreach ($dataList as $item) {
                        $prod_id = $item['prod_id'];
                        $ordt_amount = $item['ordt_amount'];
        
                        $stmt_update = $conn->prepare("UPDATE product SET prod_amount = prod_amount - ? WHERE prod_id = ?");
                        $stmt_update->bind_param("ii", $ordt_amount, $prod_id);
                        $stmt_update->execute();
                        $stmt_update->close();
                    }
        
                    echo json_encode(array("result" => 1, "message" => "ชำระเงินสำเร็จ และปรับจำนวนสินค้าเรียบร้อย", "dataList" => $dataList));
                } else {
                    echo json_encode(array("result" => 0, "messages" => "ไม่สามารถบันทึกการชำระเงินได้"));
                }
        
                $stmt_details->close();
                $stmt->close();
            } else {
                echo json_encode(array("result" => 0, "messages" => "ไม่สามารถอัพโหลดไฟล์ได้"));
            }
        }
    } else {
        echo json_encode(array("result" => 0, "messages" => "METHOD NOT CORRECT"));
        exit;
    }

?>
