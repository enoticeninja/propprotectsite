<?php
function getPackages($key = null)
{
	$packages = array(
		'SP_Y01' => array('name' => 'Daily View + Unlimited Property Search', 'months' => '12', 'price' => '1599', 'min' => '1', 'max' => '1', 'image' => 'pricing-v1.png'),
		'SP_Q01' => array('name' => 'Daily View + Unlimited Property Search', 'months' => '3', 'price' => '499', 'min' => '1', 'max' => '1', 'image' => 'pricing-v1.png'),
		'AP_P01' => array('name' => 'Set Alerts for up to 5 properties', 'months' => '12', 'price' => '499', 'min' => '1', 'max' => '5', 'image' => 'pricing-v2.png'),
		'AP_P02' => array('name' => 'Set Alerts for up to 9 properties', 'months' => '12', 'price' => '399', 'min' => '6', 'max' => '9', 'image' => 'pricing-v2.png'),
		'AP_P03' => array('name' => 'Set Alerts for 10+ properties', 'months' => '12', 'price' => '249', 'min' => '10', 'max' => '50', 'image' => 'pricing-v2.png'),
	);

	/* 'AP_P01' => array('name' => 'Set Alerts for up to 5 properties', 'time' =>'12', 'price' => '1996',),
	'AP_P02' => array('name' => 'Set Alerts for up to 9 properties', 'time' => '12', 'price' => '3591',),
	'AP_P03' => array('name' => 'Set Alerts for up to 50 properties', 'time' =>'12', 'price' => '4980',),
	Per property rate hai 
	'AP_P01'= 499
	'AP_P02' =399
	'AP_P03' =249 */


	if ($key !== null) {
		if (isset($packages[$key])) {
			return $packages[$key];
		}
	}
	return $packages;
}

function callApi($dbData, $endPoint)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, API_SITE_PATH . $endPoint);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dbData));
	$response = curl_exec($ch);
	/* print_r($dbData);
	print_r($response); */
	curl_close($ch);
	$responseArr = json_decode($response, true);
	return $responseArr;
}

function callApiJson($dbData, $endPoint)
{
	$ch = curl_init();
	/* print_r(API_SITE_PATH. $endPoint);
	print_r($dbData); */
	curl_setopt($ch, CURLOPT_URL, API_SITE_PATH . $endPoint);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dbData));
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	$response = curl_exec($ch);
	//print_r($response);
	curl_close($ch);
	
	$responseArr = json_decode($response, true);
	return $responseArr;
}

function checkSubscription()
{
	$response = callApi(array('user_id'=>$_SESSION['user_id']),API_SITE_PATH.'check_subscription.php');
	$has_subscription = $response['status'];

	return $has_subscription;
}

function getPropertyTypeArray()
{
	$arr = array(
		"Land",
		"Plot",
		"Flat",
		"Residential Unit",
		"Shop",
		"Showroom",
		"Office",
		"Commercial Unit",
		"Apartment",
		"Row House",
		"Bungalow",
		"Plinth",
		"Parking",
		"Gala",
		"Godown",
		"Industrial Unit",
		"Shed",
		"Room",
		"Kholi",
		"Premises",
		"Asset",
		"House",
		"Garage",
		"Factory",
		"TDR",
		"Unit",
	);

	
	return $arr;
}

function getOccupationArray()
{
	$arr = array(
		"Legal Services",
		"Real Esate Developers",
		"Real Estate Agents",
		"Business and Finance",
		"IT and Technical Services",
		"NRI Investors/Technocrats",
		"Accountants/Auditors",
		"Architecture and Engineering",
		"Management",
		"Healthcare and Medical Services",
		"Government Employees",
		"Others",
	);
	return $arr;
}
function get_state_list()
{
	$arr = array(
		"Select State",
		"Maharashtra",
	);
	return $arr;
}

function createSelectListFromArray($arr){
	$html = '';
	foreach($arr as $lkey=>$val){
		$html .= '<option value="'.$val.'">'.$val.'</option>';
	}
	return $html;
}

