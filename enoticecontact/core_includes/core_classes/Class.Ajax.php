<?php 
Class Ajax extends CoreDB
{
    public $typeAheadFields = array();
    public $functionMappings = 
        array (
          'get-table-fields' => 'ajaxGetTableFields',
          'search' => 'ajaxSearch',
          'searchwithpaginate' => 'ajaxSearchPaginate',
          'reportwithpaginate' => 'ajaxReportPaginate',
          'search-report' => 'searchReport',
          'select2-search' => 'ajaxSelect2Search',
          'delete' => 'ajaxDelete',
          'save' => 'ajaxSave',
          'insert_wo_config' => 'ajaxSaveWithoutConfig',
          'insert_multiple_wo_config' => 'ajaxSaveMultipleWithoutConfig',
          'update' => 'ajaxUpdate',
          'updateCommon' => 'ajaxUpdateCommon',
          'quick-update' => 'ajaxQuickUpdate',
          'delete-nestable' => 'ajaxDeleteNestable',
          'delete-bulk' => 'ajaxDeleteBulk',
          'bulk-delete' => 'ajaxDeleteBulk',
          'bulk-update' => 'ajaxBulkUpdate',
          'check-for-duplicate' => 'checkForDuplicate',
          'check-if-exists' => 'checkIfExists',
          'save-assoc-form' => 'ajaxSaveAssocForm',
          'save-bulk-assoc-form' => 'ajaxSaveBulkAssocForm',
          'typeahead' => 'getTypeAhead',
          'getOneTableRow' => 'getOneTableRow',
          'reset-password' => 'resetPassword',
          'get-dep-fields' => 'getAjaxDependentFields',
          'confirm-otp' => 'confirmOtp',
          'resend-otp' => 'resendOtp',
          'delete_image' => 'deleteImage',
          'upload_image' => 'uploadImage',
        );
	public $actionMappings;
    function __construct(){
		/* 		$this->dbTable = $class->dbTable;
		$this->class = $class;
		$this->functionMappings = array_merge($this->functionMappings,$class->getFunctionMappings()); */
    }
	
    function getFunctionMappings($func_key=''){
        
        if($func_key == ''){
            return $this->functionMappings;  
        }
        else{
            if(isset($this->functionMappings[$func_key])){
                return $this->functionMappings[$func_key];  
            }
            else{
                return false;  
            } 
        }        
              
    }
    
    function handleAjaxCall($data){
        if(!isset($data['next_data'])) $data['next_data'] = array();
        $return = array();
        $return['debug'] = false;
        $return['ajaxresult'] = false;
        //print_r($data);
        $data['call_type'] = 'ajax';
        if (isset($data['action'])){
            $func_to_call = $this->getFunctionMappings($data['action']);

            if($func_to_call){
                $return = call_user_func_array(array($this, $func_to_call), array($data));
            }
            else{
                $this->extendAjax($data);
            }
            $return['ajaxresult'] = true;
            echo json_encode($return);
            exit();
        }
		else{
			$this->extendAjax($data);
		}
	}	

    public function ajaxGetTableFields($data){
        $data['pull_data'] = true;
        $return = $this->getTableFields($data);
        $return['ajaxresult'] = true;
        echo json_encode($return);
        exit();
    }
    public function resetPassword($data){
        /* print_r($data); */
        $unique_id = $data['config']['unique_id'];
        /* print_r($data['data']['data']['new'][$unique_id]['fields']);
        exit(); */
        if($data['action'] == 'reset-password'){
            $field_data = $data['data']['data']['new'][$unique_id]['fields'];
            $user = $field_data[$this->config['id']];
            $pass = $field_data['password'];
            $pass2 = $field_data['password2'];
            $return['ajaxresult'] = true;
            $return['jsFunction'] = "";
            $return['ajaxmessage'] = 'Password updated succesfully';		
            if($user == ''){
                $return['ajaxresult'] = true;
                $return['validation_error'] = true;
                $return['ajaxmessage'] = 'Please Choose a user';	
                $return['jsFunction'] .= 
                "
                showSweetError('Error','$return[ajaxmessage]');
                ";                     
            }
            else{
                if(strlen($pass) < 6 || strlen($pass2) < 6){
                    $return['ajaxresult'] = true;
                    $return['validation_error'] = true;
                    $return['ajaxmessage'] = 'Password Should be atleast 6 characters';	
                    $return['jsFunction'] .= 
                    "
                    showSweetError('Error','$return[ajaxmessage]');
                        ";                 
                }

                else {
                    if($pass != $pass2){
                        $return['ajaxresult'] = true;
                        $return['validation_error'] = true;
                        $return['ajaxmessage'] = 'Passwords Dont Match';
                        $return['jsFunction'] .= 
                        "
                        showSweetError('Error','$return[ajaxmessage]');
                        ";                          
                    }	
                    else{
                        if($this->password_type == 'base64_encode'){
                           $p_hash = base64_encode($pass); 
                        }
                        else if($this->password_type == 'password_hash'){
                            $p_hash = password_hash($pass, PASSWORD_BCRYPT);
                        }
                        
                        
                        $sql = "UPDATE {$this->dbTable} SET password = '$p_hash' WHERE {$this->config['id']}='$user'";
                        $query = mysqli_query($this->dbConn,$sql);
                        if(!$query){
                            $return['ajaxresult'] = true;
                            $return['ajaxmessage'] = 'Database error';	
                            $return['extra'] = mysqli_error($this->dbConn);	
                            $return['jsFunction'] = 
                            "
                            showSweetError('Error','$return[extra]');
                            ";                            
                        }
                        else{
                            $return['jsFunction'] = 
                            "
                            CloseBsModal('$unique_id-main-container');
                            showSweetSuccess('Done','Password Has been Updated Succesfully');
                            ";                            
                        }

                    }	
                    
                }
                       
            }

            $return['html'] = $data;	
            echo json_encode($return);
            exit();
        }
    
    }

    public function checkForDuplicate($data){
        $dbColTemp = $data['type'];
        $arrTemp = explode('-',$dbColTemp);
        $dbCol = end($arrTemp);        
        $dbVal = $data['value'];
        $return = array();
        $sql = "SELECT {$this->config['id']} FROM $this->dbTable WHERE $dbCol='$dbVal'";
        $query = mysqli_query($this->dbConn, $sql);
        print_r(mysqli_error($this->dbConn));
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

    public function checkIfExists($data){
        $dbCol = $data['type'];
        $dbVal = $data['value'];
        $return = array();
		$exists = $this->is_property($dbCol,$dbVal);
        if($exists){
            $return['ajaxmessage'] = '<strong>'.$dbVal.'</strong> is OK';
        }
        else {
            $return['ajaxmessage'] = '<strong>'.$dbVal.'</strong> does not exist';
        }
		$return['ajaxresult'] = true;
		$return['if_exists'] = $exists;
        echo json_encode($return);
        exit();		
    }

    function getTypeAhead($data){
        $query = $data['query'];
        $this->getTableFields();
        $tempRowsNum = 100;
        $sql = $this->generateSqlPullData();
        if(isset($this->table['sql'])){
            $sql = $this->table['sql'];
        }
        //// COde to change the search fields array into string for sql
        //$search_fields_arr = explode(' ',$this->table['search_fields']);
        $search_fields_arr = $this->table['search_fields'];
        $search_sql = "";
        if(sizeof($search_fields_arr) >= 1){
            
            $where = "";
            foreach($search_fields_arr as $i => $field){
                if($i+1 == sizeof($search_fields_arr)){ ///If last key then dont add the OR
                    $where .= " $field LIKE '%$query%' ";
                }
                else{
                    $where .= " $field LIKE '%$query%' OR ";
                } 
            }
            //$name_sql = " , CONCAT($sql_temp) as name";
        }

        
        $sqlnum = "$sql
        AND ($where)
        ";
        $data = $this->getDataForTable($sqlnum);
        //$table = $table->getTable();
        $return['sql'] = $sqlnum;
        $return['data'] = $data;
        //$return['table'] = $table['table'];
        //$return['jsFunction'] .= $table['jsFunction'];

        echo json_encode($data);
        exit();               
    }

    function searchReport($data) {
        print_r($data);
    }
    function ajaxSave($data){
        /* print_r($data);
        exit(); */
		$form_type = isset($data['form_type']) ? $data['form_type'] : '';
		$form_config = array();
		if($form_type !== ''){
			$func_to_call = $this->getFunctionMappings($form_type);
			$form_config = call_user_func_array(array($this, $func_to_call), array(array('pull_data'=>false)));
		}
        $return['post'] = $data;
        /* unset($data['action']); */
        //$data = $data[$this->dbTable];
		/* $result = $this->insertEx($data,$form_config); */
		$data['action'] = 'save';
		$data['module'] = $this->module;
		$response = $this->callApi($data, 'feedbacks.php');

        $return = $response;
        if($response['status']){
            $id = $response['insert_id'];
            //$this->getTableFields();
            /* $sql = $this->generateSqlPullData();
            $db_row = $this->getSingleRowFromDB("$sql AND main.{$this->config['id']}='$id'"); */
            $return['ajaxresult'] = true;
            $return['ajaxmessage'] = 'Success';
            $return['id'] = $id;
            $return['data'] = $response['new_row'];
            $return['jsFunction'] = 
            "
			
            ";
         } else {
            $return['ajaxresult'] = false;
            $return['ajaxmessage'] = $response;		 
            $return['data'] = $data;
         }

        return $return;         
    }
    function ajaxSaveWithoutConfig($data){
		/* print_r($data);
        exit(); */
        $return['post'] = $data;
        unset($data['action']);
        //$data = $data[$this->dbTable];
        $result = $this->insert($data['data']);
        $return = $result + $return;
        if($result['id'] != 0){
            $id = $result['id'];
            if(isset($data['return']) == 'db_row'){
                //$this->getTableFields();
                $sql = $this->generateSqlPullData();
                $db_row = $this->getSingleRowFromDB("$sql AND main.{$this->config['id']}='$id'");
                $return['data'] = $db_row;
            }
            $return['ajaxresult'] = true;
            $return['ajaxmessage'] = 'Success';
            $return['id'] = $id;
            $return['jsFunction'] =  " ";
         } else {
            $return['ajaxresult'] = false;
            $return['ajaxmessage'] = $result;		 
            $return['data'] = $data;		 
            $return['err'] = mysqli_error($this->dbConn);
         }

        return $return;         
    }
    function ajaxSaveMultipleWithoutConfig($data){
		/* print_r($data);
        exit(); */
        $return['post'] = $data;
        unset($data['action']);
        //$data = $data[$this->dbTable];
        $result = $this->insertMultiple($data);
        $return = $result + $return;
        if($result['id'] != 0){
            $id = $result['id'];
            if(isset($data['return']) == 'db_row'){
                //$this->getTableFields();
                $sql = $this->generateSqlPullData();
                $db_row = $this->getSingleRowFromDB("$sql AND main.{$this->config['id']}='$id'");
                $return['data'] = $db_row;
            }
            $return['ajaxresult'] = true;
            $return['ajaxmessage'] = 'Success';
            $return['id'] = $id;
            $return['jsFunction'] =  " ";
         } else {
            $return['ajaxresult'] = false;
            $return['ajaxmessage'] = $result;		 
            $return['data'] = $data;		 
            $return['err'] = mysqli_error($this->dbConn);
         }

        return $return;         
    }

    function ajaxUpdate($data){
		/* print_r($data);
        exit(); */
        $return = array();
		$form_type = $data['form_type'];
		//print_r($form_type);
		$func_to_call = $this->getFunctionMappings($form_type);
		$form_config = call_user_func_array(array($this, $func_to_call), array(array('pull_data'=>false)));

		$data['action'] = 'update';
		$data['module'] = $this->module;
		$response = $this->callApi($data, 'feedbacks.php');		
		$result = $response;
        //$result = $this->updateNew($data,$form_config);
        //print_r($result);
        $id = $result['id'];
        $return = $response;
		//print_r($id);
		//print_r(mysqli_error($this->dbConn));
        if($response['query']){

            ////// Multiple select multipleinsert sends nnull as id
            if(!is_null($id)){
				//$this->getTableFields();
				//$sql = $this->generateSqlPullData();
                $return['id'] = $id;
                $return['data'] = $response['rowData'];
            }
            $return['ajaxresult'] = true;
            $return['ajaxmessage'] = 'Success';
            $return['jsFunction'] = 
            "
			
            ";
         } 
         else {
            $return['ajaxresult'] = false;
            $return['ajaxmessage'] = $result;		 
            $return['data'] = $data;
         }
        return $return;
    }
    function ajaxUpdateCommon($data){
		/* print_r($data);
        exit(); */
        $return = array();
        $result = $this->updateCommon($data);
        //print_r($result);
        $id = $data['id'];
        $return = $result + $return;
        if($result['query']){
            //$this->getTableFields();
            $return['ajaxresult'] = true;
            $return['ajaxmessage'] = 'Success';
            $return['id'] = $id;
            $return['jsFunction'] = "";
         } 
         else {
            $return['ajaxresult'] = false;
            $return['ajaxmessage'] = $result;
            $return['data'] = $data;		 
            $return['err'] = mysqli_error($this->dbConn);
         }
        return $return;
    }
    

    function ajaxQuickUpdate($data){
        /* print_r($data);
        exit(); */
		$form_config = array();
        $id = $data['id'];
        $key = $data['field'];
        $value = $data['value'];
        $sql = "UPDATE $this->dbTable SET $key='$value' WHERE id='$id'";
        $query = mysqli_query($this->dbConn, $sql);
        if($query){
            //$this->getTableFields();
            $sql = $this->generateSqlPullData();
            $db_row = $this->getSingleRowFromDB("$sql AND main.{$this->config['id']}='$id'");
            $return['ajaxresult'] = true;
            $return['ajaxmessage'] = 'Success';
            $return['id'] = $id;
            $return['data'] = $db_row;
            $return['jsFunction'] =   "";
         } 
         else {
            $return['ajaxresult'] = false;
            $return['ajaxmessage'] = $result;		 
            $return['data'] = $data;		 
            $return['err'] = mysqli_error($this->dbConn);		 
         }
        return $return;
    }

    function ajaxSearchPaginate($data){
        $query_string = $data['query'];
        $this->rowsCountDisplay = $return['rowsCountDisplay'] = $data['rows'];       
        $startLimit = ($data['currentPagination']-1)*$return['rowsCountDisplay'];
        $stopLimit = $data['rows'];
        
        if ($stopLimit != $return['rowsCountDisplay']){
            $startLimit = 0;
        }
		$this->getTableFields(array('pull_data' => false));
        $return['stoplimit'] = $stopLimit;
        $return['startlimit'] = $startLimit;
        $return['rowsCountDisplay'] = $stopLimit;
		
		$data['search_fields'] = $this->table['search_fields'];
		$response = $this->callApi($data, 'feedbacks.php');
		/* print_r($response); */
        $return['ajaxresult'] = true;
		//$return['jsFunction'] = "console.log('ajaxSearchPaginate');";
		$response['tableData'] = array_reverse($response['tableData']);
		$return['tableData'] = $response['tableData'];
		$return['totalRowsInDb'] = $response['totalRowsInDb'];
		$return['sql'] = $response['sql'];
        $return['currentPagination'] = $data['currentPagination'];

        return $return;
    }

    function ajaxSelect2Search($data){
        $query_string = $data['search'];
        /*$this->rowsCountDisplay = $return['rowsCountDisplay'] = $data['rows'];       
        $startLimit = ($data['currentPagination']-1)*$return['rowsCountDisplay'];
        $stopLimit = $data['rows']; 
        
        if ($stopLimit != $return['rowsCountDisplay']){
            $startLimit = 0;
        }
        
        $return['stoplimit'] = $stopLimit;
        $return['startlimit'] = $startLimit;
        $return['rowsCountDisplay'] = $stopLimit;*/
        $this->getTableFields(array('pull_data'=>true));
        $sql = $this->generateSqlPullData();
        if(isset($this->table['sql'])){
            $sql = $this->table['sql'];          
        }        
        //$tempRowsNum = $return['rowsCountDisplay']; 
        if(trim($query_string) != ''){
			$query_array = array();
			if(isset($this->table['search_other_databases']) && sizeof($this->table['search_other_databases']) >= 1){
				$search_fields_arr = $this->table['search_other_databases'];
				foreach($search_fields_arr as $field){
					$query_array[] = "$field LIKE '%$query_string%'";
				}				
			}
			else{
				$search_fields_arr = $this->table['search_fields'];
				foreach($search_fields_arr as $field){
					$query_array[] = "main.$field LIKE '%$query_string%'";
				}				
			}		

			
			$query_statement = implode(' OR ',$query_array);
            $sql = 
            "
            $sql
            AND $query_statement
            ";
            $sqlnum = 
            "
			$sql
            ORDER BY main.{$this->config['id']} DESC
            ";
        }
        else{
            $sqlnum = "$sql
            ORDER BY main.{$this->config['id']} DESC
            ";
        }

        $return['ajaxresult'] = true;
        //$return['jsFunction'] = "console.log('ajaxSearchPaginate');";
        $return['tableData'] = $this->getDataForTable($sqlnum);
        $return['totalRowsInDb'] = $this->numRows($sql);
        //$return['currentPagination'] = $data['currentPagination'];
        $return['sql'] = $sqlnum;

        return $return;
    }

    function ajaxDelete($data){
        $id = $data['id'];
        if($id == '' || $id == 0){
            $return['ajaxresult'] = true;
            $return['ajaxmessage'] = 'No Id';    
            return $return;            
        }
        $return = $this->delete($data);
		

        if(__PAGE_TYPE__ == 'table'){ 
            $return['jsFunction'] = 
            "
            $('.tr-$id').remove();
            ";             
        }
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'Success';
        $return['id'] = $id;
        return $return;        
    }

    function ajaxDeleteBulk($data){
		/// ids are comma seperated, not figure it out yet why
        $ids = $data['ids'];
		$idsArray = explode(',',$ids);
        $return = $this->deleteBulk($idsArray);
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'success';
        $return['ids'] = $ids;
        $return['html'] = '';
        $return['jsFunction'] = 
        "
            UncheckMaster();
            HideBulkUpdateRow();
        ";
        return $return;        
    }

    function ajaxBulkUpdate($data){
        $ids = $data['ids'];
        $return = $this->bulkUpdate($data);
        if($return['query']){
            
            //$this->getTableFields();
            $sql = $this->generateSqlPullData();
            
            $sql .= " AND main.{$this->config['id']} IN (".implode(", ", $ids).")";
            $ids = $data['ids'];                    
            $row = $this->getDataForTable($sql);

            $table1 = '';	
            $newRow = array();	

/*             foreach($row as $user) {
                $col_data_temp = $this->createArrayForColumns($user);
                $table1 = $this->createTableTdOnly($col_data_temp,$user);	                              
                $newRow[$user[$this->config['id']]] = $table1;
            } */
            
            $return['ajaxresult'] = true;
            $return['ajaxmessage'] = 'Success';
            $return['ids'] = $ids;
            $return['data'] = $row;
            $return['jsFunction'] = 
            "
            var ids = value.ids;
            UncheckMaster();
            HideBulkUpdateRow();
            $.each(value.data,function(inum,row){
				temp = {};
				temp.id = row.id;
				temp.data = row;
                replaceTableRow(temp);
            });			
            ";		
         } else {
            $return['ajaxresult'] = false;
            $return['ajaxmessage'] = $return;		 
         }                    

        $return['ids'] = $ids;
        $return['html'] = '';
        return $return;
    }

    function extendAjax($data){
        $this->errorAjax($data);
    }	

    function errorAjax($data){
        
        var_export($data);
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'This Action is Not Defined For This Page';
        $return['from'] = 'errorAjax';
        $return['post'] = $data;
        $return['table'] = $this->dbTable;
        $return['table1'] = $this->functionMappings;
        $return['jsFunction'] = 
        "
        console.log(value.ajaxmessage);
        ";
        echo json_encode($return);
        exit();
    }

}


?>