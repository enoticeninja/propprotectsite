<?php
$uri = get_full_current_url();
$uri = ltrim($uri, '/backend');
$uri = str_replace(SITE_PATH, '', $uri);
$__page_active = '';
$__sub_page_active = '';
$has_sidebar = true;
{
    $brand_menu = array(
        array("title" => "Manage Brands", "url" => "Table/Brand", "icon" => "", "id" => "sub-Brand"),
        array("title" => "Add New Brand", "url" => "Brand/New", "icon" => "", "id" => "sub-Brand-New"),
        array("title" => "Bulk Upload Brand", "url" => "Brand/Bulk", "icon" => "", "id" => "sub-Brand-bulk"),
    );
////// TRY TO MATCH THE ID OF SUB MENU PARENT TO get_current_url_array(0), so pages like shape/edit/id would work
    $menu = array(
        array("title" => "Dashboard", "url" => "DashBoard", "icon" => "flaticon-dashboard", "id" => "DashBoard"),
        array("title" => "Manage Clients", "url" => "Table/Clients", "icon" => "flaticon-users", "id" => "Clients"),
        array("title" => "Manage Categories", "url" => "Table/Category", "icon" => "flaticon-folder-2", "id" => "Categories"),
        array("title" => "Manage Channels", "url" => "Table/Channels", "icon" => "flaticon-technology", "id" => "Channels"),
        array("title" => "Manage Brands", "url" => "Table/Brands", "icon" => "flaticon-imac", "id" => "Brands"),
        array("title" => "Manage Ad Mediums", "url" => "Table/Mediums", "icon" => "flaticon-imac", "id" => "Mediums"),
        array("title" => "Manage Ad Elements", "url" => "Table/AdTypes", "icon" => "flaticon-time-1", "id" => "AdTypes"),
    );
/*         $menu[] = array ("title"=>"Manage Employees","url"=>"Table/Users","icon"=>"flaticon-users","id"=>"Users");
$menu[] = array ("title"=>"Manage OS","url"=>"Table/os","icon"=>"flaticon-imac","id"=>"os");
$menu[] = array ("title"=>"Manage Products","url"=>"Table/Products","icon"=>"flaticon-business","id"=>"Products");
$menu[] = array ("title"=>"View Leads","url"=>"Table/Leads","icon"=>"flaticon-user","id"=>"Leads"); */
    if (USER_LEVEL == 'admin') {

    }
}
$admin_menu = array();
/////0:Menu Title    1:url    2:icon   3:submenu   4:id

