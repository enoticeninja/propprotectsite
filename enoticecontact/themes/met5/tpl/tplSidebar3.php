<?php
{
ob_start();

{
    $clientsMenu = array(
        array("title" => "Manage Clients", "url" => "Table/AdClients", "icon" => "", "id" => "sub-AdClient"),
        array("title" => "Add New Client", "url" => "Create/AdClient", "icon" => "", "id" => "sub-AdClient-New"),
    );
    $adsMenu = array(
        array("title" => "Manage Advertisements", "url" => "Table/Advertisement", "icon" => "", "id" => "sub-Advertisement"),
        array("title" => "Add New Advertisement", "url" => "Create/Advertisement", "icon" => "", "id" => "sub-Advertisement-New"),
    );
////// TRY TO MATCH THE ID OF SUB MENU PARENT TO get_current_url_array(0), so pages like shape/edit/id would work
    $menu = array(
        array("title" => "Dashboard", "url" => "DashBoard", "icon" => "flaticon-dashboard", "id" => "DashBoard"),
        array("title" => "Feedback", "url" => "Table/Feedback", "icon" => "flaticon-edit", "id" => "Feedback"),
    );

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
        $data['id'] = str_replace('/','-',$data['url']);
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
            <li class="m-menu__item  m-menu__item--submenu sidebar-main-parent" aria-haspopup="true"  data-menu-submenu-toggle="hover"  id="sidebar-main-' . $data['id'] . '">
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
                <li class="m-menu__item  m-menu__item--submenu sidebar-main-parent" aria-haspopup="true"  data-menu-submenu-toggle="hover"  data-redirect="true" id="sidebar-main-parent-' . $data['id'] . '">
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
        } 
        else {
            $data['id'] = str_replace('/','-',$data['url']);
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
        $data['id'] = str_replace('/','-',$data['url']);
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

$sidebarcode2 = '';
foreach ($menu as $key => $menuitem) {
    {
        $title1 = $menuitem['title'];
        $url = $menuitem['url'];
        $icon = $menuitem['icon'];
        $id = $menuitem['id'];
        /* if (strtolower($url) == strtolower($uri)) {
            $__page_active = $id;
        } */

        if (isset($menuitem['mega']) && !empty(array_filter($menuitem['mega']))) {
            //$menucode .= createMegaMenu($menuitem)['menu'];
            $sidebarcode .= createSlidebarSubMenu($menuitem);
            //$sidebarcode2 .= createSlidebarSubMenu2($menuitem);
        } else if (isset($menuitem['sub']) && !empty(array_filter($menuitem['sub']))) {
            $sub = $menuitem['sub'];
            //$menucode .= createSubMenu($menuitem)['menu'];
            $sidebarcode .= createSlidebarSubMenu($menuitem);
            // $sidebarcode2 .= createSlidebarSubMenu2($menuitem);
        } else {
            //$menucode .= createSingleMenuItem($menuitem)['menu'];
            $sidebarcode .= createSlidebarMenuItem($menuitem);
            //$sidebarcode2 .= createSlidebarMenuItem2($menuitem);
        }
    }

}

?>
<style>
@media (min-width: 993px) {
    .m-aside-left{
        box-shadow: 3px 0px 7px 0 rgba(14, 14, 14, 0.5);
    }
    .m-aside-menu.m-aside-menu--skin-light .m-menu__nav>.m-menu__item.m-menu__item--active>.m-menu__heading,
    .m-aside-menu.m-aside-menu--skin-light .m-menu__nav>.m-menu__item.m-menu__item--active>.m-menu__link {
        background: linear-gradient(45deg, #0095ff, #0adef7) !important;
    }

    .m-aside-menu.m-aside-menu--skin-light .m-menu__nav>.m-menu__item.m-menu__item--active>.m-menu__heading,
    .m-aside-menu.m-aside-menu--skin-light .m-menu__nav>.m-menu__item.m-menu__item--active::after {
        content: '';
        position: absolute;
        left: 100%;
        top: 25%;
        border: 20px solid transparent;
        border-left: 20px solid #1996f6;
    }

    .m-aside-menu .m-menu__nav>.m-menu__item.m-menu__item--active>.m-menu__link {
        background: #282a3a;
    }

    .m-aside-menu .m-menu__nav .m-menu__item>.m-menu__heading:hover,
    .m-aside-menu .m-menu__nav .m-menu__item>.m-menu__link:hover {
        text-decoration: none;
        cursor: pointer;
    }

    .m-aside-menu .m-menu__nav {
        list-style: none;
        padding: 0px 0 30px 0;
    }

    .m-aside-menu.m-aside-menu--skin-light .m-menu__nav>.m-menu__item>.m-menu__heading .m-menu__link-icon,
    .m-aside-menu.m-aside-menu--skin-light .m-menu__nav>.m-menu__item>.m-menu__link .m-menu__link-icon {
        background: linear-gradient(45deg, #0095ff, #0adef7) !important;
        padding: 10px;
        width: 75px;
        box-shadow: -5px 6px 12px 0px #085088;
        color: #ffffff !important;
    }

    .m-aside-menu.m-aside-menu--skin-light .m-menu__nav>.m-menu__item:not(.m-menu__item--parent):not(.m-menu__item--open):not(.m-menu__item--expanded):not(.m-menu__item--active):hover>.m-menu__heading .m-menu__link-icon,
    .m-aside-menu.m-aside-menu--skin-light .m-menu__nav>.m-menu__item:not(.m-menu__item--parent):not(.m-menu__item--open):not(.m-menu__item--expanded):not(.m-menu__item--active):hover>.m-menu__link .m-menu__link-icon {
        box-shadow: none;
    }

    .m-aside-menu.m-aside-menu--skin-light .m-menu__nav>.m-menu__item>.m-menu__heading .m-menu__link-text,
    .m-aside-menu.m-aside-menu--skin-light .m-menu__nav>.m-menu__item>.m-menu__link .m-menu__link-text {
        color: #1996f6;
    }

    .m-aside-menu .m-menu__nav>.m-menu__item>.m-menu__link {
        display: block;
        position: relative;
        text-align: center;
        height: auto;
        padding: 0px;
        margin-bottom: 10px;
        margin-top: 5px;
    }

    .m-topbar .m-topbar__nav.m-nav>.m-nav__item>.m-nav__link .m-nav__link-icon {
        display: inline-block;
        background: #ff6f00;
        background: -webkit-linear-gradient(45deg, #ff6f00, #ffca28) !important;
        background: -moz- oldlinear-gradient(45deg, #ff6f00, #ffca28) !important;
        background: -o-linear-gradient(45deg, #ff6f00, #ffca28) !important;
        background: linear-gradient(45deg, #ff6f00, #ffca28) !important;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        text-align: center;
        line-height: 0;
        vertical-align: middle;
        padding: 0;
    }

    .m-topbar .m-topbar__nav.m-nav>.m-nav__item>.m-nav__link .m-nav__link-icon>i {
        color: #ffffff;
        font-size: 2.5rem;
        position: relative;
        top: 10px;
    }
}
</style>
<!-- BEGIN: Left Aside -->
<button class="m-aside-left-close m-aside-left-close--skin-light" id="m_aside_left_close_btn">
    <i class="la la-close"></i>
</button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-light animated slideInLeft faster">
    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu"
        class="m-aside-menu  m-aside-menu--skin-light m-aside-menu--submenu-skin-light m-aside-menu--dropdown "
        data-menu-vertical="true" data-menu-dropdown="true" data-menu-scrollable="true"
        data-menu-dropdown-timeout="500">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
            <?php echo $sidebarcode ?>
        </ul>
    </div>
    <!-- END: Aside Menu -->
</div>
<!-- END: Left Aside -->
<?php
if(!DISABLE_CACHE)file_put_contents(DIR_THEME . 'cache/tplSidebar3.php', ob_get_contents());
}
?>