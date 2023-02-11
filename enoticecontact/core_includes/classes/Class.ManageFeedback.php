<?php
class ManageFeedback extends Ajax
{
	/*Do not edit after this line*/
	public $dbTable = 'tbl_feedback';
	public $common_title = 'Feedback';
	public $userData = array();
	public $userID = '';
	public $session = [];
	public $config = array(
		'id' => 'feedback_id',
		'name' => 'feedback_id'
	);
	public $form_configuration = array();
	public $module = 'feedback';

	public function __construct($dbConn = '')
	{
		if ($dbConn != '') {
			$this->dbConn = $dbConn;
		}
		$this->module = 'feedback';
		$this->setFunctionMappings();
	}

	function setFunctionMappings()
	{
		$this->functionMappings['new'] = 'getNewForm';
		$this->functionMappings['new_ajax'] = 'getNewAjaxForm';
		$this->functionMappings['edit'] = 'getEditForm';
		$this->actionMappings = array_flip($this->functionMappings);
		return $this->functionMappings;
	}

	function getTableFields($data)
	{
		if (isset($data['pull_data']) && $data['pull_data']) {
			$sql = $this->generateSqlPullData();
			$this->table['module'] = $this->module;
			$this->table['rowsCountDisplay'] = $tempRowsNum = $this->rowsCountDisplay;
			$this->table['sql'] = $sql;
			$sqlNum = "$sql
            ORDER BY main.{$this->config['id']} DESC
            LIMIT $tempRowsNum		
            ";
			$this->table['foreignKeys'] = $this->getForeignKeys();
			$response = $this->callApi(array('action' => 'get-data','module' => $this->module,'rows' => $this->rowsCountDisplay), 'feedbacks.php');
			//print_r($response);
			$response['tableData'] = array_reverse($response['tableData']);
			$this->table['tableData'] = $response['tableData'];
			$this->table['totalRowsInDb'] = $response['totalRowsInDb'];
			$this->table['labels'] = $this->getLabels();
			$this->table['config'] = $this->config;
		}
		$this->table['common_title'] = $this->common_title;
		$this->table['cols'] = array('feedback_id','date', 'user_name', 'email', 'feedback','notes');
		$this->table['search_fields'] = array('user_name', 'email', 'feedback','notes');
		$this->table['typeahead'] = true;
		$this->table['search'] = true;
		$this->table['has_status_label'] = false;
		$this->table['has_checkbox'] = false;
		$this->table['has_tr_buttons'] = true;
		//$this->table['tr_buttons']['edit'] = '';
		$this->table['tr_buttons']['delete'] = '';

		/*$newJsFunc2 = 
            "
            let data = {};
            data['data'] = {'action':'edit_password_multiple','edit_password_multiple':'new_ajax','module':'{$this->module}'};
            data['isAjax'] = true;
            data['e'] = this;
            new FormGenerator(data);
            ";		
            $this->table['portlet_actions']['edit_password_multiple'] =   
            '
            <a href="javascript:;" type="button" class="btn btn-outline red-flamingo ladda-button"  data-style="zoom-out" data-size="l"  onClick="'.($newJsFunc2).'">Reset Password</a>
            ';

            $this->table['bulk_update_fields']['status']['field_type'] = 'select';
            $this->table['bulk_update_fields']['status']['options'] = get_numeric_status_list();	
		
		*/

		return $this->table;
	}

