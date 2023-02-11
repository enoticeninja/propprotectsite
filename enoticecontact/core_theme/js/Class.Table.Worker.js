//postMessage("I\'m working before postMessage(\'ali\').");
var $table = {};

function str_replace(replace, replace_with, replace_in) {
    var regex = new RegExp("" + replace + "", "g");
    replace_in = replace_in.replace(regex, replace_with);
    return replace_in;
}

checkboxFromArray = function ($dataArray) {
    var $inputType = ($dataArray['inputType']) ? $dataArray['inputType'] : 'text';
    var $name = ($dataArray['name']) ? $dataArray['name'] : $dataArray['id'];
    var $id = ($dataArray['id']) ? $dataArray['id'] : '';
    var $type = ($dataArray['form_group_type']) ? $dataArray['form_group_type'] : '';
    var $class = ($dataArray['class']) ? $dataArray['class'] : '';
    var $container_class = ($dataArray['container-class']) ? $dataArray['container-class'] : '';
    var $placeholder = ($dataArray['placeholder']) ? $dataArray['placeholder'] : '';
    var $label = ($dataArray['label']) ? $dataArray['label'] : '';
    var $help = ($dataArray['help']) ? $dataArray['help'] : '';
    var $color = ($dataArray['color']) && $dataArray['color'] != '' ? $dataArray['color'] : 'ff9800';
    var $disabled = ($dataArray['disabled']) ? 'disabled="' + $dataArray['disabled'] + '"' : '';
    var $value = ($dataArray['value']) ? 'value="' + $dataArray['value'] + '"' : '';
    var $checked = (($dataArray['checked']) && ($dataArray['checked'] == true || $dataArray['checked'] == 1)) ? 'checked' : '';
    var $extraCode = ($dataArray['extra']) ? $dataArray['extra'] : '';
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
    var str = $string;
    var result = str.match(/\{(.*?)\}/g).map(function (val) {
        var key = val.replace(/\{/g, '').replace(/\}/g, '');
        //console.log(val);
        if ($data[key]) $string = $string.replace(val, $data[key]);
        return key;
    });
    //console.log($string);
    return $string;
}
function getReadableDate($date){
	// Split timestamp into [ Y, M, D, h, m, s ]
	var t = $date.split(/[- :]/);
	// Apply each element to the Date function
	var d = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5]));
	var d = new Date(Date.parse($date)).toString();
	d= d.replace('GMT+0530 (India Standard Time)','');
	return d;
}
onmessage = function (oEvent) {
    var size = Object.keys(oEvent.data.tableData).length;

    $table = oEvent.data;
    for (i = 0; i <= size - 1; i++) {
        var data = oEvent.data.tableData[i];
        $col_data_temp = createArrayForColumns(data);
        var $table1 = createTableRow($col_data_temp, data);
        postMessage({
            action: 'append-row',
            row: $table1
        });
    }
    postMessage({
        action: 'done-all',
        size: size
    });
};
////CHANGE HERE FOR A NEW PAGE
var createArrayForColumns = function ($data) {
    $label = '';
    //$table['has_status_label'] = true;
    ///// STATUS LABEL CODE
    if ($table['has_status_label']) {
        $label = '';
        if (($data[$table['status_field']]) && ($data[$table['status_field']]).trim() != '' && $data[$table['status_field']] != null) {
            $label = ($table['status_label'][$data[$table['status_field']]]) ? $table['status_label'][$data[$table['status_field']]] : '';
        }
    }

    $all_buttons = '';
    //// BUTTONS CODE
    $temp_buttons = '';
    $buttons = $table['tr_buttons'];

    for (var $key in $buttons) {
        $btn = $buttons[$key];
        $btn = replaceBetweenBraces($data, $btn);
        $temp_buttons += '<li class="m-nav__item">' + $btn + '</li>';
    }

    for (var $key in $table['cond_tr_buttons']) {
        $btnObj = $table['cond_tr_buttons'][$key];
        $btn = ($btnObj[$data['status']]) ? $btnObj[$data['status']] : '';
        if ($btn != '') $temp_buttons += '<li class="m-nav__item">' + $btn + '</li>';
    }


    $all_buttons +=
        '\
		<div class="m-dropdown m-dropdown--arrow m-dropdown--align-center m-dropdown--align-push" data-dropdown-toggle="click" aria-expanded="true" data-dropdown-persistent="true">\
		<a href="#" class="m-portlet__nav-link btn btn-lg ' + $table['tr_action_btn_class'] + ' m-btn m-btn--outline-2x m-btn--air m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">\
			<i class="flaticon-cogwheel-2"></i>\
		</a>\
		<div class="m-dropdown__wrapper">\
			<span class="m-dropdown__arrow m-dropdown__arrow--center" ></span>\
			<div class="m-dropdown__inner">\
				<div class="m-dropdown__body no-padding">\
					<div class="m-dropdown__content">\
						<ul class="m-nav">\
							' + $temp_buttons + '\
						</ul>\
					</div>\
				</div>\
			</div>\
		</div>\
		</div>\
        ';


    $col_data_temp = {};
    $col_headers = {};

    ///// CHECK BOX
    if ($table['has_checkbox']) {

        $col_data_temp['checkbox'] = checkboxFromArray({
            'label': '',
            'id': 'check-__ID__',
            'name': 'ids[]',
            'class': 'check-__ID__',
            'color': 'ffa000',
            'value': '__ID__'
        });
        //$col_data_temp['checkbox'] = '<div class="" style="width:30px;margin:0 auto;">'+$col_data_temp['checkbox']+'</div>';										
    }

    if ($table['has_tr_buttons']) {
        $col_data_temp['actions'] = '' + $all_buttons + '';
    }
    ///// PREPARE TD DATA


    $fKeys = $table.foreignKeys;
    for (var $key in $table['cols']) {
        $col = $table['cols'][$key];
        if ($table['eval'][$col]) {
            var jsCode = replaceBetweenBraces($data, $table['eval'][$col]);
            $col_data_temp[$col] = eval(jsCode);
        } 
        else if (($table['custom_cols'] && $table['custom_cols'][$col])) {
            if ($table['custom_cols'][$col]['value']) {
                $col_data_temp[$col] = replaceBetweenBraces($data, $table['custom_cols'][$col]['value']);
            } else {
                $col_data_temp[$col] = $data[$col];
            }
        } 
        else {
            if ($fKeys[$col]) { //// Replace foreign keys with _name prefix
                $col_data_temp[$col] = $data['' + $col + '_name'];
            } 
            else {
                if ($col == 'datecreated' && $data[$col] && $data[$col] != '0000-00-00 00:00:00') $col_data_temp[$col] = getReadableDate($data[$col]);
                if ($col == 'date_created' && $data[$col] && $data[$col] != '0000-00-00 00:00:00') $col_data_temp[$col] = getReadableDate($data[$col]);
                else $col_data_temp[$col] = $data[$col];
            }
        }

        if (($table['field_functions'] && $table['field_functions'][$col])) {
            $col_data_temp[$col] = call_user_func_array($table['field_functions'][$col], array($col_data_temp[$col]));
        }
    }
    //console.log($table['has_status_label']);
    if ($table['has_status_label'] || ($table['override_status_label'])) {
        $col_data_temp['label'] = $label;
    } 
    else {
        //$col_data_temp['label'] = $label;
    }



    return $col_data_temp;
}



