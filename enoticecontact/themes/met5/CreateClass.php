<?php
include_once("bootstrap.php");
include_once("common.php");
require_once(DIR_OTHER_CLASSES."Class.CreateClass.php");
//$module = $arr[2];
$__page_active = '';
$__sub_page_active = '';

$username = $_SESSION[get_session_values('username')];
$class = new CreateClass($db_conx);
//$db = new Database();
//$db->db_conx = $db_conx;
$toolsAll = array();
$title = $class->common_title;
$table = new Table();
$table->rowsCountDisplay = 10;
$table->filterRows = '';
$table->actions = '
				<a href="#modalForm" type="button" class="btn btn-circle green" data-toggle="modal" aria-expanded="false" onClick="GetForm(\'new-form\',\'\',\'add-mtrow\',\'modalForm\')">
					<i class="fa fa-plus"></i> Add </a>
				<a href="#" class="btn btn-circle btn-icon-only blue reload" data-original-title="" title="" onClick="CommonFunc({\'action\':\'refresh\'})"> <i class="icon-refresh" ></i></a>										
				<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title=""> </a>
				';	
$bbttnnss = '
				<a href="#modalForm" type="button" class="btn btn-circle green" data-toggle="modal" aria-expanded="false" onClick="GetForm(\'new-form\',\'\',\'add-mtrow\',\'modalForm\')">
					<i class="fa fa-plus"></i> Add </a>
				<a href="#" class="btn btn-circle btn-icon-only blue reload" data-original-title="" title="" onClick="CommonFunc({\'action\':\'refresh\'})"> <i class="icon-refresh" ></i></a>										
				<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title=""> </a>
				';				
/* $table->tableActionFields[] = '<a href="#modalForm" type="button" class="btn btn-sm yellow dropdown-toggle" data-toggle="modal" aria-expanded="false" onClick="GetForm(\'new-form\',\'\',\'add-mtrow\',\'modalForm\')">Add '.$class->common_title.'</a>'; */

$table->tableActionsWrapper = '';
$table->tableTitle = $class->common_title;
$table->hasNewRow = false;
$table->hasSaveChanges = false;
$table->pagination = true;		
$table->portletClass = 'light';	
$table->iconClass = 'fa fa-user';	
/////CHANGE HERE FOR A NEW PAGE 

$__all_titles = array();
$__all_titles['id'] = 'ID';
$__all_titles['name'] = 'Name';
$__all_titles['firstname'] = 'First Name';
$__all_titles['first_name'] = 'First Name';
$__all_titles['lastname'] = 'Last Name';
$__all_titles['last_name'] = 'Last Name';
$__all_titles['middle_name'] = 'Middle Name';
$__all_titles['middlename'] = 'Middle Name';
$__all_titles['title'] = 'Title';
$__all_titles['gender'] = 'Gender';
$__all_titles['email'] = 'Email Address';
$__all_titles['phone'] = 'Phone Number';
$__all_titles['mobile'] = 'Mobile Number';
$__all_titles['password'] = 'Password';
$__all_titles['username'] = 'User Name';
$__all_titles['user_name'] = 'User Name';
$__all_titles['type'] = 'Type';
$__all_titles['status'] = 'Status';
$__all_titles['notes'] = 'Notes';
$__all_titles['description'] = 'Description';
$__all_titles['date_created'] = 'Created On';
$__all_titles['cretion_date'] = 'Created On';
$__all_titles['created_by'] = 'Created By';
$__all_titles['logo'] = 'Logo';
//$__all_titles['last_login'] = 'Last Login';
$__all_titles['category'] = 'Category';
$__all_titles['duration'] = 'Duration';
$__all_titles['start_date'] = 'Start Date';
$__all_titles['startdate'] = 'Start Date';
$__all_titles['end_date'] = 'End Date';
$__all_titles['enddate'] = 'End Date';

///CHANGE HERE FOR A NEW PAGE 
$sql = "SELECT * FROM $class->dbTable"; 
////CHANGE HERE FOR A NEW PAGE

