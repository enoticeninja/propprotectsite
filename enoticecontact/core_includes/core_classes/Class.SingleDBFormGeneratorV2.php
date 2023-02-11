<?php
require_once DIR_OTHER_CLASSES.'class_formV2.php'; 

class SingleDBFormGeneratorV2 extends FormV2
{
    public $buttons = array();
    public $form_group_type = 'floating-12-12';
    public $form_depth = 1;
    public $include_css = array();
    public $include_js = array();
    public $field_array = array(
            'id'=>'',
            'name'=>'',
            'name_prefix'=>false,
            'icon'=>'',
            'label'=>'',
            'class'=>'',
            'width'=>'6',
            'left_offset'=>'0',
            'right_offset'=>'0',
            'extra'=>'',
            'type'=>'input',
            'options'=>array(),
            'is_value_array'=>false,///// IN CASE OF MULTIPLE SELECT IS THE VALUE FROM DATABASE IN FORM OF AN ARRAY
            'seperator'=>'',//// IN CASE OF MULTIPLE SELECT HOW IS THE VALUES SEPERATED EG. CSV ETC.
            'code_begin'=>'',	//// HTML CODE BEFORE ELEMENT	
            'code_end'=>'',		//// HRML CODE AFTER ELEMENT
            'evalcode'=>'',	//// BEFORE EACH ELEMENT IS CREATED			
            'eval'=>'',		/// FOR EACH ELEMENT
            'html'=>'',		
            'hidden'=>false, ////hidden class on common element form wrapper
            'one_image' => false, //// IN case of image if multiple images
            'multiple_image' => false, //// IN case of image if multiple images
            'image_button' => false,	/// in csse of image field, specify custom button	
            'placeholder'=>'',	
            'start_date' => '',//// USED IN DATERANGE : THE START DATE FIELD NAME
            'end_date' => '',	///// USED IN DATERANGE : THE END DATE FIELD NAME
            'value'=>'',		
            'default'=>'',		
            'required'=>false,		
            'validation'=>false,		
            'allowed_duplicate'=>true,		
            'inputType'=>'text',		
            'form_group_type'=> 'floating-12-12',		
            'help'=>'',		
            'js'=>'',		
            'max_length'=>0,		
            'has_icons'=>false,		
            'live_search'=>false,		
            'select2'=>false,
            'bootstrap'=>false,
            'color'=>'',		
            'checked'=>'',
            'options_func'=>'',		
            'code'=>'',		
            'popover'=>false,		
        );

    
    public function include_js(){
       $includes = '';
        foreach($this->include_js as $include_file){
            $includes .= '<script src="'.get_core_theme_path().''.$include_file.'" type="text/javascript"></script>' ;
        }
        return $includes;
    }   
    
    public function include_css(){
       $includes = '';
        foreach($this->include_css as $include_file){
            $includes .= '<link href="'.get_core_theme_path().''.$include_file.'" rel="stylesheet" type="text/css" />' ;
        }
        return $includes;
    }
    
    public function __construct($db_conx = '') {

    }
    
