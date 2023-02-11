<?php
/////CONTAINS FUNCTIONS WHICH ARE SPECIFIC TO USERLEVEL, DEPARTMENT 

///// TODO: implement userlevel and department check and present the list which pertains to the given department or userlevel.... eg when pulling up userlevel for manager, it should only show manager, srmanager and admin
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

function get_department_array(){
$level = array('tech'=>'Tech',	
			'sales'=>'Sales',	
			'hr'=>'HR',	
			'wfm'=>'WFM',	
			'quality'=>'Quality',
			'admin'=>'Admin',
			'accounts'=>'Accounts'
			);
return $level;
}

function get_designation_array(){
$level = array('executive'=>'Executive',	
			'srexecutive'=>'Senior Executive',	
			'manager'=>'Manager',	
			'srmanager'=>'Senior Manager'
			);
return $level;
}


function get_user_level_list($selected = '') {
	$options = '';
	foreach(get_user_level_array() as $level){

		if($level[0] == $selected) {
			$options .= '
						<option value="'.$level[0].'" selected>'.$level[1].'</option>
						';
		} else {
			$options .= '
						<option value="'.$level[0].'">'.$level[1].'</option>
						';
		}			
	}			
	return $options;
	}



function get_user_department_list($selected = '') {
	$options = '';
	foreach(get_department_array() as $dept){

		if($dept[0] == $selected) {
			$options .= '
						<option value="'.$dept[0].'" selected>'.$dept[1].'</option>
						';
		} else {
			$options .= '
						<option value="'.$dept[0].'">'.$dept[1].'</option>
						';
		}			
	}			
	return $options;
}



function get_user_level_title($userlevel) {

	switch($userlevel) {
		case "executive":
		return "Executive";
		break;

		case "fso":
		return "Floor Support";
		break;

		case "manager":
		return "Manager";
		break;

		case "srmanager":
		return "Senior Manager";
		break;

		case "admin":
		return "Administrator";
		break;
	}
}



function get_department_title($designation) {

	switch($designation) {
		case "tech":
		return "Tech";
		break;

		case "sales":
		return "Sales";
		break;

		case "hr":
		return "Human Resources";
		break;

		case "wfm":
		return "WFM";
		break;

		case "cs":
		return "Customer Service";
		break;

		case "quality":
		return "Quality";
		break;

		case "quality":
		return "Quality";
		break;

		case "admin":
		return "Admin";
		break;

		case "accounts":
		return "Accounts";
		break;
	}

}


function get_previlige_list($selected=''){
	$previliges = array(
					'edit' => 'Edit',
					'delete' => 'Delete',
					'read' => 'Read Only',
					'individual' => 'Individual',
					);
	$options = '<option></option>';				
	foreach($previliges as $key => $value) {				
		if ($key == $selected) {
			$options .= '<option value="'.$key.'" selected>'.$value.'</option>';
		} else {
			$options .= '<option value="'.$key.'">'.$value.'</option>';
		}
	}
	return $options;
}

?>