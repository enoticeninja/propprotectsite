<?php
include_once("bootstrap.php");
include_once("common.php");
include_once(DIR_CORE_CLASSES."class_database.php");
$paper_theme_path = $site_path.'themes/'.$theme.'/paper/';
$paper_tpl_path = 'tpl/';

$__page_active = '';
$__sub_active = '';
$table = new TableOld();
$module = (!IS_SUBFOLDER) ? get_current_url_array(1) : get_current_url_array(2);
//print_r($_GET);
$rowsCountDisplay = 10;
if(isset($module)){
    switch ($module) {
        case 'ProductCategory':
        case 'productcategory':
            $class = new ProductCategory($db_conx);
            if(isset($_POST['currentParent'])) $class->setNestedVars($_POST);
            break;           
        default:
            header('Location: '.$site_path.'DashBoard');
            exit();
            break;
    }
}

$username = $_SESSION[get_session_values('username')];
$db = new Database();
$db->db_conx = $db_conx;
$title = $class->common_title;

$table->rowsCountDisplay = $rowsCountDisplay;
$table->filterRows = '';
$table->actions = '
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



///CHANGE HERE FOR A NEW PAGE 
$sql = "SELECT * FROM $class->dbTable"; 
////CHANGE HERE FOR A NEW PAGE

$col_data_buttons = array();
	if (isset($_POST['type'])){
		$class->HandleAjaxCall($_POST);	
	}
	else if (isset($_POST['action'])){

		$class->HandleAjaxCall($_POST);	
	}	
	else 
	{
        if(isset($_GET['currentParent'])){
            $currentParent = $_GET['currentParent'];
            $sql = "SELECT * FROM $class->dbTable WHERE id='$currentParent' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            $num_rows = mysqli_num_rows($query);
            if($num_rows > 0){
                $row = mysqli_fetch_array($query, MYSQLI_ASSOC);
                $nLeft = $row['nleft'];
                $nRight = $row['nright'];
                $currentLevelDepth = $row['level_depth'] + 1;
                $class->getTableFieldsCustom($currentParent,$currentLevelDepth,$nLeft,$nRight);                
            } 
            else{
                $class->getTableFieldsCustom(null,0,null,null);                
            }            
        }
        else{
            $class->getTableFieldsCustom(null,0,null,null);
        }


		$ret = $class->generateTable();
        $jsFunction = $ret['jsFunction'];
        $last_id = $ret['last_id'];

    
	} ///end main else
?>
<?php 
///messages id : status-message
///tbody id : table-body
///form id : table-form
///table id : auto-table
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
 
 <?php include $paper_tpl_path.'tplHead.php' ?>

    <link href="<?php echo get_core_theme_path() ?>assets/global/plugins/jcrop/css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo get_core_theme_path() ?>assets/pages/css/image-crop.min.css" rel="stylesheet" type="text/css" />	
    <link href="<?php echo get_core_theme_path() ?>assets/global/plugins/bootstrap-summernote/summernote.css" rel="stylesheet" type="text/css" /> 
<style>
.mt-actions{
	overflow:visible;
}

.mt-actions .mt-action .mt-action-body {
    padding-left: 15px;
    position: relative;
    overflow: visible;
}

.mt-actions .h1,.mt-actions .h2, .mt-actions .h3, .mt-actions h1, .mt-actions h2, .mt-actions h3 {
    margin-top: 0px;
}

.quick-nav-overlay2 {
    display: none;
    top: 50%;
    width: 300px;
    min-height: 400px;
    right: 10px;
    margin-top: -280px;
    position: fixed;
    z-index: 10101;
    background: transparent;
	border-radius:50px;
}

.quick-nav.nav-is-visible + .quick-nav-overlay2 {
    background: rgba(0, 0, 0, 0.8);
    display: block;
    -webkit-transition: background .7s ease-out;
    -moz-transition: background .7s ease-out;
    -o-transition: background .7s ease-out;
    transition: background .7s ease-out;
}

.md-check-all-boxes .box{
    border-radius:50%;
}

.table>tbody>tr>td {
    padding:0px;
    vertical-align:middle;
    width:auto;
    padding-left:8px;
    max-width: 100px;
    word-wrap: break-word;
}

.recently-edited{
-webkit-box-shadow: 0 5px 11px 0 rgba(11, 173, 189, 0.36), 0 4px 15px 0 rgba(6, 127, 118, 0.15);
-moz-box-shadow:    0 5px 11px 0 rgba(11, 173, 189, 0.36), 0 4px 15px 0 rgba(6, 127, 118, 0.15);
box-shadow:         0 5px 11px 0 rgba(11, 173, 189, 0.36), 0 4px 15px 0 rgba(6, 127, 118, 0.15);
box-shadow: 0 5px 11px 0 rgba(11, 173, 189, 0.36), 0 4px 15px 0 rgba(6, 127, 118, 0.15);

}

#bulk-update-row{
-webkit-box-shadow: 0px 0px 111px 0px rgba(5,5,5,1);
-moz-box-shadow: 0px 0px 111px 0px rgba(5,5,5,1);
box-shadow: 0px 0px 111px 0px rgba(5,5,5,1);
}

.modal {
    top: 50px;
}

