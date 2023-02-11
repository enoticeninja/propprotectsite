function onChangeDashedString(elemOnChange, affectedElem) {
	console.log(affectedElem);
	$(document).on('change', '#' + elemOnChange + '', function () {
		var str = $(this).val();
		//str = str.toLowerCase().replace(/ /g, '-');
		str = str.toLowerCase().replace(/[^A-Z0-9]+/ig, "-");
		$('#' + affectedElem).val(str);
		$('#' + affectedElem).addClass('edited');
		resetElementState(affectedElem);
	});
}

function uniqueid() {
	// always start with a letter (for DOM friendlyness)
	var idstr = String.fromCharCode(Math.floor((Math.random() * 25) + 65));
	do {
		// between numbers and characters (48 is 0 and 90 is Z (42-48 = 90)
		var ascicode = Math.floor((Math.random() * 42) + 48);
		if (ascicode < 58 || ascicode > 64) {
			// exclude all chars between : (58) and @ (64)
			idstr += String.fromCharCode(ascicode);
		}
	} while (idstr.length < 15);

	return (idstr);
}

function explode(seperator, str) {
	var arr = [];
	arr = str.split(seperator);
	return arr;
}

function isset(variable) {
	var returnVar = false;
	if (typeof (variable) !== "undefined" && variable !== null) {
		returnVar = true;
	}
	return returnVar;
}

function str_replace(replace, replace_with, replace_in) {
	var regex = new RegExp("" + replace + "", "g");
	replace_in = replace_in.replace(regex, replace_with);
	return replace_in;
}

function replaceBetweenSquareBrackets($data, $string, $keyword) {
	if (!$string) return $string;
	var result = $string.match(/\[val\](.*?)\[\/val\]/g);
	if (result) {
		result.map(function (vall) {
			temp = vall.replace(/\[\/?val\]/g, '');
			if (isset($data[temp])) $string = $string.replace(vall, $data[temp]);
		});
	}
	return $string;
}

function replaceBetweenBraces($data, $string) {
	if (!$string) return $string;
	var result = $string.match(/\{(.*?)\}/g);
	if (result) {
		result.map(function (val) {
			var key = val.replace(/\{/g, '').replace(/\}/g, '');
			//console.log(val);
			if ($data[key]) $string = $string.replace(val, $data[key]);
			return key;
		});
	}
	return $string;
}

function getDefault($isset, $default) {
	if (typeof ($isset) != "undefined" && $isset !== null) {
		return $isset;
	} else {
		return $default;
	}
}

function row($data, $class) {
	if (!$class) $class = '';
	$return =
		'\
		<div class="row ' + $class + '">\
		' + $data + '\
		</div>\
	';
	return $return;
}

function getRandomInt(max) {
	return Math.floor(Math.random() * Math.floor(max));
}

function getReadableDate($date) {
	// Split timestamp into [ Y, M, D, h, m, s ]
	var t = $date.split(/[- :]/);
	// Apply each element to the Date function
	var d = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));
	var d = new Date(Date.parse($date)).toString();
	d = d.replace('GMT+0530 (India Standard Time)', '');
	return d;
}

function DisplaySelectedImageOnNewForm(elem, imageElem) {
	startLoading();
	var files = elem.files; // FileList object
	var f = files[0];
	var reader = new FileReader();
	reader.onload = (function (theFile) {
		return function (e) {
			var image = new Image();
			image.src = e.target.result;
			//$(window).on('load', function(){ ...});
			image.onload = function () {
				// access image size here 
				actualImageWidth = this.width;
				actualImageHeight = this.height;
				//imageholder.html('<img src="'+e.target.result+'" class="center-block img-responsive" style="margin:0 auto;" id="'+parent+'_image-crop"/>');
				//imageElem.src = e.target.result;
				imageElem.attr("src", e.target.result);
				//image_height.val(actualImageHeight);
				//image_width.val(actualImageWidth);
				//image_resize_width.val(actualImageWidth);
				//image_resize_height.val(actualImageHeight);
				stopLoading();
			};
		}
		stopLoading();
	})(f);
	reader.readAsDataURL(f);
};

