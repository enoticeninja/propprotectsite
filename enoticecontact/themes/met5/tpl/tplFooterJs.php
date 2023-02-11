<!-- begin::Scroll Top -->
<div class="m-scroll-top m-scroll-top--skin-top" data-toggle="m-scroll-top" data-scroll-offset="500" data-scroll-speed="300">
	<i class="la la-arrow-up"></i>
</div>
<!-- end::Scroll Top -->
<!--begin::Base Scripts -->
<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/jquery-3.4.1.min.js" type="text/javascript"></script>
<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/popper.min.js" type="text/javascript"></script>
<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jquery-ui/jquery-ui-12.min.js" type="text/javascript"></script>
<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/moment.min.js" type="text/javascript"></script>
<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/js.cookie.min.js" type="text/javascript"></script>
<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/sweet-alert-extr.js" type="text/javascript"></script>
<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/bootstrap-date-time-daterange-min.js" type="text/javascript"></script>
<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/toaster-notify-min.js" type="text/javascript"></script>
<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/bootstrap-confirmation.min.js" type="text/javascript"></script>
<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/vendors.bundle.test.js" type="text/javascript"></script>
<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/jquery-input-mask-extr.js" type="text/javascript"></script>
<script src="<?php echo HTTP_THEME_PATH ?>assets/themes/theme-3/scripts.bundle.js" type="text/javascript"></script>
<script src="<?php echo HTTP_THEME_PATH ?>assets/app/js/dashboard.js" type="text/javascript"></script>
<!--end::Base Scripts -->
<!--begin::Page Vendors -->

<!--end::Page Vendors -->
<!--begin::Page Snippets -->
<!--end::Page Snippets -->
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/ladda/spin.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/ladda/ladda.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/bs3-bs4.js?<?php echo  time() ?>" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/common.js?<?php echo  time() ?>" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/table.js?<?php echo  time() ?>" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/validation.js?<?php echo  time() ?>" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/form.js?<?php echo time() ?>" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/form-generator.js?<?php echo  time() ?>" type="text/javascript"></script>
<!-- <div class="" id="console-error-log" style="position:fixed;overflow-y:scroll;right:0px;width:350px;height:100%;background:#000000;color:white;padding:10px">

</div> -->
<style>
	.tt-hint {
		color: #999;
	}

	.tt-menu {
		right: 0px;
		width: 400px;
		margin-top: 12px;
		padding: 8px 8px;
		background-color: #fff;
		border: 1px solid #ccc;
		border: 1px solid rgba(0, 0, 0, 0.2);
		border-radius: 8px;
		box-shadow: 0 5px 10px rgba(0,0,0,.2);
	}

	.tt-suggestion {
		padding: 3px 2px;
		font-size: 15px;
		line-height: 16px;
	}

	.tt-suggestion.tt-is-under-cursor {
		color: #fff;
	}

	.tt-suggestion.tt-cursor{
		color: #fff;
	}

	.tt-suggestion p {
		margin: 0;
	}

	.tt-suggestion div {
		padding-left: 20px;
	}
</style>
<script type="text/javascript">
var toolsAll = {};
var tools = {};

$(document).ready(function() {
	/* var __page_active = '<?php echo $__page_active ?>';
	$('#sidebar-main-<?php echo $__page_active ?>').addClass('m-menu__item--active');		
	$('#sidebar2-main-<?php echo $__page_active ?>').addClass('m-menu__item--active');

	var $activeSidebarMenu = $('#sidebar-main-<?php echo $__page_active ?>').clone();
	var $closestUl = $('#sidebar-main-<?php echo $__page_active ?>').closest('ul');
	$('#sidebar-main-<?php echo $__page_active ?>').remove();
	$closestUl.prepend($activeSidebarMenu); */
	var current_url = '<?php echo (get_current_url()) ?>';
	current_url = current_url.split('/').join('-');
	highlightActiveMenu($('#sidebar-main-'+current_url+''));

	stopLoading();
	//initTypeAhead();
	//CommonFunc2({'module':'notifications','action':'get_backend_order_notifications','dont_load':true},'undefined','undefined',{},'showNotifications');
	});

	function highlightActiveMenu(elem){
		if(!elem.length){

		}
		elem.addClass('m-menu__item--active');
		if(elem.parents('.sidebar-main-parent').first().length){
			highlightActiveMenu(elem.parents('.sidebar-main-parent').first());
		}
/* 		var $activeSidebarMenu = elem.clone();
		var $closestUl = elem.closest('ul');
		elem.remove();
		$closestUl.prepend($activeSidebarMenu); */
	}
</script>