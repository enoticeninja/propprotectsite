<?php
include_once 'db_conx.php';
$indian_all_states  = array (
 'AP' => 'Andhra Pradesh',
 'AR' => 'Arunachal Pradesh',
 'AS' => 'Assam',
 'BR' => 'Bihar',
 'CT' => 'Chhattisgarh',
 'GA' => 'Goa',
 'GJ' => 'Gujarat',
 'HR' => 'Haryana',
 'HP' => 'Himachal Pradesh',
 'JK' => 'Jammu & Kashmir',
 'JH' => 'Jharkhand',
 'KA' => 'Karnataka',
 'KL' => 'Kerala',
 'MP' => 'Madhya Pradesh',
 'MH' => 'Maharashtra',
 'MN' => 'Manipur',
 'ML' => 'Meghalaya',
 'MZ' => 'Mizoram',
 'NL' => 'Nagaland',
 'OR' => 'Odisha',
 'PB' => 'Punjab',
 'RJ' => 'Rajasthan',
 'SK' => 'Sikkim',
 'TN' => 'Tamil Nadu',
 'TR' => 'Tripura',
 'UK' => 'Uttarakhand',
 'UP' => 'Uttar Pradesh',
 'WB' => 'West Bengal',
 'AN' => 'Andaman & Nicobar',
 'CH' => 'Chandigarh',
 'DN' => 'Dadra and Nagar Haveli',
 'DD' => 'Daman & Diu',
 'DL' => 'Delhi',
 'LD' => 'Lakshadweep',
 'PY' => 'Puducherry',
);
$sql = "TRUNCATE state";
$query = mysqli_query($db_conx,$sql);
//exit();
$sql = "INSERT INTO state (name,country_id,status,iso_code) VALUES";
$sql_values = array();
foreach($indian_all_states as $key=>$state){
	//$sql .= " ('$state',1,1,'$key'),";
	$data['name'] = $state;
	$data['country_id'] = 1;
	$data['status'] = 1;
	$data['iso_code'] = $key;
	$sql_values[] = "('".implode("','", $data)."')";
}
$sql .= "
".implode(", ", $sql_values)."
";
print_r($sql);
$query = mysqli_query($db_conx,$sql);
print_r(mysqli_error($db_conx));
function get_indian_states_array(){
	global $indian_all_states;
	return $indian_all_states;
}
?>