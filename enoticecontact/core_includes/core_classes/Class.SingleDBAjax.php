<?php 
Class SingleDBAjax extends SingleDBHtml
{

    function __construct(){

    }	

    function handleAjaxCall($data){
        //sleep(2);
        global $table,$db,$sql;
        $return = array();
        $return['debug'] = false;	
        $return['ajaxresult'] = false;	

        if (isset($data['extended_action'])) 
        {
            $data['call_type'] = 'ajax';
            
            if($data['extended_action'] == 'get'){
                $return = $this->getExtendedForm($data);
            }
            else if($data['extended_action'] == 'save'){
                $return = $this->saveCommon();
            }
            
            $return['ajaxmessage'] = 'Success';
            $return['ajaxresult'] = true;
            echo json_encode($return);
            exit();	           
        }
        
        if (isset($data['action'])) 
        {
            if($data['action'] == 'compare-dates')
            {
                $start_date = $data['start_date'];
                $end_date = $data['end_date'];
                //$start_date = strtotime($start_date);
                $start_date = date('Y-m-d H:i:s',strtotime($start_date));
                $end_date = date('Y-m-d H:i:s',strtotime($end_date));
                //$end_date = strtotime($end_date);
                if($end_date > $start_date){
                   $return['ajaxresult'] = true;
                   $return['checkResult'] = true;

                }
                else{
                    $return['ajaxresult'] = true;
                   $return['checkResult'] = false;
                }
                $return['start_date'] = $start_date;
                $return['end_date'] = $end_date;
               $return['jsFunction'] = 
               "
               vCheck['result'] = value.checkResult;
               console.log(value); 
               console.log(vCheck); 
               ";                
                echo json_encode($return);
                exit();
            }
            if($data['action'] == 'polling')
            {
                $return = $this->ajaxPolling($data);
                echo json_encode($return);
                exit();
            }
            if($data['action'] == 'searchwithpaginate')
            {
                $return = $this->ajaxSearchPaginate($data);
                echo json_encode($return);
                exit();
            }	
        
            else if($data['action'] == 'save'){

                $return = $this->ajaxSave($data);
                echo json_encode($return);
                exit();	
                
            }	

            else if($data['action'] == 'update'){

                $return = $this->ajaxUpdate($data);            
                echo json_encode($return);
                exit();	
                
            }	

            else if($data['action'] == 'refresh'){
                $return = $this->ajaxRefresh($data);
                echo json_encode($return);
                exit();	
                
            }	
                            
            else if ($data['action'] == 'delete') 
                {
                    $return = $this->ajaxDelete($data);
                    echo json_encode($return);
                    exit();

                }	
                            
            else if ($data['action'] == 'delete-nestable') 
                {
                    $return = $this->ajaxDeleteNestable($data);
                    echo json_encode($return);
                    exit();

                }		
            else if ($data['action'] == 'delete-bulk') 
                {
                    $return = $this->ajaxDeleteBulk($data);
                    echo json_encode($return);
                    exit();

                }
            else if ($data['action'] == 'bulk-update') 
                {
                    $return = $this->ajaxBulkUpdate($data);
                    echo json_encode($return);
                    exit();

                }                
            else if ($data['action'] == 'check-for-duplicate') 
                {
                    $this->checkForDuplicate($data);
                }
            else if($data['action'] == 'save-assoc-form')
                {
                    $return = $this->ajaxSaveAssocForm($data);                    
                    echo json_encode($return);
                    exit();		
                }  
            else if($data['action'] == 'save-bulk-assoc-form')
                {
                    $return = $this->ajaxSaveBulkAssocForm($data);                    
                    echo json_encode($return);
                    exit();		
                }        
                    
            else{
                $this->extendAjax($data);    
            }
        }

        if(isset($data['type']))
        {
            if($data['type'] == 'new-form') {
                $return = $this->getNewForm('modal',true,$data);
                $return['ajaxmessage'] = 'Success';
				$return['ajaxresult'] = true;
                echo json_encode($return);
                exit();	

            }
            if($data['type'] == 'repeat-form') {
                $return = $this->getNewForm('rows',false,$data);
                $return['ajaxmessage'] = 'Success';
				$return['ajaxresult'] = true;
                echo json_encode($return);
                exit();	

            }

            if($data['type'] == 'edit-form') {
                $id = $data['id'];
                $this->loadFromDB($id);
                $return = $this->getEditForm('modal',true,$data);
                $return['ajaxresult'] = true;
                $return['ajaxmessage'] = 'Success';
                echo json_encode($return);
                exit();	
            }
            if($data['type'] == 'save-and-get-next'){
                unset($data['action']);
                $data1 = $data[$this->dbTable];
                $result = $this->insert($data1, $this->dbConn);
                if($result['id'] != 0){
                    $new_id = $result['id'];
                    
                    $this->setTableFields();
                    $sql = $this->generateSqlPullData();
                    $db_row = $this->getSingleRowFromDB("$sql WHERE main.id='$new_id'");
                    $col_data_temp = $this->createArrayForColumns($db_row);
                    $newRow = $this->createTableRow($col_data_temp, $db_row['id']);	 
                    $assoc = 'assoc';
                    if($this->assoc_with_same_table != 'assoc') $assoc = $data1[$this->assoc_with_same_table];
                    $temp = $this->getMtmForm($new_id,$assoc);
                    
                    $return['newrow'] = $newRow;
                    $return['tools'][] = array();
                    $return['ajaxresult'] = true;
                    $return['ajaxmessage'] = 'Success';
                    $return['nexthtml'] = '';
                    $return['jsFunction'] = $temp['jsFunction']	;
                    $return['jsFunction'] .= "
                    var newRow = value.newrow;
                    $('#table-tbody').prepend(newRow);                
                    "	;
                    
                    $return['nexthtml'] .= $temp['html'];	
                 } else {
                    $return['ajaxresult'] = false;
                    $return['ajaxmessage'] = $result;		 
                 }

                echo json_encode($return);
                exit();	
            }       
            if($data['type'] == 'bulk-edit-form') {

                $id = $data['id'];
                $form_fields = $this->getBulkEditForm($id, $this->dbConn, true);
                    $return['ajaxresult'] = true;
                    $return['ajaxmessage'] = 'Success';
                    $return['jsFunction'] = '';
        
            }
            if($data['type'] == 'edit-assoc-form'){
                unset($data['action']);
                $new_id = $data['id'];
                $assoc = 'assoc';
                if($this->assoc_with_same_table != 'assoc'){
                    $row = $this->loadFromDB($new_id);
                    $assoc = $row[$this->assoc_with_same_table];                    
                }
                $return = $this->getMtmForm($new_id,$assoc,false);
                $return['tools'][] = array();
                $return['ajaxresult'] = true;

                $return['ajaxmessage'] = 'Success';


                echo json_encode($return);
                exit();	
            }
            if($data['type'] == 'edit-assoc-form-multiple'){
                unset($data['action']);
                $new_id = $data['id'];
                $assoc = 'assoc';
                if($this->assoc_with_same_table != 'assoc'){
                    $row = $this->loadFromDB($new_id);
                    $assoc = $row[$this->assoc_with_same_table];                    
                }
                $return = $this->getMtmForm($new_id,$assoc,true);
                $return['tools'][] = array();
                $return['ajaxresult'] = true;

                $return['ajaxmessage'] = 'Success';


                echo json_encode($return);
                exit();	
            }
            if($data['type'] == 'get-dep-fields') {
                $parent = $data['parent'];
                $value = $data['value'];
                $field = $data['field'];
                $custom_field = isset($data['custom_field'])?$data['custom_field']:false;
                $return = $this->getAjaxDependentFields($field,$value,$custom_field);
                $return['ajaxresult'] = true;
                $return['ajaxmessage'] = 'Success';
                echo json_encode($return);
                exit();	
            }
            if($data['type'] == 'choose-form') {
                $value = $data['value'];
                $field = $data['field'];
                $temp = $this->getChooseForm();
                $return['html'] = 
                '
                <div class="col-md-4 col-md-offset-4 well animated hidden" id="choose-to" style="position:fixed;top:80px;left:0px;z-index:9">
                '.$temp.'
                </div>
                ';
                $return['ajaxresult'] = true;
                $return['ajaxmessage'] = 'Success';
                $return['jsFunction'] =  $temp['jsFunction'];          
                echo json_encode($return);
                exit();	
            }
            else{
                $this->extendAjax($data);    
            }	
        }
            
    }	

    function ajaxSave($data){
        $return['post'] = $data;
        unset($data['action']);
        $data = $data[$this->dbTable];
        $result = $this->insert($data, $this->dbConn);

        if($result['id'] != 0){
            $new_id = $result['id'];

            $this->setTableFields();
            $sql = $this->generateSqlPullData();
        
            $db_row = $this->getSingleRowFromDB("$sql WHERE main.id='$new_id'");
            $col_data_temp = $this->createArrayForColumns($db_row);
            $newRow = $this->createTableRow($col_data_temp, $db_row['id']);	                    
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
            console.log(value);
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
        
            $db_row = $this->getSingleRowFromDB("$sql WHERE main.id='$id'");
            $col_data_temp = $this->createArrayForColumns($db_row);
            $newRow = $this->createTableTdOnly($col_data_temp);	                          
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
        global $table,$db,$sql;
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
        $sqlnum = "$sql
        ORDER BY main.id DESC
        LIMIT $startLimit, $stopLimit
        ";
       
        $tempnum= $db->NumRows($sql);
        $table->totalRowsInDb = $tempnum;
        $row = $db->FetchAll($sqlnum);
        $table1 = '';
        $table->getRowCount($stopLimit, $table->totalRowsInDb,$data['currentPagination']);
        $table->getPagination($stopLimit, $table->totalRowsInDb,$data['currentPagination']);	
    
        foreach($row as $user) {
            $col_data_temp = $this->CreateArrayForColumns($user);
            $table1 .= $this->createTableRow($col_data_temp, $user['id']);	                    
        }			
        $return['ajaxresult'] = true;
        $return['rows_to_display'] = $table->rowsSelect;
        $return['table_pagination'] = $table->paginationCode;
        $return['table_body'] = $table1;
        $return['jsFunction'] = "ComponentsBootstrapSelect.init();";
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
        WHERE id > '$last_id'
        ORDER BY main.id DESC
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
            $table1 .= $this->createTableRow($col_data_temp, $user['id'],'recently-edited animated bounceIn');	            
            //$table->table .= $table1; <span class="badge badge-roundless badge-success">new</span>
        }
        $lastTemp = reset($row);
        if($num_rows > 0){
            $return['last_id'] = $lastTemp['id']; 
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
        $return = $this->delete($id);
        $this->setTableFields();
        $num = $table->rowsCountDisplay  - 1;
        $sql = $this->generateSqlPullData();
    
        $db_row = $this->getSingleRowFromDB("$sql LIMIT $num,1");
        $newRow = '';
        if($db_row != null){
            $col_data_temp = $this->createArrayForColumns($db_row);
            $newRow = $this->createTableRow($col_data_temp, $db_row['id']);	
            
        }
        $return['newrow'] = $newRow;                          
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'Success';
      
        $return['jsFunction'] = 
        "
        $('#table-tbody').append(value.newrow);
        ";  
        return $return;        
    }

    function ajaxDeleteNestable($data){
        global $table;
        $id = $data['id'];
        $return = $this->delete($id);                      
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'Success';
      
        $return['jsFunction'] = 
        "
        $('#'+data['removeElement']+'').remove();
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
            
            $sql .= " WHERE main.id IN (".implode(", ", $ids).")";
            $ids = $data['ids'];                    
            $row = $this->getDataForTable($sql);

            $table1 = '';	
            $newRow = array();	

            foreach($row as $user) {
                $col_data_temp = $this->createArrayForColumns($user);
                $table1 = $this->createTableTdOnly($col_data_temp);	                              
                $newRow[$user['id']] = $table1;
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