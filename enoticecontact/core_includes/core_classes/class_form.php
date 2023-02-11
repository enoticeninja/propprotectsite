<?php

Class Form {
public $isDoubleColumn = FALSE;
public $labelFontClass = '';
public $isDisabled = '';


public function Button($id, $class, $label, $onclick="", $attr="" ) {
	$disabled = '';
	$onClickCode = '';
	if($this->isDisabled == 'readonly') $disabled = 'disabled';
	if($onclick != '') $onClickCode = 'onClick="'.$onclick.'"';
	
	$return = 
	'
				<button type="button" id="'.$id.'" class="btn '.$class.'" '.$onClickCode.' '.$disabled.' '.$attr.'>'.$label.'</button>
	';
	return $return;
}


public function submitButton($id, $class, $label, $onclick="" ) {
		$disabled = '';
	if($this->isDisabled == 'readonly') $disabled = 'disabled';

	$return = 
	'
				<button type="submit" id="'.$id.'" class="btn '.$class.'" onClick="'.$onclick.'" '.$disabled.'>'.$label.'</button>
	';
	return $return;
}

public function enableInputs(){
	$this->isDisabled = '';
}

public function disableInputs(){
	$this->isDisabled = 'readonly';
}
////function input(inputType, id, formType:(md,md-floating, md-2-10, md-3-9, md-4-8, md-5-7, md-6-6), inputClass, placeHolder, label, help)
Function input($inputType='text',$id='', $type = '', $class='', $placeholder='', $label='', $help='', $isDoubleColumn = FALSE, $value='', $name='') {

		$temp = [];
		$temp['inputType'] = $inputType;
		if ($name == ''){$temp['name'] = $id;} else{$temp['name'] = $name;}	
		$temp['id'] = $id;
		$temp['type'] = $type;
		$temp['class'] = $class;
		$temp['placeholder'] = $placeholder;
		$temp['label'] = $label;
		$temp['help'] = $help;
		$temp['isDoubleColumn'] = $isDoubleColumn;
		if($value != '') $temp['value'] = $value;

		$return = $this->inputFromArray($temp);	
		return $return;	
}


Function advancedDateTimeFromArray($dataArray) {
		$inputType = (isset($dataArray['inputType'] )&& $dataArray['inputType'] != '')? $dataArray['inputType'] : 'text';

		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['type'])? $dataArray['type'] : '';
		$class = isset($dataArray['class'])? $dataArray['class'] : '';
		$placeholder =isset ($dataArray['placeholder'])? $dataArray['placeholder'] : '';
		$label = isset($dataArray['label'])? $dataArray['label'] : '';
		$help = isset($dataArray['help'])? $dataArray['help'] : '';
		$hidden = (isset($dataArray['hidden']) && $dataArray['hidden'] == true) ? 'hidden' : '';

		$value = (isset($dataArray['value']) && $dataArray['value'] != '')? 'value="'.$dataArray['value'].'"' : '';
		$isDoubleColumn = (isset($dataArray['isDoubleColumn']) && $dataArray['isDoubleColumn'] != '')? $dataArray['isDoubleColumn'] : false;
		$extraCode = isset($dataArray['extra'])? $dataArray['extra'] : ''; 
		$autoComplete = (isset($dataArray['autoComplete']) && $dataArray['autoComplete'] != '')? $dataArray['autoComplete'] : 'off'; 
		$return = '';
		$otherClasses = '';		
		$cols = explode('-', $type);
		if($cols[0] == 'default') $type = '';
		if($cols[0] == 'md') $type = 'form-md-line-input';
		if($value != '') $otherClasses .= 'edited ';
		$icon = (isset($dataArray['icon']) && $dataArray['icon'] != '')? '<i class="'.$dataArray['icon'].'"></i>' : '';
        $time_type = isset($dataArray['time_type'])? $dataArray['time_type'] : 'form_datetime';

        $return1 = 
        '

                        <div class="form-group '.$hidden.'" >
                            <label class="control-label col-md-12">'.$label.'</label>
                            <div class="col-md-10" style="margin-bottom:20px">
                                <div class="input-group date '.$time_type.'" data-date="'.$dataArray['value'].'" id="datetime-'.$id.'">
                                    <input type="text" size="16"  name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" '.$value.' id="'.$id.'" '.$this->isDisabled.' '.$extraCode.'>
                                    <span class="input-group-btn">
                                        <button class="btn default date-reset" type="button" style="margin: 0;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button" style="margin: 0;">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <span class="col-md-12 help-block"></span>
                        </div>                        
        ';  


        return $return1;									
}



