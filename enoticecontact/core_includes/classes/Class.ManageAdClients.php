<?php
class ManageAdClients extends Ajax
{
    /*Do not edit after this line*/
    public $dbTable = 'ad_client';
    public $common_title = 'Ad Client';
    public $userData = array();
    public $userID = '';
    public $session = [];
    public $config = array(
        'id' => 'id',
        'name' => 'name',
    );
    public $form_configuration = array();
    public $module = '';

    public function __construct($dbConn = '')
    {
        if ($dbConn != '') {
            $this->dbConn = $dbConn;
        }
        $this->module = $this->dbTable;
        $this->setFunctionMappings();
    }

    public function setFunctionMappings()
    {
        $this->functionMappings['new'] = 'getNewForm';
        $this->functionMappings['new_ajax'] = 'getNewAjaxForm';
        $this->functionMappings['edit'] = 'getEditForm';
        $this->actionMappings = array_flip($this->functionMappings);
        return $this->functionMappings;
    }

    public function getTableFields($data)
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
            $this->table['tableData'] = $this->getDataForTable($sqlNum);
            $this->table['totalRowsInDb'] = $this->numRows($this->table['sql']);
            $this->table['labels'] = $this->getLabels();
            $this->table['config'] = $this->config;
        }
        $this->table['common_title'] = $this->common_title;
        $this->table['cols'] = array('id', 'name', 'email', 'phone', 'contact_name');
        $this->table['search_fields'] = array('id', 'name', 'email', 'phone', 'contact_name');
        $this->table['typeahead'] = true;
        $this->table['search'] = true;
        $this->table['has_status_label'] = false;
        $this->table['has_checkbox'] = false;
        $this->table['has_tr_buttons'] = true;

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

    public function getNewForm($data)
    {
        $unique_id = uniqid();
        $unique_id .= rand(10, 100);
        $return['form_type'] = $this->actionMappings[__FUNCTION__];
        $return['unique_id'] = $unique_id;
        $return['request_data'] = $data;
        $return['override_function'] = array(
            'enabled' => false,
            'function' => '',
        );
        $return['fetch_data'] = array('enabled' => false);
        $return['portlet_title'] = 'Add New';
        $return['portlet_icon'] = '';
        //$return['buttons']['array']['save']['actions']['action'] = 'some_custom_action';
        $return['submit_id'] = 'submit_id';
        $return['call_type'] = isset($data['call_type']) ? $data['call_type'] : 'ajax';
        $return['return_form_as'] = isset($data['return_form_as']) ? $data['return_form_as'] : 'modal';
        $return['parent'] = $parent = $unique_id;
        $return['module'] = $module = $this->dbTable;
        $return['return_after_save'] = array();
        $return['hidden'] = array(
            'enabled' => false,
            'html' => '',
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
        $return['fields'] = array('name', 'email', 'phone', 'contact_name', 'address');
        $return['required'] = array('name', 'email', 'phone', 'contact_name', 'address');
        $return['validation'] = array(

        );
        $return['dependency'] = $this->dependency;
        $return['labels'] = $this->getLabels();
        $return['icons'] = $this->getIcons();
        $return['all_fields'] = $this->getAllFields();
        $return['duplicate'] = array('name', 'email', 'phone');
        $return['popover_error'] = false;
        $return['non_db_fields'] = array();
        $return['jsFunction'] = "";
        $return['jsFunctionLast'] = "";
        $return['jsModalClose'] = "";

        /* {
        if(!empty($data['edit']) || !empty($data['pull_data'])){
        if(!empty($return['is_multiple_form'])){
        foreach($data['has_data'] as $k=>$tempData){
        $return['has_data'][$tempData['id']] = $this->loadAllFromDbFk(array('campaign_id'=>$tempData['id']));
        }
        }
        else{
        $return['has_data'][] = $this->loadFromDB($data['id']);
        }
        }
        if(!empty($data['has_child_form'])){
        include_once DIR_OTHER_CLASSES . 'Class.ManageCampaignBrands.php';
        $class = new ManageCampaignBrands($this->dbConn);
        $childForm = $class->getNewForm(array('has_child_form'=>true,'is_multiple_form'=>true,'has_data'=>$return['has_data'],'return_form_as'=>'rows_with_buttons'));
        $childForm['portlet_title'] = 'Brands';
        $childForm['is_multiple_form'] = true;
        $childForm['parent_ids'] = array(
        'campaign' => 'campaign_id',
        );
        $return['child_form']['campaign_brands'] = $childForm;
        }
        } */
        return $return;

    }

    public function getEditForm($data)
    {
        $tempData = $data;
        $return = $this->getNewForm($tempData);
        $return['form_type'] = $this->actionMappings[__FUNCTION__];
        $return['fetch_data'] = array('enabled' => true);
        $return['on_submit'] = 'update';
        $return['portlet_title'] = 'Edit';
        $return['buttons']['array']['save']['actions']['action'] = 'update';
        $return['buttons']['array']['save']['actions']['form_type'] = 'edit';
        $return['fields'][] = $this->config['id'];
        if (isset($data['id'])) {
            $return['has_data'] = $this->loadFromDB($data['id']);
            $return['override_fields'] = array(
                'id' => array(
                    'type' => 'hidden',
                    'value' => getDefault($data['id'], ''),
                ),
            );

            foreach ($this->getForeignKeys() as $key => $value) {
                if (isset($return['all_fields']['fields'][$key]['select2']) && isset($return['all_fields']['fields'][$key]['select2']['ajax'])) {
                    $foreign_value = $return['has_data'][$key];
                    $db = $value['table'];
                    $return['override_fields'][$key] = array(
                        'type' => 'select',
                        'options' => $this->getSelectOptions("SELECT id,name FROM $db WHERE id='$foreign_value'"),
                        'width' => '4',
                    );
                }
            }
        }
        return $return;
    }

    public function getNewAjaxForm($data)
    {
        $tempData = $data;
        $return = $this->getNewForm($tempData);
        $return['form_type'] = $this->actionMappings[__FUNCTION__];
        $return['return_form_as'] = 'modal';
        return $return;
    }

    public function getAffectedTables()
    {
        $arr = array();
        return $arr;

    }

    public function getLabels()
    {
        { $arr['label'] =
            array(
                'id' => 'ID',
                'name' => 'Name',
                'email' => 'Email',
                'phone' => 'Phone Number',
                'contact_name' => 'Contact Name',
                'address' => 'Address',
            );
        }
        return $arr['label'];
    }

    public function getForeignKeys()
    {
        $arr = array(
            /*'usergroup'=>array(
        'relationship'=>'one-to-many',
        'table'=>'perm_groups',
        'join'=>'LEFT JOIN',
        'as'=>'sg',
        'id'=>'sg.id',
        'name'=>'sg.name'
        ) */
        );
        return $arr;
    }

    public function getIcons()
    {
        { $arr = array();
        }
        return $arr;
    }

    public function getAllFields()
    {
        { $arr['fields'] =
            array(
                'id' => array(
                    'type' => 'input',
                    'width' => '4',
                ),
                'name' => array(
                    'type' => 'input',
                    'width' => '4',
                ),
                'email' => array(
                    'type' => 'input',
                    'width' => '4',
                ),
                'phone' => array(
                    'type' => 'input',
                    'width' => '4',
                ),
                'contact_name' => array(
                    'type' => 'input',
                    'width' => '4',
                ),
                'address' => array(
                    'type' => 'input',
                    'width' => '4',
                ),
            )
            ;
        }

        return $arr;

    }

    public function extendAjax($data)
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
