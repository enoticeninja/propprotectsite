<?php
function getOS($user_agent) { 



    $os_platform    =   "Unknown OS Platform";

    $os_array       =   array(
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );

    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }   

    return $os_platform;

}

function getBrowser($user_agent) {

    $browser        =   "Unknown Browser";

    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Handheld Browser'
                        );

    foreach ($browser_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }

    }

    return $browser;

}

function get_client_ip() {  ///DOUBTFULL
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


///return a string after replacing the variables between {} with values provided in $dataArray
// values between {} should be exactly the same as the array idex of dataArray eg {aff_sub} dataArray should have $dataArray['aff_sub']
function replaceBetweenBraces($dataArray, $string){
	
	$pattern = '/{(.*?)[\|\|.*?]?}/';						
	$replace = preg_replace_callback($pattern, function($match) use ($dataArray)
	{
		return $dataArray[$match[1]];
	}, $string);	
	return $replace;
}

function sendConversionViaPostBack($row){
	global $db_conx;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$pb_url = $row_pb['postback_url'];

	$pb_params['transaction_id'] = $row['aff_transaction_id'];
	$pb_params['aff_sub'] = $row['aff_sub'];
	$pb_params['aff_sub1'] = $row['aff_sub1'];
	$pb_params['aff_sub2'] = $row['aff_sub2'];
	$pb_params['aff_sub3'] = $row['aff_sub3'];
	
	
	///Replace affiliate parameters saved in the click with the values in postback url eg {aff_sub} or {transaction_id}					
	$replace = replaceBetweenBraces($pb_params, $pb_url);						
	echo $replace;
	curl_setopt($ch, CURLOPT_URL,$replace);
	$content = curl_exec($ch);
	$sql_log = "UPDATE logs SET result=CONCAT(result, 'Server postback on non postback offer $content') WHERE id='$log_id' ";
	$query_log = mysqli_query($db_conx, $sql_log);			
}
?>