Function inputFromArray($dataArray) {
		$inputType = (isset($dataArray['inputType'] )&& $dataArray['inputType'] != '')? $dataArray['inputType'] : 'text';

		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['type'])? $dataArray['type'] : '';
		$class = isset($dataArray['class'])? $dataArray['class'] : '';
		$placeholder =isset ($dataArray['placeholder'])? $dataArray['placeholder'] : '';
		$label = isset($dataArray['label'])? $dataArray['label'] : '';
		$help = isset($dataArray['help'])? $dataArray['help'] : '';
		$hidden = (isset($dataArray['hidden']) && $dataArray['hidden'] == true) ? 'hidden' : '';
		$onClick = isset($dataArray['onClick']) ? $dataArray['onClick'] : '';

		$value = (isset($dataArray['value']) && $dataArray['value'] != '')? 'value="'.$dataArray['value'].'"' : '';
		$isDoubleColumn = (isset($dataArray['isDoubleColumn']) && $dataArray['isDoubleColumn'] != '')? $dataArray['isDoubleColumn'] : false;
		$extraCode = isset($dataArray['extra'])? $dataArray['extra'] : ''; 
		$autoComplete = (isset($dataArray['autoComplete']) && $dataArray['autoComplete'] != '')? $dataArray['autoComplete'] : 'off'; 
		$return = '';
		$otherClasses = '';		
		$cols = explode('-', $type);
		if($cols[0] == 'default') $type = '';
		if($cols[0] == 'md') $type = 'form-md-line-input';
		if($value != '') $otherClasses .= 'edited ';
		$icon = (isset($dataArray['icon']) && $dataArray['icon'] != '')? '<i class="'.$dataArray['icon'].'"></i>' : '';
        if($cols[0] == 'md'){

            $input = 
            '
                <input type="'.$inputType.'" class="form-control '.$class.' '.$otherClasses.'" name="'.$name.'" id="'.$id.'" placeholder="'.$placeholder.'" '.$value.' '.$this->isDisabled.' '.$extraCode.' autocomplete="'.$autoComplete.'" onClick="'.$onClick.'">
                <div class="form-control-focus"></div>
                <span class="help-block">'.$help.'</span>		
            ';
            
            if($icon != ''){
                $return2 =
                '
                    <div class="input-group">
                        '.$input.'
                        <span class="input-group-addon" id="'.$id.'-icon" style="cursor: pointer">'.$icon.'</span>										
                    </div>	
                ';
            }
            else{
                $return2 =
                '
                        '.$input.'		
                ';		
            }
            
            $return1 = 
            '
                                            <div class="form-group '.$type.' '.$hidden.'">
                                                <label class="col-md-'.$cols[1].' control-label '.$this->labelFontClass.' text-right" for="'.$id.'">'.$label.'</label>
                                                <div class="col-md-'.$cols[2].'">
                                                    '.$return2.'
                                                </div>
                                            </div>	
            ';
    }

        if($cols[0] == 'floating'){
            $type = 'form-md-line-input form-md-floating-label';
            
            $input = 
            '
                <input type="'.$inputType.'" name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" '.$value.' id="'.$id.'" '.$this->isDisabled.' '.$extraCode.'  onClick="'.$onClick.'">
                <label for="'.$id.'" class="'.$this->labelFontClass.'">'.$icon.' '.$label.'</label>
                <span class="help-block">'.$help.'</span>			
            ';
            
            if($icon != ''){
                $return2 =
                '
                <div class="input-group right-addon">						
                    '.$input.'		
                    <span class="input-group-addon" id="'.$id.'-icon" style="cursor: pointer">'.$icon.'</span>
                </div>
                ';
            }
            else{
                $return2 =
                '
                        '.$input.'		
                ';		
            }
            
            $return1 = 
            '
            <div class="form-group '.$type.' '.$hidden.'">
                '.$return2.'
            </div>		
            
            ';
        }

        if($cols[0] == 'default'){
            $input = 
            '
            <input type="'.$inputType.'" name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" '.$value.' id="'.$id.'" '.$this->isDisabled.' '.$extraCode.'  onClick="'.$onClick.'">		
            ';
            
            if($icon != ''){
                $return2 =
                '
                <div class="input-group">
                    <span class="input-group-addon" id="'.$id.'-icon" style="cursor: pointer">'.$icon.'</span>	
                    '.$input.'                     
                </div>
                ';
            }
            else{
                $return2 =
                '
                        '.$input.'		
                ';		
            }
        $return1 = 
        '
                        <div class="form-group '.$hidden.'">
                            <label class="col-md-'.$cols[1].' control-label font-green">'.$label.'</label>
                            <div class="col-md-'.$cols[2].'">
                                '.$return2.'
                                <span class="help-block">'.$help.'</span>     
                            </div>
                        </div>        
        ';  
    }

        if($cols[0] == 'defaultsbs'){
            $input = 
            '
            <input type="'.$inputType.'" name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" '.$value.' id="'.$id.'" '.$this->isDisabled.' '.$extraCode.'>		
            ';
            
            if($icon != ''){
                $return2 =
                '
                <div class="input-group ">
                    '.$input.'            
                    <span class="input-group-addon" id="'.$id.'-icon" style="cursor: pointer">'.$icon.'</span>			    
                </div>
                ';
            }
            else{
                $return2 =
                '
                        '.$input.'		
                ';		
            }
            
        $return1 = 
        '
                        <div class="form-group '.$hidden.'">
                            <label>'.$label.'</label>
                            '.$return2.'
                            <span class="help-block">'.$help.'</span>     
                           
                        </div>					
        
        ';
    }

        return $return1;									
}

Function inputFormGroup($dataArray) { //RETURNS form-group div only without the row
		$inputType = isset($dataArray['inputType'])? $dataArray['inputType'] : 'text';
		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['type'])? $dataArray['type'] : '';
		$class = isset($dataArray['class'])? $dataArray['class'] : '';
		$placeholder =isset ($dataArray['placeholder'])? $dataArray['placeholder'] : '';
		$label = isset($dataArray['label'])? $dataArray['label'] : '';
		$help = isset($dataArray['help'])? $dataArray['help'] : '';

		$value = isset($dataArray['value'])? 'value="'.$dataArray['value'].'"' : '';
		$isDoubleColumn = isset($dataArray['isDoubleColumn'])? $dataArray['isDoubleColumn'] : false;
		$extraCode = isset($dataArray['extra'])? $dataArray['extra'] : ''; 
		$autoComplete = isset($dataArray['autoComplete'])? $dataArray['autoComplete'] : 'off'; 
		$return = '';
		$otherClasses = '';		
		$cols = explode('-', $type);
		if($cols[0] == 'default') $type = '';
		if($cols[0] == 'md') $type = 'form-md-line-input';
		if($value != '') $otherClasses .= 'edited ';

	if($cols[0] == 'md'){
	$icon = isset($dataArray['icon'])? '<i class="'.$dataArray['icon'].'"></i>' : '';			
	$return1 = 
	'
									<div class="form-group '.$type.'">
										<label class="col-md-'.$cols[1].' control-label '.$this->labelFontClass.' text-right" for="'.$id.'">'.$label.'</label>
										<div class="col-md-'.$cols[2].'">
											<div class="input-group">

												<input type="'.$inputType.'" class="form-control '.$class.' '.$otherClasses.'" name="'.$name.'" id="'.$id.'" placeholder="'.$placeholder.'" '.$value.' '.$this->isDisabled.' '.$extraCode.' autocomplete="'.$autoComplete.'">
												<div class="form-control-focus">
												</div>
												<span class="help-block">'.$help.'</span>												
												<span class="input-group-addon">
												'.$icon.'
												</span>												
											</div>
										</div>
									</div>	
	';
}
	if($cols[0] == 'floating'){
		$type = 'form-md-line-input form-md-floating-label';
		$return1 = 
		'
						<div class="form-group '.$type.' col-md-'.$cols[2].'">
							<div class="input-group right-addon">						
							<input type="'.$inputType.'" name="'.$name.'" class="form-control '.$otherClasses.'" '.$value.' id="'.$id.'" '.$this->isDisabled.' '.$extraCode.'>
							<label for="'.$id.'" class="'.$this->labelFontClass.'">'.$label.'</label>
							<span class="help-block">'.$help.'</span>
								<span class="input-group-addon">
								</span>
							</div>
						</div>		
		
		';
	}

	if($cols[0] == 'default'){
	$type = 'form-md-line-input form-md-floating-label';
	$icon = isset($dataArray['icon'])? '<i class="'.$dataArray['icon'].'"></i>' : '';	
	$return1 = 
	'
					<div class="form-group">
						<label class="col-md-'.$cols[1].' control-label text-right">'.$label.'</label>
						<div class="col-md-'.$cols[2].'">
							<div class="input-icon right">
								'.$icon.'
								<input type="'.$inputType.'" name="'.$name.'" class="form-control '.$otherClasses.'" '.$value.' id="'.$id.'" '.$this->isDisabled.' '.$extraCode.'>
							</div>
						</div>
					</div>					
	
	';
}

return $return1;									
}

Function select($id='', $type = '', $class='', $placeholder='', $label='', $help='', $options, $isDoubleColumn = FALSE, $selected = '', $name='') {
		$temp = [];
		$temp['id'] = $id;
		if ($name == ''){$temp['name'] = $id;} else{$temp['name'] = $name;}	
		$temp['type'] = $type;
		$temp['class'] = $class;
		$temp['placeholder'] = $placeholder;
		$temp['label'] = $label;
		$temp['help'] = $help;
		$temp['isDoubleColumn'] = $isDoubleColumn;
		$temp['selected'] = $selected;
		$temp['options'] = $options;

		$return = $this->selectFromArray($temp);	
		return $return;								
}


