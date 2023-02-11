<?php
class FormV2 {
public $isDoubleColumn = FALSE;
public $labelFontClass = 'color-primary-custom';
public $labelOrientation = 'text-center';
public $form_group_type = 'default-12-12';
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

Function advancedDateTimeFromArray($dataArray) {
		$inputType = (isset($dataArray['inputType'] )&& $dataArray['inputType'] != '')? $dataArray['inputType'] : 'text';

		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['form_group_type'])? $dataArray['form_group_type'] : $this->form_group_type;
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
                            <label class="control-label col-md-12 '.$this->labelFontClass.' '.$this->labelOrientation.'">'.$label.'</label>
                            <div class="col-md-12" style="">
                                <div class="input-group date '.$time_type.'" data-date="'.$dataArray['value'].'" id="datetime-'.$id.'" data-date-start-date="+0d">
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
		$type = isset($dataArray['form_group_type'])? $dataArray['form_group_type'] : $this->form_group_type;
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
        
        $inputNew = 
        '
        <div class="form-group form-md-line-input">
            <div class="input-group input-icon right">
                <span class="input-group-addon">
                    <i class="fa fa-envelope font-purple"></i>
                </span>
                <i class="fa fa-exclamation tooltips" data-original-title="Invalid email." data-container="body"></i>
                <input id="emaila" class="input-error form-control" type="text" value=""> 
                <label for="emaila">Input with Icon</label>
                <span class="input-group-addon">
                    <i class="fa fa-envelope font-purple"></i>
                </span>
            </div>
        </div>          
        ';
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
                                                <label class="col-md-'.$cols[1].' control-label '.$this->labelFontClass.' '.$this->labelOrientation.'" for="'.$id.'">'.$label.'</label>
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
                <input type="'.$inputType.'" name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" '.$value.' id="'.$id.'" '.$this->isDisabled.' '.$extraCode.'  onClick="'.$onClick.'" autocomplete="'.$autoComplete.'">
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
            <input type="'.$inputType.'" name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" '.$value.' id="'.$id.'" '.$this->isDisabled.' '.$extraCode.'  onClick="'.$onClick.'" autocomplete="'.$autoComplete.'">		
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
                            <label class="col-md-'.$cols[1].' control-label '.$this->labelFontClass.' '.$this->labelOrientation.'">'.$label.'</label>
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
            <input type="'.$inputType.'" name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" '.$value.' id="'.$id.'" '.$this->isDisabled.' '.$extraCode.' autocomplete="'.$autoComplete.'">		
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

Function multipleSelectFromArray($dataArray) {
		$disabled = '';
		if($this->isDisabled == 'readonly') $disabled = 'disabled';	
		$name = isset($dataArray['name'])? $dataArray['name'].'[]' : $dataArray['id'].'[]';
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['form_group_type'])? $dataArray['form_group_type'] : $this->form_group_type;
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
    $selectedArray = array();
    if(!is_array($selected)){
        $selectedArray = explode(''.$dataArray['seperator'].'',$selected);
        $selectedArray = array_filter($selectedArray);
    }     
	if(is_array($options)){
		foreach($options as $key => $val) {

					if(in_array($key,$selectedArray)) $isSelected = 'selected';	
					else $isSelected = '';
						$options1 .= '
												<option value="'.$key.'" '.$isSelected.'>'.$val.'</option>
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
			<select name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" id="'.$id.'" '.$disabled.' '.$extraCode.' multiple>
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
        $label_left_right = 'text-left';
        if($cols[0] == 'defaultlabelleft'){
            $label_left_right = 'text-left';
        }
        if($cols[0] == 'defaultlabelcenter'){
            $label_left_right = 'text-center';
        }
	$return1 = 
	'
				<div class="form-group '.$type.' col-md-12">
					<label class="col-md-'.$cols[1].' control-label '.$label_left_right.' '.$this->labelFontClass.' '.$this->labelOrientation.'">'.$label.'</label>
					<div class="col-md-'.$cols[2].'">
					'.$return2.'
					</div>
				</div>				
	';	
	

	}

	else if($cols[0] == 'floating'){	
		$input = 
		'
			<select name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" id="'.$id.'" '.$disabled.' '.$extraCode.' multiple>
				'.$options1.'
			</select>
			<label for="form_control_1" clas="color-primary-custom"> '.$label.'</label>	
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
			<label for="'.$id.'" class="col-md-'.$cols[1].' control-label '.$this->labelFontClass.' '.$this->labelOrientation.'"> '.$label.'</label>	
			<div class="col-md-'.$cols[2].'">
				<select name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'" id="'.$id.'" '.$disabled.' '.$extraCode.' multiple>
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

function selectFromArray($dataArray) {
		$disabled = '';
		if($this->isDisabled == 'readonly') $disabled = 'disabled';	
		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['form_group_type'])? $dataArray['form_group_type'] : $this->form_group_type;
		$class = isset($dataArray['class'])? $dataArray['class'] : '';
		$placeholder =isset ($dataArray['placeholder'])? $dataArray['placeholder'] : '';
		$label = isset($dataArray['label'])? $dataArray['label'] : '';
		$help = isset($dataArray['help'])? $dataArray['help'] : '';
		$selected = isset($dataArray['value'])? $dataArray['value'] : NULL;
		$isDoubleColumn = isset($dataArray['isDoubleColumn'])? $dataArray['isDoubleColumn'] : false;
		$extraCode = isset($dataArray['extra'])? $dataArray['extra'] : ''; 
		$options = isset($dataArray['options'])? $dataArray['options'] : ''; 
		$hidden = (isset($dataArray['hidden']) && $dataArray['hidden'] == true) ? 'hidden' : '';		
		$has_icons = (isset($dataArray['has_icons']) && $dataArray['has_icons']) ? true : false;       
		$extraCode .= (isset($dataArray['live_search']) && $dataArray['live_search']) ? ' data-live-search="true"' : '';		

		$return = '';
		$otherClasses = '';	
		$form_group_class = '';	
		if(isset($dataArray['value']) && $dataArray['value'] != '') $otherClasses .= ' edited';	
		$cols = explode('-', $type);
		if($cols[0] == 'default') $type = '';
		if($cols[0] == 'defaultlabelleft') $type = '';
		if($cols[0] == 'defaultlabelcenter') $type = '';
		if($cols[0] == 'md') $type = 'form-md-line-input';
		if($cols[0] == 'floating') $type = 'form-md-line-input form-md-floating-label';		

	$options1 = '<option></option>';		
	if(is_array($options)){
        if($has_icons){ /// If has icons
            $otherClasses .= ' bs-select';
            foreach($options as $key => $value){
                if($key == $selected && $selected !== NULL) $isSelected = 'selected';	
                else $isSelected = '';
                $options1 .= 
                '
                    <option value="'.$key.'" '.$isSelected.' data-icon="'.$value[0].'">'.$value[1].'</option>
                ';
            }             
        }
        else{
            foreach($options as $key => $value){
                if($key == $selected && $selected !== NULL) $isSelected = 'selected';	
                else $isSelected = '';
                $options1 .= 
                '
                    <option value="'.$key.'" '.$isSelected.'>'.$value.'</option>
                ';
            }              
        }
	} 
    else
	{
		$options1 = $options;
	}

	$icon = (isset($dataArray['icon']) && $dataArray['icon'] != '')? '<i class="'.$dataArray['icon'].'"></i>' : '';	
	if($cols[0] == 'default' || $cols[0] == 'defaultlabelleft' || $cols[0] == 'defaultlabelcenter'){      
		$input = 
		'
			<select name="'.$name.'" class=" form-control '.$class.' '.$otherClasses.'" id="'.$id.'" '.$disabled.' '.$extraCode.' data-live-search="true">
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
		<div class="'.$form_group_class.' form-group '.$type.' '.$hidden.'">
			'.$return2.'
		</div>		
		
		';	
        $label_left_right = 'text-left';
        if($cols[0] == 'defaultlabelleft'){
            $label_left_right = 'text-left';
        }
        if($cols[0] == '    '){
            $label_left_right = 'text-center';
        }
	$return1 = 
	'
				<div class="'.$form_group_class.' form-group '.$type.' col-md-12">
					<label class="col-md-'.$cols[1].' control-label '.$this->labelFontClass.' '.$this->labelOrientation.'">'.$label.'</label>
					<div class="col-md-'.$cols[2].'">
					'.$return2.'
					</div>
				</div>				
	';	
	

	}

	else if($cols[0] == 'floating'){	
		$input = 
		'
			<select name="'.$name.'" class="'.$form_group_class.' form-control '.$class.' '.$otherClasses.'" id="'.$id.'" '.$disabled.' '.$extraCode.'>
				'.$options1.'
			</select>
			<label for="'.$id.'" class="color-primary-custom"> '.$label.'</label>	
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
				<div class=" '.$form_group_class.' form-group '.$type.' '.$hidden.'">
					'.$return2.'
				</div>
	';

	}
	else if($cols[0] == 'md'){	

		$input = 
		'
			<label for="'.$id.'" class="col-md-'.$cols[1].' control-label '.$this->labelFontClass.' '.$this->labelOrientation.'"> '.$label.'</label>	
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

Function datepickerFromArray2($dataArray){
	$disabled = '';
		if($this->isDisabled == 'readonly') $disabled = 'disabled';	
		$inputType = isset($dataArray['inputType'])? $dataArray['inputType'] : 'date';
		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['form_group_type'])? $dataArray['form_group_type'] : $this->form_group_type;
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
                                                <label class="control-label col-md-3 '.$this->labelFontClass.' '.$this->labelOrientation.'">Default Datepicker</label>
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
		$type = isset($dataArray['form_group_type'])? $dataArray['form_group_type'] : $this->form_group_type;
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
											<label class="col-md-'.$cols[1].' control-label '.$this->labelFontClass.' '.$this->labelOrientation.'" for="'.$id.'">'.$label.'</label>
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
						<label class="col-md-'.$cols[1].' control-label '.$this->labelFontClass.' '.$this->labelOrientation.'">'.$label.'</label>
						<div class="col-md-'.$cols[2].'">
						'.$return2.'
						</div>
					</div>					
	
	';
}

return $return1;		
}

Function checkboxFromArray($dataArray) {
		$inputType = isset($dataArray['inputType'])? $dataArray['inputType'] : 'text';
		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['form_group_type'])? $dataArray['form_group_type'] : $this->form_group_type;
		$class = isset($dataArray['class'])? $dataArray['class'] : '';
		$container_class = isset($dataArray['container-class'])? $dataArray['container-class'] : '';
		$placeholder =isset ($dataArray['placeholder'])? $dataArray['placeholder'] : '';
		$label = isset($dataArray['label'])? $dataArray['label'] : '';
		$help = isset($dataArray['help'])? $dataArray['help'] : '';
		$color = isset($dataArray['color'])  && $dataArray['color'] != '' ? $dataArray['color'] : 'ff9800';
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

Function checkboxGroupFromArray($dataArray) {
		$inputType = isset($dataArray['inputType'])? $dataArray['inputType'] : 'text';
		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['form_group_type'])? $dataArray['form_group_type'] : $this->form_group_type;
		$class = isset($dataArray['class'])? $dataArray['class'] : '';
		$container_class = isset($dataArray['container-class'])? $dataArray['container-class'] : '';
		$placeholder =isset ($dataArray['placeholder'])? $dataArray['placeholder'] : '';
		$label = isset($dataArray['label'])? $dataArray['label'] : '';
		$help = isset($dataArray['help'])? $dataArray['help'] : '';
		$color = isset($dataArray['color'])? $dataArray['color'] : '';
		$disabled = isset($dataArray['disabled'])? 'disabled="'.$dataArray['disabled'].'"' : '';
		//$value = isset($dataArray['value'])? 'value="'.$dataArray['value'].'"' : '';
		//$checked = (isset($dataArray['checked']) && ($dataArray['checked'] == true || $dataArray['checked'] == 1))? 'checked' : '';
		$isDoubleColumn = isset($dataArray['isDoubleColumn'])? $dataArray['isDoubleColumn'] : false;
		$extraCode = isset($dataArray['extra'])? $dataArray['extra'] : ''; 
		$autoComplete = isset($dataArray['autoComplete'])? $dataArray['autoComplete'] : 'off'; 
		$return = '';
		$otherClasses = '';		
		$cols = explode('-', $type);
		if($cols[0] == 'default') $type = '';
		if($cols[0] == 'md') $type = 'form-md-line-input';
		//if($checked != '' && $checked != '0') $extraCode .= ' checked';
		$colorCode = 'style="border-color:#00bcd4"';
		 if ($color != '') $colorCode = 'style="border-color:#00bcd4"';
        
        $data_checked = array();
        if(isset($dataArray['value'])){
            $data_array = explode(',',$dataArray['value']);
            foreach($data_array as $dataVal){
                $data_checked[$dataVal] = true;
            }
        }
        $options = $dataArray['options'];
        $return = '<div class="row md-checkbox-group">';
        $atleast_one = false;
        $i = 0;
        foreach($options as $option){
            $checked = '';
            if(isset($data_checked[$option])) $checked = 'checked';
            $atleast_one = true;
            $return .= 
            '
            <div class="col-md-3">
                <div class="md-checkbox '.$container_class.'">
                    <input type="checkbox" id="'.$id.'-'.$i.'" name="'.$name.'[]" class="md-check '.$class.' '.$otherClasses.'" '.$extraCode.' '.$disabled.' value="'.$option.'" '.$checked.'>
                    <label for="'.$id.'-'.$i.'" class="text-left"  style="    font-size: 12px;color:#'.$color.'">
                    <span class="inc" '.$colorCode.'></span>
                    <span class="check"  '.$colorCode.'></span>
                    <span class="box"  '.$colorCode.'></span>
                    '.$option.' </label>
                </div>	
            </div>
            ';
$i++;            
        }
        $return .= '</div>';

	
	if($atleast_one){
        return $return;
    } 
    else{
        return '';
    }
}

Function textareaFromArray($dataArray) {
		$name = isset($dataArray['name'])? $dataArray['name'] : $dataArray['id'];
		$id = isset($dataArray['id'])? $dataArray['id'] : '';
		$type = isset($dataArray['form_group_type'])? $dataArray['form_group_type'] : $this->form_group_type;
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
										<label class="col-md-'.$cols[1].' control-label '.$this->labelFontClass.' '.$this->labelOrientation.'" for="'.$id.'">'.$label.'</label>
										<div class="col-md-'.$cols[2].'">
											<textarea class="form-control '.$class.' '.$otherClasses.'" name="'.$name.'" id="'.$id.'" rows="'.$rows.'" '.$extraCode.'>'.$value.'</textarea>
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
								
							<textarea name="'.$name.'" class="form-control '.$class.' '.$otherClasses.'"  id="'.$id.'" '.$this->isDisabled.' rows="'.$rows.'" '.$extraCode.'>'.$value.'</textarea>
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
				<label class="col-md-'.$cols[1].' control-label '.$this->labelFontClass.' '.$this->labelOrientation.'" for="'.$id.'">'.$label.'</label>
			   <div class="col-md-'.$cols[2].'">
					<textarea class="form-control '.$class.' '.$otherClasses.'" name="'.$name.'" id="'.$id.'" rows="'.$rows.'" '.$extraCode.'>'.$value.'</textarea>
				</div>
			</div>	
	
	';
}


return $return1;									
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
        $delFunc = "CommonFunc2Confirmation({'action':'delete-nestable','id':'$row[id]','module':'$data[parent_module]','removeElement':'dd-item-primary-$row[id]'},'',this)"; 
        $editFunc = "CommonFunc2Btn({'extended_action':'get','form_type':'edit','return-action':'nestable-list','module':'$data[parent_module]','id':'$row[id]'},'',this)"; 
        $secMenuFunc = "CommonFunc2Btn({'extended_action':'get','form_type':'new','return-action':'nestable-list','parent_id':'$row[id]','module':'$data[child_module]'},'',this)";                         
        $return['newrow'] = 
        '
            <li class="dd-item" data-id="'.$row['id'].'" id="dd-item-primary-'.$row['id'].'">
                <div class="dd-handle">
                <span class="handle"><i class="fa fa-arrows color-primary-custom"></i>'.$row['name'].'</span>           
                </div>
                <div class="col-md-6" style="position:absolute;right:0px;top:5px;"> 
                    <span class="text-right" style="float:right"><a href="javascript:;" class="btn btn-sm red btnDelete" onClick="'.$delFunc.'">Delete</a></span>
                    <span class="text-right" style="float:right" ><a href="javascript:;" class="btn btn-sm yellow" onClick="'.$editFunc.'">Edit</a></span>
                    <span class="text-right" style="float:right"><a href="javascript:;" class="btn btn-sm green" onClick="'.$secMenuFunc.'">Add Child</a></span>

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
            $delFunc = "CommonFunc2Confirmation({'action':'delete-nestable','id':'$row[id]','module':'$data[child_module]','removeElement':'dd-item-secondary-$row[id]'},'',this)";
            $editFunc = "CommonFunc2Btn({'extended_action':'get','form_type':'edit','return-action':'nestable-list','module':'$data[child_module]','id':'$row[id]'},'',this)";
            $return['newrow'] = 
            '
                <li class="dd-item" data-id="'.$row['id'].'" id="dd-item-secondary-'.$row['id'].'">
                    <div class="dd-handle"> 
                    <span class="handle"><i class="fa fa-arrows color-primary-custom"></i>'.$row['name'].'</span> 

