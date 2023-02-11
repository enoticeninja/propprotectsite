<?php
require_once DIR_CORE_CLASSES.'class_form.php';
class SingleDBFormGenerator extends Form
{
    public $buttons = array(
            'table'=>array(
                    'new' => '<a href="#modalForm" type="button" class="btn btn-circle green" data-toggle="modal" aria-expanded="false" onClick="GetForm(\'new-form\',\'\',\'__DB_TABLE__\',\'modalForm\')"><i class="fa fa-plus"></i> Add </a> ',
                    'edit' => '<a href="#modalForm" type="button" class="btn btn-outline green dropdown-toggle" data-toggle="modal" aria-expanded="false" onClick="GetForm(\'edit-form\',\'__ID__\',\'\',\'modalForm\')">Edit >> __NAME__</a>',
                    'refresh' => '<a href="#" class="btn btn-circle btn-icon-only blue reload" data-original-title="" title="" onClick="CommonFunc({\'action\':\'refresh\',\'module\':\'__DB_TABLE__\'})"> <i class="icon-refresh" ></i></a>',
                    'delete' => '<a href="#" type="button" class="btn btn-outline red btnDelete" data-toggle="confirmation" data-placement="bottom" id="btnDelete" onClick="Delete(\'__DB_TABLE__\',\'__ID__\',this)">Delete >> __NAME__</a>',
                    'fullscreen'=>'<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title=""> </a>'		
                ),
            'form'=>array(
                    'new' => '<button type="button" class="btn btn-circle green">Save __COMMON_TITLE__</a> ',
                    'edit' => '<button type="button" class="btn btn-circle green" >Update __COMMON_TITLE__ </a> ',
                    'new-ajax' => '<button type="button" class="btn btn-circle green" onClick="Save({},\'__FORM_ID__\')">Add </a> ',
                    'edit-ajax' => '<button type="button" class="btn btn-circle green" onClick="Update({},\'__FORM_ID__\')">Update __COMMON_TITLE__ </a> '
                    )		
        );
    
    public function __construct($db_conx = '') {

    }

    function generateReportForm(){
        
    }
    
    public function getFormConfigurationAll($data){
            /////Form Configuration //////////////            
        {$return['forms']['new'] = array(
                'form_id'=>'new_form',
                'override_function'=>array(
                        'enabled'=>false,
                        'function'=>''		
                    ),
                ////// IMP: set a unique id as hidden input and save it in session variable to verify that the form called was form submitted
                'portlet_title'=>'Add New Menu Item',
                'portlet_icon'=>'',
                'submit_id'=>'submit_id',
                'call_type'=>isset($data['call_type']) ? $data['call_type'] : 'ajax',
                'return_form_as'=>isset($data['return_form_as']) ? $data['return_form_as'] : 'modal',
                'fetch_data'=>array(
                        'enabled'=>false,
                        'sql_from_array'=>false,
                        //'sql'=>"SELECT * FROM $this->dbTable WHERE id=$data[id]",
                        'db_table'=>$this->dbTable,
                        'where_key'=>'customer_id',
                        'where_operation'=>'=',
                        'where_match'=>'id' ///INDEX OF DATA VARIABLE TO BE USED
                        ///// "SELECT * FROM $arr[fetch_data][db_table] WHERE $arr[fetch_data][where_key] $arr[fetch_data][where_operation] '$data[$arr[fetch_data][where_match]]'"
                        ),
                'parent'=>'permission_module_new',//// WILL BE USED MOSTLY IN CASE OF MULTIPLE TABS OF SAME OR DIFFERENT CLASSES, TO INDENTIFY WHICH ELEMENT BELONGS WHERE
                'module'=>$this->dbTable,////ALWAYS THE DATABASE TABLE NAME OR ANY NAME SPECIFIED IN THE RouterX
                'unique_element_id'=>'',
                'action'=>'',
                'method'=>'',
                'return_after_save'=> array(
                        'table'=>'',
                        'nested_tree'=>'',
                        'nested_list'=>'',
                        'modal'=>'',
                        'portlet'=>'',		
                        'form'=>array(
                                'new-case'=>array(
                                    'class'=>'Case', //// CALL_USER_FUNC_ARRAY WILL CALL THE CLAS USING THIS VARIBALE... USE $this 
                                    'form_type'=>'new', //// THIS FORM TYPE IWLL BE CALLED FROM THE ABOVE CLASS
                                    'pass_data'=>'id', //// THIS DATA WILL BE PASSED TO THE FORM CALLED
                                    'fk'=>'customer_id'		//// THE PASSED ID IS REFERRED TO AS THIS FORIEGN KEY
                                ),
                                'new-product'=>array(
                                    'class'=>'Product', //// CALL_USER_FUNC_ARRAY WILL CALL THE CLAS USING THIS VARIBALE... USE $this 
                                    'form_type'=>'new', //// THIS FORM TYPE IWLL BE CALLED FROM THE ABOVE CLASS
                                    'pass_data'=>'id', //// THIS DATA WILL BE PASSED TO THE FORM CALLED
                                    'fk'=>'customer_id'		//// THE PASSED ID IS REFERRED TO AS THIS FORIEGN KEY
                                )
                            )	
                    ),
                'hidden'=> array(
                        'enabled'=>false,
                            'html'=>''
                                ),
                'buttons'=> array(
                        'enabled'=>true,
                            'html'=>''
                                ),
                'dependency'=> array(),

/*                 'dependency'=> array(
                        'type'=>array(
                                'primary'=>array(
                                    'show'=>array('filed_1','field_2','field_3','field_4','field_5','field_6','field_7','field_8','field_9')
                                    ),
                                'secondary'=>array(
                                    'show'=>array('filed_1','field_2','field_3','field_4','field_5','field_6','field_7','field_8','field_9')
                                    )		
                            )		
                    ), *//* 
                'duplicate'=> array(
                        'enabled'=>false,
                        'fileds'=> array(
                            'DATABASE_FIELD_NAME1'=> array(
                                    'TABLE_NAME1'=>array('FILED_NAME1','FILED_NAME2'), //// as many filed names as we want
                                    'TABLE_NAME2'=>array('FILED_NAME1','FILED_NAME2') //// as many filed names as we want		
                                ),
                            'DATABASE_FIELD_NAME2'=> array(
                                    'TABLE_NAME1'=>array('FILED_NAME1','FILED_NAME2'), //// as many filed names as we want
                                    'TABLE_NAME2'=>array('FILED_NAME1','FILED_NAME2') //// as many filed names as we want		
                                )		
                             ),
                        'exceptions'=>array('EXCEPTION1','EXCEPTION2','EXCEPTION3')
                    ), */
                'duplicate'=> array('name'),
                'validation'=> array(),
                'duplicate_combination'=> array(
                        'enabled'=>false,
                        'fileds'=> array('DATABASE_FIELD_NAME1','DATABASE_FIELD_NAME1'),
                        'exceptions'=>array(
                            '0'=>array('EXCEPTION1','EXCEPTION2'), ////EXCEPTIONS FILEDS SHOULD MATCH THE DATABASE_FIELD_NAME FIELDS
                            '1'=>array('EXCEPTION1','EXCEPTION2')  ////EXCEPTIONS FILEDS SHOULD MATCH THE DATABASE_FIELD_NAME FIELDS
                        )
                    ),
                'jsFunction'=>'',
                'on_submit'=>array(
                        'action'=>'save',
                        'further_action'=>'save_log',
                        'return'=>'table_row',
                        'return_js'=>''		
                    ),	
                'default_insert'=>array( //// ????? these values would be forced insert
                    'enabled'=>true,
                    'date_created'=>date('Y-m-d H-i-s'),
                    'created_by'=>$_SESSION[get_session_values('id')]	
                ),
                'on_submit_error'=>'',
                'name_prefix'=>'',
                'id_prefix'=>true, /// If true ids will be prefixed with parent
                'fields_function'=>array(
                        'enabled'=>false,
                        'function'=>'getSecondaryForm'		
                    ),
                'fields'=>array('type','name','page_name','page_link','icon','menu_position','parent_id','has_children','status','description'), //// SPECIFY THE ORDER THAT WE WANT, THIS WILL BE THE MAIN GROUP OF THE FORM ELEMENTS
                'required'=>array('name','page_name','type','status'),	
                'default_values'=>array( //// these values are for giving initial values in the form and can be changed
                    'enabled'=>false,
                    'status'=>'active'	
                ),
                'override_fields'=>array(
                        'enabled'=>false,
                        'type'=>array(
                                'type'=>'select',
                                'options'=>array(),
                                'value'=>'',
                                'width'=>'4',
                                'default'=>''
                                )
                    ),
                'group_fields'=>array( //// IF ELEMENTS ARE TO BE IN DIFFERENT SECTIONS OF THE FORM IN A SPECIFIED ORDER
                        'enabled'=>false,
                        'GROUP_1'=>array('','','','','',''), //// MAIN GROUP WILL HAVE ALL THE ELEMENTS , GROUPS SPECIFIED HERE WILL BE UNSET FROM THE MAIN GROUP
                        'GROUP_1'=>array('','','','','','') //// MAIN GROUP WILL HAVE ALL THE ELEMENTS , GROUPS SPECIFIED HERE WILL BE UNSET FROM THE MAIN GROUP
                        ),
                'non_db_fileds'=>array('','','',''),
                'override_non_db_fileds'=>array(
                        'enabled'=>false,
                        'type'=>array(
                                'type'=>'select',
                                'options'=>array(),
                                'value'=>'',
                                'width'=>'4',
                                'default'=>'',
                                'affects_save'=>array(
                                        'has_affect'=>false,
                                        'enabled'=>true,
                                        'fields'=>array(
                                                'FIELD_1'=>array(
                                                            'operation'=>'EQUALS PLUS MINUS MULTIPLY DIVIDE',
                                                            'boolean'=>'',
                                                            'string'=>'EQUALs STARTS_WITH ENDS_WITH CONTAINS'		
                                                            )
                                            )	
                                    )
                                
                                )
                    )
                    );

        }
            /////End Form Configuration //////////////         
        return $return['forms'][$data['form_type']];  
    }     

