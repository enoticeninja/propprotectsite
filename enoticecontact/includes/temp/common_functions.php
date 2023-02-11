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

function get_numeric_title_list($key=''){
	$arr = array(
		'0' => 'Mr',
		'1' => 'Mrs',
		'2' => 'Ms',
		'3' => 'Dr'
	);
	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
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
?>