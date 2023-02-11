<?php
include_once("bootstrap.php");
include_once("common.php");
$__page_active = '';
$__sub_page_active = '';
$previous_page_link = '';
$previous_page = '';
define('__PAGE_TYPE__', 'collapsible-list');
$username = $_SESSION[get_session_values('username')];
$title = 'Product Groups';
//$class = new ManageProductGroup($db_conx);
$module = get_current_url_array(1);
$return['ajaxresult'] = true;
$return['ajaxmessage'] = 'success';
/////// CHANGE HERE FOR NEW MODULES
$i = 1;
$code = ''; 
$accCode = ''; 
/* $parent = 'offer_group';
$parent_id_key = 'offer_group_id';
$child = 'offer_group_item';
$child_id_key = 'offer_group_item_id';
$inner_join = 'offer';
$inner_join_key = 'offer_id'; */
include_once("Adapter.php");
$parent = $class->dbTable;
if(isset($_POST['on_page_action'])){
    $data = $_POST;
    if($data['on_page_action'] == 'reorder-number'){
        $all = json_decode($data['sort-order'], true);
    }
    else if($data['on_page_action'] == 'rearrange_child'){
			//print_r($data);
		$sql = "INSERT INTO `$class->dbTable` (id,sort_order) VALUES ";
		$sqlArr = array();
		foreach($_POST['sequence'] as $id=>$order) {
			$sqlArr[] = "('$id','$order')";
		}
		$sql .= implode(',',$sqlArr);
		$sql .= " ON DUPLICATE KEY UPDATE sort_order=values(sort_order)";
		$query = mysqli_query($db_conx,$sql);
		//$return['sql'] = $sql;
		$return['ajaxresult'] = true;
		$return['error'] = mysqli_error($db_conx);
		//$return['jsFunction'] = "console.log(value)";
		echo json_encode($return);
		exit();
    }
		//print_r($data['on_page_action']);
    else if($data['on_page_action'] == 'rearrange_parent'){
			//print_r($data);
		$sql = "INSERT INTO `$class->dbTable` (id,sort_order) VALUES ";
		$sqlArr = array();
		foreach($_POST['sequence'] as $id=>$order) {
			$sqlArr[] = "('$id','$order')";
		}
		$sql .= implode(',',$sqlArr);
		$sql .= " ON DUPLICATE KEY UPDATE sort_order=values(sort_order)";
		$query = mysqli_query($db_conx,$sql);
		//$return['sql'] = $sql;
		$return['ajaxresult'] = true;
		$return['error'] = mysqli_error($db_conx);
		//$return['jsFunction'] = "console.log(value)";
		echo json_encode($return);
		exit();
    }
	else{
		$return['message'] = 'on_page_action not defined';
		$return['data'] = $_POST;
		$return['ajaxresult'] = true;
		echo json_encode($return);
		exit();
	}
}
else{
    //exit();
}