$menucode = '';
$sidebarcode = '';
{ ////////SIDEBAR1
    function createSlidebarMenuItem($data)
    {
        if ($data['icon'] == '') {
            $data['icon'] = 'm-menu__link-bullet m-menu__link-bullet--dot';
        } else {
            $data['icon'] = 'm-menu__link-icon ' . $data['icon'] . '';
        }

        $menu =
            '
	<li class="m-menu__item " aria-haspopup="true"  data-redirect="true" id="sidebar-main-' . $data['id'] . '">
		<a  href="' . SITE_PATH . $data['url'] . '" class="m-menu__link ">
		<i class="' . $data['icon'] . '"></i>
			<span class="m-menu__link-text">
				' . $data['title'] . '
			</span>
		</a>
	</li>
	';
        return $menu;
    }

    function createSlidebarSubMenu($data)
    {
        if ($data['icon'] == '') {
            $data['icon'] = 'm-menu__link-bullet m-menu__link-bullet--dot';
        } else {
            $data['icon'] = 'm-menu__link-icon ' . $data['icon'] . '';
        }

        $sub_menu = '';
        if (isset($data['mega'])) {
            foreach ($data['mega']['blocks'] as $sub1) {
                //print_r($sub);
                foreach ($sub1['items'] as $sub) {
                    $sub['title'] = $sub['name'];
                    $sub['url'] = 'Brand/' . $sub['id'];
                    $sub_menu .= createSlidebarSubMenuItem($sub);
                }
            }
        } else {
            foreach ($data['sub'] as $sub) {
                $sub_menu .= createSlidebarSubMenuItem($sub);
            }
        }
        $menu =
            '
	<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="click" data-menu-submenu-mode="accordion" id="sidebar-main-' . $data['id'] . '">
		<a  href="javascript:;"" class="m-menu__link m-menu__toggle">
		<i class="' . $data['icon'] . '"></i>
			<span class="m-menu__link-text">
				' . $data['title'] . '
			</span>
			<i class="m-menu__ver-arrow la la-angle-right"></i>
		</a>
		<div class="m-menu__submenu ">
			<span class="m-menu__arrow"></span>
			<ul class="m-menu__subnav" id="slidebar-' . $data['id'] . '">
				' . $sub_menu . '
			</ul>
		</div>
	</li>
	';
        return $menu;
    }

    function createSlidebarSubMenuItem($data)
    {
        if ($data['icon'] == '') {
            $data['icon'] = 'm-menu__link-bullet m-menu__link-bullet--dot';
        } else {
            $data['icon'] = 'm-menu__link-icon ' . $data['icon'] . '';
        }

        $sub_menu = '';
        if (isset($data['sub'])) {
            foreach ($data['sub'] as $sub) {
                $sub_menu .= createSlidebarSubSubMenuItem($sub);
            }
            $menu =
                '
		<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="click" data-menu-submenu-mode="accordion" data-redirect="true" id="sidebar-main-' . $data['id'] . '">
			<a  href="avascript:;" class="m-menu__link m-menu__toggle">
				<i class="' . $data['icon'] . '"></i>
				<span class="m-menu__link-text">
					' . $data['title'] . '
				</span>
				<i class="m-menu__ver-arrow la la-angle-right"></i>
			</a>
			<div class="m-menu__submenu ">
				<span class="m-menu__arrow"></span>
				<ul class="m-menu__subnav">
					' . $sub_menu . '
				</ul>
			</div>
		</li>
		';
        } else {
            $menu =
                '
		<li class="m-menu__item " aria-haspopup="true"  data-redirect="true">
			<a  href="' . SITE_PATH . $data['url'] . '" class="m-menu__link ">
				<i class="' . $data['icon'] . '"></i>
				<span class="m-menu__link-text">
					' . $data['title'] . '
				</span>
			</a>
		</li>
		';
        }
        return $menu;
    }

    function createSlidebarSubSubMenuItem($data)
    {
        if ($data['icon'] == '') {
            $data['icon'] = 'm-menu__link-bullet m-menu__link-bullet--dot';
        } else {
            $data['icon'] = 'm-menu__link-icon ' . $data['icon'] . '';
        }

        $menu =
            '
	<li class="m-menu__item " aria-haspopup="true" id="sidebar-main-' . $data['id'] . '">
		<a  href="' . SITE_PATH . $data['url'] . '" class="m-menu__link ">
			<i class="' . $data['icon'] . '"></i>
			<span class="m-menu__link-text">
				' . $data['title'] . '
			</span>
		</a>
	</li>
	';
        return $menu;
    }
}
{ ////////SIDEBAR2
    function createSlidebarMenuItem2($data)
    {
        $menu =
            '
	<li class="m-menu__item " aria-haspopup="true"  data-redirect="true" id="sidebar2-main-' . $data['id'] . '">
		<a  href="' . SITE_PATH . $data['url'] . '" class="m-menu__link ">
			<span class="m-menu__link-text">
				' . $data['title'] . '
			</span>
		</a>
	</li>
	';
        return $menu;
    }

    function createSlidebarSubMenu2($data)
    {
        $sub_menu = '';
        if (isset($data['mega'])) {
            foreach ($data['mega']['blocks'] as $sub1) {
                //print_r($sub);
                foreach ($sub1['items'] as $sub) {
                    $sub['title'] = $sub['name'];
                    $sub['url'] = 'Brand/' . $sub['id'];
                    $sub_menu .= createSlidebarSubMenuItem2($sub);
                }
            }
        } else {
            foreach ($data['sub'] as $sub) {
                $sub_menu .= createSlidebarSubMenuItem2($sub);
            }
        }
        $menu =
            '
	<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover" data-redirect="true" id="sidebar2-main-' . $data['id'] . '">
		<a  href="#" class="m-menu__link m-menu__toggle">
			<i class="m-menu__link-bullet m-menu__link-bullet--dot">
				<span></span>
			</i>
			<span class="m-menu__link-text">
				' . $data['title'] . '
			</span>
			<i class="m-menu__ver-arrow la la-angle-right"></i>
		</a>
		<div class="m-menu__submenu ">
			<span class="m-menu__arrow"></span>
			<ul class="m-menu__subnav" id="slidebar-' . $data['id'] . '">
				' . $sub_menu . '
			</ul>
		</div>
	</li>
	';
        return $menu;
    }

    function createSlidebarSubMenuItem2($data)
    {
        $sub_menu = '';
        if (isset($data['sub'])) {
            foreach ($data['sub'] as $sub) {
                $sub_menu .= createSlidebarSubSubMenuItem2($sub);
            }
            $menu =
                '
		<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover" data-redirect="true" id="sidebar2-main-' . $data['id'] . '">
			<a  href="#" class="m-menu__link m-menu__toggle">
				<i class="m-menu__link-bullet m-menu__link-bullet--dot">
					<span></span>
				</i>
				<span class="m-menu__link-text">
					' . $data['title'] . '
				</span>
				<i class="m-menu__ver-arrow la la-angle-right"></i>
			</a>
			<div class="m-menu__submenu ">
				<span class="m-menu__arrow"></span>
				<ul class="m-menu__subnav">
					' . $sub_menu . '
				</ul>
			</div>
		</li>
		';
        } else {
            $menu =
                '
		<li class="m-menu__item " aria-haspopup="true"  data-redirect="true" id="sidebar2-main-' . $data['id'] . '">
			<a  href="' . SITE_PATH . $data['url'] . '" class="m-menu__link ">
				<i class="m-menu__link-bullet m-menu__link-bullet--dot">
					<span></span>
				</i>
				<span class="m-menu__link-text">
					' . $data['title'] . '
				</span>
			</a>
		</li>
		';
        }
        return $menu;
    }

    function createSlidebarSubSubMenuItem2($data)
    {
        $menu =
            '
	<li class="m-menu__item " aria-haspopup="true" id="sidebar2-main-' . $data['id'] . '">
		<a  href="' . SITE_PATH . $data['url'] . '" class="m-menu__link ">
			<i class="m-menu__link-bullet m-menu__link-bullet--dot">
				<span></span>
			</i>
			<span class="m-menu__link-text">
				' . $data['title'] . '
			</span>
		</a>
	</li>
	';
        return $menu;
    }
}
$sidebarcode2 = '';
foreach ($menu as $key => $menuitem) {
    {
        $title1 = $menuitem['title'];
        $url = $menuitem['url'];
        $icon = $menuitem['icon'];
        $id = $menuitem['id'];
        if (strtolower($url) == strtolower($uri)) {
            $__page_active = $id;
        }

        if (isset($menuitem['mega']) && !empty(array_filter($menuitem['mega']))) {
            //$menucode .= createMegaMenu($menuitem)['menu'];
            $sidebarcode .= createSlidebarSubMenu($menuitem);
            $sidebarcode2 .= createSlidebarSubMenu2($menuitem);
        } else if (isset($menuitem['sub']) && !empty(array_filter($menuitem['sub']))) {
            $sub = $menuitem['sub'];
            //$menucode .= createSubMenu($menuitem)['menu'];
            $sidebarcode .= createSlidebarSubMenu($menuitem);
            $sidebarcode2 .= createSlidebarSubMenu2($menuitem);
        } else {
            //$menucode .= createSingleMenuItem($menuitem)['menu'];
            $sidebarcode .= createSlidebarMenuItem($menuitem);
            //$sidebarcode2 .= createSlidebarMenuItem2($menuitem);
        }
    }

}

