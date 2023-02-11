<?php
$uri = get_full_current_url();
$uri = ltrim($uri, '/backend');
$uri = str_replace(SITE_PATH,'',$uri);
$__page_active = '';
$__sub_page_active = '';
{
$brand_menu = array (
			array ("title"=>"Manage Brands","url"=>"Table/Brand","icon"=>"icon-arrow-right","id"=>"sub-Brand"),
			array ("title"=>"Add New Brand","url"=>"Brand/New","icon"=>"icon-arrow-right","id"=>"sub-Brand-New"),
			array ("title"=>"Bulk Upload Brand","url"=>"Brand/Bulk","icon"=>"icon-arrow-right","id"=>"sub-Brand-bulk"),
);

$shape_menu = array (
			array ("title"=>"Manage Shapes","url"=>"Table/Shape","icon"=>"icon-star","id"=>"sub-Shape"),
			array ("title"=>"Add New Shape","url"=>"Shape/New","icon"=>"icon-star","id"=>"sub-Shape-New"),
			array ("title"=>"Bulk Upload Shape","url"=>"Shape/Bulk","icon"=>"icon-star","id"=>"sub-Shape-bulk"),
);

$background_menu = array (
			array ("title"=>"Manage Backgrounds","url"=>"Table/Backgrounds","icon"=>"icon-picture","id"=>"sub-backgrounds"),
			array ("title"=>"Add New Background","url"=>"Background/New","icon"=>"icon-picture","id"=>"sub-bkg-New"),
			array ("title"=>"Bulk Upload Backgrounds","url"=>"Background/Bulk","icon"=>"icon-picture","id"=>"sub-bkg-bulk"),
);
$case_menu = array (
			array ("title"=>"Manage Cases","url"=>"Table/Cases","icon"=>"icon-screen-smartphone","id"=>"Products"),
			array ("title"=>"Add New Case","url"=>"Case/New","icon"=>"icon-screen-smartphone","id"=>"Case-New"),
			array ("title"=>"Bulk Upload Cases","url"=>"Case/Bulk","icon"=>"icon-screen-smartphone","id"=>"Case-Bulk"),
);
$slider_menu = array (
			array ("title"=>"Manage Slider","url"=>"Table/Slider","icon"=>"icon-picture","id"=>"Slider"),
			array ("title"=>"Add New Slider Image","url"=>"Slider/New","icon"=>"icon-picture","id"=>"Slider-New"),
			array ("title"=>"Bulk Upload Slider","url"=>"Slider/Bulk","icon"=>"icon-picture","id"=>"Slider-Bulk"),
);

////// TRY TO MATCH THE ID OF SUB MENU PARENT TO get_current_url_array(0), so pages like shape/edit/id would work
$menu = array (
			array ("title"=>"Dashboard","url"=>"DashBoard","icon"=>"icon-speedometer","id"=>"DashBoard"),
			array ("title"=>"Manage Addresses","url"=>"Table/Address","icon"=>"icon-home","id"=>"Address"),
			array ("title"=>"Manage States","url"=>"Table/States","icon"=>"icon-globe","id"=>"States"),
			array ("title"=>"Manage Country","url"=>"Table/Country","icon"=>"icon-globe","id"=>"Country"),
			array ("title"=>"Manage Customers","url"=>"Table/Customer","icon"=>"icon-user","id"=>"Customer"),
			array ("title"=>"Manage Orders","url"=>"Table/Order","icon"=>"icon-basket","id"=>"Order"),
			array ("title"=>"Brands","url"=>"Table/Brands","icon"=>"icon-arrow-right","id"=>"Brand","sub"=>$brand_menu),
			array ("title"=>"Shapes","url"=>"Table/Shapes","icon"=>"icon-star","id"=>"Shape","sub"=>$shape_menu),
			array ("title"=>"Mobile Cases","url"=>"Table/Products","icon"=>"icon-screen-smartphone","id"=>"Case","sub"=>$case_menu),
			array ("title"=>"Backgrounds","url"=>"Table/Backgrounds","icon"=>"icon-picture","id"=>"Background","sub"=>$background_menu),
			array ("title"=>"Manage Home Slider","url"=>"Table/Slider","icon"=>"icon-picture","id"=>"Slider"),
		);
}
$admin_menu = array ();
/////0:Menu Title    1:url    2:icon   3:submenu   4:id