$col_data_buttons = array();
	/* if (isset($_POST['type'])){
		$class->HandleAjaxCall($_POST);	
	}
	else  */
	if (isset($_POST['action'])){
        //
        if($_POST['action'] == 'create-class'){
            //print_r($_POST);
            unset($_POST['action']);
            unset($_POST['module']);
            $data = $_POST;
            $add_data = $_POST['add_data'];
            $fields = array();
            $all_fields = '';
            $table_fields = '';
            $table_fields_arr = array();
            $validation_fields = '';
            $validation_fields_arr = array();
            $required_fields = '';
            $required_fields_arr = array();
            $no_duplicate_fields = '';
            $no_duplicate_fields_arr = array();
            $in_table_fields = '';
            $new_form_fields = '';
            $in_table_fields_arr = array();
			unset($data['add_data']);
            foreach($data as $field){
                //if(empty($field)) continue;
                $titles[$field['field_name']] = $field['label'];
                $fields[$field['field_name']]['type'] = $field['field_type'];
				if($fields[$field['field_name']]['type'] == 'select'){
					$fields[$field['field_name']]['options'] = '';
				}
				else if($fields[$field['field_name']]['type'] == 'upload_logo' || $fields[$field['field_name']]['type'] == 'upload_image' || $fields[$field['field_name']]['type'] == 'upload_single_image'){
					$fields[$field['field_name']]['image_options'] = array(
							'upload_path' => 'uploads/shapes/',
							'upload_action' => 'upload_image_thumb',
							'default_image' => 'core_theme/images/logo111.png',
							);
				}
				if(trim($field['length']) == ''){
					$fields[$field['field_name']]['width'] = '4';
				}
				else{
					$fields[$field['field_name']]['width'] = trim($field['length']);
				}
                //if(isset($field['validation'])){
                if(trim($field['validation_type']) != ''){
					$validation_fields .= 
							"'$field[field_name]'=>array(
									'type'=>'$field[validation_type]'
								),
								";
					$validation_fields_arr[$field['field_name']]['type'] = $field['validation_type'];
				}
                if(isset($field['required']))  $required_fields .= "'$field[field_name]',";  $required_fields_arr[] = "$field[field_name]";
                if(isset($field['no_duplicate']))  $no_duplicate_fields .= "'$field[field_name]',";  $no_duplicate_fields_arr[] = "$field[field_name]";
                if(isset($field['in_table']))  $in_table_fields .= "'$field[field_name]',"; $in_table_fields_arr[] = "$field[field_name]";
                if(isset($field['new_form']))  $new_form_fields .= "'$field[field_name]',"; $in_table_fields_arr[] = "$field[field_name]";
                $table_fields .= "'$field[field_name]',";
                $table_fields_arr[] = "$field[field_name]";
            }
            $table_fields = rtrim($table_fields,',');
            $no_duplicate_fields = rtrim($no_duplicate_fields,',');
            $required_fields = rtrim($required_fields,',');
            $validation_fields = rtrim($validation_fields,',');
            $in_table_fields = rtrim($in_table_fields,',');
            $new_form_fields = rtrim($new_form_fields,',');
            $all_table_fields = "array(
									$table_fields
									)";
            $no_duplicate_fields = "array($no_duplicate_fields)";
            $validation_fields = "array(
								$validation_fields
								)";
            $required_fields = "array($required_fields)";
            $in_table_fields = "array($in_table_fields)";
            $new_form_fields = "array($new_form_fields)";
            $table_fields = "array($table_fields)";
             echo '<h1>Titles</h1></br>';
             //echo '<pre>' , var_export($_POST, true) , '</pre>';
             echo '<pre>' , var_export($titles, true) , '</pre>';
             echo '<h1>Form Fields</h1></br>';
             echo '<pre>' , var_export($fields, true) , '</pre>';
             echo '<h1>Required</h1></br>';
             echo '<pre>' , ($required_fields) , '</pre>';
             echo '<h1>Duplicates</h1></br>';
             echo '<pre>' , ($no_duplicate_fields) , '</pre>';
             echo '<h1>Validation</h1></br>';
             echo '<pre>' , ($validation_fields) , '</pre>';
             echo '<h1>Table Fields</h1></br>';
             echo '<pre>' , ($in_table_fields) , '</pre>';
             echo '<h1>Form Fields</h1></br>';
             echo '<pre>' , ($new_form_fields) , '</pre>';
            echo '<h1>All Fields</h1></br>';
             echo '<pre>' , ($all_table_fields) , '</pre>';
            echo '<h1>Post Data</h1></br>';
            echo '<pre>' , var_export($_POST, true) , '</pre>';
			
			$class_code = file_get_contents(DIR_CORE_INCLUDES.'class-template.php');
			$class_code = str_replace('[RPL]CLASS_NAME[/RPL]',$add_data['class_name'],$class_code);
			$class_code = str_replace('[RPL]DB_TABLE[/RPL]',$add_data['db_table'],$class_code);
			$class_code = str_replace('[RPL]COMMON_TITLE[/RPL]',$add_data['common_title'],$class_code);
			$class_code = str_replace('[RPL]TABLE_COLS[/RPL]',$in_table_fields,$class_code);
			$class_code = str_replace('[RPL]TITLES[/RPL]',var_export($titles, true),$class_code);
			$class_code = str_replace('[RPL]NEW_FIELDS[/RPL]',$new_form_fields,$class_code);
			$class_code = str_replace('[RPL]REQUIRED[/RPL]',$required_fields,$class_code);
			$class_code = str_replace('[RPL]ALL_FIELDS[/RPL]',var_export($fields, true),$class_code);
			$class_code = str_replace('[RPL]VALIDATION_FIELDS[/RPL]',$validation_fields,$class_code);
			$class_code = str_replace('[RPL]NO_DUPLICATE_FIELDS[/RPL]',$no_duplicate_fields,$class_code);
			file_put_contents("Class.$add_data[class_name].php", $class_code)  or die("Unable to write file!");;
