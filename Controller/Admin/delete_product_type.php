<?php
    include("../../CONFIG/Config.php");
    session_start();
?>

<?php
    if (!isset($_SESSION['email'])) {
        echo json_encode(array("result" => 0, "messages" => "กรุณาเข้าสู่ระบบ"));
        exit;
    }
?>