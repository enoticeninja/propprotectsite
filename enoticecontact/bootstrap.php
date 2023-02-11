<?php
session_start();
date_default_timezone_set("Asia/Calcutta");

function get_theme_folder_name() {
    $theme = 'met5';
    if(isset($_GET['theme'])){
        $theme = $_GET['theme'];
    }    
    return $theme;
}
$theme = get_theme_folder_name();
if(isset($_GET['theme'])){
    $theme = $_GET['theme'];
}
define('DIR_SITE_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('DIR_INCLUDES', DIR_SITE_ROOT.'includes'.DIRECTORY_SEPARATOR);
define('DIR_PHP_CLASSES', DIR_INCLUDES.'classes'.DIRECTORY_SEPARATOR);
define('DIR_THEME', DIR_SITE_ROOT.'themes/'.get_theme_folder_name().DIRECTORY_SEPARATOR);
define('DIR_THEME_INCLUDES', DIR_THEME.'includes'.DIRECTORY_SEPARATOR);
define('DIR_THEME_CLASSES', DIR_THEME_INCLUDES.'classes'.DIRECTORY_SEPARATOR);


spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = '';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/includes/classes/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', 'Class.'.$relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require_once $file;
    }
});
?>