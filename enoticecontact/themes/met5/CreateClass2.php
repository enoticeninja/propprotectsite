<?php
include_once("bootstrap.php");
include_once("common.php");
require_once(DIR_OTHER_CLASSES."Class.CreateClass2.php");
//$module = $arr[2];
$__page_active = '';
$__sub_page_active = '';
$jsFunction = '';    
$tools = ''; 
$username = $_SESSION[get_session_values('username')];
$class = new CreateClass2($db_conx);
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

	if (isset($_POST['action'])){
        $data = $_POST;
        $class->handleAjaxCall($data);
        exit();
	}	

	else
	{
        $selectDbForm = $class->selectDatabaseForm(array('action'=>'select_db','return_form_as'=>'form_rows_with_buttons'));
        $encodedDbOptions = json_encode($selectDbForm);
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
			<?php include_once $tpl_path.'tplHeader7.php'; ?>
		<!-- begin::Body -->
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
				<?php include_once $tpl_path.'tplSidebar7.php'; ?>
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
						<div class="container" id="select-db-container">

                        </div>
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
     var dbOptions = <?php echo($encodedDbOptions) ?> ;

var custFormClass = new FormGenerator(dbOptions);
custFormClass.actionCallBacks = {
    'save': 'doNothing',
    'update': 'doNothing'
};
var custForm = custFormClass.getSingleForm();
$('#select-db-container').html(custForm.form);
custFormClass.executeAllJs();

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
