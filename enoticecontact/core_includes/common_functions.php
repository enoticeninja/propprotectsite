<?php
function get_status_list($key=''){
	$arr['active'] = 'Active';
	$arr['pending'] = 'Pending';
	$arr['paused'] = 'Paused';
	$arr['deleted'] = 'Delete';	
	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;
}

function get_numeric_gender_list($key=''){
	$arr['1'] = 'Male';
	$arr['2'] = 'Female';
	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;
}

function get_numeric_status_list($key=''){
	$arr['1'] = 'Active';
	$arr['0'] = 'InActive';
	$arr['4'] = 'Deleted';	
	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;
}

function get_numeric_title_list(){
	$temp = array(
				'1' => 'Mr',
				'2' => 'Mrs',
				'3' => 'Ms',
				'4' => 'Dr'
			);

	return $temp;
}

function get_title_list(){
	$temp = array(
				'Mr' => 'Mr',
				'Mrs' => 'Mrs',
				'Ms' => 'Ms',
				'Dr' => 'Dr'
			);

	return $temp;
}

function date_only($mysql_date_time){
	$temp = date("Y-m-d", strtotime($mysql_date_time));	
	return $temp;
}

function get_enabled_disabled_list($key=''){
	$arr = array(
				'0' => 'Disabled',
				'1' => 'Enabled'
			);

	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;
}


function get_active_inactive_list($key=''){
	$arr = array(
				'1' => 'Active',
				'0' => 'InActive'
			);

	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;
}

function get_yes_no_list($key=''){
	$arr = array(
				'1' => 'Yes',
				'0' => 'No'
			);

	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;
}

function FullName($username) {
global $db_conx;
$sql = "SELECT firstname, lastname FROM users WHERE username='$username'";
$query = mysqli_query($db_conx, $sql);
$row_table = mysqli_fetch_array($query, MYSQLI_ASSOC);
$fullname = $row_table['firstname']." ".$row_table['lastname'];
return $fullname;
}

function getIpAddress(){
    $ip = getenv('HTTP_CLIENT_IP')?:
    getenv('HTTP_X_FORWARDED_FOR')?:
    getenv('HTTP_X_FORWARDED')?:
    getenv('HTTP_FORWARDED_FOR')?:
    getenv('HTTP_FORWARDED')?:
    getenv('REMOTE_ADDR');

return $ip;    
}

function getReadableDate($date){
  return date("F jS, Y -@- h:i:s a", strtotime($date));   
}
function get_readable_date($date){
  return date("F jS, Y -@- h:i:s a", strtotime($date));   
}

function DeleteDirectoryAndContents($path){
    if (is_dir($path) === true){
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file){
            if (in_array($file->getBasename(), array('.', '..')) !== true){
                if ($file->isDir() === true){
                    rmdir($file->getPathName());
                }

                else if (($file->isFile() === true) || ($file->isLink() === true)){
                    unlink($file->getPathname());
                }
            }
        }
        return rmdir($path);
    }

    else if ((is_file($path) === true) || (is_link($path) === true)){
        return unlink($path);
    }

    return false;
}

function file_extension($filename){
    $pos = strrpos($filename, '.');
    if($pos===false) {
        return false;
    } else {
        return substr($filename, $pos+1);
    }
}

function file_name($filename){
    $pos = strrpos($filename, '.');
    if($pos===false) {
        return false;
    } else {
        return substr($filename, 0, $pos);
    }
}

function img_src($path,$img){
	
}

function get_user_level_array($key=''){
$arr = array(
			'admin'=>'Administrator',	
			'manager'=>'Manager',	
			'user'=>'Employee'
			);
			
	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;
}


function getDefault(&$isset, $default) {
    return (isset($isset) && ($isset != '' || $isset != false)) ? $isset : $default;
}

function iskeyval(&$a, $k, $v) {
  return isset($a['key']) && ($a['key'] == 'value');
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function generateRandomNumber($length = 10) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>