    public function getIcons(){
        return array();
    }
    
    public function createFieldsFromArray($fieldsArr){
        $fieldsArray = array();
        foreach($fieldsArr as $field=>$label){
            $temp = array(); 
            $temp['codebegin'] = array();       
            $temp['codeend'] = array();       
            $temp['label'] = $label;       
            $temp['field_type'] = 'input';       
            $temp['id'] = $field;       
            $temp['name'] = $field;       
            $temp['class'] = '';       
            $temp['width'] = '';       
            $temp['color'] = '';       
            $temp['value'] = '{'.$field.'}';       
            $temp['required'] = false;       
            $temp['validation'] = array();       
            $temp['icon'] = '';       
            $temp['default'] = '';       
            $temp['allowed_duplicate'] = true;       
            $fieldsArray[$field] = $temp;   
        }

        return $fieldsArray;        
    }
    
    public function getExtendedForm($request_data){

        $tempform = '';
        $next_form = '';
        $new_field = '';
        $fields = array();
        $new_form = array();
        $tempTools = array();
        $hiddenFields = array();
        $form = array(); /// will hold every thing as an associative array
        $tempJsTools = '';
        $tempJsElements = '';
        $form_config = $this->setFormConfiguration($request_data);  
        $form = $form_config;
        if($form_config['override_function']['enabled']){
            $methodName = $form_config['override_function']['function'];
            $return = call_user_func_array(array($this, $methodName), array($data));            
        }


        $db_data = array();
        if($form_config['fetch_data']['enabled']){
            $db_data = $this->loadFromDB($data['id']);
            ////UNCOMMENT BELOW CODE AND COMMENT ABOVE LINE TO ENABLE SQL FROM $form_config settings
            /*
             if($form_config['fetch_data']['sql_from_array']){
                $sql = "SELECT * FROM $form_config[fetch_data][db_table] WHERE $form_config[fetch_data][where_key] $form_config[fetch_data][where_operation] '$data[$form_config[fetch_data][where_match]]' LIMIT 1";
                $query = mysqli_query($this->dbConn, $sql);
                $db_data = mysqli_fetch_array($query, MYSQLI_ASSOC);
                
            }            
            else{
                $sql = $form_config['fetch_data']['sql'];
                $query = mysqli_query($this->dbConn, $sql);
                $db_data = mysqli_fetch_array($query, MYSQLI_ASSOC);                
            }
            */


            $form_config['db_data'] = $db_data;            
            foreach($db_data as $temkDKey=>$tempDVal){
                $form['tools']['data'][''.$form_config['parent'].'-'.$temkDKey.''] = $tempDVal;
            }        
        }

        
        $form_config_fields = $form_config['fields'];
        $all_fields = $this->getAllFields();
        $form_config['labels'] = $this->getLabels();
        $form_config['icons'] = $this->getIcons();

        ///// Create Hidden fields if any
        $form['hidden'] = '';
        $form['priorityJs'] = '';
        if($form_config['hidden']['enabled']){
            $form['hidden'] .= $form_config['hidden']['html'];
        }
        
        $form['hidden'] .= '<input type="hidden" name="module" id="module" value="'.$form_config['module'].'">';        

        foreach($form_config['fields'] as $field){
            $value = $all_fields[$field];
            $element['extra'] = 'data-parent="'.$this->dbTable.'" data-module="'.$this->dbTable.'" ';
            $element['extra'] .= isset($value['extra'])?$value['extra']:'';
            $element['label'] = isset($value['label'])?$value['label']:$form_config['labels'][$field];
            $element['field_type'] = isset($value['type'])?$value['type']:'input';
            $element['icon'] = isset($value['icon'])? $value['icon'] :(isset($form_config['icons'][$field])?$form_config['icons'][$field]:'');
            //$new_form['fields'][$field] = $element['field_type'];
                $new_field = ''; ///RESET THE VARIABLE TO REMOVE ANY PREVIOUS VALUES            

                {//// CODEBEGIN
                    if (isset($value['code_begin'])){
                         $next_form .= replaceBetweenBraces($db_data,$value['code_begin']);	
                    }
                }
                
                {//// PREPARE THE ARRAY FOR FETCHING FORM ELEMENT
                $tempJsElements .= isset($value['js'])?$value['js']:'';		
                $element['type'] = isset($value['form_group_type'])?$value['form_group_type']:'floating-5-7';		
                $element['name'] = isset($form_config['name_prefix']) ? ''.$form_config['name_prefix'].'['.$field.']' :(isset($value['name']) ? replaceBetweenBraces($form_config['db_data'],$value['name']) : ''.$form_config['parent'].'['.$field.']');   
                $element['id'] = (!$form_config['id_prefix']) ? $field:(isset($value['id']) ? replaceBetweenBraces($form_config['db_data'],$value['id']) : $form_config['parent'].'-'.$field);			
                $element['class'] = isset($value['class']) ? $value['class'] : '';			
                $element['required'] = in_array ($field,$form_config['required']) || isset($form_config['required'][$field]) ? true : false;               
                $element['validation'] = isset($form_config['validation'][$field]) ? $form_config['validation'][$field] : array();			
                $element['allowed_duplicate'] = in_array($field,$form_config['duplicate']) ? false : true;
                
                $element['width'] = isset($value['width']) ? $value['width'] : '';
                $element['inputType'] = isset($value['inputType']) ? $value['inputType'] : '';
                $element['help'] = isset($value['help']) ? $value['help'] : 'Please Enter the '.$element['label'].'';			
                if(isset($form_config['db_data'][$field])){
                    $element['value'] = $form_config['db_data'][$field];
                }
                else if (isset($form_config['default_values'][$field])){
                    $element['value'] = $form_config['default_values'][$field];                    
                }
                else{
                    $element['value'] = '';
                }
                


                }
               
                {///START CREATING THE FORM ELEMENTS
                    
                    if(isset($value['evalcode'])){
                        
                        $evalcode = $value['evalcode'];
                        eval($evalcode);                         
                    }
                    if(isset($element['required']) && $element['required']){
                        $element['label'] = '<span class="font-red">*</span> '.$element['label'].'';
                    }   
                    if($element['field_type'] == 'hidden'){
                        $new_field .= '<input type="hidden" name="'.$element['name'].'" id="'.$element['id'].'" value="'.$element['value'].'">';
                    }   
                    else if($element['field_type'] == 'checkbox'){
                        $element['color'] = isset($value['color']) ? $value['color'] : '';
                        $element['value'] = isset($value['value']) ? $value['value'] : 1;
                        $element['checked'] = isset($this->dbData[$field]) ? $this->dbData[$field] : false;
                        $new_field .= $this->checkboxFromArray($element);
                    }
                    else if($element['field_type'] == 'advanceddatetime'){
                        if($element['value'] == '0000-00-00 00:00:00'){
                            $element['value'] = date('Y-m-d H:i:s');
                        }
                        $new_field .= $this->advancedDateTimeFromArray($element);				
                    }
                    else if($element['field_type'] == 'daterangepicker'){
                        $new_field .= 
                        '
                            <label class="control-label ">'.$element['label'].'</label></br>
                            <div id="'.$element['id'].'" class="tooltips btn btn-fit-height green" data-placement="top" data-original-title="Change report date range">
                                <i class="icon-calendar"></i>
                                <span class="thin uppercase hidden-xs"></span>
                                <i class="fa fa-angle-down"></i>
                            </div>
                            <input type="hidden" name="'.$this->dbTable.'[start_date]" id="'.$this->dbTable.'-'.$value['start_date'].'">
                            <input type="hidden" name="'.$this->dbTable.'[end_date]" id="'.$this->dbTable.'-'.$value['end_date'].'">
                        ';	
                        $tempDate = array(
                                'element'=>$element['id'],
                                'startdate'=>$this->dbTable.'-'.$value['start_date'],
                                'enddate'=>$this->dbTable.'-'.$value['end_date'],
                                'button'=>''	
                            );
                        $tempDate = json_encode($tempDate);
                        $tempJsTools .= 
                        "
                        var jsonDate = $tempDate;
                        initDateRangePicker(jsonDate);
                        ";                        
                    }
                    else if($element['field_type'] == 'date'){
                        
                        $new_field .= $this->datepickerFromArray($element);				
                    }
                    else if($element['field_type'] == 'input'){
                        
                        $new_field .= $this->inputFromArray($element);				
                    }
                    else if($element['field_type'] == 'textarea'){
                        $element['type'] = 'floating-4-12';	
                        $new_field .= $this->textareaFromArray($element);				
                    }
                    else if($element['field_type'] == 'select'){
                        $element['options'] = $value['options'];
                        if(isset($value['eval'])){
                            $evalcode = $value['eval'];
                            eval($evalcode);                         
                        }
                        if(isset($value['options_func']) && isset($value['ajax_dependency']) && isset($default_values['db_data'][$value['ajax_dependency']])){
                            $element['options'] = call_user_func_array($value['options_func'],array($default_values['db_data'][$value['ajax_dependency']]));
                        }                        
                        $new_field = $this->selectFromArray($element);				
                    }
                    else if($element['field_type'] == 'code'){
                        eval(isset($value['eval'])?$value['eval']:'');	
                        $new_field .=  isset($value['code'])?$value['code']:'';                       
                    }
                    else if($element['field_type'] == 'html'){
                        
                        $new_field .= str_replace("__ELEMENT_ID__",$element['id'],$value['html']);                       
                    }

                    ///END START CREATING THE FORM ELEMENTS
                }

                {////Wrap The form field with col-md if specified in the width
                    if(isset($element['width']) && $element['width'] != '' && $element['width'] != '0' && $element['width'] <= '12'){
                        //$next_form .= col($new_field,$element['width']);
                        $left_offset = isset($value['left_offset'])?'col-md-offset-'.$value['left_offset'].'':'';
                        $right_offset = isset($value['right_offset'])?'col-md-offset-right-'.$value['right_offset'].'':'';
                        
                        $next_form .= '<div class="col-md-'.$element['width'].' '.$right_offset.' '.$left_offset.' form-common-element-wrapper" id="'.$element['id'].'-holder" style="">'.$new_field.'</div>';			
                    }else{
                        //$next_form .= col6($new_field);			
                        $next_form .= '<div class="col-md-6 form-common-element-wrapper" id="'.$element['id'].'-holder">'.$new_field.'</div>';		
                    }
                }


                {//// CODEEND
                    if (isset($value['code_end'])){
                        $next_form .= replaceBetweenBraces($db_data,$value['code_end']);	
                    }
                }
                
                {///// TEMP TOOLS
                
                $form['tools']['icons'][$element['id']] = $element['icon'];		
                //print_r($f);
                if(isset($element['required']) && $element['required']){
                    if(isset($f['required'][$field])){
                        //// prepend table name as the id of the conditional field is also changed as this form is being generated
                        $f['required'][$field]['conditional']['field'] = $this->dbTable.'-'.$f['required'][$field]['conditional']['field'];
                        $form['tools']['required'][$element['id']] = $f['required'][$field];
                    }
                    else{
                        $form['tools']['required'][$element['id']]['status'] = false;
                        $form['tools']['required'][$element['id']]['message'] = $element['label'].' is Required';                        
                    }
                }
                if(isset($element['allowed_duplicate']) && !$element['allowed_duplicate']){
                    $form['tools']['duplicate'][$element['id']]['status'] = false;
                    $form['tools']['duplicate'][$element['id']]['message'] = $element['label'].' is Required';
                }	


                if (isset($element['validation']) && array_filter($element['validation'])){
                    $form['tools']['validation'][$element['id']] = $element['validation'];
                    $form['tools']['validation'][$element['id']]['status'] = false;
                    if($element['validation']['type'] == 'number' && isset($element['validation']['max_len'])){
                        $tempJsTools .= "$('#$element[id]').inputmask({mask:'9',repeat:{$element['validation']['max_len']},greedy:!1});	";
                    }
                }
                
                if($element['field_type'] != 'code'){
                    $form['fields'][$field] = $new_field;
                    $form['field-types'][] = $element['field_type'];            
                    $form['tools']['titles'][$element['id']] = 'Please Enter the '.$element['label'];                        
                }
            
                }                

        }


        
        $ajaxDepjs = '';        
        if(!empty(array_filter($this->ajax_dependency))){
            
            foreach($this->ajax_dependency as $ajField=>$depElem){
                
                $ajaxDepjs .= 
                "
                $(document).on('change','#$form_config[parent]-$ajField', function(){
                   getDependentFields('$form_config[module]','$form_config[parent]-$ajField'); 
                });
                ";
                $form['tools']['ajax_dependency'][$form_config['parent'].'-'.$ajField] = $form_config['parent'].'-'.key($depElem);                
            }            
        }

        $form['tools']['parent'] = $form_config['parent'];
        //$new_form['tools'] = $tempTools;
        //var_export($tempTools);
        $next_buttons = '';
        $common_title_stripped =preg_replace('/\s+/', '', $this->common_title);


        
        {//// Create Buttons

        $form['html_extra_start'] = isset($form_config['html_extra_start']) ? $form_config['html_extra_start'] : '';
        $form['portlet-title'] = $form_config['portlet_title'];
        $form['portlet-icon'] = $form_config['portlet_icon'];
        $form['form-id'] = $form_config['form_id'];
        $form['submit-id'] = $form_config['submit_id'];
        $next_form .= isset($form_config['hidden-fields']) ? $form_config['hidden-fields'] : ''; 
			
        }

        $form['form'] = $next_form;       
        
        if($form_config['buttons']['enabled']){
            $form['buttons'] = $form_config['buttons']['html'];
        }
        
        $depTemp = array();
        foreach($form_config['dependency'] as $depKeyTemp=>$depValTemp){
            $depTemp[$form_config['parent'].'-'.$depKeyTemp] = $depValTemp;
        }
        $form['dependency'] = $depTemp;
        
        if($form_config['return_form_as'] == 'modal'){
            $modal_html = $this->GetModalForm($form);
            $modal_wrapper = 
            '
            <div class="modal container top-10per animated zoomIn7" id="'.$form_config['parent'].'-modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
            '.$modal_html.'
            </div>            
            ';
            $form['html'] = $modal_wrapper;	
            $form['priorityJs'] = 
            "
            $('body').append(value.html);
            ShowBsModal('$form_config[parent]-modal');
            ";
        }
        else if($form_config['return_form_as'] == 'portlet'){
            $form['html'] = $this->GetPortletForm($form);	
        } 
        else if($form_config['return_form_as'] == 'rows'){
            $form['html'] = row($form['form']);	
        }  

        $form['jsFunction'] = $form['priorityJs'];
        $form['jsFunction'] .= $ajaxDepjs;
        $form['jsFunction'] .= $tempJsTools;
        $form['jsFunction'] .= $tempJsElements;
        $form['jsFunction'] .= $form_config['jsFunction'];
        $form['jsFunction'] .= "";	        
        return $form;  

    }
    