$custom_category_id  = (!IS_SUBFOLDER) ? strtolower(get_current_url_array(1)) : strtolower(get_current_url_array(2));
$sql = "SELECT * FROM `$parent` ORDER BY sort_order ASC";
$query = mysqli_query($db_conx,$sql);
$all_data = array();
$j = 0;
while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
	$all_data[$j] = $row;
    if($row['status'] == '1'){
        $tempCode = '<li class="collapsible-empty-li"></li>';
        $tempCode = '';
        $id = $row['id'];
        $sqlSec = 
        "SELECT main.*,sec.name as name
        FROM $child as main
        INNER JOIN $inner_join as sec ON sec.id = main.$inner_join_key
        WHERE main.$parent_id_key='$id'
        ORDER BY main.sort_order ASC
        ";	
		//print_r($sqlSec);
        $querySec = mysqli_query($db_conx,$sqlSec);
        print_r(mysqli_error($db_conx));
		$i = 1;
        while($rowSec = mysqli_fetch_array($querySec,MYSQLI_ASSOC)){
			$all_data[$j]['child_rows'][] = $rowSec;
			//print_r($rowSec);
			$rowSec['module'] = $module;
            $rowSec['child_module'] = $child;
			$rowSec['parent_module'] = $parent;
			$rowSec['parent_fk'] = $parent_id_key;
            $rowSec['i'] = $i;
            $rowSec['parent_id'] = $row['id'];
            $rowSec['__list_type__'] = 'secondary';
            //$return = $form->getNestableCollapsibleList($rowSec);
            //$tempCode .= $return['newrow'];
        }
        
		$row['i'] = $i;
        $row['parent_module'] = $parent;
		$row['parent_fk'] = $parent_id_key;
		$row['child_module'] = $child;
        $row['__list_type__'] = 'primary';
        $row['__child_list__'] = $tempCode;
        //$return = $form->getNestableCollapsibleList($row);
        //$code .= $return['newrow'];
    }
    else{
		$row['i'] = $i;
        $row['parent_module'] = $parent;
		$row['parent_fk'] = $parent_id_key;
		$row['child_module'] = $child;
        $row['__list_type__'] = 'primary';
        $row['__child_list__'] = '';
        //$return = $form->getNestableCollapsibleList($row);
        //$code .= $return['newrow'];
    }
	
	
	$i++;
	$j++;
}	
?>
<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
<?php include_once $tpl_path.'tplHead.php'; ?>
<style>
.dd-handle{
    display: inline-block;
    background-color: #ef9905!important;
    box-shadow: -5px 5px 7px 0px rgb(183, 116, 8);
    width: 30px;
    height: 30px;
    border-radius: 50%;
    text-align: center;
    line-height: 30px;
    vertical-align: middle;
    color: #fff;
	cursor: grab;
}
.dd-handle i{
}
</style>
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
                    <div class="row hidden">
                        <div class="col-md-12">
                            <form id="reorder-form" onSubmit="return false;">
                            <input type="hidden" name="on_page_action" value="sort-menu">
                            <textarea id="nestable_list_1_output" name="sort-order" class="form-control col-md-12 margin-bottom-10"></textarea>
                            </form>
                        </div>
                    </div>
                                 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="m-portlet m-portlet--full-height">
                                <div class="m-portlet__head">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<h3 class="m-portlet__head-text">
													Offer Groups
												</h3>
											</div>
										</div>
										
										<div class="m-portlet__head-tools">
                                        <div class="btn-group " id="nestable_list_menu">
                                            <a href="javascript:;"  class="btn bg-glossy-info"  id="collAddPrimary"><i class="fa fa-plus"></i> Add </a>
                                        </div>
										</div>										
                                </div>
                                <div class="portlet-body ">
                                    <div class="dd container" id="nestable_list_1">
                                        <ul class="dd-list m-accordion m-accordion--bordered m-accordion--solid" role="tablist" id="nestable_main_ol">
                                        <?php //echo $code ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    		
			</div>
				
					<!--Begin::Section-->
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
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jquery-nestable/jquery.nestable.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/Class.Accordion.js" type="text/javascript"></script>
<style>
.portlet-sortable-placeholder{
    margin-top:30px;
    border:5px dashed #c3bcbc;!important
    z-index:999999;
	list-style:none;
}
</style>	
<script>
var $code = '';
var $module = '<?php echo ($module)?>';
var $parent = '<?php echo ($parent)?>';
var $parent_id_key = '<?php echo ($parent_id_key)?>';
var $child = '<?php echo ($child)?>';
var $child_id_key = '<?php echo ($child_id_key)?>';
var $inner_join = '<?php echo ($inner_join)?>';
var $inner_join_key = '<?php echo ($inner_join_key)?>';
var all_data = JSON.parse('<?php echo json_encode($all_data,true)?>');
	console.log(all_data);
var PortletDraggable = function() {
    return {
        init: function(){
            jQuery().sortable && $("#nestable_main_ol").sortable(draggable_opts_primary);
        }
    }
}();

function removeCollapsiblePrimary(value){
	$('#collapsible-primary-'+value['id']+'_head').remove();
}

function removeCollapsibleSecondary(value){
	$('#collapsible-primary-'+value['id']+'_head').remove();
}
function prependCollapsiblePrimary(value){
	var data = value.data;
	console.log(data);
	data['i'] = 1;
	data['parent_module'] = $parent;
	data['parent_fk'] = $parent_id_key;
	data['child_module'] = $child;
	data['__list_type__'] = 'primary';
	data['__child_list__'] = '';
	$return = getNestableCollapsibleList(data);
	var $code = $return['newrow'];
	$('#nestable_main_ol').prepend($code);
}