?>
<style>
	@media (min-width: 993px){
	.m-aside-left--minimize .m-aside-menu .m-menu__nav > .m-menu__item > .m-menu__link {
		color: #ffffff;
		background-color: #05dcef!important;
		box-shadow: -7px 9px 7px 0px rgb(165, 236, 243)!important;
		left: 20px;
		width: 70px;
		padding-left: 0;
		padding-right: 0;
	}
	.m-aside-left--minimize .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item {
		margin-top: 15px;
	}
	.m-aside-left--minimize .m-aside-menu .m-menu__nav > .m-menu__item.m-menu__item--hover {
		position: relative;
		z-index: 100;
		width: auto;
	}
	.m-aside-left--minimize .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item > .m-menu__link > .m-menu__link-icon {
		color: #ffffff;
	}
	.m-aside-menu .m-menu__nav > .m-menu__item.m-menu__item--bottom-2 {
		position: absolute !important;
		bottom: 105px;
	}
	.m-aside-left--minimize .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item.m-menu__item--hover > .m-menu__link, .m-aside-left--minimize .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item.m-menu__item--open > .m-menu__link {
		background-color: #f8f8fb;
		box-shadow: none!important;
	}
	.m-aside-left--minimize .m-aside-menu .m-menu__nav > .m-menu__item.m-menu__item--hover > .m-menu__link {
		width: 70px;
	}
	.m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item:not(.m-menu__item--parent):not(.m-menu__item--open):not(.m-menu__item--expanded):not(.m-menu__item--active):hover > .m-menu__heading .m-menu__link-icon, .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item:not(.m-menu__item--parent):not(.m-menu__item--open):not(.m-menu__item--expanded):not(.m-menu__item--active):hover > .m-menu__link .m-menu__link-icon {
		color: #ffffff;
	}

	.m-menu-sidebar-custom.m-menu__link {
		color: #ffffff;
		background-color: #05dcef!important;
		box-shadow: -7px 9px 7px 0px rgb(165, 236, 243)!important;
		left: 20px;
		width: 70px;
		padding-left: 0;
		padding-right: 0;
	}
	}
