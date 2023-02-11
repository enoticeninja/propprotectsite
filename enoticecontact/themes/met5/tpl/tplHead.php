<meta charset="utf-8" />
<title>
	<?php echo $title ?>
</title>
<meta name="description" content="Latest updates and statistic charts">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!--begin::Web font -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script> -->
<script>
 /*  WebFont.load({
	google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
	active: function() {
		sessionStorage.fonts = true;
	}
  }); */
const site_path = '<?php echo $site_path ?>';
var theme_path = '<?php echo $theme_path ?>';
const FRONTEND_SITE_PATH = '<?php echo FRONTEND_SITE_PATH ?>';
const core_theme_path = '<?php echo get_core_theme_path() ?>';
const BOOTSTRAP_VERSION = '4';
</script>
<!--end::Web font -->
<!--begin::Base Styles -->  
<!--begin::Page Vendors -->
<!--end::Page Vendors -->
<link href="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/fonts/fonts.css" rel="stylesheet" type="text/css" />
<link href="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/vendors.bundle.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo HTTP_THEME_PATH ?>assets/demo/default/base/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo HTTP_THEME_PATH ?>assets/themes/theme-3/style.bundle.min.css" rel="stylesheet" type="text/css" />


<link href="<?php echo get_core_theme_path() ?>assets/global/css/components-md-form-only.min.css" rel="stylesheet" id="style_components" type="text/css" />
<link href="<?php echo get_core_theme_path() ?>css/primary-theme-colors.css?<?php echo  time() ?>" rel="stylesheet" type="text/css">
<link href="<?php echo get_core_theme_path() ?>/css/common.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_core_theme_path() ?>css/common-custom.css?<?php echo  time() ?>" rel="stylesheet" type="text/css">
<link href="<?php echo get_core_theme_path() ?>css/form-custom.css" rel="stylesheet" type="text/css">
<link href="<?php echo get_core_theme_path() ?>css/sidebar-custom.css" rel="stylesheet" type="text/css">
<link href="<?php echo get_core_theme_path() ?>css/checkbox-custom.css" rel="stylesheet" type="text/css">
<link href="<?php echo get_core_theme_path() ?>css/popover-custom.css" rel="stylesheet" type="text/css">
<link href="<?php echo get_core_theme_path() ?>css/materialize-3-colors.css" rel="stylesheet" type="text/css">
<link href="<?php echo get_core_theme_path() ?>css/materialize-3-btn.css" rel="stylesheet" type="text/css">
<link href="<?php echo get_core_theme_path() ?>css/mat-circle-loader.css" rel="stylesheet" type="text/css">
<link href="<?php echo get_core_theme_path() ?>css/bootstrap4-3-conflicts.css" rel="stylesheet" type="text/css">
<link href="<?php echo get_core_theme_path() ?>assets/global/plugins/ladda/ladda-themeless.min.css" rel="stylesheet" type="text/css">



<!--end::Base Styles -->
<link rel="shortcut icon" href="<?php echo HTTP_THEME_PATH ?>assets/demo/default/media/img/logo/favicon.ico" />
<style>
.modal-dialog{
	max-width:90%;
}
.input-inline{
	display:inline-block;
}
.m-subheader {
    padding: 0px 30px 10px;
}
.btn > i {
    pointer-events:none;
}
</style>