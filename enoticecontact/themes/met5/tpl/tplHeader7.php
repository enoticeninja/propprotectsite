<div class="loadingg __common_loading_element__" id="loader-wrapper">
	<div class="col-md-12 col-sm-12 center  loadingg2">
		<div class="preloader-wrapper big active">
		  <div class="spinner-layer spinner-blue">
			<div class="circle-clipper left">
			  <div class="circle"></div>
			</div>
			<div class="gap-patch">
			  <div class="circle"></div>
			</div>
			<div class="circle-clipper right">
			  <div class="circle"></div>
			</div>
		  </div>

		  <div class="spinner-layer spinner-red">
			<div class="circle-clipper left">
			  <div class="circle"></div>
			</div>
			<div class="gap-patch">
			  <div class="circle"></div>
			</div>
			<div class="circle-clipper right">
			  <div class="circle"></div>
			</div>
		  </div>

		  <div class="spinner-layer spinner-yellow">
			<div class="circle-clipper left">
			  <div class="circle"></div>
			</div>
			<div class="gap-patch">
			  <div class="circle"></div>
			</div>
			<div class="circle-clipper right">
			  <div class="circle"></div>
			</div>
		  </div>

		  <div class="spinner-layer spinner-green">
			<div class="circle-clipper left">
			  <div class="circle"></div>
			</div>
			<div class="gap-patch">
			  <div class="circle"></div>
			</div>
			<div class="circle-clipper right">
			  <div class="circle"></div>
			</div>
		  </div>

		</div>

	</div>

</div>
<style>
	.m-menu__item.active .m-menu__link-text,.m-menu__item.active .m-menu__link-icon{
		color:#fff!important;
	}
	.m-menu__item.active{
		background-color: #05dcef!important;
		box-shadow: -7px 9px 20px 0px rgb(7, 167, 181)!important;
		color: #fff;
		width: 95%;
		margin: 0 auto!important;
	}
	.m-menu__item.m-menu__item--submenu.active{
		background-color: #05dcef!important;
		box-shadow: -7px 9px 20px 0px rgb(7, 167, 181)!important;
		color: #fff;
		width: 95%;
		margin: 0 auto!important;
	}
	.m-topbar .m-topbar__nav.m-nav>.m-nav__item.m-topbar__quick-actions>.m-nav__link .m-nav__link-icon .m-nav__link-icon-wrapper{
		background-color: #05dcef!important;
		box-shadow: -5px 5px 7px 0px rgb(7, 167, 181)!important;
		border: none;
	}
	.m-topbar .m-topbar__nav.m-nav>.m-nav__item.m-topbar__quick-actions>.m-nav__link .m-nav__link-icon .m-nav__link-icon-wrapper i{
		color:#fff;
		font-size: 2.3rem;
	}
	.m-topbar .m-topbar__nav.m-nav>.m-nav__item.m-topbar__notifications>.m-nav__link .m-nav__link-icon .m-nav__link-icon-wrapper {
		background-color: #ef9905!important;
		box-shadow: -5px 5px 7px 0px rgb(183, 116, 8);
		border: none;
	}
	.m-topbar .m-topbar__nav.m-nav>.m-nav__item.m-topbar__user-profile>.m-nav__link .m-nav__link-icon .m-nav__link-icon-wrapper {
		background-color: #f71e98!important;
		box-shadow: -5px 5px 7px 0px rgb(210, 9, 122)!important;
		color: #ffffff!important;
		border: none;
	}
	.m-topbar .m-topbar__nav.m-nav>.m-nav__item.m-topbar__notifications>.m-nav__link .m-nav__link-icon .m-nav__link-icon-wrapper i{
		color:#fff;
		font-size: 2.3rem;
	}
	.m-topbar .m-topbar__nav.m-nav>.m-nav__item.m-topbar__user-profile>.m-nav__link .m-nav__link-icon .m-nav__link-icon-wrapper i{
		color:#fff;
		font-size: 2.3rem;
	}
	.m-nav-grid>.m-nav-grid__row>.m-nav-grid__item {
		display: table-cell;
		vertical-align: middle;
		text-align: center;
		padding: 1.3rem 1rem;
	}
