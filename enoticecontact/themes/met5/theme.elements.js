function getTableContainer(data) {
	var $return =
		'\
				<div class="row table-container">\
				<div class="col-md-12">\
					<!-- BEGIN BORDERED TABLE PORTLET-->\
							<div class="m-portlet">\
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
								<div class="m-portlet__body">\
									<div class="container mb-5" id="' + data.uniqueId + '-report-form-container">\
										<form class="container mb-5" id="' + data.uniqueId + '-report-form">\
										\
										</form>\
									</div>\
								' + data.tableData + '\
								</div>\
							</div>\
					</div>\
				</div> \
		 ';
	return $return;
}

function getBulkUpdateModal(data) {
	$headerBtnTemp =
		'\
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
    <span aria-hidden="true">×</span>\
    </button>\
    ';
	$bulk_update_row =
		'\
    <div class="modal animated slideInRight"  data-out-animation="slideOutRight" id="bulk-update-row" tabindex="1" data-backdrop="false" role="basic" aria-hidden="true" style="max-width:630px;margin:0 auto;top: 80px;">\
        <div class="modal-dialog modal-dialog-centered" style="width:100%">\
            <div class="modal-content" style="box-shadow: -13px 13px 20px 0px rgb(180, 187, 187);">\
                <div class="modal-header" >\
                <h3 class="modal-title fancy-modal-title">Bulk Update</h3>\
                    ' + $headerBtnTemp + '\
                </div>\
                <div class="modal-body row"  style="padding:20px" >\
                    <h5 class="font-red text-center"><i class="fa fa-warning"></i> Please be CAREFUL !! Changes Made will be applied to all selected items</h5>\
                ' + data.bulkUpdateRow + '\
                </div>\
            </div>\
            <!-- /.modal-content -->\
        </div>\
    </div>\
    ';
	return $bulk_update_row;
}

