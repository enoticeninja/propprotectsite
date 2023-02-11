<?php

class SingleDBCore extends SingleDBAjax
{

    public $fields = array();
    public $table = array();
    public $delete_dependency = array();
    public $dependency = array();
    public $ajax_dependency = array();
    public $sensitiveFields = array();
    public $associations = array();
    public $many_to_many = array();
    public $bulk_update_fields = array();
    public $wrappers = array();
    public $default_insert = array();      
    /*Do not edit after this line*/
    public $dbID;
    public $assocForm = false;
    public $dbConn;
    public $dbTable = '';
    public $common_title = '';
    public $oto_fk = '';
    public $selectListSql = '';
    public $dbData = array();
    public $dbDataMany = array();
    public $assocData = array();
    public $dataTable = array();
    public $assoc_with_same_table = 'assoc';
    public $DEVELOPMENT_MODE = false;
    private $displayErrors = false;
    public $user_ok = false;
    public $errorLog;
    public $sql = "";
    public $module = '';

    public function Common($db_conx = '') {
        $this->sql = "SELECT * FROM $this->dbTable";
        
    }
      
    public function checkForDuplicate($data){
        $dbColTemp = $data['type'];
        $arrTemp = explode('-',$dbColTemp);
        $dbCol = end($arrTemp);        
        $dbVal = $data['value'];
        $return = array();
        $sql = "SELECT id FROM $this->dbTable WHERE $dbCol='$dbVal'";
        $query = mysqli_query($this->dbConn, $sql);
        //print_r(mysqli_error($this->dbConn));
        if(mysqli_num_rows($query) >= 1){
            $return['ajaxresult'] = false;
            $return['ajaxmessage'] = '<strong>'.$dbVal.'</strong> already exists';
        }
        else {
            $return['ajaxresult'] = true;
            $return['ajaxmessage'] = '<strong>'.$dbVal.'</strong> is OK';		
        }
        echo json_encode($return);
        exit();		
    }

    public function loadFromDB($id,$fk='') {
        $sqlMain = $this->generateSqlPullData();        
        if($fk != ''){
            $sql = "$sqlMain WHERE main.$fk='$id' LIMIT 1";        
        }        
        else if($this->oto_fk != ''){
            $fk = $this->oto_fk;
            $sql = "$sqlMain WHERE main.$fk='$id' LIMIT 1";        
        }
        else{
            if($this->sql != ''){
                $sql = "$this->sql WHERE t1.id='$id'";         
            }
            else{
                $sql = "$sqlMain WHERE main.id='$id' LIMIT 1";                      
            }            
        }
        $res = $this->query($sql);
        if (!$res)
            return false;
        $this->dbData = mysqli_fetch_array($res, MYSQLI_ASSOC);
        $this->dbID = $this->dbData['id'];
 

        return $this->dbData;
    }

    public function getData($id){
        $data = $this->loadFromDB($id);
        $data = $this->filterDataToShow($data);
        return $data;
    }