/* 			$content = "some text here";
			$fp = fopen(DIR_ROOT . "admin//myText.txt","wb");
			fwrite($fp,$content);
			fclose($fp); */
            exit();
        }

		else if($_POST['action'] == 'get_db_table'){
			$data = $_POST;
			$jsFunction = '';
			$form = '';
			$portlet = '';
			$jsFunction = '';
			$tools = '';
			$other_hidden = '';
			$table = $data['data']['db_table'];
			$db = $data['data']['db_name'];
			$common_title = $data['data']['common_title'];
			$class_name = $data['data']['class_name'];
			$other_hidden .= '<input type="hidden" name="add_data[db_table]" value="'.$table.'">';
			$other_hidden .= '<input type="hidden" name="add_data[common_title]" value="'.$common_title.'">';
			$other_hidden .= '<input type="hidden" name="add_data[class_name]" value="'.$class_name.'">';
			$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table';";
			$query = mysqli_query($db_conx, $sql);
			$db_cols = array();
			while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
				$db_cols[] = $row['COLUMN_NAME'];
			}
			$db_titles =   array ();        
			//$f = $class->getExtendedForm(array('form_type'=>'new','return_form_as'=>'rows'));
			$next_buttons = '';        
			$common_title_stripped =preg_replace('/\s+/', '', $class->common_title);        
			$f['portlet-title'] = 'Database Details';
			$f['portlet-icon'] = '';
			$f['form-id'] = 'new'.$common_title_stripped.'Form';
			$f['submit-id'] = 'btnSubmit'.$common_title_stripped.'';
			$next_buttons .= $class->Button($f['submit-id'], 'green', 'Save '.$class->common_title.'','CreateClass({\'action\':\'create-class\'},\''.$f['form-id'].'\')');
			//$next_buttons .= '<button type="submit" class="btn green">Save Class Main</button>';
			$next_buttons .= $class->Button('btnAddAnother', 'green', 'Add Another','CommonFunc2({\'type\':\'get-another-field\'})');
			$f['form-action'] = 'Save'.$class->common_title.'';		
			$f['form-submit'] = true;		
			$f['form-submit-url'] = ''.$site_path.'CreateClass';		

			$f['buttons'] = $next_buttons;
			$f['form'] = '';
			$keep_track_number = 0;/// A number that increments with each loop
			$return['jsFunction'] = '';
			foreach($db_cols as $key=>$db_field){
				$keep_track_number++;
				$class->dbData = array();
				$class->dbID = 100;
				$class->dbData['field_name'] = $db_field;
				$class->dbData['keep_track'] = $keep_track_number;
				$class->dbData['label'] = isset($__all_titles[$db_field]) ? $__all_titles[$db_field] : '';
				if($class->dbData['label'] == '') {
					$class->dbData['label'] = ucfirst(implode(' ', array_map('ucfirst', explode('_', $db_field))));
				}
				$tempForm = $class->getExtendedForm(array('form_type'=>'edit','return_form_as'=>'rows'));
				$f['form'] .= 
				'
					<div class="row">
						'.$tempForm['form'].'						
					</div>
				';
				$return['tools'][$tempForm['unique_id']] = $tempForm['tools'];
				$return['jsFunction'] .= 
				"
					toolsAll['$tempForm[unique_id]'] = value.tools['$tempForm[unique_id]'];
				";
				
			}
			
			$f['form'] .= $other_hidden;
			$f['form'] .= '<button type="submit" class="btn green">Save Class</button>';
			$portlet = $class->GetPortletForm($f,false);
			$return['ajaxresult'] = true;
			$return['data'] = $data;
			$return['jsFunction'] .= 
			"
			console.log(value);
			$('#ajax-container').html(value.html);
			
			";
			$return['html'] = $portlet;
			echo json_encode($return);
			exit();
		} ///end main else
		
        else{
            $class->HandleAjaxCall($_POST);	
        }
		
	}	

	else
	{
        $jsFunction = '';
        $form = ''; 
        $portlet = '';  
      
        $jsFunction = '';    
        $tools = ''; 
		
        $table = 'joblist';
        $db = 'professionate';
		$selectDbForm = $class->getExtendedForm(array('form_type'=>'select_db','return_form_as'=>'portlet'));
		$toolsAll[$selectDbForm['unique_id']]  = $selectDbForm['tools'];
        //print_r($selectDbForm);
	} ///end main else