function getTablePagination($rowsCountDisplay, $totalRowsInDb, $activeNum, uniqueId) {
	/// $activeNum is the pagination Number
	if ($totalRowsInDb === false) $totalRowsInDb = 0;
	var $tempLengthOfPagination = Math.floor($totalRowsInDb / $rowsCountDisplay) + 1; // {next lowest integer value} total buttons in pagination
	var $activeNumToDisplayFrom = $activeNum; //// Number to display from in SHOWING label
	var $activeNumToDisplayTo = $rowsCountDisplay; //// Number to display to in SHOWING label
	var $pagination = '';
	if ($activeNum != 1) { //IF active pagination button is not the first button {check to display the showing message}
		$activeNumToDisplayFrom = (($activeNum - 1) * $rowsCountDisplay) + 1;
		$activeNumToDisplayTo = ($activeNumToDisplayFrom + $rowsCountDisplay - 1);
		if ($activeNum == $tempLengthOfPagination) {
			$activeNumToDisplayTo = ($totalRowsInDb);
		}
	} else if ($activeNum == 1 && $tempLengthOfPagination == 1) { /// If active pagination button is first button  and only one pagination box is there
		$activeNumToDisplayTo = ($totalRowsInDb); // Showing 1 to {total rows in db}			
	}
	$showing =
		'\
		<div class="col-md-5 col-sm-12 col-xs-12 text-center" style="padding-bottom:20px">\
			<div class="mt-element-ribbon bg-grey-steel">\
				<div class="ribbon ribbon-border ribbon-shadow bg-color-primary-custom uppercase dataTables_info" id="sample_1_info" role="status" >Showing ' + $activeNumToDisplayFrom + ' to ' + ($activeNumToDisplayTo) + ' of ' + $totalRowsInDb + ' entries</div>\
			</div> \
		</div>\
		';
	$pageArrowsStatusLeft = '';
	if ($activeNum == 1) { //if first page is active disable the previous arrows
		$pageArrowsStatusLeft = 'disabled';
	}
	$paginationLeft =
		'\
		<a href="javascript:;" class="btn-mtz-floating bg-color-primary-custom prev ' + $pageArrowsStatusLeft + ' ' + uniqueId + '-paginate" data-page = "1"><i class="fa fa-angle-double-left"></i></a>\
		<a href="javascript:;" class="btn-mtz-floating bg-color-primary-custom prev ' + $pageArrowsStatusLeft + ' ' + uniqueId + '-paginate" data-page = "' + ($activeNum - 1) + '"><i class="fa fa-angle-left"></i></a>	\
		';



	$paginationSelect =
		'\
		<select class="pagination-panel-input form-control input-sm input-inline not-md ' + uniqueId + '-paginate-select" style="margin: 0 5px;width:60px;display:inline" >\
		';


	for ($i = 1; $i < $tempLengthOfPagination; $i++) { //loop to get the page numbers and display
		$tempActive = '';
		if ($i == $activeNum) $tempActive = 'selected';
		$paginationSelect += '<option value="' + $i + '" ' + $tempActive + '>' + $i + '</option>';
	}

	if ($totalRowsInDb % $rowsCountDisplay != 0) { ////Check to display the remainder of rows whose count is less than 10	
		$tempActive = '';
		if ($tempLengthOfPagination == $activeNum) $tempActive = 'selected';
		$paginationSelect += '<option value="' + $i + '" ' + $tempActive + '>' + $i + '</option>';

	}


	$pageArrowsStatusRight = '';
	if ($activeNum == $tempLengthOfPagination || $tempLengthOfPagination == 1 || $activeNum * $tempLengthOfPagination == $totalRowsInDb) { //check to see when right arrows will be disabled
		$pageArrowsStatusRight = 'disabled';
	}

	$paginationSelect +=
		'\
		</select>\
		';
	$paginationRight =
		'\
		<a href="javascript:;" class="btn-mtz-floating bg-color-primary-custom next ' + $pageArrowsStatusRight + ' ' + uniqueId + '-paginate"  data-page = "' + ($activeNum + 1) + '"><i class="fa fa-angle-right"></i></a>\
		<a href="javascript:;" class="btn-mtz-floating bg-color-primary-custom next ' + $pageArrowsStatusRight + ' ' + uniqueId + '-paginate"  data-page = "' + $tempLengthOfPagination + '"><i class="fa fa-angle-double-right"></i></a>\
		';

	$pagination =
		'\
		' + $showing + '\
		<div class="col-md-7 col-sm-12 col-xs-12 text-center" style="padding-bottom:20px">\
			<div class="pagination-panel" id="sample_1_paginate">\
					' + $paginationLeft + '\
					' + $paginationSelect + '\
					' + $paginationRight + '\
			</div>\
		</div>\
		';

	return $pagination;
}

function getTableLabels(statusLabels) {
	statusLabels['active'] = '<span class="label label-success"><i class="fa fa-check"></i>  </span>';
	statusLabels['1'] = '<span class="label label-success"><i class="fa fa-check"></i>  </span>';
	statusLabels['true'] = '<span class="label label-success"><i class="fa fa-check"></i>  </span>';
	statusLabels['inactive'] = '<span class="label label-warning"><i class="fa fa-pause">  </span>';
	statusLabels['0'] = '<span class="label label-warning"><i class="fa fa-pause">  </span>';
	statusLabels['false'] = '<span class="label label-warning"><i class="fa fa-pause">  </span>';
	statusLabels['deleted'] = '<span class="label label-danger"><i class="fa fa-trash">  </span>';
	statusLabels['4'] = '<span class="label label-danger"><i class="fa fa-trash"></i>  </span>';

}