    public function getForm($f,$ajax=false, $form_type='new-form'){

        $tempform = '';
        $next_form = '';
        $new_field = '';
        $fields = array();
        $new_form = array();
        $tempTools = array();
        $hiddenFields = array();
        $tempJsTools = '';
        $tempJsElements = '';
        $tempTools['data'] = $this->dbData;
        foreach($this->dbData as $temkDKey=>$tempDVal){
            $tempTools['data2'][''.$this->dbTable.'-'.$temkDKey.''] = $tempDVal;
        }
        $hiddenFields = isset($f['hidden'][$form_type]) ?  $f['hidden'][$form_type] : array();
        $all_fields = $f['new-fields']; 

        ///// Create Hidden fields if any
        foreach($hiddenFields as $field){
            $next_form .= $field;            
        }
        $next_form .= '<input type="hidden" name="module" id="module" value="'.$this->dbTable.'">';
        
        foreach($all_fields as $field => $value){
            $element['extra'] = 'data-parent="'.$this->dbTable.'" data-module="'.$this->dbTable.'" ';
            $element['extra'] .= isset($value['extra'])?$value['extra']:'';
            $element['onClick'] = isset($value['onClick'])?$value['onClick']:'';
            $element['label'] = isset($value['label'])?$value['label']:$f['label'][$field];
            $element['field_type'] = isset($value['type'])?$value['type']:'input';
            $element['icon'] = isset($value['icon'])? $value['icon'] :(isset($f['icons'][$field])?$f['icons'][$field]:'');
            //$new_form['fields'][$field] = $element['field_type'];
            
                {//// CODEBEGIN
                    if (isset($f['codebegin'][$field])){
                        foreach($f['codebegin'] as $wrapElem){
                            $next_form .= $wrapElem;	
                        }
                    }
                }

                {//// PREPARE THE ARRAY FOR FETCHING FORM ELEMENT
                $tempJsElements .= isset($value['js'])?$value['js']:'';		
                $element['type'] = isset($value['form_group_type'])?$value['form_group_type']:'floating-5-7';		
                $element['name'] = isset($value['name']) ? replaceBetweenBraces($this->dbData,$value['name']) : ''.$this->dbTable.'['.$field.']';   
                $element['id'] = isset($value['id']) ? replaceBetweenBraces($this->dbData,$value['id']) : $this->dbTable.'-'.$field;
               
                $element['class'] = isset($value['class']) ? $value['class'] : '';			
                $element['required'] = in_array ($field,$f['required']) || isset($f['required'][$field]) ? true : false;               
                $element['validation'] = isset($f['validation'][$field]) ? $f['validation'][$field] : array();			
                $element['allowed_duplicate'] = in_array($field,$f['no_duplicate']) ? false : true;
                
                $element['width'] = isset($value['width']) ? $value['width'] : '';
                $element['inputType'] = isset($value['inputType']) ? $value['inputType'] : '';
                $element['help'] = isset($value['help']) ? $value['help'] : 'Please Enter the '.$element['label'].'';			
                if(isset($this->dbData[$field])){
                    $element['value'] = $this->dbData[$field];
                }
                else if (isset($f['default'][$field])){
                    $element['value'] = $f['default'][$field];                    
                }
                else{
                    $element['value'] = '';
                }
                


                }
               
                {///START CREATING THE FORM ELEMENTS
                    $new_field = ''; ///RESET THE VARIABLE TO REMOVE ANY PREVIOUS VALUES
                    if(isset($value['evalcode'])){
                        
                        $evalcode = $value['evalcode'];
                        eval($evalcode);                         
                    }
                    if(isset($element['required']) && $element['required']){
                        $element['label'] = '<span class="font-red">*</span> '.$element['label'].'';
                    }   
                    if($element['field_type'] == 'checkbox'){
                        $element['color'] = isset($value['color']) ? $value['color'] : '';
                        $element['value'] = isset($value['value']) ? $value['value'] : 1;
                        $element['checked'] = isset($this->dbData[$field]) ? $this->dbData[$field] : false;
                        $new_field = $this->checkboxFromArray($element);
                    }
                    else if($element['field_type'] == 'advanceddatetime'){
                        if($element['value'] == '0000-00-00 00:00:00'){
                            $element['value'] = date('Y-m-d H:i:s');
                        }
                        $new_field = $this->advancedDateTimeFromArray($element);				
                    }
                    else if($element['field_type'] == 'date'){
                        
                        $new_field = $this->datepickerFromArray($element);				
                    }
                    else if($element['field_type'] == 'daterangepicker'){
                        $new_field .= 
                        '
                        
                            <label class="control-label ">'.$element['label'].'</label></br>
                            <div id="'.$element['id'].'" class="tooltips btn btn-fit-height green" data-placement="top" data-original-title="Change report date range">
                                <i class="icon-calendar"></i>
                                <span class="thin uppercase hidden-xs"></span>
                                <i class="fa fa-angle-down"></i>
                            </div>
                            <input type="hidden" name="'.$this->dbTable.'[start_date]" id="'.$this->dbTable.'-'.$value['start_date'].'">
                            <input type="hidden" name="'.$this->dbTable.'[end_date]" id="'.$this->dbTable.'-'.$value['end_date'].'">
                        ';	
                        $tempDate = array(
                                'element'=>$element['id'],
                                'startdate'=>$this->dbTable.'-'.$value['start_date'],
                                'enddate'=>$this->dbTable.'-'.$value['end_date'],
                                'button'=>''	
                            );
                        $tempDate = json_encode($tempDate);
                        $tempJsTools .= 
                        "
                        var jsonDate = $tempDate;
                        initDateRangePicker(jsonDate);
                        ";                        
                    }
                    
                    else if($element['field_type'] == 'input'){
                        
                        $new_field = $this->inputFromArray($element);				
                    }
                    else if($element['field_type'] == 'textarea'){
                        $element['type'] = 'floating-4-12';	
                        $new_field = $this->textareaFromArray($element);				
                    }
                    else if($element['field_type'] == 'select'){
                        $element['options'] = $value['options'];
                        if(isset($value['eval'])){
                            $evalcode = $value['eval'];
                            eval($evalcode);                         
                        }
                        if(isset($value['options_func']) && isset($value['ajax_dependency']) && isset($this->dbData[$value['ajax_dependency']])){
                            $element['options'] = call_user_func_array($value['options_func'],array($this->dbData[$value['ajax_dependency']]));
                        }                        
                        $new_field = $this->selectFromArray($element);				
                    }
                    else if($element['field_type'] == 'code'){
                        eval(isset($value['eval'])?$value['eval']:'');	
                        $next_form .=  isset($value['code'])?$value['code']:'';                       
                    }
                    else if($element['field_type'] == 'html'){
                        
                        $new_field = str_replace("__ELEMENT_ID__",$element['id'],$value['html']);                       
                    }

                    ///END START CREATING THE FORM ELEMENTS
                }

                {////Wrap The form field with col-md if specified in the width
                    if(isset($element['width']) && $element['width'] != '' && $element['width'] != '0' && $element['width'] <= '12'){
                        //$next_form .= col($new_field,$element['width']);
                        $left_offset = isset($value['left_offset'])?'col-md-offset-'.$value['left_offset'].'':'';
                        $right_offset = isset($value['right_offset'])?'col-md-offset-right-'.$value['right_offset'].'':'';
                        
                        $next_form .= '<div class="col-md-'.$element['width'].' '.$right_offset.' '.$left_offset.' form-common-element-wrapper" id="'.$element['id'].'-holder" style="">'.$new_field.'</div>';			
                    }else{
                        //$next_form .= col6($new_field);			
                        $next_form .= '<div class="col-md-6 form-common-element-wrapper" id="'.$element['id'].'-holder">'.$new_field.'</div>';		
                    }
                }

                {//// CODEEND
                    if (isset($f['codeend'][$field])){
                        foreach($f['codeend'] as $wrapElem){
                            $next_form .= $wrapElem;	
                        }
                    }
                }
                
                {///// TEMP TOOLS
                
                $tempTools['icons'][$element['id']] = $element['icon'];		
                $tempTools['help'][$element['id']] = $element['help'];		
                //print_r($f);
                if(isset($element['required']) && $element['required']){
                    if(isset($f['required'][$field])){
                        //// prepend table name as the id of the conditional field is also changed as this form is being generated
                        $f['required'][$field]['conditional']['field'] = $this->dbTable.'-'.$f['required'][$field]['conditional']['field'];
                        $tempTools['required'][$element['id']] = $f['required'][$field];
                    }
                    else{
                        $tempTools['required'][$element['id']]['status'] = false;
                        $tempTools['required'][$element['id']]['message'] = $element['label'].' is Required';                        
                    }
                }
                if(isset($element['allowed_duplicate']) && !$element['allowed_duplicate']){
                    $tempTools['duplicate'][$element['id']]['status'] = false;
                    $tempTools['duplicate'][$element['id']]['message'] = $element['label'].' is Required';
                }	


                if (isset($element['validation']) && array_filter($element['validation'])){
                    $tempTools['validation'][$element['id']] = $element['validation'];
                    $tempTools['validation'][$element['id']]['status'] = false;
                    if($element['validation']['type'] == 'number' && isset($element['validation']['max_len'])){
                        $tempJsTools .= "$('#$element[id]').inputmask({mask:'9',repeat:{$element['validation']['max_len']},greedy:!1});	";
                    }
                }
                
                if($element['field_type'] != 'code'){
                    $new_form['fields'][$field] = $new_field;
                    $new_form['field-types'][] = $element['field_type'];            
                    $tempTools['titles'][$element['id']] = 'Please Enter the '.$element['label'];                        
                }
            
                }                

        }
        $ajaxDepjs = '';        
        if(!empty(array_filter($this->ajax_dependency))){
            
            foreach($this->ajax_dependency as $ajField=>$depElem){
                
                $ajaxDepjs .= 
                "
                $(document).on('change','#$this->dbTable-$ajField', function(){
                   getDependentFields('$this->dbTable','$this->dbTable-$ajField'); 
                });
                ";
                $tempTools['ajax_dependency'][$this->dbTable.'-'.$ajField] = $this->dbTable.'-'.key($depElem);                
            }            
        }

        $tempTools['parent'] = $this->dbTable;
        $tempTools['module'] = $this->dbTable;
        $new_form['tools'] = $tempTools;
        //var_export($tempTools);
        $next_buttons = '';
        $common_title_stripped =preg_replace('/\s+/', '', $this->common_title);
        $new_form['jsFunction'] = '';
        $new_form['jsFunction'] .= $ajaxDepjs;
        $new_form['jsFunction'] .= $tempJsTools;
        $new_form['jsFunction'] .= $tempJsElements;

        $new_form['jsFunction'] .= 
        "
        
        ";	

        
        {//// Create Buttons

        $new_form['html_extra_start'] = isset($f['html_extra_start']) ? $f['html_extra_start'] : '';
        $new_form['portlet-title'] = $f['portlet-title'];
        $new_form['portlet-icon'] = $f['portlet-icon'];
        $new_form['form-id'] = $f['form-id'];
        $new_form['submit-id'] = $f['submit-id'];
        $next_form .= isset($f['hidden-fields']) ? $f['hidden-fields'] : ''; 
			
        }

        $new_form['form'] = $next_form;        
        $new_form['buttons'] = $f['buttons'];
        $depTemp = array();
        foreach($this->dependency as $depKeyTemp=>$depValTemp){
            $depTemp[$this->dbTable.'-'.$depKeyTemp] = $depValTemp;
        }
        $new_form['dependency'] = $depTemp;
        return $new_form;  

    }

