<?php
include_once("bootstrap.php");
include_once("config.php");
include_once(DIR_INCLUDES."db_conx.php");
include_once(DIR_PHP_CLASSES."Class.User.php");
$frontend_path = 'frontend/';
$theme_path = 'theme/';
$title = 'Login';
$user = new User($db_conx);
$user->logout(get_redirect_logout());
?> 