function getTableActionButtons(tableConfig) {
	$editJsFunc =
		"\
	let data = {};\
	data['data'] = {'action':'edit','type':'edit','module':'" + tableConfig.module + "','id':'__ID__','return_form_as':'modal'};\
	data['isAjax'] = true;\
	data['e'] = this;\
	new FormGenerator(data);\
	";
	tableConfig['tr_buttons']['edit'] = '<a href="javascript:;"  class="dropdown-item"  onClick="' + $editJsFunc + '"><i class="la la-edit"></i> Edit </a>';
	tableConfig['tr_buttons']['delete'] = '<a href="#"  class="btn btn-outline red" onClick="CommonFunc2Confirmation({\'action\':\'delete\',\'module\':\'' + tableConfig.module + '\',\'id\':\'__ID__\'},\'\',this)">Delete >> __NAME_FIELD__</a>';
	tableConfig['tr_buttons']['delete'] = '<a href="#"  class="dropdown-item" onClick="CommonFunc2Confirmation({\'action\':\'delete\',\'module\':\'' + tableConfig.module + '\',\'id\':\'__ID__\'},\'\',this)"><i class="la la-trash"></i>Delete </a>';

	/* tableConfig['tr_buttons']['edit'] = '<a href="' + site_path + 'Edit/' + tableConfig.module + '/__ID__"  class="dropdown-item"  ><i class="la la-edit"></i> Edit</a>'; */

	tableConfig['th_buttons'] = {
		'refresh': '<a href="#"  class="btn red" onClick="CommonFunc({\'action\':\'refresh\'})"><i class="icon-refresh" ></i>Refresh</a>'
	};
	tableConfig['portlet_actions'] = {};

	$newJsFunc =
		"\
	let data = {};\
	data['data'] = {'action':'new','type':'new_ajax','module':'" + tableConfig.module + "'};\
	data['isAjax'] = true;\
	data['e'] = this;\
	new FormGenerator(data);\
	";
	tableConfig['portlet_actions']['new_ajax'] =
		'\
	<a href="javascript:;"  class="btn blue ladda-button"  data-style="zoom-out" data-size="l" onClick="' + $newJsFunc + '"><i class="fa fa-plus"></i> Add </a>\
	';
	tableConfig['portlet_actions']['new_ajax'] =
		'\
	<a href="' + site_path + 'Create/' + tableConfig.module + '"  class="btn blue ladda-button"  data-style="zoom-out" data-size="l" ><i class="fa fa-plus"></i> Add </a>\
	';
}

function wrapTrButtons($table, $data) {
	$all_buttons = '';
	$temp_buttons = '';
	$buttons = $table['tr_buttons'];
	$.each($buttons, function ($k, $btn) {
		$btn = replaceBetweenBraces($data, $btn);
		$temp_buttons += '<li class="m-nav__item">' + $btn + '</li>';
		//$temp_buttons += $btn;
	});
	$.each($table['cond_tr_buttons'], function ($field, $btnObj) {
		$btn = isset($btnObj[$data['status']]) ? $btnObj[$data['status']] : '';
		if ($btn != '') $temp_buttons += '<li class="m-nav__item">' + $btn + '</li>';
		//if($btn != '')$temp_buttons += $btn;
	});
	/*         $all_buttons += 
		'\
		<div class="btn-group btn-group-tr" data-id="__ID__">\
			<button type="button" class="btn bg-color-primary-custom dropdown-toggle" data-toggle="dropdown" data-close-others="true" aria-expanded="false"><i class="fa fa-cogs"></i><i class="fa fa-angle-down"></i>\
			</button>\
			<ul class="dropdown-menu dropdown-menu-right hold-on-click" role="menu" style="">\
				'+$temp_buttons+'\
			</ul>\
		</div>\
		'; */
	$all_buttons +=
		'\
						<div class="dropdown">\
							<a href="javascript:;" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">\
                                <i class="la la-edit"></i>\
                            </a>\
						  	<div class="dropdown-menu dropdown-menu-right">\
						    	' + $temp_buttons + '\
						  	</div>\
						</div>\
		';
	//$all_buttons += $temp_buttons;	
	return $all_buttons;
}