?>

<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
<?php include_once $tpl_path.'tplHead.php'; ?>
</head>
<!-- end::Head -->
    <!-- end::Body -->
	<body class="<?php echo get_body_class() ?>">
		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<?php include_once $tpl_path.'tplHeader3.php'; ?>
		<!-- begin::Body -->
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
				<?php include_once $tpl_path.'tplSidebar3.php'; ?>
				<div class="m-grid__item m-grid__item--fluid m-wrapper">
					<!-- BEGIN: Subheader -->
					<div class="m-subheader ">
						<div class="d-flex align-items-center">
							<div class="mr-auto">
								<h3 class="m-subheader__title ">
									Dashboard
								</h3>
							</div>
							<div>
								<span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
									<span class="m-subheader__daterange-label">
										<span class="m-subheader__daterange-title"></span>
										<span class="m-subheader__daterange-date m--font-brand"></span>
									</span>
									<a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
										<i class="la la-angle-down"></i>
									</a>
								</span>
							</div>
						</div>
					</div>
					<!-- END: Subheader -->
					<div class="m-content">
					<div class="container-fluid" id="main-container-fluid">
						<!--Begin::Section-->
						<div class="row">
						<?php echo $selectDbForm['html']; ?>
						<div class="container" id="ajax-container">
						<?php //echo $portlet; ?>	
						</div>						
						</div>
					</div>
					</div>
				</div>
			</div>
			<!-- end:: Body -->
			<?php include_once $tpl_path.'tplFooter.php'; ?>
		</div>
		<!-- end:: Page -->
		<?php include_once $tpl_path.'tplQuickSidebar.php'; ?>    

		<?php include_once $tpl_path.'tplQuickNav.php'; ?>
		<?php include_once $tpl_path.'tplFooterJs.php'; ?>    

		<div class="modal container animated zoomIn7 in" style="top:80px;" id="createClassModal" tabindex="-1" data-backdrop="static" data-keyboard="false" style="">
    <div class="modal-dialog" style="margin:0px;min-width:300px;width:auto;">
        <div class="modal-content z-depth-5">
            <div class="modal-header" style="padding:0px">
            <div class="modal-title fancy-modal-title" style="top: 20px;">
                <div class="caption">
                    <span class="bold uppercase">Class Config</span>
                    
                </div>
                
            <div class="modal-button-group pull-right">	
                <a href="javascript:;" class="btn btn-circle btn-icon-only fullscreen white"><i class=" fullscreen-iconn fa fa-expand"></i></a>									
                <a href="javascript:;" class="btn btn-circle btn-icon-only white"  data-dismiss="modal"><i class=" fa fa-close"></i></a>
            </div>	            

            </div>
            
            </div>
            <div class="modal-body" id="create-class-modal-body" style="padding:20px">
            </div>

            <div class="modal-footer">

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>	
<script>
var form = $('#table-form');
var tbody = $('#table-tbody');
var status = $('#status-message');
var ajaxContainer = $('#modalForm');
var action_buttons = [];
action_buttons = ['bulk_update_fields_header','btnBulkUpdate'];
var currentTotalRows = '';
var currentPagination = '1'; ///current value of the pagination page number
var validation_fields, validation_messages, icons, duplicates;
var toolsAll = <?php echo json_encode($toolsAll) ?>;
//var tools = <?php echo json_encode($tools) ?>;
var jsFunction = <?php echo json_encode($jsFunction) ?>;
var isReadyCommon = true;

