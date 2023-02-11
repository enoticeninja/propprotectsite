var popover_boxes = {};

function ValidateNumber(num, options) {
    var temp = {};
    temp['result'] = true;
    var min_val = 0,
        max_val = 0,
        min_len = 0,
        max_len = 0;
    if (options.hasOwnProperty('min_val') && options['min_val'] != '' && options['min_val'] != 0 && options['min_val'] != '0') {
        var min_val = options['min_val']
    };
    if (options.hasOwnProperty('max_val') && options['max_val'] != '' && options['max_val'] != 0 && options['max_val'] != '0') {
        var max_val = options['max_val']
    };
    if (num.match(/^\d+$/)) {
        temp['result'] = true;
        temp['message'] = 'OK';
        if (min_val != 0 && num < min_val) {
            temp['result'] = false;
            temp['type'] = 'min_val';
            temp['message'] = 'Minimum Value Can be ' + min_val + '';
            return temp;
        }
        if (max_val != 0 && num > max_val) {
            temp['result'] = false;
            temp['type'] = 'max_val';
            temp['message'] = 'Maximum Value Can be ' + max_val + '';
            return temp;
        }
    } else {
        temp['result'] = false;
        temp['type'] = 'not_number';
        temp['message'] = 'Please enter valid numeric values';
    }
    return temp;
}

function ValidateDecimal(num, options) {
    var temp = {};
    temp['result'] = true;
    var min_val = 0,
        max_val = 0,
        min_len = 0,
        max_len = 0;
    if (options.hasOwnProperty('min_val') && options['min_val'] != '' && options['min_val'] != 0 && options['min_val'] != '0') {
        var min_val = options['min_val']
    };
    if (options.hasOwnProperty('max_val') && options['max_val'] != '' && options['max_val'] != 0 && options['max_val'] != '0') {
        var max_val = options['max_val']
    };
    if (num.match(/^\d+(?:\.\d{1,2})?$/)) {
        temp['result'] = true;
        temp['message'] = 'OK';
        if (min_val != 0 && num < min_val) {
            temp['result'] = false;
            temp['type'] = 'min_val';
            temp['message'] = 'Minimum Value Can be ' + min_val + '';
            return temp;
        }
        if (max_val != 0 && num > max_val) {
            temp['result'] = false;
            temp['type'] = 'max_val';
            temp['message'] = 'Maximum Value Can be ' + max_val + '';
            return temp;
        }
    } else {
        temp['result'] = false;
        temp['type'] = 'not_number';
        temp['message'] = 'Please enter valid decimal values';
    }
    return temp;
}

function ValidateLetters(str, options) {
    var temp = {};
    temp['result'] = true;
    temp['message'] = 'OK';
    var re = /^[a-zA-Z]+$/;
    if (re.test(str)) {
        temp['result'] = true;
        temp['message'] = 'OK';
    } else {
        temp['result'] = false;
        temp['type'] = 'not_letters';
        temp['message'] = 'Only Letters Are Allowed';
    }
    return temp;
}

function ValidateAlphaNumericUnderscore(str, options) {
    var temp = {};
    temp['result'] = true;
    temp['message'] = 'OK';
    var re = /^[a-zA-Z0-9_]+$/;
    if (re.test(str)) {
        temp['result'] = true;
        temp['message'] = 'OK';
    } else {
        temp['result'] = false;
        temp['type'] = 'not_letters';
        temp['message'] = 'Only Number,Letters and Underscore is Allowed';
    }
    return temp;
}

function ValidateAlphaNumericSpaceUnderscoreDash(str, options) {
    var temp = {};

    temp['result'] = true;
    temp['message'] = 'OK';
    var re = /^[a-z\d\-_\s]+$/i;
    if (re.test(str)) {
        temp['result'] = true;
        temp['message'] = 'OK';
    } else {
        temp['result'] = false;
        temp['type'] = 'not_letters';
        temp['message'] = 'Only Number,Letters and Underscore is Allowed';
    }
    return temp;
}

