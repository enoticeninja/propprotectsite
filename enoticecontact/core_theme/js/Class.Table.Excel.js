var TableExcel = function(options){

    $this = new Table(options);
    /* $this.tableConfig.has_checkbox = true;
    $this.tableConfig.has_tr_buttons = false;
    $this.tableConfig.hasDataTable = false;
    $this.tableConfig.has_status_label = false; */
    $this.isCreatingNewRow = false;
	var $form = new Form();
    var firstTdClass = 'excel-focus-start';
	$this.getTable = function(){
		$formActions = '';
		$pagination = '';
		$this.functions = [];
		$this.generateTable();
        $this.headers = $this.generateTableHeaders($this.tableConfig);
        $this.filterFields = $this.generateFilterRow($this.tableConfig);    
		$this.bulkUpdateRow = $this.generateBulkUpdateRow();

        if(isset($this.tableConfig['portlet_actions'])){
            $.extend($this.actions,$this.tableConfig['portlet_actions']);
        }			
		var numOfCols = Object.keys($this.tableConfig['cols']).length;
		if(numOfCols > 8){
			$('body').addClass('sidebar-mini page-sidebar-closed');
		}
		//////// this is the function that handles search, the code is in setTableClassElements
		if($this.isFirstCall){

			$this.functions.push(function(){
					$('#'+$this.uniqueId+'-table-simple-search').val($this.searchString);
					$(document).on('click','#'+$this.uniqueId+'-table-simple-search-btn',function(e){
						if($.trim($('#'+$this.uniqueId+'-table-simple-search').val() != '')){
							$this.searchString = $.trim($('#'+$this.uniqueId+'-table-simple-search').val());
							$this.searchTable(1);
						}
					});
				}
			);
			$this.functions.push(function(){
					$(document).on('change keyup','#'+$this.uniqueId+'-table-simple-search',function(e){
						$this.searchString = $(this).val();
						if ( e.which == 13 ){ // Enter key = keycode 13
							e.preventDefault();		
							if($.trim($(this).val() != '')){
								$this.searchString = $.trim($(this).val());
								$this.searchTable(1);
							}
						}	
						else{
							if($.trim($(this).val()) != ''){
								//$this.searchTable(1);
							}
							else{
								console.log($this.searchedAtleastOnce);
								if($.trim($(this).val()) == '' && $this.searchedAtleastOnce) {
									$('#'+$this.container).html($this.initialHtml);
								}
							}
						}
					});
				}
			);
		}
		if(!$this.pagination) {
			$this.paginationCode = '';
		}

		///// If totals rows in db is less than rows to display no pagination code
		
		$this.rowsCountDisplay = Number($this.rowsCountDisplay);
		$this.totalRowsInDb = Number($this.totalRowsInDb);
		if($this.rowsCountDisplay < $this.totalRowsInDb){
			if($this.pagination) {
				$this.getPagination($this.rowsCountDisplay, $this.totalRowsInDb,$this.currentPagination);
				$this.getRowCount($this.rowsCountDisplay, $this.totalRowsInDb, $this.rowsCountDisplay);
			}			
		}
		else{
			$this.paginationCode = '';
			$this.rowsSelect = '';
		}
		if($this.rowsCountDisplay > $this.totalRowsInDb){

		}

		$bulk_update_row = '';
		if($this.bulkUpdateRow != ''){
		
			var tempData = {uniqueId:$this.uniqueId,bulkUpdateRow:$this.getBulkUpdateRow()};
			$bulk_update_row = getBulkUpdateModal(tempData);
			/*$this.functions.push(function() {
				$('body').append($bulk_update_row);$('#bulk-update-row').draggable({handle:'modal-header'});console.log('draggable');
			});	 */		
			if(this.isFirstCall) $this.jsFunction += "$('#bulk-update-row .modal-header').css('cursor','grab');$('body').append();$('#bulk-update-row').draggable({handle:'.modal-header'});console.log('draggable');";
		}
		////////////////// TABLE DATA ///////////////////////////////
		var $tableData = '';
		$tableData = 
		'\
		<div class="table-container '+$this.tableContainerClass+' justify-content-center" id="'+$this.tableContainerId+'">\
			<form id="table-form-wrapper" onSubmit="return false;">\
				'+$bulk_update_row+'\
				<table class="excel-table" id="'+$this.uniqueId+'-auto-table" >\
				'+$this.getHead()+'\
					<tbody id="'+$this.tbodyId+'">\
					'+$this.table+'\
					</tbody>\
				</table>\
			</form>\
		\
		<div id="" class="row">\
			<div class="col-md-3"></div>\
			<div id="table-pagination-bottom" class="col-md-9 row  text-center">\
				'+$this.paginationCode+'\
			</div>\
		</div>\
        ';
 
		 //$actionsString = $this.actions.join('');
		 $actionsString = '';
		 if(!$this.tableConfig['search'])$this.actions['quick-search'] = '';
		 $.each($this.actions,function($k,$action){
			$actionsString += $action;
		 });
		 var $return = getTableContainerCustom({tableTitle:$this.tableTitle,actionsString:$actionsString,uniqueId:$this.uniqueId,tableData:$tableData});
		$('#'+$this.container).html($return);
		

		if(this.isFirstCall && $this.tableConfig.is_report){
			//$this.tableConfig.report_form.buttons.enabled=false;
			$this.tableConfig.report_form.form_group_type = 'default';
			$this.tableConfig.report_form.buttons = {};
			$this.tableConfig.report_form.buttons.html='<a href="javascript:;" class="btn bg-less-glossy-info btn-half-block" id="'+$this.uniqueId+'-search-report-btn"> Get Report</a>';
			var reportFormClass = new FormGenerator($this.tableConfig.report_form);
			reportFormClass.actionCallBacks = {
				'save': '',
				'update': ''
			};
			var custForm = reportFormClass.getSingleForm();
			$('#'+$this.uniqueId+'-report-form').html(custForm.form);
			reportFormClass.executeAllJs();
			$('#'+$this.uniqueId+'-search-report-btn').on('click',function(){
				CommonFunc2({action:'search-report',module:$this.tableConfig.report_form.module},''+$this.uniqueId+'-report-form',this) .then(function (value) {
					console.log('Ajax Success');
				});					
			});
		}		
		if(this.isFirstCall && $this.tableConfig.has_search_form){
			//$this.tableConfig.report_form.buttons.enabled=false;
			$this.tableConfig.search_form.form_group_type = 'default';
			$this.tableConfig.search_form.buttons = {};
			$this.tableConfig.search_form.buttons.html='<a href="javascript:;" class="btn bg-less-glossy-info btn-half-block" id="'+$this.uniqueId+'-search-report-btn"> Get Report</a>';
			var reportFormClass = new FormGenerator($this.tableConfig.search_form);
			reportFormClass.actionCallBacks = {
				'save': '',
				'update': ''
			};
			var custForm = reportFormClass.getSingleForm();
			$('#'+$this.uniqueId+'-report-form').html(custForm.form);
			reportFormClass.executeAllJs();
			$('#'+$this.uniqueId+'-search-report-btn').on('click',function(){
				CommonFunc2({action:'search-report',module:$this.tableConfig.search_form.module},''+$this.uniqueId+'-report-form',this) .then(function (value) {
					console.log('Ajax Success');
				});					
			});
		}
		$this.afterTableCreation();
		$this.isFirstCall = false;		
    }
    
	function getTableContainerCustom(data) {
		var $return =
			'\
			<div class="m-portlet col-md-12">\
				<div class="m-portlet__head">\
					<div class="m-portlet__head-caption">\
						<div class="m-portlet__head-title">\
							<span class="m-portlet__head-icon">\
								<i class="flaticon-coins"></i>\
							</span>\
							<h3 class="m-portlet__head-text">\
								' + data.tableTitle + '\
							</h3>\
						</div>\
					</div>\
					<div class="m-portlet__head-tools row">\
						' + data.actionsString + '\
					</div>\
				</div>\
				<div class="m-portlet__body p-2">\
				' + data.tableData + '\
				<div id="table-header-fixed"></div>\
				</div>\
			</div>\
			 ';
		return $return;
	}
	$this.jEditable = function(){
	}
	$this.getPagination = function($rowsCountDisplay, $totalRowsInDb, $activeNum) {
		$pagination = getTablePagination($rowsCountDisplay, $totalRowsInDb, $activeNum, $this.uniqueId);
		$this.paginationCode = $pagination;    
		if($this.isFirstCall){
			$this.functions.push(function(){
					$(document).on('change','.'+$this.uniqueId+'-paginate-select',function(e){
						var pageNum = $(this).val();
						$this.searchTable(pageNum);
					});
				}
			);
		}

		if($this.isFirstCall){
			$this.functions.push(function(){
					$(document).on('click','.'+$this.uniqueId+'-paginate',function(e){
						var pageNum = $(this).data('page');
						console.log(pageNum);
						$this.searchTable(pageNum);
						//$this.getTable();
					});
				}
			);
		}
	}

	$this.getRowCount = function($rowsCountDisplay, $totalRowsInDb, $curSelected) {

		$tempLengthOfPagination = Math.floor($totalRowsInDb/$this.rowsCountDisplay) + 1; // next lowest integer value
		//console.log($tempLengthOfPagination);
		$this.rowsSelect =
			'\
			<label>\
			<select name="" id="'+$this.uniqueId+'-rows_to_display"  class="bs-select form-control" data-style="btn-success" >\
			';
		if($this.isFirstCall){
			$this.functions.push(function(){
					$(document).on('change','#'+$this.uniqueId+'-rows_to_display',function(e){
						var pageNum = $(this).val();
						$this.searchTable(1);
						//$this.getTable();
					});
				}
			);
		}

		$tempRow = $this.rowsCountDisplay;
		for($i=1;$i<$tempLengthOfPagination;$i++) {
			$tempSelected = '';
			if($i*$tempRow == $curSelected) $tempSelected = 'selected';			
			$this.rowsSelect += '<option value="'+$i*$tempRow +'" '+$tempSelected+'>'+$i*$tempRow +'</option>';					
		}
		if($totalRowsInDb%$this.rowsCountDisplay != 0 ){ ///Check to display the remainder of rows whose count is less than 10
			$tempSelected = '';
			if($totalRowsInDb == $curSelected) $tempSelected = 'selected';		
			$this.rowsSelect += '<option value="'+$totalRowsInDb+'"  '+$tempSelected+'>'+$totalRowsInDb+'</option>';					
				
		}

		$this.rowsSelect +=
				'\
                </select> \
                records/page</label>\
				';			
	}

	$this.initDataTable = function(){	
	}
	
	$this.getHead = function(){
		$tableHead = 
		'<thead class="">\
			<tr role="row" class="excel-th">\
		';
		$tableHead += $this.getHeaders(); 
		$tableHead += '</tr>';
		//$tableHead += $this.filterHeader();
		$tableHead += '</thead>';
		return $tableHead;
	}
	

	$this.getHeaders = function() {
		$tableData = '';
		if($this.headers != ''){
			$i = 0;
			$icon = '';
			$.each($this.headers,function($k,$title) {
				if($this.headerIcons != '') $icon = '<i class="fa fa-'+$this.headerIcons[$i]+'"></i>';		
				$tableData += '<th class="sorting">'+$icon+' '+$title+'</th>';
				$i++;			
			 });
		}
		return $tableData;		
	}
	
    $this.getBulkUpdateRow = function(){
       return $this.bulkUpdateRow; 
    }

    $this.setTableClassElements = function(){
        $this.filterRows = '';
        $this.actions['quick-search'] = getTableQuickSearch($this.uniqueId,$this.searchString);
		////// ONCLICK HANDLER IS IN getTable					
        /* $this.tableActionFields[] = '<a href="#modalForm" type="button" class="btn btn-sm yellow dropdown-toggle" data-toggle="modal" aria-expanded="false" onClick="GetForm(\'new-form\',\'\',\'add-mtrow\',\'modalForm\')">Add '+$class->common_title+'</a>'; */

        $this.tableActionsWrapper = '';
        $this.tableTitle = $this.common_title;
        $this.hasNewRow = false;
        $this.hasSaveChanges = false;
        $this.pagination = true;		
        $this.portletClass = 'light';	
        $this.iconClass = 'fa fa-user';	        
        $this.tableContainerClass = 'animated fadeIn';	        
    }    

    $this.getCommonTableFields = function(){
        ////Specify all the fields here and override them in custom-cols later
        var tableConfig = {};
        tableConfig['cols'] = {};
        tableConfig['field_functions'] = {};
        tableConfig['col_functions'] = {};/// functions to be executed on any field if needed
        $this.setTableClassElements();
        tableConfig['where_condition'] = false;
        tableConfig['is_report'] = false;
        tableConfig['has_checkbox'] = true;
        tableConfig['has_status_label'] = true;
        tableConfig['has_tr_buttons'] = true;
        tableConfig['custom_headers'] = false; //array
        tableConfig['typeahead'] = false;
        tableConfig['on_main_thread'] = true;
        tableConfig['typeahead_fields'] = ['name','page_name'];
        tableConfig['typeahead_html'] = '<span ><div><strong>{{name}}</strong> - {{page_name}} - {{type}} </div></span>';
        tableConfig['search'] = false;          
        tableConfig['search_fields'] = ['name','page_name'];
        tableConfig['status_field'] = 'status';
		tableConfig['status_label'] = {};
		getTableLabels(tableConfig['status_label']);
		tableConfig['tr_action_btn_class'] = 'btn-secondary';
        tableConfig['tr_buttons'] = {};
        tableConfig['cond_tr_buttons'] = {};
        tableConfig['editable'] = [];
        tableConfig['module'] = $this.module;
		getTableActionButtons(tableConfig);
        
        tableConfig['custom_cols'] = {};
        tableConfig['eval'] = {};
        return tableConfig;	
    }

	$this.getForeignKeys = function(){
		return this.foreignKeys;
	}
	$this.submitRowAsForm = function(idRow) {
		form = document.createElement("form"); // CREATE A NEW FORM TO DUMP ELEMENTS INTO FOR SUBMISSION
		form.method = "post"; // CHOOSE FORM SUBMISSION METHOD, "GET" OR "POST"
		form.action = ""; // TELL THE FORM WHAT PAGE TO SUBMIT TO
		$("#"+idRow+" td").children().each(function() { // GRAB ALL CHILD ELEMENTS OF <TD>'S IN THE ROW IDENTIFIED BY idRow, CLONE THEM, AND DUMP THEM IN OUR FORM
		var elemType = this.nodeName.toLowerCase();
			  if(elemType === "select") { // JQUERY DOESN'T CLONE <SELECT> ELEMENTS PROPERLY, SO HANDLE THAT
				  input = document.createElement("input"); // CREATE AN ELEMENT TO COPY VALUES TO
				  input.type = "hidden";
				  input.name = this.name; // GIVE ELEMENT SAME NAME AS THE <SELECT>
				  input.value = this.value; // ASSIGN THE VALUE FROM THE <SELECT>
				  form.appendChild(input);
			  } 
			  else if(elemType === "input"){ // IF IT'S NOT A SELECT ELEMENT, JUST CLONE IT.
				  $(this).clone().appendTo(form);
			  }
	  
		});
		form.style.display = "none"; 
		return form;
		//form.submit(); // NOW SUBMIT THE FORM THAT WE'VE JUST CREATED AND POPULATED
	}

	$this.excelAjaxSave = function (e) {
		//return;
		return new Promise(function (resolve, reject) {
			//var formData = {};
			var checkedRequired = false;
			var checkedValidation = false;
			var checkedDuplicate = false;
			var validateFields = true;
			if (validateFields) {
				var parent = $(e).closest('tr').data('parent');
				var tools = toolsAll[parent];
					//tools = (typeof toolsAll[unique_id] !== 'undefined') ? toolsAll[unique_id] : [];
					check = validateAndRequire(tools);
					checkedRequired = check['required'];
					checkedValidation = check['validated'];
					checkedDuplicate = check['duplicate'];
			}
			else{
				checkedRequired = true;
				checkedValidation = true;
				checkedDuplicate = true;			
			}

			$trId = $(e).closest('tr').attr('id');
			var form = $this.submitRowAsForm($trId);
			formData = new FormData(form);
			formData.append('action', 'save');
			if (checkedRequired && checkedValidation && checkedDuplicate) {
				///// IF REQUIRES ANOTHER FORM TO BE SUBMITTED BEFORE THIS CAN BE SAVED
				$.ajax({
					type: 'POST',
					url: '',
					enctype: 'multipart/form-data',
					contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
					processData: false, // NEEDED, DON'T OMIT THIS
					data: formData,
					success: function (val) {
						$(form).remove();
						try {
							var value = JSON.parse(val);
						} 
						catch (err) {
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
							var $col_data_temp = $this.createArrayForColumns(value.data);
							var $newRow = $this.createTableRow($col_data_temp,value.data);							
							$(e).closest('tr').replaceWith($newRow);
							stopLoading();
						} else {
							console.log(value);
							stopLoading();
						}
						resolve(true);
					}
				});
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
		});
	}

	$this.input = function($dataArray){
		var $input = 
		'<input class="excel-cell-input on-change-update-cell td-'+$dataArray['col']+' autoselect-on-focus" type="text" value="'+$dataArray['value']+'" data-field="'+$dataArray['col']+'" data-id="'+$dataArray['id']+'">\
		';
		return $input;
	}
	$this.select = function($dataArray){
		var $options1 = '<option></option>';
		var  $isSelected = '';
		$.each($dataArray['options'], function($key, $value){
			if($key == $dataArray['value']) $isSelected = 'selected';
			else $isSelected = '';
			$options1 += '<option value="'+$key+'" '+$isSelected+'>'+$value+'</option>';
		});  		
		var $input = 
		'\
		<select class="excel-cell-select td-'+$dataArray['col']+'" data-field="'+$dataArray['col']+'" data-id="'+$dataArray['id']+'">\
		'+$options1+'\
		</select>\
		';
		return $input;
	}
	
	
	 ////CHANGE HERE FOR A NEW PAGE
    $this.createArrayForColumns = function($data){
        var $table = $.extend(true,{},$this.tableConfig);
        var isCreatingEmptyRow = false;
        if($.isEmptyObject($data))isCreatingEmptyRow = true;
        $table['tr_buttons']['edit'] = $this.tableConfig['tr_buttons']['edit'];
        $table['tr_buttons']['delete'] = $this.tableConfig['tr_buttons']['delete'];
        $label = '';
        //$table['has_status_label'] = true;
        ///// STATUS LABEL CODE
        if($table['has_status_label']){
			$label = '';
			if(isset($data[$table['status_field']]) && $.trim($data[$table['status_field']]) !='' && $data[$table['status_field']] != null){
				$label = isset($table['status_label'][$data[$table['status_field']]]) ? $table['status_label'][$data[$table['status_field']]] : '';	
			}
        }
		$all_buttons = wrapTrButtons($table,$data);
		if(isCreatingEmptyRow || $this.isCreatingNewRow)$all_buttons = '';
        
        $col_data_temp = {};	
        $col_headers = {};

        ///// CHECK BOX
        if($table['has_checkbox']){
            
            $col_data_temp['checkbox'] = $form.checkboxFromArray({
                                                    'label':'',
                                                    'id':'check-__ID__',
                                                    'name':'ids[]',
                                                    'class':'check-__ID__',
                                                    'color':'ffa000',
                                                    'value':'__ID__'
													});
			//$col_data_temp['checkbox'] = '<div class="" style="width:30px;margin:0 auto;">'+$col_data_temp['checkbox']+'</div>';										
        }
        
        if(isset($table['has_tr_buttons']) && $table['has_tr_buttons']){        
            $col_data_temp['actions'] = ''+$all_buttons+'';	
        }
        ///// PREPARE TD DATA
		
		
		$fKeys = $this.getForeignKeys();
		var i = 1;
		var totalCols = $table['cols'].length;
        $.each($table['cols'],function($kk,$col){
			if(!$this.isCreatingNewRow){
				if($fKeys[$col])$data['show_value'] = $data[''+$col+'_name'];
				else $data['show_value'] = $data[''+$col+''];
				if(focusChildNode && $table['editable'][$col]){
					var elemArray = {value:$data['show_value'],col:$col,id:$data['id']};
					if($table['editable'][$col]['type'] == 'input'){
						$col_data_temp[$col] = $this.input(elemArray);
					}
					if($table['editable'][$col]['type']  == 'select'){
						elemArray['value'] = $data[$col];
						
						if($table['editable'][$col]['sorted_options']){
							var sort_by = $data[$table['editable'][$col]['sort_by']];
							elemArray['options'] = $table['editable'][$col]['sorted_options'][sort_by];
						}
						else{
							elemArray['options'] = $table['editable'][$col]['options'];
						}
						$col_data_temp[$col] = $this.select(elemArray);
					}					
					//$col_data_temp[$col] = '<input class="excel-cell-input on-change-update-cell td-'+$col+' autoselect-on-focus" type="text" value="'+$data['show_value']+'" data-field="'+$col+'" data-id="'+$data['id']+'" >';
				}
				else{
					$col_data_temp[$col] = $data['show_value'];
				}
			}
			else{
				if($data[$col])$col_data_temp[$col] = $data[$col];
				else $col_data_temp[$col] = '';
			}
			i++;
        });
		//console.log($table['has_status_label']);
		if($table['has_status_label'] || isset($table['override_status_label'])){
			$col_data_temp['label'] = $label;
		}
        else{
            //$col_data_temp['label'] = $label;
        }
        
		if($this.isCreatingNewRow)$col_data_temp['actions'] = '<span class="table-task-buttons"><a class="btn btn-accent m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill m-btn--air" href="javascript:;"" onClick="customSaveRow(this)"><i class="fa fa-check" style=""></i></a><a class="btn btn-danger m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill m-btn--air" href="javascript:;"" onClick="deleteNewRow(this)"><i class="fa fa-trash-o" style=""></i></a></span>';
		$this.isCreatingNewRow = false; ///// reset this variable since there might not always ne new rows being created
        return $col_data_temp;
    }


    $this.createTableRow = function($data, $userData, $trclass){
        if(!$trclass)$trclass = '';
        var $trid = uniqueid();
        var $dbid = 0;
        var style = '';
        if($userData[$this.config['id']]){
			$dbid = $userData[$this.config['id']];
			$trid = $userData[$this.config['id']];
		}
		var check = validatePunchRowData($userData);
		if(!check['status'])style = 'border-color:red;border-style:dashed';

		$row_data = 
		'\
		<tr class=" '+$trclass+' '+$this.module+'-tr  tr-'+$trid+' excel-tr" data-id="'+$dbid+'" id="tr-'+$trid+'" style="'+style+'">\
		';
		
		$.each($data,function( $key,$col) {
			if($key == 'ad_type_id'){
				if(!$col)$col = 'FCT';
			}
			if($key == 'campaign_creative_id'){
				if(!$col)$col = ' ';
			}
			if($key == 'program_id'){
				if(!$col)$col = ' ';
			}
			var editableClass = '';
			var tdStyle = '';
			var classes = 'excel-td excel-td-'+$key+' ';
			if($key == 'actions')classes = '';
			var extra = '';
			if($.inArray($key, $this.tableConfig.editable) !== -1){
				editableClass = ' editable-text-full';
			}
			if($.inArray($key, check['fields'] )!== -1){
				tdStyle = 'background:red;color:white';
			}
			if(!focusChildNode){
				classes = 'td-'+$key+'';
				extra = 'contenteditable="true"';
			}
			else{
				
			}
			$row_data += 
			'\
			<td class="'+classes+' '+editableClass+' '+firstTdClass+'" id="td-'+$trid+'-'+$key+'" data-field="'+$key+'" '+extra+'  style="'+tdStyle+'">'+$col+'</td>\
            ';
            firstTdClass = '';
		});
		
        $row_data += 
        '\
        </tr>\
        ';
        $row_data = str_replace('__ID__',$userData[$this.config['id']],$row_data);
        $row_data = str_replace('__NAME_FIELD__',$userData[$this.config['name']],$row_data);
        $row_data = replaceBetweenSquareBrackets($userData,$row_data);
        return $row_data;
    }

    $this.createTableTdOnly = function($data,$userData){
        
        $row_data = '';

        $.each($data,function($k, $col) {
            $row_data += 
            '\
            <td>'+$col+'</td>\
            ';
        });
        $row_data = str_replace('__ID__',$userData[$this.config['id']],$row_data);
        $row_data = str_replace('__NAME_FIELD__',$userData[$this.config['name']],$row_data);
        return $row_data;
    }	

    $this.generateTableOnMainThread = function(){
		$return = {};
		$return['rows'] = [];
		$this.headers = $this.generateTableHeaders($this.tableConfig);
		$this.filterFields = $this.generateFilterRow($this.tableConfig);    
		$this.bulkUpdateRow = $this.generateBulkUpdateRow();
		if(isset($this.tableConfig['portlet_actions'])){
			$.extend($this.actions,$this.tableConfig['portlet_actions']);
		}

		$tempRowsNum = $this.rowsCountDisplay;
		$row = $this.tableData;

		$table1 = '';

		//$this.getPagination($this.rowsCountDisplay, $this.totalRowsInDb,1);
		$this.table ='';
		$return['rows'] = [];
		$lastTemp = {};
		$.each($row,function($k,$data){
			$data['__count__'] = $k+1;
			//$return.rows.push($data);
			$col_data_temp = $this.createArrayForColumns($data);
			$table1 = $this.createTableRow($col_data_temp, $data);	            
			$this.table += $table1;
			$lastTemp = $data;
			$return['last_id'] = $lastTemp[$this.config['id']];
		});
		//$return['jsFunction'] = $this.getDependencyJsTable($this.table);
		$return['jsFunction'] = '';
		return $return;        
    }
    $this.generateTableOnWorkerThread = function(){
		var myWorker = new Worker(FRONTEND_SITE_PATH+'core_theme/js/Class.Table.Worker.js');
		var $table = $.extend(true,{},$this.tableConfig);
		$table.foreignKeys = $this.foreignKeys;
		$('#'+$this.tbodyId+'').html('');
        //$this.getPagination($this.rowsCountDisplay, $this.totalRowsInDb,1);
		$this.table = '';
		myWorker.onmessage = function(oEvent) {
			if(oEvent.data.action == 'append-row'){
				$this.table += oEvent.data.row;
				$('#'+$this.tbodyId+'').append(oEvent.data.row);
			}
			if(oEvent.data.action == 'done-all'){
				/* $this.initDataTable();
				for (i = 0; i < $this.functions.length; i++){
					$this.functions[i]();
				}

				$this.jEditable();
				if($this.isFirstCall)$this.initialHtml = $('#'+$this.container).html(); */
				$this.afterTableCreation();
				return true;
			}
		};
		myWorker.postMessage($table);
	}
    $this.generateTable = function(){
		if($this.on_main_thread){
			$return = $this.generateTableOnMainThread();
			return $return;
		}
		else{
			$this.generateTableOnWorkerThread();
		}
	}

	$this.afterTableCreation = function(){
		$this.initDataTable();
		for (i = 0; i < $this.functions.length; i++){
			$this.functions[i]();
        }
        var tableDomElement = $('#'+$this.uniqueId+'-auto-table')[0];
        resizableGrid(tableDomElement);

		$("#btnExport").click(function(e){
			/* var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#'+$this.uniqueId+'-auto-table').html());
			window.open(path);
			e.preventDefault(); */
			fnExcelReport($this.uniqueId+'-auto-table');
			//fnExcelReport('table-tbody');
		});	
		var $tableEl = $('#'+$this.uniqueId+'-auto-table');

		//console.log('done');
		//console.log($this.isFirstCall);
		$this.jEditable();
		if($this.isFirstCall){
			$this.initialHtml = $('#'+$this.container).html();
			var table = document.getElementById($this.uniqueId+'-auto-table');
			//focusedTd = table.rows[0].cells[0]; //first column	
			ArrowNavigation($this.uniqueId+'-auto-table');			
		}
		$this.isFirstCall = false;
	}
	function tableFixHead (e) {
		const el = e.target,
			  sT = el.scrollTop;
		el.querySelectorAll("thead th").forEach(th => 
		  th.style.transform = `translateY(${sT}px)`
		);
	}
	function fixedScroll(){
		var tableOffset = $('#'+$this.uniqueId+'-auto-table').offset().top;
		var $header = $('#'+$this.uniqueId+'-auto-table > thead').clone();
		var $fixedHeader = $("#table-header-fixed").append($header);
		$("#table-header-fixed").css('left',$('#'+$this.uniqueId+'-auto-table').offset().left+'px');
		$("#table-header-fixed").width($('#'+$this.uniqueId+'-auto-table').width());


		$(window).bind("scroll", function() {
			var offset = $(this).scrollTop();
			if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
				$fixedHeader.show();
			}
			else if (offset < tableOffset) {
				$fixedHeader.hide();
			}
		});		
	}
    $this.generateTableCustom = function(){
        $this.headers = $this.generateTableHeaders($this.tableConfig);
        $this.filterFields = $this.generateFilterRow($this.tableConfig);
        $this.bulkUpdateRow = $this.generateBulkUpdateRow();
        if($this.tableConfig['portlet_actions'] != ''){
			$.extend($this.actions,$this.tableConfig['portlet_actions']);
        }
		
        $tempRowsNum = $this.rowsCountDisplay;
        $row = $this.tableData;
        $tempnum= $this.numRows;
        $this.totalRowsInDb = $tempnum;

        $table1 = '';

        $this.getPagination($this.rowsCountDisplay, $this.totalRowsInDb,1);		

        $.each($row,function($k,$data) {
			$data['__count__'] = $k+1;
            $col_data_temp = $this.createArrayForColumns($data);
            $table1 = $this.createTableRow($col_data_temp, $data['id']);	            
            $this.table += $table1;
        });
        $return['jsFunction'] = $this.getDependencyJsTable($this.tableConfig);
        return $return;        
    }

    $this.generateTableHeaders = function ($table){
        $col_headers = [];
        $tempLabels = $this.labels;
        
        if($table['has_checkbox']){	
            $col_headers.push($form.checkboxFromArray({
                                                        'label':'',
                                                        'id':'table-master-check',
                                                        'name':'table-master-check',
                                                        'class':'table-master-check',											
                                                        'color':'b71c1c',
                                                        'value':'1'
			}));
        }
        if($table['has_tr_buttons'])  $col_headers.push('Actions');        
        $.each($table['cols'],function( $i,$col){
            if($table['custom_headers']){
                $col_headers.push(isset($table['custom_headers'][$i]) ? $table['custom_headers'][$i]:'');
            }
            else{
               //$col_headers.push(isset($table['custom_cols'][$col]['heading']) ? $table['custom_cols'][$col]['heading']: (isset($tempLabels[$col]) ? $tempLabels[$col] : 'Name'));
			   var tteemmpp = isset($table['custom_cols'] && $table['custom_cols'][$col] && $table['custom_cols'][$col]['heading']) ? $table['custom_cols'][$col]['heading']: (isset($tempLabels[$col]) ? $tempLabels[$col] : 'Name');
			   $col_headers.push(tteemmpp);
            }
            	
        });
        
        
        if($table['has_status_label']){
			$col_headers.push($tempLabels[$table['status_field']]);
        }
        else{
            //$col_headers[] = 'Status';
        }
        

        return $col_headers;	
    }

    $this.generateFilterRow = function($table){
        $col_headers = [];
		if($table['has_checkbox']){
            $col_headers.push('');
        }
        $.each($table['cols'],function( $key,$col){
            $col_headers.push(isset($table['custom_cols'] && $table['custom_cols'][$col] && $table['custom_cols'][$col]['filter']) ? $table['custom_cols'][$col]['filter'] : '');
            //print_r($table['custom_cols'][$col]['filter']);            
        });

        return $col_headers;
    }

    $this.generateBulkUpdateRow = function(){
	   $tr = generateBulkUpdateRow($this,$form);
        return $tr;
    }
    
    return $this;
	} //// END CLASS

	