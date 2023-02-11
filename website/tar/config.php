<?php
$site_path = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
//define('SITE_PATH', $site_path.'/notice_ninja/');
define('SITE_PATH', $site_path);
//echo "site_path".$site_path;
define('API_SITE_PATH', 'https://api.enoticeninja.com/lawyer/');
define('API_ROUTE_PATH', SITE_PATH.'api/');
define('ADMIN_SITE_PATH', SITE_PATH.'admin/');
$title = 'eNotice Ninja';
?>