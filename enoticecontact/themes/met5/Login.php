<?php
include_once DIR_SITE_ROOT . "bootstrap.php";
include_once DIR_SITE_ROOT . "config.php";
include_once DIR_CORE_CLASSES . "class_form.php";
include_once DIR_CORE_INCLUDES . "db_conx.php";
include_once DIR_PHP_CLASSES . "Class.User.php";
$title = 'Login';
$jsFunction = '';
$tools = array();
$toolsAll = array();
$has_register_form = false;
?>
<?php
//print_r($_COOKIE);
//exit();
// If user is already logged in, header that weenis away
if (!isset($_POST["login"])) {
    $user_ok = new User($db_conx);
    //echo 'User OK'.$user_ok->user_ok ;
    if (($user_ok->user_ok === true)) {
        $redir = get_redirect_login();
        header("location: $redir");
        exit();
    }
}
//print_r ($_SESSION);
?>
<?php
// AJAX CALLS THIS LOGIN CODE TO EXECUTE
if (isset($_POST["login"])) {
    // CONNECT TO THE DATABASE
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
    $login = $_POST['login'];
    $password = $_POST['password'];
    // GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
    // FORM DATA ERROR HANDLING
    if ($login == "" || $password == "") {
        $ajax_return['status'] = 'error';
        $ajax_return['value'] = 'Please check the username and the password.';
        echo json_encode($ajax_return);
        exit();
    } else {
        // END FORM DATA ERROR HANDLING
        $user = new User($db_conx);
        //print_r($password);
        $is_success = $user->login($login, $password);
        if ($is_success) {
            $ajax_return['status'] = 'success';
        }

        $ajax_return['value'] = $user->userData;
        echo json_encode($ajax_return);
        exit();
    }
    exit();
}

?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title><?php echo $title ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css" />
<link href="<?php echo get_core_theme_path() ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_core_theme_path() ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_core_theme_path() ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_core_theme_path() ?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN THEME GLOBAL STYLES -->
<link href="<?php echo get_core_theme_path() ?>assets/global/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
<link href="<?php echo get_core_theme_path() ?>assets/global/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_core_theme_path() ?>assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css" />
<!-- END THEME GLOBAL STYLES -->
<!-- BEGIN THEME LAYOUT STYLES -->
<link href="<?php echo get_core_theme_path() ?>css/login-matrox.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_core_theme_path() ?>css/form-custom.css" rel="stylesheet" type="text/css" />
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<style>
body {
	background-color:white;
    background-color: #465568!important;
	color:white;
}

.card-wrapper.alt .toggle:before {
    position: relative;
    right: -20px;
    top: -40px;
}
.toggle .register-caption {
    font-size: 30px;
    position: relative;
    bottom: 100px;
    display: block;
}
.login-wrapper.active .card-wrapper.login{
    height: 100px;
    overflow-y:hidden;
}
.login-wrapper.active .card-wrapper.alt .toggle .register-caption{
    display: none;
}

.login-wrapper.active .card-wrapper.alt .container {
    min-height: 400px;
}
.card-wrapper .footer a {
    color: #32c5d2;
}
.card-wrapper.alt .toggle-no-icon {
	overflow:hidden;
}
@media screen and (max-width: 550px){
    .login-wrapper.active .card-wrapper.alt .toggle {
        width: 80px;
        height: 100px;
        font-size: 34px;
        line-height: 80px;
    }
    .toggle .register-caption {
        font-size: 20px;
        position: relative;
        bottom: 80px;
        display: block;
    }
    .card-wrapper.alt .toggle:before {
        position: relative;
        right: -20px;
        top: -30px;
    }
}
</style>
<script>
var theme_path = '<?php echo $theme_path ?>';
var core_theme_path = '<?php echo get_core_theme_path() ?>';
</script>
<body>



		<div class="progress progress-striped active hidden">
			<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 10%">
			</div>
		</div>

        <section class="section-padding banner-wrapper login-alt banner-6 fullscreen-banner">
            <div class="container" style="">

                <div class="login-wrapper">
                  <div class="card-wrapper"></div>
                  <div class="card-wrapper login">
                    <h1 class="title">Login</h1>
                    <div class="font-green" id="status" style="margin-bottom:10px"></div>
                    <div class="progress progress-striped active hidden">
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 10%">
                        </div>
                    </div>
                    <span id="display-messages"  style="padding:10px"></span>
                      <div class="input-container">
                        <input type="text" id="username" name="username" required="required"/>
                        <label for="username">Username</label>
                        <div class="bar"></div>
                      </div>
                      <div class="input-container">
                        <input type="password" id="password" name="password" required="required"/>
                        <label for="password">Password</label>
                        <div class="bar"></div>
                      </div>
                      <div class="button-container">
                        <button  onClick="Login()" class="btn btn-lg btn-block green waves-effect waves-light">Login</button>
                      </div>
                      <div class="footer"><a href="#">Forgot your password?</a></div>

                  </div>
                <?php if ($has_register_form) {?>
                  <div class="card-wrapper alt">
                    <div class="toggle"><img src="<?php echo get_core_theme_path() ?>images/logo-backend.png"></div>
                    <div class="container container-fluid">
                    <div class="row" style="padding-right: 60px;padding-left: 30px;">
                        <?php echo $registerForm ?>

                    </div>
                    </div>
                  </div>
                <?php } else {?>

                  <div class="card-wrapper alt">
                    <div class="toggle-no-icon"><img src="<?php echo get_core_theme_path() ?>images/logo-backend.png" style="height: auto;width:110px"></div>
                  </div>
                  <?php }?>
                </div>

            </div>
        </section>