    public function getFormConfigurationAll($data){
            /////Form Configuration //////////////  
            
        {$return['forms']['new'] = array(
                'form_id'=>'new_form',
                'is_multiple_form'=>false,
                'min_multiple_forms'=>false,
                'max_multiple_forms'=>false,
                'nested_form'=>false,
                'form_depth'=>0,
                'extra_html'=>'',
                'extra_unreplaced_html'=>'',
                'custom_wrapper_begin'=>'',
                'custom_wrapper_end'=>'',
                'from_another_class'=>false,/// If the form is fetched from another class
                'class_name'=>'',/// If the form is fetched from another class
                'class_form_type'=>'', /// If the form is fetched from another class
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
                        'enabled'=>false
                        ),
                'has_data'=>false,
                'parent'=>'permission_module_new',//// WILL BE USED MOSTLY IN CASE OF MULTIPLE TABS OF SAME OR DIFFERENT CLASSES, TO INDENTIFY WHICH ELEMENT BELONGS WHERE
                'module'=>$this->dbTable,////ALWAYS THE DATABASE TABLE NAME OR ANY NAME SPECIFIED IN THE RouterX
                'before_processing' => false,
                'after_processing' => false,
                'jsFunction_after_save' => '',
                'unique_element_id'=>'',
                'action'=>'',
                'form_group_type'=>$this->form_group_type,
                'method'=>'',
                'return_after_save'=> array(),
                'hidden'=> array(
                        'enabled'=>false,
                            'html'=>''
                                ),
                'buttons'=> array(
                        'enabled'=>true,
                        'html'=>'',
						'array'=> array(
							'save'=>array(
								'actions'=>array(
									'module'=>'__MODULE__',
									'form_type'=>$data,
									'extended_action'=>'save',
								),
								'next_actions'=>array(),
								'class'=>'btn-outline green',
								'func'=>'CommonFunc2',
								'title'=>'Save',
								'extra'=>'',
							)
						)
                        ),
                'dependency'=> array(),
                'duplicate'=> array(),
                'validation'=> array(),
                'unique_in_level'=> array(),
                'duplicate_combination'=> array(),
                'jsFunction'=>'',
                'jsFunctionLast'=>'',
                'jsModalClose'=>'',
                'modal_width'=>'70%',
                'on_submit'=>array(
                        'action'=>'save',
                        'further_action'=>'save_log',
                        'return'=>'table_row',
                        'return_js'=>''		
                    ),	
                'default_insert'=>array( //// ????? these values would be forced insert
                    'enabled'=>false,
                    //'date_created'=>date('Y-m-d H-i-s'),
                    //'created_by'=>$_SESSION[get_session_values('id')]	
                ),
                'on_submit_error'=>'',
                'name_prefix'=>'',
                'id_prefix'=>true, /// If true ids will be prefixed with parent
                'fields_function'=>array(
                        'enabled'=>false,
                        'function'=>'getSecondaryForm'		
                    ),
                'has_custom_fields'=>false,               
				'small_input' => true, 
                'custom_fields'=>array(),
                'fields'=>array('type','name','page_name','page_link','icon','menu_position','parent_id','has_children','status','description'), //// SPECIFY THE ORDER THAT WE WANT, THIS WILL BE THE MAIN GROUP OF THE FORM ELEMENTS
                'required'=>array('name','page_name','type','status'),	
                'default_values'=>array( //// these values are for giving initial values in the form and can be changed
                    'enabled'=>false,
                    'status'=>'active'	
                ),
                'override_fields'=>array('enabled'=>false),
                'group_fields'=>array( //// IF ELEMENTS ARE TO BE IN DIFFERENT SECTIONS OF THE FORM IN A SPECIFIED ORDER
                        'enabled'=>false,
                        'groups'=>array(
                                'rows'=>array(),
                                'modal'=>array(),
                                'popover'=>array(),
                                'custom'=>array(
                                    'wrapper'=>'SOME HTML CODE',
                                    'fields'=>array(),
                                    ),
                            //// implement custom html to wrap the group
                            )
                        
                        ),
                'non_db_fileds'=>array('','','',''),
                'override_non_db_fileds'=>array('enabled'=>false)
                    );

        }
            /////End Form Configuration //////////////         
        return $return['forms']['new'];  
    }     
 
    public function createFormElement($field,$element){
        $new_field = '';   
        $new_js = '';
        if($element['popover']){
            $popover_template = 
            '
             <div class="popover" role="tooltip" data-background-color="orange" style="top:50px;left:100px">
                 <div class="arrow"></div>
                 <div class="popover-content">
                 
                 </div>
              </div>         
            ';             
            $new_js .= 
            "
            var pop_on = $('#__ELEMENT_ID__-holder');
            pop_on.popover({
                title: '',
                content : '$element[popover]',
                trigger:'hover',
                html: true,
                container: pop_on,
                placement: 'top',
                template: `$popover_template`,
                show: function () {
                    $(this).fadeIn('slow');
                }
            }); 
            //pop_on.popover('show');            
            ";
        }
        if($element['hidden']) $element['class'] .= ' hidden';
        {///START CREATING THE FORM ELEMENTS
            if(($element['max_length'] > 0)){
                $new_js .= '$("#'.$element['id'].'").maxlength({alwaysShow:!0,warningClass:"label label-success",limitReachedClass:"label label-danger",validate:!0});';
                $element['extra'] .= ' maxlength="'.$element['max_length'].'"';
            }  
            
            //EVALCODE
            eval($element['evalcode']);
            
            if(isset($element['required']) && $element['required']){
                $element['label'] = '<span class="font-red">*</span> '.$element['label'].'';
            }   
            if($element['type'] == 'hidden'){
                $new_field .= '<input type="hidden" name="'.$element['name'].'" id="'.$element['id'].'" value="'.$element['value'].'">';
            }   
            else if($element['type'] == 'checkbox'){
                $element['checked'] = (isset($this->dbData[$field]) && $this->dbData[$field] == $element['value']) ? true : false;
                $new_field .= $this->checkboxFromArray($element);
            }   
            else if($element['type'] == 'checkboxGroupFromArray'){
                $new_field .= $this->checkboxGroupFromArray($element);
            }
            else if($element['type'] == 'advanceddatetime'){
                $this->include_css['advanceddatetime'] = 'assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css';
                $this->include_js['advanceddatetime'] = 'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js';
                //$this->include_js['componentsdatetimepickers'] = 'assets/pages/scripts/components-date-time-pickers.min.js';
                if($element['value'] == '0000-00-00 00:00:00'){
                    $element['value'] = date('Y-m-d H:i:s');
                }
                $new_field .= $this->advancedDateTimeFromArray($element);
                $date_format = getDefault($element['date_format'],'yyyy-m-d hh:ii');
                $picker_position = getDefault($element['picker_position'],'top-left');
                $new_js .= 
                "
                $('#datetime-__ELEMENT_ID__').datetimepicker({
                    autoclose: !0,
                    isRTL: false,
                    format: '$date_format',
					todayBtn: !0,
                    pickerPosition: '$picker_position'
                });                
                ";
            }
            else if($element['type'] == 'image_crop'){
                $button = $element['image_html'];
                //$temp_new_field = $this->getImageEditModal(array('unique_id'=>'__UNIQUE_ID__','module'=>'__MODULE__','multiple_image'=>$element['multiple_image'],'one_image'=>$element['one_image']));
                $new_field .=  $button;
                //$new_js .= "$('body').append(`$temp_new_field[html]`);"; 
                //$new_js .= $temp_new_field['jsFunction']; 
            
            }
            else if($element['type'] == 'daterangepicker'){
                $this->include_css['daterangepicker'] = 'assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css';
                $this->include_js['daterangepicker'] = 'assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js';                        
                $new_field .= 
                '
                    <label class="control-label ">'.$element['label'].'</label></br>
                    <div id="'.$element['id'].'" class="tooltips btn btn-fit-height green" data-placement="top" data-original-title="Change report date range">
                        <i class="icon-calendar"></i>
                        <span class="thin uppercase hidden-xs"></span>
                        <i class="fa fa-angle-down"></i>
                    </div>
                    <input type="hidden" name="__NAME_PREFIX__['.$element['start_date'].']" id="__UNIQUE_ID__-'.$element['start_date'].'" value="'.getDefault($this->dbData[$element['start_date']],'').'">
                    <input type="hidden" name="__NAME_PREFIX__['.$element['end_date'].']" id="__UNIQUE_ID__-'.$element['end_date'].'" value="'.getDefault($this->dbData[$element['end_date']],'').'">
                ';	
                $tempDate = array(
                        'element'=>$element['id'],
                        'startdate'=>'__UNIQUE_ID__-'.$element['start_date'],
                        'enddate'=>'__UNIQUE_ID__-'.$element['end_date'],
                        'button'=>''	
                    );
                if(isset($element['drops'])) $tempDate['drops'] = $element['drops'];
                if(isset($element['opens'])) $tempDate['opens'] = $element['opens'];
                if(isset($element['range'])) $tempDate['range'] = $element['range'];
                $tempDate = json_encode($tempDate);
                $new_js .= 
                "
                var jsonDate = $tempDate;
                initDateRangePickerCommon(jsonDate);
                ";                        
            }
            
            else if($element['type'] == 'date'){
                
                $new_field .= $this->datepickerFromArray($element);				
            }
            else if($element['type'] == 'input'){
                
                $new_field .= $this->inputFromArray($element);				
            }
            else if($element['type'] == 'textarea'){
                $new_field .= $this->textareaFromArray($element);				
            }
            else if($element['type'] == 'select'){

                if($element['bootstrap']){ //// If Has icons then put the js code, set has icons true
                   $new_js .='$(".bs-select").selectpicker();' ;
                   $element['class'] .= ' bs-select';
                   $element['extra'] .= ' data-live-search="true" data-size="8" data-selectOnTab="true" data-showTick="true"';
                   $element['form_group_type'] = 'default-12-12';
                }
                else if($element['has_icons']){ //// If Has icons then put the js code, set has icons true
                   $new_js .='$(".bs-select").selectpicker({iconBase:"fa",tickIcon:"fa-check"});' ;
                   $element['form_group_type'] = 'default-12-12';
                }
                else if($element['select2']){ //// If Has icons then put the js code, set has icons true
                   $new_js .='$("#'.$element['id'].'").select2({container:"#__UNIQUE_ID__-main-container"});' ;
                   $element['form_group_type'] = 'default-12-12';
                }

                eval($element['eval']);
                if(isset($element['options_func']) && isset($element['ajax_dependency']) && isset($default_values['db_data'][$value['ajax_dependency']])){
                    $element['options'] = call_user_func_array($element['options_func'],array($default_values['db_data'][$element['ajax_dependency']]));
                }
                $new_field = $this->selectFromArray($element);
            }
            else if($element['type'] == 'multiple-select'){
                if($element['has_icons']){ //// If Has icons then put the js code, set has icons true
                   $new_js .='$(".bs-select").selectpicker({iconBase:"fa",tickIcon:"fa-check"});' ;
                } 
                if($element['select2']){ //// If Has icons then put the js code, set has icons true
                   $new_js .='$("#'.$element['id'].'").select2({container:"#__UNIQUE_ID__-main-container"});' ;
                   $element['form_group_type'] = 'default-12-12';
                }                
                eval($element['eval']);
                if(isset($element['options_func']) && isset($element['ajax_dependency']) && isset($default_values['db_data'][$element['ajax_dependency']])){
                    $element['options'] = call_user_func_array($element['options_func'],array($default_values['db_data'][$element['ajax_dependency']]));
                }                        
                $new_field = $this->multipleSelectFromArray($element);				
            }
            else if($element['type'] == 'code'){
                eval($element['eval']);	
                $new_field .=  isset($element['code'])?$element['code']:'';                       
            }
            else if($element['type'] == 'html'){                    
                $new_field .= $element['html'];                       
            }
            
            
            ///END START CREATING THE FORM ELEMENTS
        }
        $new_js .= $element['js'];
        $return['field'] = $new_field;
        $return['js'] = $new_js;
        return $return;
   
    }
    
    
    public function getExtendedForm($request_data){
        
		if(isset($request_data['call_type']) && $request_data['call_type'] == 'ajax') $request_data['return_form_as'] = 'modal';
		$form_config = $this->setFormConfiguration($request_data);
		if($form_config){
			$form_config['form_type'] = $request_data['form_type'];
			$form_config['request_data'] = $request_data;
			$form = $this->getSingleForm($form_config);
			return $form;
		}
		else{
			return false;
		}

    }
    
    public function getSingleForm($form_config){
        {/// Initial Config
        $tempform = '';
        $next_form = '';
        $new_field = '';
        $fields = array();
        $new_form = array();
        $depTemp = array();
        $tempTools = array();
        $hiddenFields = array();		
        $unReplacedJs = array();		
        $unReplacedJs['high_priority'] = $unReplacedJs['mid_priority'] = $unReplacedJs['low_priority'] = '';
        $form = array(); /// will hold every thing as an associative array
        $tempJsTools = '';
        $tempJsElements = '';
        $is_another_class = false;
        if($form_config['from_another_class']){
            $is_another_class = true;
            $className = $form_config['class_name'];
            $methodName = 'setTableFields';
            $class = new $className($db_conx);
            $form_config['form_type'] = $form_config['class_form_type'];
            $form_config = $class->setFormConfiguration($form_config['request_data']);  
        }        
        //// If form is repeat/multiple form call the multiple form function and return the value
        if($form_config['is_multiple_form']){
            $form = $this->getExtendedFormMultiple($form_config['request_data']);
            return $form;
            exit();
        }
        
        $form = $form_config;
        $form['tools']['validation'] = array();
        $form['tools']['required'] = array();
        $form['tools']['duplicate'] = array();
        $form_config['form_id'] = $form_config['unique_id'].'-form';
        if($form_config['override_function']['enabled']){
            $methodName = $form_config['override_function']['function'];
            $return = call_user_func_array(array($this, $methodName), array($data));
            return $return;
        }



        $db_data = array();

        
        if($form_config['fetch_data']['enabled']){
            if($this->dbID != 0){ /// If data has already been loaded
                $db_data = $this->dbData;
            }
            else{
                $db_data = $this->loadFromDB($form_config['request_data']['id']);
            }

            $form_config['db_data'] = $db_data;            
            foreach($db_data as $temkDKey=>$tempDVal){
                $form['tools']['data'][''.$form_config['unique_id'].'-'.$temkDKey.''] = $tempDVal;
            }        
        }
		else if($form_config['has_data']){
			$form['db_data'] = $form_config['has_data'];
			//print_r($form_config['has_data']);
			$db_data = $form_config['has_data'];  
            foreach($db_data as $temkDKey=>$tempDVal){
                $form['tools']['data'][''.$form_config['unique_id'].'-'.$temkDKey.''] = $tempDVal;
            }			
		}

        
        $form_config_fields = $form_config['fields'];
        ///// IF CUSTOM FIELDS ARE DEFINED
        if($form_config['has_custom_fields']){
            $all_config['fields'] = $form_config['custom_fields'];
            $form_config['labels'] = array();
            $form_config['icons'] = array();            
        }
        else{
            $all_config = $this->getAllFields();
            $form_config['labels'] = $this->getLabels();
            $form_config['icons'] = $this->getIcons();            
        }
        $all_fields = $all_config['fields'];

        //print_r($form_config['fields']);
        ///// Create Hidden fields if any
        $form['hidden'] = '';
        $form['priorityJs'] = '';
        if($form_config['hidden']['enabled']){
            $form['hidden'] .= $form_config['hidden']['html'];
        }
		
		//print_r($form_config['request_data']);
        if(isset($form_config['request_data']['next_data'])){
			foreach($form_config['request_data']['next_data'] as $k=>$v){
			$form['hidden'] .= '<input type="hidden" name="next_data['.$k.']" id="'.$form_config['unique_id'].'-next_data-'.$k.'" value="'.$v.'">';
			}
		}
		
        $form['hidden'] .= '<input type="hidden" name="module" id="'.$form_config['unique_id'].'-module" value="'.$form_config['module'].'">';        
        $form['hidden'] .= '<input type="hidden" name="return_form_as" id="'.$form_config['unique_id'].'-return_as" value="'.$form_config['return_form_as'].'">';        
        $form['hidden'] .= '<input type="hidden" name="unique_id" id="'.$form_config['unique_id'].'-unique_id" value="'.$form_config['unique_id'].'">';
        $form['hidden'] .= '<input type="hidden" name="parent" id="'.$form_config['unique_id'].'-parent" value="'.$form_config['unique_id'].'">';        
        }
        foreach($form_config['fields'] as $field){
           
            $value = $all_fields[$field];
            ////// MERGE THE COMMON FIELD_ARRAY WITH THE SPECIFIED FIELD VALUES
            $element = array_merge($this->field_array, $value);
            $element['form_group_type'] = getDefault($value['form_group_type'],$form_config['form_group_type']);	
            $element['unique_class'] = ' __UNIQUE_ID__-'.$field.'';	
            $element['class'] .= ' __UNIQUE_ID__-'.$field.'';	
            $element['unique_id'] = $form_config['unique_id'];
            $element['module'] = $form_config['module'];
            $element['name_prefix'] = ($form_config['name_prefix']) ? $form_config['name_prefix'] : ''; 
            ///// OVERRIDE ATTRIBUTES IF SPECIFIED IN FORM_CONFIG OVERRIDE FIELDS
            if(isset($form_config['override_fields'][$field])){
                $overrides = $form_config['override_fields'][$field];
                $element = array_merge($element, $overrides);
            }    
            {//// PREPARE THE ARRAY FOR FETCHING FORM ELEMENT            
            $element['extra'] .= ' data-unique_id="'.$form_config['unique_id'].'" data-module="'.$form_config['module'].'" ';
            $element['extra'] .= isset($value['extra'])?$value['extra']:'';
			$element['extra'] .= ' data-field="'.$field.'" ';	
             //print_r($element);
            if(!$form_config['has_custom_fields']){
				$element['label'] = getDefault($form_config['labels'][$field],$element['label']);
                $element['icon'] = getDefault($form_config['icons'][$field],$element['icon']);
            }
            $element['class'] .= ' monitor-input';	
            $new_field = ''; ///RESET THE VARIABLE TO REMOVE ANY PREVIOUS VALUES            
            $element['name'] = ($form_config['name_prefix']) ? ''.$form_config['name_prefix'].'['.$field.']' :(isset($value['name']) ? replaceBetweenBraces($db_data,$value['name']) : ''.$form_config['unique_id'].'['.$field.']'); 			
            $element['id'] = $form_config['unique_id'].'-'.$field;						
            		
            //$has_conditional_required = false;
            $element['required'] = in_array($field,$form_config['required']) || isset($form_config['required'][$field]) ? true : false;
            
            if(isset($element['required'][$field]['conditional'])){
                print_r($field);
            }
            if($element['required']) $element['extra'] .= ' field-required';

            $element['validation'] = isset($form_config['validation'][$field]) ? $form_config['validation'][$field] : array();	
            if(isset($form_config['validation'][$field])) $element['extra'] .= ' field-validate'; 
            
            $element['allowed_duplicate'] = in_array($field,$form_config['duplicate']) ? false : true;
            if(!$element['allowed_duplicate']) $element['extra'] .= ' field-check-duplicate';
            
            //$tempJsElements .= str_replace('__ELEMENT_ID__',$element['id'],$element['js']);                 
            if(isset($db_data[$field])){
                if($element['type'] == 'checkbox'){
                   $element['checked'] = $db_data[$field] == $element['value']; 
                }
                else{
                    $element['value'] = $db_data[$field];
                }                
                
            }
            else if (isset($form_config['default_values'][$field])){
                $element['value'] = $form_config['default_values'][$field];                    
            }
            else{
                //$element['value'] = isset($value['value']) ? $value['value'] : '';		
            }

            }
            
            {//// CODEBEGIN
                if ($element['code_begin'] != ''){
                     $next_form .= replaceBetweenBraces($db_data,$element['code_begin']);	
                }
            }
          
            $new_field_temp = $this->createFormElement($field,$element);
            $new_field = $new_field_temp['field'];
            $new_field = str_replace('__ELEMENT_ID__',$element['id'],$new_field);
            $new_field = str_replace('__ELEMENT_CLASS__',$element['unique_class'],$new_field);
            $new_field = str_replace('__ELEMENT_VALUE__',$element['value'],$new_field);
/*             $new_field = str_replace('__UNIQUE_ID__',$element['parent'],$new_field);
            $new_field = str_replace('__MODULE__',$element['module'],$new_field);
            $new_field = str_replace('__ELEMENT_ID__',$element['id'],$new_field);
            $new_field = str_replace('__ELEMENT_VALUE__',$element['value'],$new_field);
            $new_field = str_replace('__ID__',$request_data['id'],$new_field); */
            if(isset($form_config['request_data']['id'])) $new_field = replace_conditional_html($element,$new_field);                
            if($element['value'] != '') {                
                $new_field = str_replace('__HIDDEN_IF_VALUE__',' hidden',$new_field); 
                $new_field = str_replace('__HIDDEN_IF_NO_VALUE__','',$new_field);                 
                           
            }
            else{
                $new_field = str_replace('__HIDDEN_IF_VALUE__','',$new_field); 
                $new_field = str_replace('__HIDDEN_IF_NO_VALUE__',' hidden',$new_field);   
            }
            
            //$new_field = str_replace('__NAME_PREFIX__',$element['name_prefix'],$new_field);
            $tempJsToolsTemp = $new_field_temp['js'];
            //$tempJsToolsTemp = str_replace('__UNIQUE_ID__',$element['parent'],$tempJsToolsTemp);
            //$tempJsToolsTemp = str_replace('__MODULE__',$element['module'],$tempJsToolsTemp);
            $tempJsToolsTemp = str_replace('__ELEMENT_ID__',$element['id'],$tempJsToolsTemp);
            $tempJsToolsTemp = str_replace('__ELEMENT_CLASS__',$element['unique_class'],$tempJsToolsTemp);
            if($element['value'] != '') $tempJsToolsTemp = str_replace('__ELEMENT_VALUE__',$element['value'],$tempJsToolsTemp);           
            //$tempJsToolsTemp = str_replace('__NAME_PREFIX__',$element['name_prefix'],$tempJsToolsTemp);
/*             if($element['type'] == 'image_crop'){
                print_r($tempTempF);    
            } */
            $tempJsTools .= $tempJsToolsTemp;
            $form['field_html'][$field]['from_group_only'] = $new_field; 

            
            {////Wrap The form field with col-md if specified in the width
                if($element['type'] != 'hidden'){ //// IF NOT HIDDEN ELEMENT WRAP IT
                    if(isset($element['width']) && $element['width'] != '' && $element['width'] != '0' && $element['width'] <= '12'){
                        $left_offset = ($element['left_offset'] > 0)?'col-md-offset-'.$element['left_offset'].'':'';
                        $right_offset = ($element['right_offset'] > 0)?'col-md-offset-right-'.$element['right_offset'].'':'';
                        
                        $form['field_html'][$field]['wrapped'] = $next_form .= '<div class="col-md-'.$element['width'].' '.$right_offset.' '.$left_offset.' form-common-element-wrapper" id="'.$element['id'].'-holder" style="">'.$new_field.'</div>';			
                    }
                    else{ ///IF HIDDEN ELEMENT NO NEED TO WRAP IT		
                        $form['field_html'][$field]['wrapped'] = $next_form .= '<div class="col-md-6 form-common-element-wrapper" id="'.$element['id'].'-holder">'.$new_field.'</div>';		
                    }                        
                }
                else{
                    $form['field_html'][$field]['wrapped'] = $next_form .= $new_field;
                }
            }
            {//// CODEEND
                if ($element['code_end'] != ''){
                    $next_form .= replaceBetweenBraces($db_data,$element['code_end']);
                    $form['field_html'][$field]['wrapped'] .= replaceBetweenBraces($db_data,$element['code_end']);
                    
                }
            }
            
            {///// TEMP TOOLS
            
            $form['tools']['icons'][$element['id']] = $element['icon'];		
            //print_r($f);
            
            if(isset($element['required']) && $element['required']){
                if(isset($element['required'][$field]['conditional'])){
                    //// prepend table name as the id of the conditional field is also changed as this form is being generated
                    $form['tools']['required'][$element['id']] = $element['required'][$field];
                }
                else{
                    $form['tools']['required'][$element['id']]['status'] = false;
                    $form['tools']['required'][$element['id']]['message'] = $element['label'].' is Required';                        
                }
            }
            if(isset($form_config['required'][$field]['conditional'])){
                $form_config['required'][$field]['conditional']['field'] = $form_config['unique_id'].'-'.$form_config['required'][$field]['conditional']['field'];
               $form['tools']['required'][$element['id']] = $form_config['required'][$field];  
            }            
            if(isset($element['allowed_duplicate']) && !$element['allowed_duplicate']){
                $form['tools']['duplicate'][$element['id']]['status'] = true;
                $form['tools']['duplicate'][$element['id']]['message'] = $element['label'].' is Required';
            }	


            if (isset($element['validation']) && array_filter($element['validation'])){
                if(isset($element['validation']['conditional'])){
                    //$form['tools']['validation'][$element['id']]['conditional'] = $form_config['validation'][$field];
					$form_config['validation'][$field]['conditional']['field'] = $form_config['unique_id'].'-'.$form_config['validation'][$field]['conditional']['field'];
                    $form['tools']['validation'][$element['id']] = $form_config['validation'][$field];
                }
                else{            
                    $form['tools']['validation'][$element['id']] = $form_config['validation'][$field];   
                }                     
            }
			
			if(isset($form_config['dependency'][$field])){
				$depTemp[$element['id']] = $form_config['dependency'][$field];
			}            
            if($element['type'] != 'code'){
                $form['fields'][$field] = $new_field;
                $form['field-types'][] = $element['type'];            
                $form['tools']['titles'][$element['id']] = 'Please Enter the '.$element['label'];                        
            }
        
            }                

        }
        
        $ajaxDepjs = '';        
        if(!empty(array_filter($this->ajax_dependency))){
            
            foreach($this->ajax_dependency as $ajField=>$depElem){
                
                $ajaxDepjs .= 
                "
                $(document).on('change','#$form_config[unique_id]-$ajField', function(){
                   getDependentFields('$form_config[module]',this); 
                });
                ";
                $form['tools']['ajax_dependency'][$form_config['unique_id'].'-'.$ajField] = $form_config['unique_id'].'-'.key($depElem);
            }            
        }

        $form['tools']['unique_id'] = $form_config['unique_id'];
        $form['tools']['module'] = $form_config['module'];
        $next_buttons = '';
        $common_title_stripped =preg_replace('/\s+/', '', $this->common_title);

        {//// Portlet Info

        $form['html_extra_start'] = isset($form_config['html_extra_start']) ? $form_config['html_extra_start'] : '';
        $form['portlet-title'] = $form_config['portlet_title'];
        $form['portlet-icon'] = $form_config['portlet_icon'];
        $form['form-id'] = $form_config['form_id'];
        $form['form_id'] = $form_config['form_id'];
        $form['submit-id'] = $form_config['submit_id'];
        $next_form .= isset($form_config['hidden-fields']) ? $form_config['hidden-fields'] : ''; 
			
        }

        $form['form'] = $next_form;  		
        {////Buttons
		$form['buttons'] = '';
        if($form_config['buttons']['enabled']){
			if(trim($form_config['buttons']['html']) != ''){ //// PRIORITY IS GIVEN TO BUTTON HTML
				$form['buttons'] = $form_config['buttons']['html'];
			}
			else if($form_config['buttons']['array']){ ////// IF NOT HTML THEN CREATE BUTTONS FROM ARRAY
				
				foreach($form_config['buttons']['array'] as $btn_id=>$btn){
					//print_r($btn);
					if(empty(array_filter($btn))) continue;
					$btn_actions = htmlspecialchars(json_encode($btn['actions'],false));
					$btn_next_actions = htmlspecialchars(json_encode($btn['next_actions'],false));
					$form['buttons'] .=
					'
					<a href="javascript:;" class="btn '.$btn['class'].'" '.$btn['extra'].' id="'.$form_config['unique_id'].'-btn'.$btn_id.'"  onClick="'.$btn['func'].'('.$btn_actions.',\''.$form_config['form_id'].'\',this,'.$btn_next_actions.')" data-unique_id="__UNIQUE_ID__" >'.$btn['title'].'</a>
					';
				}
			}
		}
        else{
            $form['buttons'] = 
            '           
            <button type="button" class="btn btn-outline green" id="'.$form_config['unique_id'].'-btnSave"  onClick="Save({\'form_type\':\''.$form_config['form_type'].'\',\'extended_action\':\'save\',\'module\':\''.$form_config['module'].'\'},\''.$form_config['form_id'].'\',\''.$form_config['unique_id'].'\')">Save</button>
            ';
        }
        }
        
/*         $depTemp = array();
        foreach($form_config['dependency'] as $depKeyTemp=>$depValTemp){
            $depTemp[$form_config['unique_id'].'-'.$depKeyTemp] = $depValTemp;
        } */
        $form['dependency'] = $depTemp;
        {//// Return Form As 
        $form['form'] .= $form_config['extra_html'];

        {///// GROUPED FIELDS
        if($form_config['group_fields']['enabled']) {
            foreach($form_config['group_fields']['groups'] as $group_type=>$group){
                if($group_type == 'modal'){
                    
                }
                else if($group_type == 'rows'){
                    
                }
                else if($group_type == 'popover'){
                    
                }
                else if($group_type == 'accordion'){
                    
                }
                else if($group_type == 'custom'){
                    
                }
            }
        }
        }
        
        if($form_config['return_form_as'] == 'modal'){
			$form['buttons'] = 
			'
			<a href="javascript:;" class="btn btn-outline red" id="'.$form_config['unique_id'].'-btnClose"  onClick="CloseBsModal(\''.$form_config['unique_id'].'-main-container\',this)" data-unique_id="'.$form_config['unique_id'].'">Cancel</a>
			'.$form['buttons'].'
			';

            $modal_html = $this->GetModalForm($form);
            $modal_width = getDefault($form_config['modal_width'],'70');
            $modal_wrapper = 
            '
            <div class="modal  modal-scroll draggable-modal animated zoomIn" data-in-animation="zoomIn" data-out-animation="zoomOut" id="'.$form_config['unique_id'].'-main-container" data-backdrop="static" data-keyboard="true" style="margin:0 auto;min-width:300px;width:'.$modal_width.'">
            '.$modal_html.'
            </div>            
            ';
            $form['html'] = $modal_wrapper;	
            $tempJsTools .= 
            "
            $('#$form_config[unique_id]-main-container').on('hidden.bs.modal', function () {
                $form_config[jsModalClose];                
                $('#$form_config[unique_id]-main-container').remove();
            });
                ";
            $form['priorityJs'] = 
            "
            $('body').append(value.html);
            toolsAll['$form_config[unique_id]'] = value.tools;
            ShowBsModal('$form_config[unique_id]-main-container');
          
            $('#$form_config[unique_id]-main-container').draggable({handle:'.modal-header'});    
            ";
        }
        else if($form_config['return_form_as'] == 'portlet'){               
            $form['html'] = $this->GetPortletForm($form);
		
            $encoded_tools = json_encode($form['tools']);
            $form['priorityJs'] .= "toolsAll['__UNIQUE_ID__'] = $encoded_tools;";
        }
        else if($form_config['return_form_as'] == 'rows'){

            $form['html'] = row($form['form']);

            $encoded_tools = json_encode($form['tools']);
            $form['priorityJs'] .= "toolsAll['__UNIQUE_ID__'] = $encoded_tools;";
        }
        else if($form_config['return_form_as'] == 'popover'){
            if(!$form_config['buttons']['enabled']){
                $form['buttons'] = 
                '          
                <button type="button" class="btn yellow-gold ladda-button" data-style="zoom-out" data-size="l" id="'.$form_config['unique_id'].'-btnSave"  onClick="CommonFunc2Btn({\'form_type\':\''.$form_config['form_type'].'\',\'extended_action\':\'save\',\'module\':\''.$form_config['module'].'\'},\''.$form_config['form_id'].'\',\''.$form_config['unique_id'].'\')" data-unique_id="'.$form_config['unique_id'].'"><i class="fa fa-search"></i>SEARCH</button> 
                ';       
            }
            $popover_template = 
            '
             <div class="popover popover-with-select2 popover-custom-theme" role="tooltip" style="width:60%;max-width:60%;top:50px">
                 <div class="arrow"></div>
                 <h3 class="popover-title ">Search '.$form_config['portlet_title'].'</h3>
                 <div class="popover-actions">
                    <a href="javascript:;" class="btn btn-circle btn-icon-only red" id="__UNIQUE_ID__-btnClose"><i class=" fa fa-close" style="font-size: 30px;"></i></a>
                 </div>                  
                 <div class="popover-content" id="'.$form_config['unique_id'].'-main-container">
                 
                 </div>
                 <div class="row text-center" id="'.$form_config['unique_id'].'-popover-result">
                 <h4 class="font-yellow-gold sub-title">'.$form_config['portlet_title'].'</h4>
                 </div>
              </div>         
            ';            
            if(isset($form_config['popover_template']) && $form_config['popover_template'] != ''){

                    $popover_template = str_replace('__UNIQUE_ID__',$form_config['unique_id'],$form_config['popover_template']);        
            }            
            
            $form['html'] = row($form['form']);
            $form['html'] .= '<div class="row text-center">'.$form['buttons'].'</div>';
            $temp_content = 
            '
            <form id="'.$form_config['form_id'].'" method="POST" onSubmit="return false;">
            '.$form['html'].'
            '.$form['hidden'].'
            </form>
            ';
            $form['priorityJs'] = 
            "
            toolsAll['$form_config[unique_id]'] = value.tools;
            if($(e).data('bs.popover'))$(e).popover('destroy');
            var pop_on = $(e);
            pop_on.popover({
                title: '<h4 class=\"font-yellow-gold sub-title\"><span class=\"fa fa-search text-center\" data-background-color=\"orange\"></span>  $form_config[portlet_title]</h4>',
                content : `{$temp_content}`,
                trigger:'click',
                html: true,
                container: 'body',
                placement: 'left',
                template: `{$popover_template}`,
            }).on('show.bs.popover', function(){ $(this).data('bs.popover').tip().css('top', '0px'); }); 
            pop_on.popover('show');
            //console.log($(e).data('bs.popover'));
            $('#$form_config[unique_id]-btnClose').on('click',function(){
                pop_on.popover('destroy');
            });
            ";            
        }  
        }

        $form['include_css'] = $this->include_css;
        $form['include_js'] = $this->include_js;
        $form['jsFunction'] = $form['priorityJs'];
        $form['jsFunction'] .= $ajaxDepjs;
        $form['jsFunction'] .= $tempJsTools;
        $form['jsFunction'] .= $tempJsElements;
        $form['jsFunction'] .= $form_config['jsFunction'];
        $form['jsFunction'] .= $this->getDependencyJs($form_config);        
        $form['jsFunction'] .= $form_config['jsFunctionLast'];	        
        $form['jsFunction'] .= "";	
        if(isset($form_config['request_data']['id'])) {
            $form['html'] = str_replace('__ID__',$form_config['request_data']['id'],$form['html']); 
            $form['form'] = str_replace('__ID__',$form_config['request_data']['id'],$form['form']); 
            $form['jsFunction'] = str_replace('__ID__',$form_config['request_data']['id'],$form['jsFunction']);  
        }       

        $form['form'] = str_replace('__UNIQUE_ID__',$form_config['unique_id'],$form['form']);
        $form['form'] = str_replace('__MODULE__',$form_config['module'],$form['form']);
        $form['form'] = str_replace('__NAME_PREFIX__',$form_config['name_prefix'],$form['form']);       
        $form['form'] = str_replace('__FORM_ID__',$form_config['form_id'],$form['form']);       

        $form['html'] = str_replace('__UNIQUE_ID__',$form_config['unique_id'],$form['html']);
        $form['html'] = str_replace('__MODULE__',$form_config['module'],$form['html']);
        $form['html'] = str_replace('__NAME_PREFIX__',$form_config['name_prefix'],$form['html']);
        $form['html'] = str_replace('__FORM_ID__',$form_config['form_id'],$form['html']);      

        $form['buttons'] = str_replace('__UNIQUE_ID__',$form_config['unique_id'],$form['buttons']);
        $form['buttons'] = str_replace('__MODULE__',$form_config['module'],$form['buttons']);
        $form['buttons'] = str_replace('__NAME_PREFIX__',$form_config['name_prefix'],$form['buttons']);
        $form['buttons'] = str_replace('__FORM_ID__',$form_config['form_id'],$form['buttons']);

        $form['jsFunction'] = str_replace('__UNIQUE_ID__',$form_config['unique_id'],$form['jsFunction']);
        $form['jsFunction'] = str_replace('__MODULE__',$form_config['module'],$form['jsFunction']);
        $form['jsFunction'] = str_replace('__ELEMENT_ID__',$form_config['name_prefix'],$form['jsFunction']);      
        $form['jsFunction'] = str_replace('__FORM_ID__',$form_config['form_id'],$form['jsFunction']);      

        $form['form'] .= $form_config['extra_unreplaced_html'];		
		$form['jsFunction'] = $unReplacedJs['high_priority'].$form['jsFunction'];
		$form['jsFunction'] .= $unReplacedJs['low_priority'];		
        return $form;  

    }

    
    public function getExtendedFormMultiple($request_data){

        $form_config = $this->setFormConfiguration($request_data);  
        $form_config['form_type'] = $request_data['form_type'];
        $form_config['request_data'] = $request_data;
        $form_config['form_id'] = $form_config['unique_id'].'-form';
		$form = $this->getMultipleForm($form_config);
		return $form;

    }

    public function getMultipleForm($form_config){
		$form_config['form_type'] = getDefault($form_config['form_type'],'');
		$debugVar = '';
        {//// Initial Config
        $tempform = '';
        $form_rows = ''; ////Main Variable
        $new_field = '';
        $fields = array();
        $new_form = array();
        $tempTools = array();
        $hiddenFields = array();
        $form = $form_config;
        $tempJsTools = '';
        $tempJsElements = '';
		$extraRepeatCustomJs = '';
		$extraCustomJs = '';
        $dependencyJs = '';		
        $unReplacedJs = array();		
        $unReplacedJs['high_priority'] = $unReplacedJs['mid_priority'] = $unReplacedJs['low_priority'] = '';		
        $form['repeat_tools']['repeat_js'] = ''; 		
        if($form_config['override_function']['enabled']){
            $methodName = $form_config['override_function']['function'];
            $return = call_user_func_array(array($this, $methodName), array($data));
            return $return;
        }


        $db_data_all = array();
        $db_data = array();
        if($form_config['fetch_data']['enabled']){
            
            if($this->dbForeignID != 0){ /// If data has already been loaded
                $db_data_all = $this->dbDataMany;
            }
            else{
                $db_data_all = $this->loadAllFromDB($request_data['id'],$request_data['foreign_key']);
            }

            $form_config['db_data_all'] = $db_data_all;  
            /// TODO save data in tools for validation etc    
            foreach($db_data as $temkDKey=>$tempDVal){
                $form['tools']['data'][''.$form_config['unique_id'].'-'.$temkDKey.''] = $tempDVal;
            }        
        }
		else if($form_config['has_data']){
			$form_config['db_data_all'] = $form_config['has_data']; 
			$db_data_all = $form_config['has_data']; 		
            foreach($db_data_all as $temkDKey=>$tempDVal){
                $form['tools']['data'][''.$form_config['unique_id'].'-'.$temkDKey.''] = $tempDVal;
            }			
		}
        


        ///// Create Hidden fields if any
        $form['hidden'] = '';
        $form['priorityJs'] = '';
        if($form_config['hidden']['enabled']){
            $form['hidden'] .= $form_config['hidden']['html'];
        }
        if(isset($form_config['request_data']['next_data'])){
			$form['hidden'] .= '<input type="hidden" name="next_data" id="'.$form_config['unique_id'].'-next_data" value="'.json_encode($form_config['request_data']['next_data']).'">';
		}
        $form['hidden'] .= '<input type="hidden" name="module" id="'.$form_config['unique_id'].'-module" value="'.$form_config['module'].'">';        
        $form['hidden'] .= '<input type="hidden" name="return_form_as" id="'.$form_config['unique_id'].'-return_as" value="'.$form_config['return_form_as'].'">';        
        $form['hidden'] .= '<input type="hidden" name="unique_id" id="'.$form_config['unique_id'].'-unique_id" value="'.$form_config['unique_id'].'">';
        $form['hidden'] .= '<input type="hidden" name="parent" id="'.$form_config['unique_id'].'-parent" value="'.$form_config['unique_id'].'">'; 
        if(isset($request_data['id'])) $form['hidden'] .= '<input type="hidden" name="foreign_id" id="'.$form_config['unique_id'].'-foreign_id" value="'.$request_data['id'].'">';        
        if(isset($request_data['foreign_key']))$form['hidden'] .= '<input type="hidden" name="foreign_key" id="'.$form_config['unique_id'].'-foreign_key" value="'.$request_data['foreign_key'].'">'; 

        /// We need a dummy form for javascript and it would have the id value
        $dummy_data = array();
        foreach($form_config['fields'] as $field){
            $dummy_data[$field] = '';
        }       
        $dummy_data[$this->config['id']] = 0;
        if(isset($request_data['foreign_key']) && isset($request_data['id'])) $dummy_data[$request_data['foreign_key']] = $request_data['id'];
        
        //// If there is no associated data then we need to show atleast on row in the form, assign the dummy data to db_data_all
        if(empty(array_filter($db_data_all))){
            $db_data_all[] = $dummy_data;
        }
        $db_data_count = sizeof($db_data_all);
        }
        $i = 0;
		
        foreach($db_data_all as $db_data){
            $i++;
            $next_form = '';
			$depTemp = array();
            $form['tools']['tools'][$i]['validation'] = array();
            $form['tools']['tools'][$i]['required'] = array();
            $form['tools']['tools'][$i]['duplicate'] = array();             
			$loop_unique_id = uniqid();
			$loop_unique_id .= rand(10,100);           
		   {
			$form_config['count_row'] = $i;
			$tempData['form_config'] = $form_config;
			$tempData['db_data'] = $db_data;

			$loopedForm = $this->loopThroughFieldsCommon($tempData);
			$loopedFormStr = json_encode($loopedForm);
			$loopedFormStr = str_replace('__MODULE__',$form_config['module'],$loopedFormStr);
			$loopedFormStr = str_replace('__NAME_PREFIX__',$form_config['name_prefix'],$loopedFormStr);
			$loopedFormStr = str_replace('__ROW_COUNT__',$i,$loopedFormStr);
			$loopedFormStr = str_replace('__UNIQUE_ID__',$form_config['unique_id'],$loopedFormStr);
			$loopedFormStr = str_replace('__LOOP_UNIQUE_ID__',$loop_unique_id,$loopedFormStr);
			$loopedForm = json_decode($loopedFormStr, true);
			$temp_str_repl = $next_form = $loopedForm['form'];
			$tempJsTools .= $loopedForm['jsFunction'];
			
			$tempJsTools .= $loopedForm['elementJs'];
			$form['tools']['tools'][$i]['validation'] = $loopedForm['tools']['validation'];
			$form['tools']['tools'][$i]['required'] = $loopedForm['tools']['required'];
			$form['tools']['tools'][$i]['duplicate'] = $loopedForm['tools']['duplicate'];
			$form['tools']['tools'][$i]['titles'] = $loopedForm['tools']['titles'];
			$form['tools']['tools'][$i]['icons'] = $loopedForm['tools']['icons'];
		   }
			
			///// CHILD HTML AND JAVASCRIPT
            if($form_config['nested_form'] == 'has_child' && isset($form_config['child_config'])){
			///// CALL THE FUNCTION AGAIN IF HAS CHILD AND REPLACE THE REQUIRED THINGS AND APPEND HTML AND JAVASCRIPT TO THE CURRENT TOOLS ETC.

				//$tertiary_fields = $form_config['child_config'];
				$temp_child_li = '';
				$temp_child_tab_pane = '';
				$active = 'active';
				$i2 = 1;	
				
				foreach($form_config['child_config'] as $keyChild=>$tertiary_fields){
					//$unique_id = time();
					$unique_id = uniqid();
					$unique_id .= rand(10,100);					
					if($i == 1){
						$form['tools']['child_modules'][] = $tertiary_fields['module'];
						$form['repeat_tools']['child_modules'][] = $tertiary_fields['module'];
					}							

					$tertiary_fields['has_data'] = false;
					$tertiary_fields['form_type'] = $form_config['form_type'];
					if(isset($tertiary_fields['sql'])){
						$sql_tertiary = $tertiary_fields['sql'];
						$sql_tertiary = str_replace('__CURRENT_ID_VALUE__',$db_data['id'],$sql_tertiary);
						$query_tertiary = mysqli_query($this->dbConn,$sql_tertiary);	
						$row_tertiary = mysqli_fetch_all($query_tertiary,MYSQLI_ASSOC);	
						$tertiary_fields['has_data'] = $row_tertiary;				
					}				

					
					$tertiary_fields['unique_id'] = $form_config['unique_id'].'_'.$i.'_'.$tertiary_fields['module'];
					//$tertiary_fields['name_prefix'] = $form_config['unique_id'].'_'.$i.'_'.$tertiary_fields['module'];
					
					//// If first parent, then 
					$tertiary_fields['name_prefix'] = $form_config['name_prefix'].'[existing]['.$loop_unique_id.']'.'['.$tertiary_fields['module'].']';
					
					$tertiary_fields['fetch_data'] = array('enabled'=>false); 	
					$tertForm = $this->getMultipleForm($tertiary_fields); 
					
					if(sizeof($form_config['child_config']) > 1){
						if($i2 != 1) $active = '';
						$temp_child_li .= 
						'
						<li style="" class="wizard-li '.$active.'" id="li_'.$tertiary_fields['unique_id'].'_'.$keyChild.'"><a href="#tab_pane_'.$tertiary_fields['unique_id'].'_'.$i.'_'.$i2.'" data-toggle="tab" aria-expanded="true">'.$tertiary_fields['portlet_title'].'</a></li>   
						';	
						$temp_child_tab_pane .= 
						'
						<div class="tab-pane '.$active.' " id="tab_pane_'.$tertiary_fields['unique_id'].'_'.$i.'_'.$i2.'">	
							'.$tertForm['html'].'
						</div>   
						';						
					}

					//$form_config['child_html'] = $tertForm['html'];
					//$form_config['child_tools'] = $tertForm['tools'];  
					$unReplacedJs['low_priority'] .= $tertForm['jsFunction']; 	
					if($form_config['module'] == 'managecalculation'){
						//print_r($tertiary_fields['module']);
					}
				$i2++;
				}

				if(sizeof($form_config['child_config']) > 1){		/// If more than one type of child show tabs to save space	
/* 					$temp_child_html = 
					'
							<div class="tabbable-custom nav-justified " style="margin-top:20px;">
								<ul class="nav nav-tabs nav-justified">
									  '.$temp_child_li.'                         
								</ul>

								<div class="tab-content">
									'.$temp_child_tab_pane.'	
								</div>						
							</div>						
					';	 */
					$temp_child_html = 
					'
						<div class="card wizard-container" >
							<div class=" wizard-card creative-wizard-tab" data-color="blue" id="__UNIQUE_ID___'.$i.'-wizard-card" style="margin-left:5%;margin-right:5%;">
							<div class="wizard-navigation">
								<ul class="nav nav-pills nav-wizard">
									  '.$temp_child_li.'                         
								</ul>
							</div>

								<div class="tab-content">
									'.$temp_child_tab_pane.'	
								</div>						
							</div>						
						</div>						
					';
					$extraCustomJs .= "init_creative_wizard($('#$form_config[unique_id]_$i-wizard-card'));";
										
					//$extraRepeatCustomJs .= "init_creative_wizard($('#__UNIQUE_ID___$i-wizard-card'));";					
				}
				else{
					$temp_child_html = $tertForm['html'];
				}	
				
				$form_config['child_html'] = $temp_child_html;
            }
            

			if($form_config['nested_form'] == 'has_child' && isset($form_config['child_html'])){
				$temp_str_repl = str_replace('__CHILD_HTML__',$form_config['child_html'],$temp_str_repl); 
			} 
			
            if(isset($db_data[$this->config['id']])) $temp_str_repl = str_replace('__DB_ID__',$db_data[$this->config['id']],$temp_str_repl); 

			$form_rows .= $temp_str_repl;   

        }
        
        
        {//// PORTLET/TOOLS
        $next_buttons = '';
        $form_rows .= isset($form_config['hidden-fields']) ? $form_config['hidden-fields'] : '';    
        
/*         $form_rows = 
        '
        <div class="row '.$form_config['module'].'_repeat_holder" >
        '.$form_rows.'
        </div>
        '; */
		
        $form['form'] = 
        '
        <div class="row " id="'.$form_config['unique_id'].'-repeat-holder">
        '.$form_rows.'
        </div>
        '; 
        /// if nested form dont wrap it in anything, eg if its li in ul, wrapping will cause problrms
        if($form_config['nested_form']) $form['form'] = $form_rows; 
        
        
        $form['tools']['form_depth'] = $form_config['form_depth'];
        $form['tools']['name_prefix'] = $form_config['name_prefix'];
        $form['tools']['is_multiple_form'] = true;
        $form['tools']['rows_count'] = $i; //// Excluding the demo row, least value will be 1, it is the actual number of rows
        $form['tools']['rows_count_const'] = $i; //// The value wont change in javascript, we need for something
        $form['tools']['unique_id'] = $form_config['unique_id'];
        $form['tools']['module'] = $form_config['module'];
        $form['html_extra_start'] = isset($form_config['html_extra_start']) ? $form_config['html_extra_start'] : '';
        $form['portlet-title'] = $form_config['portlet_title'];
        $form['portlet-icon'] = $form_config['portlet_icon'];
        $form['form-id'] = $form_config['form_id'];
        $form['form_id'] = $form_config['form_id'];
        $form['submit-id'] = $form_config['submit_id'];
        if($form_config['nested_form'] == 'has_child') {
            $form['tools']['nested_form'] = $form_config['nested_form'];            
            //$form['tools']['child_tools'] = $form_config['child_tools'];            
        }

		
		///// NEW WAY OF REPEAT TOOLS AND FORMS
		$tempData['form_config'] = $form_config;
		$tempData['db_data'] = $dummy_data;
		$tempData['is_fake'] = true;
		$loopedForm = $this->loopThroughFieldsCommon($tempData);
		$loopedFormStrRepeat = json_encode($loopedForm);
		$loopedFormStrRepeat = str_replace('__MODULE__',$form_config['module'],$loopedFormStrRepeat);
		$loopedFormStrRepeat = str_replace('__NAME_PREFIX__',$form_config['name_prefix'],$loopedFormStrRepeat);

		$loopedFormRepeat = json_decode($loopedFormStrRepeat,true);		
        $form['repeat_tools']['name_prefix'] = $form_config['name_prefix'];		
        $form['repeat_tools']['form_depth'] = $form_config['form_depth'];		
        $form['repeat_tools']['unique_in_level'] = $form_config['unique_in_level'];		
        $form['repeat_tools']['portlet_title'] = $form_config['portlet_title'];		
        $form['repeat_tools']['min_multiple_forms'] = $form_config['min_multiple_forms'];		
        $form['repeat_tools']['max_multiple_forms'] = $form_config['max_multiple_forms'];		
        $form['repeat_tools']['nested_form'] = $form_config['nested_form'];		
        $form['repeat_tools']['repeat_form'] = $loopedFormRepeat['form'];  
		$form['repeat_tools']['repeat_js'] = $loopedFormRepeat['jsFunction'];
		$form['repeat_tools']['repeat_js'] .= $loopedFormRepeat['elementJs'];		
		$form['repeat_tools']['repeat_js'] .= $extraRepeatCustomJs;		
        $form['repeat_tools']['custom_wrapper_begin'] = $form_config['custom_wrapper_begin'];   
        $form['repeat_tools']['custom_wrapper_end'] = $form_config['custom_wrapper_end'];   
        $form['repeat_tools']['tools'] = $loopedFormRepeat['tools']; 
		$tempJsToolsRep = $loopedFormRepeat['jsFunction'];
		$tempJsToolsRep .= $loopedFormRepeat['elementJs'];
		
		$encoded_repeat_tools = json_encode($form['repeat_tools']);
		$unReplacedJs['high_priority'] .= "repeatTools['$form_config[module]'] = $encoded_repeat_tools;";			
        }
        
        {$add_another_btn = 
        '
        <a href="javascript:;" class="btn btn-outline green" id="'.$form_config['unique_id'].'btnAddAnother">Add Aother</a> 
        ';
        ///// On Delete BUTTON click and on ADD ANOTHER CLICK
        $addAnotherJs = 
        "
        var dataTemp = {};
        dataTemp['unique_id'] = '$form_config[unique_id]';
        dataTemp['module'] = '$form_config[module]';
        anotherBtnClickRepeatForm(dataTemp);
        deleteBtnClickRepeatForm(dataTemp);
        ";
		//// {{TEST}} : Originally not here, trying it so that we dont have to do much in javascript in terms of creating on click tools
		//$form['tools']['repeat_js'] .= $addAnotherJs;    
        }
        {//// BUTTON CONFIG
		$form['buttons'] = '';
        if($form_config['buttons']['enabled']){
            $form['buttons'] = 
            '
            '.$add_another_btn.'
            '.$form_config['buttons']['html'].'
            ';
        }
        else{
            $form['buttons'] = 
            ' 
            '.$add_another_btn.'
            <a href="javascript:;" class="btn btn-outline red" id="'.$form_config['module'].'-btnClose"  onClick="CloseBsModal(\''.$form_config['unique_id'].'-modal\',this)" >Cancel</a>             
            <button type="button" class="btn btn-outline green" id="'.$form_config['module'].'-btnSave"  onClick="Save({\'form_type\':\''.$form_config['form_type'].'\',\'extended_action\':\'save\',\'module\':\''.$form_config['module'].'\'},\''.$form_config['form_id'].'\',\''.$form_config['unique_id'].'\')">Save</button>  
            ';
        }
        }

        //$form['dependency'] = $depTemp;      
        $form['form'] .= $form_config['extra_html'];

 		
        if($form_config['return_form_as'] == 'modal'){
            if(!$form_config['buttons']['enabled']){
                $form['buttons'] = 
                '
                '.$add_another_btn.'
                <a href="javascript:;" class="btn btn-outline red" id="'.$form_config['module'].'-btnClose"  onClick="CloseBsModal(\''.$form_config['unique_id'].'-modal\',this)" >Cancel</a>             
                <button type="button" class="btn btn-outline green" id="'.$form_config['module'].'-btnSave"  onClick="Save({\'form_type\':\''.$form_config['form_type'].'\',\'extended_action\':\'save\',\'module\':\''.$form_config['module'].'\'},\''.$form_config['form_id'].'\',\''.$form_config['unique_id'].'\')">Save</button> 
                ';            
            }
            $modal_html = $this->GetModalForm($form);
            $modal_wrapper = 
            '
            <div class="modal container modal-scroll draggable-modal animated zoomIn" data-in-animation="zoomIn" data-out-animation="zoomOut" id="'.$form_config['unique_id'].'-modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
            '.$modal_html.'
            </div>            
            ';
            $form['html'] = $modal_wrapper;	
            $tempJsTools .= 
            "
            $('#$form_config[unique_id]-modal').on('hidden.bs.modal', function () {
                $form_config[jsModalClose];                
                $('#$form_config[unique_id]-modal').remove();
            });             
            ";
            $form['priorityJs'] = 
            "
            $('body').append(value.html);
            ShowBsModal('$form_config[unique_id]-modal');
            $('#$form_config[unique_id]-modal').draggable({handle:'.modal-header'});    
            ";	
        }
        else if($form_config['return_form_as'] == 'portlet'){
             if(!$form_config['buttons']['enabled']){
                $form['buttons'] = 
                '
                '.$add_another_btn.'             
                <button type="button" class="btn btn-outline  yellow-gold" id="'.$form_config['module'].'-btnSave"  onClick="Save({\'form_type\':\''.$form_config['form_type'].'\',\'extended_action\':\'save\',\'module\':\''.$form_config['module'].'\'},\''.$form_config['form_id'].'\',\''.$form_config['unique_id'].'\')">Save</button> 
                ';      
             }                
            $form['html'] = $this->GetPortletForm($form);	
            $encoded_tools = json_encode($form['tools']);
            $form['priorityJs'] .= "toolsAll['$form_config[unique_id]'] = $encoded_tools;"; 
        } 
        else if($form_config['return_form_as'] == 'rows'){
            if(!$form_config['buttons']['enabled']){
                $form['buttons'] = 
                '   
                 '.$add_another_btn.'       
                <button type="button" class="btn  yellow-gold" id="'.$form_config['module'].'-btnSave"  onClick="Save({\'form_type\':\''.$form_config['form_type'].'\',\'extended_action\':\'save\',\'module\':\''.$form_config['module'].'\'},\''.$form_config['form_id'].'\',\''.$form_config['unique_id'].'\')">Save</button> 
                '; 
               
            }                
            $form['html'] = 
            '
            <div class=" col-md-12">
            '.($form['form']) .'           
            </div>
            <div class=" col-md-12">
            '.($form['buttons']) .'           
            </div>
            ';
            /// if nested form dont wrap it in anything, eg if its li in ul, wrapping will cause problrms            
            if($form_config['nested_form']) $form['html'] = $form['form'];        		
            $encoded_tools = json_encode($form['tools']);
            $form['priorityJs'] .= "toolsAll['$form_config[unique_id]'] = $encoded_tools;";       
        }
		else if($form_config['return_form_as'] == 'custom'){ 		
            $form['html'] = 
            '
			'.($form_config['custom_wrapper_begin']) .'  
				'.$form['form'].'           
            '.($form_config['custom_wrapper_end']) .'  
            ';   
        }  
  
		$encoded_tools = json_encode($form['tools']);
		$form['priorityJs'] .= "toolsAll['$form_config[unique_id]'] = $encoded_tools;";  
       
		{//// JSFUNCTION AND STR REPLACE
        $form['jsFunction'] = $form['priorityJs'];
        //$form['jsFunction'] .= $ajaxDepjs;
        $form['jsFunction'] .= $tempJsTools;
        $form['jsFunction'] .= $tempJsElements;
        $form['jsFunction'] .= $form_config['jsFunction'];
        $form['jsFunction'] .= $dependencyJs;
        $form['jsFunction'] .= $addAnotherJs; 
        $form['jsFunction'] .= $extraCustomJs;       
        $form['jsFunction'] .= $form_config['jsFunctionLast'];
        if($form_config['form_type'] == 'new-event') $form['jsFunction'] .= "";	
        if(isset($form_config['request_data']['id'])) {
            $form['html'] = str_replace('__ID__',$form_config['request_data']['id'],$form['html']); 
            $form['form'] = str_replace('__ID__',$form_config['request_data']['id'],$form['form']); 
            $form['jsFunction'] = str_replace('__ID__',$form_config['request_data']['id'],$form['jsFunction']);  
        }
      
        $form['form'] = str_replace('__FORM_ID__',$form_config['form_id'],$form['form']);         
        $form['html'] = str_replace('__FORM_ID__',$form_config['form_id'],$form['html']);    
        $form['html'] = str_replace('__UNIQUE_ID__',$form_config['unique_id'],$form['html']);    
        $form['form'] = str_replace('__UNIQUE_ID__',$form_config['unique_id'],$form['form']);    
        $form['jsFunction'] = str_replace('__FORM_ID__',$form_config['form_id'],$form['jsFunction']);         
        //$form['jsFunction'] = str_replace('__UNIQUE_ID__',$form_config['unique_id'],$form['jsFunction']);         
		}

        $form['form'] .= $form_config['extra_unreplaced_html'];	
		$form['jsFunction'] = $unReplacedJs['high_priority'].$form['jsFunction'];
		$form['jsFunction'] .= $unReplacedJs['low_priority'];
		

        return $form;  
	}

	
    public function getDependencyJs($form_config){
        if(empty(array_filter($form_config['dependency']))){
            return '';
        }
        $arr['jsFunction'] = 
        "
        var currentSelectedDependency = false;
        ";
        
        $i = 0;
        $dependency_fields = $form_config['dependency'];
        $depTemp = array();
        foreach($dependency_fields as $depKeyTemp=>$depValTemp){
            $depTemp[$form_config['id_prefix'].'-'.$depKeyTemp] = $depValTemp;
        }
              
        foreach($dependency_fields as $depKey=>$depValue){
            $depTempJson = json_encode($depTemp[$form_config['id_prefix'].'-'.$depKey]);  
            //print_r($depValue);
            //print_r($depTemp);
            $i++;
            $depKey = $form_config['unique_id'].'-'.$depKey;
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
                        var data = [];
                        data['prevSelected'] = prevSelected;
                        data['currentSelected$i'] = currentSelected$i;
                        data['selected'] = selected;
                        data['dep'] = dep;
                        if(prevSelected !== false && dep.hasOwnProperty(prevSelected)){
                            if (dep[prevSelected].hasOwnProperty('hide')) {
                                jQuery.each(dep[prevSelected].hide, function(i, elem){
                                   $('#${form_config['unique_id']}-'+elem+'').removeClass('hidden');
                                   $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').removeClass('animated'+delay+' zoomOut hidden');
                                   $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('zoomIn');
                                });
                                
                                //// RUN THE LOOP AGAIN TO TRIGGER CHANGE IF ANY FOR THE ELEMENT
                                //// RUNNING LOOP SEPERATELY IS IMPORTNT SO THAT IT DOESNT CONFLICT WITH THE ELEMENTS OF THE ABOVE LOOP
                                jQuery.each(dep[prevSelected].hide, function(i, elem){
                                   
                                   $('#${form_config['unique_id']}-'+elem+'').trigger('change');
                                   $('#${form_config['unique_id']}-'+elem+'').addClass('edited');
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
                                            $('#${form_config['unique_id']}-'+elem+'').addClass('hidden');
                                           $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('animated'+delay+' zoomOut');
                                           window.setTimeout(function() { 
                                                $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('hidden');
                                           }, delay);
                                        });                                    
                                    }
                                } 
                                else{
                                    jQuery.each(dep[selected].hide, function(i, elem){
                                        console.log(elem);
                                        console.log($('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper'));
                                        $('#${form_config['unique_id']}-'+elem+'').addClass('hidden');
                                       $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('animated'+delay+' zoomOut');
                                       window.setTimeout(function() { 
                                            $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('hidden');
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
                                            $('#${form_config['unique_id']}-'+elem+'').addClass('hidden');
                                           $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('animated'+delay+' zoomOut');
                                           window.setTimeout(function() { 
                                                $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('hidden');
                                           }, delay);
                                        });                                    
                                    }
                                } 
                                else{
                                    jQuery.each(depTemp[selected].hide, function(i, elem){
                                        $('#${form_config['unique_id']}-'+elem+'').addClass('hidden');
                                       $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('animated'+delay+' zoomOut');
                                       window.setTimeout(function() { 
                                            $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('hidden');
                                       }, delay);
                                    });                              
                                }                             
                            
                            
                            

                        }
                    }      
                    ";  
                    
        }

        return $arr['jsFunction'];
    }
	
    public function getDependencyJs3($form_config){
        if(empty(array_filter($form_config['dependency']))){
            return '';
        }
        $arr['jsFunction'] = 
        "
        var currentSelectedDependency = false;
        ";
        
        $i = 0;
        $dependency_fields = $form_config['dependency'];
        $depTemp = array();
        foreach($dependency_fields as $depKeyTemp=>$depValTemp){
            $depTemp[$form_config['id_prefix'].'-'.$depKeyTemp] = $depValTemp;
        }
              
        foreach($dependency_fields as $depKey=>$depValue){
            $depTempJson = json_encode($depTemp[$form_config['id_prefix'].'-'.$depKey]);  
            //print_r($depValue);
            //print_r($depTemp);
            $i++;
            $depKey = $form_config['unique_id'].'-'.$depKey;
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
                        var data = [];
                        data['prevSelected'] = prevSelected;
                        data['currentSelected$i'] = currentSelected$i;
                        data['selected'] = selected;
                        data['dep'] = dep;
                        if(prevSelected !== false && dep.hasOwnProperty(prevSelected)){
                            if (dep[prevSelected].hasOwnProperty('hide')) {
                                jQuery.each(dep[prevSelected].hide, function(i, elem){
                                   $('#${form_config['unique_id']}-'+elem+'').removeClass('hidden');
                                   $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').removeClass('animated'+delay+' zoomOut hidden');
                                   $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('zoomIn');
                                });
                                
                                //// RUN THE LOOP AGAIN TO TRIGGER CHANGE IF ANY FOR THE ELEMENT
                                //// RUNNING LOOP SEPERATELY IS IMPORTNT SO THAT IT DOESNT CONFLICT WITH THE ELEMENTS OF THE ABOVE LOOP
                                jQuery.each(dep[prevSelected].hide, function(i, elem){
                                   
                                   $('#${form_config['unique_id']}-'+elem+'').trigger('change');
                                   $('#${form_config['unique_id']}-'+elem+'').addClass('edited');
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
                                            $('#${form_config['unique_id']}-'+elem+'').addClass('hidden');
                                           $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('animated'+delay+' zoomOut');
                                           window.setTimeout(function() { 
                                                $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('hidden');
                                           }, delay);
                                        });                                    
                                    }
                                } 
                                else{
                                    jQuery.each(dep[selected].hide, function(i, elem){
                                        console.log(elem);
                                        console.log($('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper'));
                                        $('#${form_config['unique_id']}-'+elem+'').addClass('hidden');
                                       $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('animated'+delay+' zoomOut');
                                       window.setTimeout(function() { 
                                            $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('hidden');
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
                                            $('#${form_config['unique_id']}-'+elem+'').addClass('hidden');
                                           $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('animated'+delay+' zoomOut');
                                           window.setTimeout(function() { 
                                                $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('hidden');
                                           }, delay);
                                        });                                    
                                    }
                                } 
                                else{
                                    jQuery.each(depTemp[selected].hide, function(i, elem){
                                        $('#${form_config['unique_id']}-'+elem+'').addClass('hidden');
                                       $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('animated'+delay+' zoomOut');
                                       window.setTimeout(function() { 
                                            $('#${form_config['unique_id']}-'+elem+'').closest('.form-common-element-wrapper').addClass('hidden');
                                       }, delay);
                                    });                              
                                }                             
                            
                            
                            

                        }
                    }      
                    ";  
                    
        }

        return $arr['jsFunction'];
    }
          
    public function getDependencyJs2($form_config){
        if(empty(array_filter($form_config['dependency']))){
			$arr['jsFunction'] = "";
            return $arr['jsFunction'];
        }
        $arr['jsFunction'] = '';
        $i = 0;
        $dependency_fields = $form_config['dependency'];
            
        foreach($dependency_fields as $depKey=>$depValue){
            $depTempJson = json_encode($depValue);  
            $i++;
            
            if($form_config['is_multiple_form']){
                $depKey = '__UNIQUE_ID___'.$depKey.'___ROW_COUNT__';
            }
            else{
                $depKey = '__UNIQUE_ID__-'.$depKey;
            }
           
                $arr['jsFunction'] .= 
                "
					//console.log('$depKey');
                    var currentSelected__UNIQUE_ID____ROW_COUNT__ = false;
                    $(document).on('change','#$depKey', function(){
                        var dep = JSON.parse('$depTempJson');
                        var prevSelected = currentSelected__UNIQUE_ID____ROW_COUNT__;
                        var selected = $('#$depKey').val(); 
                        currentSelected__UNIQUE_ID____ROW_COUNT__i = selected;
                        var delay = 500;
                        var data = [];
                        data['unique_id'] = '__UNIQUE_ID__';
                        data['prevSelected'] = prevSelected;
                        data['currentSelected__UNIQUE_ID____ROW_COUNT__'] = currentSelected__UNIQUE_ID____ROW_COUNT__;
                        data['this'] = $(this);
                        data['selected'] = selected;
                        data['dep'] = dep;
                        data['row_count'] = '__ROW_COUNT__';
                        manageDependentFields(data);

                    });
            

                    var depTemp = JSON.parse('$depTempJson');
                    var selected = $('#$depKey').val();
                    if(selected != ''){
                       currentSelected__UNIQUE_ID____ROW_COUNT__ = selected; 
                    }                    
                    var delay = 10;
                    var data = [];
                    data['prevSelected'] = '';
                    data['unique_id'] = '__UNIQUE_ID__';
                    data['currentSelected__UNIQUE_ID____ROW_COUNT__'] = currentSelected__UNIQUE_ID____ROW_COUNT__;
                    data['this'] = $('#$depKey');
                    data['selected'] = selected;
                    data['dep'] = depTemp;
                    data['row_count'] = '__ROW_COUNT__';
                    manageDependentFields(data);      
                    ";  
                    
        }

        return $arr['jsFunction'];
    }

    public function getAjaxDependentFields($data){
		$fieldId = $data['field'];
		$value = $data['value'];
		$elemId = $data['elemId'];
		$custom_field = isset($data['custom_field'])?$data['custom_field']:false;
        if(!$custom_field){
            $arrTemp = explode('-',$fieldId);
            $field = end($arrTemp); 
        }
        else{
            $field = $fieldId;
        }

        $return = array();
        $return['jsFunction'] = "";
        foreach($this->ajax_dependency[$field] as $dep=>$depCode){
            //// Js Function is before the evald code so that if we want to override jsfunction we can do it in the evald code
            $return['jsFunction'] .= 
            "
				$('#$elemId').empty();
				/// Create a Blank Option
				var opt = document.createElement('option');
				opt.value = '';
				opt.innerHTML = 'Please Choose an Option';
				$('#$elemId').append(opt);
				$('#$elemId').addClass('edited');
				$('#$elemId').val('');
				$.each(value.options, function(key,value) {
					var opt = document.createElement('option');
					opt.value = key;
					opt.innerHTML = value;
					$('#$elemId').append(opt);
				});
				if($('#$elemId').hasClass('bs-select')){//// IF BOOTSTRAP SELECT
					$('#$elemId').selectpicker('refresh');
				}
            ";            
            eval($depCode['code']);
            $return['options'] = $element['options'];
            
        }
        return $return;  

    }

    

    public function loopThroughFieldsCommon($data){
		$form_config = $data['form_config'];
		$db_data = $data['db_data'];
        $next_form = '';
        $tempJsElements = '';
        $form_config_fields = $form_config['fields'];
        ///// IF CUSTOM FIELDS ARE DEFINED
        if($form_config['has_custom_fields']){
            $all_config['fields'] = $form_config['custom_fields'];
            $form_config['labels'] = array();
            $form_config['icons'] = array();            
        }
        else{
            $all_config = $this->getAllFields();
            $form_config['labels'] = $this->getLabels();
            $form_config['icons'] = $this->getIcons();            
        }
        $all_fields = $all_config['fields'];
		$form['jsFunction'] = '';
		$form['elementJs'] = '';
		$form['tools']['required'] = array();
		$form['tools']['duplicate'] = array();
		$form['tools']['validation'] = array();		
		$form['tools']['icons'] = array();		
		foreach($form_config['fields'] as $field){

			$value = $all_fields[$field];
			////// MERGE THE COMMON FIELD_ARRAY WITH THE SPECIFIED FIELD VALUES
			$element = array_merge($this->field_array, $value);
			
			$element['unique_class'] = '__UNIQUE_ID__-'.$field.'';	
			$element['unique_class'] = '';	
			//$element['class'] .= ' __UNIQUE_ID__-'.$field.'';	
			$element['class'] .= ' field-'.$field.'';	
			$element['class'] .= ' '.$form_config['module'].'_'.$field.'';	
			if($form_config['small_input']) $element['class'] .= ' input-sm';	
			$element['unique_id'] = $form_config['unique_id'];
			$element['module'] = $form_config['module'];
			$element['name_prefix'] = ($form_config['name_prefix']) ? $form_config['name_prefix'] : '';                
			///// OVERRIDE ATTRIBUTES IF SPECIFIED IN FORM_CONFIG OVERRIDE FIELDS
			if(isset($form_config['override_fields'][$field])){
				$overrides = $form_config['override_fields'][$field];
				$element = array_merge($element, $overrides);
			}   

			
			{//// PREPARE THE ARRAY FOR FETCHING FORM ELEMENT            
			$element['extra'] .= ' data-field="'.$field.'" ';
			$element['extra'] .= ' data-unique_id="__UNIQUE_ID__" data-module="__MODULE__" ';
			$element['extra'] .= isset($value['extra'])?$value['extra']:'';
			$element['label'] = getDefault($form_config['labels'][$field],$element['label']);;
			$element['icon'] = getDefault($form_config['icons'][$field],$element['icon']);  
			
			//// replcaed all instances of $form_config['unique_id'] to __PARENT and $i to __ROW_COUNT__
			$element['extra'] .= ' data-tools-index="__ROW_COUNT__"';
			
			if($form_config['form_type'] == 'new'){
				$element['name'] = ($form_config['name_prefix']) ? '__NAME_PREFIX__[new][__LOOP_UNIQUE_ID__][form_data]['.$field.']' : '__LOOP_UNIQUE_ID__[form_data]['.$field.']'; 				
			}
			else{

				$element['name'] = ($form_config['name_prefix']) ? '__NAME_PREFIX__[existing][__LOOP_UNIQUE_ID__][form_data]['.$field.']' : '__LOOP_UNIQUE_ID__[form_data]['.$field.']'; 				
			}

			if(isset($data['is_fake']) && $data['is_fake']){
				$element['name'] = '__JS_NAME_PREFIX__[form_data]['.$field.']';
			}
	
			$element['id'] = '__UNIQUE_ID___'.$field.'___ROW_COUNT__';    
			$element['class'] .= ' monitor-input'; 
			$new_field = ''; ///RESET THE VARIABLE TO REMOVE ANY PREVIOUS VALUES						
					
				
			$element['required'] = in_array($field,$form_config['required']) || isset($form_config['required'][$field]) ? true : false;
			$element['class'] .= in_array($field,$form_config['unique_in_level']) ? ' field_unique_in_level' : '';
			if($element['required']) $element['extra'] .= ' field-required';
		   
			$element['validation'] = isset($form_config['validation'][$field]) ? $form_config['validation'][$field] : array();	
			if(isset($form_config['validation'][$field])) $element['extra'] .= ' field-validate'; 
			
			$element['allowed_duplicate'] = in_array($field,$form_config['duplicate']) ? false : true;
			if(!$element['allowed_duplicate']) $element['extra'] .= ' field-check-duplicate';
			
			//$tempJsElements .= str_replace('__ELEMENT_ID__',$element['id'],$element['js']);                 
			if(isset($db_data[$field])){
				$element['value'] = $db_data[$field];
			}
			else if (isset($form_config['default_values'][$field])){
				$element['value'] = $form_config['default_values'][$field];                    
			}
			else{
				//$element['value'] = isset($value['value']) ? $value['value'] : '';		
			}
			}
			
			{//// CODEBEGIN
				if ($element['code_begin'] != ''){
					 $next_form .= replaceBetweenBraces($db_data,$element['code_begin']);	
				}
			}
			
			{//// PREPARE THE ARRAY FOR FETCHING FORM ELEMENT			

			$element['class'] .= ' monitor-input';			
				
			$element['required'] = in_array ($field,$form_config['required']) || isset($form_config['required'][$field]) ? true : false;
			if($element['required']) $element['extra'] .= ' field-required';
		   
			$element['validation'] = isset($form_config['validation'][$field]) ? $form_config['validation'][$field] : array();	
			if(isset($form_config['validation'][$field])) $element['extra'] .= ' field-validate'; 
			
			$element['allowed_duplicate'] = in_array($field,$form_config['duplicate']) ? false : true;
			if(!$element['allowed_duplicate']) $element['extra'] .= ' field-check-duplicate';
			
			$element['help'] = isset($value['help']) ? $value['help'] : 'Please Enter the '.$element['label'].'';	
			$tempJsElements .= isset($value['js'])?str_replace('__ELEMENT_ID__',$element['id'],$value['js']):'';  
			

			}
		   
			$new_field_temp = $this->createFormElement($field,$element);
			$new_field = $new_field_temp['field'];
			$new_field = str_replace('__ELEMENT_ID__',$element['id'],$new_field);
			$new_field = str_replace('__ELEMENT_CLASS__',$element['unique_class'],$new_field);
			$tempJsToolsTemp = $new_field_temp['js'];
			$tempJsToolsTemp = str_replace('__ELEMENT_ID__',$element['id'],$tempJsToolsTemp);
			$tempJsToolsTemp = str_replace('__ELEMENT_CLASS__',$element['unique_class'],$tempJsToolsTemp);
			$form['elementJs'] .= $tempJsElements;
			$form['jsFunction'] .= $tempJsToolsTemp;


			$form['field_html'][$element['id']]['from_group_only'] = $new_field;
							
			{////Wrap The form field with col-md if specified in the width
				if(isset($element['width']) && $element['width'] != '' && $element['width'] != '0' && $element['width'] <= '12'){
					$left_offset = isset($value['left_offset'])?'col-md-offset-'.$value['left_offset'].'':'';
					$right_offset = isset($value['right_offset'])?'col-md-offset-right-'.$value['right_offset'].'':'';
					
					if($element['type'] != 'hidden'){
					$form['field_html'][$element['id']]['wrapped'] = $next_form .= '<div class="col-md-'.$element['width'].' '.$right_offset.' '.$left_offset.' form-common-element-wrapper" id="'.$element['id'].'-holder" style="">'.$new_field.'</div>';	
					}
					else{
						$form['field_html'][$element['id']]['wrapped'] = $next_form .= $new_field;
					}                            
				}
				else{
					if($element['type'] != 'hidden'){                            
					$form['field_html'][$element['id']]['wrapped'] = $next_form .= '<div class="col-md-6 form-common-element-wrapper" id="'.$element['id'].'-holder">'.$new_field.'</div>';	
					}
					else{
						$form['field_html'][$element['id']]['wrapped'] = $next_form .=   $new_field;    
					}                            
				}
			}

			
			{//// CODEEND
				if (isset($value['code_end'])){
					$next_form .= replaceBetweenBraces($db_data,$value['code_end']);	
				}
			}
							
			{/////TOOLS/VALIDATION STUFF

				$form['tools']['icons'][$element['id']] = $element['icon'];	
				if(isset($element['required']) && $element['required']){
					if(isset($element['required'][$field]['conditional'])){
						//// prepend table name as the id of the conditional field is also changed as this form is being generated
						$form['tools']['required'][$element['id']] = $element['required'][$field];	
					}
					else{
						$form['tools']['required'][$element['id']]['status'] = false;
						$form['tools']['required'][$element['id']]['message'] = $element['label'].' is Required';                        
					}
				}


				if(isset($form_config['required'][$field]['conditional'])){
				   $form_config['required'][$field]['conditional']['field'] = $form_config['unique_id'].'_'.$form_config['required'][$field]['conditional']['field'].'___ROW_COUNT__';
				   $form['tools']['required'][$element['id']] = $form_config['required'][$field];  
				}            

				if(isset($element['allowed_duplicate']) && !$element['allowed_duplicate']){
					$form['tools']['duplicate'][$element['id']]['status'] = false;
					$form['tools']['duplicate'][$element['id']]['message'] = $element['label'].' is Required';
				}	

				if (isset($element['validation']) && array_filter($element['validation'])){

					if(isset($element['validation']['conditional'])){
						$form['tools']['validation'][$element['id']]['conditional'] = $form_config['validation'][$field];
					}
					else{            
						$form['tools']['validation'][$element['id']] = $form_config['validation'][$field];   
					}                     
				}
				

				if($element['type'] != 'code'){
					$form['fields'][$field] = $new_field;
					$form['field-types'][] = $element['type'];            
					$form['tools']['titles'][$element['id']] = 'Please Enter the '.$element['label'];                        
				}
			}


		}

		///// insert each completed loop in a row and save it in the main variable
		if(!empty($form_config['form_wrapper_template'])){
			$next_form =  str_replace('__GENERATED_FORM__',$next_form,$form_config['form_wrapper_template']);
			
		}
		else{
			////// {{INCOMPLETE}}  data-id="'.$db_data[$this->config['id']].'"
			$next_form =  
			'
			<div class=" sortable card remove-after-delete" >
			'.($next_form).'
				<div class="" style="position:absolute;top:-30px;left:0px;">
					<a href="javascript:;" class="btn btn-circle btn-icon-only bg-color-primary-custom sortableBtn" id="move-btn__UNIQUE_ID__-__ROW_COUNT__"><i class="fa fa-arrows"></i></a>
				</div> 
				<div class="" style="position:absolute;top:-30px;right:0px;">
					<a href="javascript:;" class="btn btn-circle btn-icon-only red btnDelete__UNIQUE_ID__" data-module="__MODULE__" data-unique_id="__UNIQUE_ID__" data-id="__DB_ID__" data-tools-index="__ROW_COUNT__"><i class="fa fa-trash"></i></a>
				</div>  
			</div>
			';                 
		}
			
		
        $form['form'] = $next_form;
		$form['jsFunction'] .= $this->getDependencyJs2($form_config);

		return $form;		
    }


    
    }
?>
