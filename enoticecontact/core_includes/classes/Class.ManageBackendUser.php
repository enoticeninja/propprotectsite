<?php
/**
 * Flexible Access - The main class
 * 
 * @param string $dbName
 * @param string $dbHost 
 * @param string $dbUser
 * @param string $dbPass
 * @param string $dbTable
 */

class ManageBackendUser extends UserCore
{
    /*Do not edit after this line*/
    public $dbTable = 'backend_users';
    public $common_title = 'Users';
    public $tbFields = array(
            'userID'=> 'id', 
            'login' => array('username','email','phone'),
            'pass'  => 'password',
            'email' => 'email',
            'user_level' => 'userlevel',
            'active'=> 'activated'
            );

    public $config = array(
          'id'=>'id',
          'name'=>'firstname'		
      );
      
    public $password_type = 'password_hash';
    public $has_user_levels = true;

		///// FORM FIELDS
	public $common_form_fields = array(
		'fields' => array('firstname','lastname','email','phone','username','password','password2','userlevel','status'),
		'required' => array('firstname','lastname','email','phone','username','password','password2','userlevel'),
		'validation' => array(
							'email'=>array('type'=>'email'),
							'username'=>array('type'=>'alphanumeric'),
							'password'=>array('type'=>'all','min_len'=>6,'message'=>'Password should be atleast 6 characters'),
							'password2'=>array('type'=>'all','min_len'=>6,'message'=>'Password should be atleast 6 characters'),
						),
		'duplicate' => array('username','email','phone'),
	);
	
	public function __construct($dbConn = '') {
		if($dbConn != ''){
			$this->dbConn = $dbConn;
		}
		$this->module = $this->dbTable;
		$this->session = get_session_variables();  
		$this->sensitiveFields = array('id','password');
		
		//$this->selectList = $this->getSelectOptions("SELECT users.id,CONCAT(users.firstname,' ',users.lastname,'-',users.email) as name FROM $this->dbTable  as users");
        $this->setFunctionMappings();
	}
	
    function getTableFields($data){
        if(isset($data['pull_data']) && $data['pull_data']){
            $sql = $this->generateSqlPullData();
            $this->table['module'] = $this->module;
            $this->table['rowsCountDisplay'] = $tempRowsNum = $this->rowsCountDisplay;
            $this->table['sql'] = $sql;
            $sqlNum = "$sql
            ORDER BY main.{$this->config['id']} DESC
            LIMIT $tempRowsNum		
            ";
            $this->table['foreignKeys'] = $this->getForeignKeys();
            $this->table['tableData'] = $this->getDataForTable($sqlNum);
            $this->table['totalRowsInDb'] = $this->numRows($this->table['sql']);
            $this->table['labels'] = $this->getLabels();
            $this->table['config'] = $this->config;
        }
        $this->table['common_title'] = $this->common_title;
        $this->table['cols'] = array('id','username','firstname','lastname','email','phone','userlevel','date_created');
        $this->table['search_fields'] = array('id','username','firstname','lastname','email','phone','date_created');
        $this->table['typeahead'] = true;
        $this->table['search'] = true;
        $this->table['has_status_label'] = true;
        $this->table['has_checkbox'] = true;
        $this->table['has_tr_buttons'] = true;
        $this->table['hasDataTable'] = true;
		
		$user_id = get_user_id();
		$newJsFunc1 = 
		"
		let data = {};
		data['data'] = {'action':'edit_password_single','module':'{$this->module}','id':'__ID__'};
		data['isAjax'] = true;
		data['e'] = this;
		new FormGenerator(data);
		";			
        $this->table['tr_buttons']['reset-password'] = '<a href="javascript:;" class="btn bg-less-glossy-warning ladda-button"  onClick="'.($newJsFunc1).'">Reset Password</a>';		
		$newJsFunc2 = 
		"
		let data = {};
		data['data'] = {'action':'edit_password_multiple','module':'{$this->module}'};
		data['isAjax'] = true;
		data['e'] = this;
		new FormGenerator(data);
		";		
        $this->table['portlet_actions']['edit_password_multiple'] =   
        '
        <a href="javascript:;" class="btn bg-less-glossy-warning ladda-button"  data-style="zoom-out" data-size="l"  onClick="'.($newJsFunc2).'">Reset Password</a>
        ';		
        return $this->table;
    }

