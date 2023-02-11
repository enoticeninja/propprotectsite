<?php
class ManageAdvertisements extends Ajax
{
    /*Do not edit after this line*/
    public $dbTable = 'advertisements';
    public $common_title = 'Advertisement';
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

    public function getDataForApi(){
        $date = $_REQUEST['date'];
        $date = date('Y-m-d', strtotime($date));
        //print_r($date);
        $sql = "SELECT * FROM `$this->dbTable` WHERE '$date' BETWEEN `start_date` AND `end_date`";
        $query = $this->query($sql);
        $allRows = array();
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            if(empty($row['image']) || empty($row['image_path']) || empty($row['content'])){
                continue;
            }
            $row['start_date'] = date('d-m-Y', strtotime($row['start_date']));
            $row['end_date'] = date('d-m-Y', strtotime($row['end_date']));
            $row['image_url'] = SITE_PATH.$row['image_path'].$row['image'];
            unset($row['image']);
            unset($row['image_path']);
            $allRows[] = $row;
        }
        //print_r(mysqli_error($this->dbConn));
        return $allRows;
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
        $this->table['cols'] = array('id', 'client_id', 'name', 'content', 'freq_hour', 'freq_day', 'freq_week', 'start_date', 'end_date', 'image');
        $this->table['search_fields'] = array('id', 'client_id', 'name', 'content', 'freq_hour', 'freq_day', 'freq_week', 'start_date', 'end_date');
        $this->table['typeahead'] = true;
        $this->table['search'] = true;
        $this->table['has_status_label'] = false;
        $this->table['has_checkbox'] = false;
        $this->table['has_tr_buttons'] = true;
        $this->table['eval']['image'] =
        '
		if($data[\'image\'] != \'\' && $data[\'image_path\'] != \'\'){
			 \'<a href="' . FRONTEND_SITE_PATH . '{image_path}/{image}" data-fancybox="gallery" data-caption="{name}" >\
				<img src="' . FRONTEND_SITE_PATH . '{image_path}/100X100-{image}" class="card card-raised" style="margin:10px;width:100px">\
			</a>\';
		}
		else{
			 \'<img src="' . get_core_theme_path() . 'images/noimageavailable.png" class="card card-raised" style="margin:10px;width:100px">\';
		}';
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
        $return['on_submit_error'] = '';
        $return['name_prefix'] = 'data';
        $return['id_prefix'] = true;
        $return['after_save'] = 'insertImage';
        $return['fields'] = array('name', 'client_id', 'image', 'freq_hour', 'freq_day', 'freq_week', 'start_date', 'end_date', 'content', 'ad_url', 'daterange');
        $return['required'] = array('name', 'client_id', 'image', 'content', 'start_date', 'end_date');
        $return['validation'] = array(

        );
        $return['dependency'] = $this->dependency;
        $return['labels'] = $this->getLabels();
        $return['icons'] = $this->getIcons();
        $return['all_fields'] = $this->getAllFields();
        $return['duplicate'] = array();
        $return['popover_error'] = false;
        $return['non_db_fields'] = array();
        $return['jsFunction'] = "";
        $return['jsModalClose'] = "";

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
            //$return['has_data']['image'] = $return['has_data']['image_path'].$return['has_data']['image'];
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
                'client_id' => 'Client Id',
                'image_path' => 'Image Path',
                'ad_url' => 'Ad Url',
                'image' => 'Image',
                'content' => 'Content',
                'freq_hour' => 'Freq Hour',
                'freq_day' => 'Freq Day',
                'freq_week' => 'Freq Week',
                'start_date' => 'Start Date',
                'end_date' => 'End Date',
            );
        }
        return $arr['label'];
    }

    public function getForeignKeys()
    {
        $arr = array(
            'client_id' => array(
                'relationship' => 'one-to-many',
                'table' => 'ad_client',
                'join' => 'LEFT JOIN',
                'as' => 'ac',
                'id' => 'ac.id',
                'name' => 'ac.name',
            ),
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
                'client_id' => array(
                    'type' => 'select',
                    'options' => $this->getSelectOptions("SELECT id, name FROM `ad_client`"),
                    'width' => '4',
                ),
                'image_path' => array(
                    'type' => 'input',
                    'width' => '4',
                ),
                'ad_url' => array(
                    'type' => 'input',
                    'width' => '12',
                ),
                'image' => array(
                    'type' => 'upload_single_image',
                    'image_options' => array(
                        'upload_path' => 'uploads/advertisements/',
                        'upload_action' => 'upload_image_thumb',
                        'default_image' => 'core_theme/images/upload-image.png',
                    ),
                    'width' => '4',
                ),
                'content' => array(
                    'type' => 'input',
                    'width' => '12',
                ),
                'freq_hour' => array(
                    'type' => 'input',
                    'width' => '4',
                ),
                'freq_day' => array(
                    'type' => 'input',
                    'width' => '4',
                ),
                'freq_week' => array(
                    'type' => 'input',
                    'width' => '4',
                ),
                'daterange' => array(
                    'type' => 'daterangepicker',
                    'label' => 'Select Start - End Dates',
                    'start_date' => 'start_date',
                    'end_date' => 'end_date',
                    'width' => '4',
                ),
                'start_date' => array(
                    'type' => 'hidden',
                    'width' => '4',
                ),
                'end_date' => array(
                    'type' => 'hidden',
                    'width' => '4',
                ),
            )
            ;
        }

        return $arr;

    }

    public function delete($id)
    {
		$id = $data['id'];
        $sql = "SELECT image,image_path FROM $this->dbTable WHERE id='$id'";
        $query = mysqli_query($this->dbConn, $sql);
        $quotient = (int) ($id / 500); ///FOR NEW FOLDER
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            if (!empty($row['image'])) {
                if ($row['image_path'] != '' && $row['image_path'] != 'uploads') {
                    DeleteDirectoryAndContents(DIR_ROOT . $row['image_path']);
                }
            }
        }

        $return = array();
        $sql = "DELETE FROM `$this->dbTable` WHERE {$this->config['id']}='$id'";
        $query = mysqli_query($this->dbConn, $sql);
        $return['query'][] = $query;
        $return['sql'][] = $sql;
        foreach ($this->delete_dependency as $del => $fk) {
            $sql = "DELETE FROM $del WHERE $fk='$id'";
            $query1 = mysqli_query($this->dbConn, $sql);
            $return['query'][] = $query1;
            $return['sql'][] = $sql;
        }
        return $return;
    }

    public function deleteBulk($ids)
    {
        $idsString = implode(',', $ids);
        $sql = "SELECT id,image,image_path FROM $this->dbTable WHERE id IN ($ids)";
        $query = mysqli_query($this->dbConn, $sql);
        $return['mysqli_error'][] = mysqli_error($this->dbConn);
        //$quotient = (int)($id/500);///FOR NEW FOLDER
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            if (!empty($row['image'])) {
                if ($row['image_path'] != '' && $row['image_path'] != 'uploads') {
                    DeleteDirectoryAndContents(DIR_ROOT . $row['image_path']);
                }
            }
        }
        $sql = "DELETE FROM $this->dbTable WHERE id IN ($ids)";
        $query = mysqli_query($this->dbConn, $sql);
        $return['query'][] = $query;
        $return['sql'][] = $sql;
        $return['mysqli_error'][] = mysqli_error($this->dbConn);
        $return = array();
        foreach ($this->delete_dependency as $del => $fk) {
            $sql = "DELETE FROM $del WHERE $fk IN ($ids)";
            $query1 = mysqli_query($this->dbConn, $sql);
            $return['query'][] = $query1;
            $return['sql'][] = $sql;
            $return['mysqli_error'][] = mysqli_error($this->dbConn);
        }

        return $return;
    }

    public function deleteImage($data)
    {
        $id = $data['id'];
        $unique_id = $data['unique_id'];
        $sql = "SELECT image,image_path FROM $this->dbTable WHERE id='$id'";
        $query = mysqli_query($this->dbConn, $sql);
        $quotient = (int) ($id / 500); ///FOR NEW FOLDER
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            if (!empty($row['image'])) {
                if ($row['image_path'] != '' && $row['image_path'] != 'uploads') {
                    DeleteDirectoryAndContents(DIR_ROOT . $row['image_path']);
                }
            }
        }

        $sql = "UPDATE $this->dbTable SET image='',image_path='' WHERE id='$id'";
        $query = mysqli_query($this->dbConn, $sql);

        $image_site_path_link = FRONTEND_SITE_PATH . 'core_theme/images/upload-image.png';
        $return['ajaxresult'] = true;
        $return['jsFunction'] =
            "
            $('#{$unique_id}_image').addClass('hidden');
            $('#{$unique_id}_image_actions').addClass('hidden');
            $('#{$unique_id}_new_img_btn').removeClass('hidden');
            $('#{$unique_id}_image').attr('src','$image_site_path_link');
            $('#{$unique_id}_image').removeClass('hidden');
            ";
        echo json_encode($return);
        exit();
    }

    public function afterInsert($data, $unique_id, $id)
    {
        $this->insertImage($data, $unique_id, $id);
    }

    public function insertImage($data, $unique_id, $id)
    {
        if (empty($_FILES['data']['name'])) {
            return array();
        }

        include_once DIR_OTHER_CLASSES . "Class.Configuration.php";
        include_once DIR_OTHER_CLASSES . "Class.ImageManagerCore.php";
        $uploads_path = $this->getAllFields()['fields']['image']['image_options']['upload_path'];
        //print_r($_FILES['data']['name']);
        $pic_name = $_FILES['data']['name']['new'][$unique_id]['fields']['image'];
        $tmp_name = $_FILES['data']['tmp_name']['new'][$unique_id]['fields']['image'];
        $img_name_only = file_name($pic_name);
        $filename_for_db = preg_replace("/[\-_]/", " ", $img_name_only);
        $img_type_only = file_extension($pic_name);
        $image_path = '';
        $image = $id . '.' . $img_type_only;
        $imageName = $uploads_path . $image_path . $image;
        $quotient = (int) ($id / 500); ///FOR NEW FOLDER
        $fullPath = DIR_ROOT . $uploads_path . $quotient . '/' . $id . '/';
        $newRelPath = $uploads_path . $quotient . '/' . $id . '/'; ///Path without root
        if (!is_dir($fullPath)) {
            $temp = mkdir($fullPath, 0777, true);
        }
        //rename(DIR_ROOT.$imageName, $newRelPath.'/'.$image);
        move_uploaded_file($tmp_name, $fullPath . $image);

        $sql = "UPDATE {$this->dbTable} SET image='$image',image_path='$newRelPath' WHERE id='$id'";
        $query = mysqli_query($this->dbConn, $sql);

        $iman = new ImageManagerCore();
        ImageManagerCore::$dimensions = array();
        ImageManagerCore::$dimensions['100X100-'] = array('100', '100');
        ImageManagerCore::$dimensions['200X200-'] = array('200', '200');
        //print_r(DIR_ROOT.$newRelPath.$image);
        ImageManagerCore::resize($fullPath . $image, $fullPath, 50, 50, 'png', false, $image);
        return array();
    }
    public function uploadImage($data)
    {
        include_once DIR_OTHER_CLASSES . "Class.Configuration.php";
        include_once DIR_OTHER_CLASSES . "Class.ImageManagerCore.php";
        $uploads_path = $data['upload_path'];
        $unique_id = $data['unique_id'];
        $image_path = $data['image_path'];
        $originalName = $data['image'];
        $id = $data['id'];
        $base_name = basename($originalName);
        $image_ext = file_extension($base_name);
        $newName = $id . '.' . $image_ext;
        $originalNameWithRelPath = $uploads_path . $image_path . $originalName;
        $quotient = (int) ($id / 500); ///FOR NEW FOLDER
        $fullPath = DIR_ROOT . $uploads_path . $quotient . '/' . $id . '/';
        $newRelPath = $uploads_path . $quotient . '/' . $id . '/'; ///Path without root
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }
        rename(DIR_ROOT . $originalNameWithRelPath, $fullPath . $newName);
        $sql = "UPDATE {$this->dbTable} SET image='$newName',image_path='$newRelPath' WHERE id='$id'";
        $query = mysqli_query($this->dbConn, $sql);

        $iman = new ImageManagerCore();
        ImageManagerCore::$dimensions = array();
        ImageManagerCore::$dimensions['100X100-'] = array('100', '100');
        ImageManagerCore::$dimensions['200X200-'] = array('200', '200');
        //print_r(DIR_ROOT.$newRelPath.$newName);
        ImageManagerCore::resize(DIR_ROOT . $newRelPath . $newName, DIR_ROOT . $newRelPath, 50, 50, 'png', false, $newName);

        $image_site_path_link = FRONTEND_SITE_PATH . $newRelPath . $newName;
        $return['jsFunction'] =
            "
            $('#{$unique_id}_image_actions').removeClass('hidden');
            $('#{$unique_id}_new_img_btn').addClass('hidden');
            $('#{$unique_id}_default_image').addClass('hidden');
            $('#{$unique_id}_image').attr('src','$image_site_path_link');
            $('#{$unique_id}_image').removeClass('hidden');
            ";
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'Success';
        echo json_encode($return);
        exit();
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