$menucode = '';
$sidebarcode = '';			
foreach ($menu as $key=>$menuitem){
    {
	$title1 = $menuitem['title'];
	$url = $menuitem['url'];
	$icon = $menuitem['icon'];
	$id = $menuitem['id'];
    if(strtolower($url) == strtolower($uri)){
        $__page_active = $id;
    }
 
    if(isset($menuitem['sub']) && !empty(array_filter($menuitem['sub']))) {
        $sub = $menuitem['sub']; 
/*         uasort($sub, function($a, $b) {
            return $a['sort_order'] - $b['sort_order'];
        }); */
        $top_sub_menu = '';
        $sidebar_sub_menu = '';            
        foreach ($sub as $subKey=>$submenu) {
			$subtitle = $submenu['title'];
			$suburl = $submenu['url'];
			$subicon = $submenu['icon'];
			$subid = $submenu['id'];
            if(strtolower($suburl) == strtolower($uri)){
                $__page_active = $id;
                $__sub_page_active = $subid;
            }

        /////TOP MENU CODE			
            $top_sub_menu .= '
                            <li id="'.$id.'" class="">
                                <a href="'.$site_path.$suburl.'">
                                    <i class="fa fa-bookmark-o"></i>'.$subtitle.'</a>

                            </li>							
                            ';
        /////////// SIDEBAR CODE 							
            $sidebar_sub_menu .= '

                        <li class="nav-item " id="selected-'.$subid.'">
                                <a href="'.$site_path.$suburl.'" class="nav-link ">
                                <span class="sidebar-mini">  </span>
                                <span class="sidebar-normal"> '.$subtitle.' </span>
                            </a>
                        </li>                                
                            ';							
            }
		

		$menucode .= '
						<li class="classic-menu-dropdown " id="'.$__page_active.'">
							<a href="javascript:;" data-hover="megamenu-dropdown" data-close-others="true" class="hover-initialized"> '.$title1.'
								<i class="fa fa-angle-down"></i>
								<span id="selected-'.$id.'" class=""> </span>								
							</a>
							<ul class="dropdown-menu pull-left">							
								'.$top_sub_menu.'
							</ul>
						</li>  
						';


		$sidebarcode .= '

						<li class="nav-item " id="'.$id.'">
							<a class="nav-link" data-toggle="collapse" href="#selected-sidebar-'.$id.'">
								<i class="'.$icon.' "></i>
								<p> '.$title1.'
									<b class="caret"></b>
								</p>
							</a>

							<div class="collapse" id="selected-sidebar-'.$id.'">
								<ul class="nav">
									'.$sidebar_sub_menu.'
								</ul>
							</div>    
						</li>        
							';
							

    } 
    else {

    /////TOP MENU CODE	

                $menucode .= 
                '
                                <li class="classic-menu-dropdown" id="'.$id.'">
                                    <a href="'.$site_path.$url.'"> '.$title1.'
                                        <span class="" id="selected-'.$id.'"> </span>
                                    </a>
                                </li>

                ';    


        //// SIDEBAR CODE 	
    
            $sidebarcode .= 
            '
                            <li class="nav-item" id="selected-'.$id.'">
                                <a href="'.$site_path.$url.'" class="nav-link">
                                    <i class="'.$icon.'"></i>
                                    <p class="title">'.$title1.'</p>

                                </a>
                            </li>
            ';

    
    }

        
    }        
    
}