	function getNewForm($data)
	{
		$unique_id = uniqid();
		$unique_id .= rand(10, 100);
		$return['form_type'] = $this->actionMappings[__FUNCTION__];
		$return['unique_id'] = $unique_id;
		$return['request_data'] = $data;
		$return['override_function'] = array(
			'enabled' => false,
			'function' => ''
		);
		$return['fetch_data'] = array('enabled' => false);
		$return['portlet_title'] = 'Add New';
		$return['portlet_icon'] = '';
		//$return['buttons']['array']['save']['actions']['action'] = 'some_custom_action';
		$return['submit_id'] = 'submit_id';
		$return['call_type'] = isset($data['call_type']) ? $data['call_type'] : 'ajax';
		$return['return_form_as'] = isset($data['return_form_as']) ? $data['return_form_as'] : 'modal';
		$return['parent'] = $parent = $unique_id;
		$return['module'] = $module = $this->module;
		$return['return_after_save'] = array();
		$return['hidden'] = array(
			'enabled' => false,
			'html' => ''
		);
		$return['on_submit'] = 'insert';
		/*$return['default_insert'] = array(
                                    'enabled'=>true,
									'fields'=>array(
										'date_created'=>date('Y-m-d H-i-s'),
										'created_by'=>get_user_id(),
										'status'=>'inactive',
										'type'=>'general',
										)
                                    ); */
		$return['on_submit_error'] = '';
		$return['name_prefix'] = 'data';
		$return['id_prefix'] = true;
		$return['fields'] = array('user_name', 'email', 'feedback', 'date');
		$return['required'] = array('user_name', 'email', 'feedback', 'date');
		$return['validation'] = array();
		$return['dependency'] = $this->dependency;
		$return['labels'] = $this->getLabels();
		$return['icons'] = $this->getIcons();
		$return['all_fields'] = $this->getAllFields();
		$return['duplicate'] =  array();
		$return['popover_error'] = false;
		$return['non_db_fields'] =  array();
		$return['jsFunction'] =  "";
		$return['jsFunctionLast'] = "";
		$return['jsModalClose'] =  "";

		return $return;
	}

	function getEditForm($data)
	{
		$tempData = $data;
		$return = $this->getNewForm($tempData);
		$return['form_type'] = $this->actionMappings[__FUNCTION__];
		$return['fetch_data'] = array('enabled' => true);
		$return['on_submit'] = 'update';
		$return['portlet_title'] = 'Edit';
		$return['buttons']['array']['save']['actions']['action'] = 'update';
		$return['buttons']['array']['save']['actions']['form_type'] = 'edit';
		$return['fields'] = array('notes');
		$return['fields'][] = $this->config['id'];
		$return['ajaxresult'] = false;
		if (isset($data['id'])) {
			$dataApi['id'] = $data['id'];
			$dataApi['module'] = $this->module;
			$dataApi['action'] = 'get-one';
			$response = $this->callApi($dataApi, 'feedbacks.php');
			//print_r($response);
			if($response['status']){
				$return['has_data'] = $response['data'];
				$return['override_fields'] = array(
					'feedback_id'  => array(
						'type' => 'hidden',
						'value' => getDefault($data['id'], '')
					)
				);
			}

		}
		return $return;
	}

	function getNewAjaxForm($data)
	{
		$tempData = $data;
		$return = $this->getNewForm($tempData);
		$return['form_type'] = $this->actionMappings[__FUNCTION__];
		$return['return_form_as'] = 'modal';
		return $return;
	}

	function getAffectedTables()
	{
		$arr = array();
		return $arr;
	}

	function getLabels()
	{ {
			$arr['label'] =
				array(
					'feedback_id' => 'Feedback Id',
					'date' => 'Date',
					'user_name' => 'User Name',
					'email' => 'Email Address',
					'feedback' => 'Feedback',
					'notes' => 'Notes',
				);
		}
		return $arr['label'];
	}


	function getForeignKeys()
	{
		$arr = array(
			/*'usergroup'=>array(
                        'relationship'=>'one-to-many',
                        'table'=>'perm_groups',
                        'join'=>'LEFT JOIN',
                        'as'=>'sg',
                        'id'=>'sg.id',		
                        'name'=>'sg.name'		
                    ) */);
		return $arr;
	}

	function getIcons()
	{ {
			$arr = array();
		}
		return $arr;
	}

	function getAllFields()
	{ {
			$arr['fields'] =
				array(
					'feedback_id' =>
					array(
						'type' => 'input',
						'width' => '4',
					),
					'date' =>
					array(
						'type' => 'advanceddatetime',
						'width' => '4',
					),
					'user_name' =>
					array(
						'type' => 'input',
						'width' => '4',
					),
					'email' =>
					array(
						'type' => 'input',
						'width' => '4',
					),
					'feedback' =>
					array(
						'type' => 'textarea',
						'width' => '12',
					),
					'notes' =>
					array(
						'type' => 'textarea',
						'width' => '12',
					),
				);
		}

		return $arr;
	}

	/* function insert($data){

	} */
	function extendAjax($data)
	{
		if (isset($data['type'])) {
			$this->errorAjax($data);
		}

		if (isset($data['action'])) {
			$this->errorAjax($data);
		} else {
			$this->errorAjax($data);
		}
	}
}