    function getReportFields(){
        $sql = 
        "
        SELECT u.*,
        SUM(CASE WHEN bin.status LIKE '0' OR bin.status LIKE '10' THEN 1 ELSE 0 END) AS open,
        SUM(CASE WHEN bin.status LIKE '1' THEN 1 ELSE 0 END) AS closed,
        SUM(CASE WHEN bin.transferred_to <> '0' THEN 1 ELSE 0 END) AS transferred,
        SUM(CASE WHEN bin.status LIKE '10' THEN 1 ELSE 0 END) AS created
        FROM users as u
        INNER JOIN bin as bin on bin.user_id=u.id
        GROUP BY u.id
        ";
        $this->table['module'] = $this->module;
		$this->table['rowsCountDisplay'] = $tempRowsNum = $this->rowsCountDisplay;
        $this->table['sql'] = $sql;
        $sqlNum = "$sql";
/*         $sqlNum = "$sql
        ORDER BY main.{$this->config['id']} DESC
        LIMIT $tempRowsNum		
		"; */
		$this->table['foreignKeys'] = $this->getForeignKeys();
        $this->table['tableData'] = $this->getDataForTable($sqlNum);
        $this->table['totalRowsInDb'] = $this->numRows($this->table['sql']);
        $this->table['labels'] = $this->getLabels();
        $this->table['config'] = $this->config;
        $this->table['common_title'] = $this->common_title;
        $this->table['cols'] = array('id','username','firstname','lastname','email','phone','date_created','created','open','closed','transferred');
        $this->table['search_fields'] = array('id','username','firstname','lastname','email','phone','date_created');
        $this->table['custom_cols']['open']['heading'] = 'Cases Handled';
        $this->table['custom_cols']['closed']['heading'] = 'Closed Cases';
        $this->table['custom_cols']['transferred']['heading'] = 'Transferred Cases';
        $this->table['custom_cols']['created']['heading'] = 'Created Cases';
        $this->table['typeahead'] = true;
        $this->table['search'] = true;
        $this->table['has_status_label'] = true;
        $this->table['has_checkbox'] = true;
        $this->table['has_tr_buttons'] = true;
        $this->table['hasDataTable'] = true;
        $this->table['report_form'] = $this->getReportingFormFields();
		
		$user_id = get_user_id();
		$newJsFunc1 = 
		"
		let data = {};
		data['data'] = {'action':'edit_password_single','module':'{$this->module}','id':'{$user_id}'};
		data['isAjax'] = true;
		data['e'] = this;
		new FormGenerator(data);
		";			
        $this->table['tr_buttons']['reset-password'] = '<a href="javascript:;" class="btn bg-less-glossy-warning ladda-button"  onClick="'.($newJsFunc1).'">Reset Password</a>';		
		$newJsFunc2 = 
		"
		let data = {};
		data['data'] = {'action':'edit_password_multiple','module':'{$this->module}'};
		data['isAjax'] = true;
		data['e'] = this;
		new FormGenerator(data);
		";		
        $this->table['portlet_actions']['edit_password_multiple'] =   
        '
        <a href="javascript:;" class="btn bg-less-glossy-warning ladda-button"  data-style="zoom-out" data-size="l"  onClick="'.($newJsFunc2).'">Reset Password</a>
        ';		
        return $this->table;
    }

    function getReportingFormFields(){
        $return['all_fields']['fields'] = array(
                'id' => array(
                    'type' => 'input',
                    'width' => '4',
                    'label' => 'User Id',
                ),
                'username' => array(
                    'type' => 'input',
                    'width' => '4',
                    'label' => 'User Name',
                ),
                'firstname' => array(
                    'type' => 'input',
                    'width' => '4',
                    'label' => 'First Name',
                ),
                'lastname' => array(
                    'type' => 'input',
                    'width' => '4',
                    'label' => 'Last Name',
                ),
                'date_range' => array(
                    'type' => 'daterangepicker',
                    'width' => '4',
                    'start_date' => 'start_date',
                    'end_date' => 'end_date',
                    'label' => 'Date Range',
                )
            );

        $unique_id = uniqid();
        $unique_id .= rand(10, 100);
        $return['parent'] = $parent = $unique_id;
        $return['module'] = $module = $this->dbTable;
        $return['return_form_as'] = 'rows_with_buttons';
        $return['name_prefix'] = 'data';
        $return['unique_id'] = $unique_id;
        $return['fields'] = array('id','username','firstname','lastname','date_range');
        return $return;
    }

    function searchReport($data) {
        print_r($data['data']);
        $sql = 
        "
        SELECT u.* 
        FROM users as u
        INNER JOIN bin as bin on bin.user_id=u.id
        INNER JOIN cases as cases on bin.case.id=cases.id
        INNER JOIN customer as cu on cu.id as bin.cusomer_id
        ";
    }

    public function afterInsert($data, $unique_id, $insert_id)
    {
        if (DEV_MODE) {
            $fake_num = file_get_contents(DIR_CORE_INCLUDES . 'countnames.txt');
            //print_r($fake_num);
            //print_r($fake_num);
            file_put_contents(DIR_CORE_INCLUDES . 'countnames.txt', $fake_num + 1);
        }
        return array();
    }
    
    function extendAjax($data){
        if(isset($data['type'])){
            $this->errorAjax($data);
        }

        if(isset($data['action'])){
            $this->errorAjax($data);
        }
        else{
            $this->errorAjax($data);
        }
    }
}
?>
