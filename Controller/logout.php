<?php
session_start();

if (isset($_SESSION['email'])) {
    session_unset();
    session_destroy();
}

echo json_encode(array("result" => 1));
exit;
?>