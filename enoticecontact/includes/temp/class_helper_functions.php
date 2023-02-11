<?php 
function GetMtTabsFromClass($tabs){
	$code = '';
	$i = 1;
	foreach($tabs as $key => $value){
		$active = ($i == 1) ? 'active in-progress' : '';
		if(isset($value['content']) && $value['content'] == 'new-form'){		
			$code .= 
			'
										<div class="col-md-3 bg-grey mt-step-col '.$active.'" id="tab_'.$i.'">
											<div class="mt-step-number">'.$i.'</div>
											<div class="mt-step-icon"><i class="large mdi-action-done-all"></i></div>
											<div class="mt-step-icon-spin"><i class="mdi-maps-my-location spin"></i></div>
											<div class="mt-step-title uppercase font-grey-cascade">'.$value['title'].'</div>
											<div class="mt-step-content font-grey-cascade">'.$value['sub-title'].'</div>
										</div>		
			
			';	
		}
		else if(isset($value['content']) && $value['content'] == ''){
			$code .= 
			'
										<li class="col-md-3 bg-grey mt-step-col '.$active.'" id="tab_'.$i.'">
											<a href="#step_'.$i.'" data-toggle="tab" aria-expanded="false">												
												<div class="mt-step-number font-grey">'.$i.'</div>
												<div class="mt-step-icon font-red-sunglo"><i class="fa fa-rupee"></i></div>	
												<div class="mt-step-title uppercase">'.$value['title'].'</div>
												<div class="mt-step-content">'.$value['sub-title'].'</div>
											</a>
										</li>		
			
			';			
			
		}
		$i++;
	}
	if(!isset($value['content'])){		
		$return = 
		'
							<div class="mt-element-step">
								<div class="row step-thin">
									'.$code.'
								</div>
							</div>						
		';
	}
	else{
		$return = 
		'
							<div class="mt-element-step">
								<div class="row step-background-thin">
									<ul class="nav">
									'.$code.'												
									</ul>
								</div>
							</div>						
		';		
		
	}
	return $return;
}
	
function GetMtTabContentFromClass($tabs){
	global $username,$id;	
	$mainPortlet = new Portlet();
	$mainPortlet->portletClass = 'light ';
	$mainPortlet->captionClass = 'font-green-sharp';
	$mainPortlet->iconClass = 'icon-user font-green-sharp';
	$i = 1;	
	$return['content'] = '';
    $jsFunction = '';    
	foreach($tabs as $key => $value){
		$active = ($i == 1) ? 'active' : '';
		$class = $value['class'];	
        
		if(isset($value['content']) && $value['content'] == 'new-form'){
			$fields ='';

			$form_fields = $class->getForm();	
            $jsFunction .= $form_fields['jsFunction'];
			$mainPortlet->portletId = 'portlet-'.$form_fields['form-id'];
			$mainPortlet->formId = $form_fields['form-id'];
			$mainPortlet->formAction = '';

/* 			foreach($form_fields['fields'] as $field) {
				
				$fields .= col6($field);
			} */
			$prev_button = '';
			$next_button = '<a href="#" class="btn green btn-sm" onclick="NextTab(\''.($i + 1).'\')">NEXT</a>';
			if($i != 1){
				$prev_button = '<a href="#" class="btn red btn-sm" onclick="PrevTab(\''.($i - 1).'\')">PREVIOUS</a>';
			}
			if($i == 4){
				$next_button = '<a href="#" class="btn green btn-sm" onClick="SaveUser(\'new_user_form\')">SAVE</a>';
			}
			
            $fields .= $form_fields['form'];
            $buttons = '';
			$buttons .= '
					'.$prev_button.'
					'.$next_button.'
			';
            //$mainPortlet->actions = $buttons;  												
            
            $fields .= '
            <div class="col-md-12 well text-center">
                '.$buttons.'
            </div>
            ';

			$fields .= '<input type="hidden" name="createdby" id="createdby" value="'.$username.'">';						
			$fields .= '<input type="hidden" name="action" id="action" value="save">';						
			$portlet = $mainPortlet->GetPortlet($form_fields['portlet-title'], $fields);
            $return['tools'][] = $form_fields['tools'];				
		}
		else if(isset($value['content']) && $value['content'] == '')
		{
			$data['data'] = $class->getData($id);
			$data['portlet-title'] = $value['title'];
			$titles = $class->form_fields_db_label;			
			$portlet = $data['data'];
			$temp = $class->display_static_info($data['data'],$titles,'col-md-6');	
			$portlet = $mainPortlet->GetPortlet($value['title'], row($temp));			
		}
		$return['content'] .= 
		'
												<div class="tab-pane '.$active.'" id="step_'.$i.'">
													<div class="row">
														'.$portlet.'
													</div>
												</div>
		';	

    //$return['tools'][] = $form_fields['tools'];		
		$i++;		
	}

	$return['jsFunction'] = $jsFunction;

	$return['content'] = 
	'
									<div class="tabbable-custom tabs-below nav-justified">
										<div class="tab-content">

											'.$return['content'].'
										</div>
										
									</div>	
	';
	
	$return['nav_tabs'] = GetMtTabsFromClass($tabs);

	return $return;	
}

?>