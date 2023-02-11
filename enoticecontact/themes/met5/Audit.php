<?php
include_once DIR_SITE_ROOT . "bootstrap.php";
include_once DIR_SITE_ROOT . "common.php";
$__page_active = 'NewPage';
$__sub_page_active = 'NewPage';
define('__PAGE_TYPE__', 'table');
$jsFunction = '';
$toolsAll = array();
//$module = (!IS_SUBFOLDER) ? get_current_url_array(1) : get_current_url_array(2);
$module = 'punch';
$channel_id = get_current_url_array(1);
include_once 'Adapter.php';
/* $tableOptions = $class->getTableFields();
$encodedTableOptions = json_encode($tableOptions); */

/* $formOptions = $class->getNewForm(array('return_form_as'=>'rows','pull_data'=>true));
$encodedFormOptions = json_encode($formOptions); */
$getDateAndChannelForm = $class->getDateAndChannelForm(array('return_form_as'=>'rows'));
$encodedGetDateAndChannelForm = json_encode($getDateAndChannelForm);

?>
<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
<?php include_once $tpl_path . 'tplHead.php';?>
<link href="<?php echo get_core_theme_path() ?>/assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_core_theme_path() ?>/assets/global/plugins/jquery-minicolors/jquery.minicolors.css" rel="stylesheet" type="text/css" />
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
	/* 	.m-dropdown.m-dropdown--align-center .m-dropdown__wrapper {
			left: 50%;
			width: auto;
		}	 */
	.input-icon {
		box-shadow: -5px 5px 7px 0px rgb(193, 197, 197);
	}
	.modal-open .modal {
		overflow-x: hidden;
		overflow-y: hidden;
	}	
