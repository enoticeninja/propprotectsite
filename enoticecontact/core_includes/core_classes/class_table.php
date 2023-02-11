<?php
class Table
{
public $hasNewRow = false;	
public $hasSaveChanges = false;	
public $pagination = false;	
public $paginationCode = '';	
public $headers = '';	
public $table = '';	
public $tableTitle = '';	
public $tbodyId  = 'table-tbody';	//Id for ajax usage, for appending new rows
public $tableActionsWrapper = '';	
public $filterRow = '';	
public $filterFields = '';	
public $filterClass = '';	
public $filterId = '';	
public $tableActionFields = '';	
public $headerIcons = '';
public $documentReady = '';
public $rowButtons = '';
public $portletClass = 'box yellow';
public $totalRowsInDb = ''; // Total Rows in database
public $rowsSelect = ''; // Select list for rows to display
public $rowsCountDisplay = 10; // Number of Rows to display
public $bulkUpdateRow = ''; 
public $returnTableAs = ''; 
public $tableContainerClass = ''; 
public $tableContainerId = ''; 
public $jsFunction = ""; 
public $total_cols = ""; 
public $actions = array(); 

function getTable(){
	global $documentReady;

	$formActions = '';
	$pagination = '';

		if(!$this->pagination) {
			$this->paginationCode = '';
		}
		if ($this->hasSaveChanges) {
			$formActions .= 
			'
			<button type="submit" class="btn btn-primary" onclick="Save()">Save changes</button>
			';
		}

		if ($this->hasNewRow) {
			$formActions .= 
			'
			<button type="button" onclick = "NewRow()" class="btn btn-success">New Row</button>
			';
		}
		
		if($this->pagination) {
			$this->getPagination($this->rowsCountDisplay, $this->totalRowsInDb,1);	
			$this->getRowCount($this->rowsCountDisplay, $this->totalRowsInDb, $this->rowsCountDisplay);	
		}

        $bulk_update_row = '';
		if($this->bulkUpdateRow != ''){
            $this->jsFunction .= "$('#bulk-update-row').draggable({handle:'.modal-header'});";
            $bulk_update_row = 
            '
            <div class="modal draggable-modal animated zoomIn" data-in-animation="zoomIn" data-out-animation="zoomOut" id="bulk-update-row" tabindex="1" data-backdrop="static" role="basic" aria-hidden="true" style="max-width:630px;margin:0 auto;">
                <div class="modal-dialog" style="top: 50px;">
                    <div class="modal-content" style="box-shadow: 0px 0px 10px 10px rgba(104, 182, 212, 0.35), -1px -12px 6px 0px rgba(131, 212, 226, 0.23);">
                        <div class="modal-header">
                            <button type="button" class="close"  onClick="UncheckMaster()"></button>
                            <h4 class="modal-title text-center bg-color-primary-custom"><i class="fa fa-clone"></i> Bulk Update</h4>
                            
                            <h5 class="font-red text-center"><i class="fa fa-warning"></i> Please be CAREFUL !! Changes Made will be applied to all selected items</h5>
                        </div>
                        <div class="modal-body row"> 
                            '.$this->getBulkUpdateRow().'
                        </div>

                    </div>
                </div>
            </div>            
           
            ';
        }
		////////////////// TABLE DATA ///////////////////////////////
		$tableData = 
		'
        <div class="table-container container-fluid '.$this->tableContainerClass.' row" id="'.$this->tableContainerId.'">	
		<div class="row hor-ver-center">
        '.$this->getTableActionsWrapper().'	
        <div id="table-pagination" class="row col-md-9 col-sm-12 col-xs-12">					
            '.$this->paginationCode.'
        </div>
        </div>
        
        <form id="table-form-wrapper" onSubmit="return false;">     
        '.$bulk_update_row.'                    
        <table class="table table-striped table-bordered table-hover flip-content" id="auto-table" >
		';
		

			$tableData .= $this->getHead(); 
            if($this->table == '') $this->table = '<tr><td colspan="25"><h3 class="text-center"> No data Found</h3></td></tr>';
			 $tableData .= 
			 '
							<tbody id="'.$this->tbodyId.'">
							'.$this->table.'
							</tbody>	
						</table>                       
					</form> 

			 ';
			 
			  $tableData .= 
			 '
			<div id="" class="row ">                         
				<div id="table-pagination-bottom" class="col-md-9 col-sm-12 col-md-offset-3 col-xs-offset-3 text-right">
				
					'.$this->paginationCode.'
				</div>
			</div>

			 ';

             $actionsString = implode(' ',$this->actions);
			 $return = 
			 '
					<div class="row table-container">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<!-- BEGIN BORDERED TABLE PORTLET-->
                                <div class="portlet light bordered">
                                    <div class="portlet-title tabbable-line">
                                        <div class="caption">
                                            <i class=" icon-grid font-green"></i>
                                            <span class="caption-subject font-green bold uppercase">'.$this->tableTitle.'</span>
                                        </div>
                                        <div class="actions">
                                            '.$actionsString.'
                                        </div>                                        
                                    </div>
                                    <div class="portlet-body">
                                    '.$tableData.'
                                    </div>
                                </div>     
						</div>
					</div> 
			 ';
             
             if($this->returnTableAs == 'timbox'){
                $return = 
                '
					<div class="row table-container">
                        <div class="col-md-12 col-sm-12 col-xs-12">
						<!-- BEGIN BORDERED TABLE PORTLET-->
                        <div class="card ">

                            <div class="card-header card-header-success card-header-icon">
                                <div class="card-icon">
                                    <i class="material-icons">dashboard</i>
                                </div>
                                <h4 class="card-title">'.$this->tableTitle.'</h4>
                                
                                <span class="actions pull-right" style="position:absolute;top:-20px;right:10px;">
                                    '.$this->actions.'
                                </span>
                            </div>

                            <div class="card-body animated fadeIn">

                                <div class="">
                                 '.$tableData.'
                                </div>
                            </div>
                        </div>  
						</div>
					</div>                 
  
                ';                
             }
			 
             $return2['table'] = $return;
             $return2['jsFunction'] = $this->jsFunction;
             return $return2;
}