Function selectFromArray2($dataArray) {
		$disabled = '';
		if($this->isDisabled == 'readonly') $disabled = 'disabled';	
		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['type'])? $dataArray['type'] : '';
		$class = isset($dataArray['class'])? $dataArray['class'] : '';
		$placeholder =isset ($dataArray['placeholder'])? $dataArray['placeholder'] : '';
		$label = isset($dataArray['label'])? $dataArray['label'] : '';
		$help = isset($dataArray['help'])? $dataArray['help'] : '';
		$selected = isset($dataArray['value'])? $dataArray['value'] : NULL;
		$isDoubleColumn = isset($dataArray['isDoubleColumn'])? $dataArray['isDoubleColumn'] : false;
		$extraCode = isset($dataArray['extra'])? $dataArray['extra'] : ''; 
		$options = isset($dataArray['options'])? $dataArray['options'] : ''; 
		$hidden = (isset($dataArray['hidden']) && $dataArray['hidden'] == true) ? 'hidden' : '';		
		$return = '';
		$otherClasses = '';	
		$dataAttr = '';	
		if(isset($dataArray['value'])) $otherClasses .= ' edited';	
	

	$options1 = '<option></option>';		
	if(is_array($options)){
		foreach($options as $key => $value) {

					if($key == $selected && $selected !== NULL) $isSelected = 'selected';	
					else $isSelected = '';
						$options1 .= '
												<option value="'.$key.'" '.$isSelected.'>'.$value.'</option>
											';
		}	
	} else
	{
		$options1 = $options;
	}
    
    $return1 =
    '
    <div class="form-group">
    <label class="control-label col-md-5">'.$label.'</label>
    <div class="col-md-7">    
	<select name="'.$name.'" class="bs-select form-control'.$otherClasses.'" data-style="btn-default" id="'.$id.'" '.$disabled.'>  
        '.$options1.'
    </select>
    </div>    
    </div>   
    ';
return $return1;    
}



Function selectFromArray($dataArray) {
		$disabled = '';
		if($this->isDisabled == 'readonly') $disabled = 'disabled';	
		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['type'])? $dataArray['type'] : '';
		$class = isset($dataArray['class'])? $dataArray['class'] : '';
		$placeholder =isset ($dataArray['placeholder'])? $dataArray['placeholder'] : '';
		$label = isset($dataArray['label'])? $dataArray['label'] : '';
		$help = isset($dataArray['help'])? $dataArray['help'] : '';
		$selected = isset($dataArray['value'])? $dataArray['value'] : NULL;
		$isDoubleColumn = isset($dataArray['isDoubleColumn'])? $dataArray['isDoubleColumn'] : false;
		$extraCode = isset($dataArray['extra'])? $dataArray['extra'] : ''; 
		$options = isset($dataArray['options'])? $dataArray['options'] : ''; 
		$hidden = (isset($dataArray['hidden']) && $dataArray['hidden'] == true) ? 'hidden' : '';		
		$return = '';
		$otherClasses = '';	
		if(isset($dataArray['value'])) $otherClasses .= ' edited';	
		$cols = explode('-', $type);
		if($cols[0] == 'default') $type = '';
		if($cols[0] == 'defaultlabelleft') $type = '';
		if($cols[0] == 'defaultlabelcenter') $type = '';
		if($cols[0] == 'md') $type = 'form-md-line-input';
		if($cols[0] == 'floating') $type = 'form-md-line-input form-md-floating-label';		

	$options1 = '<option></option>';		
	if(is_array($options)){
		foreach($options as $key => $value) {

					if($key == $selected && $selected !== NULL) $isSelected = 'selected';	
					else $isSelected = '';
						$options1 .= '
												<option value="'.$key.'" '.$isSelected.'>'.$value.'</option>
											';
		}	
	} else
	{
		$options1 = $options;
	}

	$icon = (isset($dataArray['icon']) && $dataArray['icon'] != '')? '<i class="'.$dataArray['icon'].'"></i>' : '';	
	if($cols[0] == 'default' || $cols[0] == 'defaultlabelleft' || $cols[0] == 'defaultlabelcenter'){	
		$input = 
		'
			<select name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" id="'.$id.'" '.$disabled.' '.$extraCode.'>
				'.$options1.'
			</select>		
		';
		
		if($icon != ''){
			$return2 =
			'
			<div class="input-group right-addon" >						
				'.$input.'		
				<span class="input-group-addon" id="'.$id.'-icon" style="cursor: pointer">'.$icon.'</span>
			</div>
			';
		}
		else{
			$return2 =
			'
					'.$input.'		
			';		
		}
		
		$return1 = 
		'
		<div class="form-group '.$type.' '.$hidden.'">
			'.$return2.'
		</div>		
		
		';	
        $label_left_right = 'text-right';
        if($cols[0] == 'defaultlabelleft'){
            $label_left_right = 'text-left';
        }
        if($cols[0] == 'defaultlabelcenter'){
            $label_left_right = 'text-center';
        }
	$return1 = 
	'
				<div class="form-group '.$type.' col-md-12">
					<label class="col-md-'.$cols[1].' control-label '.$label_left_right.'">'.$label.'</label>
					<div class="col-md-'.$cols[2].'">
					'.$return2.'
					</div>
				</div>				
	';	
	

	}

	else if($cols[0] == 'floating'){	
		$input = 
		'
			<select name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" id="'.$id.'" '.$disabled.' '.$extraCode.'>
				'.$options1.'
			</select>
			<label for="form_control_1"> '.$label.'</label>	
			<span class="help-block">'.$help.'</span>	
		';
		
		if($icon != ''){
			$return2 =
			'
			<div class="input-group right-addon">						
				'.$input.'		
				<span class="input-group-addon" id="'.$id.'-icon" style="cursor: pointer">'.$icon.'</span>
			</div>
			';
		}
		else{
			$return2 =
			'
					'.$input.'		
			';		
		}	
	$return1 = 
	'
				<div class="form-group '.$type.' '.$hidden.'">
					'.$return2.'
				</div>
	';

	}
	else if($cols[0] == 'md'){	

		$input = 
		'
			<label for="'.$id.'" class="col-md-'.$cols[1].' control-label text-right"> '.$label.'</label>	
			<div class="col-md-'.$cols[2].'">
				<select name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" id="'.$id.'" '.$disabled.' '.$extraCode.'>
					'.$options1.'
				</select>
				<div class="form-control-focus"> </div>
			</div>
		';
		
		if($icon != ''){
			$return2 =
			'
			<div class="input-group right-addon">						
				'.$input.'		
				<span class="input-group-addon" id="'.$id.'-icon" style="cursor: pointer">'.$icon.'</span>
			</div>
			';
		}
		else{
			$return2 =
			'
					'.$input.'		
			';		
		}	
	$return1 = 
	'
				<div class="form-group form-md-line-input '.$hidden.'">
					'.$return2.'
				</div>
	';

	}


return $return1;			
}

