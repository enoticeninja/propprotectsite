<?php 
$uri = get_full_current_url();
$uri = ltrim($uri, '/backend');
$uri = str_replace(SITE_PATH,'',$uri);
$__page_active = '';
$__sub_page_active = '';
$has_sidebar = true;
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
$slider_menu = array (
			array ("title"=>"Manage Slider","url"=>"Table/Slider","icon"=>"icon-picture","id"=>"Slider"),
			array ("title"=>"Add New Slider Image","url"=>"Slider/New","icon"=>"icon-picture","id"=>"Slider-New"),
			array ("title"=>"Bulk Upload Slider","url"=>"Slider/Bulk","icon"=>"icon-picture","id"=>"Slider-Bulk"),
);

$case_menu = array (
			array ("title"=>"Manage Cases","url"=>"Table/Cases","icon"=>"icon-screen-smartphone","id"=>"Products"),
			array ("title"=>"Add New Case","url"=>"Case/New","icon"=>"icon-screen-smartphone","id"=>"Case-New"),
			array ("title"=>"Bulk Upload Cases","url"=>"Case/Bulk","icon"=>"icon-screen-smartphone","id"=>"Case-Bulk","sub"=>$slider_menu),
);
////// TRY TO MATCH THE ID OF SUB MENU PARENT TO get_current_url_array(0), so pages like shape/edit/id would work
$menu = array (
			array ("title"=>"Dashboard","url"=>"DashBoard","icon"=>"flaticon-dashboard","id"=>"DashBoard"),
			array ("title"=>"Manage Addresses","url"=>"Table/Address","icon"=>"flaticon-information","id"=>"Address"),
			array ("title"=>"Manage States","url"=>"Table/States","icon"=>"flaticon-information","id"=>"States"),
			array ("title"=>"Manage Country","url"=>"Table/Country","icon"=>"flaticon-information","id"=>"Country"),
			array ("title"=>"Manage Customers","url"=>"Table/Customer","icon"=>"flaticon-information","id"=>"Customer"),
			array ("title"=>"Special Offers","url"=>"Accordion/SpecialOffers","icon"=>"flaticon-information","id"=>"SpecialOffers"),
			array ("title"=>"Brands","url"=>"Table/Brands","icon"=>"flaticon-information","id"=>"Brand","sub"=>$brand_menu),
			array ("title"=>"Shapes","url"=>"Table/Shapes","icon"=>"flaticon-information","id"=>"Shape","sub"=>$shape_menu),
			array ("title"=>"Mobile Cases","url"=>"Table/Products","icon"=>"flaticon-information","id"=>"Case","sub"=>$case_menu),
			array ("title"=>"Backgrounds","url"=>"Table/Backgrounds","icon"=>"flaticon-information","id"=>"Background","sub"=>$background_menu),

		);
}
$admin_menu = array ();
/////0:Menu Title    1:url    2:icon   3:submenu   4:id

