<?php
class [RPL]CLASS_NAME[/RPL] extends Ajax
{
    /*Do not edit after this line*/
    public $dbTable = '[RPL]DB_TABLE[/RPL]';
    public $common_title = '[RPL]COMMON_TITLE[/RPL]';
    public $userData = array();
    public $userID = '';   
    public $session = [];  
    public $config = array(
          'id'=>'id',
          'name'=>'id'
      ); 
    public $form_configuration = array(); 
    public $module = ''; 

	public function __construct($dbConn = '') {
		if($dbConn != ''){
			$this->dbConn = $dbConn;
		}
		$this->module = $this->dbTable;
        $this->setFunctionMappings();
	}

    function setFunctionMappings(){
        $this->functionMappings['new'] = 'getNewForm';
        $this->functionMappings['new_ajax'] = 'getNewAjaxForm';
        $this->functionMappings['edit'] = 'getEditForm';
        $this->functionMappings['edit_password_single'] = 'getPasswordResetSingleForm';
        $this->functionMappings['edit_password_multiple'] = 'getPasswordResetMultipleForm';
        $this->functionMappings['login_form'] = 'getLoginForm';
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
        $this->table['cols'] = [RPL]TABLE_COLS[/RPL];
        $this->table['search_fields'] = [RPL]TABLE_COLS[/RPL];
        $this->table['typeahead'] = true;
        $this->table['search'] = true;
        $this->table['has_status_label'] = true;
        $this->table['has_checkbox'] = true;
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

    function getNewForm($data){
        $unique_id = uniqid();
        $unique_id .= rand(10,100);
        $return['form_type'] = $this->actionMappings[__FUNCTION__];
        $return['unique_id'] = $unique_id;
		$return['request_data'] = $data;
        $return['override_function'] = array(
                                        'enabled'=>false,
                                        'function'=>''		
                                    );
        $return['fetch_data'] = array('enabled'=>false);                                    
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
                            'enabled'=>false,
                            'html'=>''
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
        $return['fields'] = [RPL]NEW_FIELDS[/RPL];
        $return['required'] = [RPL]REQUIRED[/RPL];
        $return['validation'] = [RPL]VALIDATION_FIELDS[/RPL];
        $return['dependency'] = $this->dependency;
		$return['labels'] = $this->getLabels();
		$return['icons'] = $this->getIcons();
		$return['all_fields'] = $this->getAllFields();
        $return['duplicate'] =  [RPL]NO_DUPLICATE_FIELDS[/RPL];
        $return['popover_error'] = false;
        $return['non_db_fields'] =  array();
        $return['jsFunction'] =  "";
        $return['jsFunctionLast'] = "onChangeDashedString('__UNIQUE_ID__-name','__UNIQUE_ID__-unique_name');";
        $return['jsModalClose'] =  "";

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
    
    function getEditForm($data){
        $tempData = $data;
        $return = $this->getNewForm($tempData);
        $return['form_type'] = $this->actionMappings[__FUNCTION__];
        $return['fetch_data'] = array('enabled'=>true);
        $return['on_submit'] = 'update';
		$return['portlet_title'] = 'Edit';
        $return['buttons']['array']['save']['actions']['action'] = 'update';
        $return['buttons']['array']['save']['actions']['form_type'] = 'edit';
		$return['fields'][] = $this->config['id'];
        if(isset($data['id'])){
			$return['has_data'] = $this->loadFromDB($data['id']);
			$return['has_data']['image'] = $return['has_data']['image_path'].$return['has_data']['image'];
			$return['override_fields'] = array(
                                    'id'  => array(
                                            'type'=>'hidden',
                                            'value'=>getDefault($data['id'],'')
                                            )										
                            );
			
			foreach($this->getForeignKeys() as $key=> $value){
				if(isset($return['all_fields']['fields'][$key]['select2']) && isset($return['all_fields']['fields'][$key]['select2']['ajax'])){
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

    function getNewAjaxForm($data){
        $tempData = $data;
        $return = $this->getNewForm($tempData);
        $return['form_type'] = $this->actionMappings[__FUNCTION__];       
        $return['return_form_as'] = 'modal';  
        return $return;        
    }
	
    function getAffectedTables(){
        $arr = array();
        return $arr;
            
    }
     
    function getLabels(){
        {$arr['label'] = 
			[RPL]TITLES[/RPL];
        } 
        return $arr['label'];        
    }

    
    function getForeignKeys(){
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

    function getIcons(){
        {$arr = array();
        } 
        return $arr;        
    }
   
    function getAllFields(){
        {$arr['fields'] = 
					[RPL]ALL_FIELDS[/RPL]
					;
        }
 
        return $arr;	
        
    }

    /*  
        public function delete($id){
            
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

        public function deleteBulk($ids){
            $idsString= implode(',',$ids);
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
            if (DEV_MODE) {
                $fake_num = file_get_contents(DIR_CORE_INCLUDES . 'countnames.txt');
                //print_r($fake_num);
                //print_r($fake_num);
                file_put_contents(DIR_CORE_INCLUDES . 'countnames.txt', $fake_num + 1);
            }        
            //$this->insertImage($data,$unique_id,$id);
        }
        
        public function insertImage($data,$unique_id,$id){
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
    */

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
