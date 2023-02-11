var FormGenerator = function (options) {
	var $buttons = [];
	var $include_css = [];
	var $include_js = [];
	var $this = this;
	this.form_group_type = 'floating-12-12';
	var $formClass = new Form();
	this.fields = [];
	this.labels = [];
	this.icons = [];
	this.options = {};
	this.initialOptions = {}; //// For somethings that should not be changed after ajaxFetch
	this.jsFunction = "";
	this.priorityJsList = [];
	this.form = {};
	this.ajax_dependency = {};
	this.field_array = {
		'id': '',
		'name': '',
		'name_prefix': false,
		'icon': '',
		'label': '',
		'class': '',
		'width': '6',
		'left_offset': '0',
		'right_offset': '0',
		'extra': '',
		'type': 'input',
		'options': [],
		'is_value_array': false, ///// IN CASE OF MULTIPLE SELECT IS THE VALUE FROM DATABASE IN FORM OF AN ARRAY
		'seperator': '', //// IN CASE OF MULTIPLE SELECT HOW IS THE VALUES SEPERATED EG. CSV ETC.
		'code_begin': '', //// HTML CODE BEFORE ELEMENT	
		'code_end': '', //// HRML CODE AFTER ELEMENT
		'evalcode': '', //// BEFORE EACH ELEMENT IS CREATED			
		'eval': '', /// FOR EACH ELEMENT
		'html': '',
		'hidden': false, ////hidden class on common element form wrapper
		'one_image': false, //// IN case of image if multiple images
		'multiple_image': false, //// IN case of image if multiple images
		'image_button': false, /// in csse of image field, specify custom button	
		'placeholder': '',
		'start_date': '', //// USED IN DATERANGE : THE START DATE FIELD NAME
		'end_date': '', ///// USED IN DATERANGE : THE END DATE FIELD NAME
		'value': '',
		'default': '',
		'required': false,
		'validation': false,
		'allowed_duplicate': true,
		'inputType': 'text',
		'form_group_type': 'floating-12-12',
		'help': '',
		'js': '',
		'max_length': 0,
		'has_icons': false,
		'live_search': false,
		'select2': false,
		'color': '',
		'checked': '',
		'options_func': '',
		'code': '',
		'popover': false,
	};
	this.btnSaveConfig = {};
	this.isAjax = true;
	this.rowFormClass = ''; //eg animated fadeIn
	this.actionCallBacks = {
		'save': 'prependTableRow',
		'update': 'replaceTableRow'
	};
	this.formFunctionList = [];
	this.saveFunctionList = [];
	this.tools = {};
	var $form_config;
	this.creatingRepeatForm = false;
	this.creatingNewRepeatForm = false;
	this.totalMultipleForms = 0;
	var hasChildForm = false,isChildForm = false,initialUniqueId,currentUniqueId,isMultipleForm = false,$multipleInitOnly=false;;	

	var $next_form = '',$new_field = '',$form = {}, $tempJsTools = '', $tempJsElements = '', $element = {}, $field = {}, image_fields = '', width_count_tracker ;
	this.childClass = {}; /////Hold the child class, if has child form

	this.construct = function (options) {
		$this.initialOptions = $.extend({}, options);
		initialUniqueId = options.unique_id;
		this.options.initialUniqueId = options.unique_id;
		if (options.actionCallBacks) $this.actionCallBacks = options.actionCallBacks;
		if (!options.isAjax) {
			this.init(options);
		}
		else {
			this.ajaxFetch(options);
			return $this;
		}
	};
	this.init = function (options) {
		this.labels = options.labels;
		this.fields = options.all_fields;
		this.icons = options.icons;
		this.ajax_dependency = options.ajax_dependency;
		$this.options = $.extend(true, this.getAllFormConfig([]), options);
		if ($this.initialOptions['show_form_in_container']) $this.options['show_form_in_container'] = $this.initialOptions['show_form_in_container'];
		if ($this.initialOptions['show_buttons_in_container']) $this.options['show_buttons_in_container'] = $this.initialOptions['show_buttons_in_container'];
		if ($this.initialOptions['after_form_created_callback']) $this.options['after_form_created_callback'] = $this.initialOptions['after_form_created_callback'];
		this.labels = $this.options.labels;
		this.fields = $this.options.all_fields;
		this.icons = $this.options.icons;
		this.ajax_dependency = $this.options.ajax_dependency;
		//console.log(this.$this.options);
	}

	this.ajaxFetch = function ($data) {
		if (typeof ($data.dont_load) == "undefined") {
			startLoading();
		}
		var form = $data.form;
		var data = $data.data;
		var next_data = $data.next_data;
		var e = $data.e;
		var formData = [];
		if (typeof form !== 'undefined' && $.trim(form) != '') {
			formData = $('#' + form + '').serializeArray();
		}
		if (typeof data !== 'undefined' && !$.isEmptyObject(data)) {
			$.each(data, function (n, v) {
				formData.push({
					name: n,
					value: v
				});
			});
		}
		if (typeof next_data === 'undefined') {
			next_data = {};
		}
		formData['next_data'] = [];
		$.each(next_data, function (n, v) {
			formData.push({
				name: n,
				value: v
			});
		});

		if (typeof e !== 'undefined' && $.trim(e) != '') formData.push({
			name: 'button_clicked',
			value: $(e).attr('id')
		});

		$.ajax({
			type: 'POST',
			url: '',
			data: formData,
			success: function (val) {
				try {
					var value = JSON.parse(val);
					//console.log(value);
				} catch (err) {
					console.log(val);
					stopLoading();
				}
				if (value.ajaxresult == true) {
					/* if (value.hasOwnProperty('jsFunction')) {
						eval(value.jsFunction);
					} */
					if (value.abort_form) {
						stopLoading();
						return;
					}

					////combine initial options with the fectched options, initial options override fetched options
					//var combinedOptions = $.extend(data,value);
					var combinedOptions = $.extend(true, value, data);
					initialUniqueId = value.unique_id;
					$this.init(combinedOptions);
					$this.getForm();
					//$this.executeAllJs();
					stopLoading();
				} else {
					stopLoading();
				}

			}
		});
	}

	this.ajaxSave = function ($data) {

		if (typeof ($data.dont_load) == "undefined") {
			startLoading();
		}
		var form = $data.form;
		var data = $data.data;
		var next_data = $data.next_data;
		var callback = $data.callback;
		var e = $data.e;
		var formData = {};

		var checkedRequired = false;
		var checkedValidation = false;
		var checkedDuplicate = false;
		var validateFields = true;
		var unique_id = $data.unique_id;
		//console.log($this.tools);
		if (validateFields) {
			$.each($this.tools, function ($unq_id, tools) {
				//tools = (typeof toolsAll[unique_id] !== 'undefined') ? toolsAll[unique_id] : [];
				check = validateAndRequire(tools);
				checkedRequired = check['required'];
				checkedValidation = check['validated'];
				checkedDuplicate = check['duplicate'];
			});
		}
		else{
			checkedRequired = true;
			checkedValidation = true;
			checkedDuplicate = true;			
		}
		if (typeof form !== 'undefined' && $.trim(form) != '') {
			//formData = $('#'+form+'').serializeArray();
			var formElem = $('#' + form + '')[0]; // You need to use standard javascript object here
			formData = new FormData(formElem);
		} 
		else {
			formData = new FormData();
		}
		if (typeof data !== 'undefined' && !$.isEmptyObject(data)) {
			$.each(data, function (n, v) {
				//formData.push({name:n, value:v});
				formData.append(n, v);
			});
		}
		if (typeof next_data === 'undefined') {
			next_data = {};
		}
		formData['next_data'] = [];
		$.each(next_data, function (n, v) {
			//formData.push({name:n, value:v});
			formData.append(n, v);
		});

		if (typeof e !== 'undefined' && $.trim(e) != '') formData.append('button_clicked', $(e).attr('id'));
		if (checkedRequired && checkedValidation && checkedDuplicate) {

			///// IF REQUIRES ANOTHER FORM TO BE SUBMITTED BEFORE THIS CAN BE SAVED
			if ($this.options.requires_form_before_save) {
				var dataAnotherForm = $this.options.required_form_options;
				dataAnotherForm['has_form_waiting'] = true;
				dataAnotherForm['waiting_form'] = $this;
				dataAnotherForm['waiting_form']['formData'] = formData;
				dataAnotherForm['waiting_form']['callback'] = callback;
				$this.options.formData = formData;
				var formClass = new FormGenerator(dataAnotherForm);
			} 
			else {
				$this.ajax(formData, callback);
			}
		} 
		else {
			console.log('Something wrong with validation');
			$.each($this.tools, function ($unq_id, tools) {
				$.each(tools['required'], function ($tempK, req) {
					if(!req.status)console.log(req);
				});
				$.each(tools['validation'], function ($tempK, req) {
					if(!req.status)console.log(req);
				});
				$.each(tools['duplicate'], function ($tempK, req) {
					if(!req.status)console.log(req);
				});
			});
			stopLoading();
		}

	}

	this.getAnotherForm = function () {
		let data = {};
		data['data'] = {
			'action': 'get_otp_form',
			'module': 'refund'
		};
		data['isAjax'] = true;
		data['e'] = this;
		let formClass = new FormGenerator(data);
		formClass.actionCallBacks = {
			'save': 'redirectToEdit',
			'update': 'doNothing'
		};
	}

	this.ajax = function (formData, callback) {
		return new Promise(function (resolve, reject) {
			$.ajax({
				type: 'POST',
				url: '',
				enctype: 'multipart/form-data',
				contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
				processData: false, // NEEDED, DON'T OMIT THIS
				data: formData,
				success: function (val) {
					try {
						var value = JSON.parse(val);
					} catch (err) {
						console.log(val);
						stopLoading();
					}
					if (value.ajaxresult == true) {
						if (value.hasOwnProperty('jsFunction')) {
							eval(value.jsFunction);
						}
						if (value.validation_error) {
							$.each($this.tools, function ($unq_id, tools) {
								check = validateAndRequire(tools);
								checkedRequired = check['required'];
								checkedValidation = check['validated'];
								checkedDuplicate = check['duplicate'];
								//console.log(check);
							});
							stopLoading();
							return;
						}
						console.log(callback);
						if (typeof (callback) != 'undefined' && callback != '') {
							window[callback](value);
						}
						//console.log($this.saveFunctionList);
						for (i = 0; i < $this.saveFunctionList.length; i++) {
							$this.saveFunctionList[i]();
						}

						if ($this.initialOptions.has_form_waiting) {
							var waiting_form = $this.initialOptions.waiting_form;
							waiting_form.ajax(waiting_form.formData, waiting_form.callback);
						}
						stopLoading();
					} else {
						console.log(value);
						stopLoading();
					}
					resolve(true);
				}
			});

		});
	}

	this.getAllFormConfig = function ($data) {
		/////Form Configuration //////////////
		{
			$return = {
				'form_type': 'new',
				'request_data': {},
				'form_id': 'new_form',
				'is_multiple_form': false,
				'min_multiple_forms': false,
				'max_multiple_forms': false,
				'nested_form': false,
				'form_depth': 0,
				'extra_html': '',
				'extra_unreplaced_html': '',
				'custom_wrapper_begin': '',
				'custom_wrapper_end': '',
				///// IMP: set a unique id as hidden input and save it in session variable to verify that the form called was form submitted
				'portlet_title': 'Add New ',
				'portlet_icon': '',
				'submit_id': 'submit_id',
				'call_type': isset($data['call_type']) ? $data['call_type'] : 'ajax',
				'return_form_as': isset($data['return_form_as']) ? $data['return_form_as'] : 'modal',
				'fetch_data': {
					'enabled': false
				},
				'return_action_buttons': true,
				'wrap_in_form': false,
				'has_data': false,
				'parent': 'permission_module_new', //// WILL BE USED MOSTLY IN CASE OF MULTIPLE TABS OF SAME OR DIFFERENT CLASSES, TO INDENTIFY WHICH ELEMENT BELONGS WHERE
				'module': $this.dbTable, ////ALWAYS THE DATABASE TABLE NAME OR ANY NAME SPECIFIED IN THE RouterX
				'before_processing': false,
				'after_processing': false,
				'unique_element_id': '',
				'action': '',
				'form_group_type': $this.form_group_type,
				'hidden': {
					'enabled': false,
					'html': ''
				},
				'buttons': {
					'enabled': true,
					'html': '',
					'array': {
						'save': {
							'actions': {
								'form_type': 'new', /////MAY NOT BE NEEDED
								'action': 'save'
							},
							'next_actions': {},
							'class': 'bg-less-glossy-info',
							'func': 'CommonFunc2',
							'title': 'Save',
							'extra': '',
						}
					}
				},
				'dependency': {},
				'duplicate': {},
				'validation': {},
				'unique_in_level': {},
				'duplicate_combination': {},
				'jsFunction': '',
				'jsFunctionLast': '',
				'jsModalClose': '',
				'requires_form_before_save': false,
				'required_form_options': {},
				'has_form_waiting': false,
				'waiting_form': null,
				'chained_form_js': '',
				'modal_width': '70%',
				'default_insert': { //// ????? these values would be forced insert
					'enabled': false,
					//'date_created':date('Y-m-d H-i-s'},
					//'created_by':$_SESSION[get_session_values('id'}]	
				},
				'name_prefix': '',
				'id_prefix': true, /// If true ids will be prefixed with parent
				'fields_function': {
					'enabled': false,
					'function': 'getSecondaryForm'
				},
				'has_custom_fields': false,
				'small_input': true,
				'custom_fields': {},
				'fields': {}, //// SPECIFY THE ORDER THAT WE WANT, THIS WILL BE THE MAIN GROUP OF THE FORM ELEMENTS
				'required': {},
				'conditional_required': {},
				'labels': {},
				'icons': {},
				'default_values': { //// these values are for giving initial values in the form and can be changed
					'enabled': false,
					'status': 'active'
				},
				'override_fields': {},
				'custom_field_width': {},
				'show_form_in_container': null,
				'show_buttons_in_container': null,
				'child_form_wrapper': null,
				'custom_child_form_container': null,
				'multiple_form_wrapper': null,
				'custom_multiple_form_container': null,
				'min_multiple_rows': 1,
				'child_form': {},
				'child_form_titles': {},
				'child_name': 'Item',
				'non_db_fileds': [],
				'popover_error': false,
				'override_non_db_fileds': {
					'enabled': false
				}
			};

		}
		/////End Form Configuration //////////////         
		return $return;
	}

	this.createFormElement = function ($field, $element, $dbData) {
		var $new_field = '';
		var $new_js = '';
		var $__dbData = {};
		var $return = {};
		if ($element['popover']) {
			var $popover_template =
				'\
			 <div class="popover" role="tooltip" data-background-color="orange" style="top:50px;left:100px">\
				 <div class="arrow"></div>\
				 <div class="popover-content">\
				 \
				 </div>\
			  </div>\
			';
			$this.formFunctionList.push(
				function () {
					var pop_on = $('#' + $element['id'] + '-holder');
					pop_on.popover({
						title: '',
						content: '' + $element['popover'] + '',
						trigger: 'hover click',
						html: true,
						container: pop_on,
						placement: 'top',
						template: '' + $popover_template + '',
						show: function () {
							$(this).fadeIn('slow');
						}
					});
					pop_on.popover('show');
				});
		}
		if ($element['hidden']) $element['class'] += ' hidden'; { ///START CREATING THE FORM ELEMENTS
			if (($element['max_length'] > 0)) {
				$this.formFunctionList.push(
					function () {
						$('#' + $element['id'] + '').maxlength({
							alwaysShow: !0,
							warningClass: 'label label-success',
							limitReachedClass: 'label label-danger',
							validate: !0
						});
					});
				$element['extra'] += ' maxlength="' + $element['max_length'] + '"';
			}

			//EVALCODE
			eval($element['evalcode']);

			if (isset($element['required']) && $element['required']) {
				$element['label'] = '<span class="font-red">*</span> ' + $element['label'] + '';
			}
			if ($element['type'] == 'hidden') {
				$new_field += '<input type="hidden" name="' + $element['name'] + '" id="' + $element['id'] + '" value="' + $element['value'] + '">';
			} 
			else if ($element['type'] == 'checkbox') {
				//$element['checked'] = (isset($__dbData[$field]) && $__dbData[$field] == $element['value']) ? true : false;
				$new_field += $formClass.checkboxFromArray($element);
			} 
			else if ($element['type'] == 'checkboxswitch') {
				$new_field += $formClass.checkBoxSwitch($element);
			} 
			else if ($element['type'] == 'checkboxGroupFromArray') {
				$new_field += $formClass.checkboxGroupFromArray($element);
			}
			else if ($element['type'] == 'advancedtime') {
				if ($element['value'] == '00:00:00') {
					$element['value'] = new Date().toJSON().slice(0, 19).replace('T', ' ');
				}
				$new_field += $formClass.advancedDateTimeFromArray($element);
				$date_format = getDefault($element['date_format'], 'yyyy-m-d');
				$picker_position = getDefault($element['picker_position'], 'bottom left');
				$this.formFunctionList.push(
					function () {
						$('#' + $element['id'] + '').timepicker({
							autoclose: !0,
							todayHighlight: true,
							isRTL: false,
							format: $date_format,
							orientation: $picker_position
						});
					});
			} 
			else if ($element['type'] == 'advanceddatetime') {
				if ($element['value'] == '0000-00-00 00:00:00') {
					$element['value'] = new Date().toJSON().slice(0, 19).replace('T', ' ');
				}
				$new_field += $formClass.advancedDateTimeFromArray($element);
				$date_format = getDefault($element['date_format'], 'yyyy-m-d HH:ii:ss');
				$picker_position = getDefault($element['picker_position'], 'bottom-left');
				$this.formFunctionList.push(
					function () {
						$('#' + $element['id'] + '').datetimepicker({
							autoclose: !0,
							isRTL: false,
							format: $date_format,
							pickerPosition: $picker_position
						});
					});
			}  
			else if ($element['type'] == 'advanceddate') {
				//loadCSS(FRONTEND_SITE_PATH+'core_theme/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css');
				if ($element['value'] == '0000-00-00') {
					$element['value'] = new Date().toJSON().slice(0, 19).replace('T', ' ');
				}
				$element['inputType'] = 'search';
				$new_field += $formClass.inputFromArray($element);
				$date_format = getDefault($element['date_format'], 'yyyy-m-d');
				$picker_position = getDefault($element['picker_position'], 'bottom-left');
				$this.formFunctionList.push(
					function () {
						$('#' + $element['id'] + '').datepicker({
							todayHighlight: true,
							orientation: "bottom left",
							templates: {
								leftArrow: '<i class="la la-angle-left"></i>',
								rightArrow: '<i class="la la-angle-right"></i>'
							},							
							autoclose: !0,
							isRTL: false,
							format: 'yyyy-m-d',
							pickerPosition: $picker_position
						});
					
					});
			}  
			else if ($element['type'] == 'bootstraptimepicker') {
				if ($element['value'] == '0000-00-00 00:00:00') {
					$element['value'] = new Date().toJSON().slice(0, 19).replace('T', ' ');
				}
				$new_field += $formClass.inputFromArray($element);
				$date_format = getDefault($element['date_format'], 'yyyy-m-d HH:ii:ss');
				$picker_position = getDefault($element['picker_position'], 'bottom-left');
				$this.formFunctionList.push(
					function () {
						$('#' + $element['id'] + '').timepicker({autoclose:!0,minuteStep:1,secondStep:1,showSeconds:1,showMeridian:!1});
					});
			} 
			else if ($element['type'] == 'upload_single_image') {
				var ifValue = '';
				var ifNoValue = '';
				var valueForField = '';
				var ifNotValueHidden = '';
				var ifValueHidden = '';
				var default_image = 'core_theme/images/upload-image.png';
				if ($element['image_options']['default_image']) default_image = $element['image_options']['default_image'];
				if ($element['value']) {
					ifValueHidden = 'hidden';
					ifValue = '<a href="' + FRONTEND_SITE_PATH + '' + $dbData['image_path'] + '' + $dbData['image'] + '" data-fancybox="gallery" data-caption="" >\
					<img src="' + FRONTEND_SITE_PATH + '' + $dbData['image_path'] + '' + $dbData['image'] + '" class="img-responsive" id="__UNIQUE_ID___image" style="max-height:100px;margin:0 auto"></a>';
				} else {
					ifNotValueHidden = 'hidden';
					ifNoValue = '<img src="' + FRONTEND_SITE_PATH + default_image + '" class="img-responsive" id="__UNIQUE_ID___image" style="max-height:100px;margin:0 auto">';
				}
				$new_field +=
					'\
				<div class="card d-flex justify-content-center align-items-center"  style="">\
					<div class="actions ' + ifNotValueHidden + '" id="__UNIQUE_ID___image_actions">\
					<div class="btn-group floating">\
						<a class="btn yellow-gold btn-circle btn-icon-only dropdown-toggle" href="javascript:;" data-toggle="dropdown" aria-expanded="false">\
							<i class="fa fa-cogs"></i>\
						</a>\
						<ul class="dropdown-menu colored-buttons pull-right" role="menu">\
							<li>\
								<a href="javascript:;" class="btn red  font-white" onclick="CommonFunc2Confirmation({\'unique_id\':\'__UNIQUE_ID__\',\'module\':\'__MODULE__\',\'id\':\'__ID__\',\'action\':\'delete_image\'},\'\',this)"><i class="fa fa-trash"></i> Delete</a>\
							</li>\
							<li>\
							</li>\
						</ul>\
					</div>\
					</div>\
					<div class="logo_holder" id="__UNIQUE_ID___dom_image_holder">\
					' + ifValue + '\
					' + ifNoValue + '\
					</div>\
					<span class="btn yellow-gold fileinput-button ' + ifValueHidden + '" id="__UNIQUE_ID___new_img_btn">\
						<i class="fa fa-plus"></i>\
						<span> Add Image ... </span>\
						<input type="file"  id="' + $element['id'] + '_image" name="' + $element['name'] + '"  data-showpopovererror="true" data-unique_id="'+currentUniqueId+'">\
						<input type="hidden"  id="' + $element['id'] + '" data-showpopovererror="true" data-unique_id="'+currentUniqueId+'" value="'+$element['value']+'">\
					</span>\
				</div>\
				';
				$this.formFunctionList.push(
					function () {
						$('#' + $element['id'] + '_image').change(function(){
							$('#' + $element['id'] + '').val('has image');
						});
					});	
					
				if ($this.options['is_edit']) {
					$this.formFunctionList.push(
						function () {
							var imgEditorOptions = {
								'parent': currentUniqueId,
								'module': $this.options['module'],
								'imageProcessingUrl': 'imageProcessing',
								'modal_title': 'Upload Image',
								'upload_path': 'uploads/',
								'one_image': true,
								'direct_crop_upload': false,
								'strict_crop': false,
								'cropWidth': '200',
								'cropHeight': '200',
								'allowResizeInCrop': true,
								'allowSelectInCrop': true,
								'final_action': 'upload_image',
								'file_field': $element['id']+'_image'
							};
							if ($element['image_options']) {
								$.each($element['image_options'], function ($key, $val) {
									imgEditorOptions[$key] = $val;
								});
							}
							var imgEditor = new ImageEditor(imgEditorOptions);

							let uploadData = {
								'parent': currentUniqueId,
								'module': $this.options['module']
							};
							if (isset($form_config['request_data']['id'])) {
								uploadData['id'] = $form_config['request_data']['id'];
							}
							if ($element['image_upload_data']) {
								$.each($element['image_upload_data'], function ($key, $val) {
									uploadData[$key] = $val;
								});
							}
							imgEditor.setAjaxImageUploadData(uploadData);
						});

				} 
				else {
					$this.formFunctionList.push(
						function () {
							$('#' + $element['id'] + '_image').on('change', function () {
								var unqId = $(this).data('unique_id');
								DisplaySelectedImageOnNewForm(this, $('#' + unqId + '_image'));
								//$('#'+$this.options['unique_id']+'_image_actions').removeClass('hidden');
								//$('#'+$this.options['unique_id']+'_new_img_btn').addClass('hidden');
								$('#' + unqId + '_default_image').addClass('hidden');
								//$('#'+$this.options['unique_id']+'_image').attr('src','$image_site_path_link');
								$('#' + unqId + '_image').removeClass('hidden');
							});
						});

				}
			} 
			else if ($element['type'] == 'upload_multiple_image') { //// TODO : MAKE CHANGES ACCORDING TO upload_singlw_image FIELD RECENT CHANGES (if required)
				$new_field +=
					'\
				<div class="card text-center" style="height:90%">\
					<span class="btn yellow-gold fileinput-button" id="__UNIQUE_ID___new_img_btn">\
						<i class="fa fa-plus"></i>\
						<span> Add Images ... </span>\
						<input type="file" multiple="true" id="' + currentUniqueId + '-file" data-unique_id="'+currentUniqueId+'">\
					</span>\
				</div>\
				';
				$this.formFunctionList.push(
					function () {
						var imgEditorOptions = {
							'parent': currentUniqueId,
							'module': $this.options['module'],
							'imageProcessingUrl': 'imageProcessing',
							'modal_title': 'Upload Image',
							'upload_path': 'uploads/',
							'multiple_image': true,
							'direct_crop_upload': true,
							'strict_crop': false,
							'cropWidth': '200',
							'cropHeight': '200',
							'allowResizeInCrop': true,
							'allowSelectInCrop': true,
							'final_action': 'upload_image',
							'file_field': '' + currentUniqueId + '-file'
						};
						if ($element['image_options']) {
							$.each($element['image_options'], function ($key, $val) {
								imgEditorOptions[$key] = $val;
							});
						}
						var imgEditor = new ImageEditor(imgEditorOptions);

						let uploadData = {
							'parent': currentUniqueId,
							'module': $this.options['module']
						};
						if (isset($form_config['request_data']['id'])) {
							uploadData['id'] = $form_config['request_data']['id'];
						}
						if ($element['image_upload_data']) {
							$.each($element['image_upload_data'], function ($key, $val) {
								uploadData[$key] = $val;
							});
						}
						imgEditor.setAjaxImageUploadData(uploadData);
					});

			} 
			else if ($element['type'] == 'upload_logo') { //// TODO : MAKE CHANGES ACCORDING TO upload_singlw_image FIELD RECENT CHANGES
				var ifValue = '';
				var ifNoValue = '';
				var ifNotValueHidden = '';
				var ifValueHidden = '';
				if ($element['value']) {
					ifValueHidden = 'hidden';
					ifValue = '<a href="' + FRONTEND_SITE_PATH + '' + $dbData['image_path'] + '' + $dbData['image'] + '" data-fancybox="gallery" data-caption="" >\
					<img src="' + FRONTEND_SITE_PATH + '' + $dbData['image_path'] + '' + $dbData['image'] + '" class="img-responsive" id="__UNIQUE_ID___image" style="max-height:100px;margin:0 auto"></a>';
				} else {
					ifNotValueHidden = 'hidden';
					ifNoValue = '<a href="javascript:;" \
					<img src="' + FRONTEND_SITE_PATH + default_image + '" class="img-responsive" id="__UNIQUE_ID___image" style="max-height:100px;margin:0 auto"></a>';
				}
				$new_field +=
					'\
				<div class="card d-flex justify-content-center align-items-center" style="">\
					<div class="actions ' + ifNotValueHidden + '" id="__UNIQUE_ID___image_actions">\
					<div class="btn-group floating">\
						<a class="btn yellow-gold btn-circle btn-icon-only dropdown-toggle" href="javascript:;" data-toggle="dropdown" aria-expanded="false">\
							<i class="fa fa-cogs"></i>\
						</a>\
						<ul class="dropdown-menu colored-buttons pull-right" role="menu">\
							<li>\
								<a href="javascript:;" class="btn red btnDelete font-white" onclick="CommonFunc2Confirmation({\'unique_id\':\'__UNIQUE_ID__\',\'module\':\'__MODULE__\',\'id\':\'__ID__\',\'action\':\'delete_logo_image\'},\'\',this)"><i class="fa fa-trash"></i> Delete</a>\
							</li>\
							<li>\
							</li>\
						</ul>\
					</div>\
					</div>\
					<div class="logo_holder" id="__UNIQUE_ID___dom_image_holder">\
					' + ifValue + '\
					' + ifNoValue + '\
					</div>\
					<span class="btn yellow-gold fileinput-button ' + ifValueHidden + '" id="__UNIQUE_ID___new_img_btn">\
						<i class="fa fa-plus"></i>\
						<span> Add Logo ... </span>\
						<input type="file" multiple="" id="' + currentUniqueId + '-file" data-unique_id="'+currentUniqueId+'">\
					</span>\
				</div>\
				';
				$this.formFunctionList.push(
					function () {
						var imgEditorOptions = {
							'parent': currentUniqueId,
							'module': $this.options['module'],
							'imageProcessingUrl': 'imageProcessing',
							'modal_title': 'Upload Logo',
							'upload_path': 'uploads/',
							'one_image': true,
							'direct_crop_upload': false,
							'strict_crop': false,
							'cropWidth': '200',
							'cropHeight': '200',
							'allowResizeInCrop': true,
							'allowSelectInCrop': true,
							'final_action': 'upload_logo',
							'upload_action': 'upload_image_thumb',
							'file_field': '' + currentUniqueId + '-file'
						};
						if ($element['image_options']) {
							$.each($element['image_options'], function ($key, $val) {
								imgEditorOptions[$key] = $val;
							});
						}
						var imgEditor = new ImageEditor(imgEditorOptions);

						let uploadData = {
							'parent': currentUniqueId,
							'module': $this.options['module']
						};
						if (isset($form_config['request_data']['id'])) {
							uploadData['id'] = $form_config['request_data']['id'];
						}
						if ($element['image_upload_data']) {
							$.each($element['image_upload_data'], function ($key, $val) {
								uploadData[$key] = $val;
							});
						}
						imgEditor.setAjaxImageUploadData(uploadData);
					});

			} 
			else if ($element['type'] == 'daterangepicker') {
				$include_css['daterangepicker'] = 'assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css';
				$include_js['daterangepicker'] = 'assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js';
				$new_field +=
					'\
					<label class="control-label ">' + $element['label'] + '</label></br>\
					<div id="' + $element['id'] + '" class="tooltips btn btn-fit-height green" data-placement="top" data-original-title="Change report date range">\
						<i class="icon-calendar"></i>\
						<span class="thin uppercase hidden-xs"></span>\
						<i class="fa fa-angle-down"></i>\
					</div>\
				';
				if($dbData[$element['start_date']]){
					
				}
				var $tempDate = {
					'element': $element['id'],
					'startdate': '' + $this.options['unique_id'] + '-' + $element['start_date'],
					'enddate': '' + $this.options['unique_id'] + '-' + $element['end_date'],
					'button': ''
				};
				if (($dbData[$element['start_date']])) $tempDate['start_value'] = $dbData[$element['start_date']];
				if (($dbData[$element['end_date']])) $tempDate['end_value'] = $dbData[$element['end_date']];
				if (($element['drops'])) $tempDate['drops'] = $element['drops'];
				if (($element['opens'])) $tempDate['opens'] = $element['opens'];
				if (($element['range'])) $tempDate['range'] = $element['range'];

				$this.formFunctionList.push(
					function () {
						var jsonDate = $tempDate;
						initDateRangePickerCommon(jsonDate);
					});
			} 
			else if ($element['type'] == 'date') {

				$new_field += $formClass.datePickerFromArray($element);
			} 
			else if ($element['type'] == 'input') {

				$new_field += $formClass.inputFromArray($element);
			} 
			else if ($element['type'] == 'positive-number') {
				$element['inputType'] = 'number';
				$element['extra'] += ' min="1"';
				$element['class'] += ' positive-number';
				$new_field += $formClass.inputFromArray($element);
			} 
			else if ($element['type'] == 'textarea') {
				$new_field += $formClass.textareaFromArray($element);
			} 
			else if ($element['type'] == 'summernote') {
				$this.formFunctionList.push(
					function () {
						MySummerInit($element['id'], $form_config['unique_id'], 150);
					});
				$new_field += $formClass.textareaFromArray($element);
			} 
			else if ($element['type'] == 'select') {
				if ($element['has_icons']) { //// If Has icons then put the js code, set has icons true
					$this.formFunctionList.push(
						function () {
							$(".bs-select").selectpicker({
								iconBase: "fa",
								tickIcon: "fa-check"
							});
						});
					$element['form_group_type'] = 'default-12-12';
				}
				if ($element['select2']) {
					if ($element['select2']['ajax'] && $element['select2']['ajax']['module']) {
						//$element['options'] = {};
						$this.formFunctionList.push(
							function () {
								$('#' + $element['id'] + '').select2({
									minimumInputLength: 2,
									placeholder: $element['select2']['placeholder'],
									ajax: {
										url: '',
										delay: 500,
										data: function (params) {
											var query = {
												search: params.term,
												action: 'select2-search',
												module: $element['select2']['ajax']['module']
											}
											return query;
										},
										processResults: function (data) {
											var dataParsed = JSON.parse(data);
											return {
												results: $.map(dataParsed.tableData, function (item) {
													return {
														text: item.name,
														id: item.id
													}
												})
											};
										}
									}
								});
							});

					}
					 else {
						 var placeholder = $element['label'].replace('<span class="font-red">*</span>','');
						$this.formFunctionList.push(
							function () {
								$('#' + $element['id'] + '').select2({
									placeholder: 'Select '+placeholder,
									dropdownAutoWidth : true
								});
							});
					}


					$element['form_group_type'] = 'default-12-12';
				}

				eval($element['eval']);
				/* 				if(isset($element['options_func']) && isset($element['ajax_dependency']) && isset($default_values['db_data'][$value['ajax_dependency']])){
									$element['options'] = call_user_func_array($element['options_func'],array($default_values['db_data'][$element['ajax_dependency']]));
								}   */
				$new_field = $formClass.selectFromArray($element);
			} 
			else if ($element['type'] == 'multiple-select') {
				$element['name'] = $element['name']+'[]';
				if ($element['has_icons']) { //// If Has icons then put the js code, set has icons true
					var tempFunc =
						function () {
							$(".bs-select").selectpicker({
								iconBase: "fa",
								tickIcon: "fa-check"
							});
						}
						$this.formFunctionList.push(
							function () {
								$('#' + $element['id'] + '').on('change',function(){
									console.log($(this).val());
								});
							});						
					loadJS(FRONTEND_SITE_PATH+'core_theme/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js',tempFunc);
					$element['form_group_type'] = 'default-12-12';
				}
				if ($element['select2']) { //// If Has icons then put the js code, set has icons true
					var placeholder = $element['label'].replace('<span class="font-red">*</span>','');
					$this.formFunctionList.push(
						function () {
							$('#' + $element['id'] + '').select2({
								placeholder: 'Select '+placeholder,
								container: '#' + $this.options['unique_id'] + '-main-container'
							});
						});
					$element['form_group_type'] = 'default-12-12';
				}
				eval($element['eval']);
				/* 				if(isset($element['options_func']) && isset($element['ajax_dependency']) && isset($default_values['db_data'][$element['ajax_dependency']])){
									$element['options'] = call_user_func_array($element['options_func'],array($default_values['db_data'][$element['ajax_dependency']]));
								}    */
				$new_field = $formClass.multipleSelectFromArray($element);
			} 
			else if ($element['type'] == 'code') {
				eval($element['eval']);
				$new_field += isset($element['code']) ? $element['code'] : '';
			} 
			else if ($element['type'] == 'html') {
				$new_field += $element['html'];
			}


			///END START CREATING THE FORM ELEMENTS
		}
		$new_js += $element['js'];
		$return['field'] = $new_field;
		$return['js'] = $new_js;
		return $return;

	}

	function loadJS(file,func) {
		// DOM: Create the script element
		var jsElm = document.createElement("script");
		// set the type attribute
		jsElm.type = "application/javascript";
		// make the script element load file
		jsElm.src = file;
		jsElm.onload = func;
		// finally insert the element to the body element in order to load the script
		document.body.appendChild(jsElm);
	}

	function loadCSS(file) {
		var head  = document.getElementsByTagName('head')[0];
		var link  = document.createElement('link');
		//link.id   = cssId;
		link.rel  = 'stylesheet';
		link.type = 'text/css';
		link.href = file;
		link.media = 'all';
		console.log(link);
		head.appendChild(link);
	}
	this.getAllFields = function () {
		return this.fields;
	}

	this.getLabels = function () {
		return this.labels;
	}

	this.getIcons = function () {
		return this.icons;
	}

	this.setProperty = function (key, value) {
		$this.options[key] = value;
	}
	this.setVariable = function ($variable, value) {
		window[$variable] = value;
	}

	this.resetAllFunctions = function () {
		$this.formFunctionList = [];
		$this.priorityJsList = [];
	}

	this.repeatForm = function (unique_id) {
		$this.creatingRepeatForm = true;
		$this.creatingNewRepeatForm = true;
		$this.options.unique_id = uniqueid();
		//console.log(unique_id);
		//$this.options.has_data = {};
		$this.options.show_form_in_container = ''+initialUniqueId+'-repeat-form-container';
		//if($this.options.return_form_as !== 'table')$this.options.return_form_as = 'repeat_form';
		$form_config = $.extend({}, $this.options);
		$form_config['has_data'] = null;
		var newForm = $this.createForm();
		$this.executeAllJs(true);
	}
	this.onMultipleSelectedForm = function () {
		$this.options.unique_id = uniqueid();
		//$this.options.has_data = {};
		var newForm = $this.getForm();
		$('#' + $this.options['parent_unique_id'] + '-child-container').append(newForm.html);
		$this.executeAllJs();
	}

	this.getForm = function () {
		/* console.log($this.options.module);
		console.log($this.options.has_data); */
		if($this.options.is_multiple_form){
			if($this.options.has_data && !$.isEmptyObject($this.options['has_data'])){
				$this.options['is_edit'] = true;
				var dataKeys = Object.keys($this.options['has_data']);
				var loopCount = dataKeys.length;
				if(loopCount < 1)loopCount = 1;
				if(!$this.creatingNewRepeatForm){
					for(var i=0;i<=loopCount-1;i++){
						if(i>0){
							$this.options.unique_id = uniqueid();
							$this.creatingRepeatForm = true;
							$this.options.show_form_in_container = ''+initialUniqueId+'-repeat-form-container';
							if($this.options.show_buttons_in_container)  delete $this.options.show_buttons_in_container;
							if($this.options.return_form_as !== 'table')$this.options.return_form_as = 'repeat_form';
						}
						$form_config = $.extend({}, $this.options);
						$form_config.has_data = $this.options.has_data[dataKeys[i]];
						/* console.log(dataKeys);
						console.log($form_config['module']);*/
						/* console.log($this.options['has_data']);
						console.log($form_config['has_data']);  */
						$this.createForm();
						if(!$form_config['skip_js_execution'])$this.executeAllJs();
					}
				}
				else{
					$form_config = $.extend({}, $this.options);
					$form_config['has_data'] = null;
					console.log($form_config['has_data']);
					console.log($form_config['non_db_data']);
					$this.createForm();
					if(!$form_config['skip_js_execution'])$this.executeAllJs();
				}
			}
			else{
				//$this.options.unique_id = uniqueid();
				$form_config = $.extend({}, $this.options);
				$this.createForm();
				if(!$form_config['skip_js_execution'])$this.executeAllJs();
			}
		}
		else{
			//$this.options.unique_id = uniqueid();
			$form_config = $.extend({}, $this.options);
			//console.log($this.options);
			$this.createForm();
			if(!$form_config['skip_js_execution'])$this.executeAllJs();
		}	
	}

	this.createForm = function () {
		/* if($this.creatingNewRepeatForm && $form_config['module'] == 'event_jobs'){
			console.log($form_config['has_data']);
			console.trace();
		} */
		if(!$form_config)$form_config = $.extend({}, $this.options);
		currentUniqueId = $form_config['unique_id'];
		if (!$.isEmptyObject($form_config['child_form'])) {
			hasChildForm = true;
		}
		if ($form_config['is_child_form']) {
			isChildForm = true;
		}
		if ($form_config['is_multiple_form']) {
			isMultipleForm = true;
		}
		image_fields = ''; 
		$next_form = '';
		$form_config['form_id'] = $form_config['unique_id'] + '-form';
		$form = $.extend([], $form_config);
		$form['buttons'] = '';
		this.handleMultipleForm();
		{ /// Initial Config
			$form['tools'] = [];
			$form['tools']['id_map'] = {};
			$form['field_html'] = [];
			$form['tools']['dependency'] = {}; ///// needs to be an object
			$form['tools']['validation'] = {}; ///// needs to be an object
			$form['tools']['required'] = {}; ///// needs to be an object
			$form['tools']['duplicate'] = {}; ///// needs to be an object
			$form['tools']['icons'] = {}; ///// needs to be an object
			$form['tools']['labels'] = {}; ///// needs to be an object
			$form['tools']['titles'] = {}; ///// needs to be an object
			$form['field-types'] = [];
			$db_data = [];
			if ($form_config['has_data']) {
				$this.options['is_edit'] = true;
				$form['tools']['data'] = {};
				$form['db_data'] = $form_config['has_data'];
				$db_data = $form_config['has_data'];
				/* console.log($form_config['module']);
				console.log($form_config['has_data']); */
				$.each($db_data, function ($temkDKey, $tempDVal) {
					$form['tools']['data']['' + $form_config['unique_id'] + '-' + $temkDKey + ''] = $tempDVal;
				});
			}

			$form_config_fields = $form_config['fields'];
			///// IF CUSTOM FIELDS ARE DEFINED
			$all_config = {};
			if ($form_config['has_custom_fields']) {
				$all_config['fields'] = $form_config['custom_fields'];
				$form_config['labels'] = {};
				$form_config['icons'] = {};
			} 
			else {
				$all_config = $this.getAllFields();
				//$form_config['labels'] = $this.getLabels();
				//$form_config['icons'] = $this.getIcons();
			}
			$all_fields = $all_config['fields'];

			///// Create Hidden fields if any
			$form['hidden'] = '';
			$form['priorityJs'] = '';
			var new_or_old = 'new';
			if($form_config['has_data'])new_or_old = 'old';
			if(!$form_config['name_prefix'])$form_config['name_prefix'] = 'data';
			var name_prefix = $form_config['name_prefix'];
			name_prefix = 'data['+new_or_old+'][' + $form_config['unique_id'] + ']';
			if (hasChildForm) {
				name_prefix = 'data['+new_or_old+'][' + $form_config['unique_id'] + ']';
				
			}
			if (isChildForm) {
				//name_prefix = '' + $form_config['parent_unique_id'] + '[children][data][' + $form_config['unique_id'] + ']';
				name_prefix = '' + $form_config['name_prefix'] + '[children][' + $form_config['module'] + '][data]['+new_or_old+'][' + $form_config['unique_id'] + ']';
			}

			$form_config['name_prefix_for_child'] = name_prefix;

			if ($form_config['hidden']['enabled']) {
				$form['hidden'] += $form_config['hidden']['html'];
			}

			if (isset($form_config['request_data']['next_data'])) {
				$.each($form_config['request_data']['next_data'], function ($k, $v) {
					$form['hidden'] += '<input type="hidden" name="next_data[' + $k + ']" id="' + $form_config['unique_id'] + '-next_data-' + $k + '" value="' + $v + '">';
				});
			}

			if(!isChildForm){
				$form['hidden'] += '<input type="hidden" name="form_type" id="' + $form_config['unique_id'] + '-form_type" value="' + $form_config['form_type'] + '">';
				$form['hidden'] += '<input type="hidden" name="config[module]" id="' + $form_config['unique_id'] + '-module" value="' + $form_config['module'] + '">';
				$form['hidden'] += '<input type="hidden" name="config[unique_id]" id="' + $form_config['unique_id'] + '-unique_id" value="' + $form_config['unique_id'] + '">';
				$form['hidden'] += '<input type="hidden" name="' + $form_config['unique_id'] + '[config][module]"  value="' + $form_config['module'] + '">';
				//$form['hidden'] += '<input type="hidden" name="' + $form_config['unique_id'] + '[config][unique_id]" id="' + $form_config['unique_id'] + '-unique_id" value="' + $form_config['unique_id'] + '">';
			}
			else{
				$form['hidden'] += '<input type="hidden" name="' + $form_config['name_prefix'] + '[children][' + $form_config['module'] + '][config][module]" id="' + $form_config['unique_id'] + '-module" value="' + $form_config['module'] + '">';
				$form['hidden'] += '<input type="hidden" name="' + $form_config['name_prefix'] + '[children][' + $form_config['module'] + '][config][unique_id]" id="' + $form_config['unique_id'] + '-unique_id" value="' + $form_config['unique_id'] + '">';
			}
			
			//$form['hidden'] += '<input type="hidden" name="'+name_prefix+'[config][form_type]" id="'+$form_config['unique_id']+'-form_type" value="'+$form_config['form_type']+'">';
			//$form['hidden'] += '<input type="hidden" name="name_prefix" id="' + $form_config['unique_id'] + '-name_prefix" value="' + $form_config['name_prefix'] + '">';

		}

		if($form_config['return_form_as'] == 'table'){
			$form_config['form_group_type'] = 'default';
		}
		///// START MAIN LOOP
		///// START MAIN LOOP
		///// START MAIN LOOP
		///// START MAIN LOOP
		///// START MAIN LOOP
		if($form_config['add_html_before_form_creation']) $next_form += $form_config['add_html_before_form_creation'];
		width_count_tracker = 0;
		if(!$multipleInitOnly){
			$.each($form_config['fields'], function ($kk, $fieldTemp) {
				$element = {};
				$field = $fieldTemp;
				$value = $all_fields[$field];
				////// MERGE THE COMMON FIELD_ARRAY WITH THE SPECIFIED FIELD VALUES
				$element = $.extend($element, $this.field_array, $value);
				if($form_config['return_form_as'] == 'table')$element['hide_label'] = true;
				

				///// OVERRIDE ATTRIBUTES IF SPECIFIED IN FORM_CONFIG OVERRIDE FIELDS 
				$element['value'] = isset($value['value']) ? $value['value'] : '';
				if (isset($form_config['override_fields'][$field])) {
					$overrides = $form_config['override_fields'][$field];
					$element = $.extend({}, $element, $overrides);
				}
				if($form_config['element_only'])$element['element_only'] = true;
				$element['form_group_type'] = $value['form_group_type'] || $form_config['form_group_type'];
				$element['unique_class'] = ' '+$form_config['unique_id']+'-' + $field + '';
				$element['class'] += ' '+$form_config['unique_id']+'-' + $field + '';
				$element['class'] += ' '+$form_config['module']+'-' + $field + '';
				$element['class'] += ' '+$form_config['module']+'-element';/////common class for all module elements
				if($form_config['common_class_for_all_elements'])$element['class'] += ' '+$form_config['common_class_for_all_elements'];/////common class for all  elements
				$element['unique_id'] = $form_config['unique_id'];
				$element['module'] = $form_config['module'];
				$element['name_prefix'] = ($form_config['name_prefix']) ? $form_config['name_prefix'] : ''; 
				{ //// PREPARE THE ARRAY FOR FETCHING FORM ELEMENT
					$element['extra'] += ' data-unique_id="' + $form_config['unique_id'] + '" data-module="' + $form_config['module'] + '" ';
					$element['extra'] += isset($value['extra']) ? $value['extra'] : '';
					$element['extra'] += ' data-field="' + $field + '" ';
					if ($element['disabled']) $element['extra'] += ' disabled ';

					///// IF custom field width specified from when the form was initiaated eg sometimes the form will require different widths when shown on a small modal
					if ($form_config['custom_field_width'][$field]) $element['width'] = $form_config['custom_field_width'][$field];
					if ($form_config['popover_error']) $element['extra'] += ' data-showpopovererror="true" ';

					if (!$form_config['has_custom_fields']) {
						$element['label'] = getDefault($form_config['labels'][$field], $element['label']);
						$element['icon'] = getDefault($form_config['icons'][$field], $element['icon']);
					}
					$element['class'] += ' monitor-input';
					$new_field = ''; ///RESET THE VARIABLE TO REMOVE ANY PREVIOUS VALUES
					$element['name'] = name_prefix + '[fields][' + $field + ']';
					if($form_config['override_name'] && !$form_config['is_child_form'] && !$form_config['is_multiple_form'])$element['name'] = $field;
					//if($form_config['override_name'])$element['name'] = name_prefix + '[fields][' + $field + ']';
					//if($element['type'] == 'upload_single_image' || $element['type'] == 'upload_multiple_image' || $element['type'] == 'upload_logo')$element['name'] = $form_config['unique_id']+'['+[$field]+']';
					$form['tools']['id_map'][$field] = $element['id'] = $form_config['unique_id'] + '-' + $field;

					//$has_conditional_required = false;
					$element['required'] = ($.inArray($field, $form_config['required']) > -1) || isset($form_config['required'][$field]) ? true : false;
					if ($form_config['conditional_required'][$field]) {
						$element['required'] = true;
					}
					if ($element['required']) $element['extra'] += ' field-required';
					$element['validation'] = isset($form_config['validation'][$field]) ? $form_config['validation'][$field] : [];
					if (isset($form_config['validation'][$field])) $element['extra'] += ' field-validate';
					$element['allowed_duplicate'] = ($.inArray($field, $form_config['duplicate']) !== -1) ? false : true;

					if (!$element['allowed_duplicate']) $element['extra'] += ' field-check-duplicate';
					//$tempJsElements += str_replace('__ELEMENT_ID__',$element['id'],$element['js']);
					if (isset($db_data[$field])) {
						if ($element['type'] == 'checkbox') {
							$element['checked'] = $db_data[$field] == $element['value'];
						}
						else {
							$element['value'] = $db_data[$field];
						}
					} 
					else if ($form_config['non_db_data'] && $form_config['non_db_data'][$field]) {
						$element['value'] = $form_config['non_db_data'][$field];
					}
					else if ($form_config['default_values']['enabled'] && $form_config['default_values'][$field]) {
						$element['value'] = $form_config['default_values'][$field];
					}
					else {
						$element['value'] = isset($element['value']) ? $element['value'] : '';
					}
				}

				{ //// CODEBEGIN
					if ($element['code_begin'] != '') {
						//$next_form += replaceBetweenBraces($db_data,$element['code_begin']);
						$next_form += $element['code_begin'];
					}
				}

				var $new_field_temp = {};
				
				if(!$element['custom_element'])$new_field_temp = $this.createFormElement($field, $element, $db_data);
				else{
					if($form_config['has_data']) $new_field_temp['field'] = replaceBetweenBraces($db_data,$element['custom_element']);
					else if($form_config['non_db_data']) $new_field_temp['field'] = replaceBetweenBraces($form_config['non_db_data'],$element['custom_element']);
					else $new_field_temp['field'] = $element['custom_element'];
					$new_field_temp['js'] = '';

					if($element['type'] == 'checkbox'){
						if($form_config['has_data'] && $form_config['has_data'][$field] !== '0')$element['extra'] = 'checked=true';
					}
				}
				$new_field = $new_field_temp['field'];
				$new_field = str_replace('__ELEMENT_EXTRA__', $element['extra'], $new_field);
				$new_field = str_replace('__ELEMENT_NAME__', $element['name'], $new_field);
				$new_field = str_replace('__ELEMENT_ID__', $element['id'], $new_field);
				$new_field = str_replace('__ELEMENT_CLASS__', $element['unique_class'], $new_field);
				$new_field = str_replace('__ELEMENT_VALUE__', $element['value'], $new_field);
				//if(isset($form_config['request_data']['id'])) $new_field = replace_conditional_html($element,$new_field);
				if ($element['value'] != '') {
					$new_field = str_replace('__HIDDEN_IF_VALUE__', ' hidden', $new_field);
					$new_field = str_replace('__HIDDEN_IF_NO_VALUE__', '', $new_field);
				} else {
					$new_field = str_replace('__HIDDEN_IF_VALUE__', '', $new_field);
					$new_field = str_replace('__HIDDEN_IF_NO_VALUE__', ' hidden', $new_field);
				}

				$tempJsToolsTemp = $new_field_temp['js'];
				if($tempJsToolsTemp){
					$tempJsToolsTemp = str_replace('__ELEMENT_ID__', $element['id'], $tempJsToolsTemp);
					$tempJsToolsTemp = str_replace('__ELEMENT_CLASS__', $element['unique_class'], $tempJsToolsTemp);
					if ($element['value'] != '') $tempJsToolsTemp = str_replace('__ELEMENT_VALUE__', $element['value'], $tempJsToolsTemp);
					$tempJsTools += $tempJsToolsTemp;
				}
				$form['field_html'][$field] = {};
				$form['field_html'][$field]['from_group_only'] = $new_field;

				$this.wrapElement();

				{ ///// TEMP TOOLS

					$form['tools']['icons'][$element['id']] = $element['icon'];
					if (isset($element['required']) && $element['required']) {
						$form['tools']['required'][$element['id']] = [];
						$form['tools']['required'][$element['id']]['status'] = false;
						$form['tools']['required'][$element['id']]['message'] = $element['label'] + ' is Required';
					}

					if (typeof $form_config['conditional_required'][$field] !== 'undefined') {
						//$form_config['required'][$field]['conditional']['field'] = $form_config['unique_id']+'-'+$form_config['required'][$field]['conditional']['field'];
						$form['tools']['required'][$element['id']] = {};
						$form['tools']['required'][$element['id']]['conditional'] = $form_config['conditional_required'][$field];
					}
					if (isset($element['allowed_duplicate']) && !$element['allowed_duplicate']) {
						$form['tools']['duplicate'][$element['id']] = [];
						$form['tools']['duplicate'][$element['id']]['status'] = true;
						$form['tools']['duplicate'][$element['id']]['message'] = $element['label'] + ' is Required';
					}

					if (isset($element['validation']) && !$.isEmptyObject($element['validation'])) {
						if (isset($element['validation']['conditional'])) {
							//$form['tools']['validation'][$element['id']]['conditional'] = $form_config['validation'][$field];
							$form_config['validation'][$field]['conditional']['field'] = $form_config['unique_id'] + '-' + $form_config['validation'][$field]['conditional']['field'];
							$form['tools']['validation'][$element['id']] = $form_config['validation'][$field];
						} else {
							$form['tools']['validation'][$element['id']] = $form_config['validation'][$field];
						}
					}

					if (isset($form_config['dependency'][$field])) {
						$form['tools']['dependency'][$element['id']] = $form_config['dependency'][$field];
					}
					if ($element['type'] != 'code') {
						$form['fields'][$field] = $new_field;
						$form['field-types'].push($element['type']);
						$form['tools']['titles'][$element['id']] = 'Please Enter the ' + $element['label'];
					}
				}

			});
		}
		//////// END FIELDS LOOP
		//////// END FIELDS LOOP
		//////// END FIELDS LOOP
		//////// END FIELDS LOOP
		//////// END FIELDS LOOP

		if($form_config['return_form_as'] != 'table'){
			//$next_form = '<div class="row  justify-content-center">'+$next_form+'';
			//if (width_count_tracker <= 12) $next_form += '</div>';
		}

		$next_form = image_fields + $next_form;

		if($form_config['add_html_after_form_creation']) $next_form += $form_config['add_html_after_form_creation'];

		if (hasChildForm) {

			$.each($form_config['child_form'],function($kk,$child_form_config){
				var $child_form_container;
				if(!$form_config['child_form_wrapper'] || !$form_config['child_form_wrapper'][$child_form_config['module']]){
					$child_form_container = '<div class="" id="'+$form_config['unique_id']+'-'+$child_form_config['module']+'-child-container" style="width: 80%;"></div>';
				}
				else{
					$child_form_container = $form_config['child_form_wrapper'][$child_form_config['module']].replace('__CHILD_CONTAINER_ID__',''+$form_config['unique_id']+'-'+$child_form_config['module']+'-child-container');
				}

				if($form_config['append_child_form_in'] && $form_config['append_child_form_in'][$child_form_config['module']]){
					$('#'+$form_config['append_child_form_in'][$child_form_config['module']]+'').append($child_form_container);
				}
				else{
					$next_form += $child_form_container;;
				}
			});

		}

		$ajaxDepjs = '';
		if (!$.isEmptyObject($this.ajax_dependency)) {

			$.each($this.ajax_dependency, function ($ajField, $depElem) {

				$ajaxDepjs +=
					"\
                $(document).on('change','#" + $form_config['unique_id'] + "-$ajField', function(){\
                   getDependentFields('" + $form_config[module] + "',this);\
                });\
                ";
				$form['tools']['ajax_dependency'][$form_config['unique_id'] + '-' + $ajField] = $form_config['unique_id'] + '-' + key($depElem);
			});
		}

		$form['tools']['unique_id'] = $form_config['unique_id'];
		$form['tools']['module'] = $form_config['module'];
		$next_buttons = '';

		{ //// Portlet Info

			$form['html_extra_start'] = isset($form_config['html_extra_start']) ? $form_config['html_extra_start'] : '';
			$form['portlet-title'] = $form_config['portlet_title'];
			$form['portlet-icon'] = $form_config['portlet_icon'];
			$form['form-id'] = $form_config['form_id'];
			$form['form_id'] = $form_config['form_id'];
			$form['submit-id'] = $form_config['submit_id'];
			$next_form += isset($form_config['hidden-fields']) ? $form_config['hidden-fields'] : '';

		}

		$form['form'] = $next_form;
		

		
		

		////// BUTTONS
		if(!isChildForm)createButtons($form_config, $form);


		//$form['dependency'] = $depTemp; 
		//$this.form.tools.dependency = $depTemp;
		{ //// Return Form As 
			$form['form'] += $form_config['extra_html'];

			/////// If child form add tools to the main ( base class) $class.tools
			if (isChildForm) {
				$this.priorityJsList.push(function () {
					$this.options['base_class']['tools'][$this.options['unique_id']] = $this.form.tools;
				});
			}
			else {
				$this.priorityJsList.push(function () {
					$this.tools[$this.options.unique_id] = $this.form.tools;
				});
			}
			////// RETURN FORM AS
			{
				returnFormAs($form_config, $form);
			}

			///// PUPULATE VARIABLES AND STUFF
			{
				$form['include_css'] = $this.include_css;
				$form['include_js'] = $this.include_js;
				$form['jsFunction'] = $form['priorityJs'];
				$form['jsFunction'] += $ajaxDepjs;
				$form['jsFunction'] += $tempJsTools;
				$form['jsFunction'] += $tempJsElements;
				$form['jsFunction'] += $form_config['jsFunction'];
				//$form['jsFunction'] += $this.getDependencyJs($form_config);
				$form['jsFunction'] += $form_config['jsFunctionLast'];
				$form['jsFunction'] += "";
				if (isset($form_config['request_data']['id'])) {
					$form['html'] = str_replace('__ID__', $form_config['request_data']['id'], $form['html']);
					$form['form'] = str_replace('__ID__', $form_config['request_data']['id'], $form['form']);
					$form['jsFunction'] = str_replace('__ID__', $form_config['request_data']['id'], $form['jsFunction']);
				}

				$form['form'] = str_replace('__UNIQUE_ID__', $form_config['unique_id'], $form['form']);
				$form['form'] = str_replace('__MODULE__', $form_config['module'], $form['form']);
				$form['form'] = str_replace('__NAME_PREFIX__', $form_config['name_prefix'], $form['form']);
				$form['form'] = str_replace('__FORM_ID__', $form_config['form_id'], $form['form']);

				$form['html'] = str_replace('__UNIQUE_ID__', $form_config['unique_id'], $form['html']);
				$form['html'] = str_replace('__MODULE__', $form_config['module'], $form['html']);
				$form['html'] = str_replace('__NAME_PREFIX__', $form_config['name_prefix'], $form['html']);
				$form['html'] = str_replace('__FORM_ID__', $form_config['form_id'], $form['html']);

				$form['buttons'] = str_replace('__UNIQUE_ID__', $form_config['unique_id'], $form['buttons']);
				$form['buttons'] = str_replace('__MODULE__', $form_config['module'], $form['buttons']);
				$form['buttons'] = str_replace('__NAME_PREFIX__', $form_config['name_prefix'], $form['buttons']);
				$form['buttons'] = str_replace('__FORM_ID__', $form_config['form_id'], $form['buttons']);

				$form['jsFunction'] = str_replace('__UNIQUE_ID__', $form_config['unique_id'], $form['jsFunction']);
				$form['jsFunction'] = str_replace('__MODULE__', $form_config['module'], $form['jsFunction']);
				$form['jsFunction'] = str_replace('__ELEMENT_ID__', $form_config['name_prefix'], $form['jsFunction']);
				$form['jsFunction'] = str_replace('__FORM_ID__', $form_config['form_id'], $form['jsFunction']);

				$form['form'] += $form_config['extra_unreplaced_html'];
			}
			$this.form = $form;
			return $form;

		}
	}

	this.runFieldsLoop = function(){

	}

	this.handleMultipleForm = function(){
		//////// MULTIPLE FORM 
		var actionBtnClass = 'btn btn-circle btn-icon-only';
		var actionBtnDeleteClass = 'bg-less-glossy-error btnDelete';
		var actionBtnDeleteIcon = '<i class="flaticon-cancel" style="font-size: 30px;"></i>';
		//console.log($form_config['unique_id']);
		//console.log($this.options.unique_id);
		if ($form_config['is_multiple_form']) {
			if(!$form_config['repeat_form_on_event'] && !$this.creatingRepeatForm){
				$form['buttons'] +=
				'\
				<a href="javascript:;" class="btn bg-golden-less-glossy" id="' + $form_config['unique_id'] + '-btn-add-another-main" data-unique_id="'+$form_config['unique_id']+'">Add Another ' + $form_config['portlet_title'] + '</a>\
				';
			}
			
			var $action_buttons = '';
			if($this.creatingRepeatForm){
				var dbId = 0;
				if ($form_config['has_data']) {
					dbId = $form_config['has_data']['id'];
				}					
				$action_buttons = '<a class=" '+actionBtnClass+' '+actionBtnDeleteClass+'  ' + $form_config['unique_id'] + '" href="javascript:;" data-module="' + $form_config['module'] + '" data-unique_id="' + $form_config['unique_id'] + '" data-id="' + dbId + '" id="'+$form_config['unique_id']+'-child-row-delete-btn">\
				'+actionBtnDeleteIcon+'\
				</a>';
			
				$this.formFunctionList.push(
					function () {
						try{
							$('#' + $form_config['unique_id'] + '-scroll-to')[0].scrollIntoView({
								block: "center"
							});
						}
						catch(ex){console.log(ex)}
						
				});
				$this.formFunctionList.push(
					function () {
						$('#'+$form_config['unique_id']+'-child-row-delete-btn').on('click',function(e){
							
								$(e.target).confirmation({
									container: 'body',
									placement: 'auto',
									singleton: false,
									popout: false,
									btnOkClass: 'btn btn-sm green',
									btnCancelClass: 'btn btn-sm red',
									onConfirm: function(){
										var idTemp = $(e.target).data('id');
										var moduleTemp = $(e.target).data('module');
										CommonFunc2({action:'delete',module:moduleTemp,id:idTemp})
										.then(function (value) {
											$(e.target).closest('.remove-this-after-delete').remove();
										});
									},
									onCancel: function () {
										$(e.target).confirmation( 'destroy' );
									}
								}); 
								
								$(e.target).confirmation( 'show' );				
						})
					}
				);
			}
			//var optns2 = $this.options['child_form'];
			else{
				//if(!$form_config['external_multiple_init']){
				if(!$form_config['repeat_form_on_event']){
					$this.formFunctionList.push(
						function () {
							$('#' + $form_config['unique_id'] + '-btn-add-another-main').on('click', function () {
								console.log(initialUniqueId);
								var unqId = $(this).data('unique_id');
								$this.repeatForm(unqId);
							});
						});
				}
			}


			if ($form_config['return_form_as'] !== 'table') {
				var actionBtnHtml =
				'\
				<div class="task-title-actions">\
					'+$action_buttons+'\
				</div>\
				';
				if(!$form_config['form_wrapper']){
					$form_config['form_wrapper'] = 
					'<div class="row z-depth-2 animated zoomIn remove-this-after-delete  pt-3" style="margin-bottom:10px;position: relative;" id="__UNIQUE_ID__-scroll-to">\
						__FORM__\
						'+actionBtnHtml+'\
					</div>';
				}
				//if($form_config['external_multiple_init'])$tempFormWrapper = '';
				var tempRepeatCont;
				if(!$form_config['multiple_form_wrapper']){
					$form_config['multiple_form_wrapper'] = '<div class="row" id="__MULTIPLE_CONTAINER_ID__" style="position:relative;width:100%;">__FORM__</div>';
				}
				
				if(!$this.creatingRepeatForm){
					$form_config['multiple_form_wrapper'] = $form_config['multiple_form_wrapper'].replace('__MULTIPLE_CONTAINER_ID__',''+$form_config['unique_id']+'-repeat-form-container');
					if($form_config['portlet_title'])$form_config['multiple_form_wrapper'] = $form_config['multiple_form_wrapper'].replace('__MULTIPLE_FORM_TITLE__',$form_config['portlet_title']);

					if($form_config['min_multiple_rows'] > 0 || $form_config['is_edit']){
						$form_config['form_wrapper'] = $form_config['form_wrapper'].replace('__FORM__',$form_config['multiple_form_wrapper']);
						$this.totalMultipleForms += 1;
					}
					else{
						$form_config['form_wrapper'] = $form_config['multiple_form_wrapper'].replace('__FORM__','');
						if(!$this.options['is_edit'])$multipleInitOnly = true;
					}
				}
				else{
					$this.totalMultipleForms += 1;
					$multipleInitOnly = false;
				}
				
				//$form_config['form_wrapper'] = tempRepeatCont.replace('__FORM__',$form_config['form_wrapper']);
			}
			else{
				if(!$form_config['multiple_form_wrapper']){
					if(!$this.creatingRepeatForm){
						var tr = '';
						$.each($form_config['fields'], function($k,$field){
							if($form_config['all_fields']['fields'][$field]['type'] != 'hidden')tr += '<th>'+$form_config['labels'][$field]+'</th>';
						});
						tr += '<th>Delete</th>';
						var thead = '<thead>'+tr+'</thead>';
						var table = '\
						<table class="table table-bordered m-table m-table--border-brand m-table--head-bg-brand">\
							'+thead+'\
							<tbody id="' + $form_config['unique_id'] + '-repeat-form-container">\
								<tr>__FORM__<td></td></tr>\
							</tbody>\
						</table>';
						//$form['form'] = table;
						$form_config['form_wrapper'] = table;
					}
					else{
						$form_config['form_wrapper'] = '<tr class="animated zoomIn remove-this-after-delete" id="' + $form_config['unique_id'] + '-scroll-to">__FORM__<td>'+$action_buttons+'</td></tr>';
					}
				}
				else{
					var tempForm = $form_config['multiple_form_wrapper'].replace('__MULTIPLE_CONTAINER_ID__',''+$form_config['unique_id']+'-repeat-form-container');
					$form_config['form_wrapper'] = tempForm;
				}

			}

		}
				
	}

	this.createMultipleTableForm = function (){

	}

	this.wrapElement = function () {
		if($form_config['elements_array_only']){
			$form['field_html'][$field] = $new_field;
			return;
		}		
		var alreadyAddedToForm = false; //// If some custom element has been already added to the form eg. upload_single_image field
		if(!$element['element_only'] || !$element['custom_element']){
			if(!$element['wrapper']){
				{ ////Wrap The form field with col-md if specified in the width

					if($form_config['return_form_as'] !== 'table'){
						if ($element['type'] == 'upload_logo' || $element['type'] == 'upload_single_image') {
							if ($element['position'] == 'default') {

							} 
							else {
								$left_offset = ($element['left_offset'] > 0) ? 'col-md-offset-' + $element['left_offset'] + '' : '';
								$right_offset = ($element['right_offset'] > 0) ? 'col-md-offset-right-' + $element['right_offset'] + '' : '';
								image_fields += '<div class="col-md-12 ' + $right_offset + ' ' + $left_offset + '" id="' + $element['id'] + '-holder" style="">' + $new_field + '</div>';
								//return;
								alreadyAddedToForm = true;
							}
						}
						if ($element['type'] != 'hidden') { //// IF NOT HIDDEN ELEMENT WRAP IT
							if ($element['width'] && $element['width'] != '' && $element['width'] != '0' && $element['width'] <= 12) {


								$left_offset = ($element['left_offset'] > 0) ? '' : '';
								$right_offset = ($element['right_offset'] > 0) ? '' : '';

								if (!alreadyAddedToForm) {
									if (width_count_tracker + (+$element['width']) <= 12) {
										width_count_tracker += +$element['width'];
									} 
									else {
										width_count_tracker = +$element['width'];
										if ($element['left_offset']) width_count_tracker += +$element['left_offset'];
										if ($element['right_offset']) width_count_tracker += +$element['right_offset'];
										//$next_form += '</div>';
										//$next_form += '<div class="row  ">';
									}
									$next_form += $form['field_html'][$field] = '<div class="col-md-' + $element['width'] + ' ' + $right_offset + ' ' + $left_offset + ' form-common-element-wrapper" id="' + $element['id'] + '-holder" style="">' + $new_field + '</div>';
								}
							} 
							else {
								if (!alreadyAddedToForm) {
									if (width_count_tracker + 6 <= 12) {
										width_count_tracker += 6;
									} 
									else {
										width_count_tracker = 6;
										//$next_form += '</div>';
										//$next_form += '<div class="row  ">';
									}
									$next_form += $form['field_html'][$field] = '<div class="col-md-6 form-common-element-wrapper" id="' + $element['id'] + '-holder">' + $new_field + '</div>';
								}
							}
						} 
						else { ///IF HIDDEN ELEMENT NO NEED TO WRAP IT
							if (!alreadyAddedToForm)$next_form +=  $form['field_html'][$field] = $new_field;
						}
					}
					else{
						if ($element['type'] != 'hidden') $next_form += $form['field_html'][$field] = '<td>'+$new_field+'</td>';
						else $next_form += $form['field_html'][$field] = $new_field;
					}
				}
				{ //// CODEEND
					/*if ($element['code_end'] != ''){
						$next_form += replaceBetweenBraces($db_data,$element['code_end']);
						$form['field_html'][$field]['wrapped'] += replaceBetweenBraces($db_data,$element['code_end']);
					} */
					if ($element['code_end'] != '') {
						$next_form += $element['code_end'];
						$form['field_html'][$field]['wrapped'] += $element['code_end'];

					}
				}
			}
			//////// IF ELEMENT WRAPPER IS DEFINED
			else{
				$next_form += $element['wrapper'].replace('__ELEMENT__',$new_field);
			}
		}
		else{
			$form['field_html'][$field] = $new_field;
			$next_form += $new_field;
		}
	}

	function createButtons($form_config, $form) {
		if (!$form_config['is_child_form'] && $form_config['return_action_buttons']) {
			if ($form_config['buttons']['enabled']) {
				if ($.trim($form_config['buttons']['html']) != '') { //// PRIORITY IS GIVEN TO BUTTON HTML
					$form['buttons'] += $form_config['buttons']['html'];
				} 
				else if ($form_config['buttons']['array']) { ////// IF NOT HTML THEN CREATE BUTTONS FROM ARRAY
					$.each($form_config['buttons']['array'], function ($btn_id, $btn) {
						//if($.isEmptyObject($btn)) return false;
						if (!$this.actionCallBacks[$btn['actions']['action']]) $this.actionCallBacks[$btn['actions']['action']] = '';
						if ($btn['actions'] && !$btn['actions']['callback']) $btn['actions']['callback'] = $this.actionCallBacks[$btn['actions']['action']];
						$form['buttons'] +=
							'\
						<a href="javascript:;" class="btn ' + $btn['class'] + '" ' + $btn['extra'] + ' id="' + $form_config['unique_id'] + '-btn' + $btn_id + '"  data-unique_id="__UNIQUE_ID__" >' + $btn['title'] + '</a>\
						';
						//console.log($btn);
						$this.formFunctionList.push(
							function () {
								$('#' + $form_config['unique_id'] + '-btn' + $btn_id + '').on('click', function () {
									var ajdata = {};
									ajdata.data = $btn['actions'];
									ajdata.next_data = $btn['next_actions'];
									ajdata.e = this;
									ajdata.unique_id = $form_config['unique_id'];
									ajdata.form = $form_config['form_id'];
									ajdata.callback = $this.actionCallBacks[$btn['actions']['action']];
									//console.log(ajdata);
									$this.ajaxSave(ajdata);
								});
							});

					});
				}
			}
			else {
				$form['buttons'] +=
					'\
				<button type="button" class="btn btn-outline green" id="' + $form_config['unique_id'] + '-btnSave"  onClick="Save({\'extended_action\':\'save\',\'module\':\'' + $form_config['module'] + '\'},\'' + $form_config['form_id'] + '\',\'' + $form_config['unique_id'] + '\')">Save</button>\
				';
			}
		}
	}

	
	function returnFormAs($form_config, $form) {
		//// TODO IMPORTANT : fix the code so return form as work for  modal and other options, because right now the modal form wont work
		if(!$form_config['form_wrapper']) $form_config['form_wrapper'] = $form['form'];
		if(!$this.creatingRepeatForm){
			if ($form_config['return_form_as'] == 'modal') {
				$form_config['form_wrapper'] = '<div class="row  justify-content-center">'+$form_config['form_wrapper']+'</div>';
				console.log($form['form_wrapper']);
				$form['buttons'] =
					'\
				<a href="javascript:;" class="btn btn-outline red" id="' + $form_config['unique_id'] + '-btn-close-modal-2">Cancel</a>\
				' + $form['buttons'] + '\
				';
				$this.formFunctionList.push(
					function () {
						$('#' + $this.options['unique_id'] + '-btn-close-modal-2').on('click', function () {
							$this.RemoveBsModal();
						});
					});
				$modal_html = $this.getModalForm($form);
				$modal_width = getDefault($form_config['modal_width'], '70');
				$modal_wrapper =
					'\
				<div class="modal  modal-scroll draggable-modal animated zoomIn " data-in-animation="zoomIn" data-out-animation="zoomOut" id="' + $form_config['unique_id'] + '-main-container" data-backdrop="static" data-keyboard="true" style="margin:0 auto;min-width:300px;width:' + $modal_width + '">\
				' + $modal_html + '\
				</div>\
				';
				$form_config['form_wrapper'] = $modal_wrapper;

				$this.formFunctionList.push(
					function () {
						$('#' + $form_config['unique_id'] + '-main-container').on('hidden.bs.modal', function () {
							eval('' + $form_config['jsModalClose'] + '');
							$('#' + $form_config['unique_id'] + '-main-container').remove();
						});
					});
				//// add a function to the save finction list which will be called after the ajax save function
				$this.saveFunctionList.push(
					function () {
						eval($form_config['jsModalClose']);
						RemoveBsModal('' + $form_config['unique_id'] + '-main-container');
					});
				$this.priorityJsList.push(
					function () {
						$('body').append($this.form.html);
						toolsAll[$this.options.unique_id] = $this.form.tools;
						ShowBsModal('' + $this.options.unique_id + '-main-container');
						$('#' + $this.options.unique_id + '-main-container').draggable({
							handle: '.modal-header'
						});
					});
			} 
			else if ($form_config['return_form_as'] == 'form_rows_without_buttons') {
				$form_config['form_wrapper'] =
				'\
				<div class="col-md-12">\
					<form id="' + $form_config['form_id'] + '" method="POST" onSubmit="return false;">\
					<div class="row  justify-content-center">\
						'+$form_config['form_wrapper']+'\
						__HIDDEN__\
					</div>\
					</form>\
				</div>\
				';

			}
			else if ($form_config['return_form_as'] == 'form_rows_with_buttons') {
				//$form['form'] += '<div class="row text-center">'+$form['buttons']+'</div>';
				$form_config['form_wrapper'] =
					'\
				<div class="col-md-12">\
				<form id="' + $form_config['form_id'] + '" method="POST" onSubmit="return false;">\
				<div class="row  justify-content-center">\
				'+$form_config['form_wrapper']+'\
				__HIDDEN__\
				</div>\
				<div class="text-center">__BUTTONS__</div>\
				</form>\
				</div>\
				';
				$form['html'] = row($form['form'], $this.rowFormClass);
			} 
			else if ($form_config['return_form_as'] == 'rows_with_buttons') {
				//$form['form'] += '<div class="row text-center">'+$form['buttons']+'</div>';
				$form_config['form_wrapper'] =
					'\
					<div class="row  justify-content-center">\
					'+$form_config['form_wrapper']+'\
					__HIDDEN__\
					</div>\
					<div class="text-center">__BUTTONS__</div>\
				';
				$form['html'] = row($form['form'], $this.rowFormClass);
			} 
			else if ($form_config['return_form_as'] == 'rows') {

				$form_config['form_wrapper'] = '<div class="container" style="padding-top: 30px;"><div class="row  justify-content-center">'+$form_config['form_wrapper']+'\  __HIDDEN__</div></div>';
			}
			else if ($form_config['return_form_as'] == 'popover') {
				if(!$this.creatingRepeatForm){
					$popover_template =
						'\
					<div class="popover popover-with-select2 popover-custom-theme ' + $form_config['unique_id'] + '-popover" role="tooltip" style="width:60%;max-width:60%;">\
						<div class="arrow"></div>\
						<h3 class="popover-header ">Search ' + $form_config['portlet_title'] + '</h3>\
						<div class="popover-actions">\
							<a href="javascript:;" class="btn btn-circle btn-icon-only red" id="__UNIQUE_ID__-btnClose"><i class=" fa fa-close" style="font-size: 30px;"></i></a>\
						</div>\
						<div class="popover-body" id="' + $form_config['unique_id'] + '-main-container">\
						\
						</div>\
						<div class="row text-center d-none" id="' + $form_config['unique_id'] + '-popover-result">\
						<h4 class="font-yellow-gold sub-title">' + $form_config['portlet_title'] + '</h4>\
						</div>\
					</div>\
					';
					if (isset($form_config['popover_template']) && $form_config['popover_template'] != '') {

						$popover_template = str_replace('__UNIQUE_ID__', $form_config['unique_id'], $form_config['popover_template']);
					}

					$form['html'] = $form['form'];
					if($form_config['form_wrapper']){
						$form['html'] = $form_config['form_wrapper'].replace('__FORM__',$form['html']);
						$form['html'] = $form['html'].replace('__HIDDEN__',$form['hidden']);
					}
					$form['html'] += '<div class="text-center">' + $form['buttons'] + '</div>';
					$temp_content =
						'\
					<form id="' + $form_config['form_id'] + '" method="POST" onSubmit="return false;">\
					' + $form['html'] + '\
					' + $form['hidden'] + '\
					</form>\
					';
					$this.priorityJsList.push(
						function () {
							var e = $this.initialOptions['e'];
							console.log(e);
							if($(e).data('bs.popover'))$(e).popover('dispose');
							var pop_on = $(e);
							pop_on.popover({
								title: '<h4 class="font-yellow-gold sub-title">'+ $form_config['portlet_title'] + '</h4>',
								content : $temp_content,
								trigger:'click',
								html: true,
								container: 'body',
								placement: 'left',
								template: $popover_template,
							})
							pop_on.popover('show');
							$('#' + $form_config['unique_id'] + '-btnClose').on('click',function(){
								$(e).popover('dispose');
							});
						});
					$this.saveFunctionList.push(
						function () {
							eval($form_config['jsModalClose']);
							$('.' + $form_config['unique_id'] + '-popover').remove();
						});
				}
			}
			else {
				if($form['buttons'])$form['buttons'] = '<div class="text-center p-3">' + $form['buttons'] + '</div>'
				var tempForm = 
				'\
				' + $form_config['form_wrapper'] + '\
				' + $form['hidden'] + '\
				' + $form['buttons'] + '\
				';
				if($form_config['wrap_in_form']){
					tempForm = 
					'\
					<form id="' + $form_config['form_id'] + '" method="POST" onSubmit="return false;">\
						' + tempForm+ '\
					</form>\
					';
				}
				/* tempForm = 
				'\
				<div class="col-md-12">\
					' + tempForm+ '\
				</div>\
				'; */
				$form_config['form_wrapper'] = tempForm;
			}
		}

		if($form_config['form_wrapper']){
			$form['html'] = $form_config['form_wrapper'].replace('__FORM__',$form['form']);
			$form['html'] = $form['html'].replace('__BUTTONS__',$form['buttons']);
			$form['html'] = $form['html'].replace('__HIDDEN__',$form['hidden']);
			//$form['html'] = $form['html'].replace('__UNIQUE_ID__',$form_config['unique_id']);
			$form['html'] = $form['html'].replace(/__UNIQUE_ID__/g,$form_config['unique_id']);
			return;
		}
	}

	this.executeAllJs = function (isRepeat) {
		if ($this.options['show_form_in_container']) {
			//if($this.options.return_form_as == 'table')console.log($this.form.html);
			$('#' + $this.options['show_form_in_container'] + '').append($this.form.html);
		}
		if ($this.options['show_buttons_in_container']) {
			if(!isRepeat)$('#'+$this.options['show_buttons_in_container']+'').html($this.form.buttons);
		}
		if ($this.options['after_form_created_callback']) {
			var tempFunc = $this.options['after_form_created_callback'];
			if (window[tempFunc]) window[tempFunc]($this.form);
		}

		if($multipleInitOnly){

		}
		else{
			toolsAll[$this.options.unique_id] = $this.form.tools;
		}

		for (i = 0; i < $this.priorityJsList.length; i++) {
			$this.priorityJsList[i]();
		}
		$this.priorityJsList = [];
		eval($this.form.jsFunction);
		$this.form.jsFunction = '';
		for (i = 0; i < $this.formFunctionList.length; i++) {
			$this.formFunctionList[i]();
		}
		$this.formFunctionList = [];
		$.each($this.form.tools['dependency'],function(id,config){
			toggleDependentFields($this.form.tools, id);
		});


		//if($form_config['min_multiple_rows'] > 0){
		if (!$.isEmptyObject($this.options['child_form'])) {
			$.each ($this.options['child_form'],function($k,$child_form){
				var optns2 = $.extend({}, $child_form);
				
				optns2['is_child_form'] = true;
				optns2['parent_unique_id'] = $form_config['unique_id'];
				optns2['name_prefix'] = $form_config['name_prefix_for_child'];
				optns2['base_class'] = $this;
				optns2['unique_id'] = uniqueid();
				optns2['show_form_in_container'] = $form_config['unique_id']+'-'+optns2['module']+'-child-container';
				if($form_config['child_form_titles'][optns2['module']]){
					if($form_config['has_data'])optns2['portlet_title'] = replaceBetweenBraces($form_config['has_data'],$form_config['child_form_titles'][optns2['module']]);
					else if($form_config['non_db_data'])optns2['portlet_title'] = replaceBetweenBraces($form_config['non_db_data'],$form_config['child_form_titles'][optns2['module']]);
					else optns2['portlet_title'] = $form_config['child_form_titles'][optns2['module']];
				}
				//if($form_config['min_multiple_rows'])optns2['min_multiple_rows'] = $form_config['min_multiple_rows'];
				//if($form_config['append_child_form_in'])optns2['show_form_in_container'] = $form_config['append_child_form_in'];
				$this.childClass[optns2.module] = new FormGenerator(optns2);
				if ($this.options['is_child_form']) {
					$this.childClass[optns2.module].setProperty('base_class', $this.options.base_class);
				}
				$this.childClass.actionCallBacks = {
					'save': 'reloadPage',
					'update': 'doNothing'
				};
				var newForm = {};
				/* console.log($form_config['module']);
				console.log($multipleInitOnly) */
				if(($multipleInitOnly) && !$this.options['is_edit']){

				}
				else {
					if(!$.isEmptyObject($child_form['has_data'])) {
						if($child_form['is_multiple_form']){
							if($form_config['is_multiple_form']){
								if(!$this.creatingNewRepeatForm)$this.childClass[optns2.module].setProperty('has_data', $child_form['has_data'][$form_config['has_data']['id']]);
								else $this.childClass[optns2.module].creatingNewRepeatForm = true;
								$this.childClass[optns2.module].getForm();
								
								/* console.log($form_config['module']);
								console.log($form_config['has_data']);
								console.log($form_config['has_data']['id']); */
							}
							else{
								$this.childClass[optns2.module].getForm();
							}
						}
						else{
							$this.childClass[optns2.module].getForm();
						}
					}
					else{
						$this.childClass[optns2.module].getForm();
					}
				}
			});
		}
	}

	this.getDependencyJs = function ($form_config) {
		if ($.isEmptyObject($form_config['dependency'])) {
			return '';
		}

		$i = 0;
		$dependency_fields = $form_config['dependency'];
		$depTemp = [];
		/* $form['tools']['id_map'] */
		$.each($dependency_fields, function ($depKeyTemp, $depValTemp) {
			$depTemp[$form_config['id_prefix'] + '-' + $depKeyTemp] = $depValTemp;
		});

		$.each($dependency_fields, function ($depKey, $depValue) {
			$depTempJson = JSON.stringify($depTemp[$form_config['id_prefix'] + '-' + $depKey]);
			$i++;
			$depKey = $form_config['unique_id'] + '-' + $depKey;
			$this.formFunctionList.push(
				function () {
					let currentSelected = false;
					$(document).on('change', '#' + $depKey + '', function () {
						var dep = JSON.parse('' + $depTempJson + '');
						var prevSelected = currentSelected;
						var selected = $('#' + $depKey + '').val();
						currentSelected = selected;
						var delay = 500;
						/* //console.log(prevSelected); */
						/* //console.log(currentSelected'+$i+') */
						;
						var data = [];
						data['prevSelected'] = prevSelected;
						data['currentSelected'] = currentSelected;
						data['selected'] = selected;
						data['dep'] = dep;
						if (prevSelected !== false && dep.hasOwnProperty(prevSelected)) {
							if (dep[prevSelected].hasOwnProperty('hide')) {
								$.each(dep[prevSelected].hide, function (i, elem) {
									$('#' + $form_config['unique_id'] + '-' + elem + '').removeClass('hidden');
									$('#' + $form_config['unique_id'] + '-' + elem + '').closest('.form-common-element-wrapper').removeClass('animated' + delay + ' zoomOut hidden');
									$('#' + $form_config['unique_id'] + '-' + elem + '').closest('.form-common-element-wrapper').addClass('zoomIn');
								});
								/* //// RUN THE LOOP AGAIN TO TRIGGER CHANGE IF ANY FOR THE ELEMENT */
								/* //// RUNNING LOOP SEPERATELY IS IMPORTNT SO THAT IT DOESNT CONFLICT WITH THE ELEMENTS OF THE ABOVE LOOP */
								$.each(dep[prevSelected].hide, function (i, elem) {
									$('#' + $form_config['unique_id'] + '-' + elem + '').trigger('change');
									$('#' + $form_config['unique_id'] + '-' + elem + '').addClass('edited');
								});
							}
						}
						if (dep.hasOwnProperty(selected)) {
							if (dep[selected].hasOwnProperty('hide')) {
								if ($('#' + $depKey + '').is(':checkbox')) {
									var checked = $('#' + $depKey + '').is(':checked');
									console.log('checkbox');
									if (checked) {
										$.each(dep[selected].hide, function (i, elem) {
											$('#' + $form_config['unique_id'] + '-' + elem + '').addClass('hidden');
											$('#' + $form_config['unique_id'] + '-' + elem + '').closest('.form-common-element-wrapper').addClass('animated' + delay + ' zoomOut');
											window.setTimeout(function () {
												$('#' + $form_config['unique_id'] + '-' + elem + '').closest('.form-common-element-wrapper').addClass('hidden');
											}, delay);
										});
									}
								} else {
									$.each(dep[selected].hide, function (i, elem) {
										console.log(elem);
										console.log($('#' + $form_config['unique_id'] + '-' + elem + '').closest('.form-common-element-wrapper'));
										$('#' + $form_config['unique_id'] + '-' + elem + '').addClass('hidden');
										$('#' + $form_config['unique_id'] + '-' + elem + '').closest('.form-common-element-wrapper').addClass('animated' + delay + ' zoomOut');
										window.setTimeout(function () {
											$('#' + $form_config['unique_id'] + '-' + elem + '').closest('.form-common-element-wrapper').addClass('hidden');
										}, delay);
									});
								}
							}
						}
					});

					var depTemp = JSON.parse('' + $depTempJson + '');
					var selected = $('#' + $depKey + '').val();
					if (selected != '') {
						currentSelected = selected;
					}
					var delay = 10;
					if (depTemp.hasOwnProperty(selected)) {
						if (depTemp[selected].hasOwnProperty('hide')) {
							if ($('#' + $depKey + '').is(':checkbox')) {
								var checked = $('#' + $depKey + '').is(':checked');
								console.log('checkbox');
								if (checked) {
									$.each(depTemp[selected].hide, function (i, elem) {
										$('#' + $form_config['unique_id'] + '-' + elem + '').addClass('hidden');
										$('#' + $form_config['unique_id'] + '-' + elem + '').closest('.form-common-element-wrapper').addClass('animated' + delay + ' zoomOut');
										window.setTimeout(function () {
											$('#' + $form_config['unique_id'] + '-' + elem + '').closest('.form-common-element-wrapper').addClass('hidden');
										}, delay);
									});
								}
							} else {
								$.each(depTemp[selected].hide, function (i, elem) {
									$('#' + $form_config['unique_id'] + '-' + elem + '').addClass('hidden');
									$('#' + $form_config['unique_id'] + '-' + elem + '').closest('.form-common-element-wrapper').addClass('animated' + delay + ' zoomOut');
									window.setTimeout(function () {
										$('#' + $form_config['unique_id'] + '-' + elem + '').closest('.form-common-element-wrapper').addClass('hidden');
									}, delay);
								});
							}
						}
					}
				});
		});
		return $arr;
	}

	this.getAjaxDependentFields = function ($data) {
		$fieldId = $data['field'];
		$value = $data['value'];
		$elemId = $data['elemId'];
		$custom_field = isset($data['custom_field']) ? $data['custom_field'] : false;
		if (!$custom_field) {
			$arrTemp = explode('-', $fieldId);
			$field = end($arrTemp);
		} else {
			$field = $fieldId;
		}

		$return = [];
		$return['jsFunction'] = "";
		$.each($this.ajax_dependency[$field], function ($dep, $depCode) {
			//// Js Function is before the evald code so that if we want to override jsfunction we can do it in the evald code
			$return['jsFunction'] +=
				"\
				$('#" + $elemId + "').empty();\
				/// Create a Blank Option\
				var opt = document.createElement('option');\
				opt.value = '';\
				opt.innerHTML = 'Please Choose an Option';\
				$('#" + $elemId + "').append(opt);\
				$('#" + $elemId + "').addClass('edited');\
				$('#" + $elemId + "').val('');\
				$.each(value.options, function(key,value) {\
					var opt = document.createElement('option');\
					opt.value = key;\
					opt.innerHTML = value;\
					$('#" + $elemId + "').append(opt);\
				});\
				if($('#" + $elemId + "').hasClass('bs-select')){//// IF BOOTSTRAP SELECT\
					$('#" + $elemId + "').selectpicker('refresh');\
				}\
            ";
			eval($depCode['code']);
			$return['options'] = $element['options'];

		});
		return $return;

	}

	this.RemoveBsModal = function () {
		var $modal = $('#' + $this.options['unique_id'] + '-main-container');
		var outAnimation = $modal.attr('data-out-animation');
		var delay = $modal.attr('data-delay') || 700;
		$modal.addClass(outAnimation);
		window.setTimeout(function () {
			$modal.modal('hide');
			$modal.remove();
		}, delay);
		if ($this.initialOptions.has_form_waiting) {
			var formClass = $this.initialOptions.waiting_form;
			formClass.RemoveBsModal();
		}
		toolsAll[$this.options['unique_id']] = {};
		$this = null;
	}

	this.getModalForm = function ($data, $header_buttons, $ajax) {
		if (!$header_buttons) $header_buttons = true;
		if (!$ajax) $ajax = true;
		$title = $data['portlet-title'];
		$sub_title = isset($data['portlet-sub-title']) ? $data['portlet-sub-title'] : $title + ' Details';
		$actionMethod = '';
		if (isset($data['form-submit']) && $data['form-submit']) {
			$actionMethod = 'method="POST" action="' + $data['form-submit-url'] + '"';
		} else {
			$actionMethod = 'method="POST" onSubmit="return false;"';
		}
		$headerBtnTemp = '';
		if ($header_buttons) {
			$headerBtnTemp =
				'\
            <div class="modal-button-group pull-right">\
                <a href="javascript:;" class="btn btn-lg btn-secondary  m-btn m-btn--outline-2x m-btn--air m-btn--icon m-btn--icon-only m-btn--pill"  id="' + $this.options['unique_id'] + '-btn-close-modal"><i class=" la la-close"></i></a>\
            </div>\
            ';
			$this.formFunctionList.push(
				function () {
					$('#' + $this.options['unique_id'] + '-btn-close-modal').on('click', function () {
						$this.RemoveBsModal();
					});
				});
		}

		$return = '';
		$return +=
			'\
                <div class="modal-dialog modal-dialog-centered" style="width:100%">\
                    <div class="modal-content">\
                        <div class="modal-header" style="padding:0px">\
                        <div class="modal-title fancy-modal-title">\
                            <div class="caption">\
                                <span class="bold uppercase">' + $title + '</span>\
                                \
                            </div>\
                            ' + $headerBtnTemp + '\
                        </div>\
                        \
                        </div>\
                        <div class="modal-body" id="' + $this.options['unique_id'] + '-modal-body" style="padding:20px;max-height: 90%;" >\
                            <form role="form" id="' + $data['form-id'] + '" ' + $actionMethod + '>\
                            __HIDDEN__\
                            <div class="container" style="margin-top: 20px;">\
                            '+$form_config['form_wrapper']+'\
                            </div>\
                            </form>\
                            <div class="col-md-12 text-center">\
                            </div>\
                             <div class="row text-center" id="' + $data['tools']['unique_id'] + '-popover-result">\
                             \
                             </div>\
                        </div>\
                        <div class="modal-footer text-center justify-content-center">\
                            __BUTTONS__\
                            \
                        </div>\
                    </div>\
                    <!-- /.modal-content -->\
                </div>\
                ';
		$this.formFunctionList.push(
			function () {
				if (BOOTSTRAP_VERSION == '4') {
					var height = $(window).height() - 300;
					$('#' + $this.options['unique_id'] + '-main-container').mCustomScrollbar({
						axis: 'y',
						mouseWheel: {
							scrollAmount: 225,
							preventDefault: true
						},
						theme: "minimal-dark"
					});
				} 
				else {
					$('#' + $this.options['unique_id'] + '-modal-body').slimScroll({
						//height: '500px',
						railVisible: true,
						railColor: '#222',
						alwaysVisible: true
					});
				}
			});
		return $return;
	}

	this.construct(options);
}