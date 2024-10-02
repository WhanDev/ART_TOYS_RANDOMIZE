<?php
     @header('Content-Type: application/json');
     @header("Access-Control-Allow-Origin: *");
     @header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
?>

<?php
$serName = "localhost";
$userNameDB = "root";
$userPasswordDB = "";
$dbName = "db_art_toy";
$port = "3307"
?>
<?php
@date_default_timezone_set("Asia/Bangkok");
$conn = @mysqli_connect($serName, $userNameDB, $userPasswordDB, $dbName, $port);
@mysqli_set_charset($conn, "utf8");
?>
<?php
if ($conn->connect_error) {
    print_r("error connect");
    print_r($conn->connect_error);
} else {
    // print_r("good connect");
}
?>
