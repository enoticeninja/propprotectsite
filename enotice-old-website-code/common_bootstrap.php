<?php
/* define('DIR_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('DIR_INCLUDES', dirname(__FILE__).DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR);
define('DIR_LAWYER_ROOT', DIR_ROOT.DIRECTORY_SEPARATOR. 'lawyer' . DIRECTORY_SEPARATOR);
define('DIR_ADMIN_ROOT', DIR_ROOT.DIRECTORY_SEPARATOR. 'admin' . DIRECTORY_SEPARATOR);
define('_DS_', DIRECTORY_SEPARATOR);
include_once 'config.php';
require_once DIR_INCLUDES . 'mail-functions.php';
include_once 'submit-contact-form.php'; */

define('DIR_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('DIR_INCLUDES', dirname(__FILE__).DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR);
/* define('DIR_ROOT','https://api.enoticeninja.com');
 define('DIR_LAWYER_ROOT', DIR_ROOT.DIRECTORY_SEPARATOR. 'lawyer' . DIRECTORY_SEPARATOR);
define('DIR_ADMIN_ROOT', DIR_ROOT.DIRECTORY_SEPARATOR. 'admin' . DIRECTORY_SEPARATOR); */
define('DIR_LAWYER_ROOT', DIR_ROOT. 'lawyer' . DIRECTORY_SEPARATOR);
define('DIR_ADMIN_ROOT', DIR_ROOT. 'admin' . DIRECTORY_SEPARATOR);
define('_DS_', DIRECTORY_SEPARATOR);
include_once 'config.php';


/* echo "DIR_ROOT".DIR_ROOT;
echo '</br>';
echo '</br>';
echo "DIR_INCLUDES".DIR_INCLUDES;
echo '</br>';
echo '</br>';
echo "DIR_LAWYER_ROOT".DIR_LAWYER_ROOT;
echo '</br>';
echo '</br>';
echo "DIR_ADMIN_ROOT".DIR_ADMIN_ROOT;
echo '</br>';
echo '</br>';
 */
require_once DIR_INCLUDES . 'mail-functions.php';
require_once DIR_INCLUDES . 'common-functions.php';
include_once 'submit-contact-form.php';

?>
