<?php 

function get_advertiser_list($key=''){
	global $db;
	$row = $db->FetchAll("SELECT id,company FROM advertisers ORDER BY company");
	$arr = [];
	foreach($row as $adv){
		$arr[$adv['id']] = $adv['company'];
	}
	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}	
	return $temp;
}

function get_offer_list(){
	global $db;
	$row = $db->FetchAll("SELECT id,name FROM offers");
	$temp = [];
	foreach($row as $adv){
		$temp[$adv['id']] = $adv['name'];
	}
	return $temp;	
}

function get_affiliate_list(){
	global $db;
	$row = $db->FetchAll("SELECT id,company FROM affiliates ORDER BY company");
	$temp = [];
	foreach($row as $adv){
		$temp[$adv['id']] = $adv['company'];
	}
	return $temp;	
}


function get_affiliate_user_list(){
	global $db;
	$row = $db->FetchAll("SELECT username, firstname, lastname FROM users WHERE user_type='user' AND user_level='manager'");
	$temp = [];
	foreach($row as $user){
		$temp[$user['username']] = $user['firstname'].' '.$user['lastname'];
	}
	return $temp;	
}

function get_conversion_tracking_list($key=''){

	$arr['http'] = 'HTTP iFrame Pixel';
	$arr['https'] = 'HTTPS iFrame Pixel';
	$arr['http_img'] = 'HTTP Image Pixel';
	$arr['https_img'] = 'HTTPS Image Pixel';
	$arr['server_affiliate'] = 'PostBack With Affiliate Id';
	$arr['server'] = 'PostBack With Transaction Id';
	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;	
}

function get_all_clicks_array(){
	global $db;
	$sql = "SELECT clicks.*, offers.name, offers.advertiser, advertisers.company 
	FROM clicks 
	INNER JOIN offers ON clicks.offer_id = offers.id
	INNER JOIN advertisers ON offers.advertiser = advertisers.id
	";
	$row = $db->FetchAll($sql);
	//echo mysqli_error($db->db_conx);
	return $row;	
}

function get_total_clicks(){
	global $db;
	$sql = "SELECT COUNT(id) FROM clicks";
	$query = mysqli_query($db->db_conx, $sql);
	$row = mysqli_fetch_array($query);
	//print_r($row);
	//echo mysqli_error($db->db_conx);
	return $row[0];	
}

function get_total_conversions(){
	global $db;
	$sql = "SELECT COUNT(id) FROM clicks WHERE conversion='conversion'";
	$query = mysqli_query($db->db_conx, $sql);
	$row = mysqli_fetch_array($query);
	//print_r($row);
	//echo mysqli_error($db->db_conx);
	return $row[0];	
}

function get_total_affiliate_clicks($aff_id){
	global $db;
	$sql = "SELECT clicks FROM clicks_counter WHERE affiliate_id='$aff_id' AND advertiser_id='' AND offer_id=''";
	$query = mysqli_query($db->db_conx, $sql);
	$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
	//print_r($row);
	//echo mysqli_error($db->db_conx);
	return $row['clicks'];	
}

function get_total_affiliate_conversions($aff_id){
	global $db;
	$sql = "SELECT conversions FROM clicks_counter WHERE affiliate_id='$aff_id' AND advertiser_id='' AND offer_id=''";
	$query = mysqli_query($db->db_conx, $sql);
	$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
	//print_r($row);
	//echo mysqli_error($db->db_conx);
	return $row['conversions'];	
}

function get_affiliate_clicks_array($aff){
	global $db;
	$sql = "SELECT clicks.*, offers.name, offers.advertiser, advertisers.company 
	FROM clicks 
	INNER JOIN offers ON clicks.offer_id = offers.id
	INNER JOIN advertisers ON offers.advertiser = advertisers.id
	WHERE clicks.affiliate_id='$aff' ";
	$row = $db->FetchAll($sql);
	//echo mysqli_error($db->db_conx);
	return $row;	
}

function get_all_conversions_array(){
	global $db;
	$sql = "SELECT clicks.*, offers.name, offers.advertiser, advertisers.company 
	FROM clicks 
	INNER JOIN offers ON clicks.offer_id = offers.id
	INNER JOIN advertisers ON offers.advertiser = advertisers.id
	WHERE conversion='conversion' ";
	$row = $db->FetchAll($sql);
	//echo mysqli_error($db->db_conx);
	return $row;	
}

function get_affiliate_conversions_array($aff){
	global $db;
	$sql = "SELECT clicks.*, offers.name, offers.advertiser, advertisers.company 
	FROM clicks 
	INNER JOIN offers ON clicks.offer_id = offers.id
	INNER JOIN advertisers ON offers.advertiser = advertisers.id
	WHERE conversion='conversion' AND clicks.affiliate_id='$aff' ";
	$row = $db->FetchAll($sql);
	//echo mysqli_error($db->db_conx);
	return $row;	
}

function get_total_revenue(){
	global $db_conx;
	$sql = "SELECT SUM(click.conversions*fin.revenue_per_conversion) as total
	FROM clicks_counter click
	INNER JOIN offer_financial fin ON click.offer_id=fin.offer_id
	INNER JOIN offers ON offers.id=click.offer_id
	WHERE click.offer_id !=''
	
	";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
	//print_r($row);
	return $row['total'];	
	
}

function get_total_cost(){
	global $db_conx;
	$sql = "SELECT SUM(click.conversions*fin.cost_per_conversion) as total
	FROM clicks_counter click
	INNER JOIN offer_financial fin ON click.offer_id=fin.offer_id
	INNER JOIN offers ON offers.id=click.offer_id
	WHERE click.offer_id !=''
	
	";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
	//print_r($row);
	return $row['total'];	
	
}

function get_total_affiliate_revenue($aff_id){
	global $db_conx;
	$sql = "SELECT SUM(fin.cost_per_conversion) as total
	FROM clicks 
	INNER JOIN offer_financial fin ON clicks.offer_id=fin.offer_id
	WHERE clicks.affiliate_id ='$aff_id'
	AND clicks.conversion='conversion'
	
	";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
	//print_r($row);
	return $row['total'];	
	
}
?>