function ValidateMinMax(num, options) {
    var temp = {};
    temp['result'] = true;
    temp['message'] = 'OK';
    var min_val = 0,
        max_val = 0,
        min_len = 0,
        max_len = 0;
    if (options.hasOwnProperty('min_len') && options['min_len'] != '' && options['min_len'] != 0 && options['min_len'] != '0') {
        var min_len = options['min_len']
    }
    if (options.hasOwnProperty('max_len') && options['max_len'] != '' && options['max_len'] != 0 && options['max_len'] != '0') {
        var max_len = options['max_len']
    }

    if (min_len != 0 && num.length < min_len) {
        temp['result'] = false;
        temp['type'] = 'min_len';
        temp['message'] = 'Min Length is ' + min_len + ' Digits/Characters';
        return temp;
    } else if (min_len == 0) {
        if (max_len != 0 && num.length < max_len) {
            temp['result'] = false;
            temp['type'] = 'max_len';
            temp['message'] = 'Please Enter Exactly ' + max_len + ' Digits/Characters';
            return temp;
        }
    }
    if (max_len != 0 && num.length > max_len) {
        temp['result'] = false;
        temp['type'] = 'max_len';
        temp['message'] = 'Max Length Can be ' + max_len + ' Digits/Characters';
        return temp;
    }
    return temp;
}

function ValidateEmail(email) {
    var temp = {};
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    temp['result'] = re.test(email);
    if (temp['result']) {
        temp['message'] = 'Email Format is Correct';
    } else {
        temp['message'] = 'Please Enter a valid Email Address';
    }
    return temp;
}

function ValidateMobile(mobile) {
    var temp = {};
    var re = /[1-9]{1}[0-9]{9}/;
    temp['result'] = re.test(mobile);
    if (temp['result']) {
        temp['message'] = 'Mobile Format is Correct';
    } else {
        temp['message'] = 'Please Enter a valid Mobile number';
    }
    return temp;
}

function ValidateFields(elemVal, vOptions, tField) {
    var vCheck = {};
    vCheck['result'] = true;
    vCheck['message'] = 'OK';

    if (typeof vOptions === 'object' && vOptions.hasOwnProperty('conditional')) {
        var vOptions2 = vOptions.conditional;
        var cond_field = vOptions2.field;
        var cond_field_type = vOptions2.field_type;
        var cond_operation = vOptions2.operation;
        if (cond_field_type == 'date') {
            var start_date = $('#' + cond_field + '').val();
            var end_date = elemVal;
            if (start_date != '' && end_date != '') {
                var d1 = new Date(start_date);
                var d2 = new Date(end_date);
                if (d1.getTime() >= d2.getTime()) {
                    vCheck['result'] = false;
                    vCheck['message'] = vOptions2.message;
                    showErrorOnElement(cond_field, vOptions2.message);
                    showErrorOnElement(tField, vOptions2.message);
                } else {
                    showSuccessOnElement(cond_field, '');
                    showSuccessOnElement(tField, '');
                }
                return vCheck;
            } else {
                return vCheck;
            }

        } else {
            return vCheck;
        }
    } else {
        if (vOptions.hasOwnProperty('min_len') || vOptions.hasOwnProperty('max_len')) {
            vCheck = ValidateMinMax(elemVal, vOptions);
        }

        if (vCheck['result']) {
            switch (vOptions['type']) {
                case 'mobile':
                    vCheck = ValidateMobile(elemVal);
                    break;
                case 'email':
                    vCheck = ValidateEmail(elemVal);
                    break;
                case 'number':
                    vCheck = ValidateNumber(elemVal, vOptions);
                    break;
                case 'decimal':
                    vCheck = ValidateDecimal(elemVal, vOptions);
                    break;
                case 'letter':
                    vCheck = ValidateLetters(elemVal, vOptions);
                    break;
                case 'alphanumeric':
                    vCheck = ValidateAlphaNumericUnderscore(elemVal, vOptions);
                    break;
                case 'alphanumericspaceundash':
                    vCheck = ValidateAlphaNumericSpaceUnderscoreDash(elemVal, vOptions);
                    break;
                case 'all':
                    break;
            }
        }

        return vCheck;
    }
}

