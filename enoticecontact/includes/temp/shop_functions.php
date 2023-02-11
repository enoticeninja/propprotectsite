<?php
function get_categories($key = ''){
	$arr = array();
	$arr['software'] = 'Software';
	$arr['antivirus'] = 'Antivirus';
	$arr['os'] = 'Operating System';
	$arr['misc'] = 'Miscellaneous';
	
	if($key == '')
	{
		$return = $arr;
	}
	else 
	{
		if(isset($arr[$key]))
		{
			$return = $arr[$key];
		}
		else
		{
			$return = false;
		}
	}
	
	return $return;
}

?>