<?php
$class = null;
if (isset($_REQUEST['config']['module'])) {
    $module = $_REQUEST['config']['module'];
}
if (isset($_REQUEST['module'])) {
    $module = $_REQUEST['module'];
}

include_once 'Routes.php';

if (isset($module)) {
    $class = getAdapter($module);
}

if (isset($_REQUEST['action'])) {
    //print_r($_REQUEST);
    //exit();
    if (isset($_REQUEST['module']) || isset($_REQUEST['config']['module'])) {
        if (!is_null($class)) {
            $class->handleAjaxCall($_REQUEST);
        } else {
            $return['ajaxresult'] = true;
            $return['data'] = $_REQUEST;
            $return['ajaxmessage'] = 'This action is not defined';
            $return['jsFunction'] = 'console.log(value);';
            echo json_encode($return);
            exit();
        }

    } else {
        $return['ajaxresult'] = true;
        $return['data'] = $_REQUEST;
        $return['ajaxmessage'] = 'Module is not defined';
        $return['jsFunction'] = 'console.log(value);';
        echo json_encode($return);
        exit();
    }
}
