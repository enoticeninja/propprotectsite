<?php 
include_once(DIR_SITE_ROOT."bootstrap.php");
include_once(DIR_SITE_ROOT."common.php");
$jsFunction = '';
$toolsAll = array();
//$module = (!IS_SUBFOLDER) ? get_current_url_array(1) : get_current_url_array(2);
$module = 'dashboard';
include_once('Adapter.php');
?>
<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
<?php include_once $tpl_path.'tplHead.php'; ?>
<link href="<?php echo HTTP_THEME_PATH ?>assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
</head>
<!-- end::Head -->
    <!-- end::Body -->
<body class="<?php echo get_body_class() ?>">
	<!-- begin:: Page -->
	<div class="m-grid m-grid--hor m-grid--root m-page">
		<?php include_once $tpl_path.'tplHeader3.php'; ?>
	<!-- begin::Body -->
		<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
			<?php include_once $tpl_path.'tplSidebar3.php'; ?>
			<div class="m-grid__item m-grid__item--fluid m-wrapper">
				<!-- BEGIN: Subheader -->
				<div class="m-subheader ">
					<div class="d-flex align-items-center">
						<div class="mr-auto">
							<h3 class="m-subheader__title ">
								Dashboard
							</h3>
							
								
													
						</div>
					</div>
				</div>
				<!-- END: Subheader -->
				<div class="m-content">
					<!--Begin::Section-->
					<div class="row">
						<div class="col-xl-6">
							<!--begin:: Widgets/Top Products-->
							<div class="m-portlet  ">
								<div class="m-portlet__head">
									<div class="m-portlet__head-caption">
										<div class="m-portlet__head-title">
											<h3 class="m-portlet__head-text">
												
											</h3>
										</div>
									</div>
								</div>
								<div class="m-portlet__body" >
									<div id="chart_5" class="chart" style="height: 400px;">
									</div>
								</div>
							</div>
							<!--end:: Widgets/Top Products-->
						</div>
						<div class="col-xl-6">
							<!--begin:: Widgets/Top Products-->
							<div class="m-portlet">
								<div class="m-portlet__head">
									<div class="m-portlet__head-caption">
										<div class="m-portlet__head-title">
											<h3 class="m-portlet__head-text">
												
											</h3>
										</div>
									</div>
								</div>
								<div class="m-portlet__body" >
									<div id="chart_8" class="chart" style="height: 400px;">
									</div>
								</div>
							</div>
							<!--end:: Widgets/Top Products-->
						</div>
						</div>

					<!--End::Section-->

				</div>
			</div>
		</div>
		<!-- end:: Body -->
		<?php include_once $tpl_path.'tplFooter.php'; ?>
	</div>
	<!-- end:: Page -->
	<?php //include_once $tpl_path.'tplQuickSidebar.php'; ?>    

	<?php //include_once $tpl_path.'tplQuickNav.php'; ?>
	<?php include_once $tpl_path.'tplFooterJs.php'; ?>
	<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/Chart.min.js" type="text/javascript"></script>
	<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/chartist.min.js" type="text/javascript"></script>
	<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/custom/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>
	<script src="<?php echo HTTP_THEME_PATH ?>assets/vendors/base/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
	<script src="<?php echo get_core_theme_path()?>assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
	<script src="<?php echo get_core_theme_path()?>assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
	<script src="<?php echo get_core_theme_path()?>assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
	<script src="<?php echo get_core_theme_path()?>assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
	<script src="<?php echo get_core_theme_path()?>assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
	<script src="<?php echo get_core_theme_path()?>assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
	<script src="<?php echo get_core_theme_path()?>assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
	<script src="<?php echo get_core_theme_path()?>assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
	<script src="<?php echo get_core_theme_path()?>assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>