    public function getNewForm($modal='modal',$ajax=false,$ajax_data=array()){
        $f = $this->getFields('new-form');
        $next_buttons = '';        
        $common_title_stripped =preg_replace('/\s+/', '', $this->common_title);        
        $f['portlet-title'] = 'New '.$this->common_title.'';
        $f['portlet-icon'] = '';
        $f['form-id'] = 'new'.$common_title_stripped.'Form';
        $f['submit-id'] = 'btnSubmit'.$common_title_stripped.'';
            if($ajax){
                if(isset($f['buttons']['new'])){
                    $next_buttons .= str_replace("__FORM_ID__",$f['form-id'],$f['buttons']['new']);
                }
                else{
                    $next_buttons .= $this->Button($f['submit-id'], 'green', 'Save '.$this->common_title.'','Save({\'module\':\''.$this->dbTable.'\'},\''.$f['form-id'].'\')');
                }
                    
            }
            else
            {
                if(isset($f['buttons']['new'])){
                    $next_buttons .= str_replace("_FORM_ID_",$f['form-id'],$f['buttons']['new']);
                }
                else{
                    $next_buttons .= $this->SubmitButton($f['submit-id'], 'green', 'Save '.$this->common_title.'');	
                }                
                
                $f['form-action'] = 'Save'.$this->common_title.'';		
            } 
            
        if(isset($ajax_data['return-action'])){
            if($ajax_data['return-action'] == 'nestable-list'){
                $f['hidden']['new-form'][] = '<input type="hidden" name="return-action"  value="nestable-list">';	
            }
        }            
        $f['buttons'] = $next_buttons;
          
        $form = $this->getForm($f,$ajax,'new-form');
        $form['jsFunction'] .= $f['jsFunction'];
        $form['jsFunction'] .= isset($f['onModalCloseJs'])? $f['onModalCloseJs'] : '';
        
        if(isset($ajax_data['ajax-container'])){
            $form['jsFunction'] .= "$('#${ajax_data['ajax-container']}').html(value['html']);";
        }        

        if($modal == 'modal'){
            $form['html'] = $this->GetModalForm($form);	
        }
        else if($modal == 'portlet'){
            $form['html'] = $this->GetPortletForm($form);	
        } 
        else if($modal == 'rows'){
            $form['html'] = row($form['form']);	
        }
        if($ajax){
            
        }
        return $form;
    }
    
