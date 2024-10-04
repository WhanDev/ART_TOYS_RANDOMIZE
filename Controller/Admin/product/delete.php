<?php
    include("../../../CONFIG/Config.php");
    session_start();
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        if (!isset($_GET['prod_id']) || empty(trim($_GET['prod_id']))) {
            echo json_encode(array("result" => 0, "messages" => "ไม่พบ prod_id"));
            exit;
        }

        $prod_id = trim($_GET['prod_id']);
        $stmt = $conn->prepare("DELETE FROM product WHERE prod_id = $prod_id");
        $stmt->execute();
        if ($stmt->execute()) {
            echo json_encode(array("result" => 1, "messages" => "ลบสินค้าสำเร็จ"));
        } else {
            echo json_encode(array("result" => 0, "messages" => "ไม่สามารถลบสินค้าได้"));
        }
        $stmt->close();
        mysqli_close($conn);
    } else {
        echo json_encode(array("result" => 0, "messages" => "METHOD NOT CORRECT"));
        exit;
    }
?>