function CheckForDuplicate(elemId) {
    var elemVal = $('#' + elemId + '').val();
    if (elemVal == '') {
        return;
    } else {
        $('#' + elemId + '').closest('.form-group').find('.input-group-addon').html('<i class="fa-spin fa fa-spinner"></i>');
        $('#' + elemId + '').closest('.form-group').find('.help-block').html('checking for availability');
        var vCheck = {};
        vCheck['result'] = true;

        if (tools.hasOwnProperty('data') && tools['data'].hasOwnProperty(elemId)) {
            if (elemVal == tools['data2'][elemId]) {
                showSuccessOnElement(elemId, '');
                return true;
            }

        }

        if (tools.hasOwnProperty('validation') && tools['validation'].hasOwnProperty(elemId)) {
            var vOptions = tools['validation'][elemId];
            vCheck = ValidateFields(elemVal, vOptions, elemId);
        }

        if (vCheck['result']) {
            var parent = tools['parent'];
            var module = tools['module'];
            $.ajax({
                method: "POST",
                url: "",
                data: {
                    'action': 'check-for-duplicate',
                    'type': elemId,
                    'value': elemVal,
                    'module': module
                },
                success: function (val) {
                    //console.log(val);
                    var value = JSON.parse(val);
                    if (value['ajaxresult'] === true) {
                        tools['duplicate'][elemId]['status'] = true;
                        showSuccessOnElement(elemId, value['ajaxmessage']);
                        return true;
                    } else {
                        tools['duplicate'][elemId]['status'] = false;
                        showErrorOnElement(elemId, value['ajaxmessage']);
                        return false;
                    }
                },
                error: function (value) {
                    //console.log(value);
                    tools['duplicate'][elemId]['status'] = false;
                    showErrorOnElement(elemId, 'Failed to check !');
                }
            });
        } else {
            tools['duplicate'][elemId]['status'] = false;
            showErrorOnElement(elemId, vCheck['message']);
        }
    }


}

function CheckForDuplicateModule(module, elemId) {
    var elemVal = $('#' + elemId + '').val();
    if (elemVal == '') {
        return;
    } else {
        $('#' + elemId + '').closest('.form-group').find('.input-group-addon').html('<i class="fa-spin fa fa-spinner"></i>');
        $('#' + elemId + '').closest('.form-group').find('.help-block').html('checking for availability');
        var vCheck = {};
        vCheck['result'] = true;
        if (tools.hasOwnProperty('data2') && tools['data2'].hasOwnProperty(elemId)) {
            if (elemVal == tools['data2'][elemId]) {
                showSuccessOnElement(elemId, '');
                return;
            }

        }
        if (tools.hasOwnProperty('data') && tools['data'].hasOwnProperty(elemId)) {
            if (elemVal == tools['data'][elemId]) {
                showSuccessOnElement(elemId, '');
                return;
            }

        }

        if (tools.hasOwnProperty('validation') && tools['validation'].hasOwnProperty(elemId)) {
            var vOptions = tools['validation'][elemId];
            vCheck = ValidateFields(elemVal, vOptions, elemId);
        }

        if (vCheck['result']) {
            $.ajax({
                method: "POST",
                url: "",
                data: {
                    'action': 'check-for-duplicate',
                    'type': elemId,
                    'value': elemVal,
                    'module': module
                },
                success: function (val) {
                    //console.log(val);
                    var value = JSON.parse(val);
                    if (value['ajaxresult'] === true) {
                        tools['duplicate'][elemId]['status'] = true;
                        showSuccessOnElement(elemId, value['ajaxmessage']);

                    } else {
                        tools['duplicate'][elemId]['status'] = false;
                        showErrorOnElement(elemId, value['ajaxmessage']);

                    }
                },
                error: function (value) {
                    //console.log(value);
                    tools['duplicate'][elemId]['status'] = false;
                    showErrorOnElement(elemId, 'Failed to check !');
                }
            });
        } else {
            tools['duplicate'][elemId]['status'] = false;
            showErrorOnElement(elemId, vCheck['message']);
        }
    }


}


