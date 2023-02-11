<?php
Class Database {
public $db_conx;

public function __construct(){
global $db_conx;
$this->db_conx = $db_conx;	
}

function InsertRow($table_name, $form_data, $date='')
{
// retrieve the keys of the array (column titles)
$fields = array_keys($form_data);
// build the query

$sql = "INSERT INTO ".$table_name."
		(`".implode('`,`', $fields)."`)
		VALUES('".implode("','", $form_data)."')";
// run and return the query result resource
	$return = array();
	$return['result'] = mysqli_query($this->db_conx, $sql);
	$return['id'] = mysqli_insert_id($this->db_conx);
	$return['sql'] = $sql;
	$return['error'] = mysqli_error($this->db_conx);
 return $return;
				
}


function UpdateRow($table_name, $form_data, $where_clause='')
{
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

	$return['query'] = mysqli_query($this->db_conx, $sql);
	$return['sql'] = $sql;
	$return['error'] = mysqli_error($this->db_conx);
	return $return;
}

function Run($sql){
$query = mysqli_query($this->db_conx,$sql); 
return $query;
 }
 
function GetSingleRowFromDB($sql) {
	$query = mysqli_query($this->db_conx,$sql);
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
	return $row;
}
 
function GetArrayFromDB($sql) {
	$query = mysqli_query($this->db_conx,$sql);
	$row = mysqli_fetch_all($query, MYSQLI_ASSOC);
	return $row;
}

function RowExists($sql) {
	$query = mysqli_query($this->db_conx,$sql);
	$row = mysqli_num_rows($query);
	return $row;
}


function FetchAll($sql){
	$query = mysqli_query($this->db_conx, $sql);
    $data = array();
    while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        $data[] = $row;
    }
	echo mysqli_error($this->db_conx);
	return $data;
}

function NumRows($sql){
	$query = mysqli_query($this->db_conx, $sql);
	$row = mysqli_num_rows($query);
	return $row;
}
}
?>