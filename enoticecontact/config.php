<?php
define('IS_SUBFOLDER', false);////If the backend folder a subfolder of the base domain/main domain
define('SUB_FOLDER_NAME', 'backend');
define('IS_BASE_SUB_FOLDER', true);/////If the containing folder is a sub fo.lder of the main domain
define('BASE_SUB_FOLDER_NAME', 'enoticecontact');
$frontend_site_path = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
if(IS_BASE_SUB_FOLDER)$frontend_site_path = $frontend_site_path.BASE_SUB_FOLDER_NAME."/";
if(IS_SUBFOLDER) $site_path = $frontend_site_path.SUB_FOLDER_NAME.'/';
else $site_path = $frontend_site_path;
define('FRONTEND_SITE_PATH', $frontend_site_path);
define('SITE_PATH', $site_path);
define('USER_TABLE', 'users');
define('DISABLE_CACHE', true);
define('STATIC_USERNAME', 'admin');
define('STATIC_PASSWORD', '123456');
define('API_SITE_PATH', 'https://api.enoticeninja.com/lawyer/');

//echo "apiSitePath".API_SITE_PATH;


$theme_path = $site_path.'themes/'.get_theme_folder_name().'/';
define('HTTP_THEME_PATH', $theme_path);
$core_theme_path = $frontend_site_path.'core_theme/';
$absolute_theme_path = DIR_SITE_ROOT.'themes/'.get_theme_folder_name().'/';
$Mat_theme_path = $site_path.'themes/'.get_theme_folder_name().'/';
$frontend_path = 'frontend/';
$tpl_path = DIR_SITE_ROOT.'themes/'.get_theme_folder_name().'/tpl/';
$site_logo_path = '';
$title = 'eNotice Ninja Admin';
$company_name = $title;
define('SUPPORT_EMAIL', 'support@example.com');
define('SUPPORT_NUMBER', '1-800-5555-6666');
define('COMPANY_NAME', $company_name);
define('COMPANY_ADDRESS', '');
define('CURRENCY_SYMBOL', 'Rs. ');
define('HAS_NOTIFICATIONS', true);
define('NOTIFICATION_TABLE', 'notification');

$site_config = [];
$site_config['logout-redirect']	= $site_path.'Login';		
$site_config['login-redirect']	= $site_path.'DashBoard';

function get_absolute_theme_path() {
    global $site_path;
    $theme = $site_path.'themes/'.get_theme_folder_name().'/';
    return $theme;
}
function has_horizontal_menu(){
	return false;
}
function has_vertical_menu(){
	return true;
}

function get_session_variables(){
	$session = array (
					'id' => 'nanoadmin-id',
					'username' => 'nanoadmin-username',
					'password' => 'nanoadmin-password',
					'user_level' => 'nanoadmin-userlevel',
					'image-undo'=> 'nanoadmin-image-undo',
					'image-undo-count'=> 'nanoadmin-image-undo-count'
					);
	return $session;				
}

function get_full_current_url(){
    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    return $actual_link;
}

function get_current_url(){
    $uri = $_SERVER['REQUEST_URI'];
    $arr_get = explode('?',$uri);
    $arr = $arr_get[0];
    $arr = ltrim($arr, '/');
    $arr = rtrim($arr, '/');
    $arr = explode ('/', $arr);
	if(IS_BASE_SUB_FOLDER){
		$arr = array_splice($arr, 1);
	}
	if(IS_SUBFOLDER){
		$arr = array_splice($arr, 1);
	}
    $return = implode('/',$arr);
	
    return $return;    
}

function get_current_url_array($key = ''){
    $uri = $_SERVER['REQUEST_URI'];
    $arr_get = explode('?',$uri);
    $arr = $arr_get[0];
    $arr = ltrim($arr, '/');
    $arr = rtrim($arr, '/');
    $arr = explode ('/', $arr);
	if(IS_BASE_SUB_FOLDER){
		$arr = array_splice($arr, 1);
	}
	if(IS_SUBFOLDER){
		$arr = array_splice($arr, 1);
	}
    $return = $arr;
    if($key !== ''){
        $return = isset($arr[$key]) ? $arr[$key] : false;
    }
	
    return $return;    
}

function get_user_id(){
    return $_SESSION[get_session_values('id')];
}

function get_session_values($key){
	$session = get_session_variables();
	return $session[$key];				
}

function get_cookie_domain(){
	$domain = '';
	if(IS_SUBFOLDER)$domain = SUB_FOLDER_NAME;
	if(IS_BASE_SUB_FOLDER)$domain = BASE_SUB_FOLDER_NAME.'/'.SUB_FOLDER_NAME;
	return $domain;				
}
function get_cookie_name(){
	$name = 'nanoadmin';
	return $name;				
}
function get_cookie_variables(){
	$cookie = array (
					'userid' => 'nanoadmin-id',
					'username' => 'nanoadmin-user',
					'password' => 'nanoadmin-pass'
					);
	return $cookie;				
}

function get_site_path(){
	global $site_path;
	return $site_path;
}	

function get_theme_path(){
	global $theme_path;
	return $theme_path;
}		

function get_core_theme_path(){
	global $core_theme_path;
	return $core_theme_path;
}	
	

function get_tpl_path(){
	global $tpl_path;
	return $tpl_path;
}	

function get_redirect_logout(){
	global $site_config;
	return $site_config['logout-redirect'];
}	

function get_redirect_login(){
	global $site_config;
	return $site_config['login-redirect'];
}

function get_site_logo(){
	global $site_logo_path;
	return $site_logo_path;
}

function get_company_name(){
	global $company_name;
	return $company_name;
}


function get_time_zone(){
	global $time_zone;
	return $time_zone;
}			
?>
