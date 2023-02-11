<?php
define('DIR_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('_DS_', DIRECTORY_SEPARATOR);
define('DIR_CORE_INCLUDES', DIR_ROOT.'core_includes'. DIRECTORY_SEPARATOR);
define('DIR_CORE_CLASSES', DIR_CORE_INCLUDES.'core_classes'. DIRECTORY_SEPARATOR);
define('DIR_OTHER_CLASSES', DIR_CORE_INCLUDES.'classes'. DIRECTORY_SEPARATOR);
define('DIR_CORE_THEME', DIR_ROOT.'core_theme'. DIRECTORY_SEPARATOR);
define('DIR_FRONTEND_ROOT', DIR_ROOT.'frontend'. DIRECTORY_SEPARATOR);
define('DIR_ADMIN_ROOT', DIR_ROOT.'backend'. DIRECTORY_SEPARATOR);
define('DIR_IMAGE_UPLOADS', DIR_ROOT.'uploads/');
define('DIR_PHP_UPLOADS', DIR_ROOT.'uploads/');
define('DIR_IMAGE_UNDO', DIR_PHP_UPLOADS.'undo/');
define('DIR_PRODUCT_UPLOADS', DIR_IMAGE_UPLOADS.'products/');
define('DIR_PRODUCT_UPLOADS_50', DIR_PRODUCT_UPLOADS.'50x50');
define('DIR_PRODUCT_UPLOADS_100', DIR_PRODUCT_UPLOADS.'100x100');
date_default_timezone_set('Asia/Kolkata');

define('SMTP_HOSTNAME', 'smtp.gmail.com');
define('SMTP_PORT', '25');
define('SMTP_USERNAME', 'rayansh.golu@gmail.com');
define('SMTP_PASSWORD', 'Kemosabe@1010');


define('DEV_MODE', true);

spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = '';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/core_includes/core_classes/';

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
        require $file;
    }
});
?>