var Form = function () {

	this.inputFromArray = function ($dataArray) {
		var $inputType = (isset($dataArray['inputType']) && $dataArray['inputType'] != '') ? $dataArray['inputType'] : 'text';

		var $name = isset($dataArray['name']) ? $dataArray['name'] : $dataArray['id'];
		var $id = isset($dataArray['id']) ? $dataArray['id'] : '';
		var $type = isset($dataArray['form_group_type']) ? $dataArray['form_group_type'] : ' floating-12-12';
		var $class = isset($dataArray['class']) ? $dataArray['class'] : '';
		var $placeholder = isset($dataArray['placeholder']) ? $dataArray['placeholder'] : '';
		var $label = isset($dataArray['label']) ? $dataArray['label'] : '';
		if ($dataArray['hide_label']) $label = '';
		var $help = isset($dataArray['help']) ? $dataArray['help'] : '';
		var $hidden = (isset($dataArray['hidden']) && $dataArray['hidden'] == true) ? 'hidden' : '';
		var $onClick = isset($dataArray['onClick']) ? $dataArray['onClick'] : '';

		var $value = (isset($dataArray['value']) && $dataArray['value'] != '') ? 'value="' + $dataArray['value'] + '"' : '';
		var $isDoubleColumn = (isset($dataArray['isDoubleColumn']) && $dataArray['isDoubleColumn'] != '') ? $dataArray['isDoubleColumn'] : false;
		var $extraCode = isset($dataArray['extra']) ? $dataArray['extra'] : '';
		var $autoComplete = $dataArray['autoComplete'] ? $dataArray['autoComplete'] : 'off';
		var $readOnly = $dataArray['readOnly'] ? 'readonly' : '';
		var $return = '',
			$return1 = '',
			$return2 = '';
		var $otherClasses = '';
		var $cols = explode('-', $type);
		$cols[0] = $.trim($cols[0]);
		if ($cols[0] == 'default') $type = '';
		if ($cols[0] == 'md') $type = 'form-md-line-input';
		if ($value != '') $otherClasses += 'edited ';
		$icon = (isset($dataArray['icon']) && $dataArray['icon'] != '') ? '<i class="' + $dataArray['icon'] + '"></i>' : '';
		$input =
			'<input type="' + $inputType + '" class="form-control ' + $class + ' ' + $otherClasses + '" name="' + $name + '" id="' + $id + '" placeholder="' + $placeholder + '" ' + $value + ' ' + $extraCode + ' autocomplete="' + $autoComplete + '" ' + $readOnly + ' onClick="' + $onClick + '">\
			<span class="help-block">' + $help + '</span>';
		if ($dataArray['element_only']) {
			$return1 = $input;
			return $return1;
		}
		if ($cols[0] == 'md') {



			if ($icon != '') {
				$return2 =
					'<div class="input-group">\
					' + $input + '\
					<span class="input-group-addon" id="' + $id + '-icon" style="cursor: pointer">' + $icon + '\
					</span>\
					</div>';
			} else {
				$return2 = $input;
			}

			$return1 =
				'\
				<div class="form-group ' + $type + ' ' + $hidden + '">\
					<label class="col-md-' + $cols[1] + ' control-label " for="' + $id + '">' + $label + '</label>\
					<div class="col-md-' + $cols[2] + '">\
						' + $return2 + '\
					</div>\
				</div>	\
				';
		}

		if ($cols[0] == 'floating') {
			$type = 'form-md-line-input form-md-floating-label';

			$input =
				'\
					<input type="' + $inputType + '" name="' + $name + '" class="form-control ' + $class + ' ' + $otherClasses + '" ' + $value + ' id="' + $id + '"  ' + $extraCode + '  onClick="' + $onClick + '">\
					<label for="' + $id + '" class="">' + $icon + ' ' + $label + '</label>\
					<span class="help-block">' + $help + '</span>\
				';

			if ($icon != '') {
				$return2 =
					'\
					<div class="input-group ">\
					' + $input + '\
					<span class="input-group-addon" id="' + $id + '-icon" style="cursor: pointer">' + $icon + '</span>\
					</div>\
					';
			} else {
				$return2 = $input;
			}

			$return1 =
				'\
				<div class="form-group ' + $type + ' ' + $hidden + '">\
					' + $return2 + '\
					</div>\
					';
		}

		if ($cols[0] == 'default') {
			$input =
				'\
				<input type="' + $inputType + '" name="' + $name + '" class="form-control ' + $class + ' ' + $otherClasses + '" ' + $value + ' id="' + $id + '"  ' + $extraCode + '  onClick="' + $onClick + '">';

			if ($icon != '') {
				$return2 =
					'\
					<div class="input-group">\
					<span class="input-group-addon" id="' + $id + '-icon" style="cursor: pointer">' + $icon + '</span>\
					' + $input + '\
					</div>\
					';
			} else {
				$return2 = $input;
			}

			$return1 =
				'\
			<div class="form-group ' + $hidden + '">\
			<label class="col-md-' + $cols[1] + ' control-label text-left">' + $label + '</label>\
			<div class="col-md-' + $cols[2] + '">\
			' + $return2 + '\
			<span class="help-block">' + $help + '</span>\
			</div>\
			</div>\
			';
		}

		if ($cols[0] == 'defaultsbs') {
			$input =
				'<input type="' + $inputType + '" name="' + $name + '" class="form-control ' + $class + ' ' + $otherClasses + '" ' + $value + ' id="' + $id + '" ' + $extraCode + '>';

			if ($icon != '') {
				$return2 =
					'\
					<div class="input-group ">\
					' + $input + '\
					<span class="input-group-addon" id="' + $id + '-icon" style="cursor: pointer">' + $icon + '</span>\
					</div>\
					';
			} else {
				$return2 = $input;
			}

			$return1 =
				'\
			<div class="form-group ' + $hidden + '">\
			<label>' + $label + '</label>\
			' + $return2 + '\
			<span class="help-block">' + $help + '</span>\
			</div>\
			';
		}

		return $return1;
	}

	this.datePickerFromArray = function ($dataArray) {
		var $inputType = 'date';
		var $name = isset($dataArray['name']) ? $dataArray['name'] : $dataArray['id'];
		var $id = isset($dataArray['id']) ? $dataArray['id'] : '';
		var $type = isset($dataArray['form_group_type']) ? $dataArray['form_group_type'] : ' floating-12-12';
		var $class = isset($dataArray['class']) ? $dataArray['class'] : '';
		var $placeholder = isset($dataArray['placeholder']) ? $dataArray['placeholder'] : '';
		var $label = isset($dataArray['label']) ? $dataArray['label'] : '';
		var $help = isset($dataArray['help']) ? $dataArray['help'] : '';
		var $hidden = (isset($dataArray['hidden']) && $dataArray['hidden'] == true) ? 'hidden' : '';
		var $onClick = isset($dataArray['onClick']) ? $dataArray['onClick'] : '';

		var $value = (isset($dataArray['value']) && $dataArray['value'] != '' && $dataArray['value'] != '0000-00-00') ? 'value="' + $dataArray['value'] + '"' : '';
		var $isDoubleColumn = (isset($dataArray['isDoubleColumn']) && $dataArray['isDoubleColumn'] != '') ? $dataArray['isDoubleColumn'] : false;
		var $extraCode = isset($dataArray['extra']) ? $dataArray['extra'] : '';
		var $autoComplete = (isset($dataArray['autoComplete']) && $dataArray['autoComplete'] != '') ? $dataArray['autoComplete'] : 'off';
		var $return = '',
			$return1 = '',
			$return2 = '';
		var $otherClasses = '';
		var $cols = explode('-', $type);
		$cols[0] = $.trim($cols[0]);
		if ($cols[0] == 'default') $type = '';
		if ($cols[0] == 'md') $type = 'form-md-line-input';
		if ($value != '') $otherClasses += 'edited ';
		$icon = (isset($dataArray['icon']) && $dataArray['icon'] != '') ? '<i class="' + $dataArray['icon'] + '"></i>' : '';
		$input =
			'<input type="' + $inputType + '" class="form-control ' + $class + ' ' + $otherClasses + '" name="' + $name + '" id="' + $id + '" placeholder="' + $placeholder + '" ' + $value + ' ' + $extraCode + ' autocomplete="' + $autoComplete + '" onClick="' + $onClick + '">\
			<span class="help-block">' + $help + '</span>';
		if ($dataArray['element_only']) {
			$return1 = $input;
			return $return1;
		}

		if ($cols[0] == 'md') {

			$input =
				'<input type="' + $inputType + '" class="form-control ' + $class + ' ' + $otherClasses + ' date-picker" name="' + $name + '" id="' + $id + '" placeholder="' + $placeholder + '" ' + $value + ' ' + $extraCode + ' autocomplete="' + $autoComplete + '" onClick="' + $onClick + '">\
				<span class="help-block">' + $help + '</span>';

			if ($icon != '') {
				$return2 =
					'<div class="input-group">\
					' + $input + '\
					<span class="input-group-addon" id="' + $id + '-icon" style="cursor: pointer">' + $icon + '\
					</span>\
					</div>';
			} else {
				$return2 = $input;
			}

			$return1 =
				'\
				<div class="form-group ' + $type + ' ' + $hidden + '">\
					<label class="col-md-' + $cols[1] + ' control-label " for="' + $id + '">' + $label + '</label>\
					<div class="col-md-' + $cols[2] + '">\
						' + $return2 + '\
					</div>\
				</div>	\
				';
		}

		if ($cols[0] == 'floating') {
			$type = 'form-md-line-input form-md-floating-label';

			$input =
				'\
					<input type="' + $inputType + '" name="' + $name + '" class="form-control ' + $class + ' ' + $otherClasses + ' date-picker" ' + $value + ' id="' + $id + '"  ' + $extraCode + '  onClick="' + $onClick + '">\
					<label for="' + $id + '" class="">' + $icon + ' ' + $label + '</label>\
					<span class="help-block">' + $help + '</span>\
				';

			if ($icon != '') {
				$return2 =
					'\
					<div class="input-group right-addon">\
					' + $input + '\
					<span class="input-group-addon" id="' + $id + '-icon" style="cursor: pointer">' + $icon + '</span>\
					</div>\
					';
			} else {
				$return2 = $input;
			}

			$return1 =
				'\
				<div class="form-group ' + $type + ' ' + $hidden + '">\
					' + $return2 + '\
					</div>\
					';
		}

		if ($cols[0] == 'default') {
			$input =
				'\
				<input type="' + $inputType + '" name="' + $name + '" class="form-control ' + $class + ' ' + $otherClasses + ' date-picker" ' + $value + ' id="' + $id + '"  ' + $extraCode + '  onClick="' + $onClick + '">';

			if ($icon != '') {
				$return2 =
					'\
					<div class="input-group">\
					<span class="input-group-addon" id="' + $id + '-icon" style="cursor: pointer">' + $icon + '</span>\
					' + $input + '\
					</div>\
					';
			} else {
				$return2 = $input;
			}

			$return1 =
				'\
			<div class="form-group ' + $hidden + '">\
			<label class="col-md-' + $cols[1] + ' control-label ">' + $label + '</label>\
			<div class="col-md-' + $cols[2] + '">\
			' + $return2 + '\
			<span class="help-block">' + $help + '</span>\
			</div>\
			</div>\
			';
		}

		if ($cols[0] == 'defaultsbs') {
			$input =
				'<input type="' + $inputType + '" name="' + $name + '" class="form-control ' + $class + ' ' + $otherClasses + ' date-picker" ' + $value + ' id="' + $id + '" ' + $extraCode + '>';

			if ($icon != '') {
				$return2 =
					'\
					<div class="input-group ">\
					' + $input + '\
					<span class="input-group-addon" id="' + $id + '-icon" style="cursor: pointer">' + $icon + '</span>\
					</div>\
					';
			} else {
				$return2 = $input;
			}

			$return1 =
				'\
			<div class="form-group ' + $hidden + '">\
			<label>' + $label + '</label>\
			' + $return2 + '\
			<span class="help-block">' + $help + '</span>\
			</div>\
			';
		}

		return $return1;
	}

	this.multipleSelectFromArray = function ($dataArray) {
		var $disabled = '';
		//if($this->isDisabled == 'readonly') $disabled = 'disabled';	
		var $name = isset($dataArray['name']) ? $dataArray['name'] : $dataArray['id'];
		var $id = isset($dataArray['id']) ? $dataArray['id'] : '';
		var $type = isset($dataArray['form_group_type']) ? $dataArray['form_group_type'] : ' floating-12-12';
		var $class = isset($dataArray['class']) ? $dataArray['class'] : '';
		var $placeholder = isset($dataArray['placeholder']) ? $dataArray['placeholder'] : '';
		var $label = isset($dataArray['label']) ? $dataArray['label'] : '';
		var $help = isset($dataArray['help']) ? $dataArray['help'] : '';
		var $selected2 = isset($dataArray['value']) ? $dataArray['value'] : [];
		var $selected = $selected2.split(",");
		var $isDoubleColumn = isset($dataArray['isDoubleColumn']) ? $dataArray['isDoubleColumn'] : false;
		var $extraCode = isset($dataArray['extra']) ? $dataArray['extra'] : '';
		var $options = isset($dataArray['options']) ? $dataArray['options'] : '';
		var $hidden = (isset($dataArray['hidden']) && $dataArray['hidden'] == true) ? 'hidden' : '';
		var $has_icons = (isset($dataArray['has_icons']) && $dataArray['has_icons']) ? true : false;
		$extraCode += (isset($dataArray['live_search']) && $dataArray['live_search']) ? ' data-live-search="true"' : '';

		var $return = '';
		var $otherClasses = '';
		var $form_group_class = '';
		var $input = '',
			$return1 = '',
			$return2 = '';
		//if(isset($dataArray['value'])) $otherClasses += ' edited';	
		var $cols = {};
		$cols = explode('-', $type);
		$cols[0] = $.trim($cols[0]);
		if ($cols[0] == 'default') $type = '';
		if ($cols[0] == 'defaultlabelleft') $type = '';
		if ($cols[0] == 'defaultlabelcenter') $type = '';
		if ($cols[0] == 'md') $type = 'form-md-line-input';
		if ($cols[0] == 'floating') $type = 'form-md-line-input form-md-floating-label';


		var $options1 = '<option></option>';
		if (typeof $options == 'object') {
			if ($has_icons) { /// If has icons
				$otherClasses += ' bs-select';
				$.each($options, function ($key, $value) {
					//if($key == $selected && $selected !== null) $isSelected = 'selected';	
					if ($.inArray($key, $selected) !== -1) $isSelected = 'selected';
					else $isSelected = '';
					$options1 += '<option value="' + $key + '" ' + $isSelected + ' data-icon="' + $value[0] + '">' + $value + '</option>';
				});
			} else {
				$.each($options, function ($key, $value) {
					if ($key == $selected && $selected !== null) $isSelected = 'selected';
					else $isSelected = '';
					$options1 += '<option value="' + $key + '" ' + $isSelected + '>' + $value + '</option>';
				});
			}
		} else {
			$options1 = $options;
		}

		var $icon = (isset($dataArray['icon']) && $dataArray['icon'] != '') ? '<i class="' + $dataArray['icon'] + '"></i>' : '';
		$input = '<select name="' + $name + '" class=" form-control ' + $class + ' ' + $otherClasses + '" id="' + $id + '" ' + $disabled + ' ' + $extraCode + ' data-live-search="true" multiple>' + $options1 + '</select>';
		if ($dataArray['element_only']) {
			$return1 = $input;
			return $return1;
		}
		if ($cols[0] == 'default' || $cols[0] == 'defaultlabelleft' || $cols[0] == 'defaultlabelcenter') {

			if ($icon != '') {
				$return2 =
					'<div class="input-group right-addon">	' + $input + '		\
					<span class="input-group-addon" id="' + $id + '-icon" style="cursor: pointer">' + $icon + '</span>\
				</div>\
				';
			} else {
				$return2 = $input
			}

			$return1 =
				'<div class="' + $form_group_class + ' form-group ' + $type + ' ' + $hidden + '"> ' + $return2 + '</div>';
			$label_left_right = 'text-left';
			if ($cols[0] == 'defaultlabelleft') {
				$label_left_right = 'text-left';
			}
			if ($cols[0] == '') {
				$label_left_right = 'text-center';
			}

			$return1 =
				'<div class="' + $form_group_class + ' form-group ' + $type + ' col-md-12"><label class="col-md-' + $cols[1] + ' control-label ' + $label_left_right + '"> ' + $label + ' </label><div class="col-md-' + $cols[2] + '"> ' + $return2 + ' </div></div>';


		} else if ($cols[0] == 'floating') {
			$input =
				'<select name="' + $name + '" class="' + $form_group_class + ' form-control ' + $class + ' ' + $otherClasses + '" id="' + $id + '" ' + $disabled + ' ' + $extraCode + ' multiple>\
					' + $options1 + '\
				</select>\
				<label for="' + $id + '" class="color-primary-custom"> ' + $label + '</label>\
				<span class="help-block">' + $help + '</span>';
			if ($icon != '') {
				$return2 =
					'<div class="input-group right-addon">' + $input + '<span class="input-group-addon" id="' + $id + '-icon" style="cursor: pointer">' + $icon + '</span></div>';
			} else {
				$return2 = $input;
			}
			$return1 =
				'\
						<div class=" ' + $form_group_class + ' form-group ' + $type + ' ' + $hidden + '">\
							' + $return2 + '\
						</div>\
			';

		}

		return $return1;
	}

	this.selectFromArray = function ($dataArray) {
		var $disabled = '';
		//if($this->isDisabled == 'readonly') $disabled = 'disabled';	
		var $name = isset($dataArray['name']) ? $dataArray['name'] : $dataArray['id'];
		var $id = isset($dataArray['id']) ? $dataArray['id'] : '';
		var $type = isset($dataArray['form_group_type']) ? $dataArray['form_group_type'] : ' floating-12-12';
		var $class = isset($dataArray['class']) ? $dataArray['class'] : '';
		var $placeholder = isset($dataArray['placeholder']) ? $dataArray['placeholder'] : '';
		var $label = isset($dataArray['label']) ? $dataArray['label'] : '';
		var $help = isset($dataArray['help']) ? $dataArray['help'] : '';
		var $selected = isset($dataArray['value']) ? $dataArray['value'] : false;
		var $isDoubleColumn = isset($dataArray['isDoubleColumn']) ? $dataArray['isDoubleColumn'] : false;
		var $extraCode = isset($dataArray['extra']) ? $dataArray['extra'] : '';
		var $options = isset($dataArray['options']) ? $dataArray['options'] : false;
		var $hidden = (isset($dataArray['hidden']) && $dataArray['hidden'] == true) ? 'hidden' : '';
		var $has_icons = (isset($dataArray['has_icons']) && $dataArray['has_icons']) ? true : false;
		$extraCode += (isset($dataArray['live_search']) && $dataArray['live_search']) ? ' data-live-search="true"' : '';

		var $return = '';
		var $otherClasses = '';
		var $form_group_class = '';
		var $input = '',
			$return1 = '',
			$return2 = '';
		if ($selected && $selected != '') $otherClasses += ' edited';
		var $cols = {};
		$cols = explode('-', $type);
		$cols[0] = $.trim($cols[0]);
		if ($cols[0] == 'default') $type = '';
		if ($cols[0] == 'defaultlabelleft') $type = '';
		if ($cols[0] == 'defaultlabelcenter') $type = '';
		if ($cols[0] == 'md') $type = 'form-md-line-input';
		if ($cols[0] == 'floating') $type = 'form-md-line-input form-md-floating-label';


		var $options1 = '<option></option>';
		if (typeof $options == 'object') {
			if ($has_icons) { /// If has icons
				$otherClasses += ' bs-select';
				$.each($options, function ($key, $value) {
					if ($key == $selected && $selected) $isSelected = 'selected';
					else $isSelected = '';
					$options1 += '<option value="' + $key + '" ' + $isSelected + ' data-icon="' + $value[0] + '">' + $value[1] + '</option>';
				});
			} else {
				$.each($options, function ($key, $value) {
					if ($key == $selected && $selected) $isSelected = 'selected';
					else $isSelected = '';
					$options1 += '<option value="' + $key + '" ' + $isSelected + '>' + $value + '</option>';
				});
			}
		} else {
			$options1 = $options;
		}

		var $icon = (isset($dataArray['icon']) && $dataArray['icon'] != '') ? '<i class="' + $dataArray['icon'] + '"></i>' : '';
		$input = '<select name="' + $name + '" class=" form-control ' + $class + ' ' + $otherClasses + '" id="' + $id + '" ' + $disabled + ' ' + $extraCode + ' data-live-search="true">' + $options1 + '</select>';
		if ($dataArray['element_only']) {
			$return1 = $input;
			return $return1;
		}
		if ($cols[0] == 'default' || $cols[0] == 'defaultlabelleft' || $cols[0] == 'defaultlabelcenter') {

			if ($icon != '') {
				$return2 =
					'<div class="input-group right-addon">	' + $input + '		\
					<span class="input-group-addon" id="' + $id + '-icon" style="cursor: pointer">' + $icon + '</span>\
				</div>\
				';
			} else {
				$return2 = $input
			}

			$return1 =
				'<div class="' + $form_group_class + ' form-group ' + $type + ' ' + $hidden + '"> ' + $return2 + '</div>';
			$label_left_right = 'text-left';
			if ($cols[0] == 'defaultlabelleft') {
				$label_left_right = 'text-left';
			}
			if ($cols[0] == '') {
				$label_left_right = 'text-center';
			}

			$return1 =
				'<div class="' + $form_group_class + ' form-group ' + $type + ' col-md-12"><label class="col-md-' + $cols[1] + ' control-label ' + $label_left_right + '"> ' + $label + ' </label><div class="col-md-' + $cols[2] + '"> ' + $return2 + ' </div></div>';


		} else if ($cols[0] == 'floating') {
			$input =
				'<select name="' + $name + '" class="' + $form_group_class + ' form-control ' + $class + ' ' + $otherClasses + '" id="' + $id + '" ' + $disabled + ' ' + $extraCode + '>\
					' + $options1 + '\
				</select>\
				<label for="' + $id + '" class="color-primary-custom"> ' + $label + '</label>\
				<span class="help-block">' + $help + '</span>';
			if ($icon != '') {
				$return2 =
					'<div class="input-group right-addon">' + $input + '<span class="input-group-addon" id="' + $id + '-icon" style="cursor: pointer">' + $icon + '</span></div>';
			} else {
				$return2 = $input;
			}
			$return1 =
				'\
						<div class=" ' + $form_group_class + ' form-group ' + $type + ' ' + $hidden + '">\
							' + $return2 + '\
						</div>\
			';

		}

		return $return1;
	}

	this.checkboxFromArray = function ($dataArray) {
		var $inputType = isset($dataArray['inputType']) ? $dataArray['inputType'] : 'text';
		var $name = isset($dataArray['name']) ? $dataArray['name'] : $dataArray['id'];
		var $id = isset($dataArray['id']) ? $dataArray['id'] : '';
		var $type = isset($dataArray['form_group_type']) ? $dataArray['form_group_type'] : '';
		var $class = isset($dataArray['class']) ? $dataArray['class'] : '';
		var $container_class = isset($dataArray['container-class']) ? $dataArray['container-class'] : '';
		var $placeholder = isset($dataArray['placeholder']) ? $dataArray['placeholder'] : '';
		var $label = isset($dataArray['label']) ? $dataArray['label'] : '';
		var $help = isset($dataArray['help']) ? $dataArray['help'] : '';
		var $color = isset($dataArray['color']) && $dataArray['color'] != '' ? $dataArray['color'] : 'ff9800';
		var $disabled = isset($dataArray['disabled']) ? 'disabled="' + $dataArray['disabled'] + '"' : '';
		var $value = isset($dataArray['value']) ? 'value="' + $dataArray['value'] + '"' : '';
		var $checked = (isset($dataArray['checked']) && ($dataArray['checked'] == true || $dataArray['checked'] == 1)) ? 'checked' : '';
		var $extraCode = isset($dataArray['extra']) ? $dataArray['extra'] : '';
		$return = '';
		$otherClasses = '';
		if ($checked != '' && $checked != '0') $extraCode += ' checked';
		$colorCode = '';
		if ($color != '') $colorCode = 'style="border-color:#' + $color + '"';
		$return =
			'\
			<div class="md-checkbox ' + $container_class + '">\
				<input type="checkbox" id="' + $id + '" name="' + $name + '" class="md-check ' + $class + ' ' + $otherClasses + '" ' + $extraCode + ' ' + $disabled + ' ' + $value + '>\
				<label for="' + $id + '" class="text-right"  style="color:#' + $color + '">\
				<span class="inc" ' + $colorCode + '></span>\
				<span class="check"  ' + $colorCode + '></span>\
				<span class="box"  ' + $colorCode + '></span>\
				' + $label + '\
				</div>';

		return $return;
	}
	this.checkBoxSwitch = function ($dataArray) {
		var $name = isset($dataArray['name']) ? $dataArray['name'] : $dataArray['id'];
		var $id = isset($dataArray['id']) ? $dataArray['id'] : '';
		var $class = isset($dataArray['class']) ? $dataArray['class'] : '';
		var $container_class = isset($dataArray['container-class']) ? $dataArray['container-class'] : '';
		var $label = isset($dataArray['label']) ? $dataArray['label'] : '';
		if ($label) $label = '<h5>' + $label + '</h5>';
		var $color = isset($dataArray['color']) && $dataArray['color'] != '' ? $dataArray['color'] : 'ff9800';
		var $disabled = isset($dataArray['disabled']) ? 'disabled="' + $dataArray['disabled'] + '"' : '';
		var $value = isset($dataArray['value']) ? 'value="' + $dataArray['value'] + '"' : '';
		var $checked = (isset($dataArray['checked']) && ($dataArray['checked'] == true || $dataArray['checked'] == 1)) ? 'checked' : '';
		var $extraCode = isset($dataArray['extra']) ? $dataArray['extra'] : '';
		var $color = isset($dataArray['checked_color']) ? $dataArray['checked_color'] : 'm-switch--danger';
		$return = '';
		$otherClasses = '';
		if ($checked != '' && $checked != '0') $extraCode += ' checked';
		var $return = '\
		<div class="' + $container_class + ' text-center">\
			<span class="m-switch m-switch--outline ' + $color + ' m-switch--icon">\
				<label>\
					<input type="checkbox" id="' + $id + '" name="' + $name + '" class=" ' + $class + ' ' + $otherClasses + '"  ' + $extraCode + ' ' + $disabled + ' ' + $value + '>\
					<span></span>\
				</label>\
				' + $label + '\
			</span>\
		</div>\
		';
		return $return;
	}
	this.checkboxGroupFromArray = function ($dataArray) {
		var $inputType = isset($dataArray['inputType']) ? $dataArray['inputType'] : 'text';
		var $name = isset($dataArray['name']) ? $dataArray['name'] : $dataArray['id'];
		var $id = isset($dataArray['id']) ? $dataArray['id'] : '';
		var $class = isset($dataArray['class']) ? $dataArray['class'] : '';
		var $container_class = isset($dataArray['container-class']) ? $dataArray['container-class'] : '';
		var $placeholder = isset($dataArray['placeholder']) ? $dataArray['placeholder'] : '';
		var $label = isset($dataArray['label']) ? $dataArray['label'] : '';
		var $help = isset($dataArray['help']) ? $dataArray['help'] : '';
		var $color = isset($dataArray['color']) ? $dataArray['color'] : '';
		var $disabled = isset($dataArray['disabled']) ? 'disabled="' + $dataArray['disabled'] + '"' : '';
		var $extraCode = isset($dataArray['extra']) ? $dataArray['extra'] : '';
		var $return = '';
		var $otherClasses = '';
		var $colorCode = 'style="border-color:#00bcd4"';
		if ($color != '') $colorCode = 'style="border-color:#00bcd4"';

		$data_checked = {};
		if (isset($dataArray['value'])) {
			$data_array = explode(',', $dataArray['value']);
			$.each($data_array, function ($key, $dataVal) {
				$data_checked[$dataVal] = true;
			});
		}
		$options = $dataArray['options'];
		$return = '<div class="row md-checkbox-group">';
		var $atleast_one = false;
		var $i = 0;
		$.each($options, function ($key, $option) {
			var $checked = '';
			if (isset($data_checked[$option])) $checked = 'checked';
			$atleast_one = true;
			$return +=
				'\
				<div class="col-md-3">\
					<div class="md-checkbox ' + $container_class + '">\
						<input type="checkbox" id="' + $id + '-' + $i + '" name="' + $name + '[]" class="md-check ' + $class + ' ' + $otherClasses + '" ' + $extraCode + ' ' + $disabled + ' value="' + $option + '" ' + $checked + '>\
						<label for="' + $id + '-' + $i + '" class="text-left"  style="    font-size: 12px;color:#' + $color + '">\
						<span class="inc" ' + $colorCode + '></span>\
						<span class="check"  ' + $colorCode + '></span>\
						<span class="box"  ' + $colorCode + '></span>\
						' + $option + ' </label>\
					</div>	\
				</div>\
				';
			$i++;
		});

		$return += '</div>';


		if ($atleast_one) {
			return $return;
		} else {
			return '';
		}
	}

	this.advancedDateTimeFromArray = function ($dataArray) {
		var $inputType = (isset($dataArray['inputType']) && $dataArray['inputType'] != '') ? $dataArray['inputType'] : 'text';

		var $name = isset($dataArray['name']) ? $dataArray['name'] : $dataArray['id'];
		var $id = isset($dataArray['id']) ? $dataArray['id'] : '';
		var $type = isset($dataArray['form_group_type']) ? $dataArray['form_group_type'] : '';
		var $class = isset($dataArray['class']) ? $dataArray['class'] : '';
		var $placeholder = isset($dataArray['placeholder']) ? $dataArray['placeholder'] : '';
		var $label = isset($dataArray['label']) ? $dataArray['label'] : '';
		var $help = isset($dataArray['help']) ? $dataArray['help'] : '';
		var $hidden = (isset($dataArray['hidden']) && $dataArray['hidden'] == true) ? 'hidden' : '';

		var $value = (isset($dataArray['value']) && $dataArray['value'] != '') ? 'value="' + $dataArray['value'] + '"' : '';
		var $isDoubleColumn = (isset($dataArray['isDoubleColumn']) && $dataArray['isDoubleColumn'] != '') ? $dataArray['isDoubleColumn'] : false;
		var $extraCode = isset($dataArray['extra']) ? $dataArray['extra'] : '';
		var $autoComplete = (isset($dataArray['autoComplete']) && $dataArray['autoComplete'] != '') ? $dataArray['autoComplete'] : 'off';
		var $return = '';
		var $otherClasses = '';
		if ($value != '') $otherClasses += 'edited ';
		var $icon = (isset($dataArray['icon']) && $dataArray['icon'] != '') ? '<i class="' + $dataArray['icon'] + '"></i>' : '';
		var $time_type = isset($dataArray['time_type']) ? $dataArray['time_type'] : 'form_datetime';
		if ($dataArray['element_only']) {
			$input = '<input type="text" size="16"  name="' + $name + '" class="form-control ' + $class + ' ' + $otherClasses + '" ' + $value + ' id="' + $id + '" ' + $extraCode + '>\''
			$return1 = $input;
			return $return1;
		}
		var $return1 =
			'\
							<div class="form-group ' + $hidden + '" >\
								<label class="control-label col-md-12">' + $label + '</label>\
								<div class="col-md-12" style="">\
									<div class="input-group date ' + $time_type + '" data-date="' + $dataArray['value'] + '" id="datetime-' + $id + '">\
										<input type="text" size="16"  name="' + $name + '" class="form-control ' + $class + ' ' + $otherClasses + '" ' + $value + ' id="' + $id + '" ' + $extraCode + '>\
										<span class="input-group-btn">\
											<button class="btn default date-reset" type="button" style="margin: 0;">\
												<i class="fa fa-times"></i>\
											</button>\
											<button class="btn default date-set" type="button" style="margin: 0;">\
												<i class="fa fa-calendar"></i>\
											</button>\
										</span>\
									</div>\
								</div>\
								<span class="col-md-12 help-block"></span>\
								</div>';


		return $return1;
	}

	this.textareaFromArray = function ($dataArray) {
		var $rows = isset($dataArray['rows']) ? $dataArray['rows'] : 3;
		var $name = isset($dataArray['name']) ? $dataArray['name'] : $dataArray['id'];
		var $id = isset($dataArray['id']) ? $dataArray['id'] : '';
		var $type = isset($dataArray['form_group_type']) ? $dataArray['form_group_type'] : ' floating-12-12';
		var $class = isset($dataArray['class']) ? $dataArray['class'] : '';
		var $placeholder = isset($dataArray['placeholder']) ? $dataArray['placeholder'] : '';
		var $label = isset($dataArray['label']) ? $dataArray['label'] : '';
		var $help = isset($dataArray['help']) ? $dataArray['help'] : '';
		var $hidden = (isset($dataArray['hidden']) && $dataArray['hidden'] == true) ? 'hidden' : '';
		var $onClick = isset($dataArray['onClick']) ? $dataArray['onClick'] : '';

		var $value = (isset($dataArray['value']) && $dataArray['value'] != '') ? $dataArray['value'] : '';
		var $extraCode = isset($dataArray['extra']) ? $dataArray['extra'] : '';
		var $autoComplete = (isset($dataArray['autoComplete']) && $dataArray['autoComplete'] != '') ? $dataArray['autoComplete'] : 'off';
		var $return = '',
			$return1 = '',
			$return2 = '';
		var $otherClasses = '';
		var $cols = explode('-', $type);
		$cols[0] = $.trim($cols[0]);
		if ($cols[0] == 'default') $type = '';
		if ($cols[0] == 'md') $type = 'form-md-line-input';
		if ($value != '') $otherClasses += 'edited ';


		if ($cols[0] == 'md') {
			$icon = isset($dataArray['icon']) ? '<i class="' + $dataArray['icon'] + '"></i>' : '';
			$return1 =
				'\
			<div class="form-group form-md-line-input ' + $hidden + '">\
				<label class="col-md-' + $cols[1] + ' control-label " for="' + $id + '">' + $label + '</label>\
				<div class="col-md-' + $cols[2] + '">\
					<textarea class="form-control ' + $class + ' ' + $otherClasses + '" name="' + $name + '" id="' + $id + '" rows="' + $rows + '" ' + $extraCode + '>' + $value + '</textarea>\
					<div class="form-control-focus">\
					</div>\
					<span class="help-block">' + $help + '</span>\
					</div>\
				</div>\
					';
		}
		if ($cols[0] == 'floating') {
			$type = 'form-md-line-input form-md-floating-label';
			$return1 =
				'\
				<div class="form-group form-md-line-input form-md-floating-label ' + $hidden + '">\
					<textarea name="' + $name + '" class="form-control  ' + $class + ' ' + $otherClasses + '"  id="' + $id + '"  rows="' + $rows + '" ' + $extraCode + '>' + $value + '</textarea>\
					<label for="' + $id + '" class="">' + $label + '</label>\
					<span class="help-block">' + $help + '</span>\
					</div>\
				';
		}

		if ($cols[0] == 'default') {
			$type = 'form-md-line-input form-md-floating-label';
			$icon = isset($dataArray['icon']) ? '<i class="' + $dataArray['icon'] + '"></i>' : '';
			$return1 =
				'\
			<div class="form-group ' + $hidden + '">\
				<label class="col-md-' + $cols[1] + ' control-label " for="' + $id + '">' + $label + '</label>\
			   <div class="col-md-' + $cols[2] + '">\
					<textarea class="form-control  ' + $class + ' ' + $otherClasses + '" name="' + $name + '" id="' + $id + '" rows="' + $rows + '" ' + $extraCode + '>' + $value + '</textarea>\
				</div>\
			</div>	\
			';
		}

		return $return1;
	}

	this.getImageBox = function ($data) {
		var $unique_id = $data['unique_id'];
		var $image_box = ''; { //// Image
			if (!isset($data['image']) || $data['image'] == '' || $data['image'] == 'NULL') {
				$image_box =
					'\
						<div class="col-md-6 paired-img-btn-holder main-image-has-button text-center">\
							<input type="hidden" class="unique-id" value="' + $unique_id + '"> \
							<button type="button" class="btn yellow" data-toggle="modal" href="#full" onClick="SetCurrentImageFunction(\'CommonFuncImage() \');SetCurrentHolder(\'' + $unique_id + '\')" id="btn-' + $unique_id + '-image"><i class="fa fa-plus"></i>Image</button>\
							<div class="col-md-12 new-content paired-image-holder hidden" id="' + $unique_id + '-image-holder">\
							</div>\
						</div>';
			} else {

				$image_box =
					'\
				<div class="col-md-6 paired-img-btn-holder text-center">\
							<input type="hidden" class="unique-id" value="' + $unique_id + '">\
							<button type="button" class="btn yellow hidden" data-toggle="modal" href="#full" onClick="SetCurrentImageFunction(\'CommonFuncImage() \');SetCurrentHolder(\'' + $unique_id + '\')" id="btn-' + $unique_id + '-image"><i class="fa fa-plus"></i>Image</button>\
								<div class="col-md-12 new-content paired-image-holder" id="' + $unique_id + '-image-holder">\
									<div class="task-config">\
										<div class="action-buttons">\
											<a class="btn btn-xs yellow"  data-toggle="modal" href="#full" onClick="SetCurrentImageFunction(\'CommonFuncImage() \');SetCurrentHolder(\'' + $unique_id + '\')"><i class="fa fa-edit"></i> Change </a>\
											<a class="btn btn-xs yellow"  data-toggle="modal" href="#full" onClick="EditImage(\'' + $data['image_name'] + '\');SetCurrentHolder(\'' + $unique_id + '\')"><i class="fa fa-edit"></i> Edit </a>\
											<a class="btn btn-xs red" href="javascript:;" onClick="DeletePairedImage(this,\'' + $data['id'] + '\')"><i class="fa fa-trash-o"></i> Delete </a>\
											<a class="btn btn-xs default btnImgMinimize" href="javascript:;" ><i class="fa fa-minus"></i> Minimize </a>\
											<a class="btn btn-xs default btnImgMaximize" href="javascript:;" ><i class="fa fa-plus"></i> Maximize </a>\
										</div>\
									</div>	\
									<img src="' + $frontend_site_path + 'uploads/' + $data['image_name'] + '" alt="" class="img-responsive img100">\
								</div> \
							</div>	\
				';
			}

		}

		return $image_box;
	}

	this.getSummerNoteBox = function ($data) {
		////Paragraph
		var $content = isset($data['content']) ? $data['content'] : '';
		var $paragraph_box =
			'\
						<div class="col-md-6 new-content">\
							' + $hidden_inputs + '\
							<div class="form-group">\
								<div class="col-md-12">\
									<div id="' + $unique_id + '">' + $content + '</div>\
								</div>\
							</div>\
						</div>	\
		';

		return $content;
	}

	this.getImageEditModal = function ($data) {
		$data['parent'] = getDefault($data['parent'], '');
		$data['modal_title'] = getDefault($data['modal_title'], ''); {
			var $single_image_panel =
				'\
		<div class="row">\
			<span class="" id="' + $data['parent'] + '_error-messages"></span>\
			<div class="text-center">\
			<!-- This is the form that our event handler fills -->\
			<form method="post" id="' + $data['parent'] + '_imageForm" class="" onsubmit="return false" enctype="multipart/form-data">\
				<div class="form-group text-center">\
					<label class="control-label">Choose Image<span class="required">* </span></label>\
					<div class="input-icon">\
						<i class="fa"></i>\
						<input type="file" class="btn green" style="margin:0 auto;" name="file" id="' + $data['parent'] + '_file_single" >\
					</div>\
				</div>\
			</form>\
			<form method="post" id="' + $data['parent'] + '_cropForm" class="" onsubmit="return false">\
				<div class="form-actions pull-right">\
					<input type="hidden" id="' + $data['parent'] + '_crop_x" name="x"/>\
					<input type="hidden" id="' + $data['parent'] + '_crop_y" name="y"/>\
					<input type="hidden" id="' + $data['parent'] + '_crop_w" name="w"/>\
					<input type="hidden" id="' + $data['parent'] + '_crop_h" name="h"/>\
					<input type="hidden" id="' + $data['parent'] + '_image_path" name="image_path"/>\
				</div>\
			</form>	\
			</div>\
			<div class="text-center"  id="' + $data['parent'] + '_preview">	\
			<div class="text-center" style="width:50%;padding-right:50px;padding-left:50px;margin:0 auto;" id="' + $data['parent'] + '_image-holder">\
			<!-- This is the image were attaching Jcrop to -->\
			</div>\
			</div>\
	\
		<div id="' + $data['parent'] + '_imageEditToolbar" style="position:fixed;top:10px;z-index:999999;" class="col-md-11 text-center imageEditToolbar">\
			<div class="form-group inline">\
				<div class="input-inline">\
					<div class="input-group">\
						<span class="input-group-addon">\
						<i class="fa fa-arrows-h"></i>\
						</span>\
						<input type="text" name="__image_width" id="' + $data['parent'] + '_image_width" class="form-control" placeholder="Width" disabled>\
					</div>\
				</div>\
			</div>\
	\
			<div class="form-group inline">\
				<div class="input-inline">\
					<div class="input-group">\
						<span class="input-group-addon">\
						<i class="fa fa-arrows-v"></i>\
						</span>\
						<input type="text" name="__image_height" id="' + $data['parent'] + '_image_height" class="form-control" placeholder="Height" disabled>\
					</div>\
				</div>\
			</div>\
			<div class="form-group inline">\
				<div class="input-inline">\
					<div class="input-group">\
						<span class="input-group-addon">\
						<i class="fa fa-arrows-v"></i>\
						</span>\
						<input type="text" name="__image_size" id="' + $data['parent'] + '_image_size" class="form-control" placeholder="Size" disabled>\
					</div>\
				</div>\
			</div>\
			\
		<button type="button" class="btn orange hidden" id="' + $data['parent'] + '_btn_upload" >Upload/Edit</button>\
		<button class="btn red hidden" id="' + $data['parent'] + '_btn_save_use" data-dismiss="modal" ">SAVE AND USE</button>\
		<button class="btn yellow hidden" id="' + $data['parent'] + '_btn_crop"  >CROP</button>\
		<button class="btn blue hidden" id="' + $data['parent'] + '_btn_crop_use" >CROP NOW</button>\
		<button class="btn btn-circle btn-icon-only yellow hidden" id="' + $data['parent'] + '_btn_rotate_270" ><i class="fa fa-rotate-left"></i></button>\
		<button class="btn yellow hidden" id="' + $data['parent'] + '_btn_undo"  >UNDO</button>\
		<button class="btn btn-circle btn-icon-only yellow hidden" id="' + $data['parent'] + '_btn_rotate_90" ><i class="fa fa-rotate-right"></i></button>\
		<div class="dropdown hidden" id="' + $data['parent'] + '_dropdown_resize">\
		<a href="javascript:;" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="true">\
		Resize <i class="fa fa-angle-down"></i>\
		</a>\
		<div class="dropdown-menu hold-on-click" style="width:250px; padding:20px;">\
		<form role="form" id="' + $data['parent'] + '_resize_form" onsubmit="return false">\
		<div class="form-body">\
			<div class="form-group form-md-radios">\
				<label>Resize By</label>\
				<div class="md-radio-inline">\
					<div class="md-radio has-warning">\
						<input type="radio" id="' + $data['parent'] + '_radio_pixel" name="__radio_by" class="md-radiobtn" value="pixel" checked>\
						<label for="__radio_pixel">\
						<span></span>\
						<span class="check"></span>\
						<span class="box"></span>\
						Px </label>\
					</div>\
					<div class="md-radio has-warning">\
						<input type="radio" id="' + $data['parent'] + '_radio_percent" name="__radio_by" class="md-radiobtn" value="percent">\
						<label for="__radio_percent">\
						<span></span>\
						<span class="check"></span>\
						<span class="box"></span>\
						% </label>\
					</div>\
				</div>\
			</div>\
			<div class="form-group">\
				<div class="md-checkbox has-warning">\
					<input type="checkbox" id="' + $data['parent'] + '_cons_prop" name="__cons_prop" class="md-check" >\
					<label for="__cons_prop">\
					<span></span>\
					<span class="check"></span>\
					<span class="box"></span>\
					Constraint Proportion </label>\
				</div>\
			</div>\
			\
			<div class="form-group">\
				<div class="input-inline">\
					<div class="input-group">\
						<span class="input-group-addon">\
						<i class="fa fa-arrows-h"></i>\
						</span>\
						<input type="number" name="__image_resize_width" id="' + $data['parent'] + '_image_resize_width" class="form-control" placeholder="Width" style="width:90px;">\
						<input type="hidden" name="__image_resize_width_post" id="' + $data['parent'] + '_image_resize_width_post">\
						<span class="input-group-addon resize_by_addon_width">\
						Pixels\
						</span>\
					</div>\
						<span class="help-block" id="' + $data['parent'] + '_image_resize_width_label"></span>\
				</div>\
			</div>\
			\
			<div class="form-group">\
				<div class="input-inline">\
					<div class="input-group">\
						<span class="input-group-addon">\
						<i class="fa fa-arrows-v"></i>\
						</span>\
						<input type="number" name="__image_resize_height" id="' + $data['parent'] + '_image_resize_height" class="form-control" placeholder="Height" style="width:90px;">\
						<input type="hidden" name="__image_resize_height_post" id="' + $data['parent'] + '_image_resize_height_post">\
						<span class="input-group-addon resize_by_addon_height">\
						Pixels\
						</span>	\
					</div>\
						<span class="help-block" id="' + $data['parent'] + '_image_resize_height_label"></span>\
				</div>\
			</div>\
			<button class="btn btn-warning dropdown-toggle" id="' + $data['parent'] + '_resize_btn">Resize</button>\
			</div>\
			</form>\
			</div>\
			</div>\
			</div>\
		</div>\
		';
		}

		{
			var $multiple_image_panel =
				'\
		<div class="row">\
			<div class="text-center">\
				<!-- This is the form that our event handler fills -->\
				<form method="post" id="' + $data['parent'] + '_multipleImageForm" class="" onsubmit="return false" enctype="multipart/form-data">\
					<div class="form-group text-center">\
						<label class="control-label">Choose Images<span class="required">* </span></label>\
						<div class="input-icon">\
							<i class="fa"></i>\
							<input type="file" class="btn green" style="margin:0 auto;" name="file[]" id="' + $data['parent'] + '_file" onchange="" multiple>\
						</div>\
					</div>\
					<button class="btn btn-lg green" id="' + $data['parent'] + '_multiple-upload-button" >UPLOAD</button>\
				</form>\
			</div>\
		</div>\
		';
		}

		{
			var $nav_tabs =
				'\
				<div class="tabbable-custom ">\
					<ul class="nav nav-tabs ">\
						<li class="active">\
							<a href="#' + $data['parent'] + '_tab_1" data-toggle="tab" aria-expanded="tue">\
							UPLOAD AND EDIT </a>\
						</li>\
						<li class="">\
							<a href="#' + $data['parent'] + '_tab_2" data-toggle="tab" aria-expanded="false">\
							UPLOAD MULTIPLE IMAGES </a>\
						</li>\
					</ul>\
					<div class="tab-content">\
						<div class="tab-pane active" id="' + $data['parent'] + '_tab_1">  \
						' + $single_image_panel + '\
						</div>\
						<div class="tab-pane" id="' + $data['parent'] + '_tab_2">  \
						' + $multiple_image_panel + '\
						</div>\
					</div>\
				</div>\
		';
		}

		var $modal_content = $nav_tabs;
		if (isset($data['one_image']) && $data['one_image']) {
			$modal_content = $single_image_panel;
		} else if (isset($data['multiple_image']) && $data['multiple_image']) {
			$modal_content = $nav_tabs;
		}

		{
			var $modal =
				'\
		<div class="modal" id="' + $data['parent'] + '_image_edit_modal" tabindex="-1" role="dialog" aria-hidden="true" style="top:0px">\
			<div class="modal-dialog modal-full">\
				<div class="modal-content" id="' + $data['parent'] + '_image_edit_modal_content">\
					<div class="modal-header">\
						<div class="row">\
							<div class="col-md-7">\
								<h4 class="modal-title" id="' + $data['parent'] + '_image_modal_title">' + $data['modal_title'] + '</h4>\
							</div>\
							<div class="col-md-5 text-right">\
							<button type="button" class="btn red" id="' + $data['parent'] + '_close_edit_image_modal" data-dismiss="modal" >Close</button>\
							</div>\
						</div>\
					</div>\
					<div class="modal-body">\
						' + $modal_content + '\
					</div>\
					<div class="modal-footer">\
					</div>\
				</div>\
			</div>\
		</div>\
		';
		}
		$return['html'] = $modal;
		$return['parent'] = $data['parent'];
		$return['jsFunction'] =
			"\
		var img" + $data['parent'] + " = new ImageEditor({'parent':'" + $data['parent'] + "','imageProcessingUrl':'imageProcessing'});\
		img" + $data['parent'] + ".setAjaxImageUploadData({'action':'upload_company_logo','parent':'" + $data['parent'] + "'});\
		$('#" + $data['parent'] + "-edit-modal').on('hidden.bs.modal', function () {\
		\
		});\
		";
		return $return;
	}

}

$(document).ready(function () {
	/*     var options = [];
		options['name'] = 'data';
		options['id'] = 'data11';
		options['label'] = 'Enter Data';
		options['options'] = [1,2,3,4];
		var test = new Form();
		console.log(test);
		var input = test.selectFromArray(options);
		$('#main-container').append(input);; */
});