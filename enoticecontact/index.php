<?php
include_once("common_bootstrap.php");
include_once("bootstrap.php");
include_once("config.php");
/* print_r(get_current_url_array());
exit(); */
$page_name = get_current_url_array(0);
/* echo $page_name;
exit(); */
if(file_exists(''.$page_name.'.php')){
    include_once ''.$page_name.'.php';
    exit();
}

/* print_r($page_name);
exit(); */
if($page_name){
    
	include (DIR_SITE_ROOT.'themes/'.$theme.'/'.''.$page_name.'.php');
}
else{
	include DIR_SITE_ROOT.'themes/'.$theme.'/'.'Login.php';
}
?>