function generateBulkUpdateRow($this, $form) {
	$tr = '';
	$table = $this.tableConfig;
	var has_bulk_update = false;
	if (isset($table['bulk_update_fields'])) {
		has_bulk_update = true;
		$.each($table['bulk_update_fields'], function ($field, $fieldVal) {
			$fieldVal['label'] = $this.labels[$field];
			$fieldVal['name'] = '' + $this.module + '[' + $field + ']';
			$fieldVal['value'] = '';
			$fieldVal['id'] = 'bulk-' + $field + '';
			$fieldVal['type'] = 'floating-4-6';
			if ($fieldVal['field_type'] == 'select') {
				$tr += '<div class="form-common-element-wrapper col-md-4">' + $form.selectFromArray($fieldVal) + '</div>';
			}
			if ($fieldVal['field_type'] == 'input') {
				$tr += '<div class="form-common-element-wrapper col-md-4">' + $form.inputFromArray($fieldVal) + '</div>';
			}
			if ($fieldVal['field_type'] == 'checkbox') {
				$fieldVal['value'] = '1';
				$tr += '<div class="form-common-element-wrapper col-md-4">' + $form.checkboxFromArray($fieldVal) + '</div>';
			}
			if ($fieldVal['field_type'] == 'advanceddatetime') {
				$tr += '<div class="form-common-element-wrapper col-md-4">' + $form.advancedDateTimeFromArray($fieldVal) + '</div>';
				$date_format = getDefault($fieldVal['date_format'], 'yyyy-m-d HH:ii:ss');
				$picker_position = getDefault($fieldVal['picker_position'], 'bottom-left');
				$this.functions.push(
					function () {
						$('#' + $fieldVal['id'] + '').datetimepicker({
							autoclose: !0,
							isRTL: false,
							format: $date_format,
							pickerPosition: $picker_position
						});
					});
			}
		});
		if (!isset($table['bulk_update_buttons'])) {
			$tr +=
				'\
			<div class="col-md-12 text-center">\
				<button class="btn red btn-outline" id="btnBulkCancel" onClick="UncheckMaster()" >Cancel</button>\
				<button class="btn green" id="btnBulkUpdate" onClick="BulkUpdate(this,\'' + $this.module + '\')">Update Selected</button>\
				<button class="btn red btnDelete" id="btnDelete" onClick="BulkDelete(this,\'' + $this.module + '\')">Delete Selected</button>\
			</div>\
			';
		} else {
			$tr +=
				'\
			<div class="col-md-12 text-center">\
				' + $table['bulk_update_buttons'] + '\
			</div>\
			';
		}
	} else if ($table['has_checkbox']) {
		has_bulk_update = true;
		if (!isset($table['bulk_update_buttons'])) {
			$tr +=
				'\
			<div class="col-md-12 text-center">\
				<button class="btn red btn-outline" id="btnBulkCancel" onClick="UncheckMaster()" >Cancel</button>\
				<button class="btn red btnDelete" id="btnDelete" onClick="BulkDelete(this,\'' + $this.module + '\')">Delete Selected</button>\
			</div>\
			';
		} else {
			$tr +=
				'\
			<div class="col-md-12 text-center">\
				' + $table['bulk_update_buttons'] + '\
			</div>\
			';
		}
	}

	if (has_bulk_update) {
		$this.actions['bulk-update'] =
			'\
	   <button class="btn yellow-gold hidden" id="btn-bulk-update-modal" onClick="ShowBulkUpdateRow()">Bulk Update</button>\
	   ';
		if ($this.isFirstCall) {
			$this.functions.push(function () {
				var isShowingModal = false;
				$(document).on('click', '#btn-bulk-update-modal', function () {
					ShowBulkUpdateRow();
				});
			});
		}
	}
	return $tr;
}

function getTableQuickSearch(uniqueId, searchString) {
	$search_html = '\
	<div class="portlet-input input-inline  col-md-4" style="width: 400px;">\
	<form id="' + uniqueId + '-table-simple-search-form" onSubmit="return false;">\
		<div class="input-group">\
			<div class="input-icon">\
				<i class="fa fa-search"></i>\
				<input id="' + uniqueId + '-table-simple-search" class="form-control" type="text" name="table-simple-search" placeholder="Search in Database" value="' + searchString + '">\
			</div>\
			<span class="input-group-btn">\
				<a href="javascript:;" id="' + uniqueId + '-table-simple-search-btn" class="btn blue">\
				<i class="fa fa-search"></i> Search</a>\
			</span>\
		</div>\
		</form>\
	</div>\
	';
	return $search_html;
}