Function selectFormGroup($dataArray) {  //RETURNS form-group div only without the row
		$disabled = '';
		if($this->isDisabled == 'readonly') $disabled = 'disabled';	
		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['type'])? $dataArray['type'] : '';
		$class = isset($dataArray['class'])? $dataArray['class'] : '';
		$placeholder =isset ($dataArray['placeholder'])? $dataArray['placeholder'] : '';
		$label = isset($dataArray['label'])? $dataArray['label'] : '';
		$help = isset($dataArray['help'])? $dataArray['help'] : '';
		$selected = isset($dataArray['value'])? $dataArray['value'] : '';
		$isDoubleColumn = isset($dataArray['isDoubleColumn'])? $dataArray['isDoubleColumn'] : false;
		$extraCode = isset($dataArray['extra'])? $dataArray['extra'] : ''; 
		$options = isset($dataArray['options'])? $dataArray['options'] : ''; 
		$return = '';
		$otherClasses = 'browser-default ';	
		if($selected != '') $otherClasses .= 'edited ';		
		$cols = explode('-', $type);
		if($cols[0] == 'default') $type = '';
		if($cols[0] == 'md') $type = 'form-md-line-input';
		if($cols[0] == 'floating') $type = 'form-md-line-input form-md-floating-label';		

	$options1 = '<option></option>';		
	if(is_array($options)){
		foreach($options as $key => $value) {

					if($key == $selected) $isSelected = 'selected';	
					else $isSelected = '';
						$options1 .= '
												<option value="'.$key.'" '.$isSelected.'>'.$value.'</option>
											';
		}	
	} else{
		$options1 = $options;
	}

	if($cols[0] == 'default'){	
	$return1 = 
	'
				<div class="form-group col-md-12">
					<label class="col-md-'.$cols[1].' control-label text-center">'.$label.'</label>
					<div class="col-md-'.$cols[2].'">
						<select name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" id="'.$id.'" '.$disabled.' '.$extraCode.'>
							'.$options1.'
						</select>
					</div>
				</div>				
	';	
	

	}

	else if($cols[0] == 'floating'){	
	$return1 = 
	'
				<div class="form-group '.$type.' col-md-'.$cols[2].'">
					<div class="input-group right-addon">									
						<select name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" id="'.$id.'" '.$disabled.'  '.$extraCode.'>
							'.$options1.'
						</select>
						<label for="form_control_1"> '.$label.'</label>	
						<span class="help-block">'.$help.'</span>
							<span class="input-group-addon">
							</span>										
					</div>
				</div>
	';	

	}
	else if($cols[0] == 'md'){	
	$icon = isset($dataArray['icon'])? '<i class="'.$dataArray['icon'].'"></i>' : '';			
	$return1 = 
	'
				<div class="form-group form-md-line-input">
					<label class="col-md-'.$cols[1].' control-label text-right" for="'.$id.'">'.$label.'</label>
					<div class="col-md-'.$cols[2].'">
						<div class="input-group">
						<select name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" id="'.$id.'" '.$disabled.'  '.$extraCode.'>
							'.$options1.'
						</select>
						<div class="form-control-focus">
						</div>
						<span class="help-block">'.$help.'</span>												
						<span class="input-group-addon">
						'.$icon.'
						</span>												
					</div>
					</div>
				</div>
	';

	}


return $return1;			
}

Function datepicker($id='', $type = '', $class='', $placeholder='', $label='', $help='', $isDoubleColumn = FALSE, $value='', $name=''){
		$temp = [];
		$temp['inputType'] = $type;
		if ($name == ''){$temp['name'] = $id;} else{$temp['name'] = $name;}
		$temp['id'] = $id;
		$temp['type'] = $type;
		$temp['class'] = $class;
		$temp['placeholder'] = $placeholder;
		$temp['label'] = $label;
		$temp['help'] = $help;
		$temp['isDoubleColumn'] = $isDoubleColumn;
		$temp['value'] = $value;

		$return = $this->datepickerFromArray($temp);	
		return $return;	

}

Function datepickerFromArray2($dataArray){
	$disabled = '';
		if($this->isDisabled == 'readonly') $disabled = 'disabled';	
		$inputType = isset($dataArray['inputType'])? $dataArray['inputType'] : 'date';
		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['type'])? $dataArray['type'] : '';
		$class = isset($dataArray['class'])? $dataArray['class'] : '';
		$placeholder =isset ($dataArray['placeholder'])? $dataArray['placeholder'] : '';
		$label = isset($dataArray['label'])? $dataArray['label'] : '';
		$help = isset($dataArray['help'])? $dataArray['help'] : '';
		$value = isset($dataArray['value'])? $dataArray['value'] : '';
		$isDoubleColumn = isset($dataArray['isDoubleColumn'])? $dataArray['isDoubleColumn'] : false;
		$extraCode = isset($dataArray['extra'])? $dataArray['extra'] : ''; 
		$return = '';
		$otherClasses = '';		
		$cols = explode('-', $type);
		if($cols[0] == 'default') $type = '';
		if($cols[0] == 'md') $type = 'form-md-line-input';
		if($value != '') $otherClasses .= 'edited ';

		$return1 = 
		'
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Default Datepicker</label>
                                                <div class="col-md-3">
                                                    <input class="form-control form-control-inline input-medium datepicker" size="16" type="text" value="" />
                                                    <span class="help-block"> Select date </span>
                                                </div>
                                            </div>		
		';
	return $return1;
}


