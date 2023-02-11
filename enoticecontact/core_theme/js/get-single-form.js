
function getSingleForm($form_config){
	{/// Initial Config
	var $tempform = '';
	var $next_form = '';
	var $new_field = '';
	var $fields = {};
	var $new_form = {};
	var $depTemp = {};
	var $tempTools = {};
	var $hiddenFields = {};		
	var $unReplacedJs = {};		
	var $unReplacedJs['high_priority'] = $unReplacedJs['mid_priority'] = $unReplacedJs['low_priority'] = '';
	var $form = {}; /// will hold every thing as an associative array
	var $tempJsTools = '';
	var $tempJsElements = '';
	var $is_another_class = false;
       
	//// If form is repeat/multiple form call the multiple form function and return the value
	if($form_config['is_multiple_form']){
		$form = getExtendedFormMultiple($form_config['request_data']);
		return $form;
		//exit();
	}
	
	$form = $form_config;
	$form['tools']['validation'] = {};
	$form['tools']['required'] = {};
	$form['tools']['duplicate'] = {};
	$form_config['form_id'] = $form_config['parent']+'-form';
	
/* 	if($form_config['override_function']['enabled']){
		$methodName = $form_config['override_function']['function'];
		$return = call_user_func_array(array($this, $methodName), array($data));
		return $return;
	} */

	var $db_data = {};
	if($form_config['has_data']){
		$form['db_data'] = $form_config['has_data'];   
		$db_data = $form_config['has_data'];  
		$.each($options, function($temkDKey, $tempDVal){
			$form['tools']['data'][''+$form_config['parent']+'-'+$temkDKey+''] = $tempDVal;
		}			
	}

	
	$form_config_fields = $form_config['fields'];
	///// IF CUSTOM FIELDS ARE DEFINED
	if($form_config['has_custom_fields']){
		$all_config['fields'] = $form_config['custom_fields'];
		$form_config['labels'] = {};
		$form_config['icons'] = {};            
	}
	else{
		$all_config = getAllFields();
		$form_config['labels'] = getLabels();
		$form_config['icons'] = getIcons();            
	}
	$all_fields = $all_config['fields'];

	///// Create Hidden fields if any
	$form['hidden'] = '';
	$form['priorityJs'] = '';
	if($form_config['hidden']['enabled']){
		$form['hidden'] += $form_config['hidden']['html'];
	}
	
	$form['hidden'] += '<input type="hidden" name="module" id="'+$form_config['parent']+'-module" value="'+$form_config['module']+'">';        
	$form['hidden'] += '<input type="hidden" name="return_form_as" id="'+$form_config['parent']+'-return_as" value="'+$form_config['return_form_as']+'">';        
	$form['hidden'] += '<input type="hidden" name="unique_id" id="'+$form_config['parent']+'-unique_id" value="'+$form_config['unique_id']+'">';
	$form['hidden'] += '<input type="hidden" name="parent" id="'+$form_config['parent']+'-parent" value="'+$form_config['parent']+'">';        
	}
	
	$.each($form_config['fields'], function($tempFieldKey, $field){
	   
		var $value = $all_fields[$field];
		////// MERGE THE COMMON FIELD_ARRAY WITH THE SPECIFIED FIELD VALUES
		var $element = $.extend(field_array, $value);
		$element['unique_class'] = ' __PARENT__-'+$field+'';	
		$element['class'] += ' __PARENT__-'+$field+'';	
		$element['parent'] = $form_config['parent'];
		$element['module'] = $form_config['module'];
		$element['name_prefix'] = ($form_config['name_prefix']) ? $form_config['name_prefix'] : ''; 
		///// OVERRIDE ATTRIBUTES IF SPECIFIED IN FORM_CONFIG OVERRIDE FIELDS
		if(isset($form_config['override_fields'][$field])){
			$overrides = $form_config['override_fields'][$field];
			$element = array_merge($element, $overrides);
		}    
		{//// PREPARE THE ARRAY FOR FETCHING FORM ELEMENT            
		$element['extra'] += ' data-parent="'+$form_config['parent']+'" data-module="'+$form_config['module']+'" ';
		$element['extra'] += isset($value['extra'])?$value['extra']:'';
		 //print_r($element);
		if(!$form_config['has_custom_fields']){
			$element['label'] = getDefault($element['label'],$form_config['labels'][$field]);
			$element['icon'] = getDefault($form_config['icons'][$field],$element['icon']);                
		}
		$element['class'] += ' monitor-input';	
		var $new_field = ''; ///RESET THE VARIABLE TO REMOVE ANY PREVIOUS VALUES            
		$element['name'] = ($form_config['name_prefix']) ? ''+$form_config['name_prefix']+'['+$field+']' :(isset($value['name']) ? $value['name'] : ''+$form_config['parent']+'['+$field+']'); 			
		$element['id'] = $form_config['parent']+'-'+$field;						
				
		//$has_conditional_required = false;
		$element['required'] = inArray($field,$form_config['required']) || isset($form_config['required'][$field]) ? true : false;
		
		if(isset($element['required'][$field]['conditional'])){
			print_r($field);
		}
		if($element['required']) $element['extra'] += ' field-required';

		$element['validation'] = isset($form_config['validation'][$field]) ? $form_config['validation'][$field] : {};	
		if(isset($form_config['validation'][$field])) $element['extra'] += ' field-validate'; 
		
		$element['allowed_duplicate'] = inArray($field,$form_config['duplicate']) ? false : true;
		if(!$element['allowed_duplicate']) $element['extra'] += ' field-check-duplicate';
		
		//$tempJsElements += str_replace('__ELEMENT_ID__',$element['id'],$element['js']);                 
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
				 $next_form += replaceBetweenBraces($db_data,$element['code_begin']);	
			}
		}
	  
		$new_field_temp = $this->createFormElement($field,$element);
		$new_field = $new_field_temp['field'];
		$new_field = str_replace('__ELEMENT_ID__',$element['id'],$new_field);
		$new_field = str_replace('__ELEMENT_CLASS__',$element['unique_class'],$new_field);
		$new_field = str_replace('__ELEMENT_VALUE__',$element['value'],$new_field);
/*             $new_field = str_replace('__PARENT__',$element['parent'],$new_field);
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
		//$tempJsToolsTemp = str_replace('__PARENT__',$element['parent'],$tempJsToolsTemp);
		//$tempJsToolsTemp = str_replace('__MODULE__',$element['module'],$tempJsToolsTemp);
		$tempJsToolsTemp = str_replace('__ELEMENT_ID__',$element['id'],$tempJsToolsTemp);
		$tempJsToolsTemp = str_replace('__ELEMENT_CLASS__',$element['unique_class'],$tempJsToolsTemp);
		if($element['value'] != '') $tempJsToolsTemp = str_replace('__ELEMENT_VALUE__',$element['value'],$tempJsToolsTemp);           
		//$tempJsToolsTemp = str_replace('__NAME_PREFIX__',$element['name_prefix'],$tempJsToolsTemp);
/*             if($element['type'] == 'image_crop'){
			print_r($tempTempF);    
		} */
		$tempJsTools += $tempJsToolsTemp;
		$form['field_html'][$field]['from_group_only'] = $new_field; 

		
		{////Wrap The form field with col-md if specified in the width
			if($element['type'] != 'hidden'){ //// IF NOT HIDDEN ELEMENT WRAP IT
				if(isset($element['width']) && $element['width'] != '' && $element['width'] != '0' && $element['width'] <= '12'){
					$left_offset = ($element['left_offset'] > 0)?'col-md-offset-'+$element['left_offset']+'':'';
					$right_offset = ($element['right_offset'] > 0)?'col-md-offset-right-'+$element['right_offset']+'':'';
					
					$form['field_html'][$field]['wrapped'] = $next_form += '<div class="col-md-'+$element['width']+' '+$right_offset+' '+$left_offset+' form-common-element-wrapper" id="'+$element['id']+'-holder" style="">'+$new_field+'</div>';			
				}
				else{ ///IF HIDDEN ELEMENT NO NEED TO WRAP IT		
					$form['field_html'][$field]['wrapped'] = $next_form += '<div class="col-md-6 form-common-element-wrapper" id="'+$element['id']+'-holder">'+$new_field+'</div>';		
				}                        
			}
			else{
				$form['field_html'][$field]['wrapped'] = $next_form += $new_field;
			}
		}
		{//// CODEEND
			if ($element['code_end'] != ''){
				$next_form += replaceBetweenBraces($db_data,$element['code_end']);
				$form['field_html'][$field]['wrapped'] += replaceBetweenBraces($db_data,$element['code_end']);
				
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
				$form['tools']['required'][$element['id']]['message'] = $element['label']+' is Required';                        
			}
		}
		if(isset($form_config['required'][$field]['conditional'])){
			$form_config['required'][$field]['conditional']['field'] = $form_config['parent']+'-'+$form_config['required'][$field]['conditional']['field'];
		   $form['tools']['required'][$element['id']] = $form_config['required'][$field];  
		}            
		if(isset($element['allowed_duplicate']) && !$element['allowed_duplicate']){
			$form['tools']['duplicate'][$element['id']]['status'] = false;
			$form['tools']['duplicate'][$element['id']]['message'] = $element['label']+' is Required';
		}	


		if (isset($element['validation']) && array_filter($element['validation'])){
			if(isset($element['validation']['conditional'])){
				$form['tools']['validation'][$element['id']]['conditional'] = $form_config['validation'][$field];
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
			$form['tools']['titles'][$element['id']] = 'Please Enter the '+$element['label'];                        
		}
	
		}                

	}
	
	$ajaxDepjs = '';        
	if(!empty(array_filter($this->ajax_dependency))){
		
		foreach($this->ajax_dependency as $ajField=>$depElem){
			
			$ajaxDepjs += 
			"
			$(document).on('change','#$form_config[parent]-$ajField', function(){
			   getDependentFields('$form_config[module]','$form_config[parent]-$ajField'); 
			});
			";
			$form['tools']['ajax_dependency'][$form_config['parent']+'-'+$ajField] = $form_config['parent']+'-'+key($depElem);                
		}            
	}

	$form['tools']['parent'] = $form_config['parent'];
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
	$next_form += isset($form_config['hidden-fields']) ? $form_config['hidden-fields'] : ''; 
		
	}

	$form['form'] = $next_form;       
	{////Buttons
	if($form_config['buttons']['enabled']){
		//$form_config['buttons']['html'] = str_replace('__FORM_ID__',$form_config['form_id'],$form_config['buttons']['html']);
		//$form_config['buttons']['html'] = str_replace('__PARENT__',$form_config['parent'],$form_config['buttons']['html']);
		//$form_config['buttons']['html'] = str_replace('__MODULE__',$form_config['module'],$form_config['buttons']['html']);
		$form['buttons'] = $form_config['buttons']['html'];
	}
	else{
		$form['buttons'] = 
		' 
		<a href="javascript:;" class="btn btn-outline red" id="'+$form_config['parent']+'-btnClose"  onClick="CloseBsModal(\''+$form_config['parent']+'-modal\',this)" >Cancel</a>             
		<button type="button" class="btn btn-outline green" id="'+$form_config['parent']+'-btnSave"  onClick="Save({\'form_type\':\''+$form_config['form_type']+'\',\'extended_action\':\'save\',\'module\':\''+$form_config['module']+'\'},\''+$form_config['form_id']+'\',\''+$form_config['parent']+'\')">Save</button>  
		';
	}
	}
	
/*         $depTemp = {};
	foreach($form_config['dependency'] as $depKeyTemp=>$depValTemp){
		$depTemp[$form_config['parent']+'-'+$depKeyTemp] = $depValTemp;
	} */
	$form['dependency'] = $depTemp;
	{//// Return Form As 
	$form['form'] += $form_config['extra_html'];

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
		if(!$form_config['buttons']['enabled']){
			$form['buttons'] = 
			'
			<a href="javascript:;" class="btn btn-outline red" id="'+$form_config['parent']+'-btnClose"  onClick="CloseBsModal(\''+$form_config['parent']+'-main-container\',this)" data-parent="'+$form_config['parent']+'">Cancel</a>             
			<button type="button" class="btn btn-outline green" id="'+$form_config['parent']+'-btnSave"  onClick="Save({\'form_type\':\''+$form_config['form_type']+'\',\'extended_action\':\'save\',\'module\':\''+$form_config['module']+'\'},\''+$form_config['form_id']+'\',\''+$form_config['parent']+'\')" data-parent="'+$form_config['parent']+'">Save</button> 
			';            
		}
		$modal_html = $this->GetModalForm($form);
		$modal_width = getDefault($form_config['modal_width'],'70');
		$modal_wrapper = 
		'
		<div class="modal  modal-scroll draggable-modal animated zoomIn" data-in-animation="zoomIn" data-out-animation="zoomOut" id="'+$form_config['parent']+'-main-container" data-backdrop="static" data-keyboard="true" style="margin:0 auto;min-width:300px;width:'+$modal_width+'">
		'+$modal_html+'
		</div>            
		';
		$form['html'] = $modal_wrapper;	
		$tempJsTools += 
		"
		$('#$form_config[parent]-main-container').on('hidden.bs.modal', function () {
			$form_config[jsModalClose];                
			$('#$form_config[parent]-main-container').remove();
		});
			";
		$form['priorityJs'] = 
		"
		$('body').append(value.html);
		toolsAll['$form_config[parent]'] = value.tools;
		ShowBsModal('$form_config[parent]-main-container');
	  
		$('#$form_config[parent]-main-container').draggable({handle:'+modal-header'});    
		";
	}
	else if($form_config['return_form_as'] == 'portlet'){
		 if(!$form_config['buttons']['enabled']){
			$form['buttons'] = 
			'            
			<button type="button" class="btn btn-outline btn-half-block  yellow-gold" id="'+$form_config['parent']+'-btnSave"  onClick="Save({\'form_type\':\''+$form_config['form_type']+'\',\'extended_action\':\'save\',\'module\':\''+$form_config['module']+'\'},\''+$form_config['form_id']+'\',\''+$form_config['parent']+'\')" data-parent="'+$form_config['parent']+'">Save</button> 
			';      
		 }                
		$form['html'] = $this->GetPortletForm($form);
	
		$encoded_tools = json_encode($form['tools']);
		$form['priorityJs'] += "toolsAll['__PARENT__'] = $encoded_tools;";          
	} 
	else if($form_config['return_form_as'] == 'rows'){
		if(!$form_config['buttons']['enabled']){
			$form['buttons'] = 
			'            
			<button type="button" class="btn btn-half-block  yellow-gold" id="'+$form_config['parent']+'-btnSave"  onClick="Save({\'form_type\':\''+$form_config['form_type']+'\',\'extended_action\':\'save\',\'module\':\''+$form_config['module']+'\'},\''+$form_config['form_id']+'\',\''+$form_config['parent']+'\')" data-parent="'+$form_config['parent']+'">Save</button> 
			';       
		}       
		
		$form['html'] = row($form['form']);	
				
		$encoded_tools = json_encode($form['tools']);
		$form['priorityJs'] += "toolsAll['__PARENT__'] = $encoded_tools;";
	} 
	else if($form_config['return_form_as'] == 'popover'){
		if(!$form_config['buttons']['enabled']){
			$form['buttons'] = 
			'          
			<button type="button" class="btn yellow-gold ladda-button" data-style="zoom-out" data-size="l" id="'+$form_config['parent']+'-btnSave"  onClick="CommonFunc2Btn({\'form_type\':\''+$form_config['form_type']+'\',\'extended_action\':\'save\',\'module\':\''+$form_config['module']+'\'},\''+$form_config['form_id']+'\',\''+$form_config['parent']+'\')" data-parent="'+$form_config['parent']+'"><i class="fa fa-search"></i>SEARCH</button> 
			';       
		} 
		$popover_template = 
		'
		 <div class="popover popover-with-select2 popover-custom-theme" role="tooltip" style="width:60%;max-width:60%;top:50px">
			 <div class="arrow"></div>
			 <h3 class="popover-title ">Search '+$form_config['portlet_title']+'</h3>
			 <div class="popover-actions">
				<a href="javascript:;" class="btn btn-circle btn-icon-only red" id="__PARENT__-btnClose"><i class=" fa fa-close" style="font-size: 30px;"></i></a>
			 </div>                  
			 <div class="popover-content" id="'+$form_config['parent']+'-main-container">
			 
			 </div>
			 <div class="row text-center" id="'+$form_config['parent']+'-popover-result">
			 <h4 class="font-yellow-gold sub-title">'+$form_config['portlet_title']+'</h4>
			 </div>
		  </div>         
		';            
		if(isset($form_config['popover_template']) && $form_config['popover_template'] != ''){

				$popover_template = str_replace('__PARENT__',$form_config['parent'],$form_config['popover_template']);        
		}            
		
		$form['html'] = row($form['form']);
		$form['html'] += '<div class="row text-center">'+$form['buttons']+'</div>';
		$temp_content = 
		'
		<form id="'+$form_config['form_id']+'" method="POST" onSubmit="return false;">
		'+$form['html']+'
		'+$form['hidden']+'
		</form>
		';
		$form['priorityJs'] = 
		"
		toolsAll['$form_config[parent]'] = value.tools;
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
		$('#$form_config[parent]-btnClose').on('click',function(){
			pop_on.popover('destroy');
		});
		";            
	}  
	}
	
	
	$form['include_css'] = $this->include_css;
	$form['include_js'] = $this->include_js;
	$form['jsFunction'] = $form['priorityJs'];
	$form['jsFunction'] += $ajaxDepjs;
	$form['jsFunction'] += $tempJsTools;
	$form['jsFunction'] += $tempJsElements;
	$form['jsFunction'] += $form_config['jsFunction'];
	$form['jsFunction'] += $this->getDependencyJs($form_config);        
	$form['jsFunction'] += $form_config['jsFunctionLast'];	        
	$form['jsFunction'] += "";	
	if(isset($form_config['request_data']['id'])) {
		$form['html'] = str_replace('__ID__',$form_config['request_data']['id'],$form['html']); 
		$form['form'] = str_replace('__ID__',$form_config['request_data']['id'],$form['form']); 
		$form['jsFunction'] = str_replace('__ID__',$form_config['request_data']['id'],$form['jsFunction']);  
	}       

	$form['form'] = str_replace('__PARENT__',$form_config['parent'],$form['form']);
	$form['form'] = str_replace('__MODULE__',$form_config['module'],$form['form']);
	$form['form'] = str_replace('__NAME_PREFIX__',$form_config['name_prefix'],$form['form']);       
	$form['form'] = str_replace('__FORM_ID__',$form_config['form_id'],$form['form']);       

	$form['html'] = str_replace('__PARENT__',$form_config['parent'],$form['html']);
	$form['html'] = str_replace('__MODULE__',$form_config['module'],$form['html']);
	$form['html'] = str_replace('__NAME_PREFIX__',$form_config['name_prefix'],$form['html']);
	$form['html'] = str_replace('__FORM_ID__',$form_config['form_id'],$form['html']);

	$form['jsFunction'] = str_replace('__PARENT__',$form_config['parent'],$form['jsFunction']);
	$form['jsFunction'] = str_replace('__MODULE__',$form_config['module'],$form['jsFunction']);
	$form['jsFunction'] = str_replace('__ELEMENT_ID__',$form_config['name_prefix'],$form['jsFunction']);      
	$form['jsFunction'] = str_replace('__FORM_ID__',$form_config['form_id'],$form['jsFunction']);      

	$form['form'] += $form_config['extra_unreplaced_html'];		
	$form['jsFunction'] = $unReplacedJs['high_priority'].$form['jsFunction'];
	$form['jsFunction'] += $unReplacedJs['low_priority'];		
	return $form;  

}