    public function loadAllFromDB($id,$fk) {
        $sql = $this->generateSqlPullData();  
        $sql .= " WHERE main.$fk='$id'"; 
        //print_r($sql);        
        $res = $this->query($sql);
        //print_r($res);
        //print_r(mysqli_error($this->dbConn));
        if ( mysqli_num_rows($res) == 0 )
        return false;

        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC)){
            
            $this->dbDataMany[$row['id']] = $row;
            $return[$row['id']] = $row;
        }
        return $return;
    } 

    public function filterDataToShow($data){
        $fKeys = $this->getForeignKeys();        
        $labels = $this->getLabels();        
        foreach($fKeys as $field=>$fieldData){
/*             if(isset($fieldData['join2']) && $fieldData['join2']){
                $firstElement = reset($fieldData['name']);
                $firstKey = key($fieldData['name']);
                //print_r($fieldData);
                $data[$field] = $data[''.$field.'_name'];
                unset($data[''.$field.'_name']);
                unset($data[''.$field.'_id']);                 
                unset($data[''.$firstKey.'_id']);                 
            }
            else{
                $data[$field] = $data[''.$field.'_name'];
                unset($data[''.$field.'_name']);
                unset($data[''.$field.'_id']);  
            } */
                $data[$field] = $data[''.$field.'_name'];
                unset($data[''.$field.'_name']);
                unset($data[''.$field.'_id']); 
            
        }
        
        foreach($data as $key=>$value){
            if(!isset($labels[$key])) unset($data[$key]);
        }        
        foreach($this->sensitiveFields as $unset){
            unset($data[$unset]);
        }
        return $data;        
    }

    public function loadAssociatedFromDB($table,$id,$assoc='assoc',$sql=''){
        $primary_key = $this->many_to_many[0][$assoc]['this_fk_in_joining'];
        $loop_key = $this->many_to_many[0][$assoc]['fk_in_joining'];
        if($sql == ''){
            $res = $this->query("SELECT * FROM $table WHERE $primary_key='$id'");        
        }
        else{
            $res = $this->query($sql);              
        }
        print_r(mysqli_error($this->dbConn));
        
        if ( !$res )
        return false;

        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC)){
            $this->assocData[$table][$row[$loop_key]] = $row;
        }
        return $this->assocData;        
    }

    public function getDataForTable($sql){
        //print_r($sql);
        $query = mysqli_query($this->dbConn,$sql);
        echo mysqli_error($this->dbConn);
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            $this->dataTable[] = $row;
            //print_r($row);
        } 
        return $this->dataTable;        
    }
    
    public function insert($data, $db_conx='', $isManual=false){
        
        if($db_conx != ''){
            $this->dbConn = $db_conx;
        }

        $return = array();
        if (!is_array($data)) $this->error('Data is not an array', __LINE__);
        
        $getFields = $this->getFields();
        
        
//// Check if there was a checkbox and if it was unckecked, it will not show in the $_POST ... SET ITS VALUE TO ZERO        
        foreach($getFields['new-fields'] as $tempField=>$tempVal){
            if($tempVal['type'] == 'checkbox' && !isset($data[$tempField])){
                $data[$tempField] = 0;
            }
        }

        
///// Check Dependency
///// Check for default Values if empty
///// Add isManualEntry Variable
        $getLabels = $this->getLabels();
        foreach($this->dependency as $depField=>$depValue){
            if(isset($data[$depField])){
                if(isset($depValue[$data[$depField]]['hide'])){
                    $hidden = $depValue[$data[$depField]]['hide'];
                    
                    foreach($hidden as $hide){
                        if(isset($getLabels[$hide])){
                            $data[$hide] = '';
                        }
                        else{
                            unset($data[$hide]);
                        }
                        
                    }                
                }
            }
        }
    
        if(!$isManual && isset($getFields['default-insert'])){
            foreach($getFields['default-insert'] as $defF=>$defV){
                
                $data[$defF] = replaceBetweenBraces($data,$defV);
            }
        } 
        foreach ($data as $k => $v ) $data[$k] = "'".$this->escape($v)."'";
        
       
        $sql = "INSERT INTO $this->dbTable (`".implode('`, `', array_keys($data))."`) VALUES (".implode(", ", $data).")";
        $this->query($sql);
        $return['id'] = mysqli_insert_id($this->dbConn);
        $return['db_error'] = $this->errorLog;
        $return['message'] = "New Entry Saved";
        $return['sql'] = $sql;

        return $return;
    }

    public function update($properties) {
        $return = array();
        $getFields = $this->getFields();
        
        
        //// Check if there was a checkbox and if it was unckecked, it will not show in the $_POST ... SET ITS VALUE TO ZERO        
        foreach($getFields['new-fields'] as $tempField=>$tempVal){
            if($tempVal['type'] == 'checkbox' && !isset($properties[$tempField])){
                $properties[$tempField] = 0;
            }
        }
        
        $getLabels = $this->getLabels();        
        foreach($this->dependency as $depField=>$depValue){
            if(isset($properties[$depField])){
                if(isset($depValue[$properties[$depField]]['hide'])){
                    $hidden = $depValue[$properties[$depField]]['hide'];
                    
                    foreach($hidden as $hide){
                        if(isset($getLabels[$hide])){
                            $properties[$hide] = '';
                        }
                        else{
                            unset($properties[$hide]);
                        }                        
                    }                
                }
            }            

        } 
        
        if(is_array($properties) && count($properties) > 0) {
            $id = $properties['id'];
            unset($properties['id']);
            $i=1;
            $query = "UPDATE `".$this->dbTable."` SET ";
            $c = count($properties);//a small optimization :)
            foreach($properties AS $k => $v) {

                $query .= '`'.$this->escape($k)."` = '".$this->escape($v)."'".(($i++ < $c) ? ', ' : ' ');
            }
            $query .= "WHERE `id` = '".$id."'";
            $return['query'] = mysqli_query($this->dbConn, $query); 
        }
        return $return;
    }

    public function bulkUpdate($data) {
        $return = array();
        $ids = $data['ids'];
        $properties = $data[$this->dbTable];
     
        if(is_array($properties) && count($properties) > 0) {
            $properties = array_filter($properties,'strlen');
            $i=1;
            $query = "UPDATE `".$this->dbTable."` SET ";
            $c = count($properties);
            foreach($properties AS $k => $v) {

                $query .= '`'.$this->escape($k)."` = '".$this->escape($v)."'".(($i++ < $c) ? ', ' : ' ');
            }
            $query .= "WHERE `id` IN (".implode(", ", $ids).")";
            //echo $query;
            $return['query'] = mysqli_query($this->dbConn, $query); 
        }
        return $return;
    }

    public function delete($id){
        $return = array();
        $sql = "DELETE FROM $this->dbTable WHERE id='$id'";
        $query = mysqli_query($this->dbConn,$sql);
        $return['query'][] = $query;
        $return['sql'][] = $sql;
        foreach($this->delete_dependency as $del => $fk){
            $sql = "DELETE FROM $del WHERE $fk='$id'";
            $query1 = mysqli_query($this->dbConn,$sql); 
            $return['query'][] = $query1;
            $return['sql'][] = $sql;            
        } 
        
/*         $affected_tables = $this->getAffectedTables();
        foreach($affected_tables as $affected_table){
            $sql = "DELETE FROM $affected_table[table] WHERE $affected_table[fk]='$id'";
            $query1 = mysqli_query($this->dbConn,$sql); 
            $return['query'][] = $query1;
            $return['sql'][] = $sql;            
        }  */
        
/*         $foreign_keys = $this->getForeignKeys();
        foreach($foriegn_keys as $foreign_key){
            $sql = "DELETE FROM $del WHERE $fk='$id'";
            $query1 = mysqli_query($this->dbConn,$sql); 
            $return['query'][] = $query1;
            $return['sql'][] = $sql;            
        }  */
        
       return $return;
    }

    public function deleteBulk($ids){
        $return = array();
        foreach($ids as $id){
            $sql = "DELETE FROM $this->dbTable WHERE id='$id'";
            $query = mysqli_query($this->dbConn,$sql);
            $return['query'][] = $query;
            $return['sql'][] = $sql;
            foreach($this->delete_dependency as $del => $fk){
                $sql = "DELETE FROM $del WHERE $fk='$id'";
                $query1 = mysqli_query($this->dbConn,$sql); 
                $return['query'][] = $query1;
                $return['sql'][] = $sql;            
            }             
        }

       return $return;
    }    

    public function is($prop){
        return $this->get_property($prop)==1?true:false;
    }

    public function get_property($property) {
        if (empty($this->dbID)) $this->error('No user is loaded', __LINE__);
        if (!isset($this->dbData[$property])) $this->error('Unknown property <b>'.$property.'</b>', __LINE__);
        return $this->dData[$property];
    }

    public function getForeignKeys(){
        return array();
    }
    
    public function generateSqlPullData(){
        
/*                  'productid'=>array(
                        'relationship'=>'many-to-one',
                        'table'=>'products',
                        'join'=>'LEFT JOIN',
                        'as'=>'p',
                        'id'=>'p.id',		
                        'join2'=>true,		
                        'name'=>array(
                            'product'=>
                                    array(
                                        'relationship'=>'many-to-one',
                                        'table'=>'product_items',
                                        'join'=>'LEFT JOIN',
                                        'as'=>'pi',
                                        'id'=>'pi.id',		
                                        'name'=>'pi.name'		
                                        )
                                    )                                        
                    )  */      
                    
        $arr = $this->getForeignKeys();
        //print_r($arr);
        if(empty(array_filter($arr))){      
            $sql = "SELECT main.* FROM `$this->dbTable` as main"; 
        }
        else{
            $sql = 
            "
            SELECT main.*,";
            $selectTemp = "";
            $fromTemp = "";
            $whereTemp = "";
            
            foreach($arr as $key=>$value){
                
                if(isset($value['join2']) && $value['join2']){
                    $selectTemp .= 
                    "
                    $value[id] as ${key}_id,";
                    $fromTemp .= 
                    "
                    $value[join] $value[table] as $value[as] ON main.$key = $value[id]";                         
                    $tempRetData = $this->loopGenerateSqlPullData($key,$value);
                    $selectTemp .= $tempRetData['select'];
                    $fromTemp .= $tempRetData['from'];
                }
                else{
                    $selectTemp .= 
                    "
                    $value[id] as ${key}_id,$value[name] as ${key}_name,";
                    $fromTemp .= 
                    "
                    $value[join] $value[table] as $value[as] ON main.$key = $value[id]";                    
                }
            }
        
            $selectTemp = rtrim($selectTemp, ',');
            $sql .=
            "
            $selectTemp
            FROM $this->dbTable  as main      
            $fromTemp
            ";
        }
        //print_r($sql);
        return $sql;
    }    

    public function loopGenerateSqlPullData($field,$arr){

        $selectTemp = "";
        $fromTemp = "";
        $whereTemp = "";
        
            foreach($arr['name'] as $key=>$val){
                
                if(isset($val['join2']) && $val['join2']){
                    $tempRetData = $this->loopGenerateSqlPullData($val);
                    $selectTemp .= $tempRetData['select'];
                    $fromTemp .= $tempRetData['from'];
                }
                else{
                    $value = $val;
                    $selectTemp .= 
                    "
                    $value[id] as ${key}_id,$value[name] as ${field}_name,";
                    $fromTemp .= 
                    "
                    $value[join] $value[table] as $value[as] ON $arr[as].$key = $value[id]";                    
                }
            }
        $sql['select'] = $selectTemp;
        $sql['from'] = $fromTemp;
        $sql['where'] = $whereTemp;
        return $sql;
    }

    public function getSelectList($type=''){
        $arr = array();
        $sql = "";        
        if($type == ''){
            $tempF = $this->config;            
            $sql = "SELECT $tempF[id],$tempF[name] FROM $this->dbTable";
        }
        else{
            $temp = $this->getForeignKeys();
            $tempF = isset($temp[$type])?$temp[$type]:'';
            if(isset($temp[$type])){
                $sql = "SELECT $tempF[id],$tempF[name] FROM $tempF[table] as $tempF[as]";
            }
            else{
                $sql = "";
            }
        }
        
        if($sql != ''){
            $query = mysqli_query($this->dbConn, $sql);
            if($query){
                while($row = mysqli_fetch_array($query)){
                    $arr[$row[0]] = $row[1];
                }            
            }            
        }
        else{
      
            return false;
        }
        //print_r($sql);
        //print_r(mysqli_error($this->dbConn));     
        return $arr;                
    }

   
    public function getAjaxDependentOptions($value){
        $aDep = $this->ajax_dependency[$value];
        $i = 0;
        foreach($aDep as $depElem=>$depOpt){
            eval($depOpt['code']);
            $return[''.$value.'-'.$i.''] = $element; 
            $i++;            
        }
        return $return;
    }

    function DeleteDirectoryAndContents($path)
    {
        if (is_dir($path) === true)
        {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file)
            {
                if (in_array($file->getBasename(), array('.', '..')) !== true)
                {
                    if ($file->isDir() === true)
                    {
                        rmdir($file->getPathName());
                    }

                    else if (($file->isFile() === true) || ($file->isLink() === true))
                    {
                        unlink($file->getPathname());
                    }
                }
            }

            return rmdir($path);
        }

        else if ((is_file($path) === true) || (is_link($path) === true))
        {
            return unlink($path);
        }

        return false;
    }
    
    ////////////////////////////////////////////
    // PRIVATE FUNCTIONS
    ////////////////////////////////////////////

    protected function query($sql, $line = 'Uknown') {
        //if ($this->DEVELOPMENT_MODE ) echo '<b>Query to execute: </b>'.$sql.'<br /><b>Line: </b>'.$line.'<br />';
        $this->errorLog .= '<b>Query to execute: </b>'.$sql.'<br /><b>Line: </b>'.$line.'<br />';
        $res = mysqli_query($this->dbConn, $sql);
        if ( !$res )
        $this->error(mysqli_error($this->dbConn), $line);
        return $res;
    }

    protected function getSingleRowFromDB($sql) {
        $query = mysqli_query($this->dbConn,$sql);
        //echo mysqli_error($this->dbConn);
        //echo $sql;
        $row = array();
        if($query){
            $row = mysqli_fetch_array($query, MYSQLI_ASSOC);           
        }

        return $row;
    }
    /**
    * Produces the result of addslashes() with more safety
    * @access private
    * @param string $str
    * @return string
*/  
    protected function escape($str) {
        $str = get_magic_quotes_gpc()?stripslashes($str):$str;
        $str = mysqli_real_escape_string($this->dbConn, $str);
        return $str;
    }

    /**
    * Error holder for the class
    * @access private
    * @param string $error
    * @param int $line
    * @param bool $die
    * @return bool
*/  
    protected function error($error, $line = '', $die = false) {
        //if ( $this->displayErrors )
        $this->errorLog .=  '<b>Error: </b>'.$error.'<br /><b>Line: </b>'.($line==''?'Unknown':$line).'<br />';
        if ($die) exit;
        return false;
    }

    protected function getSelectOptions($sql) {
/*         $backtrace = debug_backtrace();

        print_r( $backtrace ); */
        $arr = array();
        $query = mysqli_query($this->dbConn, $sql);
        //print_r(mysqli_error($this->dbConn));
        if($query){
            while($row = mysqli_fetch_array($query)){
                //$arr[$row[$this->config['id']]] = replaceBetweenBraces($row,$this->config['name']);
                $temp_name = $row[1];
                $temp_name .= isset($row[2]) ? '-'.$row[2]:'';
                $temp_name .= isset($row[3]) ? '-'.$row[3]:'';
                $arr[$row[0]] = $temp_name;
            }            
        }
        return $arr;
    }

    protected function getSelectOptionsCommon($table, $cond='live=\'1\'') {
        $where = ($cond != '') ? 'WHERE '.$cond.'' : '';
        $arr = array();
        $sql = "SELECT id, name FROM $table $where";
        $query = mysqli_query($this->dbConn, $sql);
        if($query){
            while($row = mysqli_fetch_array($query)){
                $arr[$row[0]] = $row[1];
            }            
        }

        return $arr;
    }

    protected function getSelectOptionsRange($num) {
        $arr = array();
        for($i=1;$i<=$num;$i++){
            $arr[$i] = $i;    
        }
        return $arr;
    }

}
?>
