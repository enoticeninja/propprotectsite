<?php
include_once '../common_bootstrap.php';
include_once '../config.php';
include_once DIR_INCLUDES . 'common-functions.php';

//$json_request = (json_decode($_REQUEST) != NULL) ? true : false;
$getOrPost = $_SERVER['REQUEST_METHOD'];
$fileName = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
/* var_export($method);*/
//var_export($_REQUEST); 

$entityBody = file_get_contents('php://input');
//print_r(($_POST));
//print_r(($entityBody));

/* print_r(($entityBody));
print_r(http_build_query($entityBody)); */
if(!empty($_GET)){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, API_SITE_PATH . $fileName . '?' . http_build_query($_GET));
	$response = curl_exec($ch);
	echo $response;
	exit();	
}
if(!empty($_POST)){
	$method = 'POST';
	if(!empty($entityBody)){
		$method = 'JSON';
	}
	if (strtolower($method) == 'post'){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, API_SITE_PATH . $fileName);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($_POST));
		$response = curl_exec($ch);
		echo $response;
		exit();	
	}
	if (strtolower($method) == 'json') {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, API_SITE_PATH . $fileName);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $entityBody);
		$response = curl_exec($ch);
		echo $response;
		exit();	
	}	
}




//print_r($method);
/* $response = array('gfhjfg'=>'dfghdfgh');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, API_SITE_PATH . $fileName);
//curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
if(strtolower($method) == 'post'){
	print_r($method);
//print_r(http_build_query($entityBody));
	//$response = callApi($_REQUEST,$fileName);
	if(strtolower($getOrPost) == 'post'){
		curl_setopt($ch, CURLOPT_POST, 1);

		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($entityBody));
	}
}
else if(strtolower($method) == 'json'){
	//$response = callApiJson($_REQUEST, $fileName);
	curl_setopt($ch, CURLOPT_POSTFIELDS, ($entityBody));
}
$response = curl_exec($ch);
//print_r($response);
echo $response;
exit(); */