                    </div>
                    <div class="col-md-6" style="position:absolute;right:0px;top:5px;"> 
                    <span class="text-right" style="float:right"><a href="javascript:;" class="btn btn-sm red" onClick="'.$delFunc.'">Delete</a></span>
                    <span class="text-right" style="float:right" ><a href="javascript:;" class="btn btn-sm yellow" onClick="'.$editFunc.'">Edit</a></span>

                    </div>
                </li>            
            ';                                      
            $return['jsFunction'] .= "$('#dd-list-${row['parent_id']}').append(value.newrow)";
        }    
    }
        

    return $return;    
    
}



function getNestableCollapsibleList($data){
    $row = $data;
    if(!isset($data['__child_list__'])){
        $data['__child_list__'] = ''; 
    }
    $return['jsFunction'] = '';
    $return['post'] = $data;    
    if($data['__list_type__'] == 'primary'){
        {
        //$delFunc = "Delete('post_group','$row[id]',this,'dd-item-primary-$row['id']')";
        $delFunc = "CommonFunc2Confirmation({'action':'delete-nestable','id':'$row[id]','module':'$data[parent_module]','removeElement':'collapsible-primary-$row[id]'},'',this)"; 
        $editFunc = "
				let data = {};
				data['data'] = {'action':'edit','id':'$row[id]','module':'$data[parent_module]'};
				data['isAjax'] = true;
				data['e'] = this;
				let formClass = new FormGenerator(data);
				formClass.actionCallBacks = {
					'save':'doNothing',
					'update':'doNothing'
				};		
		"; 
        $secMenuFunc = "
				let data = {};
				data['data'] = {'action':'new','$data[parent_fk]':'$row[id]','module':'$data[child_module]'};
				data['isAjax'] = true;
				data['e'] = this;
				let formClass = new FormGenerator(data);
				formClass.actionCallBacks = {
					'save':'doNothing',
					'update':'doNothing'
				};		
		";
		$row['name'] = (strlen($row['name']) > 60) ? substr($row['name'],0,55).'...' : $row['name'];
        $return['newrow'] = 
        '
		<li class="m-accordion__item sortable-item-parimary" data-id="'.$row['id'].'" >
			<div class="m-accordion__item-head collapsed"  role="tab" id="collapsible-primary-'.$row['id'].'_head" data-toggle="collapse" href="#collapsible-primary-'.$row['id'].'_body" aria-expanded="    false">
				<span class="m-accordion__item-icon">
					<i class="fa flaticon-user-ok"></i>
				</span>
				<span class="m-accordion__item-title">
					<span class="dd-handle dd-handle-primary"><i class="fa fa-arrows font-white"></i></span>
					<a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#nestable_list_1"  href="#collapse_'.$row['id'].'_'.$row['i'].'" id="collapsible-primary-label-'.$row['id'].'">
					</a>
					<span class="" style=""> 
						<span class="text-right" style="float:right"><a href="javascript:;" class="btn btn-sm red" onClick="'.$delFunc.'">Delete</a></span>
						<span class="text-right" style="float:right" ><a href="javascript:;" class="btn btn-sm yellow" onClick="'.$editFunc.'">Edit</a></span>
						<span class="text-right" style="float:right"><a href="javascript:;" class="btn btn-sm green" onClick="'.$secMenuFunc.'">Add Child</a></span>
					</span> 
					'.$row['name'].' 
				</span>
				<span class="m-accordion__item-mode"></span>
			</div>
			<div class="m-accordion__item-body collapse" id="collapsible-primary-'.$row['id'].'_body" class=" " role="tabpanel" aria-labelledby="collapsible-primary-'.$row['id'].'_head" data-parent="#nestable_main_ol">
				<div class="m-accordion__item-content">	
				<ul class="m-accordion m-accordion--bordered m-accordion--solid sortable-primary" id="collapsible-primary-container-'.$row['id'].'" role="tablist" data-id="'.$row['id'].'">
				'.$data['__child_list__'].'
				</ul>
					</div>
				</div>
            </li>
        ';
        }    
    }
    else if($data['__list_type__'] == 'secondary'){
        {
            $delFunc = "CommonFunc2Confirmation({'action':'delete-nestable','id':'$row[id]','module':'$data[child_module]','removeElement':'collapsible-secondary-$row[id]'},'',this)";
            $editFunc = "
				let data = {};
				data['data'] = {'action':'edit','id':'$row[id]','module':'$data[child_module]'};
				data['isAjax'] = true;
				data['e'] = this;
				let formClass = new FormGenerator(data);
				formClass.actionCallBacks = {
					'save':'doNothing',
					'update':'doNothing'
				};
			
			";
			$row['name'] = (strlen($row['name']) > 60) ? substr($row['name'],0,55).'...' : $row['name'];
            $return['newrow'] = 
            '
			<li class="m-accordion__item sortable-item-secondary">
				<div class="m-accordion__item-head collapsed"  role="tab" id="collapse_sec_'.$row['id'].'_'.$row['i'].'_head" data-toggle="collapse" href="#collapse_sec_'.$row['id'].'_'.$row['i'].'_body" aria-expanded="    false">
					<span class="m-accordion__item-icon">
						<i class="fa flaticon-user-ok"></i>
					</span>
					<span class="m-accordion__item-title">
					<span class="dd-handle dd-handle-secondary"><i class="fa fa-arrows font-white"></i></span>
					<a class=""  id="collapsible-secondary-label-'.$row['id'].'">
					 
					 </a>
                    <span class="" style="">
                    <span class="text-right" style="float:right"><a href="javascript:;" class="btn btn-sm red btnDelete" onClick="'.$delFunc.'">Delete</a></span>
                    <span class="text-right" style="float:right" ><a href="javascript:;" class="btn btn-sm yellow" onClick="'.$editFunc.'">Edit</a></span>

                    </span>
					'.$row['name'].'
					</span>
					<span class="m-accordion__item-mode"></span>
				</div>
				<div class="m-accordion__item-body collapse" id="collapse_sec_'.$row['id'].'_'.$row['i'].'_body" class=" " role="tabpanel" aria-labelledby="collapse_sec_'.$row['id'].'_'.$row['i'].'_head" data-parent="#m_accordion_'.$row['id'].'">
					<div class="m-accordion__item-content">
						<p>
							Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing
						</p>
					</div>
				</div>
			</li>			
            ';                                      
            $return['jsFunction'] .= "$('#dd-list-${row['parent_id']}').append(value.newrow)";
        }    
    }
        

    return $return;    
    
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


}


?>