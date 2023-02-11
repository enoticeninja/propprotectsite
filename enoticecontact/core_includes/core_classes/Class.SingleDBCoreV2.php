<?php
class SingleDBCoreV2 extends SingleDBAjaxV2
{

    //public $fields = array();
    public $table = array();
    public $delete_dependency = array();
    public $dependency = array();
    public $ajax_dependency = array();
    public $sensitiveFields = array();
    public $associations = array();
    public $many_to_many = array();
    public $bulk_update_fields = array();
    //public $wrappers = array();
    //public $default_insert = array();      
    /*Do not edit after this line*/
    public $dbID = 0;
    //public $assocForm = false;
    public $dbConn;
    public $dbConnPDO;
    public $dbTable = '';
    public $common_title = '';
    public $oto_fk = '';
    //public $selectListSql = '';
    public $dbData = array();
    public $dbDataMany = array();
    public $dbForeignID = 0;
    //public $assocData = array();
    //public $dataTable = array();
    //public $assoc_with_same_table = 'assoc';
    public $DEVELOPMENT_MODE = false;
    private $displayErrors = false;
    public $user_ok = false;
    public $errorLog;
    public $sql = "";
    public $has_user_levels = false;   

    public function Common($db_conx = '') {
        $this->sql = "SELECT * FROM $this->dbTable";
    }

	public function switchToPDO(){
		global $db_servername, $db_name, $db_username, $db_password;
		if($this->dbConn) mysqli_close($this->dbConn);
		 $this->dbConnPDO = new PDO("mysql:host=$db_servername;dbname=$db_name", $db_username, $db_password);
		 return $this->dbConnPDO;
	}
	
    public function loadFromDB($id,$fk='') {
		$sqlMain = (trim($this->sql) != '') ? $this->sql : $this->generateSqlPullData();
        //print_r($sqlMain);        
        if($fk != ''){
            $sql = "$sqlMain AND main.$fk='$id' LIMIT 1";        
        }        
        else if($this->oto_fk != ''){
            $fk = $this->oto_fk;
            $sql = "$sqlMain AND main.$fk='$id' LIMIT 1";        
        }
        else{
            if($this->sql != ''){
                $sql = "$this->sql AND main.{$this->config['id']}='$id'";         
            }
            else{
                $sql = "$sqlMain AND main.{$this->config['id']}='$id' LIMIT 1";
                //print_r($sql);                
            }            
        }
        $res = $this->query($sql);

        $this->print_mysqli_error();
        if (!$res) return false;
        $this->dbData = mysqli_fetch_array($res, MYSQLI_ASSOC);
        $this->dbID = $this->dbData[$this->config['id']];
 
        //print_r($this->dbData);
        return $this->dbData;
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

            $sql = "SELECT main.*  FROM `$this->dbTable` as main";
        }
        else{
            $sql = 
            "
            SELECT main.*,
            ";
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
                    $value[join] `$value[table]` as $value[as] ON main.$key = $value[id]";                         
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
            FROM `$this->dbTable`  as main      
            $fromTemp
            ";
        }
        $sql .= 
        " 
            WHERE 1
            ";
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

    
    public function numRows($sql){
        $query = mysqli_query($this->dbConn, $sql);
        $row = mysqli_num_rows($query);
		if(mysqli_error($this->dbConn)){
			print_r(mysqli_error($this->dbConn));
			print_r($sql);
		}
        return $row;
    }
	
    public function getData($id){
        $data = $this->loadFromDB($id);
        $data = $this->filterDataToShow($data);
        return $data;
    }

