<?php 
include_once(DIR_SITE_ROOT."bootstrap.php");
include_once(DIR_SITE_ROOT."common.php");
$__page_active = 'NewPage';
define('__PAGE_TYPE__', 'table');
$jsFunction = '';
$toolsAll = array();
//$module = (!IS_SUBFOLDER) ? get_current_url_array(1) : get_current_url_array(2);
$module = get_current_url_array(1);
include_once('Adapter.php');
$tableOptions = $class->getReportFields();
$encodedTableOptions = json_encode($tableOptions);
?>
<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
<?php include_once $tpl_path.'tplHead.php'; ?>
<link href="<?php echo get_core_theme_path() ?>assets/global/plugins/fancybox/dist/jquery.fancybox.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_core_theme_path() ?>assets/global/plugins/jcrop/css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/datatables.min.css" rel="stylesheet" type="text/css" />
<style>
.table td, .table th {
	text-align: center;
    padding: .75rem;
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
	max-width:100px;
	word-break: break-word;
}
.table.fixedHeader-floating td, .table.fixedHeader-floating th {
	text-align: center;
    padding: .75rem;
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
	max-width:100px!important;
	word-break: break-word;
}
table.dataTable.dtr-column>tbody>tr>td.control:before, table.dataTable.dtr-column>tbody>tr>th.control:before {
    top: 40%;
    left: unset;
    right: 0px;
    height: 25px;
    width: 25px;
    margin-top: -10px;
    margin-left: -10px;
    display: block;
    position: absolute;
    color: white;
    border: 2px solid white;
    border-radius: 14px;
    box-shadow: 0 0 3px #444;
    box-sizing: content-box;
    text-align: center;
    text-indent: 0 !important;
    font-family: 'Courier New', Courier, monospace;
    line-height: 25px;
    font-size: 25px;
    content: '+';
    background-color: #05dcef;
}
table.table-bordered.dataTable tbody th, table.table-bordered.dataTable tbody td {
    border-bottom-width: 0;
	width:auto;
    max-width: 100px!important;
	word-wrap: break-word;
	padding: .2rem;
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
		<?php //include_once $tpl_path.'tplMaterialSideBar.php'; ?>
		<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
			<?php include_once $tpl_path.'tplSidebar7.php'; ?>
			<div class="m-grid__item m-grid__item--fluid m-wrapper ">
				<!-- BEGIN: Subheader -->
				<div class="m-subheader d-none">
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
					<!--Begin::Section-->
					<div class="row">
						<div class="container-fluid" id="table-container-fluid">
							
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
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jcrop/js/jquery.Jcrop.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/fancybox/dist/jquery.fancybox.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/jquery.elevatezoom.js" type="text/javascript"></script>
<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/datatables.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jquery-jeditable/jquery.jeditable.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/Class.Table.Bs4.js?<?php echo time() ?>" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/checkbox-control.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/imageCropJs.js?<?php echo time() ?>" type="text/javascript"></script>

<script>
var form = $('#table-form');
var tbody = $('#table-tbody');
var status = $('#status-message');
var module = '<?php echo $module ?>';
var __MODULE__ = '<?php echo $module ?>';
var tools = [];
var toolsAll = [];
var toolsAll = <?php echo (json_encode($toolsAll)) ?>;
//toolsAll = '<?php echo json_encode($toolsAll) ?>;
var jsFunction = <?php echo json_encode($jsFunction) ?>;
var isReadyCommon = true;
var ajaxCallBacks = {};
var table1,tableOptns;
jQuery(document).ready(function() {
    eval(jsFunction);
	ajaxCallBacks.update = function(data){
		
	};
	ajaxCallBacks.save = function(data){
		
	};
	
	tableOptns = <?php echo ($encodedTableOptions) ?>;
	tableOptns['container'] = 'table-container-fluid';
	table1 = new Table(tableOptns);
	//table1.generateTable();
	var tableValue = table1.getTable();

	form = $('#table-form');
	tbody = $('#table-tbody');
	status = $('#status-message');
});

function newForm(data){
	var form1 = new FormGenerator(data);
	var value = form1.getForm();
	eval(value.jsFunction);
}

function editForm(data){
	var form1 = new FormGenerator(data);
	var value = form1.getForm();
	eval(value.jsFunction);
}

function prependTableRow(data){
	console.log(data);
	$col_data_temp = table1.createArrayForColumns(data['data']);
	$newRow = table1.createTableRow($col_data_temp,data.data);
	tbody.prepend($newRow);
}

function appendTableRow(data){
	tbody.append(data.html);
}

function replaceTableRow(data){
	var trid = data.id;
	$col_data_temp = table1.createArrayForColumns(data['data']);
	$newRow = table1.createTableTdOnly($col_data_temp,data.data);
	tbody.find('.tr-'+trid+'').html($newRow);
}

$(document).ready(function(){   
     eval(jsFunction);
     initial_table = $("#main-container-fluid").html();
});

function UncheckMaster(){
    $('#table-master-check').prop('checked',false); 
    $('#table-master-check').trigger('change');
	HideBulkUpdateRow();
    $('#table-master-check').closest('.md-checkbox').removeClass('partial-check');
}


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
			if($.trim($(this).val()) === ''){
				is_field_empty = true;
				showErrorOnElement(id,tools['required'][id]['message']);
			}
            //CheckRequiredAssoc(assocArr);
        }
        if($(this).is('[field-validate]') && !is_field_empty){
                var elemVal = $(this).val();
                var vOptions = tools['validation'][id];
                vCheck = ValidateFields(elemVal,vOptions,id);
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

/* var str = "[VAL]Bob[/VAL], I'm [VAL]20[/VAL] years old, I like [VAL]programming[/VAL].";

var result = str.match(/\[VAL\](.*?)\[\/VAL\]/g).map(function(val){
	console.log(val);
   return val.replace(/\[\/?VAL\]/g,'');
});
console.log(result); */

/* var str = "{Bob}, I'm {20} years old, I like {programming}.";

var result = str.match(/\{(.*?)\}/g).map(function(val){
	console.log(val);
   return val.replace(/\{/g,'').replace(/\}/g,'');
   //return val;
}); 
console.log(result);*/
</script>


	</body>
	<!-- end::Body -->
</html>
