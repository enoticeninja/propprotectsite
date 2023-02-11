<?php 
Class SingleDBHtmlV2 extends SingleDBFormGeneratorV2
{
    public $table;
    public $dataTable = array();/// Holds the table data passed from getDataForTable() in Core Class
    function SingleDBHtml(){

    }	

    function setTableFields($merge=true){
        $this->getTableFields();
    }
    
    function getTableFields(){
        $this->table = getCommonTableFields();
    }

    function setTableClassElements(){
        global $table;
        
        $table->filterRows = '';
        $jsFunc = 'TableSimpleSearch();';
        $table->jsFunction .= 
        "
        $('#table-simple-search').on('keydown',function(e){
            if ( e.which == 13 ){
                e.preventDefault();		
                $jsFunc
            }else{
                return true;		
            }});        
        ";

        $table->actions[] = '
                        <div class="portlet-input input-inline  col-md-4" style="width: 400px;">
                        <form id="table-simple-search-form" onSubmit="return false;">
                            <div class="input-group">
                                <div class="input-icon">
                                    <i class="fa fa-search"></i>
                                    <input id="table-simple-search" class="form-control" type="text" name="table-simple-search" placeholder="Search...">
                                </div>
                                <span class="input-group-btn">
                                    <a href="javascript:;" id="table-simple-search-btn" class="btn bg-color-primary-custom"   onClick="'.$jsFunc.'">
                                    <i class="fa fa-search"></i> Search</a>
                                </span>
                            </div>
                            </form>
                        </div>
                        
                        ';	
                       
        /* $table->tableActionFields[] = '<a href="#modalForm" type="button" class="btn btn-sm yellow dropdown-toggle" data-toggle="modal" aria-expanded="false" onClick="GetForm(\'new-form\',\'\',\'add-mtrow\',\'modalForm\')">Add '.$class->common_title.'</a>'; */

        $table->tableActionsWrapper = '';
        $table->tableTitle = $this->common_title;
        $table->hasNewRow = false;
        $table->hasSaveChanges = false;
        $table->pagination = true;		
        $table->portletClass = 'light';	
        $table->iconClass = 'fa fa-user';	        
        $table->tableContainerClass = 'animated fadeIn';	        
    }    

    function getCommonTableFields(){
        ////Specify all the fields here and override them in custom-cols later
        $this->table['cols'] = array();
        $this->table['field_functions'] = array();
        $this->table['col_functions'] = array();/// functions to be executed on any field if needed
        $this->setTableClassElements();
        $this->table['where_condition'] = false;
        $this->table['has_checkbox'] = true;
        $this->table['has_status_label'] = true;
        $this->table['custom_headers'] = false; //array
        $this->table['typeahead'] = false;           
        $this->table['typeahead_fields'] = array('name','page_name');          
        $this->table['typeahead_html'] = '<span ><div><strong>{{name}}</strong> - {{page_name}} - {{type}} </div></span>';          
        $this->table['search'] = false;          
        $this->table['search_fields'] = array('name','page_name');          
        $this->table['status_field'] = 'status';
        $this->table['status_label']['active'] = '<span class="label label-success"><i class="fa fa-check"></i> ACTIVE </span>';
        $this->table['status_label']['inactive'] = '<span class="label label-danger"><i class="fa fa-wrong"> INACTIVE </span>';
        $this->table['tr_buttons'] = array();
        $this->table['tr_buttons']['edit'] = '<a href="javascript:;" type="button" class="btn btn-outline green dropdown-toggle"  onClick="CommonFunc2({\'extended_action\':\'get\',\'form_type\':\'edit\',\'id\':\'__ID__\',\'module\':\''.$this->dbTable.'\'},\'\',this)">Edit >> __NAME_FIELD__</a>';	
        $this->table['tr_buttons']['delete'] = '<a href="#" type="button" class="btn btn-outline red btnDelete" data-toggle="confirmation" data-placement="bottom" id="btnDelete" onClick="Delete(\''.$this->dbTable.'\',\'__ID__\',this)">Delete >> __NAME_FIELD__</a>';	
        $this->table['th_buttons'] = array(
                            'refresh'=>'<a href="#" type="button" class="btn red" onClick="CommonFunc({\'action\':\'refresh\'})"><i class="icon-refresh" ></i>Refresh</a>'	
                            );
        $this->table['portlet_actions']['new_ajax'] =   
        '
        <a href="javascript:;" type="button" class="btn bg-color-primary-custom ladda-button"  data-style="zoom-out" data-size="l" onClick="CommonFunc2Btn({\'extended_action\':\'get\',\'form_type\':\'new_ajax\'},\'\',this)"><i class="fa fa-plus"></i> Add </a>
        ';      
        
        $this->table['custom_cols'] = array();
        $this->table['eval'] = array();
        return $this->table;	
    }

    function createTableTdOnly($data,$userData){
        
        $row_data = '';

        foreach($data as $col) {
            $row_data .= 
            '
            <td>'.$col.'</td>
            ';
        }
        $row_data = str_replace('__ID__',$userData[$this->config['id']],$row_data);
        $row_data = str_replace('__NAME_FIELD__',$userData[$this->config['name']],$row_data);        
        return $row_data;
    }
   
    function createTableRow($data, $userData, $trclass=''){
        $trid = $userData[$this->config['id']];        
        if($trid != ''){
            $row_data = 
            '
            <tr class="mt-element-ribbon '.$trclass.' '.$this->dbTable.'-tr" id="tr-'.$trid.'" >
            ';		
        }
        else{
            $row_data = 
            '
            <tr class="mt-element-ribbon '.$trclass.' '.$this->dbTable.'-tr" id="tr-'.$data['id'].'">
            ';		
        }

            foreach($data as $key=>$col) {
            $row_data .= 
            '
            <td class="td-'.$key.'" id="td-'.$trid.'-'.$key.'">'.$col.'</td>
            ';
            }
        $row_data .= 
        '
        </tr>
        ';
        $row_data = str_replace('__ID__',$userData[$this->config['id']],$row_data);
        $row_data = str_replace('__NAME_FIELD__',$userData[$this->config['name']],$row_data);
        $row_data = replaceBetweenSquareBrackets($userData,$row_data);
        return $row_data;
    }

    function generateTable($custom_sql=''){
        global $table;
        $table->headers = $this->generateTableHeaders($this->table);
        $table->filterFields = $this->generateFilterRow($this->table);    
        $table->bulkUpdateRow = $this->generateBulkUpdateRow();
        if(isset($this->table['portlet_actions']) && $this->table['portlet_actions'] != ''){
            $table->actions = array_merge($table->actions,$this->table['portlet_actions']);                
        }
    
        $tempRowsNum = $table->rowsCountDisplay;
        if($custom_sql != ''){
            $sql = $custom_sql;
        }
        else {
            $sql = $this->table['sql'];          
        }
        $sqlnum = "$sql
        ORDER BY main.{$this->config['id']} DESC
        LIMIT $tempRowsNum
        ";
        $row = $this->getDataForTable($sqlnum);       
        $tempnum= $this->numRows($sql);
        $table->totalRowsInDb = $tempnum;

        $table1 = '';

        $table->getPagination($table->rowsCountDisplay, $table->totalRowsInDb,1);		
        $table->table ='';
        foreach($row as $user) {
            $return['rows'][] = $user;
            $col_data_temp = $this->createArrayForColumns($user,$this->table);
            $table1 = $this->createTableRow($col_data_temp, $user);	            
            $table->table .= $table1;
        }
        $lastTemp = reset($row);
        $return['last_id'] = $lastTemp[$this->config['id']];
        //$return['jsFunction'] = $this->getDependencyJsTable($this->table);
        $return['jsFunction'] = '';
        return $return;        
    }

    function generateTableCustom($sql,$cols=''){
        global $table;
        $table->headers = $this->generateTableHeaders($this->table);
        $table->filterFields = $this->generateFilterRow($this->table);    
        $table->bulkUpdateRow = $this->generateBulkUpdateRow();
        if($this->table['portlet_actions'] != ''){
            $table->actions = $this->table['portlet_actions'];                
        }
    
        $tempRowsNum = $table->rowsCountDisplay;
 
        $sqlnum = "$sql
        ORDER BY main.id DESC
        LIMIT $tempRowsNum
        ";
        $row = $this->getDataForTable($sqlnum);       
        $tempnum= $this->numRows($sql);
        $table->totalRowsInDb = $tempnum;

        $table1 = '';

        $table->getPagination($table->rowsCountDisplay, $table->totalRowsInDb,1);		

        foreach($row as $user) {
            $col_data_temp = $this->createArrayForColumns($user,$this->table);
            $table1 = $this->createTableRow($col_data_temp, $user['id']);	            
            $table->table .= $table1;
        }
        $return['jsFunction'] = $this->getDependencyJsTable($this->table);
        return $return;        
    }

    function getDependencyJsTable($table){
        $arr['jsFunction'] = '';
        if(isset($table['bulk_update_fields'])){
            $tempDep['dependency'] = $this->dependency;
            $tempDep['fields'] = $table['bulk_update_fields'];
            $tempDepen = json_encode($tempDep);
            $arr['jsFunction'] = "var value = JSON.parse('$tempDepen');";

            $arr['jsFunction'] .= 
            "
            var currentSelectedDependencyBulk = false;
            ";        
            foreach($this->dependency as $depKey=>$depValue){
                if(isset($table['bulk_update_fields'][$depKey])){            
                    $arr['jsFunction'] .= 
                    "        
                        $(document).on('change','#bulk-$depKey', function(){
                            var dep = value.dependency.$depKey;
                            var prevSelected = currentSelectedDependencyBulk;
                            var selected = $('#bulk-$depKey').val(); 
                            currentSelectedDependencyBulk = selected;
                            var delay = 500;
                            console.log(prevSelected);
                            console.log(currentSelectedDependencyBulk);
                            
                            if(prevSelected && dep.hasOwnProperty(prevSelected)){
                                if (dep[prevSelected].hasOwnProperty('hide')) {
                                    jQuery.each(dep[prevSelected].hide, function(i, elem){
                                       $('#bulk-'+elem+'').closest('.form-common-element-wrapper').removeClass('animated'+delay+' zoomOut hidden');
                                       $('#bulk-'+elem+'').closest('.form-common-element-wrapper').addClass('zoomIn');
                                    });	
                                }                                
                            } 

                            
                            if (dep.hasOwnProperty(selected)) {
                                
                                if (dep[selected].hasOwnProperty('hide')) {
                                    jQuery.each(dep[selected].hide, function(i, elem){
                                       $('#bulk-'+elem+'').closest('.form-common-element-wrapper').addClass('animated'+delay+' zoomOut');
                                       window.setTimeout(function() { 
                                            $('#bulk-'+elem+'').closest('.form-common-element-wrapper').addClass('hidden');
                                       }, delay);
                                    });
                                }
                            }			

                        });    
                        ";   
                }                    
            }

        }
        return $arr['jsFunction'];
    }

    function generateDataTable($table){
        global $sql;

        if(isset($table['sql'])){
            $sql = $table['sql'];          
        }
        
        $sqlnum = "$sql
        ORDER BY id DESC
        ";
        $row = $this->getDataForTable($sqlnum);          
        $tempnum= $this->numRows($sql);

        $table1 = array();	
        
        foreach($row as $user) {
            $table1[] = $this->createArrayForColumns($user);
        }
        $return['data'] = $table1;
        $return['recordsFiltered'] = $tempnum;
        $return['recordsTotal'] = $tempnum;
        return $return;	
    }

    function generateTableHeaders($table){
        $tempLabels1 = $this->getLabels();
        $col_headers = array();

        $tempLabels = $this->getLabels();
        
        //print_r($tempLabels);
        if($table['has_checkbox']){	
            $col_headers[] = $this->checkBoxFromArray(array(
                                                        'label'=>'',
                                                        'id'=>'table-master-check',
                                                        'name'=>'table-master-check',
                                                        'class'=>'table-master-check',											
                                                        'color'=>'b71c1c',
                                                        'value'=>'1'
                                                        )	);	
        }
        
        foreach($table['cols'] as $i=>$col){
            if($table['custom_headers']){
                $col_headers[] = isset($table['custom_headers'][$i]) ? $table['custom_headers'][$i]:'';
            }
            else{
               $col_headers[]	= isset($table['custom_cols'][$col]['heading']) ? $table['custom_cols'][$col]['heading']: (isset($tempLabels[$col]) ? $tempLabels[$col] : 'Name'); 
            }
            	
        }
        
        
        if($table['has_status_label']){
			$col_headers[] = $tempLabels[$table['status_field']];
			$table['has_status_label'] = false;
        }
        else{
            //$col_headers[] = 'Status';
        }
        
        if($table['has_tr_buttons'])  $col_headers[] = 'Actions';
        return $col_headers;	
    }

    function generateFilterRow($table){
        $col_headers = array();
        if($table['has_checkbox']){
            $col_headers[] = '';	
        }
        foreach($table['cols'] as $key=>$col){
            $col_headers[]	= isset($table['custom_cols'][$col]['filter']) ? $table['custom_cols'][$col]['filter'] : '';
            //print_r($table['custom_cols'][$col]['filter']);            
        }

        if(empty(array_filter($col_headers))){
            return array_filter($col_headers);            
        }
        else{
            return $col_headers;
        }
        	
    }

    function createArrayForColumns($user){ ////CHANGE HERE FOR A NEW PAGE

        global $dev_mode;
        
        $table = $this->table;
        $table['tr_buttons']['edit'] = $this->table['tr_buttons']['edit'];	
        $table['tr_buttons']['delete'] = $this->table['tr_buttons']['delete'];
        $label = '';
        
        ///// STATUS LABEL CODE
        if($table['has_status_label']){
			$label = '';
			if(isset($user[$table['status_field']]) && trim($user[$table['status_field']]) !='' && $user[$table['status_field']] != null){
				$label = isset($table['status_label'][$user[$table['status_field']]]) ? $table['status_label'][$user[$table['status_field']]] : '';	
			}
        }
        
        $all_buttons = '';        
        //// BUTTONS CODE
        $temp_buttons = '';
        $buttons = $table['tr_buttons'];
        foreach($buttons as $btn){
            //$temp_buttons .= '<li>'.replaceBetweenBraces($buttonConf,$btn).'</li>';
            $temp_buttons .= '<li>'.$btn.'</li>';		
            
        }
        $all_buttons .= 
        '
        <div class="btn-group btn-group-tr" data-id="__ID__">
            <button type="button" class="btn bg-color-primary-custom dropdown-toggle" data-toggle="dropdown" data-close-others="true" aria-expanded="false"><i class="fa fa-cogs"></i><i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-right hold-on-click" role="menu" style="">
                '.$temp_buttons.'
            </ul>
        </div>    
        '; 

        
        $col_data_temp = array();	
        $col_headers = array();

        ///// CHECK BOX
        if($table['has_checkbox']){
            $col_data_temp['checkbox'] = $this->checkBoxFromArray(array(
                                                    'label'=>'',
                                                    'id'=>'check-__ID__',
                                                    'name'=>'ids[]',
                                                    'class'=>'check-__ID__',
                                                    'color'=>'ffa000',
                                                    'value'=>'__ID__'
                                                    )
                                                    );
        }

        ///// PREPARE TD DATA
		
		
        $fKeys = $this->getForeignKeys();
        foreach($table['cols'] as $col){
            if(isset($table['custom_cols'][$col])){
                $col_data_temp[$col] = replaceBetweenBraces($user, $table['custom_cols'][$col]['value']);
            }
            else
            {
                if(isset($fKeys[$col])){ //// Replace foreign keys with _name prefix
                    $col_data_temp[$col] = $user[''.$col.'_name'];
                }
                else{
                    
                    if($col == 'datecreated' && strtotime($user[$col]) != strtotime('0000-00-00 00:00:00')) $col_data_temp[] = getReadableDate($user[$col]);
                    else $col_data_temp[$col] = $user[$col];
                }
                          
            }

            if(isset($table['field_functions'][$col])){
                $col_data_temp[$col] = call_user_func_array($table['field_functions'][$col], array($col_data_temp[$col]));
            }
        }
        
        if(isset($table['has_status_label']) || isset($table['override_status_label'])){
            if($table['has_status_label'] || isset($table['override_status_label'])){
                $col_data_temp[] = $label;
            } 
        }
        else{
            $col_data_temp['label'] = $label;            
        }
        
        
        if(isset($table['has_tr_buttons']) && $table['has_tr_buttons']){        
            $col_data_temp['actions'] = ''.$all_buttons.'';	
        }
			
        return $col_data_temp;
    }


    function generateBulkUpdateRow(){
        $tr = '';
        $table = $this->table;
        if(isset($table['bulk_update_fields']) && !empty(array_filter($table['bulk_update_fields']))){
            foreach($table['bulk_update_fields'] as $field => $fieldVal){
                $fieldVal['label'] = $this->getLabels()[$field];
                $fieldVal['name'] = ''.$this->dbTable.'['.$field.']';
                $fieldVal['value'] = '';
                $fieldVal['id'] = 'bulk-'.$field.'';
                $fieldVal['type'] = 'floating-4-6';            
                $tr .= '<div class="form-common-element-wrapper col-md-4">'.$this->selectFromArray($fieldVal).'</div>';   
            }
            if(!isset($table['bulk_update_buttons'])){
                $tr .= 
                '
                <div class="row col-md-12 text-center">
                <button class="btn red btn-outline" id="btnBulkCancel" onClick="UncheckMaster()" >Cancel</button>
                <button class="btn green" id="btnBulkUpdate" onClick="BulkUpdate()">Update Selected</button>
                <button class="btn red btnDelete" id="btnDelete" onClick="BulkDelete()">Delete Selected</button>
                </div>
                ';                  
            }
            else{
                $tr .= 
                '
                <div class="row col-md-12 text-center">
                    '.$table['bulk_update_buttons'].'
                </div>
                ';                  
            }
        }
		else if($table['has_checkbox']){
            if(!isset($table['bulk_update_buttons'])){
                $tr .= 
                '
                <div class="row col-md-12 text-center">
                <button class="btn red btn-outline" id="btnBulkCancel" onClick="UncheckMaster()" >Cancel</button>
                <button class="btn red btnDelete" id="btnDelete" onClick="BulkDelete()">Delete Selected</button>
                </div>
                ';                  
            }
            else{
                $tr .= 
                '
                <div class="row col-md-12 text-center">
                    '.$table['bulk_update_buttons'].'
                </div>
                ';                  
            }		
		}

       
        return $tr;
    }

    function getModal($form, $buttons, $title=''){
        $title = ($title == '')? $this->common_title:$title;
        $common_title_stripped = preg_replace('/\s+/', '', $this->common_title);		
        $sub_title = ($title == '')? $this->common_title.' Details':$title;
        $return = ''; 
        $return .= 
                '
                <div class="modal-dialog" style="margin:0px;width:100%">
                    <div class="modal-content z-depth-5">
                        <div class="modal-header" style="padding:0px">

                            <div class="mt-element-step">

                                <div class="row step-background-thin">
                                    <div class="col-md-12 bg-grey-steel mt-step-col active" style="padding:0px">
                                        <div class="mt-step-title uppercase font-grey-salsa" style="padding-right:0px;text-align:center">'.$title.' </div>
                                        <div class="mt-step-content font-grey-salsa" style="padding-right:0px;text-align:center">'.$sub_title.' Details</div>
                                                <div class="modal-button-group pull-right">	
                                                    <a href="javascript:;" class="btn-mat-floating mat-red fullscreen" ><i class=" fullscreen-iconn large mdi-navigation-fullscreen"></i></a>									
                                                    <a href="javascript:;" class="btn-mat-floating mat-red" data-dismiss="modal" aria-hidden="true"><i class="large mdi-action-highlight-remove"></i></a>
                                        </div>									
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body" id="modal-body">
                            <form role="form" id="new'.$common_title_stripped.'Form">
                            <div class="row">
                            '.$form.'						
                            </div>
                            </form>
                            </div>

                        <div class="modal-footer">
                            '.$buttons.'
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                ';	
        return $return;
    }

    function getModalForm($data,$header_buttons=true,$ajax=true){
        $title = ($data['portlet-title'] == '')? $this->common_title:$data['portlet-title'];	
        $sub_title = isset($data['portlet-sub-title'])? $data['portlet-sub-title']:$title.' Details';
        $actionMethod = '';
        if(isset($data['form-submit']) && $data['form-submit']){
            $actionMethod = 'method="POST" action="'.$data['form-submit-url'].'"';
        }
        else{
            $actionMethod = 'method="POST" onSubmit="return false;"'; 
        }
        $headerBtnTemp = '';
        if($header_buttons){
            $headerBtnTemp = 
            '
            <div class="modal-button-group pull-right">	
                <a href="javascript:;" class="btn btn-circle btn-icon-only fullscreen white" ><i class=" fullscreen-iconn fa fa-expand"></i></a>									
                <a href="javascript:;" class="btn btn-circle btn-icon-only white"       onClick="CloseBsModal(\'\',this)"><i class=" fa fa-close"></i></a>
            </div>	            
            ';
        }
        $return = ''; 
        $return .= 
                '
                <div class="modal-dialog" style="width:100%">
                    <div class="modal-content z-depth-5">
                        <div class="modal-header" style="padding:0px">
                        <div class="modal-title fancy-modal-title bg-color-primary-custom" style="top: 20px;">
                            <div class="caption">
                                <span class="bold uppercase">'.$title.'</span>
                                
                            </div>
                            '.$headerBtnTemp.'
                        </div>
                        
                        </div>
                        <div class="modal-body" id="modal-body" style="padding:20px">
                            <form role="form" id="'.$data['form-id'].'" '.$actionMethod.'>
                            '.$data['hidden'].'
                            <div class="row" style="margin-top: 20px;">
                            '.$data['form'].'						
                            </div>
                            </form>
                            <div class="col-md-12 text-center">
                            '.$data['buttons'].'
                            </div>
                             <div class="row text-center" id="'.$data['tools']['unique_id'].'-popover-result">
                             
                             </div>                            
                        </div>

                        <div class="modal-footer">
                            
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                ';	
        return $return;
    }

    function getPortletForm($data,$class=''){
        if($class == ''){
            $mainPortlet = new Portlet();
            $mainPortlet->portletClass = 'box green col-md-12';
            $mainPortlet->iconClass = 'icon-pencil';
            $mainPortlet->portletId = 'portlet-'.$data['form-id'];
            $mainPortlet->formId = $data['form-id'];
            $mainPortlet->formAction = '';
            $form2['fields'] = $data['form'];
            $form2['buttons'] = $data['buttons'];
            $return = $mainPortlet->GetPortletForm($data['portlet-title'], $form2);            
        }
        else{
            $class->portletId = 'portlet-'.$data['form-id'];
            $class->formId = $data['form-id'];
            $class->formAction = '';
            $form2['fields'] = $data['form'];
            $form2['buttons'] = $data['buttons'];
            $return = $class->GetPortletForm($data['portlet-title'], $form2);            
            
        }

        return $return;        
    }
}
?>