function ValidateFieldsAssoc(tools, return_invalid) {
    var input, isReady = true;
    var v_i = 1;
    var first_invalid = '';
    //var rreturn = '';            
    jQuery.each(tools['validation'], function (index, value) {
        //// IF CONDITIONAL REQUIRED
        if (typeof tools['required'][index] === 'object' && tools['required'][index].hasOwnProperty('conditional')) {
            var required = tools['required'][index];
            var field = $('#' + index + '');
            var conditional_field = $('#' + required.conditional.field + '');
            var condition = required.conditional.condition;

            ////IF CONDITIONAL FIELD IS CHECKBOX
            if (required.conditional.type == 'checkbox') {
                var checked = conditional_field.is(':checked');
                if (checked === condition) {
                    ////Validate Here
                    required.conditional.status = false;
                    var tVal = field.val();
                    vCheckTemp = ValidateFields(tVal, value, index);
                    if (vCheckTemp.result === false) {
                        isReady = false;
                        showErrorOnElement(index, vCheckTemp.message);
                    }
                }
            } ////IF IF CONDITIONAL FIELD IS NOT A CHECKBOX
            else {
                if (condition == conditional_field.val()) {
                    ////Validate Here
                    required.conditional.status = false;
                    var tVal = field.val();
                    vCheckTemp = ValidateFields(tVal, value, index);
                    if (vCheckTemp.result === false) {
                        isReady = false;
                        showErrorOnElement(index, vCheckTemp.message);
                    }
                }
            }

            //console.log(value);    

        } //// IF SIMPLE REQUIRED
        else {
            var field = $('#' + index + '');
            ////Validate Here
            var tVal = field.val();
            vCheckTemp = ValidateFields(tVal, value, index);
            if (vCheckTemp.result === false) {
                isReady = false;
                showErrorOnElement(index, vCheckTemp.message);
            }
        }
        if (v_i == 1 && isReady == false) {
            first_invalid = index;
            v_i++;
        }

    });
    if (typeof return_invalid !== 'undefined' && return_invalid == true) {
        var rreturn = [];
        rreturn['invalid'] = first_invalid;
        rreturn['isReady'] = isReady;
        return rreturn;
    } else {
        return isReady;
    }

}


function CheckRequiredAssoc(tools) {
    var assocArray = tools['required'];
    var input, isReady = true;
    //console.log(assocArray);
    $.each(assocArray, function (index, value) {
        //// IF CONDITIONAL REQUIRED
        if (typeof value === 'object' && value.hasOwnProperty('conditional')) {
            var field = $('#' + index + '');
            var conditional_field = $('#' + tools['id_map'][value.conditional.field] + '');
            var condition = value.conditional.condition;
            ////IF CONDITIONAL FIELD IS CHECKBOX
            if (value.conditional.type == 'checkbox') {
                var checked = conditional_field.is(':checked');
                var checkedval = conditional_field.val();
                if (checked == condition) {
                    if ($(field).val() === null || $(field).val().trim() == '') {
                        value.conditional.status = false;
                        isReady = false;
                        showErrorOnElement(index, value.conditional.message);
                    } else {
                        showSuccessOnElement(index);
                    }
                }
            } ////IF IF CONDITIONAL FIELD IS NOT A CHECKBOX
            else {
                ///// Since condition values can be multiple therefore only one of them must be true for this field to be required                
                var isOrConditionReady = true;
                console.log(condition);
                $.each(condition, function (iIndex, iCon) {
                    if (iCon == conditional_field.val()) {
                        isOrConditionReady = false;
                    }
                });

                if (!isOrConditionReady) {
                    if ($(field).val() === null || $(field).val().trim() == '') {
                        value.conditional.status = false;
                        isReady = false;
                        showErrorOnElement(index, value.message);
                    } else {
                        showSuccessOnElement(index);
                    }
                }
            }
        } //// IF SIMPLE REQUIRED
        else {
            var field = $('#' + index + '');
            var type = field.attr('type');
            /* console.log(index);
            console.log(type); */
            /* console.log(document.getElementById(index).type);
            console.log(field[0].type); */
            if (type == 'file') {
                if ($(field).get(0).files.length === 0) {
                    value.status = false;
                    isReady = false;
                    showErrorOnElement(index, value.message);
                } else {
                    showSuccessOnElement(index);
                }
            } 
            else {
                /* console.log($(field).val()); */
                if ($(field).val() === null || $.trim((field).val()) == '') {
                    value.status = false;
                    isReady = false;
                    showErrorOnElement(index, value.message);
                } else {
                    showSuccessOnElement(index);
                }
            }
        }
    });
    return isReady;
}