$id = get_user_id();
if($__page_active == '')$__page_active = get_current_url_array(0);
	$editSelfPassword = 
	"
	let data = {};
	data['data'] = {'action':'edit_password_single','module':'backend_user','return_form_as':'modal','id':'$id'};
	data['isAjax'] = true;
	var form = new FormGenerator(data);
	form.actionCallBacks['save'] = 'doNothing';
	form.actionCallBacks['update'] = 'doNothing';
	";
	$editBackendUserPassword = 
	"
	let data = {};
	data['data'] = {'action':'edit_password_multiple','module':'backend_user','return_form_as':'modal'};
	data['isAjax'] = true;
	var form = new FormGenerator(data);
	form.actionCallBacks['save'] = 'doNothing';
	form.actionCallBacks['update'] = 'doNothing';
	";
	$editCustomerPassword = 
	"
	let data = {};
	data['data'] = {'action':'edit_password_multiple','module':'customer','return_form_as':'modal'};
	data['isAjax'] = true;
	var form = new FormGenerator(data);
	form.actionCallBacks['save'] = 'doNothing';
	form.actionCallBacks['update'] = 'doNothing';
	";
  {$passwordCode = 
'
            <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#passwordMenu">
                <i class="material-icons">lock</i>
                <p> Reset Passwords
                    <b class="caret"></b>
                </p>
            </a>

            <div class="collapse" id="passwordMenu">
                <ul class="nav">
                    <li class="nav-item ">
                        <a class="nav-link" data-toggle="modal" href="#" onClick="'.($editBackendUserPassword).'">
                            <span class="sidebar-normal"> Reset Backenduser Password </span>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" data-toggle="modal" href="#" onClick="'.($editCustomerPassword).'">
                            <span class="sidebar-normal"> Reset Customer Password </span>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" data-toggle="modal" href="#" onClick="'.($editSelfPassword).'">
                            <span class="sidebar-normal"> Reset Your Password </span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
';


$sidebarcode .= $passwordCode;	
$profile_menu = 
'
									<ul class="dropdown-menu" role="menu">
										<li>
											<a onclick="GetChangePasswordForm(\''.$_SESSION[get_session_values('id')].'\',\'change_own_password\')" data-toggle="modal" href="#responsive">
												<i class="icon-lock"></i> Change Your Password </a>
										</li>									
										<li>
											<a href="'.$site_path.'Logout">
												<i class="icon-logout"></i> Logout </a>
										</li>									
									</ul>
';


$header_profile_menu = 
'
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
										<a onclick="GetChangePasswordForm(\''.$_SESSION[get_session_values('id')].'\',\'change_own_password\')" data-toggle="modal" href="#responsive">
                                        <i class="icon-lock"></i> Change Password </a>
                                </li>
                                <li>
                                    <a href="'.$site_path.'Logout">
                                        <i class="icon-key"></i> Log Out </a>
                                </li>
                            </ul>
';

/* $file = DIR_ADMIN_ROOT.'themes/default/cache_tpl/backend_top_menu.php';
file_put_contents($file, $menucode.PHP_EOL);
$file = DIR_ADMIN_ROOT.'themes/default/cache_tpl/backend_sidebar_menu.php';
file_put_contents($file, $sidebarcode.PHP_EOL ); */
}
?>
<link href="<?php echo get_core_theme_path() ?>css/material-sidebar-only.css?<?php echo time() ?>" rel="stylesheet" type="text/css" />
<style>
.navbar-toggler{
	height:40px
}
.sidebar[data-color=blue-gradient] li.active>a {
    background-color: #00bcd4;
    background: linear-gradient(60deg,#37b9ec,#0f8fc0);
}

.sidebar[data-background-color=dark-blue] .nav .nav-item .nav-link{color:#fff}.sidebar[data-background-color=dark-blue] .nav .nav-item i{color:hsla(0,0%,100%,.8)}.sidebar[data-background-color=dark-blue] .nav .nav-item.active [data-toggle=collapse],.sidebar[data-background-color=dark-blue] .nav .nav-item:hover [data-toggle=collapse]{color:#fff}.sidebar[data-background-color=dark-blue] .nav .nav-item.active [data-toggle=collapse] i,.sidebar[data-background-color=dark-blue] .nav .nav-item:hover [data-toggle=collapse] i{color:hsla(0,0%,100%,.8)}.sidebar[data-background-color=dark-blue] .simple-text,.sidebar[data-background-color=dark-blue] .user a{color:#fff}.sidebar[data-background-color=dark-blue] .sidebar-background:after{background:#074987;opacity:.8}.sidebar[data-background-color=dark-blue] .logo:after,.sidebar[data-background-color=cyan] .nav li.separator:after,.sidebar[data-background-color=dark-blue] .user:after{background-color:#074987}.sidebar[data-background-color=dark-blue] .nav li.active>[data-toggle=collapse],.sidebar[data-background-color=dark-blue] .nav li:hover:not(.active)>a{background-color:#636161b3}.sidebar.has-image:after,.sidebar[data-image]:after{opacity:.77}.main-panel{position:relative;float:right;width:calc(100% - 260px);transition:.33s,cubic-bezier(.685,.0473,.346,1)}

.sidebar[data-background-color=cyan] .nav .nav-item .nav-link{color:#fff}.sidebar[data-background-color=cyan] .nav .nav-item i{color:hsla(0,0%,100%,.8)}.sidebar[data-background-color=cyan] .nav .nav-item.active [data-toggle=collapse],.sidebar[data-background-color=cyan] .nav .nav-item:hover [data-toggle=collapse]{color:#fff}.sidebar[data-background-color=cyan] .nav .nav-item.active [data-toggle=collapse] i,.sidebar[data-background-color=cyan] .nav .nav-item:hover [data-toggle=collapse] i{color:hsla(0,0%,100%,.8)}.sidebar[data-background-color=cyan] .simple-text,.sidebar[data-background-color=cyan] .user a{color:#fff}.sidebar[data-background-color=cyan] .sidebar-background:after{background:#0cb2e6;opacity:.8}.sidebar[data-background-color=cyan] .logo:after,.sidebar[data-background-color=cyan] .nav li.separator:after,.sidebar[data-background-color=cyan] .user:after{background-color:#0cb2e6}.sidebar[data-background-color=cyan] .nav li.active>[data-toggle=collapse],.sidebar[data-background-color=cyan] .nav li:hover:not(.active)>a{background-color:#636161b3}.sidebar.has-image:after,.sidebar[data-image]:after{opacity:.77}.main-panel{position:relative;float:right;width:calc(100% - 260px);transition:.33s,cubic-bezier(.685,.0473,.346,1)}

.sidebar[data-background-color=blue] .nav .nav-item .nav-link{color:#fff}.sidebar[data-background-color=blue] .nav .nav-item i{color:hsla(0,0%,100%,.8)}.sidebar[data-background-color=blue] .nav .nav-item.active [data-toggle=collapse],.sidebar[data-background-color=blue] .nav .nav-item:hover [data-toggle=collapse]{color:#fff}.sidebar[data-background-color=blue] .nav .nav-item.active [data-toggle=collapse] i,.sidebar[data-background-color=blue] .nav .nav-item:hover [data-toggle=collapse] i{color:hsla(0,0%,100%,.8)}.sidebar[data-background-color=blue] .simple-text,.sidebar[data-background-color=blue] .user a{color:#fff}.sidebar[data-background-color=blue] .sidebar-background:after{background:#0c887d;opacity:.8}.sidebar[data-background-color=blue] .logo:after,.sidebar[data-background-color=blue] .nav li.separator:after,.sidebar[data-background-color=blue] .user:after{background-color:hsla(0,0%,100%,.3)}.sidebar[data-background-color=blue] .nav li.active>[data-toggle=collapse],.sidebar[data-background-color=blue] .nav li:hover:not(.active)>a{background-color:hsla(0,0%,100%,.1)}.sidebar.has-image:after,.sidebar[data-image]:after{opacity:.77}.main-panel{position:relative;float:right;width:calc(100% - 260px);transition:.33s,cubic-bezier(.685,.0473,.346,1)}

.sidebar[data-background-color=orange] .nav .nav-item .nav-link{color:#fff}.sidebar[data-background-color=orange] .nav .nav-item i{color:hsla(0,0%,100%,.8)}.sidebar[data-background-color=orange] .nav .nav-item.active [data-toggle=collapse],.sidebar[data-background-color=orange] .nav .nav-item:hover [data-toggle=collapse]{color:#fff}.sidebar[data-background-color=orange] .nav .nav-item.active [data-toggle=collapse] i,.sidebar[data-background-color=orange] .nav .nav-item:hover [data-toggle=collapse] i{color:hsla(0,0%,100%,.8)}.sidebar[data-background-color=orange] .simple-text,.sidebar[data-background-color=orange] .user a{color:#fff}.sidebar[data-background-color=orange] .sidebar-background:after{background:#ff9800;opacity:.8}.sidebar[data-background-color=orange] .logo:after,.sidebar[data-background-color=orange] .nav li.separator:after,.sidebar[data-background-color=orange] .user:after{background-color:hsla(0,0%,100%,.3)}.sidebar[data-background-color=orange] .nav li.active>[data-toggle=collapse],.sidebar[data-background-color=orange] .nav li:hover:not(.active)>a{background-color:hsla(0,0%,100%,.1)}.sidebar.has-image:after,.sidebar[data-image]:after{opacity:.77}.main-panel{position:relative;float:right;width:calc(100% - 260px);transition:.33s,cubic-bezier(.685,.0473,.346,1)}

.sidebar[data-background-color=grey] .nav .nav-item .nav-link{color:#fff}.sidebar[data-background-color=grey] .nav .nav-item i{color:hsla(0,0%,100%,.8)}.sidebar[data-background-color=grey] .nav .nav-item.active [data-toggle=collapse],.sidebar[data-background-color=grey] .nav .nav-item:hover [data-toggle=collapse]{color:#fff}.sidebar[data-background-color=grey] .nav .nav-item.active [data-toggle=collapse] i,.sidebar[data-background-color=grey] .nav .nav-item:hover [data-toggle=collapse] i{color:hsla(0,0%,100%,.8)}.sidebar[data-background-color=grey] .simple-text,.sidebar[data-background-color=grey] .user a{color:#fff}.sidebar[data-background-color=grey] .sidebar-background:after{background:#676464;opacity:.8}.sidebar[data-background-color=grey] .logo:after,.sidebar[data-background-color=grey] .nav li.separator:after,.sidebar[data-background-color=grey] .user:after{background-color:#676464}.sidebar[data-background-color=grey] .nav li.active>[data-toggle=collapse],.sidebar[data-background-color=grey] .nav li:hover:not(.active)>a{background-color:#2f2d2dbd}.sidebar.has-image:after,.sidebar[data-image]:after{opacity:.77}.main-panel{position:relative;float:right;width:calc(100% - 260px);transition:.33s,cubic-bezier(.685,.0473,.346,1)}

.sidebar .collapse .nav:before {
    position:relative;
    top: -45px;
}
</style>
<div class="sidebar" data-color="blue-gradient" data-background-color="grey" data-image="<?php echo get_absolute_theme_path() ?>images/sidebar-1.jpg">
    <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
	<div class="navbar-minimize">
		<button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round btn-circle">
			<i class="material-icons text_align-center visible-on-sidebar-regular">more_vert</i>
			<i class="material-icons design_bullet-list-67 visible-on-sidebar-mini">view_list</i>
		</button>
	</div>
    <div class="logo">
        <a href="<?php echo $site_path ?>DashBoard" class="simple-text logo-normal text-center">
            <img src="<?php echo get_core_theme_path() ?>images/logo.png" alt="logo" style="width:200px;" class="logo-default">
        </a>
    </div>

    <div class="sidebar-wrapper">
        <div class="user">
            <div class="user-info">
                <a href="<?php echo $site_path ?>DashBoard" class="username">
                    <span>
                       <?php echo $user_ok->userData['name_to_display'] ?>
                    </span>
                </a>
            </div>
        </div>
        <ul class="nav">

            <?php echo $sidebarcode ?>
        </ul>
    </div>
</div>
<script>
var script = document.createElement('script');
script.src = '<?php echo get_core_theme_path() ?>js/perfect-scrollbar.jquery.min.js';
script.type = 'text/javascript';

document.addEventListener('DOMContentLoaded', function() {
$('body').append(script);
(function() {
    isWindows = navigator.platform.indexOf('Win') > -1 ? true : false;

    if (isWindows && !$('body').hasClass('sidebar-mini')) {
        // if we are on windows OS we activate the perfectScrollbar function
        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();
        $('html').addClass('perfect-scrollbar-on');
    } else {
        $('html').addClass('perfect-scrollbar-off');
    }
})();

var breakCards = true;

var searchVisible = 0;
var transparent = true;

var transparentDemo = true;
var fixedTop = false;

var mobile_menu_visible = 0,
    mobile_menu_initialized = false,
    toggle_initialized = false,
    bootstrap_nav_initialized = false;

var seq = 0,
    delays = 80,
    durations = 500;
var seq2 = 0,
    delays2 = 80,
    durations2 = 500;


$(document).ready(function() {

   //$('body').bootstrapMaterialDesign();

    $sidebar = $('.sidebar');

    md.initSidebarsCheck();

    // if ($('body').hasClass('sidebar-mini')) {
    //     md.misc.sidebar_mini_active = true;
    // }

    window_width = $(window).width();

    // check if there is an image set for the sidebar's background
    md.checkSidebarImage();

    md.initMinimizeSidebar();

});

$(document).on('click', '.navbar-toggler', function() {
    $toggle = $(this);

    if (mobile_menu_visible == 1) {
        $('html').removeClass('nav-open');

        $('.close-layer').remove();
        setTimeout(function() {
            $toggle.removeClass('toggled');
        }, 400);

        mobile_menu_visible = 0;
    } else {
        setTimeout(function() {
            $toggle.addClass('toggled');
        }, 430);

        var $layer = $('<div class="close-layer"></div>');

        if ($('body').find('.main-panel').length != 0) {
            $layer.appendTo(".main-panel");

        } else if (($('body').hasClass('off-canvas-sidebar'))) {
            $layer.appendTo(".wrapper-full-page");
        }

        setTimeout(function() {
            $layer.addClass('visible');
        }, 100);

        $layer.click(function() {
            $('html').removeClass('nav-open');
            mobile_menu_visible = 0;

            $layer.removeClass('visible');

            setTimeout(function() {
                $layer.remove();
                $toggle.removeClass('toggled');

            }, 400);
        });

        $('html').addClass('nav-open');
        mobile_menu_visible = 1;

    }

});

// activate collapse right menu when the windows is resized
$(window).resize(function() {
    md.initSidebarsCheck();

    // reset the seq for charts drawing animations
    seq = seq2 = 0;

});

md = {
    misc: {
        navbar_menu_visible: 0,
        active_collapse: true,
        disabled_collapse_init: 0,
    },

    checkSidebarImage: function() {
        $sidebar = $('.sidebar');
        image_src = $sidebar.data('image');

        if (image_src !== undefined) {
            sidebar_container = '<div class="sidebar-background" style="background-image: url(' + image_src + ') "/>';
            $sidebar.append(sidebar_container);
        }
    },

    initFormExtendedDatetimepickers: function(){
        $('.datetimepicker').datetimepicker({
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }
         });

         $('.datepicker').datetimepicker({
            format: 'MM/DD/YYYY',
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }
         });

         $('.timepicker').datetimepicker({
//          format: 'H:mm',    // use this format if you want the 24hours timepicker
            format: 'h:mm A',    //use this format if you want the 12hours timpiecker with AM/PM toggle
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'

            }
         });
    },


    initSliders: function(){
        // Sliders for demo purpose
        var slider = document.getElementById('sliderRegular');

        noUiSlider.create(slider, {
            start: 40,
            connect: [true,false],
            range: {
                min: 0,
                max: 100
            }
        });

        var slider2 = document.getElementById('sliderDouble');

        noUiSlider.create(slider2, {
            start: [ 20, 60 ],
            connect: true,
            range: {
                min:  0,
                max:  100
            }
        });
    },

    initSidebarsCheck: function() {
        if ($(window).width() <= 991) {
            if ($sidebar.length != 0) {
                md.initRightMenu();
            }
        }
    },

    initMinimizeSidebar: function() {

        $('#minimizeSidebar').click(function() {
            var $btn = $(this);

            if (md.misc.sidebar_mini_active == true) {
                $('body').removeClass('sidebar-mini');
                md.misc.sidebar_mini_active = false;
            } else {
                $('body').addClass('sidebar-mini');
                md.misc.sidebar_mini_active = true;
            }

            // we simulate the window Resize so the charts will get updated in realtime.
            var simulateWindowResize = setInterval(function() {
                window.dispatchEvent(new Event('resize'));
            }, 180);

            // we stop the simulation of Window Resize after the animations are completed
            setTimeout(function() {
                clearInterval(simulateWindowResize);
            }, 1000);
        });
    },

    checkScrollForTransparentNavbar: debounce(function() {
        if ($(document).scrollTop() > 260) {
            if (transparent) {
                transparent = false;
                $('.navbar-color-on-scroll').removeClass('navbar-transparent');
            }
        } else {
            if (!transparent) {
                transparent = true;
                $('.navbar-color-on-scroll').addClass('navbar-transparent');
            }
        }
    }, 17),


    initRightMenu: debounce(function() {
        $sidebar_wrapper = $('.sidebar-wrapper');

        if (!mobile_menu_initialized) {
            $navbar = $('nav').find('.navbar-collapse').children('.navbar-nav');

            mobile_menu_content = '';

            nav_content = $navbar.html();

            nav_content = '<ul class="nav navbar-nav nav-mobile-menu">' + nav_content + '</ul>';

            navbar_form = $('nav').find('.navbar-form').get(0).outerHTML;

            $sidebar_nav = $sidebar_wrapper.find(' > .nav');

            // insert the navbar form before the sidebar list
            $nav_content = $(nav_content);
            $navbar_form = $(navbar_form);
            $nav_content.insertBefore($sidebar_nav);
            $navbar_form.insertBefore($nav_content);

            $(".sidebar-wrapper .dropdown .dropdown-menu > li > a").click(function(event) {
                event.stopPropagation();

            });

            // simulate resize so all the charts/maps will be redrawn
            window.dispatchEvent(new Event('resize'));

            mobile_menu_initialized = true;
        } else {
            if ($(window).width() > 991) {
                // reset all the additions that we made for the sidebar wrapper only if the screen is bigger than 991px
                $sidebar_wrapper.find('.navbar-form').remove();
                $sidebar_wrapper.find('.nav-mobile-menu').remove();

                mobile_menu_initialized = false;
            }
        }
    }, 200),


    // initBootstrapNavbarMenu: debounce(function(){
    //
    //     if(!bootstrap_nav_initialized){
    //         $navbar = $('nav').find('.navbar-collapse').first().clone(true);
    //
    //         nav_content = '';
    //         mobile_menu_content = '';
    //
    //         //add the content from the regular header to the mobile menu
    //         $navbar.children('ul').each(function(){
    //             content_buff = $(this).html();
    //             nav_content = nav_content + content_buff;
    //         });
    //
    //         nav_content = '<ul class="nav nav-mobile-menu">' + nav_content + '</ul>';
    //
    //         $navbar.html(nav_content);
    //         $navbar.addClass('off-canvas-sidebar');
    //
    //         // append it to the body, so it will come from the right side of the screen
    //         $('body').append($navbar);
    //
    //         $toggle = $('.navbar-toggle');
    //
    //         $navbar.find('a').removeClass('btn btn-round btn-default');
    //         $navbar.find('button').removeClass('btn-round btn-fill btn-info btn-primary btn-success btn-danger btn-warning btn-neutral');
    //         $navbar.find('button').addClass('btn-simple btn-block');
    //
    //         bootstrap_nav_initialized = true;
    //     }
    // }, 500),

    startAnimationForLineChart: function(chart) {

        chart.on('draw', function(data) {
            if (data.type === 'line' || data.type === 'area') {
                data.element.animate({
                    d: {
                        begin: 600,
                        dur: 700,
                        from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
                        to: data.path.clone().stringify(),
                        easing: Chartist.Svg.Easing.easeOutQuint
                    }
                });
            } else if (data.type === 'point') {
                seq++;
                data.element.animate({
                    opacity: {
                        begin: seq * delays,
                        dur: durations,
                        from: 0,
                        to: 1,
                        easing: 'ease'
                    }
                });
            }
        });

        seq = 0;
    },
    startAnimationForBarChart: function(chart) {

        chart.on('draw', function(data) {
            if (data.type === 'bar') {
                seq2++;
                data.element.animate({
                    opacity: {
                        begin: seq2 * delays2,
                        dur: durations2,
                        from: 0,
                        to: 1,
                        easing: 'ease'
                    }
                });
            }
        });

        seq2 = 0;
    }
}


// Returns a function, that, as long as it continues to be invoked, will not
// be triggered. The function will be called after it stops being called for
// N milliseconds. If `immediate` is passed, trigger the function on the
// leading edge, instead of the trailing.

function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this,
            args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        }, wait);
        if (immediate && !timeout) func.apply(context, args);
    };
};
    
}, false);


</script>