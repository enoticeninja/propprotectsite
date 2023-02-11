<?php 
function get_permissions($key=''){
    $arr['admin']['title'] = 'Administrator';
    $arr['admin']['level'] = 100;
    $arr['manager']['title'] = 'Manager';
    $arr['manager']['level'] = 500;
    if($key != ''){
        if(isset($arr[$key])){
            return $arr[$key];
        }
        else{
            return false;
        }
    }
    else{
        return $arr;
    }
    
}	

////check permission for create, edit, delete in table and form function (in each class), most probably in createarrayforcolumns function
?>