    public function getEditForm($modal='modal',$ajax=false,$ajax_data=array()){
        $f = $this->getFields('edit-form');
        $next_buttons = '';          
        $common_title_stripped =preg_replace('/\s+/', '', $this->common_title);           
        $f['portlet-title'] = 'Edit '.$this->common_title.'';
        $f['portlet-icon'] = '';
        $f['form-id'] = 'edit'.$common_title_stripped.'Form';
        $f['submit-id'] = 'btnUpdate'.$common_title_stripped.'';			

        //$f['hidden-fields'] = '<input type="hidden" name="action" id="form-action" value="update">';
        $f['hidden-fields'] = '';
        $f['hidden-fields'] .= '<input type="hidden" name="'.$this->dbTable.'[id]" id="id" value="'.$this->dbData['id'].'">';
            if($ajax){
                if(isset($f['buttons']['edit'])){
                    $next_buttons .= str_replace("__FORM_ID__",$f['form-id'],$f['buttons']['edit']);
                    $next_buttons .= str_replace("__COMMON_TITLE__",$this->common_title,$f['buttons']['edit']);
                }
                else{
                    $next_buttons .= $this->Button($f['submit-id'], 'green', 'Update '.$this->common_title.'','Update({\'module\':\''.$this->dbTable.'\'},\''.$f['form-id'].'\')');
                }                
                
            }
            else
            {
                $next_buttons .= $this->SubmitButton($f['submit-id'], 'green', 'Update '.$this->common_title.'');	
                $f['form-action'] = 'Save'.$this->common_title.'';		
            }
        $f['buttons'] = $next_buttons;
            
        $form = $this->getForm($f,$ajax,'edit-form');
        $form['jsFunction'] .= $f['jsFunction'];    
        if($modal == 'modal'){
            $form['html'] = $this->GetModalForm($form);	
        }        
        else if($modal == 'portlet'){
            $form['html'] = $this->GetPortletForm($form);	
        }        
        else if($modal == 'rows'){
            $form['html'] = row($form['form']);

        }
        else{
            //$form['html'] = $form['form'];	
        }
        if($ajax){
                    
        }
        //print_r($form);  
        return $form;
    }
        
