<?php

/// CONTAINS FUNCTIONS IRRELEVANT OF THE THEME AND DATABASE
function Select($class, $id, $options, $selected='', $isDisabled=false){
 $options1 = '<option></option>';

	if(is_array($options)){
		foreach($options as $key => $value) {
				if (is_int($key)) {
					$options1 .= '
											<option value="'.$value.'">'.$value.'</option>
										';				
				} 
				else {
					if($key == $selected) $isSelected = 'selected';
					else $isSelected = '';
						$options1 .= '
												<option value="'.$key.'" '.$isSelected.'>'.$value.'</option>
											';
					
				}
		}	
	} else {
		$options1 = $options;
	}

	$disabled = $isDisabled ? 'disabled': '';
	$return = 
	'
	<select class="'.$class.'" id="'.$id.'" name="'.$id.'[]" '.$disabled.'>
	'.$options1.'
	</select>
	';	
return $return;
}

function Input($class, $id, $value) {
$return = 
'
<input type="text" class="'.$class.'" id="'.$id.'" value="'.$value.'" name="'.$class.'[]">
';
return $return;
}

function Button($class, $id, $label, $onclick) {
$return = 
'
<button type="submit" class="btn btn-'.$class.'" onclick="'.$onclick.'(this)">'.$label.'</button>
';
return $return;
}

function DisabledButton($class, $id, $label, $onclick) {
$return = 
'
<button type="submit" class="btn btn-'.$class.'" onclick="'.$onclick.'(this)" disabled>'.$label.'</button>
';
return $return;
}

function DisabledInput($class, $id, $value) {
$return = 
'
<input type="text" class="'.$class.'" id="'.$id.'" value="'.$value.'" name="'.$class.'[]" disabled>
';
return $return;
}

function CreateTableRow($data){
	$row_data = 
	'
	<tr class="mt-element-ribbon">
	';
		foreach($data as $col) {
		$row_data .= 
		'
		<td>'.$col.'</td>
		';
		}
	$row_data .= 
	'
	</tr>
	';
return $row_data;
}

?>