Function datepickerFromArray($dataArray){
		$inputType = (isset($dataArray['inputType'] )&& $dataArray['inputType'] != '')? $dataArray['inputType'] : 'text';

		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['type'])? $dataArray['type'] : '';
		$class = isset($dataArray['class'])? $dataArray['class'] : '';
		$placeholder =isset ($dataArray['placeholder'])? $dataArray['placeholder'] : '';
		$label = isset($dataArray['label'])? $dataArray['label'] : '';
		$help = isset($dataArray['help'])? $dataArray['help'] : '';
		$hidden = (isset($dataArray['hidden']) && $dataArray['hidden'] == true) ? 'hidden' : '';

		$value = (isset($dataArray['value']) && $dataArray['value'] != '' && $dataArray['value'] !=  '0000-00-00')? 'value="'.$dataArray['value'].'"' : '';
		$isDoubleColumn = (isset($dataArray['isDoubleColumn']) && $dataArray['isDoubleColumn'] != '')? $dataArray['isDoubleColumn'] : false;
		$extraCode = isset($dataArray['extra'])? $dataArray['extra'] : ''; 
		$autoComplete = (isset($dataArray['autoComplete']) && $dataArray['autoComplete'] != '')? $dataArray['autoComplete'] : 'off'; 
		$return = '';
		$otherClasses = '';		
		$cols = explode('-', $type);
		if($cols[0] == 'default') $type = '';
		if($cols[0] == 'md') $type = 'form-md-line-input';
		if($value != '') $otherClasses .= 'edited ';
		$icon = isset($dataArray['icon'])? '<i class="'.$dataArray['icon'].'"></i>' : '';
	if($cols[0] == 'md'){

		$input = 
		'
			<input type="'.$inputType.'" class="form-control '.$class.' '.$otherClasses.' date-picker" name="'.$name.'" id="'.$id.'" placeholder="'.$placeholder.'" '.$value.' '.$this->isDisabled.' '.$extraCode.' autocomplete="'.$autoComplete.'">
			<div class="form-control-focus"></div>
			<span class="help-block">'.$help.'</span>		
		';
		
		if($icon != ''){
			$return2 =
			'
				<div class="input-group">
					'.$input.'
					<span class="input-group-addon">'.$icon.'</span>												
				</div>	
			';
		}
		else{
			$return2 =
			'
					'.$input.'		
			';		
		}
		
		$return1 = 
		'
										<div class="form-group '.$type.' '.$hidden.'">
											<label class="col-md-'.$cols[1].' control-label '.$this->labelFontClass.' text-right" for="'.$id.'">'.$label.'</label>
											<div class="col-md-'.$cols[2].'">
												'.$return2.'
											</div>
										</div>	
		';
}
	if($cols[0] == 'floating'){
		$type = 'form-md-line-input form-md-floating-label';
		
		$input = 
		'
			<input type="'.$inputType.'" name="'.$name.'" class="form-control date-picker '.$class.' '.$otherClasses.'" '.$value.' id="'.$id.'" '.$this->isDisabled.' '.$extraCode.'>
			<label for="'.$id.'" class="'.$this->labelFontClass.'">'.$icon.' '.$label.'</label>
			<span class="help-block">'.$help.'</span>			
		';
		
		if($icon != ''){
			$return2 =
			'
			<div class="input-group right-addon">						
				'.$input.'		
				<span class="input-group-addon">'.$icon.'</span>
			</div>
			';
		}
		else{
			$return2 =
			'
					'.$input.'		
			';		
		}
		
		$return1 = 
		'
		<div class="form-group '.$type.'">
			'.$return2.'
		</div>		
		
		';
	}

	if($cols[0] == 'default'){
		$input = 
		'
		<input type="'.$inputType.'" name="'.$name.'" class="form-control date-picker '.$class.' '.$otherClasses.'" '.$value.' id="'.$id.'" '.$this->isDisabled.' '.$extraCode.'>		
		';
		
		if($icon != ''){
			$return2 =
			'
			<div class="input-icon right">
				'.$icon.'
				'.$input.'
			</div>
			';
		}
		else{
			$return2 =
			'
					'.$input.'		
			';		
		}
		
	$return1 = 
	'
					<div class="form-group">
						<label class="col-md-'.$cols[1].' control-label text-right">'.$label.'</label>
						<div class="col-md-'.$cols[2].'">
						'.$return2.'
						</div>
					</div>					
	
	';
}

return $return1;		
}

Function checkbox($id='', $label='', $extraCode='', $name='', $color='has-success') {
	$name = ($name == '')?$id:$name;
	$return = 
	'
		<div class="md-checkbox '.$color.'">
			<input type="checkbox" id="'.$id.'" name="'.$name.'" class="md-check" '.$extraCode.'>
			<label for="'.$id.'" class="text-right">
			<span class="inc"></span>
			<span class="check"></span>
			<span class="box"></span>
			'.$label.' </label>
		</div>	
	';
	
	return $return;
}


Function checkboxFromArray($dataArray) {
		$inputType = isset($dataArray['inputType'])? $dataArray['inputType'] : 'text';
		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['type'])? $dataArray['type'] : '';
		$class = isset($dataArray['class'])? $dataArray['class'] : '';
		$container_class = isset($dataArray['container-class'])? $dataArray['container-class'] : '';
		$placeholder =isset ($dataArray['placeholder'])? $dataArray['placeholder'] : '';
		$label = isset($dataArray['label'])? $dataArray['label'] : '';
		$help = isset($dataArray['help'])? $dataArray['help'] : '';
		$color = isset($dataArray['color'])? $dataArray['color'] : '';
		$disabled = isset($dataArray['disabled'])? 'disabled="'.$dataArray['disabled'].'"' : '';
		$value = isset($dataArray['value'])? 'value="'.$dataArray['value'].'"' : '';
		$checked = (isset($dataArray['checked']) && ($dataArray['checked'] == true || $dataArray['checked'] == 1))? 'checked' : '';
		$isDoubleColumn = isset($dataArray['isDoubleColumn'])? $dataArray['isDoubleColumn'] : false;
		$extraCode = isset($dataArray['extra'])? $dataArray['extra'] : ''; 
		$autoComplete = isset($dataArray['autoComplete'])? $dataArray['autoComplete'] : 'off'; 
		$return = '';
		$otherClasses = '';		
		$cols = explode('-', $type);
		if($cols[0] == 'default') $type = '';
		if($cols[0] == 'md') $type = 'form-md-line-input';
		if($checked != '' && $checked != '0') $extraCode .= ' checked';
		$colorCode = '';
		 if ($color != '') $colorCode = 'style="border-color:#'.$color.'"';
	$return = 
	'
		<div class="md-checkbox '.$container_class.'">
			<input type="checkbox" id="'.$id.'" name="'.$name.'" class="md-check '.$class.' '.$otherClasses.'" '.$extraCode.' '.$disabled.' '.$value.'>
			<label for="'.$id.'" class="text-right"  style="color:#'.$color.'">
			<span class="inc" '.$colorCode.'></span>
			<span class="check"  '.$colorCode.'></span>
			<span class="box"  '.$colorCode.'></span>
			'.$label.' </label>
		</div>	
	';
	
	return $return;
}


Function textareaFromArray($dataArray) {
		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['type'])? $dataArray['type'] : '';
		$class = isset($dataArray['class'])? $dataArray['class'] : '';
		$placeholder =isset ($dataArray['placeholder'])? $dataArray['placeholder'] : '';
		$label = isset($dataArray['label'])? $dataArray['label'] : '';
		$help = isset($dataArray['help'])? $dataArray['help'] : '';
		$rows = isset($dataArray['rows'])? $dataArray['rows'] : '4';
		$hidden = (isset($dataArray['hidden']) && $dataArray['hidden'] == true) ? 'hidden' : '';	
		$value = isset($dataArray['value'])? $dataArray['value'] : '';
		$isDoubleColumn = isset($dataArray['isDoubleColumn'])? $dataArray['isDoubleColumn'] : false;
		$extraCode = isset($dataArray['extra'])? $dataArray['extra'] : ''; 
		$autoComplete = isset($dataArray['autoComplete'])? $dataArray['autoComplete'] : 'off'; 
		$return = '';
		$otherClasses = '';		
		$cols = explode('-', $type);
		if($cols[0] == 'default') $type = '';
		if($cols[0] == 'md') $type = 'form-md-line-input';
		if($value != '') $otherClasses .= 'edited';

	if($cols[0] == 'md'){
	$icon = isset($dataArray['icon'])? '<i class="'.$dataArray['icon'].'"></i>' : '';			
	$return1 = 
	'
									<div class="form-group form-md-line-input '.$hidden.'">
										<label class="col-md-'.$cols[1].' control-label text-right" for="'.$id.'">'.$label.'</label>
										<div class="col-md-'.$cols[2].'">
											<textarea class="form-control" name="'.$name.'" id="'.$id.'" rows="'.$rows.'" '.$extraCode.'>'.$value.'</textarea>
											<div class="form-control-focus">
											</div>
											<span class="help-block">'.$help.'</span>												
										</div>
									</div>
	';
	}
	if($cols[0] == 'floating'){
		$type = 'form-md-line-input form-md-floating-label';
		$return1 = 
		'
						<div class="form-group form-md-line-input form-md-floating-label col-md-'.$cols[2].' '.$hidden.'">
								
							<textarea name="'.$name.'" class="form-control '.$otherClasses.'"  id="'.$id.'" '.$this->isDisabled.' rows="'.$rows.'" '.$extraCode.'>'.$value.'</textarea>
							<label for="'.$id.'" class="'.$this->labelFontClass.'">'.$label.'</label>
							<span class="help-block">'.$help.'</span>								
						</div>
		
		';
	}

	if($cols[0] == 'default'){
	$type = 'form-md-line-input form-md-floating-label';
	$icon = isset($dataArray['icon'])? '<i class="'.$dataArray['icon'].'"></i>' : '';	
	$return1 = 
	'
			<div class="form-group '.$hidden.'">
				<label class="col-md-'.$cols[1].' control-label text-right" for="'.$id.'">'.$label.'</label>
			   <div class="col-md-'.$cols[2].'">
					<textarea class="form-control" name="'.$name.'" id="'.$id.'" rows="'.$rows.'" '.$extraCode.'>'.$value.'</textarea>
				</div>
			</div>	
	
	';
}


