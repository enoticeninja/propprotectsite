<style>   

    @media (min-width: 993px){
        .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item.m-menu__item--active > .m-menu__heading, .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item.m-menu__item--active > .m-menu__link {
            background: #ff6f00;
            background: -webkit-linear-gradient(45deg, #ff6f00, #ffca28) !important;
            background: -moz- oldlinear-gradient(45deg, #ff6f00, #ffca28) !important;
            background: -o-linear-gradient(45deg, #ff6f00, #ffca28) !important;
            background: linear-gradient(45deg, #ff6f00, #ffca28) !important;
        } 
        .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item.m-menu__item--active > .m-menu__heading, .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item.m-menu__item--active::after {
            content: '';
            position: absolute;
            left: 100%;
            top: 25%;
            border: 20px solid transparent;
            border-left: 20px solid #ffc124;
        }            
        .m-aside-menu .m-menu__nav > .m-menu__item.m-menu__item--active > .m-menu__link {
            background: #282a3a;
        }

        .m-aside-menu .m-menu__nav .m-menu__item > .m-menu__heading:hover, .m-aside-menu .m-menu__nav .m-menu__item > .m-menu__link:hover {
            text-decoration: none;
            cursor: pointer;
        }
        .m-aside-menu .m-menu__nav {
            list-style: none;
            padding: 0px 0 30px 0;
        }
        .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item > .m-menu__heading .m-menu__link-icon, .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item > .m-menu__link .m-menu__link-icon {
            background: #ff6f00;
            background: -webkit-linear-gradient(45deg, #ff6f00, #ffca28) !important;
            background: -moz- oldlinear-gradient(45deg, #ff6f00, #ffca28) !important;
            background: -o-linear-gradient(45deg, #ff6f00, #ffca28) !important;
            background: linear-gradient(45deg, #ff6f00, #ffca28) !important;
            background: #05dcef;
            padding: 10px;
            width: 75px;
            box-shadow: 0 6px 20px 0 rgba(255, 160, 0, .5) !important;
            color: #ffffff!important;
        }
        .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item:not(.m-menu__item--parent):not(.m-menu__item--open):not(.m-menu__item--expanded):not(.m-menu__item--active):hover > .m-menu__heading .m-menu__link-icon, .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item:not(.m-menu__item--parent):not(.m-menu__item--open):not(.m-menu__item--expanded):not(.m-menu__item--active):hover > .m-menu__link .m-menu__link-icon {
            box-shadow: none;
        }    
        .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item > .m-menu__heading .m-menu__link-text, .m-aside-menu.m-aside-menu--skin-light .m-menu__nav > .m-menu__item > .m-menu__link .m-menu__link-text {
            color: #ff8d0a;
        }

        .m-aside-menu .m-menu__nav > .m-menu__item > .m-menu__link {
            display: block;
            position: relative;
            text-align: center;
            height: auto;
            padding: 0px;
            margin-bottom: 10px;
            margin-top: 5px;
        }  
        .m-topbar .m-topbar__nav.m-nav > .m-nav__item > .m-nav__link .m-nav__link-icon {
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
        .m-topbar .m-topbar__nav.m-nav > .m-nav__item > .m-nav__link .m-nav__link-icon > i {
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
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-light ">
    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu"
        class="m-aside-menu  m-aside-menu--skin-light m-aside-menu--submenu-skin-light m-aside-menu--dropdown "
        data-menu-vertical="true" data-menu-dropdown="true" data-menu-scrollable="true"
        data-menu-dropdown-timeout="500">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
            
            <li class="m-menu__item " aria-haspopup="true"  data-redirect="true" id="sidebar-main-DashBoard">
                <a  href="http://mediaplanet.loc/DashBoard" class="m-menu__link ">
                <i class="m-menu__link-icon flaticon-dashboard"></i>
                    <span class="m-menu__link-text">
                        Dashboard
                    </span>
                </a>
            </li>
            
            <li class="m-menu__item " aria-haspopup="true"  data-redirect="true" id="sidebar-main-Clients">
                <a  href="http://mediaplanet.loc/Table/Clients" class="m-menu__link ">
                <i class="m-menu__link-icon flaticon-users"></i>
                    <span class="m-menu__link-text">
                        Manage Clients
                    </span>
                </a>
            </li>
            
            <li class="m-menu__item " aria-haspopup="true"  data-redirect="true" id="sidebar-main-Categories">
                <a  href="http://mediaplanet.loc/Table/Category" class="m-menu__link ">
                <i class="m-menu__link-icon flaticon-folder-2"></i>
                    <span class="m-menu__link-text">
                        Manage Categories
                    </span>
                </a>
            </li>
            
            <li class="m-menu__item " aria-haspopup="true"  data-redirect="true" id="sidebar-main-Channels">
                <a  href="http://mediaplanet.loc/Table/Channels" class="m-menu__link ">
                <i class="m-menu__link-icon flaticon-technology"></i>
                    <span class="m-menu__link-text">
                        Manage Channels
                    </span>
                </a>
            </li>
            
            <li class="m-menu__item " aria-haspopup="true"  data-redirect="true" id="sidebar-main-Brands">
                <a  href="http://mediaplanet.loc/Table/Brands" class="m-menu__link ">
                <i class="m-menu__link-icon flaticon-imac"></i>
                    <span class="m-menu__link-text">
                        Manage Brands
                    </span>
                </a>
            </li>
            
            <li class="m-menu__item " aria-haspopup="true"  data-redirect="true" id="sidebar-main-Mediums">
                <a  href="http://mediaplanet.loc/Table/Mediums" class="m-menu__link ">
                <i class="m-menu__link-icon flaticon-imac"></i>
                    <span class="m-menu__link-text">
                        Manage Ad Mediums
                    </span>
                </a>
            </li>
            
            <li class="m-menu__item " aria-haspopup="true"  data-redirect="true" id="sidebar-main-AdTypes">
                <a  href="http://mediaplanet.loc/Table/AdTypes" class="m-menu__link ">
                <i class="m-menu__link-icon flaticon-time-1"></i>
                    <span class="m-menu__link-text">
                        Manage Ad Elements
                    </span>
                </a>
            </li>
            
            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover"  id="sidebar-main-Sale">
                <a  href="javascript:;"" class="m-menu__link m-menu__toggle">
                <i class="m-menu__link-icon flaticon-time-1"></i>
                    <span class="m-menu__link-text">
                        Campaigns
                    </span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu ">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav" id="slidebar-Sale">
                        
                <li class="m-menu__item " aria-haspopup="true"  data-redirect="true">
                    <a  href="http://mediaplanet.loc/Table/Campaign" class="m-menu__link ">
                        <i class="m-menu__link-bullet m-menu__link-bullet--dot"></i>
                        <span class="m-menu__link-text">
                            View All Campigns
                        </span>
                    </a>
                </li>
                
                <li class="m-menu__item " aria-haspopup="true"  data-redirect="true">
                    <a  href="http://mediaplanet.loc/Campaign" class="m-menu__link ">
                        <i class="m-menu__link-bullet m-menu__link-bullet--dot"></i>
                        <span class="m-menu__link-text">
                            Add New Campaign
                        </span>
                    </a>
                </li>
                
                    </ul>
                </div>
            </li>
            
            <li class="m-menu__item " aria-haspopup="true"  data-redirect="true" id="sidebar-main-Users">
                <a  href="http://mediaplanet.loc/Table/Users" class="m-menu__link ">
                <i class="m-menu__link-icon flaticon-users"></i>
                    <span class="m-menu__link-text">
                        Manage Employees
                    </span>
                </a>
            </li>
                    </ul>
    </div>
    <!-- END: Aside Menu -->
</div>
<!-- END: Left Aside -->