$(document).ready(function(){
     eval(jsFunction);
    $('body').on( 'click', '.btnDelete', function () {
        $(this).confirmation({
            container: "body",
            btnOkClass: "btn btn-sm btn-success",
            btnCancelClass: "btn btn-sm btn-danger"
        }); 
      $(this).confirmation( 'show' );
    });

});

function Save(data,form){
	//calledFrom is where function is called from
	startLoading(); 
    var isReady = true; 
    var isValidated = true; 
    if (tools.hasOwnProperty('validation')) {    
        $.each(tools['validation'],function(tField,tOptions){
            var tVal = $('#'+tField+'').val();
         
            vCheck = ValidateFields(tVal,tOptions)
        
            if(vCheck.result === false){
                showErrorOnElement(tField,vCheck.message);
                isValidated = false;
            }        
        });
    }

    if (tools.hasOwnProperty('required')) {
        isReady = CheckRequiredAssoc(tools['required']);
    }

	if(isReadyCommon && isReady && isValidated) {	
		//var formData = $('#'+form+'').serializeArray();	
        if(typeof form !== 'undefined'){
            var formData = $('#'+form+'').serializeArray();
            if(typeof data !== 'undefined' && !$.isEmptyObject(data)){
                $.each(data,function(n,v){
                    formData.push({name:n, value:v});
                });
            }       
        }
        else{
            formData = data;
        } 
        console.log(formData);
        console.log(data);
			$.ajax({
			type:'POST',
			url: '',
			data: formData,
			success: function(val){
				var value = JSON.parse(val);
                console.log(value);
				if(value.debug){
					console.log(val);					
				}	
				if(value.ajaxresult == true) {
					if (value.hasOwnProperty('jsFunction')) {
						 eval(value.jsFunction);
					}
					else 
					{
						showMessage('success','Added', 'Success');							
					}
				}
				else {
					showMessage('error','Could Not Save', 'Error!');
					if (value.hasOwnProperty('jsFunction')) {
						 eval(value.jsFunction);
					}                    
				}
				stopLoading();				
			}
		});	

	}
	else {
		
		stopLoading();
	
	}
}

function CreateClass(data,form){
    ajaxElement = $('#create-class-modal-body');
	ajaxElement.html('');	
	//calledFrom is where function is called from
	startLoading(); 
    var isReady = true; 
    var isValidated = true; 
    if (tools.hasOwnProperty('validation')) {    
        $.each(tools['validation'],function(tField,tOptions){
            var tVal = $('#'+tField+'').val();
         
            vCheck = ValidateFields(tVal,tOptions)
        
            if(vCheck.result === false){
                showErrorOnElement(tField,vCheck.message);
                isValidated = false;
            }        
        });
    }

    if (tools.hasOwnProperty('required')) {
        isReady = CheckRequiredAssoc(tools['required']);
    }

	if(isReadyCommon && isReady && isValidated) {	
		//var formData = $('#'+form+'').serializeArray();	
        if(typeof form !== 'undefined'){
            var formData = $('#'+form+'').serializeArray();
            if(typeof data !== 'undefined' && !$.isEmptyObject(data)){
                $.each(data,function(n,v){
                    formData.push({name:n, value:v});
                });
            }       
        }
        else{
            formData = data;
        } 
        console.log(formData);
        console.log(data);
			$.ajax({
			type:'POST',
			url: '',
			data: formData,
			success: function(val){
                var html = '<div class="well col-md-12"><code>'+val+'</code></div>';
                $('#create-class-modal-body').html(html);
                ShowBsModal('createClassModal');
                stopLoading();
                
				var value = JSON.parse(val);
                console.log(value);

                
				if(value.debug){
					console.log(val);					
				}	
				if(value.ajaxresult == true) {
					if (value.hasOwnProperty('jsFunction')) {
						 eval(value.jsFunction);
					}
					else 
					{
						showMessage('success','Added', 'Success');							
					}
				}
				else {
					showMessage('error','Could Not Save', 'Error!');
					if (value.hasOwnProperty('jsFunction')) {
						 eval(value.jsFunction);
					}                    
				}
				stopLoading();				
			}
		});	

	}
	else {
		
		stopLoading();
	
	}
}



