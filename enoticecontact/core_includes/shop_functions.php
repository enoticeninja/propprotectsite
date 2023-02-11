<?php
function get_vendors_list($key=''){
	global $db_conx;
	$arr = array();
	$sql = "SELECT id, name FROM vendor WHERE status='active'";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
		$arr[$row['id']] = $row['name'];
	}
	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;
}

function get_products_list($key=''){
	global $db_conx;
	$arr = array();
	$sql = 
    "
    SELECT p.id, p.name, v.name as vendor 
    FROM product as p
	LEFT JOIN vendor as v on v.id=p.vendor_id
    WHERE p.status='active'
    ";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
		$arr[$row['id']] = ''.$row['name'].' ';
	}
	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;
}

function get_category_list($key=''){
	global $db_conx;
	$arr = array();
	$sql = 
    "
    SELECT nc.id, nc.name
    FROM nested_category as nc
    ";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
		$arr[$row['id']] = ''.$row['name'].'';
	}
	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;
}

function get_products_list_by_vendor($vendor){
	global $db_conx;
	$arr = array();
	$sql = 
    "
    SELECT p.id, p.name, v.name as vendor 
    FROM product as p
    INNER JOIN vendor as v on p.vendor_id=v.id
    WHERE p.status='active' AND p.vendor_id='$vendor'
    ";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
		$arr[$row['id']] = ''.$row['name'].' By '.$row['vendor'].'';
	}

	return $arr;
}

function get_products_list_by_category($category_id){
	global $db_conx;
	$arr = array();
	$sql = 
    "
    SELECT p.id,p.name,v.name as vendor
    FROM nested_category AS parent 
    INNER JOIN nested_category as children ON children.nleft BETWEEN parent.nleft AND parent.nright
    INNER JOIN product_category AS pc ON children.id = pc.category_id 
    INNER JOIN product as p on pc.product_id = p.id 
    LEFT JOIN vendor as v on p.vendor_id=v.id   
    WHERE  parent.id = '$category_id'
    GROUP BY p.id;
    ";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
		$arr[$row['id']] = ''.$row['name'].' By '.$row['vendor'].'';
	}
    print_r(mysqli_error($db_conx));
	return $arr;
}

/* function get_products_list($key=''){
	global $db_conx;
	$arr = array();
	$sql = "SELECT id, name FROM products WHERE status='active'";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
		$arr[$row['id']] = ''.$row['name'].'';
	}
	if($key == ''){
		$temp = $arr;
	}
	else{
		$temp = $arr[$key];
	}
	return $temp;
} */



function get_country_list_db(){
    global $db_conx;
    $sql = "SELECT id,name FROM country";
    $query = mysqli_query($db_conx, $sql);
    while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        $temp[$row['id']] = $row['name'];
    }
	return $temp;	
}

function get_state_list_db($country_id){
    global $db_conx;
    $sql = "SELECT id,name FROM state WHERE country_id='$country_id'";
    $query = mysqli_query($db_conx, $sql);
    while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        $temp[$row['id']] = $row['name'];
    }
	return $temp;	
}

function sendSMS($data){
	$query = http_build_query([
	 'user' => 'apjtek',
	 'apikey' => 'Q8i5ypXQMCZuqQWAb6rX',
	 'mobile' => $data['mobile'],
	 'message' => '8826612434',
	 //'message' => $data['message'],
	 'senderid' => 'TELISM',
	 'type' => 'txt'
	]);
	$url = "http://telisms.com/api/sendsms.php?".$query;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
/* 	$output = curl_exec($ch);
	curl_close($ch);
	echo $output;
	return $output; */
	return '123456';
	//return true;
}
?>