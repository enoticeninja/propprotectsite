<?php 
Class SingleDBHtml extends SingleDBFormGenerator
{
    public $table;
    function __construct(){

    }	

    function setTableFields($merge=true){
        $this->getTableFields();
    }

    function getTableFields(){
        ////Specify all the fields here and override them in custom-cols later
        $this->table['cols'] = array();
        $this->table['has-checkbox'] = true;
        $this->table['tr-buttons'] = array();
        $this->table['th-buttons'] = array(
                            'refresh'=>'<a href="#" type="button" class="btn red" onClick="CommonFunc({\'action\':\'refresh\'})"><i class="icon-refresh" ></i>Refresh</a>'	
                            );
        $this->table['portlet-actions'] =                         
                        '
                            <a href="#modalForm" type="button" class="btn btn-circle green" data-toggle="modal" aria-expanded="false" onClick="GetForm(\'new-form\',\'\',\'add-mtrow\',\'modalForm\')">
                                <i class="fa fa-plus"></i> Add </a>
                            <a href="#" class="btn btn-circle btn-icon-only blue reload" data-original-title="" title="" onClick="CommonFunc({\'action\':\'refresh\'})"> <i class="icon-refresh" ></i></a>										
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title=""> </a>
                        ';
        
        $this->table['custom-cols'] = array();
        return $this->table;	
    }    

    function createTableTdOnly($data){
        
        $row_data = '';

        foreach($data as $col) {
            $row_data .= 
            '
            <td>'.$col.'</td>
            ';
        }
        return $row_data;
    }
   
    function createTableRow($data, $trid = '', $trclass=''){
        
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
    return $row_data;
    }

    function generateTable(){
        global $table,$db,$sql;
        $table->headers = $this->generateTableHeaders($this->table);
        $table->filterFields = $this->generateFilterRow($this->table);    
        $table->bulkUpdateRow = $this->generateBulkUpdateRow();
        if(isset($this->table['portlet-actions']) && $this->table['portlet-actions'] != ''){
            $table->actions = $this->table['portlet-actions'];                
        }
    
        $tempRowsNum = $table->rowsCountDisplay;

        if(isset($this->table['sql'])){
            $sql = $this->table['sql'];          
        }
        $sqlnum = "$sql
        ORDER BY main.id DESC
        LIMIT $tempRowsNum
        ";
        $row = $this->getDataForTable($sqlnum);       
        $tempnum= $db->NumRows($sql);
        $table->totalRowsInDb = $tempnum;

        $table1 = '';

        $table->getPagination($table->rowsCountDisplay, $table->totalRowsInDb,1);		

        foreach($row as $user) {

            $col_data_temp = $this->createArrayForColumns($user,$this->table);
            $table1 = $this->createTableRow($col_data_temp, $user['id']);	            
            $table->table .= $table1;
        }
        $lastTemp = reset($row);
        $return['last_id'] = $lastTemp['id'];
        $return['jsFunction'] = $this->getDependencyJsTable($this->table);
        return $return;        
    }

    function generateTableCustom($sql,$cols=''){
        global $table,$db;
        $table->headers = $this->generateTableHeaders($this->table);
        $table->filterFields = $this->generateFilterRow($this->table);    
        $table->bulkUpdateRow = $this->generateBulkUpdateRow();
        if($this->table['portlet-actions'] != ''){
            $table->actions = $this->table['portlet-actions'];                
        }
    
        $tempRowsNum = $table->rowsCountDisplay;
 
        $sqlnum = "$sql
        ORDER BY main.id DESC
        LIMIT $tempRowsNum
        ";
        $row = $this->getDataForTable($sqlnum);       
        $tempnum= $db->NumRows($sql);
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
            $arr['jsFunction'] = "var value = JSON.parse('$tempDepen');console.log(value);";

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
        global $db,$sql;

        if(isset($table['sql'])){
            $sql = $table['sql'];          
        }
        
        $sqlnum = "$sql
        ORDER BY id DESC
        ";
        $row = $this->getDataForTable($sqlnum);          
        $tempnum= $db->NumRows($sql);

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
        $tempLabels1 = $this->getFields();
        $col_headers = array();
        $tempLabels = $tempLabels1['label'];
        if($table['has-checkbox']){	
            $col_headers[] = $this->checkBoxFromArray(array(
                                                        'label'=>'',
                                                        'id'=>'table-master-check',
                                                        'name'=>'table-master-check',
                                                        'class'=>'table-master-check',											
                                                        'color'=>'b71c1c',
                                                        'value'=>'1'
                                                        )	);	
        }
        foreach($table['cols'] as $col){
            $col_headers[]	= isset($table['custom-cols'][$col]['heading']) ? $table['custom-cols'][$col]['heading']: $tempLabels[$col];	
        }
        
        
        if(isset($table['has-status-label'])  || isset($table['override-status-label'])){
            if(isset($table['override-status-label']) && $table['override-status-label']){
                $col_headers[] = $tempLabels[$table['override-status-label']];
                $table['has-status-label'] = false;
            }             
            if($table['has-status-label']){
                $col_headers[] = 'Status';
            } 

        }
        else{
            $col_headers[] = 'Status';            
        }
        
        if(isset($table['has-tr-buttons']) && $table['has-tr-buttons'])  $col_headers[] = 'Actions';
        return $col_headers;	
    }

    function generateFilterRow($table){
        $col_headers = array();
        if($table['has-checkbox']){
            $col_headers[] = '';	
        }
        foreach($table['cols'] as $key=>$col){
            $col_headers[]	= isset($table['custom-cols'][$col]['filter']) ? $table['custom-cols'][$col]['filter'] : '';
            //print_r($table['custom-cols'][$col]['filter']);            
        }

        if(empty(array_filter($col_headers))){
            return array_filter($col_headers);            
        }
        else{
            return $col_headers;
        }
        	
    }

    function createArrayForColumns($user){ ////CHANGE HERE FOR A NEW PAGE

        global $class,$dev_mode;

        $name_field = replaceBetweenBraces($user,$this->config['name']);
        if($this->config['name'] == $name_field) $name_field = $user[$this->config['name']]; 
        //$name_field = $user[$this->config['name']];
        $buttonConf['name'] = $name_field;
        $buttonConf['id'] = $user['id'];
        
        $table = $this->table;
        if(!isset($table['tr-buttons']['edit'])){
            $table['tr-buttons']['edit'] = '<a href="#modalForm" type="button" class="btn btn-outline green dropdown-toggle" data-toggle="modal" aria-expanded="false" onClick="GetForm(\'edit-form\',\''.$user['id'].'\',\'\',\'modalForm\')">Edit >> '.$name_field.'</a>';	
        }
        if(!isset($table['tr-buttons']['delete'])){       
            $table['tr-buttons']['delete'] = '<a href="#" type="button" class="btn btn-outline red btnDelete" data-toggle="confirmation" data-placement="bottom" id="btnDelete" onClick="Delete(\''.$this->dbTable.'\',\''.$user['id'].'\',this)">Delete >> '.$name_field.'</a>';
        }
        $label = '';
        
        ///// STATUS LABEL CODE
        if(isset($table['override-status-label'])){
            $table['has-status-label'] = false;
            if($user[$table['override-status-label']] == 1){
                $label = '<span class="label label-success"><i class="fa fa-check"></i> ENABLED </span>';	
            }
            else if($user[$table['override-status-label']] == 0){
                $label = '<span class="label label-danger"><i class="fa fa-wrong"> DISABLED </span>';
                
                if(!$dev_mode){
                    $buttons['delete'] = '';
                    $buttons['edit'] = '';		
                }

            }           
        }
        else{
            if(isset($user['status'])){
                if($user['status'] == 'active'){
                    $label = '<span class="label label-success"><i class="fa fa-check"></i> ACTIVE </span>';	
                }
                else if($user['status'] == 'deleted'){
                    $label = '<span class="label label-danger"><i class="fa fa-wrong"> DELETED </span>';
                    
                    if(!$dev_mode){
                        $buttons['delete'] = '';
                        $buttons['edit'] = '';		
                    }

                }
                else {
                    $label = '<span class="label label-danger"><i class="fa fa-wrong"> NOT ACTIVE </span>';	
                }
            }
            
        }
        $all_buttons = '';        
        if(isset($table['has-tr-buttons']) && $table['has-tr-buttons']){
         {//// BUTTONS CODE
                $all_buttons = '';
                $temp_buttons = '';
                $buttons = $table['tr-buttons'];
                foreach($buttons as $key=>$btn){
                    $temp_buttons .= '<li>'.$btn.'</li>';		
                    //echo $temp_buttons;

                }
                $all_buttons .= 
                '
                <div class="btn-group btn-group-tr" data-id="'.$user['id'].'">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-close-others="true" aria-expanded="false"><i class="fa fa-cogs"></i><i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu" role="menu" style="left:-100px;">
                        '.$temp_buttons.'
                    </ul>
                </div>    
                ';
                //$all_buttons = replaceBetweenBraces($buttonConf,$all_buttons);
                 //str_replace("__ID__",$user['id'],$all_buttons);                
                }           
        }

        
        $col_data_temp = array();	
        $col_headers = array();

        ///// CHECK BOX
        if($table['has-checkbox']){
            $col_data_temp['checkbox'] = $this->checkBoxFromArray(array(
                                                    'label'=>'',
                                                    'id'=>'check-'.$user['id'].'',
                                                    'name'=>'ids[]',
                                                    'class'=>'check-'.$user['id'].'',											
                                                    'color'=>'ffa000',
                                                    'value'=>$user['id']
                                                    )	
                                                    );
        }	

        ///// PREPARE TD DATA
        $fKeys = $this->getForeignKeys();
        foreach($table['cols'] as $col){
            if(isset($table['custom-cols'][$col])){
                $col_data_temp[$col] = replaceBetweenBraces($user, $table['custom-cols'][$col]['value']);            
            }
            else
            {
                if(isset($fKeys[$col])){
                    $col_data_temp[$col] = $user[''.$col.'_name'];
                }
                else{
                    
                    if($col == 'datecreated' && strtotime($user[$col]) != strtotime('0000-00-00 00:00:00')) $col_data_temp[] = getReadableDate($user[$col]);
                    else if($col == 'start_date' && strtotime($user[$col]) != strtotime('0000-00-00 00:00:00')) $col_data_temp[] = getReadableDate($user[$col]);
                    else if($col == 'end_date' && strtotime($user[$col]) != strtotime('0000-00-00 00:00:00')) $col_data_temp[] = getReadableDate($user[$col]);
                    else $col_data_temp[$col] = $user[$col];
                }
                          
            }

        }
        
        if(isset($table['has-status-label']) || isset($table['override-status-label'])){
            if($table['has-status-label'] || isset($table['override-status-label'])){
                $col_data_temp[] = $label;
            } 
        }
        else{
            $col_data_temp['label'] = $label;            
        }
        
        
        if(isset($table['has-tr-buttons']) && $table['has-tr-buttons']){        
            $col_data_temp['actions'] =
            '
                '.$all_buttons.'
            ';	
        }
        $col_data_temp = replaceBetweenBraces($buttonConf,$col_data_temp);			
        return str_replace('__ID__',$user['id'],$col_data_temp);
    }

    function generateBulkUpdateRow(){ 
        $tr = '';
        $table = $this->table;
        if(isset($table['bulk_update_fields'])){
            foreach($table['bulk_update_fields'] as $field => $fieldVal){
                $fieldVal['label'] = $this->getLabels()[$field];
                $fieldVal['name'] = ''.$this->dbTable.'['.$field.']';
                $fieldVal['value'] = '';
                $fieldVal['id'] = 'bulk-'.$field.'';
                $fieldVal['type'] = 'floating-4-8';            
                $tr .= '<div class="form-common-element-wrapper col-md-12">'.$this->selectFromArray($fieldVal).'</div>';   
            }
            if(!isset($table['bulk_update_buttons'])){
                $tr .= 
                '
                <div class="row col-md-12 text-center">
                <button class="btn red btn-outline" id="btnBulkCancel" onClick="UncheckMaster()">Cancel</button>
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
                <a href="javascript:;" class="btn btn-circle btn-icon-only white" onClick="CloseBsModal(\'\',this)"><i class=" fa fa-close"></i></a>
            </div>	            
            ';
        }
        $return = ''; 
        $return .= 
                '
                <div class="modal-dialog" style="">
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
                            <div class="row" style="margin-top: 20px;">
                            '.$data['form'].'						
                            </div>
                            </form>
                            </div>

                        <div class="modal-footer">
                            '.$data['buttons'].'
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
            $mainPortlet->portletClass = 'box green';
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