    public function getEditFormMultiple($data, $modal='modal',$ajax=false){
        $form = '';
        foreach($data as $row){
            $this->dbData = $row;
            $temp = $this->getEditForm($modal,$ajax);
            //print_r($temp);
            $form .= $temp['html'];
        }
        return $form;
    }
        
    public function getChooseForm($title,$modal='modal',$ajax=false){
        $next_buttons = '';          
        $common_title_stripped =preg_replace('/\s+/', '', $this->common_title);           
        $form['portlet-title'] = 'Choose '.$this->common_title.' to '.$title.'';
        $form['portlet-icon'] = '';
        $form['form-id'] = 'choose'.$common_title_stripped.'Form';
        $form['submit-id'] = 'btnChoose'.$common_title_stripped.'';
        $element['label'] = 'Choose '.$common_title_stripped.'';       
        $element['id'] = 'chosen';       
        $element['type'] = 'floating-4-6';       
        $element['name'] = 'chosen';
            
        $sql = $this->selectListSql; 
        if ($sql == '') $sql = "SELECT id, name FROM $this->dbTable"; 
               
        $element['options'] = $this->getSelectOptions($sql);       
        //$element['options'] = $this->getSelectOptions($sql);       
        $form['form'] = col12($this->selectFromArray($element));        
        $form['buttons'] = '<button id="" class="btn btn-outline green" onClick="CommonFunc(\'\',\''.$form['form-id'].'\')">'.$title.'</button>';        
        $form['form'] .= '<input type="hidden" name="type" id="form-action" value="'.strtolower($title).'">';       
        
        if($modal == 'modal'){
            $temp = $this->GetModalForm($form,false);
            $form['html'] = 
            '
            <div class="col-md-4 col-md-offset-4 col-xs-10 col-xs-offset-1 well animated hidden" id="choose-to" style="position:fixed;top:80px;left:0px;z-index:9">
            '.$temp.'
            </div>
            ';        
        }
        else if($modal == 'portlet'){
            $temp = $this->GetPortletForm($form);
            $form['html'] = 
            '
            <div class="col-md-4 col-md-offset-4 col-xs-10 col-xs-offset-1 well animated hidden" id="choose-to" style="position:fixed;top:80px;left:0px;z-index:9">
            '.$temp.'
            </div>
            ';        
        }
        $form['jsFunction'] = 
        "

        ShowMyElement('choose-to');
        tools = [];        
        ";                 
        return $form;
    }
    
    public function getDependencyJs(){
        $arr['jsFunction'] = 
        "
        var currentSelectedDependency = false;
        ";
        
        $i = 0;
        
        $depTemp = array();
        foreach($this->dependency as $depKeyTemp=>$depValTemp){
            $depTemp[$this->dbTable.'-'.$depKeyTemp] = $depValTemp;
        }
              
        foreach($this->dependency as $depKey=>$depValue){
            $depTempJson = json_encode($depTemp[$this->dbTable.'-'.$depKey]);  
            //print_r($depValue);
            //print_r($depTemp);
            $i++;
            $depKey = $this->dbTable.'-'.$depKey;
                $arr['jsFunction'] .= 
                "    
                    var currentSelected$i = false;
                    $(document).on('change','#$depKey', function(){
                        //var dep = value.dependency['$depKey'];
                        var dep = JSON.parse('$depTempJson');
                        
                        var prevSelected = currentSelected$i;
                        var selected = $('#$depKey').val(); 
                        currentSelected$i = selected;
                        var delay = 500;
                        //console.log(prevSelected);
                        //console.log(currentSelected$i);
                        
                        if(prevSelected !== false && dep.hasOwnProperty(prevSelected)){
                            if (dep[prevSelected].hasOwnProperty('hide')) {
                                jQuery.each(dep[prevSelected].hide, function(i, elem){
                                   $('#$this->dbTable-'+elem+'').removeClass('hidden');
                                   $('#$this->dbTable-'+elem+'').closest('.form-common-element-wrapper').removeClass('animated'+delay+' zoomOut hidden');
                                   $('#$this->dbTable-'+elem+'').closest('.form-common-element-wrapper').addClass('zoomIn');
                                });
                                
                                //// RUN THE LOOP AGAIN TO TRIGGER CHANGE IF ANY FOR THE ELEMENT
                                //// RUNNING LOOP SEPERATELY IS IMPORTNT SO THAT IT DOESNT CONFLICT WITH THE ELEMENTS OF THE ABOVE LOOP
                                jQuery.each(dep[prevSelected].hide, function(i, elem){
                                   
                                   $('#$this->dbTable-'+elem+'').trigger('change');
                                   $('#$this->dbTable-'+elem+'').addClass('edited');
                                });	                                
                            }                                
                        } 

                        
                        if (dep.hasOwnProperty(selected)) {
                            
                            if (dep[selected].hasOwnProperty('hide')) {
                                if($('#$depKey').is(':checkbox')){
                                    var checked =$('#$depKey').is(':checked');                                    
                                    console.log('checkbox');
                                    if(checked){
                                        jQuery.each(dep[selected].hide, function(i, elem){
                                            $('#$this->dbTable-'+elem+'').addClass('hidden');
                                           $('#$this->dbTable-'+elem+'').closest('.form-common-element-wrapper').addClass('animated'+delay+' zoomOut');
                                           window.setTimeout(function() { 
                                                $('#$this->dbTable-'+elem+'').closest('.form-common-element-wrapper').addClass('hidden');
                                           }, delay);
                                        });                                    
                                    }
                                } 
                                else{
                                    jQuery.each(dep[selected].hide, function(i, elem){
                                        console.log(elem);
                                        console.log($('#$this->dbTable-'+elem+'').closest('.form-common-element-wrapper'));
                                        $('#$this->dbTable-'+elem+'').addClass('hidden');
                                       $('#$this->dbTable-'+elem+'').closest('.form-common-element-wrapper').addClass('animated'+delay+' zoomOut');
                                       window.setTimeout(function() { 
                                            $('#$this->dbTable-'+elem+'').closest('.form-common-element-wrapper').addClass('hidden');
                                       }, delay);
                                    });                                
                                }                                

                            }
                        }			

                    });
            
                
                    //var depTemp = value.dependency['$depKey'];
                    var depTemp = JSON.parse('$depTempJson');
                    var selected = $('#$depKey').val();
                    if(selected != ''){
                       currentSelected$i = selected; 
                    }                    
                    var delay = 10;
                    if (depTemp.hasOwnProperty(selected)) {	

                        if (depTemp[selected].hasOwnProperty('hide')) {
                            
                                if($('#$depKey').is(':checkbox')){
                                    var checked = $('#$depKey').is(':checked');                                    
                                    console.log('checkbox');
                                    if(checked){
                                        jQuery.each(depTemp[selected].hide, function(i, elem){
                                            $('#$this->dbTable-'+elem+'').addClass('hidden');
                                           $('#$this->dbTable-'+elem+'').closest('.form-common-element-wrapper').addClass('animated'+delay+' zoomOut');
                                           window.setTimeout(function() { 
                                                $('#$this->dbTable-'+elem+'').closest('.form-common-element-wrapper').addClass('hidden');
                                           }, delay);
                                        });                                    
                                    }
                                } 
                                else{
                                    jQuery.each(depTemp[selected].hide, function(i, elem){
                                        $('#$this->dbTable-'+elem+'').addClass('hidden');
                                       $('#$this->dbTable-'+elem+'').closest('.form-common-element-wrapper').addClass('animated'+delay+' zoomOut');
                                       window.setTimeout(function() { 
                                            $('#$this->dbTable-'+elem+'').closest('.form-common-element-wrapper').addClass('hidden');
                                       }, delay);
                                    });                              
                                }                             
                            
                            
                            

                        }
                    }      
                    ";  
                    
        }

        return $arr['jsFunction'];
    }