    public function loadAllFromDB($id,$fk) {
        $sql = $this->generateSqlPullData();  
        $sql .= " AND main.$fk='$id'"; 
        $this->dbForeignID = $id;
        //print_r($sql);        
        $res = $this->query($sql);
        //print_r($res);
        //print_r(mysqli_error($this->dbConn));
        if ( mysqli_num_rows($res) == 0 )
        return false;
        
        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC)){
            
            $this->dbDataMany[$row[$this->config['id']]] = $row;
            $return[$row[$this->config['id']]] = $row;
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
  
    public function prepareInsert($data){
        return $data;
    }

	public function prepInsertData($data){
		return $data;
	}
	
    public function insert($data, $db_conx='', $isManual=false){
        
        if($db_conx != ''){
            $this->dbConn = $db_conx;
        }

        $return = array();
        if (!is_array($data)) $this->error('Data is not an array', __LINE__);

        foreach ($data as $k => $v ) $data[$k] = "'".$this->escape($v)."'";
		
        $sql = "INSERT INTO $this->dbTable (`".implode('`, `', array_keys($data))."`) VALUES (".implode(", ", $data).")";
        $this->query($sql);
        $return['id'] = mysqli_insert_id($this->dbConn);
        $return['db_error'] = $this->errorLog;
        $return['message'] = "New Entry Saved";
        $return['sql'] = $sql;

        return $return;
    }

		
    public function insertEx($form_data, $db_conx='', $isManual=false,$form_config){

        if($db_conx != ''){
            $this->dbConn = $db_conx;
        }

        if (!is_array($form_data)) $this->error('Form Data Passed is not an array', __LINE__);

/*         print_r($form_data);
		exit(); */
        $data = $form_data[$form_config['name_prefix']];
		$data = $this->prepInsertData($data,$form_data['unique_id']);
        $return = array();
        if (!is_array($data)) $this->error('Data is not an array', __LINE__);

        $form_fields= $this->getAllFields();
        //print_r($form_fields);
        //// Check if there was a checkbox and if it was unckecked, it will not show in the $_POST ... SET ITS VALUE TO ZERO
        foreach($form_config['fields'] as $tempField){
            $tempVal = $form_fields['fields'][$tempField];
            if($tempVal['type'] == 'checkbox' && !isset($data[$tempField])){
                $data[$tempField] = getDefault($tempVal['other_value'],0);
            }
            if($tempVal['type'] == 'multiple-select'){
                $data[$tempField] = implode(''.$tempVal['seperator'].'',$data[$tempField]);
            }            
        }

        
    ///// Check Dependency
    ///// Check for default Values if empty
    ///// Add isManualEntry Variable
        $getLabels = $this->getLabels();
        foreach($form_config['dependency']as $depField=>$depValue){
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
    
            if(!$isManual && $form_config['default_insert']['enabled']){
                
                foreach($form_config['default_insert']['fields'] as $defF=>$defV){
                    
                    $data[$defF] = replaceBetweenBraces($data,$defV);
                    
                }
            }

        $final_data = array();
        //print_r($data);
        foreach ($data as $k => $v ) $final_data[$k] = "'".$this->escape($v)."'";
        
       
        $sql = "INSERT INTO $this->dbTable (`".implode('`, `', array_keys($final_data))."`) VALUES (".implode(", ", $final_data).")";
        $this->query($sql);
        $return['id'] = mysqli_insert_id($this->dbConn);
        $return['db_error'] = $this->errorLog;
        $return['message'] = "New Entry Saved";
        $return['sql'] = $sql;
        $return['data'] = $data;
        $return['jsFunction'] = '';

        return $return;
    }  

	public function insertNestedLoop($data,$form_config_array){
		
		/// WE DONT KNOW WHICH LEVEL WE ARE IN THE NESTED LOOP
		/// CHILD CONFIG IS AN ARRAY SO
		foreach($form_config_array as $child_key=>$form_config){
			
		}
		
		foreach($data as $key => $value){
			$event['job_id'] = $job_id;
			$event['company_id'] = $company_id;
			$sql = "INSERT INTO $form_config[db_table] (`".implode('`, `', array_keys($event))."`) VALUES (:".implode(",:", array_keys($event)).")";
			$stmt = $conn->prepare($sql);
			$stmt->execute($event);    
			$event_id = $conn->lastInsertId(); 
		}				
	}
	
    public function insertMultiple($form_data, $db_conx='', $isManual=false,$form_config){

        $data_multiple = $form_data[$form_config['name_prefix']];
        $return = array();
        $form_fields= $this->getAllFields();
        //print_r($form_fields);
              
        foreach($data_multiple as $data){
			$data = $data['form_data'];
            $data = $this->prepareInsert($data);
            //// Check if there was a checkbox and if it was unckecked, it will not show in the $_POST ... SET ITS VALUE TO ZERO        
            foreach($form_config['fields'] as $tempField){
                $tempVal = $form_fields['fields'][$tempField];
                if($tempVal['type'] == 'checkbox' && !isset($data[$tempField])){
                    $data[$tempField] = getDefault($tempVal['other_value'],0);
                }
            }

            ///// Check Dependency
            ///// Check for default Values if empty
            ///// Add isManualEntry Variable
            $getLabels = $this->getLabels();
            foreach($form_config['dependency']as $depField=>$depValue){
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
        
            if(!$isManual && $form_config['default_insert']['enabled']){
                
                foreach($form_config['default_insert']['fields'] as $defF=>$defV){
                    
                    $data[$defF] = replaceBetweenBraces($data,$defV);
                    
                }
            }

            $final_data = array();
            //print_r($data);
            foreach ($data as $k => $v ) $final_data[$k] = "'".$this->escape($v)."'";
            $sqlTemp[] = " ('".implode("', '", $final_data)."')";
        }
       
        $sql = "INSERT INTO {$this->dbTable} (".implode(",", array_keys($data)).") VALUES";  
        $sql .= implode(',',$sqlTemp);
        print_r($sql);
        //$this->query($sql);
        $return['id'] = 1;
        //$return['id'] = mysqli_insert_id($this->dbConn);
        $return['db_error'] = $this->errorLog;
        $return['message'] = "New Entry Saved";
        $return['sql'] = $sql;
        $return['data'] = $data;
        $return['jsFunction'] = '';

        return $return;
    }
 

    public function updateMultiple($form_data, $db_conx='', $isManual=false,$form_config){
        $data_multiple = $form_data[$form_config['name_prefix']];
        $return = array();
        $form_fields= $this->getAllFields();
        //print_r($form_fields);
        $multiple_sql = array();   
        foreach($data_multiple as $data){
			$data = $data['form_data'];
            $data = $this->prepareInsert($data);
            //// Check if there was a checkbox and if it was unckecked, it will not show in the $_POST ... SET ITS VALUE TO ZERO        
            foreach($form_config['fields'] as $tempField){
                $tempVal = $form_fields['fields'][$tempField];
                if($tempVal['type'] == 'checkbox' && !isset($data[$tempField])){
                   $data[$tempField] = getDefault($tempVal['other_value'],0);
                }
            }

            $final_data = array();
            foreach ($data as $k => $v ) $final_data[$k] = $this->escape($v);
            $sqlTemp[] = " ('".implode("', '", $final_data)."')";
        }
        
        $sql = "INSERT INTO {$this->dbTable} (".implode(",", array_keys($data)).") VALUES ";  
        $sql .= implode(',',$sqlTemp);

        $i=1;
        $c = count($data);//a small optimization :) 
        $sql .= ' ON DUPLICATE KEY UPDATE '; 
        
        foreach($data AS $k => $v) {
            $sql .= ' '.$k." = VALUES(".$k.")".(($i++ < $c) ? ', ' : ' ');
        }
            
        //print_r($sql);
        $this->query($sql);
        $return['id'] = 1;
        //$return['id'] = mysqli_insert_id($this->dbConn);
        $return['db_error'] = $this->errorLog;
        $return['message'] = "New Entry Saved";
        $return['sql'] = $sql;
        $return['data'] = $data;
        $return['jsFunction'] = "";

        return $return;
    }

	public function prepUpdateData($data){
		return $data;
	}
	
    public function update($properties) {
        $return = array();
        
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

    public function updateEx($form_data,$form_config) {
        if (!is_array($form_data)) $this->error('Form Data Passed is not an array', __LINE__);
        $data = $form_data[$form_config['name_prefix']];
        $data = $this->prepUpdateData($data,$form_data['unique_id']);
        $return = array();
        if (!is_array($data)) $this->error('Data is not an array', __LINE__);

        $form_fields= $this->getAllFields();
        //// Check if there was a checkbox and if it was unckecked, it will not show in the $_POST ... SET ITS VALUE TO ZERO        
        foreach($form_config['fields'] as $tempField){
            $tempVal = $form_fields['fields'][$tempField];
            if($tempVal['type'] == 'checkbox' && !isset($data[$tempField])){
                $data[$tempField] = getDefault($tempVal['other_value'],0);
            }
            if($tempVal['type'] == 'multiple-select'){
                $data[$tempField] = implode(''.$tempVal['seperator'].'',$data[$tempField]);
            }
        }
        
        $getLabels = $this->getLabels();        
        foreach($form_config['dependency']as $depField=>$depValue){
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
        
        if(is_array($data) && count($data) > 0) {
            $id = $data[$this->config['id']];
            unset($data[$this->config['id']]);
            $i=1;
            $query = "UPDATE `".$this->dbTable."` SET ";
            $c = count($data);//a small optimization :)
            foreach($data AS $k => $v) {

                $query .= '`'.$this->escape($k)."` = '".$this->escape($v)."'".(($i++ < $c) ? ', ' : ' ');
            }
            $query .= "WHERE `{$this->config['id']}` = '".$id."'";
            $return['query'] = mysqli_query($this->dbConn, $query); 
        }
        $return['id'] = $id;
        $return['db_error'] = $this->errorLog;
        $return['message'] = "New Entry Saved";
        $return['sql'] = $query;
        $return['data'] = $data;
        $return['jsFunction'] = "";      
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
            $query .= "WHERE `{$this->config['id']}` IN (".implode(", ", $ids).")";
            //echo $query;
            $return['query'] = mysqli_query($this->dbConn, $query); 
        }
        return $return;
    }

    public function delete($id){
        $return = array();
        $sql = "DELETE FROM `$this->dbTable` WHERE {$this->config['id']}='$id'";
        $query = mysqli_query($this->dbConn,$sql);
        $return['query'][] = $query;
        $return['sql'][] = $sql;
        foreach($this->delete_dependency as $del => $fk){
            $sql = "DELETE FROM $del WHERE $fk='$id'";
            $query1 = mysqli_query($this->dbConn,$sql); 
            $return['query'][] = $query1;
            $return['sql'][] = $sql;            
        } 
       return $return;
    }

    public function deleteBulk($ids){
        $return = array();
        foreach($ids as $id){
            $sql = "DELETE FROM $this->dbTable WHERE {$this->config['id']}='$id'";
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
    protected function print_mysqli_error() {
        print_r(mysqli_error($this->dbConn));
        return false;
    }
    protected function dberror() {
        print_r(mysqli_error($this->dbConn));
        return false;
    }

    protected function getSelectOptions($sql) {
/*         $backtrace = debug_backtrace();

        print_r( $backtrace ); */
        $arr = array();
        $query = mysqli_query($this->dbConn, $sql);
        //print_r(mysqli_error($this->dbConn));
        if($query){
            while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
				print_r($row);
                //$arr[$row[$this->config['id']]] = replaceBetweenBraces($row,$this->config['name']);
                $temp_name = $row[1];
                $temp_name .= isset($row[2]) ? '-'.$row[2]:'';
                $temp_name .= isset($row[3]) ? '-'.$row[3]:'';
                $arr[$row[0]] = $temp_name;
            }            
        }
        return $arr;
    }

    protected function getTablesInDB() {
		$sql = "SHOW TABLES FROM ".DB_NAME."";
        $arr = array();
        $query = mysqli_query($this->dbConn, $sql);
        //print_r(mysqli_error($this->dbConn));
        if($query){
            while($row = mysqli_fetch_array($query)){
				//print_r($row);
                $arr[$row[0]] = $row[0];
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
