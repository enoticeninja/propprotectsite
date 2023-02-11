var Table = function(options){
	var	$this = this;
	this.hasNewRow = false;	
	this.hasSaveChanges = false;	
	this.pagination = false;	
	this.paginationCode = '';	
	this.headers = '';	
	this.table = '';	
	this.tableTitle = '';	
	this.tbodyId  = 'table-tbody';	//Id for ajax usage, for appending new rows
	this.tableActionsWrapper = '';	
	this.filterRow = '';	
	this.filterFields = {};	
	this.filterClass = '';	
	this.filterId = '';	
	this.tableActionFields = '';	
	this.headerIcons = '';
	this.documentReady = '';
	this.rowButtons = '';
	this.portletClass = 'box yellow';
	this.totalRowsInDb = 0; // Total Rows in database
	this.rowsSelect = ''; // Select list for rows to display
	this.rowsCountDisplay = 3; // Number of Rows to display
	this.bulkUpdateRow = ''; 
	this.returnTableAs = ''; 
	this.tableContainerClass = ''; 
	this.tableContainerId = ''; 
	this.jsFunction = ""; 
	this.total_cols = ""; 
	this.actions = {};
	this.tableConfig = {};
	this.foreignKeys = {};
	this.data = {};
	this.config = {};
	this.numRows;
	this.module;
	this.container;
	this.currentPagination = 1;
	var $form = new Form();
	this.uniqueId = uniqueid();
	this.functions = [];
	this.tableData;
	this.initialHtml;
	this.isFirstCall = true;
	this.searchedAtleastOnce = false;
	this.hasDataTable = false;
	this.searchString = '';
	this.searchAction = 'searchwithpaginate';
	this.on_main_thread = true;	

    this.construct = function(options){
        this.foreignKeys = $.extend({},options.foreignKeys);
		this.common_title = options.common_title;
		this.tableData = options.tableData;
		this.config = options.config;
		if(options.searchAction)this.searchAction = options.searchAction;
		this.totalRowsInDb = options.totalRowsInDb;
		if(options.hasDataTable)this.hasDataTable = options.hasDataTable;
		this.labels = options.labels;
		this.module = options.module;
		this.container = options.container;
		if(options.rowsCountDisplay)this.rowsCountDisplay = options.rowsCountDisplay;
		if(options.report_form && options.report_form.fields)options.is_report = true;
		if(options.search_form && options.search_form.fields)options.has_search_form = true;
		this.tableConfig = $.extend(true,this.getCommonTableFields(),options);
		this.on_main_thread = this.tableConfig.on_main_thread;
		console.log(this.on_main_thread);
		if(this.tableConfig.is_report){
			this.tableConfig['typeahead'] = false;
			this.tableConfig['search'] = false;
			this.tableConfig['has_status_label'] = false;
			this.tableConfig['has_checkbox'] = false;
			this.tableConfig['has_tr_buttons'] = false;
			this.tableConfig['th_buttons'] = {};
			this.tableConfig['portlet_actions'] = {};
		}
		return this;
    };
	
	this.getTable = function(){
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
		<div class="table-container '+$this.tableContainerClass+'" id="'+$this.tableContainerId+'">	\
			<div class="row">\
			'+$this.getTableActionsWrapper()+'\
				<div id="table-pagination" class="col-md-9 col-sm-12 col-xs-12 row text-center">\
					'+$this.paginationCode+'\
				</div>\
			</div>\
			\
			<form id="table-form-wrapper" onSubmit="return false;">\
				'+$bulk_update_row+'\
				<table class="table table-striped table-bordered table-hover flip-content" id="'+$this.uniqueId+'-auto-table" >\
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
		 var $return = getTableContainer({tableTitle:$this.tableTitle,actionsString:$actionsString,uniqueId:$this.uniqueId,tableData:$tableData});
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

	this.initDataTable = function(){
		//$this.hasDataTable = false;
		if($this.hasDataTable){
			$this.functions.push(function() {
				var e = $('#'+$this.uniqueId+'-auto-table'),
					a = 0;
					a = $("header").outerHeight(!0);
				/* App.getViewPort().width < App.getResponsiveBreakpoint("md") ? $(".page-header").hasClass("page-header-fixed-mobile") && (a = $(".page-header").outerHeight(!0)) : $(".page-header").hasClass("navbar-fixed-top") ? a = $(".page-header").outerHeight(!0) : $("body").hasClass("page-header-fixed") && (a = 64); */
				var dtAuto = e.DataTable({
					language: {
						aria: {
							sortAscending: ": activate to sort column ascending",
							sortDescending: ": activate to sort column descending"
						},
						emptyTable: "<h2>No data available in table</h2>",
						info: "",
						infoEmpty: "No entries found",
						infoFiltered: "",
						lengthMenu: "",
						search: "Search In Displayed Data:",
						zeroRecords: "No matching records found"
					},
					//bAutoWidth:false,
					buttons: {
					  dom: {
						button: {
						  className: 'btn-mtz btn-mtz-square gradient-shadow'
						}
					  },
					buttons: [{
						extend: "print",
						className: " gradient-45deg-purple-deep-purple"
					}, {
						extend: "copy",
						className: "gradient-45deg-orange-amber"
					}, {
						extend: "pdf",
						className: "gradient-45deg-purple-amber"
					}, {
						extend: "excel",
						className: "gradient-45deg-blue-indigo"
					}, {
						extend: "csv",
						className: "gradient-45deg-green-teal"
					}, {
						extend: "colvis",
						className: " gradient-45deg-purple-deep-orange",
						text: "Columns"
					}]},
					colReorder: {
						reorderCallback: function() {
							console.log("callback")
						}
					},
					responsive: {
						details: {
							type: 'column'
						}
					},
					columnDefs: [{
						className: 'control',
						orderable: false,
					}],
					fixedHeader: {
						header: !0,
						headerOffset: a
					},
					order: [
						[0, "asc"]
					],
					lengthMenu: [
						[5, 10, 15, 20, -1],
						[5, 10, 15, 20, "All"]
					],
					pageLength: $this.rowsCountDisplay,
					//dom: "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>"
					dom: "<'row' <'col-md-6 col-sm-12 text-left'f><'col-md-6 text-right'B>><'table-scrollable't><'row'>"
				})
				dtAuto.fixedHeader.adjust();
				});
			}		
	}
	this.jEditable = function(){
		var currentID = 0;
		var currentField;
		var editedField;
		var submitdata = {}
		$(document).on('mouseover','.editable-text-full',function(){
			currentID = $(this).closest('tr').data('id');
			currentField = $(this).data('field');
			//console.log(currentID);
		});
		$(".editable-text-full").editable("", {
			indicator : "<img src='img/spinner.svg' />",
			type : "text",
			// only limit to three letters example
			//pattern: "[A-Za-z]{3}",
			onedit : function() { 
				//console.log('If I return false edition will be canceled'); return true;
			},
			before : function() { 
				//console.log('Triggered before form appears');
				//console.log(currentID);
			},
			callback : function(result, settings, submitdata) {
				//console.log('Triggered after submit');
				//console.log('Result: ' + result);
			},
			cancel : 'Cancel',
			cssclass : 'custom-class',
			cancelcssclass : 'btn btn-danger',
			submitcssclass : 'btn btn-success',
			maxlength : 200,
			// select all text
			select : false,
			label : '',
			onreset : function() { 
				//console.log('Triggered before reset') 
			},
			onsubmit : function() { 
				//console.log('Triggered before submit') 
			},
			showfn : function(elem) { elem.fadeIn('slow') },
			submit : 'Save',
			//submitdata : submitdata,
			//submitdata as a function example
			submitdata : function(revert, settings, submitdata) {
				submitdata['action'] = 'quick-update';
				submitdata['module'] = $this.tableConfig.module;
				submitdata['id'] = currentID;
				submitdata['field'] = editedField = currentField;
				submitdata['value'] = submitdata.value;
				//console.log("Revert text: " + revert);
				//console.log(submitdata);
				//console.log("User submitted text: " + submitdata.value);
			},
			intercept : function(jsondata) {
				json = JSON.parse(jsondata);
				//console.log(json);
				//console.log(submitdata.field);
				return json.data[editedField];
			},
			 
			tooltip : "Click to edit...",
			width : 160
		});			
	}
	this.typeahead = function(){
		var typeahead_html = 
		'\
		<a href="'+site_path+'Job/{{ID}}" target="blank"><span ><div><strong>{{name}} - {{name}} {{companyName}} {{name}}</strong> - {{name}} </div></span></a>\
		';
        var remoteData = new Bloodhound({
          datumTokenizer: Bloodhound.tokenizers.obj.whitespace,
          queryTokenizer: Bloodhound.tokenizers.whitespace,
          remote: {
                url:  window.location.href+'?module='+$this.tableConfig['module']+'&action=typeahead&query=%QUERY',
                wildcard: '%QUERY',
                rateLimitWait: 500,
                transform: function (response){
                        console.log(response);
                        //eval(response.jsFunction);
                        //return response.rows;
                        return response;
                },
                cache: false //NEW!
          }
        });

        remoteData.clearPrefetchCache();
        remoteData.initialize();
        $('#remote .typeahead').typeahead({
			minLength: 3,
			hint: true,
			highlight: true,
			name: 'asdfsd',
			display: $this.tableConfig['typeahead_fields'][0],
			source: remoteData.ttAdapter(),
			select: function (eve,obj) {
				eve.preventDefault();
				console.log(obj);
				return false;
			},
			templates: {
			notFound : [
			  '<div class=\"empty-message\">',
				'Found Nothing',
			  '</div>'
			].join('\\n'),
			suggestion: Handlebars.compile('<div><strong>{{name}}</strong> – {{id}}</div>')
			}
        });
		
		$('.typeahead').bind('typeahead:select', function(ev, suggestion) {
		  console.log(suggestion);
		  return suggestion.id;
		});
	}

	this.getPagination = function($rowsCountDisplay, $totalRowsInDb, $activeNum) {
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

	this.searchTable = function(pageNo){
		startLoading();
		if(!pageNo) pageNo = 1;
		var rows = $('#'+$this.uniqueId+'-rows_to_display').val();
		var query = $('#'+$this.uniqueId+'-table-simple-search').val();
		if(!rows)rows = $this.totalRowsInDb;
		var currentPagination = pageNo;
		$.ajax({
			type:'POST',
			url: '',
			data: {'action':$this.searchAction,'rows':rows,'currentPagination': currentPagination,'query': query,'module': $this.module},
			success: function(val){
				try{
				var value = {};
				value = JSON.parse(val);
				stopLoading();
				if(value.ajaxresult == true) {
					$this.tableData = {};
					$this.tableData = value.tableData;
					$this.currentPagination = Number(value.currentPagination);
					$this.rowsCountDisplay = Number(value.rowsCountDisplay);
					$this.totalRowsInDb = Number(value.totalRowsInDb);
					$this.rowsCountDisplay = Number(value.rowsCountDisplay);
					$this.tableConfig = $.extend($this.tableConfig,value);
					//console.log($this.tableConfig);
					$('#'+$this.container).html('');
					$this.searchedAtleastOnce = true;
					$this.getTable();
					if (value.hasOwnProperty('jsFunction')) {
						 eval(value.jsFunction);
					}
				}
				else {
					stopLoading();								
				}
				$('#'+$this.uniqueId+'-table-simple-search').val(query);
				}
				catch(e){
					console.log(e);
					console.log(val);
					stopLoading();
				}
			}
		});	
	}

	this.getRowCount = function($rowsCountDisplay, $totalRowsInDb, $curSelected) {

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
		
	this.getTableActionsWrapper = function(){
		$tableActionsWrapper = '';		
		$dataTemp = '';
		if ($this.tableActionFields != '') {
			$.each($this.tableActionFields, function($k,$value){
				$dataTemp += $value;
			});
		}

		$tableActionsWrapper = 
		'\
		<div id="rows_to_display_container" class="col-md-3 hidden-sm hidden-xs">\
			'+$this.rowsSelect+'\
		</div>\
		';

		return $tableActionsWrapper;	
	}
	
	this.getHead = function(){
		$tableHead = 
		'<thead class="">\
			<tr role="row" class="">\
		';
		$tableHead += $this.getHeaders(); 
		$tableHead += '</tr>';
		//$tableHead += $this.filterHeader();
		$tableHead += '</thead>';
		return $tableHead;
	}
	
	this.filterHeader = function(){
		$filterColumns = '';
		$.each($this.filterFields, function($k,$v) {
			
			$filterColumns += 
			'\
			<td rowspan="1" colspan="1">\
				'+$v+'\
			</td>\
			';
		
		});
		$this.filterRow = 
		'\
			<tr role="row" class="filter '+$this.filterClass+'" id="'+$this.filterId+'">\
				<form method="POST" action="return false;" id="tablefilterform">\
					'+$filterColumns+'\
				</form>\
			</tr>\
		';	
	return $this.filterRow;	
	}

	this.getHeaders = function() {
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
	
    this.getBulkUpdateRow = function(){
       return $this.bulkUpdateRow; 
    }	

    this.setTableClassElements = function(){
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

    this.getCommonTableFields = function(){
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

	this.getForeignKeys = function(){
		return this.foreignKeys;
	}
	
	 ////CHANGE HERE FOR A NEW PAGE
    this.createArrayForColumns = function($data){
		var $table = $.extend(true,{},$this.tableConfig);
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
        $.each($table['cols'],function($kk,$col){
			
			/// IF CUSTOM COLS ARE DEFINED
			//// 0. if its defined as $this->table['eval']['backend_image']['value'] = 'some html'
			//// 1. if its defined as $this->table['custom_cols']['backend_image']['value'] = 'some html'
			//// 2. if its defined as custom col but no value, it will try to find the key in db data
			//// 3. if no custom cols and it is a foreign key, the values of key_name will be used
			//// 4. Lastly if no custom col and no foreign key, the actual value from db will be used
			
            if($table['eval'][$col]){
				var jsCode = replaceBetweenBraces($data, $table['eval'][$col]);	
				$col_data_temp[$col] =  eval(jsCode);
			}
            else if(isset($table['custom_cols'] && $table['custom_cols'][$col])){
				if($table['custom_cols'][$col]['value']){
					$col_data_temp[$col] = replaceBetweenBraces($data, $table['custom_cols'][$col]['value']);
				}
				else{
					$col_data_temp[$col] = $data[$col];
				}
            }
            else{
                if($fKeys[$col]){ //// Replace foreign keys with _name prefix
                    $col_data_temp[$col] = $data[''+$col+'_name'];
                }
                else{
                    if($col == 'datecreated' && $data[$col] && $data[$col] != '0000-00-00 00:00:00') $col_data_temp[$col] = getReadableDate($data[$col]);
                    if($col == 'date_created' &&  $data[$col] && $data[$col] != '0000-00-00 00:00:00') $col_data_temp[$col] = getReadableDate($data[$col]);
                    else $col_data_temp[$col] = $data[$col];
                }
            }

            if(isset($table['field_functions'] && $table['field_functions'][$col])){
                $col_data_temp[$col] = call_user_func_array($table['field_functions'][$col], array($col_data_temp[$col]));
            }
        });
		//console.log($table['has_status_label']);
		if($table['has_status_label'] || isset($table['override_status_label'])){
			$col_data_temp['label'] = $label;
		}
        else{
            //$col_data_temp['label'] = $label;
        }
        

			
        return $col_data_temp;
    }

    this.createTableRow = function($data, $userData, $trclass){
		if(!$trclass)$trclass = '';
        $trid = $userData[$this.config['id']];
        if($trid != ''){
            $row_data = 
            '\
            <tr class="mt-element-ribbon '+$trclass+' '+$this.module+'-tr  tr-'+$trid+'" data-id="'+$trid+'">\
            ';		
        }
        else{
            $row_data = 
            '\
            <tr class="mt-element-ribbon '+$trclass+' '+$this.module+'-tr tr-'+$data['id']+'" data-id="'+$trid+'">\
            ';		
        }

		$.each($data,function( $key,$col) {
			var editableClass = '';
			if($.inArray($key, $this.tableConfig.editable) !== -1){
				editableClass = 'editable-text-full';
			}
			$row_data += 
			'\
			<td class="td-'+$key+' '+editableClass+' " id="td-'+$trid+'-'+$key+'" data-field="'+$key+'">'+$col+'</td>\
			';
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

    this.createTableTdOnly = function($data,$userData){
        
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

    this.generateTableOnMainThread = function(){
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
    this.generateTableOnWorkerThread = function(){
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
    this.generateTable = function(){
		if($this.on_main_thread){
			$return = $this.generateTableOnMainThread();
			return $return;
		}
		else{
			$this.generateTableOnWorkerThread();
		}
	}
	this.afterTableCreation = function(){
		$this.initDataTable();
		for (i = 0; i < $this.functions.length; i++){
			$this.functions[i]();
		}
		//console.log('done');
		//console.log($this.isFirstCall);
		$this.jEditable();
		if($this.isFirstCall)$this.initialHtml = $('#'+$this.container).html();
		$this.isFirstCall = false;
	}	
    this.generateTableCustom = function(){
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

    this.generateTableHeaders = function ($table){
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

    this.generateFilterRow = function($table){
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

    this.generateBulkUpdateRow = function(){
	   $tr = generateBulkUpdateRow($this,$form);
        return $tr;
    }
	
	this.construct(options);
	} //// END CLASS

	