</style>
<!-- BEGIN: Left Aside -->
<button class="m-aside-left-close  m-aside-left-close--skin-light " id="m_aside_left_close_btn">
	<i class="la la-close"></i>
</button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-light ">
	<!-- BEGIN: Brand -->
	<div class="m-brand  m-brand--skin-light ">
		<a href="<?php echo SITE_PATH ?>DashBoard" class="m-brand__logo">
			<img alt="" src="<?php echo get_core_theme_path() ?>images/logo-backend.png"/>
		</a>
	</div>
	<!-- END: Brand -->
<!-- BEGIN: Aside Menu -->
<div
id="m_ver_menu"
class="m-aside-menu  m-aside-menu--skin-light m-aside-menu--submenu-skin-light "
data-menu-vertical="true"
data-menu-scrollable="true" data-menu-dropdown-timeout="100"
>
		<ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
			<li class="m-menu__item  m-menu__item--submenu m-menu__item--submenu-fullheight" aria-haspopup="true"  data-menu-submenu-toggle="hover" data-menu-dropdown-toggle-class="m-aside-menu-overlay--on" >
				<a  href="#" class="m-menu__link m-menu__toggle">
					<i class="m-menu__link-icon flaticon-menu"></i>
					<span class="m-menu__link-text">
						Applications
					</span>
					<i class="m-menu__ver-arrow la la-angle-right"></i>
				</a>
				<div class="m-menu__submenu ">
					<span class="m-menu__arrow"></span>
					<div class="m-menu__wrapper">
						<ul class="m-menu__subnav">
							<li class="m-menu__item  m-menu__item--parent m-menu__item--submenu-fullheight" aria-haspopup="true" >
								<span class="m-menu__link">
									<span class="m-menu__link-text">
										Main Menu
									</span>
								</span>
							</li>
							<li class="m-menu__section">
								<h4 class="m-menu__section-text">
									Modules
								</h4>
								<i class="m-menu__section-icon flaticon-more-v3"></i>
							</li>
							<?php echo $sidebarcode ?>
							</ul>
					</div>
				</div>
			</li>
