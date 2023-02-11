<?php 

$servername = "localhost";
$username = "root";
$password = "Nano4545$";
$username = "root";
$password = "";
$db ="lawyer_app"; 
/*
$servername = "localhost";
$username = "root";
$password = "";
$db ="lawyer_app"; */

// Create connection
$conn = new mysqli($servername, $username, $password , $db );


// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}




?>