function validateTabElements(toolIndex, toolValue) {
    if (toolValue.hasOwnProperty('validation')) {
        $.each(toolValue['validation'], function (tField, tOptions) {
            var tVal = $('#' + tField + '').val();
            var vCheck = ValidateFields(tVal, tOptions, tField);
            isValidated = vCheck.result;
            popover_boxes['li_' + toolIndex + ''] = {};
            popover_boxes['li_' + toolIndex + '']['title'] = '';
            if (vCheck.result === false) {
                popover_boxes['li_' + toolIndex + '']['title'] += vCheck.message + '</br>';
                popover_boxes['li_' + toolIndex + '']['message'] = 'Please Correct the Duplicate Values';
                isValidated = false;
            } else {
                //resetMtTabState('tab_'+toolIndex+'')
            }
        });
    }
    if (toolValue.hasOwnProperty('required')) {
        isReady = CheckRequiredAssoc(toolValue['required']);
        if (isReady === false) {
            popover_boxes['li_' + toolIndex + ''] = {};
            popover_boxes['li_' + toolIndex + '']['title'] = 'Missing Fields';
            popover_boxes['li_' + toolIndex + '']['message'] = 'Please Fill in the Required Fields';
            // showErrorOnMtTab('tab_'+toolIndex+'','Missing Fields');
        } else {
            //resetMtTabState('tab_'+toolIndex+'');                
        }
    }

    //console.log(isValidated);    
    //console.log(isReady);    

}

function showErrorPopovers(elements, refresh) {
    elements.each(function (i, element) {
        var temp_id = $(element).attr('id');
        placementPop = 'left';
        if (popover_boxes.hasOwnProperty(temp_id)) {
            //placementPop = (placementPop == 'left') ? 'right' : 'left';             
            //$(element).addClass('has-error');
            $(element).find('i').removeClass('hidden');
            $(element).find('i').popover({
                title: '<h6 class="font-white"><span class="fa fa-warning font-white"></span> ' + popover_boxes[temp_id]['title'] + '</h6>',
                content: '',
                trigger: 'hover',
                html: true,
                container: 'body',
                placement: placementPop,
            });
            $element = $(element);
            if ($element.find('i').data()['bs.popover'].tip().hasClass('in') && !refresh) {

            } else {
                $(element).find('i').popover('show');
            }

        } else {

            //$(element).removeClass('has-error');
            $(element).find('i').addClass('hidden');
            $(element).find('i').popover('destroy');
        }


    });
}


function showErrorPopoversOnElements(element, message, refresh) {
    if (!message) messaage = 'Error';
    var popover_template =
        '\
	 <div class="popover has-error" role="tooltip" data-background-color="red" style="top:1000px;left:100px">\
		 <div class="arrow"></div>\
		 <div class="popover-content">\
		 \
		 </div>\
	  </div>\
	';
    if (BOOTSTRAP_VERSION == '4') {
        popover_template =
            '\
		 <div class="popover has-error" role="tooltip" data-background-color="red" style="top:1000px;left:100px">\
			 <div class="arrow"></div>\
			 <div class="popover-body">\
			 \
			 </div>\
		  </div>\
		';
    }
    var temp_id = $('#' + element + '').attr('id');
    placementPop = 'top';
    var $element = $('#' + element + '');
    if($('#' + element + '-holder').length)$element = $('#' + element + '-holder');
    $element.popover({
        title: '<h6 class="font-white"><span class="fa fa-warning font-white"></span> Error</h6>',
        content: message,
        trigger: 'manual',
        html: true,
        container: $element.closest('div'),
        placement: placementPop,
        template: popover_template
    }).addClass('has-warning');
    $element.popover('show');

}


function validateAndRequire(tools) {
    var isReady = true;
    var isValidated = true;

    if (tools.hasOwnProperty('required')) {
        isReady = CheckRequiredAssoc(tools);
    }
    if (tools.hasOwnProperty('validation')) {
        $.each(tools['validation'], function (tField, tOptions) {
            var tVal = $('#' + tField + '').val();
            vCheck = ValidateFields(tVal, tOptions, tField);
            if (vCheck.result === false) {
                showErrorOnElement(tField, vCheck.message);
                isValidated = false;
            } else {
                showSuccessOnElement(tField);
            }
        });
    }

    var isDuplicateOk = true;
    if (tools.hasOwnProperty('duplicate')) {
        $.each(tools['duplicate'], function (k, v) {
            if (!v['status']) {
                showErrorOnElement(k, 'Already exists! Please enter a different value');
                isDuplicateOk = false;
            }
        });
    }
    var check = [];
    check['validated'] = isValidated;
    check['required'] = isReady;
    check['duplicate'] = isDuplicateOk;
    return check;
}