<!-- 			<li class="m-menu__item  " >
				<a  href="<?php echo SITE_PATH . 'Table/Cases/Open' ?>" class="m-menu-sidebar-custom m-menu__link " data-container="body" data-toggle="m-popover" data-placement="right" data-content="Shows All your Open Cases" data-original-title="Your Bin" title="">
					<i class="m-menu__link-icon flaticon-interface-2"></i>
					<span class="m-menu__link-text">
						Bin
					</span>
					<i class="m-menu__ver-arrow la la-angle-right"></i>
				</a>
			</li>
			<li class="m-menu__item  " >
				<a  href="<?php echo SITE_PATH . 'Table/Customers' ?>" class="m-menu-sidebar-custom m-menu__link " data-container="body" data-toggle="m-popover" data-placement="right" data-content="Shows All Your Customers" data-original-title="Your Customers" title="">
					<i class="m-menu__link-icon flaticon-users"></i>
					<span class="m-menu__link-text">
						Customers
					</span>
					<i class="m-menu__ver-arrow la la-angle-right"></i>
				</a>
			</li>	 -->
			<?php if ($sidebarcode2 != '') {?>
			<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover" data-redirect="true">
				<a  href="#" class="m-menu__link m-menu__toggle">
					<i class="m-menu__link-icon flaticon-add"></i>
					<span class="m-menu__link-text">
						Add
					</span>
					<i class="m-menu__ver-arrow la la-angle-right"></i>
				</a>
				<div class="m-menu__submenu ">
					<span class="m-menu__arrow"></span>
					<ul class="m-menu__subnav">
					<?php echo $sidebarcode2 ?>
					</ul>
				</div>
			</li>
			<?php }?>
			<?php if ($sidebarcode2 != '') {?>
			<li class="m-menu__item  m-menu__item--submenu m-menu__item--bottom" aria-haspopup="true"  data-menu-submenu-toggle="hover" data-redirect="true">
				<a  href="#" class="m-menu__link m-menu__toggle">
					<i class="m-menu__link-icon flaticon-stopwatch"></i>
					<span class="m-menu__link-text">
						Customers
					</span>
					<i class="m-menu__ver-arrow la la-angle-right"></i>
				</a>
				<div class="m-menu__submenu ">
					<span class="m-menu__arrow"></span>
					<ul class="m-menu__subnav">
					<?php echo $sidebarcode2 ?>
					</ul>
				</div>
			</li>
			<?php }?>

			<?php if ($sidebarcode2 != '') {?>
			<li class="m-menu__item  m-menu__item--submenu m-menu__item--bottom-2" aria-haspopup="true"  data-menu-submenu-toggle="hover">
				<a  href="#" class="m-menu__link m-menu__toggle">
					<i class="m-menu__link-icon flaticon-settings"></i>
					<span class="m-menu__link-text">
						Settings
					</span>
					<i class="m-menu__ver-arrow la la-angle-right"></i>
				</a>
				<div class="m-menu__submenu m-menu__submenu--up">
					<span class="m-menu__arrow"></span>
					<ul class="m-menu__subnav">
						<li class="m-menu__item  m-menu__item--parent m-menu__item--bottom-2" aria-haspopup="true" >
							<span class="m-menu__link">
								<span class="m-menu__link-text">
									Settings
								</span>
							</span>
						</li>
						<?php echo $sidebarcode2 ?>
					</ul>
				</div>
			</li>
			<?php }?>
			<?php if ($sidebarcode2 != '') {?>
			<li class="m-menu__item  m-menu__item--submenu m-menu__item--bottom-1" aria-haspopup="true"  data-menu-submenu-toggle="hover">
				<a  href="#" class="m-menu__link m-menu__toggle">
					<i class="m-menu__link-icon flaticon-info"></i>
					<span class="m-menu__link-text">
						Help
					</span>
					<i class="m-menu__ver-arrow la la-angle-right"></i>
				</a>
				<div class="m-menu__submenu m-menu__submenu--up">
					<span class="m-menu__arrow"></span>
					<ul class="m-menu__subnav">
						<li class="m-menu__item  m-menu__item--parent m-menu__item--bottom-1" aria-haspopup="true" >
							<span class="m-menu__link">
								<span class="m-menu__link-text">
									Help
								</span>
							</span>
						</li>
						<?php echo $sidebarcode2 ?>
					</ul>
				</div>
			</li>
			<?php }?>
		</ul>
	</div>
	<!-- END: Aside Menu -->
</div>
<div class="m-aside-menu-overlay"></div>
<!-- END: Left Aside -->