<?php

function get_full_name($username) {
	global $db_conx;
	$sql = "SELECT firstname, lastname FROM affiliate_users WHERE username='$username'";
	$query = mysqli_query($db_conx, $sql);
	$row_table = mysqli_fetch_array($query, MYSQLI_ASSOC);
	$fullname = $row_table['firstname']." ".$row_table['lastname'];
	return $fullname;
}


?>