return $return1;									
}

Function textarea($id='', $type = '', $class='', $placeholder='', $label='', $help='', $rows='3', $value='', $isDoubleColumn = false, $name='') {
	$cols = explode('-', $type);
	if($value != '') {$edited = 'edited';} else {$edited = '';}
	if ($name == '') {$name = $id;}	
	if($col1 = 'floating') {
		$return1 = '
						<div class="form-group form-md-line-input form-md-floating-label col-md-'.$cols[2].'">
						
							<div class="input-group right-addon">
								
							<textarea name="'.$name.'" class="form-control autosizeme '.$edited.'" rows="'.$rows.'" id="'.$id.'" '.$this->isDisabled.'>'.$value.'</textarea>
							<label for="form_control_1" class="'.$this->labelFontClass.'">'.$label.'</label>
							<span class="help-block">'.$help.'</span>												
							<span class="input-group-addon">

							</span>	
			
						</div>
				
						</div>
					';
	}
	else {
		$return1 = 
		'
		<div class="form-group form-md-line-input col-md-'.$cols[2].'">
		<label class="col-md-'.$cols[1].' control-label '.$this->labelFontClass.'" for="'.$id.'">'.$label.'</label>
			<div class="col-md-'.$cols[2].'">
				<textarea name="'.$name.'" class="form-control autosizeme" rows="'.$rows.'" id="'.$id.'" placeholder="'.$placeholder.'" '.$this->isDisabled.'>'.$value.' </textarea>
				<div class="form-control-focus">
				</div>
			</div>
		</div>	
		';		
	}
	
	if($isDoubleColumn) {
		$return = 
		'
								<div class="row">									
									'.$return1.'

		';
		

		$this->isDoubleColumn = TRUE;
	}		
	else {
		if($this->isDoubleColumn){

		$return = 
			'
									'.$return1.'
								</div>

			';
				$this->isDoubleColumn = FALSE;			
		} else {
		$return = 
		'
								<div class="row">									
									'.$return1.'
									</div>

		';

		}
	}
		
	return $return;
}

Function input_username($type = 'floating-3-9', $isDoubleColumn = FALSE){
	$return = $this->input('','username',$type,'','Please enter the username','UserName','', $isDoubleColumn);
	return $return;
}


Function input_email($type = 'md-3-9', $isDoubleColumn = FALSE, $value=''){
	$return = $this->input('','email',$type,'','Please enter the email','Email','', $isDoubleColumn, $value);
	return $return;	

}


Function input_password($type = 'md-3-9', $isDoubleColumn = FALSE){
	$return = $this->input('password','password',$type,'','Please enter the Password','Password','', $isDoubleColumn);	
	return $return;	

}

Function input_name($type = 'md-3-9', $isDoubleColumn = FALSE, $value=''){
	$return = $this->input('','name',$type,'','Please enter the Name','Name','', $isDoubleColumn);	
	return $return;	

}

Function input_firstname($type = 'md-3-9', $isDoubleColumn = FALSE, $value=''){
	$return = $this->input('','firstname',$type,'','Please enter the First Name','First Name','', $isDoubleColumn, $value);	
	return $return;	

}

Function input_lastname($type = 'md-3-9', $isDoubleColumn = FALSE, $value=''){
	$return = $this->input('','lastname',$type,'','Please enter the Last Name','Last Name','', $isDoubleColumn, $value);	
	return $return;	

}

Function input_phone($type = 'md-3-9', $isDoubleColumn = FALSE, $value=''){
	$return = $this->input('','phone',$type,'','Please enter the Phone Number','Phone Number','', $isDoubleColumn, $value);	
	return $return;	

}

