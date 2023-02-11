<?php
if (!function_exists('mysqli_fetch_all')) {
    function mysqli_fetch_all($query, $type) {
        $data = [];
        while ($row = mysqli_fetch_array($query,$type)) {
			$data[] = $row;
		}
        return $data;
    }
}
function getAssocArrayOne($table,$id,$field1,$where=''){
    global $db_conx;
    $where = $where != '' ? 'WHERE '.$where.'' : '';
    $sql = "SELECT $id, $field1 FROM $table $where";
    $query = mysqli_query($db_conx, $sql);
    $return = array();
    while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        $return[$row['id']] = $row[$field1];
    }
    return $return;
}
function getAssocArrayTwo($table,$id,$field1,$field2,$where=''){
    global $db_conx;
    $where = $where != '' ? 'WHERE '.$where.'' : '';
    $sql = "SELECT $id, $field1, $field2 FROM $table $where";
    $query = mysqli_query($db_conx, $sql);
    $return = array();
    while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        $return[$row['id']] = $row[$field1].' '.$row[$field2];
    }
    return $return;
}
function getAssocArrayThree($table,$id,$field1,$field2,$field3,$where=''){
    global $db_conx;
    $where = $where != '' ? 'WHERE '.$where.'' : '';
    $sql = "SELECT $id, $field1, $field2, $field3 FROM $table $where";
    $query = mysqli_query($db_conx, $sql);
    $return = array();
    while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        $return[$row['id']] = $row[$field1].' '.$row[$field2].' '.$row[$field3];
    }
    return $return;
}

function insertRow($table_name, $form_data, $date='')
{
    global $db_conx;
	// retrieve the keys of the array (column titles)
	$fields = array_keys($form_data);
	// build the query

	$sql = "INSERT INTO ".$table_name."
		(`".implode('`,`', $fields)."`)
		VALUES('".implode("','", $form_data)."')";
	// run and return the query result resource
	$return = array();
	$return['result'] = mysqli_query($db_conx, $sql);
	$return['id'] = mysqli_insert_id($db_conx);
	$return['sql'] = $sql;
	$return['error'] = mysqli_error($db_conx);
	return $return;
				
}


function updateRow($table_name, $form_data, $where_clause='')
{
    global $db_conx;
		// check for optional where clause
	$whereSQL = '';
	if(!empty($where_clause))
	{
		// check to see if the 'where' keyword exists
		if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
		{
			// not found, add key word
			$whereSQL = " WHERE ".$where_clause;
		} else
		{
			$whereSQL = " ".trim($where_clause);
		}
	}
	// start the actual SQL statement
	$sql = "UPDATE ".$table_name." SET ";
	// loop and build the column /
	$sets = array();
		foreach($form_data as $column => $value)
		{
		 $sets[] = "`".$column."` = '".$value."'";
		}
	$sql .= implode(', ', $sets);
	// append the where statement
	$sql .= $whereSQL;

	$return['query'] = mysqli_query($db_conx, $sql);
	$return['sql'] = $sql;
	$return['error'] = mysqli_error($db_conx);
	return $return;
}

function run($sql){
    global $db_conx;
	$query = mysqli_query($db_conx,$sql); 
	return $query;
 }
 
function getSingleRowFromDB($sql) {
    global $db_conx;
	$query = mysqli_query($db_conx,$sql);
	$return['row'] = mysqli_fetch_array($query, MYSQLI_ASSOC);
	$return['error'] = mysqli_error($db_conx);
	return $return;
}

 
/* function getArrayFromDB($sql) {
    global $db_conx;
	$query = mysqli_query($db_conx,$sql);
	$return['rows'] = array();
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
		$return['rows'][] = $row;
	}
	$return['error'] = mysqli_error($db_conx);
	return $return;
}  */
function getArrayFromDB($sql) {
    global $db_conx;
	$query = mysqli_query($db_conx,$sql);
	$return['rows'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
	$return['error'] = mysqli_error($db_conx);
	return $return;
}

function rowExists($sql){
    global $db_conx;
	$query = mysqli_query($db_conx,$sql);
	$return['row'] = mysqli_fetch_array($query, MYSQLI_ASSOC);
	$return['error'] = mysqli_error($db_conx);
	return $return;
}


function fetchAll($sql){
    global $db_conx;
	$query = mysqli_query($db_conx, $sql);
    $return['rows'] = array();
    while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        $return['rows'][] = $row;
    }
	$return['error'] = mysqli_error($db_conx);
	return $return;
}

function numRows($sql){
    global $db_conx;
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_num_rows($query);
	return $row;
}

function incrementProperty($table,$key,$value,$id){
	global $db_conx;
	$sql = "UPDATE `$table` SET `$key`= $key +'$value' WHERE id='$id'";
	$query = mysqli_query($db_conx, $sql);
	//print_r($sql);
	print_r(mysqli_error($db_conx));
	return $query;
}
?>