<script>
$(document).ready(function() {
	//handleAnimatedPieChart();
	//initChartSample5();
	//ClicksPieChart();
	//handleAnimatedDonutChart();

	$element = {};
	$element['unique_id'] = 'dashboarddaterange';
	$element['label'] = '';
	$element['id'] = 'dashboarddaterange-daterange';
	$element['start_date'] = 'start_date';
	$element['end_date'] = 'end_date';
	$new_field = 
	'\
		<label class="control-label ">'+$element['label']+'</label></br>\
		<div id="'+$element['id']+'" class="tooltips btn btn-fit-height blue" data-placement="top" data-original-title="Change report date range">\
			<i class="icon-calendar"></i>\
			<span class="thin uppercase hidden-xs"></span>\
			<i class="fa fa-angle-down"></i>\
		</div>\
		<input type="hidden" name="__NAME_PREFIX__['+$element['start_date']+']" id="'+$element['unique_id']+'-'+$element['start_date']+'" value="">\
		<input type="hidden" name="__NAME_PREFIX__['+$element['end_date']+']" id="'+$element['unique_id']+'-'+$element['end_date']+'" value="">\
	';
	$('#dashboard-daterange-container').html($new_field);
	var $tempDate = {
			'element':$element['id'],
			'startdate':''+$element['unique_id']+'-'+$element['start_date'],
			'enddate':''+$element['unique_id']+'-'+$element['end_date'],
			'button':''	
	};
	if(isset($element['drops'])) $tempDate['drops'] = $element['drops'];
	if(isset($element['opens'])) $tempDate['opens'] = $element['opens'];
	if(isset($element['range'])) $tempDate['range'] = $element['range'];

	var jsonDate = $tempDate;
	initDateRangePickerCommon(jsonDate);
});
function ClicksPieChart(){
	$.ajax({
		type:'POST',
		data: {'type': 'all'},		
		url: 'ajaxGetStats.php',
		success: function(val){
			console.log(val);
			var value = JSON.parse(val);

			if(value.ajaxmessage == 'success') {
				initChartSample5(value.piestats);				
				handleAnimatedDonutChart(value.donutstats);				
				handleAnimatedPieChart(value.donutstats);				
			}
			else {
				
			}			  

		}
	});	
	
}
    var initChartSample5 = function(data) {

		var chart = AmCharts.makeChart("chart_5", {
            "theme": "light",
            "type": "serial",
            "startDuration": 2,
            "fontSize": 16,

            "fontFamily": 'Poppins',
            
            "color":    '#888',

            "dataProvider": data,
            "valueAxes": [{
                "position": "left",
                "axisAlpha": 0,
                "minMaxMultiplier": 1,
                "precision": 0,
                "gridAlpha": 0
            }],
            "graphs": [{
                "balloonText": "[[label]]: <b>[[value]]</b>",
                "colorField": "color",
                "fillAlphas": 0.85,
                "lineAlpha": 0.1,
                "type": "column",
                "topRadius": 1,
                "valueField": "count"
            }],
            "depth3D": 40,
            "angle": 30,
            "chartCursor": {
                "categoryBalloonEnabled": false,
                "cursorAlpha": 0,
                "zoomable": false
            },
            "categoryField": "affiliate",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "gridAlpha": 0

            },
            "exportConfig": {
                "menuTop": "20px",
                "menuRight": "20px",
                "menuItems": [{
                    "icon": '/lib/3/images/export.png',
                    "format": 'png'
                }]
            }
        }, 0);

		
        $('#chart_5').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }

	
	var handleAnimatedPieChart = function(data) {
		var chart = AmCharts.makeChart( "chart_7", {
		  "type": "pie",
		  "theme": "light",
		  "fontFamily": 'Poppins',
		  "fontSize": 16,
		  "dataProvider": data,
		  "valueField": "value",
		  "titleField": "country",
		  "outlineAlpha": 0.4,
		  "depth3D": 15,
		  "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
		  "angle": 30,
		  "export": {
			"enabled": true
		  }
		} );
			
	}

var handleAnimatedDonutChart = function(data) {
		var chart = AmCharts.makeChart( "chart_8", {
		  "type": "pie",
		  "theme": "light",
		  "fontFamily": 'Poppins',
		  "fontSize": 16,
		  "titles": [ {
			"text": "Product Sales",
			"size": 16
		  } ],
		  "dataProvider": data,
		  "valueField": "count",
		  "titleField": "affiliate",
		  "startEffect": "elastic",
		  "startDuration": 2,
		  "labelRadius": 15,
		  "innerRadius": "50%",
		  "depth3D": 10,
		  "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[label]]</b> ([[percents]]%)</span>",
		  "angle": 15,
		  "export": {
			"enabled": true
		  }
		} );
}	

</script>
</body>
	<!-- end::Body -->
</html>
