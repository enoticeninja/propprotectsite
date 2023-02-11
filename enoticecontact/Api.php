<?php
include_once(DIR_CORE_INCLUDES."db_conx.php");
include_once 'Routes.php';
$class = null;
$module = get_current_url_array(1);
$action = get_current_url_array(2);
//print_r($module);
//print_r($action);

if (isset($module) && !empty($module)) {
    $class = getAdapter($module);
}

if(!is_null($class)){
    $returnData = $class->getDataForApi();
    //var_export($returnData);
    echo json_encode($returnData);
    exit();
}
?>