	public function getPagination($rowsCountDisplay, $totalRowsInDb, $activeNum) {
			/// $activeNum is the pagination Number

            $tempLengthOfPagination = floor($totalRowsInDb/$rowsCountDisplay) + 1; // {next lowest integer value} total buttons in pagination				
			$activeNumToDisplayFrom = $activeNum;   /// Number to display from in SHOWING label
			$activeNumToDisplayTo = $rowsCountDisplay;  //// Number to display to in SHOWING label
			$pagination = '';			
			if($activeNum != 1) { //IF active pagination button is not the first button {check to display the showing message}
				$activeNumToDisplayFrom = ($activeNum-1)*$rowsCountDisplay +1;
				$activeNumToDisplayTo = ($activeNumToDisplayFrom + $rowsCountDisplay -1);
				if($activeNum == $tempLengthOfPagination) {
					$activeNumToDisplayTo = ($totalRowsInDb);				
				}
			}
			else if($activeNum == 1 && $tempLengthOfPagination == 1){   /// If active pagination button is first button  and only one pagination box is there
				$activeNumToDisplayTo = ($totalRowsInDb);	// Showing 1 to {total rows in db}			
			}
			$showing = 
			'
            <div class="col-md-5 col-sm-12 col-xs-12" style="padding-bottom:20px">
                <div class="mt-element-ribbon bg-grey-steel">
                    <div class="ribbon ribbon-border ribbon-shadow bg-color-primary-custom uppercase dataTables_info" id="sample_1_info" role="status" >Showing '.$activeNumToDisplayFrom.' to '.($activeNumToDisplayTo).' of '.$totalRowsInDb.' entries</div>
                </div> 
            </div>			
			';
			$pageArrowsStatusLeft = '';
			$leftFuncOnClickSingle = 'SearchTable('.($activeNum - 1) .')';	/// JS function for the left most SINGLE arrow button			
			$leftFuncOnClickDouble = 'SearchTable(1)';		/// JS function for the left most DOUBLE arrow button		
			if($activeNum == 1){ //if first page is active disable the previous arrows
				$pageArrowsStatusLeft = 'disabled';	
				$leftFuncOnClickSingle = '';				
				$leftFuncOnClickDouble = '';						
			}
			$pagination .= 
			'
            <a href="#" class="btn btn-sm bg-color-primary-custom prev '.$pageArrowsStatusLeft.'" onclick="'.$leftFuncOnClickDouble.'"><i class="fa fa-angle-double-left"></i></a>
            <a href="#" class="btn btn-sm bg-color-primary-custom prev '.$pageArrowsStatusLeft.'" onclick="'.$leftFuncOnClickSingle.'"><i class="fa fa-angle-left"></i></a>	
			';				
				
		

		$pagination .= 
		'
		<select class="pagination-panel-input form-control input-sm input-inline not-md" style="margin: 0 5px;width:60px;display:inline" onChange="SearchTable(this.value)">
		';	
		for($i=1;$i<$tempLengthOfPagination;$i++ ) { //loop to get the page numbers and display
			$tempActive = '';
			if($i == $activeNum) $tempActive = 'selected';
			$pagination .= 
			'
				<option value="'.$i.'" '.$tempActive.'>'.$i.'</option>					
			
			';						
		}

		if($totalRowsInDb%$rowsCountDisplay != 0 ){ ///Check to display the remainder of rows whose count is less than 10	
			$tempActive = '';
			if($tempLengthOfPagination == $activeNum) $tempActive = 'selected';
			$pagination .= 
			'				
				<option value="'.$i.'" '.$tempActive.'>'.$i.'</option>					
			';						
				
		}


			$pageArrowsStatusRight = '';
				$rightFuncOnClickSingle = 'SearchTable(\''.($activeNum+1).'\')';				
				$rightFuncOnClickDouble = 'SearchTable(\''.($tempLengthOfPagination).'\')';	
			if($activeNum == $tempLengthOfPagination || $tempLengthOfPagination == 1 || $activeNum*$tempLengthOfPagination == $totalRowsInDb){ //check to see when right arrows will be disabled
				$pageArrowsStatusRight = 'disabled';
				$rightFuncOnClickSingle = '';				
				$rightFuncOnClickDouble = '';				
			} 
			
			$pagination .= 
			'
					</select>
					<a href="#" class="btn btn-sm bg-color-primary-custom next '.$pageArrowsStatusRight.'" onclick="'.$rightFuncOnClickSingle.'"><i class="fa fa-angle-right"></i></a> 
					<a href="#" class="btn btn-sm bg-color-primary-custom next '.$pageArrowsStatusRight.'" onclick="'.$rightFuncOnClickDouble.'"><i class="fa fa-angle-double-right"></i></a>
			';		
			$pagination1 = 
			'
            '.$showing.'
            <div class="col-md-7 col-sm-12 col-xs-12" style="padding-bottom:20px">
                <div class="pagination-panel pull-right" id="sample_1_paginate">

                        '.$pagination.'

                </div>
            </div>				
			
			';
		$this->paginationCode = $pagination1;		
	}