</style>
<style>
    .popover.confirmation{
        max-width:600px;
        box-shadow: -5px 6px 12px 0px #545656
    }
    .popover.confirmation.blue{
        box-shadow: -5px 6px 12px 0px #085088;
    }

    .popover.confirmation.blue .popover-header {
        background: linear-gradient(45deg, #0095ff, #0adef7) !important;    
    }
    .bootstrap-timepicker-widget.dropdown-menu.open {
        display: none;
    }
    .minicolors {
        position: relative;
        display: inline-block;
    }
    .minicolors-theme-bootstrap .minicolors-swatch {
        z-index: 2;
        margin: 0 auto;
        top: unset;
        bottom: 1px;
        left: 0px;
        width: 100%;
        height: 10px;
        border-radius: 0px;
    }
    .icon-color-bar{
        font-size: 1.8rem!important;
        top: 40%!important;
    }
    .excel-table{
        table-layout: auto; 
        width: 100%;
    }
    
    .excel-cell-select{
        color: inherit;
        background: transparent;
        height: 100%;
        /* width: 50%; */
        position: absolute;
        left: 0;
        top: 0;        
        width:100%;
        text-align: center;
        z-index: 99;
        border:0px;
    }
    
    .excel-cell-input{
        color: inherit;
        background: transparent;
        height: 100%;
        /* width: 50%; */
        position: absolute;
        left: 0;
        top: 0;        
        width:100%;
        text-align: center;
        z-index: 99;
        border:0px;
    }
    .excel-td{
        padding: 0px;
    }
    .excel-table th:first-child {
        border-left: 1px solid #ccc;
    }
    .excel-table th {
        border-right: 1px solid #ccc;
        border-top: 1px solid #ccc;
        text-align: center;
        font-size: 17px;
        background: #999999;
        color: #fff;
    }
    .excel-table th, .excel-table td {
        /* border-right: 1px solid #CCC;
        border-bottom: 1px solid #CCC;  */   
    }
    .excel-td {
        white-space: nowrap;
        font-style: oblique;
        font-weight: bold;
        overflow: hidden;
        position: relative;
        height: 20px;
        text-align: center;
        font-size: 16px;
        border: 1px solid #ccc;
        /* border-right: 1px solid #ccc;
        border-bottom: 1px solid #ccc;  */       
    }
    .excel-td-client_id{
        max-width:100px;
    }
    .excel-td-campaign_id{
        max-width:100px;
    }
    .excel-td-program_id{
        max-width:100px;
    }
    .excel-tr {
        /* background-color: #EEEEEE; */
        text-align: center;
        font-size: 16px;
        height: 20px;
    }
    .excel-tr.new-tr {
        height: 30px;
    }

    .excel-table-context-menu.dropdown-menu {
        padding: 0 0;
        border: 1px solid #ccc;
    }
    .excel-table-context-menu.dropdown-menu > .dropdown-item {
        padding: 0.25rem 1.5rem;
    }

    .excel-selected-cell{
        border:4px solid green;
    }
    /* .excel-selected-tr{
        border:2px dotted #ccc;
    } */
    .excel-selected-tr {
        width: max-content;
        border: 2px dashed #999;
        /* background: linear-gradient(90deg, #999 50%, transparent 50%), linear-gradient(90deg, #999 50%, transparent 50%), linear-gradient(0deg, #999 50%, transparent 50%), linear-gradient(0deg, #999 50%, transparent 50%);
        background-repeat: repeat-x, repeat-x, repeat-y, repeat-y;
        background-size: 15px 4px, 15px 4px, 4px 15px, 4px 15px;
        padding: 10px;
        animation: border-dance 30s infinite linear; */
        }

        @keyframes border-dance {
            0% {
                background-position: 0 0, 100% 100%, 0 100%, 100% 0;
            }
            100% {
                background-position: 100% 0, 0 100%, 0 0, 100% 100%;
            }
        }    
    textarea:focus, input:focus, select:focus{
        outline: none;
    }

    .excel-td .form-control{
        /* height: 30px!important; */
        padding:0 0!important;
    }
</style>
</head>
<!-- end::Head -->
    <!-- end::Body -->
<body class="<?php echo get_body_class() ?>">
	<!-- begin:: Page -->
	<div class="m-grid m-grid--hor m-grid--root m-page">
		<?php include_once $tpl_path . 'tplHeader3.php';?>
	<!-- begin::Body -->
		<?php //include_once $tpl_path.'tplMaterialSideBar.php'; ?>
		<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
			<?php include_once $tpl_path . 'tplSidebar3.php';?>
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
                    <div class="m-accordion m-accordion--default m-accordion--toggle-arrow" id="m_accordion_1" role="tablist">
                        <!--begin::Item-->
                        <div class="m-accordion__item">
                            <div class="m-accordion__item-head" role="tab" id="m_accordion_1_item_1_head" data-toggle="collapse"
                                href="#m_accordion_1_item_1_body" aria-expanded="true" style="background-color: #fff;">
                                <span class="m-accordion__item-icon">
                                    <i class="fa flaticon-analytics"></i>
                                </span>
                                <span class="m-accordion__item-title">
                                    Select Date and Channel
                                </span>
                                <span class="m-accordion__item-mode"></span>
                            </div>
                            <div class="m-accordion__item-body collapse show" id="m_accordion_1_item_1_body" role="tabpanel"
                                aria-labelledby="m_accordion_1_item_1_head" data-parent="#m_accordion_1" style="">
                                <div class="m-accordion__item-content" id="select-date-channel-form-container">

                                </div>
                                <div class="mb-2 text-center">
                                    <a href="javascript:;" class="btn blue ladda-button" data-style="zoom-out" data-size="l" onclick="loadTable(this);" id="pull-data-by-date-channel-btn"><i class="fa fa-plus"></i> Pull Data </a>											
                                </div>                                  
                            </div>
                        </div>
                        <!--end::Item-->
                    </div>
                    <div class="container d-flex justify-content-center" id="table-container-fluid">

                    </div>
                    <div class="container-fluid " id="clonedtable-container-fluid">

                    </div>
				</div>
			</div>
		</div>
        <div class="excel-table-context-menu dropdown-menu dropdown-menu-sm  fadeIn" id="context-menu">
          <a class="dropdown-item" href="javascript:;" onClick="addRowBelow(this)">Add New Punch </a>
          <!-- <a class="dropdown-item" href="javascript:;" onClick="addRowAbove(this)">Add Row Above</a> -->
          <a class="dropdown-item" href="javascript:;" onClick="deleteRow(this)">Delete Row</a>
        </div>        
		<!-- end:: Body -->
		<?php include_once $tpl_path . 'tplFooter.php';?>
	</div>
    
	<!-- end:: Page -->
	<?php //include_once $tpl_path.'tplQuickSidebar.php'; ?>

	<?php //include_once $tpl_path.'tplQuickNav.php'; ?>
	<?php include_once $tpl_path . 'tplFooterJs.php';?>


    <script src="<?php echo get_core_theme_path() ?>/assets/global/plugins/jquery-minicolors/jquery.minicolors.min.js" type="text/javascript"></script>
    <script src="<?php echo get_core_theme_path() ?>/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" type="text/javascript"></script>
    <script src="<?php echo HTTP_THEME_PATH ?>theme.elements.js?<?php echo time() ?>" type="text/javascript"></script>
    <script src="<?php echo get_core_theme_path() ?>js/Table.ArrowNavigation.js?<?php echo time() ?>" type="text/javascript"></script>
    <script src="<?php echo get_core_theme_path() ?>js/Table.Resizable.js?<?php echo time() ?>" type="text/javascript"></script>
    <script src="<?php echo get_core_theme_path() ?>js/Class.Table.Bs4.js?<?php echo time() ?>" type="text/javascript"></script>
    <script src="<?php echo get_core_theme_path() ?>js/Class.Table.Excel.js?<?php echo time() ?>" type="text/javascript"></script>
    <script src="<?php echo get_core_theme_path() ?>js/checkbox-control.js" type="text/javascript"></script>
    <script src="<?php echo get_core_theme_path() ?>js/imageCropJs.js?<?php echo time() ?>" type="text/javascript"></script>
    <script src="<?php echo HTTP_THEME_PATH ?>script-audit.js?<?php echo time() ?>" type="text/javascript"></script>
    <style>
        #table-header-fixed {
            position: fixed;
            top: 80px; 
            display:none;
            background-color:white;
        }
        #table-header-fixed > thead{
            height:90px;
            background-color:grey;
        }
    </style>
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
    var tableOptns,focusedTd;
    var focusChildNode = true;
    var selectedTr,$formClass,newFormOptions,getDateAndChannelForm;
    var channel_id;
    var punch_date;
    var tableClassObject;
    $(document).ready(function() {
        eval(jsFunction);
        /* tableOptns = <?php echo ($encodedTableOptions) ?>;
        tableOptns['container'] = 'table-container-fluid';
        tableClassObject = new TableExcel(tableOptns);
        var tableValue = tableClassObject.getTable(); */
        tableClassObject = new TableExcel({});
        tableClassObject.excelAjaxSave = function(e,$uniqueId){
            excelAjaxSave(e,$uniqueId);
        }
        form = $('#table-form');
        tbody = $('#table-tbody');
        status = $('#status-message');
        getDateAndChannelForm = <?php echo ($encodedGetDateAndChannelForm) ?>;
		getDateAndChannelForm['show_form_in_container'] = 'select-date-channel-form-container';
		getDateAndChannelForm['wrap_in_form'] = true;
        $formClass2 = new FormGenerator(getDateAndChannelForm);   
		$formClass2.actionCallBacks = {
			'save': '',
			'update': ''
        };
        var selectDateChannelForm = $formClass2.getForm();
        $(document).on('focus','.autoselect-on-focus',function(e){
            $(e.target).select();
        });
    });
</script>


</body>
	<!-- end::Body -->
</html>