function Update(data,form){
	//calledFrom is where function is called from
	startLoading();
    var isReady = true; 
    var isValidated = true; 
    if (tools.hasOwnProperty('validation')) {    
        $.each(tools['validation'],function(tField,tOptions){
            var tVal = $('#'+tField+'').val();
            vCheck = ValidateFields(tVal,tOptions)

            if(vCheck.result === false){
                showErrorOnElement(tField,vCheck.message);
                isValidated = false;
            }        
        });
    }
   
    if (tools.hasOwnProperty('required')) {
        isReady = CheckRequiredAssoc(tools['required']);
    }  
    
	if(isReady && isValidated) {	
		//var formData = $('#'+form+'').serializeArray();
        if(typeof form !== 'undefined'){
            var formData = $('#'+form+'').serializeArray();
            if(typeof data !== 'undefined' && !$.isEmptyObject(data)){
                $.each(data,function(n,v){
                    formData.push({name:n, value:v});
                });
            }       
        }
        else{
            formData = data;
        }        
        console.log(formData);
			$.ajax({
			type:'POST',
			url: '',
			data: formData,
			success: function(val){
				var value = JSON.parse(val);
				if(value.debug){
					console.log(val);					
				}
				console.log(value);			
                if(value.ajaxresult == true) {
                    if (value.hasOwnProperty('jsFunction')) {
                         eval(value.jsFunction);
                    }
                    showSweetSuccess('Success','Updated Succesfully');
                    stopLoading();    
                }
                else {
                    showSweetError('Error','Could Not Update Details!!!');
                    stopLoading();      
                }
				stopLoading();				
			}
		});	

	}
	else {
		stopLoading();			
	}
}

function Delete(id, e) { ///Fetches a new row

    $(document).on("canceled.bs.confirmation",".btnDelete", function() {
        console.log("You canceled Delete")
    });
    $(document).on("confirmed.bs.confirmation",".btnDelete", function() {
            startLoading();

            $.ajax({
                type:'POST',
                url: '',
                data: {'action':'delete','id':id},
                success: function(val){
                    console.log(val);
                    var value = JSON.parse(val);                   
                    if(value.ajaxresult == true) {

                        $(e).closest('tr').remove();                        
                        if (value.hasOwnProperty('jsFunction')) {
                            console.log(jsFunction);
                             eval(value.jsFunction);
                        }
                        showSweetSuccess('Success','Deleted Succesfully');
                        stopLoading();    
                    }
                    else {
                        showSweetError('Error','Could Not Delete!!!');
                        stopLoading();      
                    }					
                },
                error:function(){
                
                    stopLoading();
                }		
            });

    });

}

function BulkDelete(e) { ///Fetches a new row
    $(".btnDelete").on("canceled.bs.confirmation", function() {
        console.log("You canceled Delete")
    });
    $(".btnDelete").on("confirmed.bs.confirmation", function() {
        startLoading();
        var formData = $('#table-form-wrapper').serializeArray();
        formData.push({name:'action', value:'delete-bulk'});

        $.ajax({
            type:'POST',
            url: '',
            data: formData,
            success: function(val){
            console.log(val);
            var value = JSON.parse(val);
                //$(e).closest('tr').remove();
            if(value.ajaxresult == true) {
                $.each(value.ids,function(i,id){
                    $('#tr-'+id+'').remove();
                    console.log(id);              
                });
                
                if (value.hasOwnProperty('jsFunction')) {
                     eval(value.jsFunction);
                }
                else 
                {
                    showMessage('success','Updated Succesfully', 'Success');							
                }
                $('#table-master-check').prop('checked',false);
                stopLoading();
            }
            

                stopLoading();					
            },
            error:function(){
            
                stopLoading();
            }		
        });
	});
}