	public function getRowCount($rowsCountDisplay, $totalRowsInDb, $curSelected) {

			$tempLengthOfPagination = floor($totalRowsInDb/$this->rowsCountDisplay) + 1; // next lowest integer value			
			$this->rowsSelect .=
				'
                <label>  
                <select name="" id="rows_to_display" onchange="SearchTable()" class="bs-select form-control" data-style="btn-success" >
				';
		$tempRow = $this->rowsCountDisplay;
		for($i=1;$i<$tempLengthOfPagination;$i++) {
			$tempSelected = '';
			if($i*$tempRow == $curSelected) $tempSelected = 'selected';			
			$this->rowsSelect .=
					'				
                    <option value="'.$i*$tempRow .'" '.$tempSelected.'>'.$i*$tempRow .'</option>

					';					
		}
		if($totalRowsInDb%$this->rowsCountDisplay != 0 ){ ///Check to display the remainder of rows whose count is less than 10
			$tempSelected = '';
			if($totalRowsInDb == $curSelected) $tempSelected = 'selected';		
			$this->rowsSelect .=
					'				
                <option value="'.$totalRowsInDb.'"  '.$tempSelected.'>'.$totalRowsInDb.'</option>

					';					
				
		}		
		$this->rowsSelect .=
				'
                </select> 
                records/page</label>
				';			
	}
		
