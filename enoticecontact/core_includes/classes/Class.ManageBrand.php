<?php
class ManageBrand extends Ajax
{
    /*Do not edit after this line*/
    public $dbTable = 'brand';
    public $common_title = 'Brand';
    public $userData = array();
    public $userID = '';
    public $session = [];
    public $config = array(
        'id' => 'id',
        'name' => 'id',
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
        $this->functionMappings['delete_image'] = 'deleteImage';
        $this->actionMappings = array_flip($this->functionMappings);
        return $this->functionMappings;
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
        $this->table['cols'] = array('id', 'name', 'category', 'image');
        $this->table['search_fields'] = array('id', 'name', 'category', 'image');
        $this->table['typeahead'] = true;
        $this->table['search'] = true;
        $this->table['has_status_label'] = true;
        $this->table['has_checkbox'] = true;
        $this->table['has_tr_buttons'] = true;
		$this->table['eval']['image'] = 
		'
		if($data[\'image\'] != \'\' && $data[\'image_path\'] != \'\'){
			 \'<a href="'.FRONTEND_SITE_PATH.'{image_path}/{image}" data-fancybox="gallery" data-caption="{name}" >\
				<img src="'.FRONTEND_SITE_PATH.'{image_path}/100X100-{image}" class="card card-raised" style="margin:10px;width:100px">\
			</a>\';
		}
		else{
			 \'<img src="'.get_core_theme_path().'images/noimageavailable.png" class="card card-raised" style="margin:10px;width:100px">\';
		}';
            /*         $newJsFunc2 =
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
        $return['after_save'] = 'insertImage';
        $return['hidden'] = array(
            'enabled' => false,
            'html' => '',
        );
        $return['on_submit'] = 'insert';
        /*         $return['default_insert'] = array(
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
        $return['fields'] = array('name', 'category', 'image', 'type', 'status', 'notes');
        $return['required'] = array('name', 'status');
        $return['validation'] = array(

        );
        $return['dependency'] = $this->dependency;
        $return['labels'] = $this->getLabels();
        $return['icons'] = $this->getIcons();
        $return['all_fields'] = $this->getAllFields();
        $return['duplicate'] = array('name');
        $return['popover_error'] = false;
        $return['non_db_fields'] = array();
        $return['jsFunction'] = "";
        $return['jsFunctionLast'] = "";
        $return['jsModalClose'] = "";
        return $return;

    }

    public function getEditForm($data)
    {
        $tempData = $data;
        $return = $this->getNewForm($tempData);
        $return['fetch_data'] = array('enabled' => true);
        $return['form_type'] = $this->actionMappings[__FUNCTION__];
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
                'category' => 'Category',
                'image_path' => 'Image Path',
                'image' => 'Image',
                'type' => 'Type',
                'status' => 'Status',
                'notes' => 'Notes',
            );
        }
        return $arr['label'];
    }

    public function getForeignKeys()
    {
        $arr = array(
            /*                 'usergroup'=>array(
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
                'category' => array(
                    'type' => 'select',
                    'options' => $this->getSelectOptions("SELECT id,name FROM category"),
                    'width' => '4',
                ),
                'image_path' => array(
                    'type' => 'input',
                    'width' => '4',
                ),
                'image' => array(
                    'type' => 'upload_single_image',
                    'image_options' => array(
                        'upload_path' => 'uploads/brands/',
                        'upload_action' => 'upload_image_thumb',
                        'default_image' => 'core_theme/images/upload-image.png',
                    ),
                    'width' => '4',
                ),
                'type' => array(
                    'type' => 'input',
                    'width' => '4',
                ),
                'status' => array(
                    'type' => 'select',
                    'options' => get_numeric_status_list(),
                    'width' => '4',
                ),
                'notes' => array(
                    'type' => 'textarea',
                    'width' => '12',
                ),
            )
            ;
        }

        return $arr;

    }
    public function delete($data){
		$id = $data['id'];
        $sql = "SELECT image,image_path FROM $this->dbTable WHERE id='$id'";
        $query = mysqli_query($this->dbConn, $sql);
		$quotient = (int)($id/500);///FOR NEW FOLDER
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            if(!empty($row['image']) ){
				if($row['image_path'] != '' && $row['image_path'] != 'uploads'){
                    DeleteDirectoryAndContents(DIR_ROOT.$row['image_path']);
                }
			}
        }
		
        $return = array();
        $sql = "DELETE FROM `$this->dbTable` WHERE {$this->config['id']}='$id'";
        $query = mysqli_query($this->dbConn,$sql);
        $return['query'][] = $query;
        $return['sql'][] = $sql;
        foreach($this->delete_dependency as $del => $fk){
            $sql = "DELETE FROM $del WHERE $fk='$id'";
            $query1 = mysqli_query($this->dbConn,$sql); 
            $return['query'][] = $query1;
            $return['sql'][] = $sql;            
        } 
       return $return;
    }

    public function deleteBulk($idsArr){
        $ids = implode(',',$idsArr);
        $sql = "SELECT id,image,image_path FROM $this->dbTable WHERE id IN ($ids)";
        $query = mysqli_query($this->dbConn, $sql);
		$return['mysqli_error'][] = mysqli_error($this->dbConn);
		//$quotient = (int)($id/500);///FOR NEW FOLDER
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            if(!empty($row['image']) ){
				if($row['image_path'] != '' && $row['image_path'] != 'uploads'){
                    DeleteDirectoryAndContents(DIR_ROOT.$row['image_path']);
                }
			}
        }
		$sql = "DELETE FROM $this->dbTable WHERE id IN ($ids)";
		$query = mysqli_query($this->dbConn,$sql);
		$return['query'][] = $query;
		$return['sql'][] = $sql;
		$return['mysqli_error'][] = mysqli_error($this->dbConn);
        $return = array();
		foreach($this->delete_dependency as $del => $fk){
			$sql = "DELETE FROM $del WHERE $fk IN ($ids)";
			$query1 = mysqli_query($this->dbConn,$sql);
			$return['query'][] = $query1;
			$return['sql'][] = $sql;  
			$return['mysqli_error'][] = mysqli_error($this->dbConn);
		}

       return $return;
    }
	
	public function deleteImage($data){
        $id = $data['id'];
        $unique_id = $data['unique_id'];
        $sql = "SELECT image,image_path FROM $this->dbTable WHERE id='$id'";
        $query = mysqli_query($this->dbConn, $sql);
		$quotient = (int)($id/500);///FOR NEW FOLDER
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            if(!empty($row['image']) ){
				if($row['image_path'] != '' && $row['image_path'] != 'uploads'){
                    DeleteDirectoryAndContents(DIR_ROOT.$row['image_path']);
                }
			}
        }
        
        $sql = "UPDATE $this->dbTable SET image='',image_path='' WHERE id='$id'";
        $query = mysqli_query($this->dbConn, $sql);
        
        $image_site_path_link = FRONTEND_SITE_PATH.'core_theme/images/upload-image.png';        
        $return['ajaxresult'] = true;
        $return['jsFunction'] = 
        "
        $('#{$unique_id}_image').addClass('hidden');
        $('#{$unique_id}_image_actions').addClass('hidden');
        $('#{$unique_id}_new_img_btn').removeClass('hidden');
        $('#{$unique_id}_image').attr('src','$image_site_path_link');
        $('#{$unique_id}_image').removeClass('hidden');
        ";
        echo json_encode($return);;
        exit();
    }


	public function afterInsert($data,$unique_id,$id){
		$this->insertImage($data,$unique_id,$id);
	}
	public function insertImage($data,$unique_id,$id){
		if(empty($_FILES['data']['name'])) return array();
		include_once(DIR_OTHER_CLASSES."Class.Configuration.php");
		include_once(DIR_OTHER_CLASSES."Class.ImageManagerCore.php");				
		$uploads_path = $this->getAllFields()['fields']['image']['image_options']['upload_path'];
		//print_r($_FILES['data']['name']);
		$pic_name = $_FILES['data']['name']['new'][$unique_id]['fields']['image'];
		$tmp_name = $_FILES['data']['tmp_name']['new'][$unique_id]['fields']['image'];
		$img_name_only = file_name($pic_name);
		$filename_for_db = preg_replace("/[\-_]/", " ", $img_name_only);
		$img_type_only = file_extension($pic_name);
		$image_path = '';
		$image = $id.'.'.$img_type_only;
		$imageName = $uploads_path.$image_path.$image;
		$quotient = (int)($id/500);///FOR NEW FOLDER
		$fullPath = DIR_ROOT.$uploads_path.$quotient.'/'.$id.'/';
		$newRelPath = $uploads_path.$quotient.'/'.$id.'/';///Path without root
		if(!is_dir($fullPath)){
			$temp = mkdir($fullPath, 0777, true);
		}
		//rename(DIR_ROOT.$imageName, $newRelPath.'/'.$image);
		move_uploaded_file($tmp_name, $fullPath.$image);
		
		$sql = "UPDATE {$this->dbTable} SET image='$image',image_path='$newRelPath' WHERE id='$id'";
		$query = mysqli_query($this->dbConn, $sql);
		
		$iman = new ImageManagerCore();
		ImageManagerCore::$dimensions = array();
		ImageManagerCore::$dimensions['100X100-'] = array('100','100');
		ImageManagerCore::$dimensions['200X200-'] = array('200','200');
		//print_r(DIR_ROOT.$newRelPath.$image);
		ImageManagerCore::resize($fullPath.$image, $fullPath, 50, 50, 'png',false,$image);		
		return array();
	}
    function uploadImage($data){
        include_once(DIR_OTHER_CLASSES."Class.Configuration.php");
        include_once(DIR_OTHER_CLASSES."Class.ImageManagerCore.php");				
        $uploads_path = $data['upload_path'];
        $unique_id = $data['unique_id'];
        $image_path = $data['image_path'];
        $originalName = $data['image'];
        $id = $data['id'];
        $base_name = basename($originalName);
        $image_ext = file_extension($base_name);
        $newName = $id.'.'.$image_ext;
        $originalNameWithRelPath = $uploads_path.$image_path.$originalName;
        $quotient = (int)($id/500);///FOR NEW FOLDER
        $fullPath = DIR_ROOT.$uploads_path.$quotient.'/'.$id.'/';
        $newRelPath = $uploads_path.$quotient.'/'.$id.'/';///Path without root
        if(!is_dir($fullPath)){
          mkdir($fullPath, 0777, true);
        }
        rename(DIR_ROOT.$originalNameWithRelPath, $fullPath.$newName);
        $sql = "UPDATE {$this->dbTable} SET image='$newName',image_path='$newRelPath' WHERE id='$id'";
        $query = mysqli_query($this->dbConn, $sql);
        
        $iman = new ImageManagerCore();
        ImageManagerCore::$dimensions = array();
        ImageManagerCore::$dimensions['100X100-'] = array('100','100');
        ImageManagerCore::$dimensions['200X200-'] = array('200','200');
        //print_r(DIR_ROOT.$newRelPath.$newName);
        ImageManagerCore::resize(DIR_ROOT.$newRelPath.$newName, DIR_ROOT.$newRelPath, 50, 50, 'png',false,$newName);


        $image_site_path_link = FRONTEND_SITE_PATH.$newRelPath.$newName;
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
