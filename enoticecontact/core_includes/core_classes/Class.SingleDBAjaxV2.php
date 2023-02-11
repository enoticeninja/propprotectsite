<?php 
Class SingleDBAjaxV2 extends SingleDBHtmlV2
{
    public $typeAheadFields = array();
    public $handleAjaxCall = 
        array (
          'search' => 'ajaxSearch',
          'searchwithpaginate' => 'ajaxSearchPaginate',
          'delete' => 'ajaxDelete',
          'save' => 'ajaxSave',
          'update' => 'ajaxUpdate',
          'delete-nestable' => 'ajaxDeleteNestable',
          'delete-bulk' => 'ajaxDeleteBulk',
          'bulk-update' => 'ajaxBulkUpdate',
          'check-for-duplicate' => 'checkForDuplicate',
          'save-assoc-form' => 'ajaxSaveAssocForm',
          'save-bulk-assoc-form' => 'ajaxSaveBulkAssocForm',
          'typeahead' => 'getTypeAhead',
          'getOneTableRow' => 'getOneTableRow',
          'reset-password' => 'resetPassword',
          'get-dep-fields' => 'getAjaxDependentFields',
          'confirm-otp' => 'confirmOtp',
          'resend-otp' => 'resendOtp',
        );
    function __construct(){

    }
    function getFunctionMappings($func_key=''){
        
        if($func_key == ''){
            return $this->handleAjaxCall;  
        }
        else{
            if(isset($this->handleAjaxCall[$func_key])){
                return $this->handleAjaxCall[$func_key];  
            }
            else{
                return false;  
            } 
        }        
              
    }
    
    function handleAjaxCall($data){
        if(!isset($data['next_data'])) $data['next_data'] = array();
        global $table,$db,$sql;
        $return = array();
        $return['debug'] = false;	
        $return['ajaxresult'] = false;	
        //print_r($data);
        $data['call_type'] = 'ajax';
        if (isset($data['extended_action'])){
            
            if($data['extended_action'] == 'get'){
				$data['call_type'] = 'ajax';
                $return = $this->getExtendedForm($data);
            }
            else if($data['extended_action'] == 'save'){
				//print_r($data['next_data']);
                $return = $this->saveCommon($data);
            }
            $return['ajaxmessage'] = 'Success';

            $return['ajaxresult'] = true;
            echo json_encode($return);
            exit();	           
        }    
        else if (isset($data['action'])){
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
        else if (isset($data['form'])){
            $func_to_call = $this->getFunctionMappings($data['form']);
            
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
        else if (isset($data['type'])){
            $func_to_call = $this->getFunctionMappings($data['type']);
            
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
    }	

          
    public function resetPassword($data){
        if($data['action'] == 'reset-password'){
            $user = $data['data'][$this->config['id']];
            $pass = $data['data']['password'];
            $pass2 = $data['data']['password2'];
            $return['ajaxresult'] = true;
            $return['jsFunction'] = "";
            $return['ajaxmessage'] = 'Password updated succesfully';		
            if($user == ''){
                $return['ajaxresult'] = true;
                $return['ajaxmessage'] = 'Please Choose a user';	
                $return['jsFunction'] .= 
                "
                showSweetError('Error','$return[ajaxmessage]');
                ";                     
            }
            else{
                if(strlen($pass) < 6 || strlen($pass2) < 6){
                    $return['ajaxresult'] = true;
                    $return['ajaxmessage'] = 'Password Should be atleast 6 characters';	
                    $return['jsFunction'] .= 
                    "
                    showSweetError('Error','$return[ajaxmessage]');
                        ";                 
                }

                else {
                    if($pass != $pass2){
                        $return['ajaxresult'] = true;
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
                            CloseBsModal('$data[unique_id]-main-container');
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
    
    function saveCommon($data){
        $form_config = $this->setFormConfiguration($data);
        $return = array();      
        $return['jsFunction'] = '';         
        $unique_id = $data['unique_id'];
        $return['jsFunction'] .= 
        "
            CloseBsModal('$unique_id-main-container');
            $('#$unique_id-main-container').on('hidden.bs.modal', function () {            
                $('#$unique_id-main-container').remove();
            });            
        ";
		$return['jsFunction'] .= getDefault($data['next_options']['jsFunction'],'');
        if(isset($form_config['on_submit']) && $form_config['on_submit'] !=''){
            if(!$form_config['is_multiple_form']){
                if($form_config['on_submit'] == 'insert'){
                    $return['after_insert'] = $this->insertEx($data,'',false,$form_config);
                    $data['id'] = $return['after_insert']['id'];
                    if(__PAGE_TYPE__ == 'table'){
                        $this->setTableFields();
                        $sql = (trim($this->sql) != '') ? $this->sql : $this->generateSqlPullData();
                        $db_row = $this->getSingleRowFromDB("$sql AND main.{$this->config['id']}='$data[id]'");
                        $col_data_temp = $this->createArrayForColumns($db_row);
                        $newRow = $this->createTableRow($col_data_temp,$db_row);
                        $return['newrow'] = $newRow;
                        $return['jsFunction'] .= 
                        "
                            var newRow = value.newrow;
                            $('#table-tbody').prepend(newRow);	
                            showMessage('success','New $this->common_title Has Been Added', 'Success!');                    
                        "; 
                    } 
                    else if(__PAGE_TYPE__ == 'collapsible-list' || $data['next_data']['return'] == 'collapsible-list'){
                        $this->setTableFields();
                        $sql = (trim($this->sql) != '') ? $this->sql : $this->generateSqlPullData();
                        $db_row = $this->getSingleRowFromDB("$sql AND main.{$this->config['id']}='$data[id]'");
						$db_row['i'] = time();
						$db_row['__list_type__'] = $data['next_data']['__list_type__'];
						$db_row['parent_id'] = $data['next_data']['parent_id'];
						$db_row['parent_module'] = $data['next_data']['parent_module'];
						$db_row['child_module'] = $data['next_data']['child_module'];
                        $newRow = $this->getNestableCollapsibleList($db_row);
                        $return['newrow'] = $newRow['newrow'];
						$return['jsFunction'] .= 
						"
							var newRow = value.newrow;
							console.log(value.newrow);
							showMessage('success','New $this->common_title Has Been Added', 'Success!');
						";
						//print_r(['next_data']);
						
						if($data['next_data']['__list_type__'] == 'secondary'){
							$return['jsFunction'] .= 
							"
								$('#collapsible-primary-container-{$data['next_data']['parent_id']}').prepend(newRow);
							"; 							
						}
						else{
							$return['jsFunction'] .= 
							"
								$('#nestable_main_ol').prepend(newRow);
							"; 	

						}
                    } 
                    else{
                        $return['jsFunction'] .=
                        "
                        showMessage('success','Updated Successfully', 'Success!');       
                        ";
                    }                        
                }
                else if($form_config['on_submit'] == 'update'){
                    $return['after_insert'] = $this->updateEx($data,$form_config);
                    $data['id'] = $return['after_insert']['id'];  
                    if(__PAGE_TYPE__ == 'table'){
                        $this->setTableFields();
                        $sql = (trim($this->sql) != '') ? $this->sql : $this->generateSqlPullData();
                        $db_row = $this->getSingleRowFromDB("$sql AND main.{$this->config['id']}='$data[id]'");

                        $col_data_temp = $this->createArrayForColumns($db_row);
                        $newRow = $this->createTableTdOnly($col_data_temp,$db_row);
                        $return['newrow'] = $newRow;
                        $return['jsFunction'] .= 
                        "            
                            var newRow = value.newrow;
                            $('#tr-$data[id]').html(newRow);
                            $('#tr-$data[id]').addClass('recently-edited');
                        ";                       
                    }
                    else if(__PAGE_TYPE__ == 'collapsible-list' || $data['next_data']['return'] == 'collapsible-list'){
                        $this->setTableFields();
                        $sql = (trim($this->sql) != '') ? $this->sql : $this->generateSqlPullData();
                        $db_row = $this->getSingleRowFromDB("$sql AND main.{$this->config['id']}='$data[id]'");
						$db_row['name'] = (strlen($db_row['name']) > 60) ? substr($db_row['name'],0,55).'...' : $db_row['name'];
						$return['jsFunction'] .= 
						"
							showMessage('success','New $this->common_title Has Been Updated', 'Success!');
						";
						//print_r(['next_data']);
						
						if($data['next_data']['__list_type__'] == 'secondary'){
							$return['jsFunction'] .= 
							"
								$('#collapsible-secondary-label-{$data['id']}').html('$db_row[name]');
							"; 							
						}
						else{
							$return['jsFunction'] .= 
							"
								$('#collapsible-primary-label-{$data['id']}').html('$db_row[name]');
							"; 	

						}
                    } 
					
                    else{
                        $return['jsFunction'] .=
                        "
                        showMessage('success','Updated Successfully', 'Success!');       
                        ";
                    }
                }
                            
            }
            else{ /// If Multiple Form
                if($form_config['on_submit'] == 'insert'){
                    $return['after_insert'] = $this->insertMultiple($data,'',false,$form_config);
                    $data['id'] = $return['after_insert']['id']; 
                    if(__PAGE_TYPE__ == 'table'){
                        $this->setTableFields();
                        $sql = $this->generateSqlPullData();
                        $db_row = $this->getSingleRowFromDB("$sql AND main.{$this->config['id']}='$data[id]'");
                        $col_data_temp = $this->createArrayForColumns($db_row);
                        $newRow = $this->createTableRow($col_data_temp,$db_row);
                        $return['newrow'] = $newRow;
                        $return['jsFunction'] .= 
                        "
                            var newRow = value.newrow;
                            $('#table-tbody').prepend(newRow);	
                            showMessage('success','New $this->common_title Has Been Added', 'Success!');                    
                        "; 
                    }
                    else{
                        $return['jsFunction'] .=
                        "
                        showMessage('success','Updated Successfully', 'Success!');       
                        ";
                    }                    
                }
                else if($form_config['on_submit'] == 'update'){
                    $return['post_data_mltiple'] = $data;
                    /// TODO DELETE ALL FIRST AND THEN INSERT NEW
                    $return['after_insert'] = $this->updateMultiple($data,'',false,$form_config);
                    $data['id'] = $return['after_insert']['id']; 
                    if(__PAGE_TYPE__ == 'table'){
                        $this->setTableFields();
                        $sql = $this->generateSqlPullData();
                        $db_row = $this->getSingleRowFromDB("$sql AND main.{$this->config['id']}='$data[id]'");
                        $col_data_temp = $this->createArrayForColumns($db_row);
                        $newRow = $this->createTableRow($col_data_temp,$db_row);
                        $return['newrow'] = $newRow;
                        $return['jsFunction'] .= 
                        "
                            var newRow = value.newrow;
                            $('#table-tbody').prepend(newRow);	
                            showMessage('success','New $this->common_title Has Been Added', 'Success!');                    
                        "; 
                    }   
                    else{
                        $return['jsFunction'] .=
                        "
                        //console.log(value);
                        showMessage('success','Updated Successfully', 'Success!');       
                        ";
                    }                    
                }
            }
            if($return['after_insert']['id'] != 0 && (isset($data['next_ext']) || isset($data['next_action']))){
                
                $return['jsFunction'] .= 
                "
                value = value.next;
                //console.log(value);
                ";
                if(isset($data['next_action'])){
                    $func_to_call = $this->getFunctionMappings($data['next_action']);
                    
                    if($func_to_call){
                        $data['form_action'] = $data['next_form_action'];
                        $return['next'] = call_user_func_array(array($this, $func_to_call), array($data));
                    }
                    else{
                        $this->extendAjax($data);    
                    }                    
                }
                else if(isset($data['next_ext']) && isset($data['next_form_type'])){
                    $data['extended_action'] = $data['next_ext'];
                    $data['form_type'] = $data['next_form_type'];
                    $return['next'] = $this->getExtendedForm($data);
                }
                $return['jsFunction'] .= 
                "
                eval(value.jsFunction);
                ";                
/*                 foreach($form_config['return_after_save'] as $return_action){
                    
                    if($return_action['type'] == 'code'){
                        eval($return_actions);
                        //call_user_func_array(array($this, $return_action), array($data));                
                    }            
                    else if($return_action['type'] == 'function'){
                        if($return_action['class'] == 'this'){
                            $return[$return_action['name']] = call_user_func_array(array($this, $return_action['name']), array($data));
                            $return['jsFunction'] .= $return[$return_action['name']]['jsFunction'];
                        }
                        else if($return_action['type'] == ''){
                            $return[$return_action['name']] = call_user_func_array($return_action['name'], array($data));
                            $return['jsFunction'] .= $return[$return_action['name']]['jsFunction'];
                        }
                        else{
                            eval($return_action['name']);
                            $return[$return_action['name']] = call_user_func_array(array($this, $return_action['name']), array($data));
                            $return['jsFunction'] .= $return[$return_action['name']]['jsFunction'];
                        }
                        
                    }
                }
                $return['jsFunction'] .= $form_config['jsFunction_after_save'];   */ 
            }
        }

        
        return $return;
        //$return = $this->processReturn($form_config,$result);
    }  

    function getTypeAhead($data){
        $query = $data['query'];
        global $table;
        $this->setTableFields();
        $tempRowsNum = 100;
        $sql = $this->table['sql'];
        
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
        $return = $this->generateTable($sqlnum);
        $table = $table->getTable();
        $return['ajaxresult'] = true;
        $return['jsFunction'] .= 
        "
        console.log(response);
        $('#main-container-fluid').html(response.table);
                    
        ";        
        $return['sql'] = $sqlnum;
        $return['table'] = $table['table'];
        $return['jsFunction'] .= $table['jsFunction'];

        echo json_encode($return);
        exit();               
    }
     
    function ajaxSearch($data){
        $query = $data['table-simple-search'];
        global $table;
        $this->setTableFields();
        $tempRowsNum = 100;
        $sql = $this->table['sql'];
        
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
        $return = $this->generateTable($sqlnum);
        $table = $table->getTable();
        $return['ajaxresult'] = true;
        $return['jsFunction'] .= 
        "
        $('#main-container-fluid').html(value.table);   
        $('#table-simple-search').val('$query');
                    
        ";        
        $return['sql'] = $sqlnum;
        $return['table'] = $table['table'];
        $return['jsFunction'] .= $table['jsFunction'];

        echo json_encode($return);
        exit();        
    }
    
    function getOneTableRow($data){
        $new_id = $data['id'];

        $this->setTableFields();
        $sql = $this->table['sql'];
    
        $db_row = $this->getSingleRowFromDB("$sql AND main.{$this->config['id']}='$new_id'");
        $col_data_temp = $this->createArrayForColumns($db_row);
        $newRow = $this->createTableRow($col_data_temp, $db_row);
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'Success';
        $return['newrow'] = $newRow;
        $return['error'] = $db_row;
        $return['jsFunction'] = '';
    
        $return['jsFunction'] .= 
        "
        var newRow = value.getOneTableRow.newrow;
        console.log(value);
        $('#table-tbody').prepend(newRow);	
        showMessage('success','New $this->common_title Has Been Added', 'Success!');				
        ";	 
        return $return;        
    }

    
    function ajaxSave($data){
        $return['post'] = $data;
        unset($data['action']);
        //$data = $data[$this->dbTable];
        $result = $this->insert($data, $this->dbConn);

        if($result['id'] != 0){
            $new_id = $result['id'];

            $this->setTableFields();
            $sql = $this->generateSqlPullData();
        
            $db_row = $this->getSingleRowFromDB("$sql AND main.{$this->config['id']}='$new_id'");
            $col_data_temp = $this->createArrayForColumns($db_row);
            $newRow = $this->createTableRow($col_data_temp, $db_row);
            //$newRow = CreateMtTable($db_row);
            $return = $result;
            $return['ajaxresult'] = true;
            $return['ajaxmessage'] = 'Success';
            $return['newrow'] = $newRow;
            $return['error'] = $db_row;
            $return['jsFunction'] = '';
        
            $return['jsFunction'] .= 
            "
            var newRow = value.newrow;
            CloseBsModal('modalForm');
            $('#table-tbody').prepend(newRow);	
            showMessage('success','New $this->common_title Has Been Added', 'Success!');				
            ";	
                    
         } else {
            $return['sql'] = $result['sql'];                 
            $return['ajaxresult'] = false;
            $return['ajaxmessage'] = $result;
            $return['error'] = mysqli_error($this->dbConn); 
            $return['jsFunction'] = 
            "
            console.log(value);	
            ";                
         }

        return $return;         
    }

    function ajaxUpdate($data){
        unset($data['action']);
        $data = $data[$this->dbTable];			
        $id = $data['id'];			
        $result = $this->update($data);
        if($result['query']){
            
            $this->setTableFields();
            $sql = $this->generateSqlPullData();
            $db_row = $this->getSingleRowFromDB("$sql AND main.{$this->config['id']}='$id'");
            $col_data_temp = $this->createArrayForColumns($db_row);
            $newRow = $this->createTableTdOnly($col_data_temp,$db_row);
            $return['ajaxresult'] = true;
            $return['ajaxmessage'] = 'Success';
            $return['id'] = $id;
            $return['newrow'] = $newRow;
            $return['jsFunction'] = 
            "
            var newRow = value.newrow;
            CloseBsModal('modalForm');
            $('#tr-$id').html(newRow);
            $('#tr-$id').addClass('recently-edited');			
            ";		
         } else {
            $return['ajaxresult'] = false;
            $return['ajaxmessage'] = $result;		 
            $return['data'] = $data;		 
            $return['err'] = mysqli_error($this->dbConn);		 
         }
        return $return;
    }

    function ajaxSearchPaginate($data){
        global $table;
        
        $query_string = $data['query'];
        $table->rowsCountDisplay = $data['rows'];       
        $startLimit = ($data['currentPagination']-1)*$table->rowsCountDisplay;
        $stopLimit = $data['rows'];
        
        if ($stopLimit != $table->rowsCountDisplay)
        {
            $startLimit = 0;
        }
        
        $table->rowsCountDisplay = $stopLimit;
        $this->setTableFields();
        $sql = $this->generateSqlPullData();
        if(isset($this->table['sql'])){
            $sql = $this->table['sql'];          
        }        
        $tempRowsNum = $table->rowsCountDisplay; 
        if(trim($query_string) != ''){
            $sqlnum = 
            "
            $sql
            AND main.name LIKE '%$query_string%' OR main.page_name LIKE '%$query_string%'
            ORDER BY main.{$this->config['id']} DESC
            LIMIT $startLimit, $stopLimit
            ";   
        }
        else{
            $sqlnum = "$sql
            ORDER BY main.{$this->config['id']} DESC
            LIMIT $startLimit, $stopLimit
            ";            
        }
        //print_r($sqlnum);
       
        $row = $this->getDataForTable($sqlnum);       
        $tempnum= $this->numRows($sql);
        $table->totalRowsInDb = $tempnum;
        $table1 = '';
        $table->getRowCount($stopLimit, $table->totalRowsInDb,$data['currentPagination']);
        $table->getPagination($stopLimit, $table->totalRowsInDb,$data['currentPagination']);	
    
        foreach($row as $user) {
            $col_data_temp = $this->CreateArrayForColumns($user);
            $table1 .= $this->createTableRow($col_data_temp, $user, $user[$this->config['id']]);
        }			
        $return['ajaxresult'] = true;
        $return['rows_to_display'] = $table->rowsSelect;
        $return['table_pagination'] = $table->paginationCode;
        $return['table_body'] = $table1;
        $return['jsFunction'] = "//ComponentsBootstrapSelect.init();";
        $return['sql'] = $sql; 

        return $return;
    }

    function ajaxPolling($data){
        global $table;
        $this->setTableFields();                 
        global $table,$db,$sql;
        $table->headers = $this->generateTableHeaders($this->table);
        $table->filterFields = $this->generateFilterRow($this->table);    
        $table->bulkUpdateRow = $this->generateBulkUpdateRow();
        if($this->table['portlet-actions'] != ''){
            $table->actions = $this->table['portlet-actions'];                
        }
    
        $tempRowsNum = $table->rowsCountDisplay;

        if(isset($this->table['sql'])){
            $sql = $this->table['sql'];          
        }
        
        $last_id = $data['last_id'];
        $sqlnum = "$sql
        AND main.{$this->config['id']} > '$last_id'
        ORDER BY main.{$this->config['id']} DESC
        LIMIT $tempRowsNum
        ";
        $query = mysqli_query($this->dbConn,$sqlnum);
        $num_rows = mysqli_num_rows($query);
        $row = $this->getDataForTable($sqlnum);       
        $tempnum= $db->NumRows($sql);
        $table->totalRowsInDb = $tempnum;

        $table1 = '';

        $table->getPagination($table->rowsCountDisplay, $table->totalRowsInDb,1);		

        foreach($row as $user) {
            $col_data_temp = $this->createArrayForColumns($user,$this->table);
            $col_data_temp[] = '<span class="badge badge-roundless badge-success">new</span>';
            $table1 .= $this->createTableRow($col_data_temp, $user,'recently-edited animated bounceIn');	            
            //$table->table .= $table1; <span class="badge badge-roundless badge-success">new</span>
        }
        $lastTemp = reset($row);
        if($num_rows > 0){
            $return['last_id'] = $lastTemp[$this->config['id']]; 
        }
        else {
            $return['last_id'] = $last_id;
        }
        $return['jsFunction'] = $this->getDependencyJsTable($this->table);
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'Success';
        $return['table_body'] = $table1;
        $return['jsFunction'] = 
        "
        console.log(value);
        $('#table-tbody').prepend(value['table_body']);		
        //showMessage('success','$this->common_title Details Has Been Refreshed', 'Success!');				
        "; 
        return $return;        
    }

    function ajaxRefresh($data){
        global $table;
        $this->setTableFields();                 
        $newRow = $this->GenerateTable();
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'Success';
        $return['table_body'] = $table->getTable();
        $return['jsFunction'] = 
        "
        console.log(value);
        $('#main-container-fluid').html(value['table_body']);		
        showMessage('success','$this->common_title Details Has Been Refreshed', 'Success!');				
        "; 
        return $return;        
    }

    function ajaxDelete($data){
        global $table;
        $id = $data['id'];
        if($id == '' || $id == 0){
            $return['ajaxresult'] = true;
            $return['ajaxmessage'] = 'Success';    
            return $return;            
        }
        $return = $this->delete($id);
		

        if(__PAGE_TYPE__ == 'table'){ 
            $return['jsFunction'] = 
            "
            $('#tr-$id').remove();
            ";             
        }
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'Success';
        return $return;        
    }

    function ajaxDeleteNestable($data){
        $id = $data['id'];
        $return = $this->delete($id);
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'Success';
      
        $return['jsFunction'] = 
        "
		console.log(value);
        $('#$data[removeElement]').remove();
        ";  
        return $return;        
    }

    function ajaxDeleteBulk($data){
        $ids = $data['ids'];
        $return = $this->deleteBulk($ids);
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'success';
        $return['ids'] = $ids;
        $return['html'] = '';
        $return['jsFunction'] = 
        "
            UncheckMaster();
            HideBulkUpdateRow();
            var \$this = $('.fixed-action-btn');
            HideFixedActionButtons(\$this);                        
        ";
        return $return;        
    }
    
    function ajaxBulkUpdate($data){
        $ids = $data['ids'];
        $return = $this->bulkUpdate($data);
        if($return['query']){
            
            $this->setTableFields();
            $sql = $this->generateSqlPullData();
            
            $sql .= " AND main.{$this->config['id']} IN (".implode(", ", $ids).")";
            $ids = $data['ids'];                    
            $row = $this->getDataForTable($sql);

            $table1 = '';	
            $newRow = array();	

            foreach($row as $user) {
                $col_data_temp = $this->createArrayForColumns($user);
                $table1 = $this->createTableTdOnly($col_data_temp,$user);	                              
                $newRow[$user[$this->config['id']]] = $table1;
            }
            
            $return['ajaxresult'] = true;
            $return['ajaxmessage'] = 'Success';
            $return['ids'] = $ids;
            $return['newrow'] = $newRow;
            $return['jsFunction'] = 
            "
            var newRows = value.newrow;
            var ids = value.ids;
            UncheckMaster();
        
            HideBulkUpdateRow();
            var \$this = $('.fixed-action-btn');
            HideFixedActionButtons(\$this);   
            $.each(newRows,function(inum,row){
                $('#tr-'+inum+'').html(row);	                               
                $('#tr-'+inum+'').addClass('recently-edited');	                               
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

    function ajaxSaveAssocForm($data){
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'Success';		
        $this->getManyToManyFormFields();
        $mtm = $this->many_to_many[0];
        $datecreated = date('Y-m-d');
        $createdby = $_SESSION[get_session_values('id')];
        $joining = $mtm['joining'];
        $this_fk_field = $mtm[$data['assoc']]['this_fk_in_joining'];
        $mtm_fk_field = $mtm[$data['assoc']]['fk_in_joining'];              
        $this_fk_value = $data[$mtm[$data['assoc']]['this_fk_in_joining']];
        $dataArray[$this_fk_field] = $this_fk_value;                  
                         
        $dataArray['status'] = 'active'; 
        $sqlDel = "DELETE FROM $joining WHERE $this_fk_field='$this_fk_value'";
        $queryDel = $this->query($sqlDel);
    

        $return['post'] = $data;                    
        $tempsql = '';
        if(isset($data['modules'])){
            foreach($data['modules'] as $mtm_id => $module){

                if(is_array($module)){
                    foreach($mtm['fields'] as $field=>$optns){
                        if(!isset($optns['save']) || $optns['save'] === false){
                            $dataArray[$mtm_fk_field] = $mtm_id;                                 
                            $dataArray[$field] = isset($module[$field])? $module[$field]:'';          
                        }
                                                   
                    }
                            $tempsql .= "
                            ('".implode("','", $dataArray)."'),";
                   }
            }
            $sql = "INSERT INTO $joining (".implode(', ', array_keys($dataArray)).")
            VALUES 
            
            "; 
            
            $sql1 = rtrim($tempsql, ',');
            $sql .= $sql1;
            $query = mysqli_query($this->dbConn, $sql);			

            $return['error'] = mysqli_error($this->dbConn);			
            $return['sql'] = $sql;
        }
        //$return['testerror'] = $this->GetModal(json_encode($data),'');
        //$return['jsFunction'] = "ajaxElement.html(value['testerror']);";                    
        $return['jsFunction'] = 
        "
        console.log(value.post);
        console.log(value.error);
        CloseBsModal('modalForm');
        ";
        return $return;        
                    
    }

    function ajaxSaveBulkAssocForm($data){
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'Success';		
        $this->getManyToManyFormFields();
        $mtm = $this->many_to_many[0];
        $datecreated = date('Y-m-d');
        $createdby = $_SESSION[get_session_values('id')];
        $joining = $mtm['joining'];
        $this_fk_field = $mtm[$data['assoc']]['this_fk_in_joining'];
        $mtm_fk_field = $mtm[$data['assoc']]['fk_in_joining'];  
        $ids = array();        
        $ids = $data['ids'];
        $idArray = explode(',',$ids);
        //$dataArray[$this_fk_field] = $this_fk_value;  

       
        $sqlDel = "DELETE FROM $joining WHERE $this_fk_field IN (". $ids .")";  
        $queryDel = $this->query($sqlDel);

        $return['post'] = $data;                    
        $tempsql = '';
        
        foreach($idArray as $this_fk_value){
            $dataArray[$this_fk_field] = $this_fk_value; 
            $dataArray['status'] = 'active';             
            if(isset($data['modules'])){
                foreach($data['modules'] as $mtm_id => $module){

                    if(is_array($module)){
                        foreach($mtm['fields'] as $field=>$optns){
                            if(!isset($optns['save']) || $optns['save'] === false){
                                $dataArray[$mtm_fk_field] = $mtm_id;                                 
                                $dataArray[$field] = isset($module[$field])? $module[$field]:'';          
                            }
                                                       
                        }
                                $tempsql .= "
                                ('".implode("','", $dataArray)."'),";
                       }
                }

            }
                      
        }
        $sql = "INSERT INTO $joining (".implode(', ', array_keys($dataArray)).")
        VALUES 
        
        "; 
        
        $sql1 = rtrim($tempsql, ',');
        $sql .= $sql1;
        $query = mysqli_query($this->dbConn, $sql);			
        //print_r($sql);
        $return['error'] = mysqli_error($this->dbConn);			
        $return['sql'] = $sql;           
        $return['jsFunction'] = 
        "
        UncheckMaster();
        console.log(value.post);
        console.log(value.error);
        CloseBsModal('modalForm');
        ";
        return $return;        
                    
    }

    function extendAjax($data){
        $this->errorAjax($data);      
    }	

    function errorAjax($data){
        
        var_export($data);
        $return['ajaxmessage'] = 'This Action is Not Defined For This Page';				
        $return['post'] = $data;
        $return['table'] = $this->dbTable;
        $return['jsFunction'] = 
        "
        console.log(value.ajaxmessage);		
        ";			
        echo json_encode($return);
        exit();        
    }

}


?>