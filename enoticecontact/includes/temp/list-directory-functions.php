<?php
function find_page_templates(){
   $return = array(); 
   $list = glob(DIR_FRONTEND_ROOT.'/pageTemplate-*.php'); 
   //print_r($list);
   foreach ($list as $l) { 
      $fileName = basename($l);
      $baseName = basename($l,'.php');
      $tplName = str_replace("pageTemplate-", "", $baseName);
      $return[$fileName] = $tplName;  
   }
return $return;   
}

function find_widget_templates(){
   $return = array(); 
   $list = glob(DIR_FRONTEND_ROOT.'/widgets/widget-*.php'); 
   //print_r($list);
   foreach ($list as $l) { 
      $fileName = basename($l);
      $baseName = basename($l,'.php');
      $tplName = str_replace("widget-", "", $baseName);
      $return[$fileName] = $tplName;  
   }
return $return;   
}

?>