	public function getTableActionsWrapper(){
		$tableActionsWrapper = '';		
		$dataTemp = '';		
		$formActions = '';		
		if ($this->tableActionFields != '') {
			foreach($this->tableActionFields as $value){
					$dataTemp .= $value;
			}
		}			
		if ($this->hasSaveChanges) {
			$formActions .= 
			'
			<button type="submit" class="btn btn-primary" onclick="Save()">Save changes</button>
			';
		}

		if ($this->hasNewRow) {
			$formActions .= 
			'
			<button type="button" onclick = "NewRow()" class="btn btn-success">New Row</button>
			';
		}			
			$tableActionsWrapper = 
			'
            <div id="rows_to_display_container" class="col-md-3 col-sm-6 col-xs-6">						
                '.$this->rowsSelect.'
            </div>

											
			';

		return $tableActionsWrapper;	
	}
	
	public function getHead()	{
		$tableHead = 
		'				<thead class="flip-content">
							<tr role="row" class="heading">
		';
		

		$tableHead .= $this->getHeaders(); 
		$tableHead .= '</tr>'; 

		$tableHead .= $this->filterHeader(); 
		
		$tableHead .= '</thead>'; 
		return $tableHead;		
	}
	
	public function filterHeader(){
		$filterColumns = '';
		if($this->filterFields != ''){

			foreach($this->filterFields as $v) {
				
				$filterColumns .= '<td rowspan="1" colspan="1">
							'.$v.'
							</td>';
			
			}
		}
		$this->filterRow = 
		'
										<tr role="row" class="filter '.$this->filterClass.'" id="'.$this->filterId.'">
											<form method="POST" action="return false;" id="tablefilterform">
												'.$filterColumns.'
											</form>
										</tr>
										
		';	
	return $this->filterRow;	
	}

	public function getHeaders() {
		$tableData = '';
		if($this->headers != ''){
			$i = 0;
			$icon = '';
			foreach($this->headers as $title) {
				if($this->headerIcons != '') $icon = '<i class="fa fa-'.$this->headerIcons[$i].'"></i>';		
				$tableData .= '
										<th class="sorting">'.$icon.' '.$title.'</th>
							';
				$i++;			
			 } 
		}
		return $tableData;		
	}
	
    public function getBulkUpdateRow(){
       return $this->bulkUpdateRow; 
    }	
	///// $data will be an array of multiple rows of data
	//// $index will be an array of indeses of $data
	//// Save the data in $this->table and also returns it 
	public function tableDataFromArray($index, $data){  

		$return = '';

		if(is_array($index)) {
				$count = 0;
			foreach($data as $tr) {
				$tempData = '';
				foreach($index as $td){
					$tempData .= 
					'
					<td>
					'.$tr[$td].'
					</td>
					';

				}
				$count++;

			if(is_array($this->rowButtons)) {

				foreach($this->rowButtons as $v) {			
					$tempData .= 
					'
					<td>
					'.$v.'
					</td>
					';			
			
				}
			
			}	

			$return .= 
			'
			<tr>
			'.$tempData.'
			</tr>
			';				
			}				
			



		}
		
		else {
			$return = 'No Data Found';
		}
		$this->table = $return;
		return $return;
	}	
	

} //// END CLASS
?>