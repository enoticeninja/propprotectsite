<?php
// Put your specific mysql database connection data below
    $db_servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "magicalmessi";
$db_conx = mysqli_connect($db_servername, $db_username, $db_password, $db_name);
// Evaluate the connection
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
    exit();
}

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