function display_static_info($data,$titles,$col='col-md-5',$keyVal='4'){
	$temp = 
	'
	<div class="'.$col.' display-info">	
	';	
	$num = sizeof($data);
	$smartNum = ceil($num/2);
	$smartNum2 = $num - $smartNum;
	$keys = array_keys($data);
	if($num > 5){
			
		for($i=0;$i<$smartNum;$i++){
			$temp .= 
			'
				<div class="row static-info">
					<div class="col-md-'.$keyVal.' name">
						 '.$titles[$keys[$i]].':
					</div>
					<div class="col-md-'. (12 - $keyVal).' value float-left wrap">
						 '.$data[$keys[$i]].' 
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
		<div class="'.$col.' display-info">	
		';	


		for($i=$smartNum;$i<$num;$i++){
			$temp .= 
			'
				<div class="row static-info">
					<div class="col-md-'.$keyVal.' name">
						 '.$titles[$keys[$i]].':
					</div>
					<div class="col-md-'. (12 - $keyVal).' value float-left wrap">
						 '.$data[$keys[$i]].' 
					</div>
				</div>

			';			
			
		}
		
		$temp .= 
		'
		</div>

		';	
	}
	else{
		$temp .= 
		'
		<div class="'.$col.' display-info">	
		';	


		for($i=0;$i<$num;$i++){
			$temp .= 
			'
				<div class="row static-info">
					<div class="col-md-7 name">
						 '.$titles[$keys[$i]].':
					</div>
					<div class="col-md-5 value text-right">
						 '.$data[$keys[$i]].' 
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


function getNestableList($data){
    $row = $data;
    if(!isset($data['__child_list__'])){
        $data['__child_list__'] = ''; 
    }
    $return['jsFunction'] = '';
    $return['post'] = $data;    
    if($data['__list_type__'] == 'primary'){
        {
        //$delFunc = "Delete('post_group','$row[id]',this,'dd-item-primary-$row['id']')";
        $delFunc = "CommonFunc2Confirmation({'action':'delete-nestable','id':'$row[id]','module':'$data[module]','removeElement':'dd-item-primary-$row[id]'})"; 
        $editFunc = "GetForm('edit-form','$row[id]','$data[module]','modalForm')"; 
        $secMenuFunc = "CommonFunc2({'type':'get-secondary-form','return-action':'nestable-list','id':'$row[id]','module':'post_group_post','ajax-container':'modalForm'})";                         
        $return['newrow'] = 
        '
            <li class="dd-item" data-id="'.$row['id'].'" id="dd-item-primary-'.$row['id'].'">
                <div class="dd-handle">
                <span class="handle"><i class="fa fa-arrows font-green"></i>'.$row['name'].'</span>           
                </div>
                <div class="col-md-6" style="position:absolute;right:0px;top:5px;"> 
                    <span class="text-right" style="float:right"><a href="javascript:;" class="btn btn-sm red btnDelete" onClick="'.$delFunc.'">Delete</a></span>
                    <span class="text-right" style="float:right" ><a href="#modalForm" data-toggle="modal" class="btn btn-sm yellow" onClick="'.$editFunc.'">Edit</a></span>
                    <span class="text-right" style="float:right"><a href="#modalForm" data-toggle="modal" class="btn btn-sm green" onClick="'.$secMenuFunc.'">Add Child</a></span>

                </div> 
                <ol class="dd-list" id="dd-list-'.$row['id'].'">
                '.$data['__child_list__'].'
                </ol>                                           
            </li>    
        ';                                   
            $return['jsFunction'] .= "$('#nestable-main-ol').append(value.newrow)";    
        }    
    }
    else if($data['__list_type__'] == 'secondary'){
        {
            $delFunc = "CommonFunc2Confirmation({'action':'delete-nestable','id':'$row[id]','module':'$data[module]','removeElement':'dd-item-secondary-$row[id]'})";
            $editFunc = "GetForm('edit-form','$row[id]','$data[module]','modalForm')";
            $return['newrow'] = 
            '
                <li class="dd-item" data-id="'.$row['id'].'" id="dd-item-secondary-'.$row['id'].'">
                    <div class="dd-handle"> 
                    <span class="handle"><i class="fa fa-arrows font-green"></i>'.$row['name'].'</span> 

                    </div>
                    <div class="col-md-6" style="position:absolute;right:0px;top:5px;"> 
                    <span class="text-right" style="float:right"><a href="javascript:;" class="btn btn-sm red btnDelete" onClick="'.$delFunc.'">Delete</a></span>
                    <span class="text-right" style="float:right" ><a href="#modalForm" data-toggle="modal" class="btn btn-sm yellow" onClick="'.$editFunc.'">Edit</a></span>

                    </div>
                </li>            
            ';                                      
            $return['jsFunction'] .= "$('#dd-list-${row['post_group_id']}').append(value.newrow)";           
        }    
    }
        

    return $return;    
    
}


function getImageBox($data){
    $unique_id = $data['unique_id'];
        { //// Image
        if(!isset($data['image']) || $data['image'] == '' || $data['image'] == 'NULL') {
            $image_box = 
            '
                                  
                    <div class="col-md-6 paired-img-btn-holder main-image-has-button text-center">
                        <input type="hidden" class="unique-id" value="'.$unique_id.'"> 
                        <button type="button" class="btn yellow" data-toggle="modal" href="#full" onClick="SetCurrentImageFunction(\'CommonFuncImage() \');SetCurrentHolder(\''.$unique_id.'\')" id="btn-'.$unique_id.'-image"><i class="fa fa-plus"></i>Image</button>
                        <div class="col-md-12 new-content paired-image-holder hidden" id="'.$unique_id.'-image-holder">
                        </div> 		
                    </div> 		
            ';  
        } 
        else{
            
            $image_box = 
            '
                        
                    <div class="col-md-6 paired-img-btn-holder text-center">  
                        <input type="hidden" class="unique-id" value="'.$unique_id.'">                   
                        <button type="button" class="btn yellow hidden" data-toggle="modal" href="#full" onClick="SetCurrentImageFunction(\'CommonFuncImage() \');SetCurrentHolder(\''.$unique_id.'\')" id="btn-'.$unique_id.'-image"><i class="fa fa-plus"></i>Image</button>
                            <div class="col-md-12 new-content paired-image-holder" id="'.$unique_id.'-image-holder">
                                <div class="task-config">
                                    <div class="action-buttons">
                                        <a class="btn btn-xs yellow"  data-toggle="modal" href="#full" onClick="SetCurrentImageFunction(\'CommonFuncImage() \');SetCurrentHolder(\''.$unique_id.'\')"><i class="fa fa-edit"></i> Change </a>
                                        <a class="btn btn-xs yellow"  data-toggle="modal" href="#full" onClick="EditImage(\''.$data['image_name'].'\');SetCurrentHolder(\''.$unique_id.'\')"><i class="fa fa-edit"></i> Edit </a>
                                        <a class="btn btn-xs red" href="javascript:;" onClick="DeletePairedImage(this,\''.$data['id'].'\')"><i class="fa fa-trash-o"></i> Delete </a>
                                        <a class="btn btn-xs default btnImgMinimize" href="javascript:;" ><i class="fa fa-minus"></i> Minimize </a>
                                        <a class="btn btn-xs default btnImgMaximize" href="javascript:;" ><i class="fa fa-plus"></i> Maximize </a>
                                    </div>
                                </div>	                             
                                <img src="'.$frontend_site_path.'uploads/'.$data['image_name'].'" alt="" class="img-responsive img100">
            
                           
                            </div>  	
                        </div>  	
            ';              
        }        

        }

        return $image_box;
}

function getSummerNoteBox($data){
    { ////Paragraph
    $content = isset($data['content'])?stripslashes($data['content']):'';
        $paragraph_box = 
    '
                    <div class="col-md-6 new-content">
                        '.$hidden_inputs.'
                        <div class="form-group">
                            <div class="col-md-12">
                                <div id="'.$unique_id.'">'. $content .'</div>
                            </div>
                        </div>
                    </div>	
    
    '; 
    }    
    
    return $content;
}


function getImageEditModal($data){
    
    {$single_image_panel = 
    '
    <div class="row">
        <span class="" id="image-edit-error-messages"></span>
        <div class="text-center">
        <!-- This is the form that our event handler fills -->
        <form method="post" id="imageForm" class="" onsubmit="return false" enctype="multipart/form-data">
            <div class="form-group text-center">
                <label class="control-label">Choose Image<span class="required">* </span></label>
                <div class="input-icon">
                    <i class="fa"></i>
                    <input type="file" class="btn green" style="margin:0 auto;" name="file" id="file" onchange="DisplaySelectedImage(this)">
                </div>
            </div>
        </form>
        <form method="post" id="cropForm" class="" onsubmit="return false">									
            <div class="form-actions pull-right">
                <input type="hidden" id="crop_x" name="x"/>
                <input type="hidden" id="crop_y" name="y"/>
                <input type="hidden" id="crop_w" name="w"/>
                <input type="hidden" id="crop_h" name="h"/>
                <input type="hidden" id="image_path" name="image_path"/>
            </div>
        </form>	
        </div>
        <div class="text-center"  id="preview">	
        <div class="text-center" style="width:50%;padding-right:50px;padding-left:50px;margin:0 auto;" id="image-holder">
        <!-- This is the image were attaching Jcrop to -->
        </div>	
        </div>								

    <div id="imageEditToolbar" style="position:fixed;top:10px;z-index:999999;" class="col-md-11 text-center">
        <div class="form-group inline">
            <div class="input-inline">
                <div class="input-group">
                    <span class="input-group-addon">
                    <i class="fa fa-arrows-h"></i>
                    </span>
                    <input type="text" name="__image_width" id="__image_width" class="form-control" placeholder="Width" disabled>
                </div>
            </div>
        </div>

        <div class="form-group inline">
            <div class="input-inline">
                <div class="input-group">
                    <span class="input-group-addon">
                    <i class="fa fa-arrows-v"></i>
                    </span>
                    <input type="text" name="__image_height" id="__image_height" class="form-control" placeholder="Height" disabled>
                </div>
            </div>
        </div>
        <div class="form-group inline">
            <div class="input-inline">
                <div class="input-group">
                    <span class="input-group-addon">
                    <i class="fa fa-arrows-v"></i>
                    </span>
                    <input type="text" name="__image_size" id="__image_size" class="form-control" placeholder="Size" disabled>
                </div>
            </div>
        </div>
                                

    <button type="button" class="btn orange hidden" id="__btn_upload" onclick="Upload()">Upload/Edit</button>
    <button class="btn red hidden" id="__btn_save_use" data-dismiss="modal" onclick="ExecuteFunction(\'\')">SAVE AND USE</button>
    <button class="btn yellow hidden" id="__btn_crop" onclick="InitCrop()" >CROP</button>							
    <button class="btn blue hidden" id="__btn_crop_use" onclick="CropAndUpload()" >CROP NOW</button>
    <button class="btn btn-circle btn-icon-only yellow hidden" id="__btn_rotate_270" onclick="Rotate(90)" ><i class="fa fa-rotate-left"></i></button>
    <button class="btn yellow hidden" id="__btn_undo" onclick="Undo()" >UNDO</button>
    <button class="btn btn-circle btn-icon-only yellow hidden" id="__btn_rotate_90" onclick="Rotate(-90)" ><i class="fa fa-rotate-right"></i></button>
    <button class="btn yellow hidden" id="__btn_undo" onclick="Undo()" >UNDO</button>
    <div class="dropdown hidden" id="__btn_resize">
    <a href="javascript:;" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
    Resize <i class="fa fa-angle-down"></i>
    </a>
    <div class="dropdown-menu hold-on-click" style="width:250px; padding:20px;">
    <form role="form" id="__resize_form" onsubmit="return false">
    <div class="form-body">
        <div class="form-group form-md-radios">
            <label>Resize By</label>
            <div class="md-radio-inline">
                <div class="md-radio has-warning">
                    <input type="radio" id="__radio_pixel" name="__radio_by" class="md-radiobtn" value="pixel" checked>
                    <label for="__radio_pixel">
                    <span></span>
                    <span class="check"></span>
                    <span class="box"></span>
                    Px </label>
                </div>
                <div class="md-radio has-warning">
                    <input type="radio" id="__radio_percent" name="__radio_by" class="md-radiobtn" value="percent">
                    <label for="__radio_percent">
                    <span></span>
                    <span class="check"></span>
                    <span class="box"></span>
                    % </label>
                </div>
            </div>
        </div>
        <div class="form-group">									
            <div class="md-checkbox has-warning">
                <input type="checkbox" id="__cons_prop" name="__cons_prop" class="md-check" >
                <label for="__cons_prop">
                <span></span>
                <span class="check"></span>
                <span class="box"></span>
                Constraint Proportion </label>
            </div>														
        </div>														
                                            
        <div class="form-group">
            <div class="input-inline">
                <div class="input-group">
                    <span class="input-group-addon">
                    <i class="fa fa-arrows-h"></i>
                    </span>
                    <input type="number" name="__image_resize_width" id="__image_resize_width" class="form-control" placeholder="Width" style="width:90px;">
                    <input type="hidden" name="__image_resize_width_post" id="__image_resize_width_post">
                    <span class="input-group-addon resize_by_addon_width">
                    Pixels
                    </span>
                </div>
                    <span class="help-block" id="__image_resize_width_label"></span>											
            </div>
        </div>
                
        <div class="form-group">
            <div class="input-inline">
                <div class="input-group">
                    <span class="input-group-addon">
                    <i class="fa fa-arrows-v"></i>
                    </span>
                    <input type="number" name="__image_resize_height" id="__image_resize_height" class="form-control" placeholder="Height" style="width:90px;">
                    <input type="hidden" name="__image_resize_height_post" id="__image_resize_height_post">
                    <span class="input-group-addon resize_by_addon_height">
                    Pixels
                    </span>	
                </div>
                    <span class="help-block" id="__image_resize_height_label"></span>											
            </div>
        </div>										
        <button class="btn btn-warning dropdown-toggle" onclick="Resize()" >Resize</button>
        </div>
        </form>
        </div>
        </div>		
        </div>
    </div>              
    ';
    }

    {$multiple_image_panel = 
    '
    <div class="row">
        <div class="text-center">
            <!-- This is the form that our event handler fills -->
            <form method="post" id="multipleImageForm" class="" onsubmit="return false" enctype="multipart/form-data">
                <div class="form-group text-center">
                    <label class="control-label">Choose Images<span class="required">* </span></label>
                    <div class="input-icon">
                        <i class="fa"></i>
                        <input type="file" class="btn green" style="margin:0 auto;" name="file[]" id="file" onchange="" multiple>
                    </div>
                </div>
                <button class="btn btn-lg green" id="ultiple-upload-button" onClick="UploadMultiple()">UPLOAD</button>
            </form>
        </div>
    </div>      
    ';
    }
    
    {$nav_tabs = 
    '
			<div class="tabbable-custom ">
                <ul class="nav nav-tabs ">
                    <li class="active">
                        <a href="#single_image_tab_pane" data-toggle="tab" aria-expanded="tue">
                        UPLOAD AND EDIT </a>
                    </li>
                    <li class="">
                        <a href="#multiple_image_tab_pane" data-toggle="tab" aria-expanded="false">
                        UPLOAD MULTIPLE IMAGES </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="single_image_tab_pane">  
                    '.$single_image_panel.'
                    </div>
                    <div class="tab-pane active" id="multiple_image_tab_pane">  
                    '.$multiple_image_panel.'
                    </div>
                </div>
            </div>        
    ';
    }


    if(isset($data['one_image']) && $data['one_image']) {
        $modal_content = $single_image_panel;
    }
    else if(isset($data['multiple_image']) && $data['multiple_image']){
        $modal_content = $nav_tabs;
    }
    
    {$modal = 
    '
    <div class="modal" id="full" tabindex="-1" role="dialog" aria-hidden="true" style="top:0px">
        <div class="modal-dialog modal-full">
            <div class="modal-content" id="image-modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-md-7">
                            <h4 class="modal-title" id="img-modal-title">Upload Image</h4>
                        </div>
                        <div class="col-md-5 text-right">
                        <button type="button" class="btn red" data-dismiss="modal" onclick="ResetAll()">Close</button>	
                        </div>				
                    </div>
                </div>
                <div class="modal-body">
                    '.$modal_content.'
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    '; 
    }
    $return['html'] = $modal;
    $return['jsFunction'] = "";
    return $return;  
}
}


?>