    public function getMtmForm($id,$assoc='assoc',$multiple=false){
        $this->getManyToManyFormFields();
        $temp = false;
        if(isset($this->many_to_many[0]['joining'])){
            $joining_table = $this->many_to_many[0]['joining'];
            $gp_data = $this->loadAssociatedFromDB($joining_table,$id,$assoc);
            $mtm = $this->many_to_many[0];
            $next_form = '';
            $next_buttons = '';
            $conf['this_id'] = $id;
            if($multiple){
                foreach($mtm['buttons-if-multiple'] as $button){
                    $next_buttons .= $button;	
                }
            }
            else{
                foreach($mtm['buttons'] as $button){
                    $next_buttons .= $button;	
                }    
            }
            foreach($mtm[$assoc]['hidden'] as $hiddenElem){
                $next_form .= replaceBetweenBraces($conf,$hiddenElem);	
            }
            $temp = array();

            ///// EVAL ANY INITIALIZATION CODE OUTSIDE ALL THE LOOPS
            if(isset($mtm['init-eval-code'])) eval($mtm['init-eval-code']);     
            
            $many_to_many_table = $mtm[$assoc]['table'];
            $sql_cond = isset($mtm[$assoc]['sql_condition']) ? 'AND '.$mtm[$assoc]['sql_condition'].'' : '';
            $sql = "SELECT id,name FROM $many_to_many_table WHERE status='active' $sql_cond";
            $query = mysqli_query($this->dbConn, $sql);
       
            while($many_to_many_data = mysqli_fetch_array($query, MYSQLI_ASSOC)){       

                $next_form .= $this->loopThroughFields($mtm,$many_to_many_data); 
                $temp['jsFunction'] = $mtm['jsFunction']; 
            }
            
            $temp['form'] = $next_form;
            $this_data = $this->loadFromDB($id);
            $temp['portlet-title'] = replaceBetweenBraces($this_data,$mtm[$assoc]['title']);
            $temp['portlet-sub-title'] = $mtm[$assoc]['sub-title'];
            $temp['form-id'] = $mtm['form-id'];
            $temp['buttons'] = $next_buttons;        

            $temp['html'] = $this->GetModalForm($temp);
            //$temp['assdata'] = $assData;            
                
        }


        return $temp;  

    }

    public function getAjaxDependentFields($fieldId,$value,$custom_field=false){
        if(!$custom_field){
            $arrTemp = explode('-',$fieldId);
            $field = end($arrTemp); 
        }
        else{
            $field = $fieldId;
        }

        
        $return = array();
        $return['jsFunction'] = "";
        $f = $this->getFields();
        foreach($this->ajax_dependency[$field] as $dep=>$depCode){
            $element['extra'] = 'data-parent="'.$this->dbTable.'"';
            $element['label'] = isset($f['label'][$dep])?$f['label'][$dep]:'';
            $element['icon'] = isset($f['icons'][$dep])?$f['icons'][$dep]:'';
            $element['type'] = 'floating-2-12';
            $element['name'] = isset($f['name'][$dep]) ? $f['name'][$dep] : ''.$this->dbTable.'['.$dep.']';
            $element['id'] = isset($f['id'][$dep]) ? $f['id'][$dep] : $this->dbTable.'-'.$dep;
            $element['class'] = isset($f['class'][$dep]) ? $f['class'][$dep] : '';
            $element['required'] = in_array ($dep,$f['required']) ? true : false;
            $element['validation'] = isset($f['validation'][$dep]) ? $f['validation'][$dep] : array();			
            $element['allowed_duplicate'] = in_array($dep,$f['no_duplicate']) ? false : true;
            $element['value'] = isset($f['default'][$dep]) ? $f['default'][$dep] : '';
            $element['width'] = isset($value['width']) ? $value['width'] : '';
            $element['help'] = 'Please Enter the '.$element['label'].'';
            //// Js Function is before the evald code so that if we want to override jsfunction we can do it in the evald code
            $return['jsFunction'] .= 
            "
                console.log(value.$dep);
                $('#$this->dbTable-$dep-holder').html(value.$dep);
            ";            
            eval($depCode['code']);  
            $return[$dep] = $form_element;
            
        }
        return $return;  

    }

