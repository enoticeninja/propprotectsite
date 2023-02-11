<?php
class CoreDB
{
    public $table = array();
    public $delete_dependency = array();
    public $dependency = array();
    public $ajax_dependency = array();
    public $sensitiveFields = array();
    public $associations = array();
    public $many_to_many = array();
    public $bulk_update_fields = array();
    public $formatTableData = array();
    //public $wrappers = array();
    public $default_insert = array();
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
    public $rowsCountDisplay = 10;
    public $has_notifications = false;

    public function Common($db_conx = '')
    {
        $this->sql = "SELECT * FROM $this->dbTable";
    }
	function callApi($dbData, $endPoint)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, API_SITE_PATH . $endPoint);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dbData));
		$response = curl_exec($ch);
	 	/* echo "endPoint".$endPoint;
		echo "dbData";
		print_r($dbData);  */
		/* echo "response:::::::";
		print_r($response); 
		echo "End response:::::::"; */
		curl_close($ch);
		$responseArr = json_decode($response, true);
		//$responseArr = array_reverse($responseArr);
		//$responseArr = $response;
		return $responseArr;
	}

	function callApiJson($dbData, $endPoint)
	{
		$ch = curl_init();
		/* print_r(API_SITE_PATH. $endPoint);
	print_r($dbData); */
		curl_setopt($ch, CURLOPT_URL, API_SITE_PATH . $endPoint);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dbData));
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$response = curl_exec($ch);
		/* echo "callApiJson response";
		print_r($response); */
		curl_close($ch);

		$responseArr = json_decode($response, true);
		return $responseArr;
	}
    public function switchToPDO()
    {
        global $db_servername, $db_name, $db_username, $db_password;
        if ($this->dbConn) {
            mysqli_close($this->dbConn);
        }

        $this->dbConnPDO = new PDO("mysql:host=$db_servername;dbname=$db_name", $db_username, $db_password);
        return $this->dbConnPDO;
    }

    public function loadFromDB($id, $fk = '')
    {
        $sqlMain = (trim($this->sql) != '') ? $this->sql : $this->generateSqlPullData();
        //print_r($sqlMain);
        if ($fk != '') {
            $sql = "$sqlMain AND main.$fk='$id' LIMIT 1";
        } else if ($this->oto_fk != '') {
            $fk = $this->oto_fk;
            $sql = "$sqlMain AND main.$fk='$id' LIMIT 1";
        } else {
            if ($this->sql != '') {
                $sql = "$this->sql AND main.{$this->config['id']}='$id'";
            } else {
                $sql = "$sqlMain AND main.{$this->config['id']}='$id' LIMIT 1";
                //print_r($sql);
            }
        }
        $res = $this->query($sql);

        $this->print_mysqli_error();
        if (!$res) {
            return false;
        }

        $this->dbData = mysqli_fetch_array($res, MYSQLI_ASSOC);
        $this->dbID = $this->dbData[$this->config['id']];

        //print_r($this->dbData);
        return $this->dbData;
    }

    public function generateSqlPullData()
    {

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

        if (empty(array_filter($arr))) {

            $sql = "SELECT main.*  FROM `$this->dbTable` as main";
        } else {
            $sql =
                "
            SELECT main.*,
            ";
            $selectTemp = "";
            $fromTemp = "";
            $whereTemp = "";

            foreach ($arr as $key => $value) {

                if (isset($value['join2']) && $value['join2']) {
                    $selectTemp .=
                        "
                    $value[id] as ${key}_id,";
                    $fromTemp .=
                        "
                    $value[join] `$value[table]` as $value[as] ON main.$key = $value[id]";
                    $tempRetData = $this->loopGenerateSqlPullData($key, $value);
                    $selectTemp .= $tempRetData['select'];
                    $fromTemp .= $tempRetData['from'];
                } else {
                    $selectTemp .=
                        "
                    $value[id] as ${key}_id,$value[name] as ${key}_name,";

                    if (isset($value['other_fields']) && !empty(array_filter($value['other_fields']))) {
                        $selectTemp .= implode(',', $value['other_fields']);
                    }
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

    public function loopGenerateSqlPullData($field, $arr)
    {

        $selectTemp = "";
        $fromTemp = "";
        $whereTemp = "";

        foreach ($arr['name'] as $key => $val) {

            if (isset($val['join2']) && $val['join2']) {
                $tempRetData = $this->loopGenerateSqlPullData($val);
                $selectTemp .= $tempRetData['select'];
                $fromTemp .= $tempRetData['from'];
            } else {
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

    public function numRows($sql)
    {
        $query = mysqli_query($this->dbConn, $sql);
        $row = mysqli_num_rows($query);
        if (mysqli_error($this->dbConn)) {
            print_r(mysqli_error($this->dbConn));
            print_r($sql);
        }
        return $row;
    }

    public function getData($id)
    {
        $data = $this->loadFromDB($id);
        $data = $this->filterDataToShow($data);
        return $data;
    }
    public function loadAllFromDb() {
        $sql = $this->generateSqlPullData();      
        $res = $this->query($sql);
        if ( mysqli_num_rows($res) == 0 ) return false;

        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC)){
            $this->dbDataMany[$row[$this->config['id']]] = $row;
            $return[$row[$this->config['id']]] = $row;
        }
        return $return;
    }

    public function loadAllFromDbFk($fkData)
    {
        $sql = $this->generateSqlPullData();

        foreach($fkData as $fk=>$fVal){
            $sql .= " AND main.$fk='$fVal'";
        }
        
       /// $this->dbForeignID = $id;
        //print_r($sql);
        $res = $this->query($sql);
        //print_r($res);
        //print_r(mysqli_error($this->dbConn));
        if (mysqli_num_rows($res) == 0) {
            return false;
        }

        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
            //$this->dbDataMany[$row[$this->config['id']]] = $row;
            $return[$row[$this->config['id']]] = $row;
        }
        return $return;
    }
    public function loadAllFromDbFkold($id, $fk)
    {
        $sql = $this->generateSqlPullData();
        $sql .= " AND main.$fk='$id'";
        $this->dbForeignID = $id;
        //print_r($sql);
        $res = $this->query($sql);
        //print_r($res);
        //print_r(mysqli_error($this->dbConn));
        if (mysqli_num_rows($res) == 0) {
            return false;
        }

        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
            //$this->dbDataMany[$row[$this->config['id']]] = $row;
            $return[$row[$this->config['id']]] = $row;
        }
        return $return;
    }

    public function filterDataToShow($data)
    {
        $fKeys = $this->getForeignKeys();
        $labels = $this->getLabels();
        foreach ($fKeys as $field => $fieldData) {
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
            $data[$field] = $data['' . $field . '_name'];
            unset($data['' . $field . '_name']);
            unset($data['' . $field . '_id']);

        }

        foreach ($data as $key => $value) {
            if (!isset($labels[$key])) {
                unset($data[$key]);
            }

        }
        foreach ($this->sensitiveFields as $unset) {
            unset($data[$unset]);
        }
        return $data;
    }

    public function loadAssociatedFromDB($table, $id, $assoc = 'assoc', $sql = '')
    {
        $primary_key = $this->many_to_many[0][$assoc]['this_fk_in_joining'];
        $loop_key = $this->many_to_many[0][$assoc]['fk_in_joining'];
        if ($sql == '') {
            $res = $this->query("SELECT * FROM $table WHERE $primary_key='$id'");
        } else {
            $res = $this->query($sql);
        }
        print_r(mysqli_error($this->dbConn));

        if (!$res) {
            return false;
        }

        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
            $this->assocData[$table][$row[$loop_key]] = $row;
        }
        return $this->assocData;
    }

    public function getDataForTable($sql)
    {
        //print_r($sql);
        $this->dataTable = array();
        $query = mysqli_query($this->dbConn, $sql);
        echo mysqli_error($this->dbConn);
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            
            if(!empty($this->formatTableData)){
                foreach($this->formatTableData as $field=>$format){
                    if(isset($row[$field])){
                        switch($format['type']){
                            case 'transform_from_array':
                                if(!empty($row[$field]) || $row[$field] == '0'){
                                    $row[$field] = $format['transformation_array'][$row[$field]];
                                }
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
            $this->dataTable[] = $row;
        }
        return $this->dataTable;
    }

    public function insertNotification($data)
    {
        $date_created = date('Y-m-d H-i-s');
        //// Save notification
        {
            $notification_data = array(
                'notification_type' => $data['notification_type'],
                'notification_about_id' => $data['notification_about_id'],
                'notification_from_id' => get_user_id(),
                'notification_for' => $data['notification_for'],
                'notification_from' => $data['notification_from'],
                'notification_about' => $data['notification_about'],
                'date_created' => $date_created,
                'status' => '1',
                'total_similar_notifications' => 1,
            );
            $sql =
            "INSERT INTO `notification` (`" . implode('`, `', array_keys($notification_data)) . "`) VALUES ('" . implode("', '", $notification_data) . "')
		";
            $query = mysqli_query($this->dbConn, $sql);
            $return['query'] = $query;
            $return['db_error'] = mysqli_error($this->dbConn);
            return $return;
        }
    }

    public function prepareInsert($data)
    {
        return $data;
    }

    public function prepInsertData($data, $unique_id)
    {
        return $data;
    }

    public function afterInsert($data, $unique_id, $insert_id)
    {
        return array();
    }

    public function insert($data, $db_conx = '', $isManual = false)
    {
        if ($db_conx != '') {
            $this->dbConn = $db_conx;
        }

        $return = array();
        if (!is_array($data)) {
            $this->error('Data is not an array', __LINE__);
        }
        $data = $this->prepInsertData($data,0);
        //foreach ($data as $k => $v ) $data[$k] = "'".$this->escape($v)."'";
        foreach ($data as $k => $v) {
            $data[$k] = $this->escape($v);
        }

        $sql = "INSERT INTO $this->dbTable (`" . implode('`, `', array_keys($data)) . "`) VALUES ('" . implode("', '", $data) . "')";
        $this->query($sql);

        if (HAS_NOTIFICATIONS && $this->has_notifications) {
            $dataNot['notification_type'] = 'default';
            $dataNot['notification_about_id'] = $insert_id;
            $dataNot['notification_for'] = 'all';
            $dataNot['notification_from'] = '';
            $dataNot['notification_about'] = $this->dbTable;
            $this->insertNotification($dataNot);
        }
        $insert_id = mysqli_insert_id($this->dbConn);
        $return = $this->afterInsert($data, 0, $insert_id);

        $return['id'] = $insert_id;
        $return['db_error'] = $this->errorLog;
        $return['message'] = "New Entry Saved";
        $return['sql'] = $sql;
        return $return;
    }

    
    public function insertEx($form_data,$form_config){
        /* print_r($form_data);
        exit(); */ 
		/* print_r($_FILES); */
		//exit();
        if (!is_array($form_data)) $this->error('Form Data Passed is not an array', __LINE__);
        $data_multiple = array();
		//$data = $form_data;
		$has_form_config = false;
        if(!empty(array_filter($form_config))){
			//$data = $form_data;
			//if($form_config['name_prefix'] != '')$data_multiple[] = $form_data[$form_config['name_prefix']];
			$has_form_config = true;
		}
		
		//$data_multiple = $form_data['main'];
        $return = array();
        

        $form_fields= $this->getAllFields();
        //print_r($form_fields);
        $getLabels = $this->getLabels();
        $sqlTemp = array();
        
        $all_data = $form_data;
        $all_files = $_FILES;
        $insert_id = null;
        $has_multiple_select_non_csv = false;
        $multipleDataArray = array();
        //print_r($all_data['data'][$form_config['name_prefix']]['new']);
		foreach($all_data['data']['new'] as $kData=>$dataTemp){
            $data = $dataTemp['fields'];
            $currentUniqueId = $kData;
			//print_r($_FILES);
			//print_r($data);
            $is_required_ok = true;
            $req_return = array();            
            if($has_form_config){
                if(empty($form_config['insert_ids']))$form_config['insert_ids'] = array();
                foreach ($form_config['required'] as $tempField) {
					$fieldType = $form_config['all_fields']['fields'][$tempField]['type'];
					if($fieldType == 'upload_single_image' || $fieldType == 'upload_multiple_image' || $fieldType == 'upload_logo'){
						
					}
					else{							
						if($data[$tempField] == ''){
							$req_return['required_fileds'][] = $tempField;
							$is_required_ok = false;
						}
					}
                }//$is_required_ok = true;
                if(!$is_required_ok){
                    $req_return['ajaxresult'] = true;
                    $req_return['validation_error'] = true;
                    $req_return['error_type'] = 'required';
                    echo json_encode($req_return);
                    exit();
                }
                if(isset($form_config['prepare_data'])){
                    if(!($form_config['prepare_data'] == '')){
                        $data = call_user_func_array(array($this, $form_config['prepare_data']), array($dataTemp['fields'],$kData));
                    }
                }
                else{
                    $data = $this->prepInsertData($dataTemp['fields'],$kData);
                }

                
                ///// Check Dependency
                ///// Check for default Values if empty
                ///// Add isManualEntry Variable
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
            
                if(isset($form_config['default_insert']) && $form_config['default_insert']['enabled']){
                    foreach($form_config['default_insert']['fields'] as $defF=>$defV){
                        $data[$defF] = replaceBetweenBraces($data,$defV);
                    }
                }
            
                if(isset($form_config['non_db_fields'])){
                    foreach($form_config['non_db_fields'] as $defF=>$defV){
                        unset($data[$defV]);
                    }
                }

                foreach($form_config['fields'] as $tempField){
                    $tempVal = $form_fields['fields'][$tempField];
                    if($tempVal['type'] == 'checkbox' && !isset($data[$tempField])){
                        $data[$tempField] = getDefault($tempVal['other_value'],0);
                    }
                    if($tempVal['type'] == 'multiple-select'){
                        if(isset($tempVal['multiple_type']) && $tempVal['multiple_type'] == 'csv'){
                            $data[$tempField] = implode(''.$tempVal['seperator'].'',$data[$tempField]);
                        }
                        else{
                            //// TODO : this handles the case where ther is only one other key apart from the multiple select
                            $has_multiple_select_non_csv = true;
                            foreach($data[$tempField] as $tempKey => $tempMultipleData){
                                $multipleDataArray[$tempKey] = $data;
                                $multipleDataArray[$tempKey][$tempField] = $tempMultipleData;
                            }
                            
                        }
                    }            
                }                
            }

            if($has_multiple_select_non_csv)break;

            
            $final_data = array();
            if(isset($data['id']))unset($data['id']);////// INCASE OF THE LATEST FORMS, WHEN THE ID IS WITH THE NEW ROWS
            foreach ($data as $k => $v ) $final_data[$k] = "".$this->escape($v)."";
            //print_r($data);
            if(!empty($form_config['child_form'])  || !empty($form_config['after_save'])){
                $sql = "INSERT INTO {$this->dbTable} (".implode(",", array_keys($data)).") VALUES";
                $sql .= " ('".implode("', '", $final_data)."')";
                $this->query($sql);
                $insert_id = mysqli_insert_id($this->dbConn);
                //print_r($sql);
                //print_r(mysqli_error($this->dbConn));

                if(HAS_NOTIFICATIONS && $this->has_notifications){
                    $dataNot['notification_type'] = 'default';
                    $dataNot['notification_about_id'] = $insert_id;
                    $dataNot['notification_for'] = 'all';
                    $dataNot['notification_from'] = '';
                    $dataNot['notification_about'] = $this->dbTable;
                    $this->insertNotification($dataNot);
                }

                //$return[$insert_id] = $this->afterInsert($data,$currentUniqueId,$insert_id);
                if(!empty($form_config['after_save']))$return[$insert_id] = call_user_func_array(array($this, $form_config['after_save']), array($data,$currentUniqueId,$insert_id));
                $return['id'] = $insert_id;
                if(isset($form_config['is_multiple_form']) && $form_config['is_multiple_form'])$return['ids'][] = $insert_id;
                $return[$insert_id] ['id'] = $insert_id;
                $return[$insert_id] ['db_error'] = $this->errorLog;
                $return[$insert_id] ['message'] = "New Entry Saved";
                $return[$insert_id] ['sql'] = $sql;
                $return[$insert_id] ['ajaxresult'] = true;
                $return[$insert_id] ['data'] = $data;
                $return[$insert_id] ['jsFunction'] = '';
    
                if(!empty($form_config['child_form'])){
                    foreach($form_config['child_form'] as $child_module=>$child_form_config){
                        //$child_form_config = $child_config;
                        /* $child_form_config['is_child_form'] = true; */
                        $class = getAdapter($child_form_config['module']);
                        $child_form_config['insert_ids'] = $form_config['insert_ids'];
                        $child_form_config['insert_ids'][$form_config['module']] = $insert_id;
                        if(!empty($child_form_config['parent_ids'])){
                            foreach($child_form_config['parent_ids'] as $module=>$foreign_key){
                                $child_form_config['default_insert']['enabled'] = true;
                                $child_form_config['default_insert']['fields'][$foreign_key] = $child_form_config['insert_ids'][$module];
                            }
                        }


                        ////// EXTRA FOREIGN KEY DATA FROM TEH PARENT VALUES
                        if(!empty($child_form_config['foreign_key_from_parent_data'])){
                            foreach($child_form_config['foreign_key_from_parent_data'] as $key=>$foreign_key){
                                $child_form_config['default_insert']['enabled'] = true;
                                $child_form_config['default_insert']['fields'][$foreign_key] = $final_data[$key];
                            }
                        }
                        $return2 = $class->insertEx($dataTemp['children'][$child_module],$child_form_config);
                        $return[$insert_id]['children'][] = $return2;
                        //print_r($return2);
                    }
                }
            }
            else{
                $sqlTemp[] = " ('".implode("', '", $final_data)."')";
            }
        }

        if(empty($form_config['child_form']) && empty($form_config['after_save'])){
            if(!$has_multiple_select_non_csv){
                $sql = "INSERT INTO {$this->dbTable} (".implode(",", array_keys($data)).") VALUES";
                $sql .= implode(',',$sqlTemp);
                $this->query($sql);
                //print_r($sql);
                //print_r(mysqli_error($this->dbConn));
                $insert_id = mysqli_insert_id($this->dbConn);
                $return['id'] = $insert_id;
                if(isset($form_config['is_multiple_form']) && $form_config['is_multiple_form'])$return['ids'][] = $insert_id;
            }
            else{
                $return = $this->insertMultiple($multipleDataArray);
            }
        }
        return $return;
    }

    public function updateNew($form_data,$form_config){
        /* print_r($form_data);
        exit(); */ 
        //print_r($form_data); 
		//print_r($_FILES);
		//exit();
		$has_form_config = false;
        if(!empty(array_filter($form_config))){
			$has_form_config = true;
		}

        $return = array();
        $form_fields= $this->getAllFields();
        //print_r($form_fields);
        $getLabels = $this->getLabels();
        $sqlTemp = array();
        
        $all_data = $form_data;
        $insert_id = null;

        $has_multiple_select_non_csv = false;
        $multipleDataArray = array();

        if(!empty($all_data['data']['new'])){
            $class = getAdapter($form_config['module']);
            $form_config['insert_ids'][$form_config['module']] = $insert_id;
            /* if(!empty($child_form_config['parent_ids'])){
                foreach($child_form_config['parent_ids'] as $module=>$foreign_key){
                    $child_form_config['default_insert']['enabled'] = true;
                    $child_form_config['default_insert']['fields'][$foreign_key] = $child_form_config['insert_ids'][$module];
                }
            } */
            $returnNewData = $this->insertEx($all_data,$form_config);
            $return = $returnNewData + $return;
        }
        if(!empty($all_data['data']['old'])){
            foreach($all_data['data']['old'] as $kData=>$dataTemp){
                if(!empty($dataTemp['fields'])){
                    
                    if(isset($form_config['prepare_data'])){
                        if(!($form_config['prepare_data'] == '')){
                            $data = call_user_func_array(array($this, $form_config['prepare_data']), array($dataTemp['fields'],$kData,$insert_id));
                        }
                    }
                    else{
                        $data = $this->prepUpdateData($dataTemp['fields'],$kData,$insert_id);
                    }                    
                    $currentUniqueId = $kData;

                    $is_required_ok = true;
                    $req_return = array();            
                    if($has_form_config){
                        if(empty($form_config['insert_ids']))$form_config['insert_ids'] = array();
                        foreach ($form_config['required'] as $tempField) {
							$fieldType = $form_config['all_fields']['fields'][$tempField]['type'];
							if($fieldType == 'upload_single_image' || $fieldType == 'upload_multiple_image' || $fieldType == 'upload_logo'){
								
							}
							else{							
								if($data[$tempField] == ''){
									$req_return['required_fileds'][] = $tempField;
									$is_required_ok = false;
								}
                            }
                        }
                        if(!$is_required_ok){
                            $req_return['ajaxresult'] = true;
                            $req_return['validation_error'] = true;
                            $req_return['error_type'] = 'required';
                            echo json_encode($req_return);
                            exit();
                        }

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
                    
                        if(isset($form_config['non_db_fields'])){
                            foreach($form_config['non_db_fields'] as $defF=>$defV){
                                unset($data[$defV]);
                            }
                        }

                        foreach($form_config['fields'] as $tempField){
                            $tempVal = $form_fields['fields'][$tempField];
                            if($tempVal['type'] == 'checkbox' && !isset($data[$tempField])){
                                $data[$tempField] = getDefault($tempVal['other_value'],0);
                            }
                            if($tempVal['type'] == 'multiple-select'){
                                if(isset($tempVal['multiple_type']) && $tempVal['multiple_type'] == 'csv'){
                                    $data[$tempField] = implode(''.$tempVal['seperator'].'',$data[$tempField]);
                                }
                                else{
                                    //// TODO : this handles the case where ther is only one other key apart from the multiple select
                                    $has_multiple_select_non_csv = true;
                                    foreach($data[$tempField] as $tempKey => $tempMultipleData){
                                        $multipleDataArray[$tempKey] = $data;
                                        $multipleDataArray[$tempKey][$tempField] = $tempMultipleData;
                                    }
                                    
                                }
                            }          
                        }
                      
                    }

                    if($has_multiple_select_non_csv)break;  

                    $insert_id = $dataTemp['fields'][$this->config['id']];
                    $final_data = array();
                    //print_r($data);
                    
                    $sql = "UPDATE `" . $this->dbTable . "` SET ";
                    unset($data[$this->config['id']]);
                    $c = count($data); //a small optimization :)
                    $i = 1;
                    foreach ($data as $k => $v) {
                        $sql .= '`' . $this->escape($k) . "` = '" . $this->escape($v) . "'" . (($i++ < $c) ? ', ' : ' ');
                    }
                    
                    $sql .= "WHERE `id` = '" . $insert_id . "'";
                    //print_r($sql);
                    /* $query = 1;// */$query = mysqli_query($this->dbConn, $sql);
                    
                        
                    $return[$insert_id] = $this->afterUpdate($data,$currentUniqueId,$insert_id);
                    $return['id'] = $insert_id;
                    if(!isset($return['query']))$return['query'] = $query;
                    $return[$insert_id] ['id'] = $insert_id;
                    $return[$insert_id] ['db_error'] = $this->errorLog;
                    $return[$insert_id] ['message'] = "Updated Succesfully";
                    $return[$insert_id] ['sql'] = $sql;
                    $return[$insert_id] ['ajaxresult'] = true;
                    $return[$insert_id] ['jsFunction'] = '';

                    if(!empty($form_config['child_form'])){
                        foreach($form_config['child_form'] as $child_module=>$child_form_config){
                            $class = getAdapter($child_form_config['module']);
                            $child_form_config['insert_ids'] = $form_config['insert_ids'];
                            $child_form_config['insert_ids'][$form_config['module']] = $insert_id;
                            if(!empty($child_form_config['parent_ids'])){
                                foreach($child_form_config['parent_ids'] as $module=>$foreign_key){
                                    $child_form_config['default_insert']['enabled'] = true;
                                    $child_form_config['default_insert']['fields'][$foreign_key] = $child_form_config['insert_ids'][$module];
                                }
                            }
                            ////// EXTRA FOREIGN KEY DATA FROM TEH PARENT VALUES
                            if(!empty($child_form_config['foreign_key_from_parent_data'])){
                                foreach($child_form_config['foreign_key_from_parent_data'] as $key=>$foreign_key){
                                    $child_form_config['default_insert']['enabled'] = true;
                                    $child_form_config['default_insert']['fields'][$foreign_key] = $data[$key];
                                }
                            }  
                            $return2 = $class->updateNew($dataTemp['children'][$child_module],$child_form_config);
                            $return[$insert_id]['children'][] = $return2;
                            //print_r($return2);
                        }
                    }
                }
            }
        }

        if($has_multiple_select_non_csv){
            $return = $this->insertMultiple($multipleDataArray);
            $return['id'] = null;
        }        
        /* if(empty($form_config['child_form'])){
            $sql = "INSERT INTO {$this->dbTable} (".implode(",", array_keys($data)).") VALUES";  
            $sql .= implode(',',$sqlTemp);
            $this->query($sql);
            $insert_id = mysqli_insert_id($this->dbConn);
            $return['id'] = $insert_id;
        }  */       
        return $return;
    } 
    public function prepUpdateData($data, $unique_id, $id)
    {
        return $data;
    }
    public function afterUpdate($data, $unique_id, $id)
    {
        return array();
    }
    public function update($form_data, $form_config)
    {
        if (!is_array($form_data)) {
            $this->error('Form Data Passed is not an array', __LINE__);
        }

        $dataOriginal = $form_data;
        $properties = $form_data[$form_config['name_prefix']];
        $return = array();
        $form_fields = $this->getAllFields();
        foreach ($form_config['fields'] as $tempField) {
            $tempVal = $form_fields['fields'][$tempField];
            if ($tempVal['type'] == 'checkbox' && !isset($properties[$tempField])) {
                $properties[$tempField] = getDefault($tempVal['other_value'], 0);
            }
            if ($tempVal['type'] == 'multiple-select') {
                $properties[$tempField] = implode('' . $tempVal['seperator'] . '', $properties[$tempField]);
            }
        }

        if (isset($form_config['non_db_fields'])) {
            foreach ($form_config['non_db_fields'] as $defF => $defV) {
                unset($properties[$defV]);
            }
        }
        if (is_array($properties) && count($properties) > 0) {
            $id = $properties['id'];
            $properties = $this->prepUpdateData($properties, $form_data['unique_id'], $id);
            unset($properties['id']);
            $i = 1;
            $query = "UPDATE `" . $this->dbTable . "` SET ";
            $c = count($properties); //a small optimization :)
            foreach ($properties as $k => $v) {

                $query .= '`' . $this->escape($k) . "` = '" . $this->escape($v) . "'" . (($i++ < $c) ? ', ' : ' ');
            }
            $query .= "WHERE `id` = '" . $id . "'";
            $sql_query = mysqli_query($this->dbConn, $query);
            $return = $this->afterUpdate($dataOriginal, $form_data['unique_id'], $id);
            $return['query'] = $sql_query;
        }
        return $return;
    }

    public function updateCommon($form_data)
    {
        if (!is_array($form_data)) {
            $this->error('Form Data Passed is not an array', __LINE__);
        }

        $properties = $form_data;
        $return = array();
        $return['result'] = false;
        if (is_array($properties) && count($properties) > 0) {
            $id = $properties['id'];
            unset($properties['id']);
            $i = 1;
            $sql = "UPDATE `" . $this->dbTable . "` SET ";
            $c = count($properties); //a small optimization :)
            foreach ($properties as $k => $v) {
                $sql .= '`' . $this->escape($k) . "` = '" . $this->escape($v) . "'" . (($i++ < $c) ? ', ' : ' ');
            }
            $sql .= "WHERE `id` = '" . $id . "'";
            $sql_query = $this->query($sql);
            $return['query'] = $sql_query;
            $return['result'] = true;
        }
        return $return;
    }

    public function insertMultiple($form_data)
    {
        ///print_r($form_data);
        $data_multiple = $form_data;
        $return = array();
        $form_fields = $this->getAllFields();
        //print_r($form_fields);

        foreach ($data_multiple as $data) {
            //$data = $data['form_data'];
            $data = $this->prepInsertData($data,0);
            $final_data = array();
            //print_r($data);
            foreach ($data as $k => $v ) $final_data[$k] = "".$this->escape($v)."";

            $sqlTemp[] = " ('" . implode("', '", $final_data) . "')";
        }

        $sql = "INSERT INTO {$this->dbTable} (" . implode(",", array_keys($data)) . ") VALUES";
        $sql .= implode(',', $sqlTemp);
        $sql .= " ON DUPLICATE KEY UPDATE id=id";
        //print_r($sql);
        $query = $this->query($sql);
        $return['id'] = 1;
        //$return['id'] = mysqli_insert_id($this->dbConn);
        $return['query'] = $query;
        $return['db_error'] = $this->errorLog;
        $return['message'] = "New Entry Saved";
        $return['sql'] = $sql;
        $return['data'] = $data;
        $return['jsFunction'] = '';

        return $return;
    }

    public function updateEx($form_data, $form_config)
    {
        if (!is_array($form_data)) {
            $this->error('Form Data Passed is not an array', __LINE__);
        }

        $dataOriginal = $form_data[$form_config['name_prefix']];
        $id = $properties['id'];
        $data = $this->prepUpdateData($dataOriginal, $form_data['unique_id'], $id);
        $return = array();
        if (!is_array($data)) {
            $this->error('Data is not an array', __LINE__);
        }

        $form_fields = $this->getAllFields();
        //// Check if there was a checkbox and if it was unckecked, it will not show in the $_POST ... SET ITS VALUE TO ZERO
        foreach ($form_config['fields'] as $tempField) {
            $tempVal = $form_fields['fields'][$tempField];
            if ($tempVal['type'] == 'checkbox' && !isset($data[$tempField])) {
                $data[$tempField] = getDefault($tempVal['other_value'], 0);
            }
            if ($tempVal['type'] == 'multiple-select') {
                $data[$tempField] = implode('' . $tempVal['seperator'] . '', $data[$tempField]);
            }
        }

        $getLabels = $this->getLabels();
        foreach ($form_config['dependency'] as $depField => $depValue) {
            if (isset($data[$depField])) {
                if (isset($depValue[$data[$depField]]['hide'])) {
                    $hidden = $depValue[$data[$depField]]['hide'];

                    foreach ($hidden as $hide) {
                        if (isset($getLabels[$hide])) {
                            $data[$hide] = '';
                        } else {
                            unset($data[$hide]);
                        }

                    }
                }
            }
        }

        if (is_array($data) && count($data) > 0) {
            $id = $data[$this->config['id']];
            unset($data[$this->config['id']]);
            $i = 1;
            $query = "UPDATE `" . $this->dbTable . "` SET ";
            $c = count($data); //a small optimization :)
            foreach ($data as $k => $v) {

                $query .= '`' . $this->escape($k) . "` = '" . $this->escape($v) . "'" . (($i++ < $c) ? ', ' : ' ');
            }
            $query .= "WHERE `{$this->config['id']}` = '" . $id . "'";
            $return['query'] = mysqli_query($this->dbConn, $query);
        }

        $return = $this->afterUpdate($dataOriginal, $form_data['unique_id'], $id);
        $return['id'] = $id;

        $return['db_error'] = $this->errorLog;
        $return['message'] = "New Entry Saved";
        $return['sql'] = $query;
        $return['data'] = $data;
        return $return;
    }

    public function bulkUpdate($data)
    {
        $return = array();
        $ids = $data['ids'];
        $properties = $data[$this->dbTable];

        if (is_array($properties) && count($properties) > 0) {
            $properties = array_filter($properties, 'strlen');
            $i = 1;
            $query = "UPDATE `" . $this->dbTable . "` SET ";
            $c = count($properties);
            foreach ($properties as $k => $v) {

                $query .= '`' . $this->escape($k) . "` = '" . $this->escape($v) . "'" . (($i++ < $c) ? ', ' : ' ');
            }
            $query .= "WHERE `{$this->config['id']}` IN (" . implode(", ", $ids) . ")";
            //echo $query;
            $return['query'] = mysqli_query($this->dbConn, $query);
        }
        return $return;
    }

    public function delete($data)
    {
        $return = array();
        $id = $data['id'];
        $sql = "DELETE FROM `$this->dbTable` WHERE {$this->config['id']}='$id'";
        $query = mysqli_query($this->dbConn, $sql);
        print_r(mysqli_error($this->dbConn));
        $return['query'][] = $query;
        $return['sql'][] = $sql;
        foreach ($this->delete_dependency as $del => $fk) {
            $sql = "DELETE FROM $del WHERE $fk='$id'";
            $query1 = mysqli_query($this->dbConn, $sql);
            $return['query'][] = $query1;
            $return['sql'][] = $sql;
        }
        return $return;
    }

    public function deleteBulk($ids)
    {
        $return = array();
        foreach ($ids as $id) {
            $sql = "DELETE FROM $this->dbTable WHERE {$this->config['id']}='$id'";
            $query = mysqli_query($this->dbConn, $sql);
            $return['query'][] = $query;
            $return['sql'][] = $sql;
            foreach ($this->delete_dependency as $del => $fk) {
                $sql = "DELETE FROM $del WHERE $fk='$id'";
                $query1 = mysqli_query($this->dbConn, $sql);
                $return['query'][] = $query1;
                $return['sql'][] = $sql;
            }
        }

        return $return;
    }

    public function is($prop)
    {
        return $this->get_property($prop) == 1 ? true : false;
    }

    public function get_property($property)
    {
        if (empty($this->dbID)) {
            $this->error('No user is loaded', __LINE__);
        }

        if (!isset($this->dbData[$property])) {
            $this->error('Unknown property <b>' . $property . '</b>', __LINE__);
        }

        return $this->dData[$property];
    }

    public function getForeignKeys()
    {
        return array();
    }

    public function getSelectList($type = '')
    {
        $arr = array();
        $sql = "";
        if ($type == '') {
            $tempF = $this->config;
            $sql = "SELECT $tempF[id],$tempF[name] FROM $this->dbTable";
        } else {
            $temp = $this->getForeignKeys();
            $tempF = isset($temp[$type]) ? $temp[$type] : '';
            if (isset($temp[$type])) {
                $sql = "SELECT $tempF[id],$tempF[name] FROM $tempF[table] as $tempF[as]";
            } else {
                $sql = "";
            }
        }

        if ($sql != '') {
            $query = mysqli_query($this->dbConn, $sql);
            if ($query) {
                while ($row = mysqli_fetch_array($query)) {
                    $arr[$row[0]] = $row[1];
                }
            }
        } else {

            return false;
        }
        //print_r($sql);
        //print_r(mysqli_error($this->dbConn));
        return $arr;
    }

    public function getAjaxDependentOptions($value)
    {
        $aDep = $this->ajax_dependency[$value];
        $i = 0;
        foreach ($aDep as $depElem => $depOpt) {
            eval($depOpt['code']);
            $return['' . $value . '-' . $i . ''] = $element;
            $i++;
        }
        return $return;
    }

    public function DeleteDirectoryAndContents($path)
    {
        if (is_dir($path) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                if (in_array($file->getBasename(), array('.', '..')) !== true) {
                    if ($file->isDir() === true) {
                        rmdir($file->getPathName());
                    } else if (($file->isFile() === true) || ($file->isLink() === true)) {
                        unlink($file->getPathname());
                    }
                }
            }

            return rmdir($path);
        } else if ((is_file($path) === true) || (is_link($path) === true)) {
            return unlink($path);
        }

        return false;
    }

    ////////////////////////////////////////////
    // PRIVATE FUNCTIONS
    ////////////////////////////////////////////

    protected function query($sql, $line = 'Uknown')
    {
        //if ($this->DEVELOPMENT_MODE ) echo '<b>Query to execute: </b>'.$sql.'<br /><b>Line: </b>'.$line.'<br />';
        $this->errorLog .= '<b>Query to execute: </b>' . $sql . '<br /><b>Line: </b>' . $line . '<br />';
        $res = mysqli_query($this->dbConn, $sql);
        if (!$res) {
            $this->error(mysqli_error($this->dbConn), $line);
        }

        return $res;
    }

    protected function getSingleRowFromDB($sql)
    {
        $query = mysqli_query($this->dbConn, $sql);
        //echo mysqli_error($this->dbConn);
        //echo $sql;
        $row = array();
        if ($query) {
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
    protected function escape($str)
    {
        $str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
        /* $str = mysqli_real_escape_string($this->dbConn, $str); */
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
    protected function error($error, $line = '', $die = false)
    {
        //if ( $this->displayErrors )
        $this->errorLog .= '<b>Error: </b>' . $error . '<br /><b>Line: </b>' . ($line == '' ? 'Unknown' : $line) . '<br />';
        if ($die) {
            exit;
        }

        return false;
    }
    protected function print_mysqli_error()
    {
        print_r(mysqli_error($this->dbConn));
        return false;
    }
    protected function dberror()
    {
        print_r(mysqli_error($this->dbConn));
        return false;
    }

    protected function getSelectOptions($sql)
    {
        $arr = array();
        $query = mysqli_query($this->dbConn, $sql);
        //print_r(mysqli_error($this->dbConn));
        if ($query) {
            while ($row = mysqli_fetch_array($query, MYSQLI_NUM)) {
                //print_r($row);
                //$arr[$row[$this->config['id']]] = replaceBetweenBraces($row,$this->config['name']);
                $temp_name = $row[1];
                $temp_name .= isset($row[2]) ? '-' . $row[2] : '';
                $temp_name .= isset($row[3]) ? '-' . $row[3] : '';
                $arr[$row[0]] = $temp_name;
            }
        }
        return $arr;
    }

    protected function getSelectOptionsCommon($table, $cond = 'live=\'1\'')
    {
        $where = ($cond != '') ? 'WHERE ' . $cond . '' : '';
        $arr = array();
        $sql = "SELECT id, name FROM $table $where";
        $query = mysqli_query($this->dbConn, $sql);
        if ($query) {
            while ($row = mysqli_fetch_array($query)) {
                $arr[$row[0]] = $row[1];
            }
        }

        return $arr;
    }

    protected function getSelectOptionsRange($num)
    {
        $arr = array();
        for ($i = 1; $i <= $num; $i++) {
            $arr[$i] = $i;
        }
        return $arr;
    }

    public function display_static_info($data, $titles, $col = 'col-md-5', $keyVal = '4')
    {
        $temp =
            '
		<div class="' . $col . ' display-info">
		';
        $num = sizeof($data);
        $smartNum = ceil($num / 2);
        $smartNum2 = $num - $smartNum;
        $keys = array_keys($data);
        if ($num > 5) {
            for ($i = 0; $i < $smartNum; $i++) {
                $temp .=
                    '
					<div class="row static-info">
						<div class="col-md-' . $keyVal . ' name">
							 ' . $titles[$keys[$i]] . ':
						</div>
						<div class="col-md-' . (12 - $keyVal) . ' value float-left wrap">
							 ' . $data[$keys[$i]] . '
						</div>
					</div>
				';
            }
            $temp .=
                '
			</div>
			';
            $temp .=
                '
			<div class="' . $col . ' display-info">
			';
            for ($i = $smartNum; $i < $num; $i++) {
                $temp .=
                    '
					<div class="row static-info">
						<div class="col-md-' . $keyVal . ' name">
							 ' . $titles[$keys[$i]] . ':
						</div>
						<div class="col-md-' . (12 - $keyVal) . ' value float-left wrap">
							 ' . $data[$keys[$i]] . '
						</div>
					</div>
				';
            }
            $temp .=
                '
			</div>
			';
        } else {
            $temp .=
                '
			<div class="' . $col . ' display-info">
			';
            for ($i = 0; $i < $num; $i++) {
                $temp .=
                    '
					<div class="row static-info">
						<div class="col-md-7 name">
							 ' . $titles[$keys[$i]] . ':
						</div>
						<div class="col-md-5 value text-right">
							 ' . $data[$keys[$i]] . '
						</div>
					</div>
				';
            }

            $temp .=
                '
			</div>
			';
        }
        return $temp;
    }

}
