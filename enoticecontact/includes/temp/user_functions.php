<?php
////CONTAINS FUNCTIONS WHICH ARE DATABASE SPECIFIC

Function get_user_list() {
	global $db_conx;
	$options = '<option></option>';
	$sql = "SELECT id, firstname, lastname FROM users";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$id = $row['id'];
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$options .= '<option value="'.$id.'">'.$firstname.' '.$lastname.'</option>';
	}
	return $options;
}

Function get_user_list_by_id() {
	global $db_conx;
	$options = '<option></option>';
	$sql = "SELECT id, firstname, lastname FROM users";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$id = $row['id'];
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$options .= '<option value="'.$id.'">'.$firstname.' '.$lastname.'</option>';
	}
	return $options;
}

Function get_user_array() {
	global $db_conx;
	$return = array();
	$sql = "SELECT id, firstname, lastname FROM users";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$id = $row['id'];
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$return[$id] = ''.$firstname.' '.$lastname.'';
	}
	return $return;
}

Function get_user_array_by_id() {
	global $db_conx;
	$return = array();
	$sql = "SELECT id, firstname, lastname FROM users ";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$id = $row['id'];
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$return[$id] = ''.$firstname.' '.$lastname.'';
	}
	return $return;
}

function get_manager_array($selected='') {
	global $db_conx;
	$options = array();
	$sql = "SELECT a.username, a.firstname, a.lastname FROM users AS a
			INNER JOIN user_settings AS b
			ON a.username = b.username
			WHERE b.designation='manager' OR b.designation='srmanager' OR b.designation='admin'
			AND a.username != '$selected'";
	$query = mysqli_query($db_conx, $sql);
	$options['admin'] = 'Administrator';	
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$username = $row['username'];
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$options[$username] = ''.$firstname.' '.$lastname.'';
	}
	return $options;
}

function get_manager_list($selected='') {
	global $db_conx;
	$options = array();
	$sql = "SELECT a.username, a.firstname, a.lastname FROM users AS a
			INNER JOIN user_settings AS b
			ON a.username = b.username
			WHERE b.designation='manager' OR b.designation='srmanager' OR b.designation='admin'";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$username = $row['username'];
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$options[$username] = ''.$firstname.' '.$lastname.'';
	}
	return $options;
}

function get_full_name($username) {
	global $db_conx;
	$sql = "SELECT firstname, lastname FROM users WHERE username='$username'";
	$query = mysqli_query($db_conx, $sql);
	$row_table = mysqli_fetch_array($query, MYSQLI_ASSOC);
	$fullname = $row_table['firstname']." ".$row_table['lastname'];
	return $fullname;
}

function get_one_from_database($table,$field,$where) {
 global $db_conx;
 $sql = "SELECT `$field` FROM `$table` $where";
 $query = mysqli_query($db_conx, $sql);
 $row = mysqli_fetch_array($query);
 $fullname = $row[$field];
  return $fullname;
 }


?>