</style>
<!-- BEGIN: Header -->
<header class="m-grid__item    m-header "  data-minimize-offset="200" data-minimize-mobile-offset="200" >
	<div class="m-container m-container--fluid m-container--full-height">
		<div class="m-stack m-stack--ver m-stack--desktop">
			<!-- BEGIN: Brand -->
			<div class="m-stack__item m-brand ">
				<div class="m-stack m-stack--ver m-stack--general">
					<div class="m-stack__item m-stack__item--middle m-brand__logo">
						<a href="<?php echo SITE_PATH ?>DashBoard" class="m-brand__logo-wrapper">
							<img alt="" src="<?php echo get_core_theme_path() ?>images/logo-backend.png"/>
						</a>
					</div>
					<div class="m-stack__item m-stack__item--middle m-brand__tools">
						<!-- BEGIN: Responsive Aside Left Menu Toggler -->
						<a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
							<span></span>
						</a>
						<!-- END -->
						<!-- BEGIN: Responsive Header Menu Toggler -->
						<a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
							<span></span>
						</a>
						<!-- END -->
						<!-- BEGIN: Topbar Toggler -->
						<a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
							<i class="flaticon-more"></i>
						</a>
						<!-- BEGIN: Topbar Toggler -->
					</div>
				</div>
			</div>
			<!-- END: Brand -->
			<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
				<?php //include_once 'tplHeader7Menu.php';?>
				<!-- BEGIN: Topbar -->
				<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
					<div class="m-stack__item m-stack__item--middle m-dropdown m-dropdown--arrow m-dropdown--large m-dropdown--mobile-full-width m-dropdown--align-right m-dropdown--skin-light m-header-search m-header-search--expandable m-header-search--skin-light" id="m_quicksearch" data-search-type="default">
						<!--BEGIN: Search Form -->
						<form class="m-header-search__form">
							<div class="m-header-search__wrapper" id="remote">
								<span class="m-header-search__icon-search" id="m_quicksearch_search">
									<i class="flaticon-search"></i>
								</span>
								<span class="m-header-search__input-wrapper">
									<input autocomplete="off" type="text" name="q" class="m-header-search__input typeahead" value="" placeholder="Search..." id="typeahead">
								</span>
								<span class="m-header-search__icon-close" id="m_quicksearch_close">
									<i class="la la-remove"></i>
								</span>
								<span class="m-header-search__icon-cancel" id="m_quicksearch_cancel">
									<i class="la la-remove"></i>
								</span>
							</div>
						</form>
						<!--END: Search Form -->
						<!--BEGIN: Search Results -->
						<div class="m-dropdown__wrapper">
							<div class="m-dropdown__arrow m-dropdown__arrow--center"></div>
							<div class="m-dropdown__inner">
								<div class="m-dropdown__body">
									<div class="m-dropdown__scrollable m-scrollable" data-max-height="300" data-mobile-max-height="200">
										<div class="m-dropdown__content m-list-search m-list-search--skin-light"></div>
									</div>
								</div>
							</div>
						</div>
						<!--BEGIN: END Results -->
					</div>
					<div class="m-stack__item m-topbar__nav-wrapper">
						<ul class="m-topbar__nav m-nav m-nav--inline">

						<?php if(USER_LEVEL == 'admin'){ ?>						
							<li class="m-nav__item m-topbar__notifications m-dropdown m-dropdown--large m-dropdown--arrow m-dropdown--align-right 	m-dropdown--mobile-full-width" data-dropdown-toggle="click" data-dropdown-persistent="true">
								<a href="#" class="m-nav__link m-dropdown__toggle" id="">
									<span class="m-nav__link-badge m-badge m-badge--dot m-badge--dot-small m-badge--danger"></span>
									<span class="m-nav__link-icon">
										<span class="m-nav__link-icon-wrapper">
											<i class="flaticon-music-2"></i>
										</span>
									</span>
								</a>
								<div class="m-dropdown__wrapper">
								<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
									<div class="m-dropdown__inner">
										<div class="m-dropdown__header m--align-center">
											<span class="m-dropdown__header-title">
												9 New
											</span>
											<span class="m-dropdown__header-subtitle">
												User Notifications
											</span>
										</div>
										<div class="m-dropdown__body">
											<div class="m-dropdown__content">
												<ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand" role="tablist">
													<li class="nav-item m-tabs__item">
														<a class="nav-link m-tabs__link active" data-toggle="tab" href="#topbar_notifications_notifications" role="tab">
															Alerts
														</a>
													</li>
													<li class="nav-item m-tabs__item">
														<a class="nav-link m-tabs__link" data-toggle="tab" href="#topbar_notifications_events" role="tab">
															Events
														</a>
													</li>
													<li class="nav-item m-tabs__item">
														<a class="nav-link m-tabs__link" data-toggle="tab" href="#topbar_notifications_logs" role="tab">
															Logs
														</a>
													</li>
												</ul>
												<div class="tab-content">
													<div class="tab-pane active"  id="topbar_notifications_notifications" role="tabpanel">
														<div class="m-scrollable" data-scrollable="true" data-max-height="250" data-mobile-max-height="200">
															<div class="m-list-timeline m-list-timeline--skin-light">
																<div class="m-list-timeline__items" id="topbar_notifications_container">


																</div>
															</div>
														</div>
													</div>
													<div class="tab-pane" id="topbar_notifications_events" role="tabpanel">
														<div class="m-scrollable" m-scrollabledata-scrollable="true" data-max-height="250" data-mobile-max-height="200">
															<div class="m-list-timeline m-list-timeline--skin-light">
																<div class="m-list-timeline__items">

																</div>
															</div>
														</div>
													</div>
													<div class="tab-pane" id="topbar_notifications_logs" role="tabpanel">
														<div class="m-stack m-stack--ver m-stack--general" style="min-height: 180px;">
															<div class="m-stack__item m-stack__item--center m-stack__item--middle">
																<span class="">
																	All caught up!
																	<br>
																	No new logs.
																</span>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</li>
							<li class="m-nav__item m-topbar__quick-actions m-dropdown m-dropdown--skin-light m-dropdown--large m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--mobile-full-width m-dropdown--skin-light"  data-dropdown-toggle="click">
								<a href="#" class="m-nav__link m-dropdown__toggle">
									<span class="m-nav__link-badge m-badge m-badge--dot m-badge--info m--hide"></span>
									<span class="m-nav__link-icon">
										<span class="m-nav__link-icon-wrapper">
											<i class="flaticon-plus"></i>
										</span>
									</span>
								</a>
								<div class="m-dropdown__wrapper">
									<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
									<div class="m-dropdown__inner">
										<div class="m-dropdown__header m--align-center">
											<span class="m-dropdown__header-title">
												Quick Actions
											</span>
											<span class="m-dropdown__header-subtitle">
												Shortcuts
											</span>
										</div>
										<div class="m-dropdown__body m-dropdown__body--paddingless">
											<div class="m-dropdown__content">
												<div class="m-scrollable" data-scrollable="false" data-max-height="380" data-mobile-max-height="200">
													<div class="m-nav-grid m-nav-grid--skin-light">
														<div class="m-nav-grid__row">
															<a href="javascript:;" class="m-nav-grid__item" onclick="
																let data = {};
																data['data'] = {'action':'new','module':'lead'};
																data['isAjax'] = true;
																data['e'] = this;
																let formClass = new FormGenerator(data);
																formClass.actionCallBacks = {
																	'save':'doNothing',
																	'update':'doNothing'
																};">
																<i class="m-nav-grid__icon flaticon-user"></i>
																<span class="m-nav-grid__text">
																	New Customer
																</span>
															</a>
															<a href="javascript:;" class="m-nav-grid__item" onclick="
																let data = {};
																data['data'] = {'action':'new','module':'product'};
																data['isAjax'] = true;
																data['e'] = this;
																let formClass = new FormGenerator(data);
																formClass.actionCallBacks = {
																	'save':'doNothing',
																	'update':'doNothing'
																};">
																<i class="m-nav-grid__icon flaticon-cart"></i>
																<span class="m-nav-grid__text">
																	New Product
																</span>
															</a>
														</div>
														<div class="m-nav-grid__row">
															<a href="javascript:;" class="m-nav-grid__item" onclick="
																let data = {};
																data['data'] = {'action':'new','module':'users'};
																data['isAjax'] = true;
																data['e'] = this;
																let formClass = new FormGenerator(data);
																formClass.actionCallBacks = {
																	'save':'doNothing',
																	'update':'doNothing'
																};">
																<i class="m-nav-grid__icon flaticon-user"></i>
																<span class="m-nav-grid__text">
																	New Employee
																</span>
															</a>
															<a href="javascript:;" class="m-nav-grid__item" onclick="
																let data = {};
																data['data'] = {'action':'new','module':'os'};
																data['isAjax'] = true;
																data['e'] = this;
																let formClass = new FormGenerator(data);
																formClass.actionCallBacks = {
																	'save':'doNothing',
																	'update':'doNothing'
																};">
																<i class="m-nav-grid__icon flaticon-laptop"></i>
																<span class="m-nav-grid__text">
																	New Operating System
																</span>
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</li>
							<?php } ?>
							<li class="m-nav__item m-topbar__user-profile  m-dropdown m-dropdown--medium m-dropdown--arrow  m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
								<a href="#" class="m-nav__link m-dropdown__toggle">
									<span class="m-nav__link-icon">
										<span class="m-nav__link-icon-wrapper">
											<i class="flaticon-user"></i>
										</span>
									</span>
									<span class="m-topbar__username m--hide">
										<?php echo $__USER_DATA__['firstname'] ?>
									</span>
								</a>
								<div class="m-dropdown__wrapper">
									<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
									<div class="m-dropdown__inner">
										<div class="m-dropdown__header m--align-center" style="">
											<div class="m-card-user m-card-user--skin-dark">
												<div class="m-card-user__details">
													<span class="m-card-user__name m--font-weight-500">
														<?php echo $__USER_DATA__['display_name'] ?>
													</span>
													<a href="" class="m-card-user__email m--font-weight-300 m-link">
														<?php echo $__USER_DATA__['email'] ?>
													</a>
												</div>
											</div>
										</div>
										<div class="m-dropdown__body">
											<div class="m-dropdown__content">
												<ul class="m-nav m-nav--skin-light">
													<li class="m-nav__section m--hide">
														<span class="m-nav__section-text">
															Section
														</span>
													</li>
													<?php if(USER_LEVEL == 'admin') { ?>
													<li class="m-nav__item">
														<a href="javascript:;" class="m-nav__link" onclick="
																let data = {};
																data['data'] = {'action':'edit_password_single','module':'users','id':<?php echo get_user_id() ?>};
																data['isAjax'] = true;
																data['e'] = this;
																let formClass = new FormGenerator(data);
																formClass.actionCallBacks = {
																	'save':'doNothing',
																	'update':'doNothing'
																};
															">
															<i class="m-nav__link-icon flaticon-profile-1"></i>
															<span class="m-nav__link-title">
																<span class="m-nav__link-wrap">
																	<span class="m-nav__link-text">
																		Change Your Password
																	</span>
																</span>
															</span>
														</a>
													</li>
													<li class="m-nav__item">
														<a href="javascript:;" class="m-nav__link"  onclick="
																let data = {};
																data['data'] = {'action':'edit_password_multiple','module':'users'};
																data['isAjax'] = true;
																data['e'] = this;
																let formClass = new FormGenerator(data);
																formClass.actionCallBacks = {
																	'save':'doNothing',
																	'update':'doNothing'
																};
															">>
															<i class="m-nav__link-icon flaticon-users"></i>
															<span class="m-nav__link-text">
																Change Employee Password
															</span>
														</a>
													</li>
													<?php } ?>						
													<li class="m-nav__separator m-nav__separator--fit"></li>
													<li class="m-nav__item">
														<a href="<?php echo SITE_PATH ?>Logout" class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
															Logout
														</a>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</li>
<!-- 							<li id="m_quick_sidebar_toggle" class="m-nav__item">
								<a href="#" class="m-nav__link m-dropdown__toggle">
									<span class="m-nav__link-icon m-nav__link-icon-alt">
										<span class="m-nav__link-icon-wrapper">
											<i class="flaticon-grid-menu"></i>
										</span>
									</span>
								</a>
							</li> -->
						</ul>
					</div>
				</div>
				<!-- END: Topbar -->
			</div>
		</div>
	</div>
</header>
<script>
function showNotifications(data){
	$.each(data['data'],function(k,item){
		console.log(k);
		var html = 
		'\
		<div class="m-list-timeline__item">\
			<span class="m-list-timeline__badge -m-list-timeline__badge--state-success"></span>\
			<span class="m-list-timeline__text">\
				'+item['notification_about']+'\
			</span>\
			<span class="m-list-timeline__time">\
				'+getReadableDate(item['date_created'])+'\
			</span>\
		</div>\
		';
		$('#topbar_notifications_container').append(html);
	});
}
</script>
<!-- END: Header -->