$menucode = '';
$sidebarcode = '';	
{
function createSlidebarMenuItem($data){
	$menu = 
	'
	<li class="m-menu__item  m-menu__item--active" aria-haspopup="true">
		<a  href="'.SITE_PATH.$data['url'].'" class="m-menu__link ">
			<i class="m-menu__link-icon '.$data['icon'].'"></i>
			<span class="m-menu__link-title">
				<span class="m-menu__link-wrap">
					<span class="m-menu__link-text">
						'.$data['title'].'
					</span>
				</span>
			</span>
		</a>
	</li>	
	';
	return $menu;
}

function createSlidebarSubMenu($data){
	$sub_menu = '';
	if(isset($data['mega'])){
		foreach($data['mega']['blocks'] as $sub1){
			//print_r($sub);
			foreach($sub1['items'] as $sub){
				$sub['title'] = $sub['name'];
				$sub['url'] = SITE_PATH.'Brand/'.$sub['id'];
				$sub_menu .= createSlidebarSubMenuItem($sub);
			}
		}
	}
	else{
		foreach($data['sub'] as $sub){
			$sub_menu .= createSlidebarSubMenuItem($sub);
		}
	}
	$menu = 
	'
	<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover">
		<a  href="javascript:;" class="m-menu__link m-menu__toggle">
			<i class="m-menu__link-icon '.$data['icon'].'"></i>
			<span class="m-menu__link-text">
				'.$data['title'].'
			</span>
			<i class="m-menu__ver-arrow la la-angle-right"></i>
		</a>
		<div class="m-menu__submenu ">
			<span class="m-menu__arrow"></span>
			<ul class="m-menu__subnav" id="slidebar-'.$data['id'].'" >
				<li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
					<span class="m-menu__link">
						<span class="m-menu__link-text">
							'.$data['title'].'
						</span>
					</span>
				</li>
				'.$sub_menu.'
			</ul>
		</div>
	</li>
	';
	return $menu;
}

function createSlidebarSubMenuItem($data){
	$sub_menu = '';
	if(isset($data['sub'])){	
		foreach($data['sub'] as $sub){
			$sub_menu .= createSlidebarSubSubMenuItem($sub);	
		}
		$menu = 
		'
		<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover">
			<a  href="javascript:;" class="m-menu__link m-menu__toggle">
				<i class="m-menu__link-bullet m-menu__link-bullet--dot">
					<span></span>
				</i>
				<span class="m-menu__link-text">
					'.$data['title'].'
				</span>
				<i class="m-menu__ver-arrow la la-angle-right"></i>
			</a>
			<div class="m-menu__submenu ">
				<span class="m-menu__arrow"></span>
				<ul class="m-menu__subnav">
					'.$sub_menu.'
				</ul>
			</div>
		</li>
		';
	}
	else{
		$menu = 
		'
		<li class="m-menu__item " aria-haspopup="true" >
			<a  href="'.$data['url'].'" class="m-menu__link ">
				<i class="m-menu__link-bullet m-menu__link-bullet--dot">
					<span></span>
				</i>
				<span class="m-menu__link-text">
					'.$data['title'].'
				</span>
			</a>
		</li>	
		';	
	}
	return $menu;
}

function createSlidebarSubSubMenuItem($data){
	$menu = 
	'
	<li class="m-menu__item " aria-haspopup="true" >
		<a  href="'.$data['url'].'" class="m-menu__link ">
			<i class="m-menu__link-bullet m-menu__link-bullet--dot">
				<span></span>
			</i>
			<span class="m-menu__link-text">
				'.$data['title'].'
			</span>
		</a>
	</li>
	';
	return $menu;
}
}

foreach ($menu as $key=>$menuitem){
    {
	$title1 = $menuitem['title'];
	$url = $menuitem['url'];
	$icon = $menuitem['icon'];
	$id = $menuitem['id'];
    if(strtolower($url) == strtolower($uri)){
        $__page_active = $id;
    }
 
    if(isset($menuitem['mega']) && !empty(array_filter($menuitem['mega']))) {
		//$menucode .= createMegaMenu($menuitem)['menu'];
		$sidebarcode .= createSlidebarSubMenu($menuitem);
    } 
 
    else if(isset($menuitem['sub']) && !empty(array_filter($menuitem['sub']))) {
        $sub = $menuitem['sub'];
		//$menucode .= createSubMenu($menuitem)['menu'];
		$sidebarcode .= createSlidebarSubMenu($menuitem);
    } 
    else {
		//$menucode .= createSingleMenuItem($menuitem)['menu'];
		$sidebarcode .= createSlidebarMenuItem($menuitem);
    }
    }
    
}


?>
<!-- BEGIN: Left Aside -->
<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
	<i class="la la-close"></i>
</button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
	<!-- BEGIN: Aside Menu -->
	<div  id="m_ver_menu"  class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark "  data-menu-vertical="true" data-menu-scrollable="true" data-menu-dropdown-timeout="500" >
		<ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
		<?php echo $sidebarcode ?>
		</ul>
	</div>
	<!-- END: Aside Menu -->
</div>
<!-- END: Left Aside -->