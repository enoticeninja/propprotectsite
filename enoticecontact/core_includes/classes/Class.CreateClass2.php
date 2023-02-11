<?php
class CreateClass2 extends Ajax
{
    public $validation_types = array(		
        'number' => 'Number Only',
        'email' => 'Email Address',
        'mobile' => 'Mobile Number',
        'letters' => 'Letters Only',
        'alphanumeric' => 'Alpha Numeric',
        'alphanumericspaceundash' => 'Alphanumeric Space Underscore Dash'
        );

       
    public $dbTable = 'asdfasdf';
    public $common_title = 'Class';
    public $assoc_with_same_table = 'type';
    public $config = array(
            'id'=>'id',
            'name'=>'name'
        );

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
          $this->functionMappings['saveFirstProduct'] = 'saveFirstProduct';
          $this->functionMappings['select_db'] = 'selectDatabaseForm';
          return $this->functionMappings;
      }

    function getIcons(){
        return array();
    } 
    function getLabels(){
        {$arr['label'] = array(
            'table_name' => 'Table Name',
            'field_name' => 'Field',
            'length' => 'Length',
            'label' => 'Label',
            'icon' => 'Icon',
            'validation' => 'Validate',
            'validation_type' => 'Validation',
            'required' => 'Require',
            'no_duplicate' => 'No Duplicates',
            'default' => 'Default Value',
            'field_type' => 'Field Type',
            'field_index' => 'Field Index',
            'new_form' => 'New Form',
            'edit_form' => 'Edit Form',
            'html_id' => 'HTML ID',
            'html_name' => 'HTML NAME',
            'in_table' => 'Show in Table'
            );
        }
        return $arr['label'];        
    }
    
    function getForeignKeys(){
        $arr = array();
        return $arr;            
    }


    
    function getNewForm($data){
        $unique_id = uniqid();
        $unique_id .= rand(10,100);  
        $jsFunction = '';   
            /////Form Configuration //////////////
        $return['unique_id'] = $unique_id;
        $return['request_data'] = $data;
        $return['override_function'] = array(
                                        'enabled'=>false,
                                        'function'=>''		
                                    );
        $return['fetch_data'] = array('enabled'=>false);                                    
        $return['portlet_title'] = 'Add New Company';
        $return['portlet_icon'] = '';
        $return['has_data'] = $this->dbData;
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
        $return['buttons'] = array(
                        'enabled'=>false,
                        'html'=>''
                        );
        $return['on_submit'] = 'insert';
        $return['default_insert'] = array(
                                    'enabled'=>false,
                                    'date_created'=>date('Y-m-d H-i-s'),
                                    'createdby'=>$_SESSION[get_session_values('id')]
                                    );
        $return['on_submit_error'] = '';
        $return['id_prefix'] = true;
        $return['fields'] = array('field_name','length','label','validation_type','required','no_duplicate','in_table','new_form','field_type');
        $return['required'] = array();
        $return['validation'] = array();
        $return['dependency'] = $this->dependency;
        $return['labels'] = $this->getLabels();
        $return['icons'] = $this->getIcons();
        $return['all_fields'] = $this->getAllFields();
        $return['duplicate'] = array();
        $return['popover_error'] = false;
        $return['non_db_fileds'] = array();
        $return['jsFunction'] = "";
        $return['jsFunctionLast'] = "";
        $return['jsModalClose'] = "";
        return $return;
    
    }
    
    function selectDatabaseForm($data){
        $unique_id = uniqid();
        $unique_id .= rand(10,100);  
        $jsFunction = '';   
            /////Form Configuration //////////////      
        $tempData = $data;
        $return = $this->getNewForm($tempData);
        $return['unique_id'] = $unique_id;
        $return['request_data'] = $data;
        $return['fetch_data'] = array('enabled'=>false);                                    
        $return['portlet_title'] = 'Choose Database';
        $return['portlet_icon'] = '';
        $return['submit_id'] = 'submit_id';
        $return['call_type'] = isset($data['call_type']) ? $data['call_type'] : 'ajax';
        $return['return_form_as'] = isset($data['return_form_as']) ? $data['return_form_as'] : 'rows';
        $return['parent'] = $parent = $unique_id;
        $return['module'] = $module = $this->dbTable;
        $return['hidden'] = array(
                            'enabled'=>false,
                            'html'=>''
                                );
        $return['buttons'] = array(
                        'enabled'=>true,
                        'html'=>'<button type="button" class="btn btn-outline btn-half-block green" id="__UNIQUE_ID__-btnSave" onclick="CommonFunc2({\'action\':\'get_db_table\',\'unique_id\':\'__UNIQUE_ID__\'},\'__UNIQUE_ID__-form\',this)" data-unique_id="__UNIQUE_ID__">GET DATABASE CONFIGURATION</button>'
                        );
        $return['on_submit'] = 'insert';
        $return['default_insert'] = array(
                                    'enabled'=>false,
                                    'date_created'=>date('Y-m-d H-i-s'),
                                    'createdby'=>$_SESSION[get_session_values('id')]
                                    );
        $return['name_prefix'] = 'data';
        $return['fields'] = array('db_name','db_table','class_name','common_title');
        $return['required'] = array('db_name','db_table');
        $return['validation'] = array(); 
        $return['dependency'] = array();
        $return['duplicate'] =  array();
        return $return;
    
    }
    function getEditForm($data){
        $tempData = $data;
        $tempData['form_type'] = 'new';
        $return = $this->getNewForm($tempData);
        $return['on_submit'] = 'update';        
        //$return['fields'][] = 'ID';  
        return $return;        
    }
    protected function getTablesInDB() {
		$sql = "SHOW TABLES FROM ".DB_NAME."";
        $arr = array();
        $query = mysqli_query($this->dbConn, $sql);
        //print_r(mysqli_error($this->dbConn));
        if($query){
            while($row = mysqli_fetch_array($query)){
				//print_r($row);
                $arr[$row[0]] = $row[0];
            }            
        }
        return $arr;
    }    
    function getAllFields(){
        $checkboxEval = "
                         if(isset(\$value['color'])){
                          \$element['color'] = \$value['color'];
                        }
                        \$element['value'] = \$element['id'];
                        \$t = uniqid();
                        \$element['id'] = ''.\$element['id'].'-'.\$this->dbData['keep_track'];                       
                        ";
        {$arr = array(	        
                'db_name'=>array(
                        'type'=>'input',
                        'width'=>'3',
                        'label'=>'Database',
                        'value'=>DB_NAME,
                        ),
                'db_table'=>array(
                        'type'=>'select',
                        'options'=>$this->getTablesInDB(),
                        'width'=>'3',
                        'label'=>'Table',
                        'value'=>'client',
                        ),		        
                'class_name'=>array(
                        'type'=>'input',
                        'width'=>'3',
                        'label'=>'Class Name'
                        ),			        
                'common_title'=>array(
                        'type'=>'input',
                        'width'=>'3',
                        'label'=>'Common Title'
                        ),	        
                'table_name'=>array(
                        'type'=>'hidden',
                        'width'=>'4',
                        'left_offset'=>'4',
                        'right_offset'=>'4',
                        'name'=>'table_name[]',
                        'eval'=>""
                        ),	         
                'field_name'=>array(
                        'type'=>'input',
                        'width'=>'1',
                        'name'=>'{field_name}[field_name]',
                        'eval'=>""
                        ),	        
                'length'=>array(
                        'type'=>'input',
                        'width'=>'1',
                        'name'=>'{field_name}[length]',
                        'eval'=>""
                        ),
                'label'=>array(
                        'type'=>'input',
                        'width'=>'2',
                        'name'=>'{field_name}[label]',
                        'eval'=>""
                        ),
/*                 'icon'=>array(
                        'type'=>'input',
                        'width'=>'1',
                        'name'=>'{field_name}[icon]',
                        'eval'=>""
                        ), */
	            'validation'=>array(
                        'type'=>'checkbox',
                        'value'=>'1',
                        'width'=>'1',
                        'name'=>'{field_name}[validation]',
                        'color'=>'33691e',		
                        'evalcode'=> $checkboxEval,		
                        'default'=>''
                        ),
                'validation_type'=>array(
                        'type'=>'select',
                        'options'=>$this->validation_types,
                        'value'=>'',
                        'name'=>'{field_name}[validation_type]',
                        'width'=>'1',
                        'default'=>''
                        ),                        
                'required'=>array(
                        'type'=>'checkbox',
                        'value'=>'1',
                        'width'=>'1',
                        'name'=>'{field_name}[required]',											
                        'color'=>'ffa000',		
                        'evalcode'=> $checkboxEval,	
                        'default'=>''
                        ),                        
                'no_duplicate'=>array(
                        'type'=>'checkbox',
                        'value'=>'1',
                        'width'=>'1',
                        'name'=>'{field_name}[no_duplicate]',											
                        'color'=>'ffa000',		
                        'evalcode'=> $checkboxEval,	
                        'default'=>''
                        ), 	
	            'default'=>array(
                        'type'=>'checkbox',
                        'value'=>'1',
                        'width'=>'1',
                        'name'=>'{field_name}[default]',		
                        'evalcode'=> $checkboxEval,	
                        'default'=>''
                        ), 	
	            'in_table'=>array(
                        'type'=>'checkbox',
                        'value'=>'1',
                        'width'=>'1',
                        'name'=>'{field_name}[in_table]',											
                        'color'=>'b71c1c',		
                        'evalcode'=> $checkboxEval,	
                        'default'=>''
                        ),  	
	            'field_type'=>array(
                        'type'=>'select',
                        'options'=>$this->getFieldTypes(),
                        'value'=>'input',
                        'width'=>'1',
                        'name'=>'{field_name}[field_type]',
                        ), 	
 	            'new_form'=>array(
                        'type'=>'checkbox',
                        'value'=>'',
                        'width'=>'1',
                        'name'=>'{field_name}[new_form]',											
                        'color'=>'38b71c',		
                        'evalcode'=> $checkboxEval,	
                        'default'=>''
                        ), 	
/*	            'edit_form'=>array(
                        'type'=>'checkbox',
                        'value'=>'',
                        'width'=>'1',
                        'name'=>'{field_name}[edit_form]',											
                        'color'=>'b71c1c',		
                        'evalcode'=> $checkboxEval,	
                        'default'=>''
                        ) */
            );
        }
        
        $return['fields'] = $arr;
        return $return;
    }

	function getFieldTypes(){
		$arr['input'] = 'Input';
		$arr['textarea'] = 'Textarea';
		$arr['select'] = 'Select Box';
		$arr['checkbox'] = 'Check Box';
		$arr['advanceddatetime'] = 'Advanced Date Time';
		$arr['upload_image'] = 'Upload Image Field';
		$arr['upload_single_image'] = 'Upload Single Image Field';
		$arr['upload_logo'] = 'Upload Logo Field';
		return $arr;
    }
    
    function generateBlankTable(){
        global $table,$db,$sql;
        $table->headers = $this->generateTableHeaders($this->table);
        $table->filterFields = $this->generateFilterRow($this->table);    
        $table->bulkUpdateRow = $this->generateBulkUpdateRow();
        if($this->table['portlet-actions'] != ''){
            $table->actions = $this->table['portlet-actions'];                
        }
        $return['jsFunction'] = $this->getDependencyJsTable($this->table);
        return $return;         
    }

    function getTableFields(){
        ////Specify all the fields here and override them in custom-cols later
        $sql = $this->generateSqlPullData();
        $this->table['sql'] = $sql;       
        $this->table['cols'] = array('name','state','country','zip','type','status');
        $this->table['has-checkbox'] = true;
        $this->table['has-tr-buttons'] = true;
        $this->table['tr-buttons']['edit-distances'] = '<a href="#modalForm" type="button" class="btn btn-outline green dropdown-toggle" data-toggle="modal" aria-expanded="false" onClick="GetForm(\'edit-assoc-form\',\'{id}\',\'replace-mtrow\',\'modalForm\')">Edit Distances  </a>';
        $this->table['th-buttons'] = array(
                            'refresh'=>'<a href="#" type="button" class="btn red" onClick="CommonFunc({\'action\':\'refresh\'})"><i class="icon-refresh" ></i>Refresh</a>'	
                            );
        $this->table['portlet-actions'] = '';
        
        $this->table['custom-cols'] = array(
                            'name'=>array(
                                    'heading'=>'Name',
                                    'value'=>'{name}'
                                    )	
                            );
        return $this->table;
    }

        
    public function getAnotherFieldForm($modal='modal',$ajax=false){
        $f = $this->getFields('edit-form');
        $next_buttons = '';          
        $common_title_stripped =preg_replace('/\s+/', '', $this->common_title);           
        $f['portlet-title'] = 'Edit '.$this->common_title.'';
        $f['portlet-icon'] = '';
        $f['form-id'] = 'edit'.$common_title_stripped.'Form';
        $f['submit-id'] = 'btnUpdate'.$common_title_stripped.'';
        $f['buttons'] = $next_buttons;
            
        $form = $this->getForm($f,$ajax,'edit-form');
        return $form;
    }
    

    function extendAjax($data){
        if(isset($data['type'])){
            if($data['type'] == 'get-another-field'){
                $return = $this->getAnotherFieldForm('',true);
                $return['ajaxresult'] = true;
                $return['ajaxmessage'] = 'Success';
                $common_title_stripped = preg_replace('/\s+/', '', $this->common_title);     
                $return['jsFunction'] .= 
                "
                $('#new${common_title_stripped}Form').append('<div class=\"row\">'+value.form+'</div>');
                ";
                echo json_encode($return);
                exit();	               
            }
            $this->errorAjax($data);            
        }

        if(isset($data['action'])){        
            if($data['action'] == 'save-and-create-class'){
                //print_r($data);
                unset($_POST['action']);
                unset($_POST['module']);
                $return['html'] = '<pre>'.$data.'</pre>';	
                $return['jsFunction'] = "";	
                echo json_encode($return);
                exit();
            }
            
            $this->errorAjax($data);            
        }
        else{
            $this->errorAjax($data);
        }        
        
    }

}
?>

