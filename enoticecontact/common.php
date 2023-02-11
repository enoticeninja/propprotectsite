<?php
include_once DIR_SITE_ROOT.'config.php';
include_once DIR_THEME.'theme-config.php';
include_once DIR_CORE_INCLUDES.'function_bootstrap_columns.php';		
include_once(DIR_CORE_INCLUDES."db_conx.php");
include_once(DIR_CORE_CLASSES."class_portlet.php");
include_once(DIR_CORE_CLASSES."class_form.php");
include_once(DIR_CORE_CLASSES."class_table.php");
include_once(DIR_CORE_INCLUDES."common_functions.php");
include_once(DIR_CORE_INCLUDES."common_db_functions.php");
include_once(DIR_CORE_INCLUDES."crm_functions.php");
include_once(DIR_PHP_CLASSES."Class.User.php");
include_once("permissions.php");
$user_ok = new User($db_conx);
if(($user_ok->user_ok != true )){
	$redir = get_redirect_logout();
	
/* 	$_SESSION[$user_ok->session['id']] = 1;
	$_SESSION[$user_ok->session['username']] = 1;
	$_SESSION[$user_ok->session['password']] = 1;
	$user_ok->userData['name_to_display'] = 'Admin'; */
						
	header("location: $redir");
    exit();
}
$__USER_DATA__ = $user_ok->userData;
define('USER_LEVEL',$__USER_DATA__['userlevel']);
function getUserData($field=''){
    global $__USER_DATA__;
    if($field == '') return $__USER_DATA__;
    return $__USER_DATA__[$field];
}
$uri = $_SERVER['REQUEST_URI'];
$arr = explode ('/', $uri);
$arr_get = explode('?',$arr[1]);
$page_name = $arr_get[0];
$username = $_SESSION[get_session_values('username')];
/*$user_level = $user_ok->userData['userlevel']; */

?>