.portlet .actions .md-checkbox {
       display: inline;
       padding-right:20px
}

.page-breadcrumb{
    list-style:none;
}
.page-bar .page-breadcrumb>li {
    display: inline-block;
}

.page-bar {

}
</style>

	<script>
	var theme_path = '<?php echo $theme_path ?>';
	</script>
    

</head>

<body class="<?php echo get_body_class() ?> has-material-sidebar">
        

 <?php include $paper_tpl_path.'tplHeader.php' ?>
 <div class="page-container">
<?php include ''.$tpl_path.'tplMaterialSidebar.php'; ?>
	<div class="page-content-wrapper">	
		<div class="page-content">
			<div class="container-fluid" id="main-container-fluid">
                    <div class="page-bar" style="margin-top:20px">
                        
                        
                        <?php 
                        if(isset($currentParent)){
                            $parents = $class->getAllParents($currentParent);
                            $currentUrl = strtok($_SERVER["REQUEST_URI"],'?');
                            $breadcrumbs = '<ul class="page-breadcrumb" style="">';
                                foreach($parents as $parent){
                                    if($parent['id'] != $currentParent){
                                        $breadcrumbs .= 
                                        '
                                        <li>
                                            <a href="'.$currentUrl.'?currentParent='.$parent['id'].'">'.$parent['name'].'</a>
                                            /
                                        </li>
                                        ';                                            
                                    }

                                }
                                $breadcrumbs .= '<li>'.$parents[$currentParent]['name'].'</li>';
                                $breadcrumbs .= '</ul>';
                                echo $breadcrumbs;                                    
                        }
                        ?>
       
                    </div>              
                <form id="table-form-wrapper" onSubmit="return false;">   
          
				<?php echo $table->getTable(); ?>	
				<?php //echo $portlet; ?>	
                </form>
          
			</div>
		</div>
	</div>
    
<?php include ''.$tpl_path.'tplFooter.php'; ?>
    <!-- Load site level scripts -->
<?php include $paper_tpl_path.'tplFooterJs.php' ?>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jcrop/js/jquery.Jcrop.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/bootstrap-summernote/summernote.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/pages/scripts/components-editors.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/pages/scripts/portlet-draggable.min.js" type="text/javascript"></script>


<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/checkbox-control.js" type="text/javascript"></script>

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
var tools = [];
//var tools = JSON.parse('<?php //echo json_encode($form['tools']) ?>');
var jsFunction = <?php echo json_encode($jsFunction) ?>;
var isReadyCommon = true;

/* $('#auto-table tr td').not('.td-checkbox, .td-actions').click(function(){
  //some operation
});
 */
function RouteToAnotherLevel(currentParent){
    window.location = window.location.href = window.location.pathname + "?currentParent=" + currentParent;    
}



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
            if(typeof data !== 'undefined' && !($.isPlainObject(data))){
                console.log($.isPlainObject(data));
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
                console.log(val);
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
                    RouteToAnotherLevel(value.parent);
                    
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

function Delete(module,id, e) { ///Fetches a new row

    $(document).on("canceled.bs.confirmation",".btnDelete", function() {
        console.log("You canceled Delete")
    });
    $(document).on("confirmed.bs.confirmation",".btnDelete", function() {
            startLoading();

            $.ajax({
                type:'POST',
                url: '',
                data: {'module':module,'action':'delete','id':id},
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

function BulkDelete(e,action) { ///Fetches a new row

    if(typeof action === 'undefined'){
        action = 'delete-bulk';
    }
    $(".btnDelete").on("canceled.bs.confirmation", function() {
        console.log("You canceled Delete")
    });
    $(".btnDelete").on("confirmed.bs.confirmation", function() {
        startLoading();
        var formData = $('#table-form-wrapper').serializeArray();
        formData.push({name:'action', value:action});

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

$('body').on('keydown change blur','form :input:not(:checkbox)',function(e){
	var id = $(e.target).attr('id');
    if(id == 'password') return;
    if(id == 'password2') return;
    if(id == 'users-password') return;
    if(id == 'users-password2') return;
	//var check_dup = tools['duplicate'][id]['allowed'];

    //var dup = tools['duplicate'];
    //console.log(tools);
	if(tools.hasOwnProperty('duplicate') && !tools['duplicate'].hasOwnProperty(id)){
		if(e.type == 'focusout'){
			if(tools.hasOwnProperty('validation') && tools['validation'].hasOwnProperty(id)){
				var elemVal = $('#'+id+'').val();				
				var vOptions = tools['validation'][id];
				vCheck = ValidateFields(elemVal,vOptions);
				if(!vCheck['result']){
					tools['validation'][id]['status'] = false;						
					showErrorOnElement(id,vCheck['message']);				
					//console.log(vCheck);	
				}
				else{
					tools['validation'][id]['status'] = true;						
					showSuccessOnElement(id,vCheck['message']);	                 
					//console.log(vCheck);					
				}
			}
            else{
                resetElementState(id);                 
                
            }
        }
		else{		
            resetElementState(id);  
		}
	}
	else
	{
        if(e.type == 'focusout' && tools.hasOwnProperty('duplicate')){
            CheckForDuplicate(id);
        }
		else{		
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


<!-- END JAVASCRIPTS -->

  

    </body>

</html>