function matchPasswords(password, password2) {
    $(document).on('change', '#' + password + ', #' + password2 + '', function () {
        var pass1 = $('#' + password + '').val();
        var pass2 = $('#' + password2 + '').val();
        var vOptions1 = tools['validation'][password];
        var vOptions2 = tools['validation'][password2];
        vCheck1 = ValidateFields(pass1, vOptions1);
        vCheck2 = ValidateFields(pass2, vOptions2);
        if (vCheck1['result'] && vCheck2['result']) {
            if (pass1 != pass2) {
                //$('#__UNIQUE_ID__-password2').val('');
                showErrorOnElement(password2, 'Passwords Do Not Match');
                showErrorOnElement(password, 'Passwords Do Not Match');
                showErrorPopoversOnElements(password2, 'Passwords Do Not Match');
                showErrorPopoversOnElements(password, 'Passwords Do Not Match');
            } else {
                if (BOOTSTRAP_VERSION == '4') {
                    $('#' + password2 + '-holder').popover('dispose');
                    $('#' + password + '-holder').popover('dispose');
                } else {
                    $('#' + password2 + '-holder').popover('destroy');
                    $('#' + password + '-holder').popover('destroy');
                }
                showSuccessOnElement(password2, '');
                showSuccessOnElement(password, '');
            }
        } else {
            if (this.id == password) {
                var msg = vCheck1.message;
            } else {
                var msg = vCheck2.message;
            }
            showErrorOnElement(this.id, msg);
        }
    });
}

function toggleDependentFields(tools, id) {
    delay = 500;
    $.each(tools['dependency'][id], function (key, dep_config) {
        var elem_val = $.trim($('#' + id).val());
        if ($('#' + id + '').is(':checkbox')) {
            if ($('#' + id + '').is(':checked')) {
                elem_val = '1';
            } else {
                elem_val = '0';
            }
        }

        if (key == elem_val) {
            if (tools['dependency'][id][elem_val]) {
                if (tools['dependency'][id][elem_val]['show']) {
                    $.each(tools['dependency'][id][elem_val]['show'], function (i, field) {
                        var elem_id = tools.id_map[field];
                        elem = $('#' + elem_id);
                        elem.removeClass('hidden');
                        elem.closest('.form-common-element-wrapper').removeClass('hidden zoomOut');
                        elem.closest('.form-common-element-wrapper').addClass('animated ' + delay + ' zoomIn');
                    });
                }
                if (tools['dependency'][id][elem_val]['hide']) {
                    $.each(tools['dependency'][id][elem_val]['hide'], function (i, field) {
                        var elem_id = tools.id_map[field];
                        elem = $('#' + elem_id);
                        elem.addClass('hidden');
                        elem.closest('.form-common-element-wrapper').addClass('animated ' + delay + ' zoomOut hidden');
                    });
                }
            }
        }
        else {
            if (tools['dependency'][id][key]) {
                if (tools['dependency'][id][key]['hide']) {
                    $.each(tools['dependency'][id][key]['hide'], function (i, field) {
                        var elem_id = tools.id_map[field];
                        elem = $('#' + elem_id);
                        elem.removeClass('hidden');
                        elem.closest('.form-common-element-wrapper').removeClass('hidden zoomOut');
                        elem.closest('.form-common-element-wrapper').addClass('animated ' + delay + ' zoomIn');
                    });
                }
                if (tools['dependency'][id][key]['show']) {
                    $.each(tools['dependency'][id][key]['show'], function (i, field) {
                        var elem_id = tools.id_map[field];
                        elem = $('#' + elem_id);
                        elem.addClass('hidden');
                        elem.closest('.form-common-element-wrapper').addClass('animated ' + delay + ' zoomOut hidden');
                    });
                }
            }
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    $(document).on('change keyup', '.positive-number', function (e) {
        if ($.trim($(this).val()) < 0) {
            $(this).val(0);
        }
    });
});