function prependCollapsibleSecondary(value){
	var data = value.data;
	console.log(data);
	data['module'] = $module;
	data['child_module'] = $child;
	data['parent_module'] = $parent;
	data['parent_fk'] = $parent_id_key;
	data['i'] = 1;
	data['parent_id'] = $row['id'];
	data['__list_type__'] = 'secondary';
	$return = getNestableCollapsibleList(data);
	var $tempCode = $return['newrow'];
	$('#collapsible-primary-container-'+data[$parent_id_key]+'').prepend($tempCode);
}        
jQuery(document).ready(function(){

	$.each(all_data,function($key,$row){
		var $tempCode = '';
		if(isset($row['child_rows'])){
			var $i = 1;
			$.each($row['child_rows'],function($key,$rowSec){
				$rowSec['module'] = $module;
				$rowSec['child_module'] = $child;
				$rowSec['parent_module'] = $parent;
				$rowSec['parent_fk'] = $parent_id_key;
				$rowSec['i'] = $i;
				$rowSec['parent_id'] = $row['id'];
				$rowSec['__list_type__'] = 'secondary';
				$return = getNestableCollapsibleList($rowSec);
				$tempCode += $return['newrow'];
				$i++;
			});
		}
		
		$row['i'] = $i;
        $row['parent_module'] = $parent;
		$row['parent_fk'] = $parent_id_key;
		$row['child_module'] = $child;
        $row['__list_type__'] = 'primary';
        $row['__child_list__'] = $tempCode;
        $return = getNestableCollapsibleList($row);
        $code += $return['newrow'];
		$('#nestable_main_ol').html($code);
	});
	$(document).on('click','#collAddPrimary',function(){
		let data = {};
		data['data'] = {'action':'new','module':$module};
		data['isAjax'] = true;
		data['e'] = this;
		let formClass = new FormGenerator(data);
		formClass.actionCallBacks['save'] = 'prependCollapsiblePrimary';
	});	

	
    var isDragging = false;
	{
	var draggable_opts_primary = {
		items: ".sortable-item-parimary",
		opacity: 1,
		handle: ".dd-handle-primary",
		coneHelperSize: true,
		placeholder: "portlet-sortable-placeholder",
		forcePlaceholderSize: true,
		tolerance: "pointer",
		helper: "clone",
		cancel: ".portlet-sortable-empty, .portlet-fullscreen",
		revert: 250,
		scroll: true,
		dropOnEmpty : true,
		scrollSensitivity: 10,
		update: function(event, ui){
			//var oldParent = $(ui.item[0]).data('parent');
			//var newParent = $(ui.item[0].parentElement).data('id');
			//var oldParentElem = $('#collapsible-primary-container-'+oldParent);
			//var newParentElem = $(ui.item[0].parentElement);
			//console.log('Old Parent: '+oldParent);
			//console.log('New Parent: '+newParent);
            var sortedIDs = $(this).sortable( "toArray" );
			var sequence = {};
			$.each(sortedIDs,function(i,id){
				sequence[$('#'+id).data('id')] = i+1;
			});
			formData = {};
			formData['sequence'] = sequence;
			formData['module'] = '<?php echo $parent ?>';
			formData['on_page_action'] = 'rearrange_parent';
			console.log(formData);
			ajax(formData);
		}
    };
	
    jQuery().sortable && $("#nestable_main_ol").sortable(draggable_opts_primary);

	}
	
	{
	var draggable_opts_secondary = {
		items: ".sortable-item-secondary",
		opacity: 1,
		handle: ".dd-handle-secondary",
		coneHelperSize: true,
		placeholder: "portlet-sortable-placeholder",
		forcePlaceholderSize: true,
		tolerance: "pointer",
		helper: "clone",
		cancel: ".portlet-sortable-empty, .portlet-fullscreen",
		revert: 250,
		scroll: true,
		dropOnEmpty : true,
		scrollSensitivity: 10,
		update: function(event, ui){
			//var oldParent = $(ui.item[0]).data('parent');
			//var newParent = $(ui.item[0].parentElement).data('id');
			//var oldParentElem = $('#collapsible-primary-container-'+oldParent);
			//var newParentElem = $(ui.item[0].parentElement);
			//console.log('Old Parent: '+oldParent);
			//console.log('New Parent: '+newParent);
            var sortedIDs = $(this).sortable( "toArray" );
			var sequence = {};
			$.each(sortedIDs,function(i,id){
				sequence[$('#'+id).data('id')] = i+1;
			});
			formData = {};
			formData['sequence'] = sequence;
			formData['module'] = '<?php echo $child ?>';
			formData['on_page_action'] = 'rearrange_child';
			console.log(formData);
			ajax(formData);
		}
    };
	
	jQuery().sortable && $(".sortable-primary").sortable(draggable_opts_secondary);
	}
});
</script>
<script>
$("body").on("click",'a.btnExpandCollapse', function () {
    
    if ($(this).data('action') =='collapse-all'){
        $(this).text('Expand All');
        $(this).data('action','expand-all');
    }
    else  if ($(this).data('action') =='expand-all'){
        $(this).text('Collapse All');
        $(this).data('action','collapse-all');
    }
  
});







var tools = [];
var toolsAll = [];
//var tools = JSON.parse('<?php //echo json_encode($form['tools']) ?>');
var jsFunction = '';
var isReadyCommon = true;
$('body').on('keydown change blur','.monitor-input',function(e){
	var id = $(e.target).attr('id');
	var parent = $(e.target).data('unique_id');
	if($(this).hasClass('bs-select')){ ////// IF BOOTSTRAP SELECT
		id = $(this).closest('.bootstrap-select').find('select').attr('id');
		parent = $('#'+id).data('unique_id');
	}
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

</script> 
</body>
	<!-- end::Body -->
</html>
