<?php
Class Portlet {
    public $actions = '';
    public $title = '';						
    public $portletClass ='light';
    public $titleClass = '';
    public $captionClass = '';
    public $iconClass = '';
    public $spanClass = 'caption-subject bold uppercase';
    public $formClass = '';

    public $code = '';
    public $portletContent = '';
    public $hiddenElements = '';
    public $portletBodyClass = '';
    public $portletBodyId = '';
    public $formBodyClass = '';
    public $formAction = '';
    public $formId = 'default-form';
    public $portletId = 'default-portlet';
    public $buttonsPosition = 'text-center';
    public $tableClass = '';
    public $theadClass = '';
    public $thClass = '';
    public $trClass = '';
    public $tdClass = '';
    public $tabsContent = array();

	public function __construct($parameters = '') {
		if($parameters != '') {
		$param = explode(',', $parameters);
		$this->portletClass = $param[0];
		$this->iconClass = $param[1];
		}
	}
	
	public function GetPortletForm($title, $data) {
		$temp = '';
		$onSubmit = '';
		if($this->formAction == '') $onSubmit = 'onsubmit="return false"'; 
		if($title != '') {
			$temp =
			'
							<div class="portlet-title '.$this->titleClass.'">
								<div class="caption '.$this->captionClass.'">
									<i class="'.$this->iconClass.'"></i>
									<span class="'.$this->spanClass.'"> '.$title.'</span>
								</div>
							'.$this->actions.'
							</div>			
			';
			
		}		
		$return = 
		'
		<div class="portlet '.$this->portletClass.'" id="'.$this->portletId.'">
			'.$temp.'
			<div class="portlet-body '.$this->portletBodyClass.'" id="'.$this->portletBodyId.'">
				<form method="post"  '.$onSubmit.' action="'.$this->formAction.'" role="form" id="'.$this->formId.'" class="">
					<div class="form-body '.$this->formBodyClass.'">
					<div class="row">
					'.$this->hiddenElements.'
					';
		if(is_array($data['fields'])) {   ///if data is array run a foreach loop								
			foreach($data['fields'] as $field) {
				$return .= $field;
			}
		}
		else{ //// if data is not array return the data itself
			$return .= $data['fields'];
		}
		$return .=
		'
			</div>
			</div>
		<span id="'.$this->formId.'-status-message"></span>	
		<div class="form-actions '.$this->buttonsPosition.' noborder">
		';
		if(is_array($data['buttons'])) {
			foreach($data['buttons'] as $button) {
				$return .= $button;
			}
		}
		else{ //// if data is not array return the data itself
			$return .= $data['buttons'];
		}
		$return .= '							
									</div>
									</form>
								</div>
							</div>
		';

		return $return;
	}


	public function GetPortlet($title, $data) {
		$temp = '';
		if($title != '') {
			$tempIcon = '';
			$tempActions = '';
			$tempCaption = '';
			
			if($this->iconClass != ''){
				$tempIcon = 
				'
					<i class="'.$this->iconClass.'"></i>				
				';				
			}
	
			if($this->actions != ''){
				$tempActions = 
				'
								<div class="actions">
									'.$this->actions.'
								</div>					
				';				
			}
			
			$tempCaption = 
			'
								<div class="caption '.$this->captionClass.'">
									'.$tempIcon.'
									<span class="'.$this->spanClass.'"> '.$title.'</span>
								</div>			
			';			
			$temp =
			'
							<div class="portlet-title '.$this->titleClass.'">
								'.$tempCaption.'
								'.$tempActions.'
							</div>			
			';
			
		}
		$tempData = '';
		if(isset($data['fields'])) {   ///if data is array run a foreach loop								
			foreach($data['fields'] as $field) {
				$tempData .= $field;
			}
			$data = $tempData;
		}		
	$return = 
	'
						<div class="portlet '.$this->portletClass.'" id="'.$this->portletId.'">
							'.$temp.'
							<div class="portlet-body '.$this->portletBodyClass.'" id="'.$this->portletBodyId.'">

							'.$data.'	

							'.$this->portletContent.'	

							</div>							
						</div>
	';
	$this->portletContent = '';
	return $return;
	}

	public function tabs($tabs){
		
		$return = 
		'
								<div class="tab-content ajax-return-tab-content-container">
								'.$this->tabContentOnly($tabs).'	
								</div>		
		';
	return $return;	
		

	}

	public function tabContentOnly($tabs){
	$temp = '';
	$count = 0;
	if (is_array($tabs)){
		foreach($tabs as $value) {	
			$isActive = '';
			if($count == 0) $isActive = 'active';		
			$isModal = '';
			$temp .= 
				'		
								<div class="tab-pane '.$isActive.'" id="'.$value['id'].'">
									'.$value['content'].'	
								</div>
				';
			$count++;	
		} 		
	
	}
	$return = 
	'
							<div class="tab-content ajax-return-tab-content-container">
							'.$temp.'	
							</div>		
	';
	return $temp;	
	

}

	public function navTabs($data, $tabIdPrefix='tab_') {

		$list = '';
		if (is_array($data)){
			$i = 0;
			foreach($data as $value) {	
				$isActive = '';
				if($i == 0) $isActive = 'active';	
				$list .= 
					'		
										<li class="'.$isActive.'">
											<a href="#'.$tabIdPrefix.'_'.$i.'" data-toggle="tab">
											'.$value.' </a>
										</li>
					';
				$i++;	
			} 		
		
		}		
		$return = 
		'
								<ul class="nav nav-tabs ">
								'.$list.'
								</ul>
		';	
			return $return;
	}
	
	public function customTabs($navData, $tabData, $tabIdPrefix){
		$return = 
		'
				<div class="tabbable-custom">	
					'.$this->navTabs($navData, $tabIdPrefix).'
					'.$this->tabs($tabData).'
				</div>				
		';
		return $return;
	}
	
	public function table($data){

		$i = 0;
		$table = '';
		$emptyTableMessage = '';
		$thead = '';
		if(!isset($data[1])) $emptyTableMessage = 'No Data Found';
		foreach($data as $value){
			if($i != 0) {
				$table .=
				'
				<tr class="'.$this->trClass.'">
				';
				foreach($value as $td){
					$table .= 
					'
					<td class="'.$this->tdClass.'">
					'.$td.'
					</td>
					';
				}
				$table .=
				'
				</tr>
				';
				}				

			else {
				$thead .=
				'
				<thead class="'.$this->theadClass.'">
				<tr class="'.$this->trClass.'">
				';
				foreach($value as $td){
					$thead .= 
					'
					<th class="'.$this->thClass.'">
					'.$td.'
					</th>
					';
				}
				$thead .=
				'
				</thead>
				</tr>

				';
				}
		$i++;	
		}
		
		$return = 
		'
		<table class="'.$this->tableClass.'">
		'.$thead.'
		<tbody>
		'.$table.'
		'.$emptyTableMessage.'		
		</tbody>
		</table>
		';

		return $return;
	}

	
	public function infoDisplayData($data){
		$return = '';

		foreach($data as $k=>$v){
			$return .= 
			'
				<div class="row static-info">
					<div class="col-md-5 name">
						 '.$k.':
					</div>
					<div class="col-md-7 value">
						 '.$v.' 
					</div>
				</div>			
			';
		}
		return $return;
	}


	function actions($actions){
        $this->actions = $actions;
    }

}
?>