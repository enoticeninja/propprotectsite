<?php
Function GetUserList() {
global $db_conx;
$options = '<option></option>';
$sql = "SELECT username, firstname, lastname FROM users";
$query = mysqli_query($db_conx, $sql);
while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
$username = $row['username'];
$firstname = $row['firstname'];
$lastname = $row['lastname'];
$options .= '<option value="'.$username.'">'.$firstname.' '.$lastname.'</option>';
}
return $options;
}


function GetSupervisorList() {
global $db_conx;
$options = '';
$sql = "SELECT username, firstname, lastname FROM users WHERE designation='manager'";
$query = mysqli_query($db_conx, $sql);
while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
$username = $row['username'];
$firstname = $row['firstname'];
$lastname = $row['lastname'];
$options .= '<option value="'.$username.'">'.$firstname.' '.$lastname.'</option>';
}
return $options;
}

function FullName($username) {
global $db_conx;
$sql = "SELECT firstname, lastname FROM users WHERE username='$username'";
$query = mysqli_query($db_conx, $sql);
$row_table = mysqli_fetch_array($query, MYSQLI_ASSOC);
$fullname = $row_table['firstname']." ".$row_table['lastname'];
return $fullname;
}

 function GetOneFromDB($table,$field,$where) {
 global $db_conx;
 $sql = "SELECT `$field` FROM `$table` $where";
 $query = mysqli_query($db_conx, $sql);
 $row = mysqli_fetch_array($query);
 $fullname = $row[$field];
  return $fullname;
 }

function Run($sql){
global $db_conx; 
$query = mysqli_query($db_conx,$sql); 
return $query;
 }
 
 
function GetArrayFromDB($sql) {
global $db_conx;
$query = mysqli_query($db_conx,$sql);
$row = mysqli_fetch_all($query, MYSQLI_ASSOC);
return $row;
}

function RowExists($sql) {
global $db_conx;
$query = mysqli_query($db_conx,$sql);
$row = mysqli_num_rows($query);
return $row;
}

function GetInputField($input,$label,$divspan){
$code = '											<div class="span'.$divspan.' ">
														<div class="control-group">
															<label class="control-label">'.$label.'</label>
															<div class="controls">
																'.$input.'
															</div>
														</div>
													</div>
													';
echo $code;													

}
?>