<?php
$db_hostspec = "144.hosttech.eu";
$db_database = "usr_web116_5";
$db_username = "web116";
$db_password = XXXXXXXX

$conn = mysqli_connect($db_hostspec, $db_username, $db_password, $db_database);
if(!$conn) {
	die("Connection failed: " .mysqli_connect_error());
	
} else {
    $conn->set_charset("utf8");
}
?>