function BulkUpdate(e) { ///Fetches a new row
	startLoading();
    var formData = $('#table-form-wrapper').serializeArray();
    formData.push({name:'action', value:'bulk-update'});
    console.log(formData);
	$.ajax({
		type:'POST',
		url: '',
		data: formData,
		success: function(val){
		console.log(val);
		var value = JSON.parse(val);
            console.log(value);			
            if(value.ajaxresult == true) {
                if (value.hasOwnProperty('jsFunction')) {
                     eval(value.jsFunction);
                }
                else 
                {
                    showMessage('success','Updated Succesfully', 'Success');							
                }
                $('#table-master-check').prop('checked',false);
                stopLoading();
            }
            else {
                showMessage('error','Could Not Update ', 'Error!');
				stopLoading();
            }				
		},
		error:function(){
		
			stopLoading();
		}		
	});
}

function getDependentFields(parent,field){
    var tempElement = tools['ajax_dependency'][field];
    App.blockUI({target:'#'+tempElement+'-holder'});
    var value = $('#'+field+'').val();
		$.ajax({
			type:'POST',
			url: '',
			data: {'parent':parent,'type':'get-dep-fields','value':value,'field':field},
			success: function(val){
				var value = JSON.parse(val);
				if(value.debug){
					console.log(val);					
				}
				console.log(value);			
                if(value.ajaxresult == true) {
                    if (value.hasOwnProperty('jsFunction')) {
                         eval(value.jsFunction);
                    }
                    //showSweetSuccess('Success','Updated Succesfully');
                    //stopLoading();    
                }
                else {
                    //showSweetError('Error','Could Not Update Details!!!');
                    //stopLoading();      
                }
				stopLoading();	
                App.unblockUI('#'+tempElement+'-holder');                
			}
		});	
}

$('body').on('keydown change blur','.monitor-input',function(e){
	var id = $(e.target).attr('id');
	var parent = $(e.target).data('unique_id');
    tools = toolsAll[parent];
    if(typeof tools['is_multiple_form'] !== 'undefined' && tools['is_multiple_form']){
        var tools_index = $(e.target).attr('data-tools-index');
        tools = tools['tools'][tools_index];
    } 
    ///check for required first
    ///check for validation
    ///check for dulpicate
    var is_field_empty = false;
    var is_field_validated = true;
    var not_duplicate = true;
    if(e.type == 'focusout'){     
        resetElementState(id);
        if($(this).is('[field-required]')) {
            var assocArr = [];
            assocArr = tools['required'];
            CheckRequiredAssoc(assocArr);
        // do something…
/*             if(typeof tools['required'][id]['conditional']['field'] === 'undefined'){ /// If Required is not conditional
                if($.trim($(this).val()) == '') {
                   showErrorOnElement(id,'This Field is Required');	 
                   is_field_empty = true;
                }
                else{
                    resetElementState(id);
                    is_field_empty = false;
                }                
            }
            else{ /// If Required is Conditional
                var depField = tools['required'][id]['conditional']['field'];
                if($.trim($(this).val()) == '') {
                   showErrorOnElement(id,'This Field is Required');	 
                   is_field_empty = true;
                }
                else{
                    resetElementState(id);
                    is_field_empty = false;
                }                 
            } */

        }
        if($(this).is('[field-validate]') && !is_field_empty){
                var elemVal = $(this).val();				
                var vOptions = tools['validation'][id];
                vCheck = ValidateFields(elemVal,vOptions);
                if(!vCheck['result']){
                    tools['validation'][id]['status'] = false;						
                    showErrorOnElement(id,vCheck['message']);	
                    is_field_validated = false;                    
                    //console.log(vCheck);	
                }
                else{
                    tools['validation'][id]['status'] = true;						
                    showSuccessOnElement(id,vCheck['message']);	   
                    is_field_validated = true;                    
                    //console.log(vCheck);					
                }  

        }
        if($(this).is('[field-check-duplicate]') && !is_field_empty && is_field_validated) {
            
            not_duplicate = CheckForDuplicateModule(tools.module,id);
        }
        if(!is_field_empty && is_field_validated && not_duplicate){
            resetElementState(id);
        }
    }

});

function UncheckMaster(){
    $('#table-master-check').prop('checked',true); 
    $('#table-master-check').trigger('click');
   
    $('#table-master-check').closest('.md-checkbox').removeClass('partial-check');    
}
</script>
	</body>
	<!-- end::Body -->
</html>