<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/respond.min.js"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/ladda/spin.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/ladda/ladda.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script src="<?php echo get_core_theme_path() ?>assets/pages/scripts/ui-sweetalert.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/app.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/common.js?<?php echo time() ?>" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/table.js?<?php echo time() ?>" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/crmFunctions.js?<?php echo time() ?>" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/validation.js?<?php echo time() ?>" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/form-generator.js?<?php echo time() ?>" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/form-generator-helper.js?<?php echo time() ?>" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<?php if ($has_register_form) {?>
<script>
    $('.toggle').on('click', function() {
      $('.login-wrapper').stop().addClass('active');
    });

    $('.close').on('click', function() {
      $('.login-wrapper').stop().removeClass('active');
    });

</script>
<?php }?>
<script>
var toolsAll = [];
var tools = [];
toolsAll = <?php echo json_encode($tools) ?>;
var jsFunction = <?php echo json_encode($jsFunction) ?>;
var isReadyCommon = true;
jQuery(document).ready(function() {
    eval(jsFunction);
});

$("#vendor-registration-form").submit(function(e){
	startLoading();
    var isReady = true;
    var isValidated = true;
    var tools = toolsAll['vendor'];
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

    }
    else {
        e.preventDefault();
        stopLoading();
    }
});
function Save(data,form){
	//calledFrom is where function is called from
	startLoading();
    var isReady = true;
    var isValidated = true;
    var tools = toolsAll['vendor'];
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
    if ($(e.target).is(".btn")) {
         // not stuff in here
         return false;
    }
    var module = $(e.target).attr('data-parent');
    tools = toolsAll[module];
	var id = $(e.target).attr('id');
    if(id == 'password') return;
    if(id == 'password2') return;

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

</script>
<script>
var statusmessage = $('#status');
var formWrapper = $('#display-messages');
var finalMessage = '';
 var loaded = 20;

$("#password").on("keydown",function(e){

    if ( e.which == 13 ) // Enter key = keycode 13
    {
		e.preventDefault();
		Login();
	}
	else{
		return true;
	}

})

function Login(){
    var login = $.trim($('#username').val()),
    pass = $.trim($('#password').val());
    loaded = 20;
    $('.progress').removeClass('hidden');
    $('.progress').css('display','block');
    if (login.length === 0){
        // Display message
        displayError('Username cannot be empty')
        $('.progress').addClass('hidden');
        return false;

    }
    else if (pass.length === 0){

        $('.progress').addClass('hidden');
        // Display message
        displayError('Please fill in your password');
        return false;
    }
    else{
        displayLoading('');
        $.ajax({
                type:'POST',
                url: '',
                data: {'login':login, 'password':pass},
                success: function(data1) {
                    console.log(data1);
					var data = JSON.parse(data1);
					console.log(data1);
					{
                    if (data['status'] == 'success')
                    {
                        $('.progress-bar').removeClass('progress-bar-warning progress-bar-success progress-bar-info progress-bar-danger').addClass('progress-bar-success');
                        finalMessage = 'Login Succesfull.. Please Wait';
                        document.location.href = '<?php echo get_redirect_login() ?>';
                    }
                    else
                    {
                        $('.progress-bar').removeClass('progress-bar-warning progress-bar-success progress-bar-info progress-bar-danger').addClass('progress-bar-danger');
                        finalMessage = '';
                        displayError('Invalid user/password, please try again');
                    }
					}
                }
            });

    }
}

/**
 * Function to display error messages
 * @param string message the error to display
 */
function displayError(message)
{
	App.alert({
		container: formWrapper, // alerts parent container(by default placed after the page breadcrumbs)
		place: 'append', // append or prepent in container
		type: 'danger',  // alert's type
		message: message,  // alert's message
		close: true, // make alert closable
		reset: true, // close all previouse alerts first
		focus: true, // auto scroll to the alert after shown
		closeInSeconds: 10, // auto close after defined seconds
		icon: 'error' // put icon before the message
	});
};

/**
 * Function to display loading messages
 * @param string message the message to display
 */

function displayLoading(message)
{
    ++loaded;
    var progress = $('.progress-bar');
    progress.css('width', ''+loaded+'%');
    if (loaded === 100)
    {

        statusmessage.text(finalMessage);
        setTimeout(function() { $('.progress').hide(); }, 1500);
    }
    else
    {
        //console.log(loaded);
        if (loaded === 1)
        {
            statusmessage.text('Checking Credentials...');
        }
        else if (loaded === 25)
        {
            statusmessage.text('Retrieving From Database (1/3)...');

        }
        else if (loaded === 45)
        {
            statusmessage.text('Checking Permissions (2/3)...');
        }
        else if (loaded === 85)
        {
            statusmessage.text('Initializing User Settings (3/3)...');
        }
        else if (loaded === 92)
        {
            statusmessage.text('Loading User Profile...');
        }
        timeout = setTimeout(displayLoading, 5);
    }


}
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>