function getPropertyFormFields(){
	$form_fields['user_id'] = (array('type' => 'input', 'inputType' => 'hidden', 'help' => '', 'label' => 'User ID',  'extra' => '', 'required' => true, 'width' => '4'));

	$form_fields['property_id'] = (array('type' => 'input', 'inputType' => 'hidden', 'help' => '', 'label' => 'Property ID',  'extra' => '', 'required' => true, 'width' => '4'));

	$form_fields['state'] = (array('type' => 'select', 'help' => '', 'label' => 'State',  'extra' => '', 'required' => true, 'width' => '4', 'options' => get_state_list()));

	$form_fields['city'] = (array('type' => 'select', 'help' => '', 'label' => 'City',  'extra' => '', 'required' => true, 'width' => '4', 'options' => array()));

	$form_fields['taluka'] = (array('type' => 'select', 'help' => '', 'label' => 'Taluka',  'extra' => '', 'required' => false, 'width' => '4', 'options' => array()));

	$form_fields['village'] = (array('type' => 'input', 'help' => '', 'label' => 'Village',  'extra' => '', 'required' => true, 'width' => '4', 'options' => array()));

	$form_fields['property_type'] = (array('type' => 'select', 'help' => '', 'label' => 'Property Type',  'extra' => '', 'required' => true, 'width' => '4', 'options' => getPropertyTypeArray()));

	//$form_fields['propert_number'] = (array('type' => 'input','help' => '', 'label' => 'Property Number',  'extra' => '','required'=>true,'width'=>'4'));

	$form_fields['area_name'] = (array('type' => 'input', 'help' => '', 'label' => 'Area Name',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['survey_number'] = (array('type' => 'input', 'help' => '', 'label' => 'Survey Number',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['hissa_number'] = (array('type' => 'input', 'help' => '', 'label' => 'Hissa Number',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['cts_number'] = (array('type' => 'input', 'help' => '', 'label' => 'CTS Number',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['gat_number'] = (array('type' => 'input', 'help' => '', 'label' => 'GAT Number',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['fp_number'] = (array('type' => 'input', 'help' => '', 'label' => 'FP Number',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['tp_number'] = (array('type' => 'input', 'help' => '', 'label' => 'TP Number',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['glr_survey_number'] = (array('type' => 'input', 'help' => '', 'label' => 'GLR Survey Number',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['plot_number'] = (array('type' => 'input', 'help' => '', 'label' => 'Plot Number',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['unit_no'] = (array('type' => 'input', 'help' => '', 'label' => 'Unit Number',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['block_number'] = (array('type' => 'input', 'help' => '', 'label' => 'Block Number',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['zonenumber'] = (array('type' => 'input', 'help' => '', 'label' => 'Zone NUmber',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['wing_number'] = (array('type' => 'input', 'help' => '', 'label' => 'Wing Number',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['building_number'] = (array('type' => 'input', 'help' => '', 'label' => 'Building Number',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['building_name'] = (array('type' => 'input', 'help' => '', 'label' => 'Building Name',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['society_name'] = (array('type' => 'input', 'help' => '', 'label' => 'Society Name', 'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['sector_no'] = (array('type' => 'input', 'help' => '', 'label' => 'Sector Number',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['floor_no'] = (array('type' => 'input', 'help' => '', 'label' => 'Floor Number',  'extra' => '', 'required' => false, 'width' => '4'));

	$form_fields['others'] = (array('type' => 'input', 'help' => '', 'label' => 'Others',  'extra' => '', 'required' => false, 'width' => '4'));

	$required = array(
		'state' => 'State is Required',
		'city' => 'City is Required',
		'taluka' => 'Taluka is Required',
	);	

	$return['fields'] = $form_fields;
	$return['required'] = $required;
	return $return;
}
function getViewPropertyLabels()
{
	$arr = array(
		'state' => 'State',
		'city' => 'City',
		'taluka' => 'Taluka',
		'village' => 'Village',
		'property_type' => 'Property Type',
		'area_name' => 'Area Name',
		'survey_number' => 'Survey Number',
		'glr_survey_number' => 'Glr Survey Number',
		'hissa_number' => 'Hissa Number',
		'gat_number' => 'Gat Number',
		'cts_number' => 'Cts Number',
		'zonenumber' => 'Zonenumber',
		'sector_no' => 'Sector No',
		'propertynumber' => 'Propertynumber',
		'tp_number' => 'Tp Number',
		'fp_number' => 'Fp Number',
		'plot_number' => 'Plot Number',
		'unit_no' => 'Unit No',
		'floor_no' => 'Floor No',
		'block_number' => 'Block Number',
		'bulding_number' => 'Bulding Number',
		'bulding_name' => 'Bulding Name',
		'wing_number' => 'Wing Number',
		'society_name' => 'Society Name',
		'others' => 'Others',
	);
	return $arr;
}
function getRegisterFormFields(){
	$form_fields['first_name'] = (array('type' => 'input', 'help' => '', 'placeholder_only' => 'First Name' , 'label' => 'First Name',  'extra' => '', 'required' => true, 'width' => '12', 'icon' => 'fas fa-user'));
	
	$form_fields['last_name'] = (array('type' => 'input', 'help' => '', 'placeholder_only' => 'Last Name', 'label' => 'Last Name',  'extra' => '', 'required' => true, 'width' =>'12', 'icon' => 'fas fa-user'));

	$form_fields['email'] = (array('type' => 'input', 'help' =>'', 'placeholder_only' => 'Email', 'label' => 'Email',  'extra' => '', 'required' => true, 'width' =>'12', 'icon' => 'fas fa-envelope', 'onBlur' => 'validateEmail(this);'));

	$form_fields['phone'] = (array('type' => 'input', 'help' =>'', 'placeholder_only' => 'Mobile Number', 'label' => 'Mobile Number',  'extra' => '', 'required' => true, 'width' =>'12', 'icon' =>'fas fa-phone', 'onBlur' => 'validateEmail(this);'));

	$form_fields['city'] = (array('type' => 'input', 'help' =>'', 'placeholder_only' => 'City', 'label' => 'City',  'extra' => '', 'required' => false, 'width' =>'12', 'icon' => 'fas fa-building'));

	$form_fields['password'] = (array('type' => 'input', 'help' =>'', 'placeholder_only' => 'Password', 'label' => 'Password',  'extra' => '', 'required' => true, 'width' =>'12', 'icon' => 'fas fa-lock'));

	$form_fields['confirm_password'] = (array('type' => 'input', 'help' =>'', 'placeholder_only' => 'Confirm Password', 'label' => 'Confirm Password',  'extra' => '', 'required' => true, 'width' =>'12', 'icon' => 'fas fa-lock'));
	//$form_fields['confirm_password'] = (array('type' => 'select', 'help' => '', 'label' => 'Property Type',  'extra' => '', 'required' => true, 'width' => '4', 'options' => getPropertyTypeArray()));

	//$form_fields['propert_number'] = (array('type' => 'input','help' => '', 'label' => 'Property Number',  'extra' => '','required'=>true,'width'=>'4'));

	$required = array(
		'first_name' => 'State is Required',
		'last_name' => 'City is Required',
		'email' => 'Taluka is Required',
		'city' => 'Taluka is Required',
		'password' => 'Taluka is Required',
		'confirm_password' => 'Taluka is Required',
	);	

	$return['fields'] = $form_fields;
	$return['required'] = $required;
	return $return;
}
