<?php 
$rowsCountDisplay = 10;
function getAdapter($module)
{
    global $db_conx;
    $class = null;
    switch (strtolower($module)) {
        case 'backend_users':
        case 'backend_user':
        case 'backenduser':
        case 'backendusers':
            require_once DIR_OTHER_CLASSES . 'Class.ManageBackendUser.php';
            $class = new ManageBackendUser($db_conx);
            $class->rowsCountDisplay = 50;
            break;
        case 'brand':
        case 'brands':
            require_once DIR_OTHER_CLASSES . 'Class.ManageBrand.php';
            $class = new ManageBrand($db_conx);
            $class->rowsCountDisplay = 50;
            break;
        case 'ad_clients':
        case 'ad_client':
        case 'adclient':
        case 'adclients':
            require_once DIR_OTHER_CLASSES . 'Class.ManageAdClients.php';
            $class = new ManageAdClients($db_conx);
            $class->rowsCountDisplay = 10;
            break;
        case 'advertisement':
        case 'advertisements':
            require_once DIR_OTHER_CLASSES . 'Class.ManageAdvertisements.php';
            $class = new ManageAdvertisements($db_conx);
            $class->rowsCountDisplay = 10;
            break;
        case 'notices':
        case 'notice':
            require_once DIR_OTHER_CLASSES . 'Class.ManageNotices.php';
            $class = new ManageNotices($db_conx);
            $class->rowsCountDisplay = 10;
            break;
        case 'feedback':
        case 'contact':
            require_once DIR_OTHER_CLASSES . 'Class.ManageFeedback.php';
            $class = new ManageFeedback($db_conx);
            $class->rowsCountDisplay = 10;
            break;
        default:
            //header('Location: '.$site_path.'DashBoard');
            //exit();
            break;
    }
    return $class;
}
?>