var createTableRow = function ($data, $userData, $trclass) {
    if (!$trclass) $trclass = '';
    $trid = $userData[$table.config['id']];
    if ($trid != '') {
        $row_data =
            '\
            <tr class="mt-element-ribbon ' + $trclass + ' ' + $table.module + '-tr  tr-' + $trid + '" data-id="' + $trid + '">\
            ';
    } else {
        $row_data =
            '\
            <tr class="mt-element-ribbon ' + $trclass + ' ' + $table.module + '-tr tr-' + $data['id'] + '" data-id="' + $trid + '">\
            ';
    }


    for (var $key in $data) {
        $col = $data[$key];
        var editableClass = '';
        if ($table.editable.includes($key)) {
            editableClass = 'editable-text-full';
        }
        $row_data +=
            '\
			<td class="td-' + $key + ' ' + editableClass + ' " id="td-' + $trid + '-' + $key + '" data-field="' + $key + '">' + $col + '</td>\
			';
    }

    $row_data +=
        '\
        </tr>\
        ';
    $row_data = str_replace('__ID__', $userData[$table.config['id']], $row_data);
    $row_data = str_replace('__NAME_FIELD__', $userData[$table.config['name']], $row_data);
    $row_data = replaceBetweenSquareBrackets($userData, $row_data);
    return $row_data;
}

var createTableTdOnly = function ($data, $userData) {

    $row_data = '';
    for (i = 0; i <= Object.keys($data).length - 1; i++) {
        //$.each($data,function($k, $col) {
        $col = $data[i];
        $row_data +=
            '\
            <td>' + $col + '</td>\
            ';
    }
    $row_data = str_replace('__ID__', $userData[$table.config['id']], $row_data);
    $row_data = str_replace('__NAME_FIELD__', $userData[$table.config['name']], $row_data);
    return $row_data;
}