    public function loopThroughFields($mtm,$many_to_many_data){
        {
        $temp_form = '';

        $next_form = '';
        $joining_table = $mtm['joining'];
        $assData = isset($this->assocData[$joining_table][$many_to_many_data['id']])?$this->assocData[$joining_table][$many_to_many_data['id']]:false;                  

        $conf['hidden'] = 'hidden'; 
        
        $outer = $mtm['loop_wrapper'];
        $outer_code_begin = '';
        $outer_code_end = '';
        $master_check = '';
        
        foreach($mtm['loop_wrapper']['codebegin'] as $wrapElem){
            $outer_code_begin .= $mtm['wrappers'][$wrapElem]['codebegin'];	
        }                
        foreach($mtm['loop_wrapper']['codeend'] as $wrapElem){
            $outer_code_end .= $mtm['wrappers'][$wrapElem]['codeend'];	
        }
              
        //LOOP THROUGH EACH FIELDS TO BE PRESENTED IN THE FORM
        foreach($mtm['fields'] as $fieldKey => $fieldVal){

                //// BEGIN CREATING FORM ELEMENTS
                $form_element = '';
                $fieldVal['label'] = replaceBetweenBraces($many_to_many_data, $fieldVal['label']);
                $fieldVal['name'] = replaceBetweenBraces($many_to_many_data, $fieldVal['name']);
                if(!isset($assData[$fieldKey])){
                    $fieldVal['value'] =  '';                   
                }
                else{
                    $fieldVal['value'] = replaceBetweenBraces($assData, $fieldVal['value']);
                }
                
                $fieldVal['id'] = replaceBetweenBraces($many_to_many_data, $fieldVal['id']);
                $fieldVal['type'] = 'floating-4-4';
                    
                if(isset($mtm['fields-loop-eval-code'])) eval($mtm['fields-loop-eval-code']);
                if(isset($fieldVal['eval'])) eval($fieldVal['eval']);

                //// BEGIN WRAPPER CODE 
                $begin_wrapper = '';                   
                if (isset($fieldVal['codebegin']) && array_filter($fieldVal['codebegin'])){
                    foreach($fieldVal['codebegin'] as $wrapElem){ 
                        $begin_wrapper .= replaceBetweenBraces($conf, $mtm['wrappers'][$wrapElem]['codebegin']);	
                    }   
                }
                
                ///// END WRAPPER CODE
                $end_wrapper = '';
                if (isset($fieldVal['codeend']) && array_filter($fieldVal['codeend'])){
                    foreach($fieldVal['codeend'] as $wrapElem){
                        $end_wrapper .= $mtm['wrappers'][$wrapElem]['codeend'];	
                    }

                }
                
                $temp_form .= 
                '
                '.replaceBetweenBraces($conf,$begin_wrapper).'
                    '.$form_element.'
                 '.$end_wrapper.'
                ';            
        }

       $next_form .= 
       '
        '.$outer_code_begin.'
            '.$master_check.'
            '.$temp_form.'
        '.$outer_code_end.'    
       ';

        return $next_form;       
            
        }
               
    }

    public function loopThroughFieldsCommon($fieldsArr,$dataArr){
        {
        $temp_form = '';
        $form = isset($fieldsArr['hidden'])?$fieldsArr['hidden']:'';              
        $outer_code_begin = '';
        $outer_code_end = '';
        $return['jsFunction'] = '';
        if(isset($fieldsArr['eval-begin'])) eval($fieldsArr['eval-begin']);

        
        //LOOP THROUGH EACH FIELDS TO BE PRESENTED IN THE FORM
        foreach($fieldsArr['fields'] as $fieldKey => $fieldVal){
                $return['jsFunction'] .= isset($fieldVal['js'])?$fieldVal['js']:'';
                //// BEGIN CREATING FORM ELEMENTS
                $form_element = '';
                $element['label'] = isset($fieldsArr['label'][$fieldKey])?$fieldsArr['label'][$fieldKey]:'';
                ///$element['label'] = replaceBetweenBraces($dataArr, $fieldVal['label']);
                $element['name'] = isset($fieldVal['name'])?replaceBetweenBraces($dataArr, $fieldVal['name']): ''.$this->dbTable.'['.$fieldKey.']';
                $element['value'] = isset($fieldVal['value'])?replaceBetweenBraces($dataArr, $fieldVal['value']): '';
                $element['id'] = isset($fieldVal['id'])?replaceBetweenBraces($dataArr, $fieldVal['id']): $fieldKey;
                $element['field_type'] = isset($fieldVal['type'])?$fieldVal['type']:'';
                $element['icon'] = isset($fieldVal['icon'])?$fieldVal['icon']:'';                
                $element['type'] = 'floating-4-6';							
                $element['class'] = isset($fieldVal['class']) ? $fieldVal['class'] : '';
                
                $element['required'] = in_array ($fieldKey,$fieldsArr['required']) ? true : false;			
                $element['validation'] = isset($fieldsArr['validation'][$fieldKey]) ? $fieldsArr['validation'][$fieldKey] : array();			
                $element['allowed_duplicate'] = in_array($fieldKey,$fieldsArr['no_duplicate']) ? false : true;
                
                $element['width'] = isset($fieldVal['width']) ? $fieldVal['width'] : '';
                $element['options'] = isset($fieldVal['options']) ? $fieldVal['options'] : '';
                $element['inputType'] = isset($fieldVal['inputType']) ? $fieldVal['inputType'] : '';
                $element['help'] = 'Please Enter the '.$element['label'].'';	                

                {///START CREATING THE FORM ELEMENTS
                    if(isset($fieldsArr['common-eval-begin'])) eval($fieldsArr['common-eval-begin']);
                    if(isset($fieldVal['eval-begin'])) eval($fieldVal['eval-begin']);
   
                    if($element['field_type'] == 'checkbox'){

                        $form_element = $this->checkboxFromArray($element);
                    }
                    else if($element['field_type'] == 'date'){
                        
                        $form_element = $this->datepickerFromArray($element);				
                    }
                    else if($element['field_type'] == 'input'){
                        
                        $form_element = $this->inputFromArray($element);				
                    }
                    else if($element['field_type'] == 'textarea'){
                        $element['type'] = 'floating-4-12';	
                        $form_element = $this->textareaFromArray($element);				
                    }
                    else if($element['field_type'] == 'select'){                       
                        $form_element = $this->selectFromArray($element);				
                    }
                    else if($element['field_type'] == 'code'){                       
                        if(isset($element['eval'])) eval($element['eval']);			
                    }
                    
                    
                    {////Wrap The form field with col-md if specified in the width
                        if(isset($element['width']) && $element['width'] != '' && $element['width'] != '0' && $element['width'] <= '12'){
                            
                            $left_offset = isset($fieldVal['left_offset'])?'col-md-offset-'.$fieldVal['left_offset'].'':'';
                            $right_offset = isset($fieldVal['right_offset'])?'col-md-offset-right-'.$fieldVal['right_offset'].'':'';
                            
                            $form_element = '<div class="col-md-'.$element['width'].' '.$right_offset.' '.$left_offset.' form-common-element-wrapper" id="'.$element['id'].'-holder">'.$form_element.'</div>';			
                        }
                        else{
                            
                            $form_element = '<div class="col-md-6 form-common-element-wrapper" id="'.$element['id'].'-holder">'.$form_element.'</div>';		
                        }

                    }


                
                    if(isset($fieldVal['eval-end'])) eval($fieldVal['eval-end']);
                    if(isset($fieldsArr['common-eval-end'])) eval($fieldsArr['common-eval-end']);                    
                    ///END START CREATING THE FORM ELEMENTS
                }

                {///// TEMP TOOLS
                $tempTools['icons'][$element['id']] = $element['icon'];		

                if(isset($element['required']) && $element['required']){
                    $tempTools['required'][$element['id']]['status'] = false;
                    $tempTools['required'][$element['id']]['message'] = $element['label'].' is Required';
                }
                if(isset($element['allowed_duplicate']) && !$element['allowed_duplicate']){
                    $tempTools['duplicate'][$element['id']]['status'] = false;
                    $tempTools['duplicate'][$element['id']]['message'] = $element['label'].' is Required';
                }	


                if (isset($element['validation']) && array_filter($element['validation'])){
                    $tempTools['validation'][$element['id']] = $element['validation'];
                    $tempTools['validation'][$element['id']]['status'] = false;
                }
            
                }
                
                $temp_form .= $form_element;
          
        }

        if(isset($fieldsArr['eval-end'])) eval($fieldsArr['eval-end']);        
        $form .= $temp_form;
        $return['form'] = $form;
        $return['tools'] = $tempTools;
        return $return;       
            
        }
               
    }

    }
?>
