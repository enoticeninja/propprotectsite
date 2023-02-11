<?php
define('CASE_REFERENCE_STRING', 'CSR');
define('CASE_REFERENCE_DIGITS', 1000);
define('CUSTOMER_REFERENCE_STRING', 'ATC');
define('CUSTOMER__REFERENCE_DIGITS', 1000);

function get_os_types($key=''){
	$arr['1'] = 'Laptop';
	$arr['2'] = 'Desktop';
	$arr['3'] = 'Notebook';
	$arr['4'] = 'Mobile';
	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;
}
function get_case_status_list($key=''){
	$arr['1'] = 'Resolved';
	$arr['2'] = 'Not Resolved';
	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;
}

function get_case_teansfer_reasons_list($key=''){
	$arr['1'] = 'After Sales';
	$arr['2'] = 'Escalated';
	$arr['3'] = 'End Of Shift';
	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;
}

?>