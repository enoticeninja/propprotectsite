<?php
// Put your specific mysql database connection data below
define('DB_SERVERNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'lawyer_app');
$db_conx = null;
/* $db_conx = mysqli_connect(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
$CONNECT = $db_conx;
// Evaluate the connection
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
    exit();
} */

function randStrGen($len){
	$result = "";
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789$$$$$$$1111111";
    $charArray = str_split($chars);
    for($i = 0; $i < $len; $i++){
	    $randItem = array_rand($charArray);
	    $result .= "".$charArray[$randItem];
    }
    return $result;
}
?>