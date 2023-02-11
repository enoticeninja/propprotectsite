<?php 
include_once(DIR_SITE_ROOT."bootstrap.php");
include_once(DIR_SITE_ROOT."common.php");
$__page_active = 'NewPage';
define('__PAGE_TYPE__', 'form');
$jsFunction = '';
$toolsAll = array();
$module = get_current_url_array(1);////SHOULD MATCH THE PAGE NAME TO BE REDIRECTED TO AFTER SAVE
$__page_active = strtolower($module);
include_once('Adapter.php');
$class = getAdapter($module);
$action = get_current_url_array(1);
$action = strtolower($action);
$db_data = array();
$module_title = $class->common_title;
$options = $class->getNewForm(array('return_form_as'=>'form_rows_without_buttons'));
$encodedOptions = json_encode($options);
?>
<!DOCTYPE html>
<html lang="en" dir="">

<head>
    <?php include_once $tpl_path.'tplHead.php'; ?>
    <link href="<?php echo get_core_theme_path() ?>assets/global/plugins/jcrop/css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" />
	<style>
	.m-body .m-content {
		padding: 0px 30px;
	}
	</style>
</head>

<body class="<?php echo get_body_class() ?>">

	<div class="m-grid m-grid--hor m-grid--root m-page">
			<?php include_once $tpl_path.'tplHeader3.php'; ?>

			<?php //include_once $tpl_path.'tplMaterialSideBar.php'; ?>
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
				<?php include_once $tpl_path.'tplSidebar3.php'; ?>
				<div class="m-grid__item m-grid__item--fluid m-wrapper ">
					<!-- BEGIN: Subheader -->
					<div class="m-subheader ">
						<div class="d-flex align-items-center">
							<div class="mr-auto">
								<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
									<li class="m-nav__item m-nav__item--home">
										<a href="<?php echo $site_path ?>Dashboard" class="m-nav__link m-nav__link--icon">
											<i class="m-nav__link-icon la la-home"></i>
										</a>
									</li>
									<li class="m-nav__separator">
										-
									</li>
									<li class="m-nav__item">
										<a href="<?php echo $site_path ?>Table/<?php echo $module?>" class="m-nav__link">
											<span class="m-nav__link-text">
												<?php echo $class->common_title ?>(s)
											</span>
										</a>
									</li>
									<li class="m-nav__separator">
										-
									</li>
									<li class="m-nav__item">
										<span class="m-nav__link-text">
											Add New 
										</span>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- END: Subheader -->
					<div class="m-content">
						<!--Begin::Section-->
						<div class="row">
						<div class="col-md-12">

							<div class="m-portlet  ">
								<div class="m-portlet__head">
									<div class="m-portlet__head-caption">
										<div class="m-portlet__head-title">
											<h3 class="m-portlet__head-text">
												Add New <?php echo $class->common_title?>
											</h3>
										</div>
									</div>
								</div>
								<div class="m-portlet__body" >
									<div class="col-md-12" id="cust-form-container"> </div>
									<div class="col-md-12 text-center " id="cust-form-buttons-container"> </div>
								</div>
							</div>								

						</div>

					</div>

					</div>
				</div>
			</div>	
			<?php include_once $tpl_path.'tplFooter.php'; ?>
		</div>
		<!-- end:: Page -->

		<?php include_once $tpl_path.'tplFooterJs.php'; ?>
		<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jcrop/js/jquery.Jcrop.min.js" type="text/javascript"></script>
		<script src="<?php echo get_core_theme_path() ?>js/imageCropJs.js?<?php echo time() ?>" type="text/javascript"></script>
</body>
<script>
	var form = $('#table-form');
	var tbody = $('#table-tbody');
	var status = $('#status-message');
	var module = '<?php echo $module ?>';
	var page_action = '<?php echo $action ?>';
    var __MODULE__ = '<?php echo $module ?>';
	var module_title = '<?php echo $module_title ?>';    
	var tools = [];
	var toolsAll = [];
	var toolsAll = <?php echo(json_encode($toolsAll)) ?> ;
	//toolsAll = '<?php echo json_encode($toolsAll) ?>;
	var jsFunction = <?php echo json_encode($jsFunction) ?> ;
	var isReadyCommon = true;
	var ajaxCallBacks = {};
	var table1, tableOptns;
	var select2Data = {};
	var cartItems = {};
	var cartTotalAmount = 0;
    var formOptns = <?php echo($encodedOptions) ?> ;
	$(document).ready(function () {
		eval(jsFunction);
		formOptns['show_form_in_container'] = 'cust-form-container';
		formOptns['wrap_in_form'] = true;
        formOptns['show_buttons_in_container'] = 'cust-form-buttons-container';
        
		var formClass = new FormGenerator(formOptns);
		formClass.actionCallBacks = {
			'save': 'redirectToEdit',
			'update': 'changeCustomerDetails'
		};
		var custForm = formClass.getForm();
    });
    function redirectToEdit(data){
		console.log(data);
		if(data.id){
			//window.location = site_path + 'Edit/'+ module +'/'+ data.id;
			window.location = site_path + 'Table/'+ module +'';
		}
		
    }
</script>
</html>