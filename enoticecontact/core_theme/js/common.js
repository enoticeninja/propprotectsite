
/* function startLoading(){
	App.blockUI({target:"html",message:"Processing...",zIndex:'99999999',overlayColor:'#333333'});
}


function stopLoading(){
	App.unblockUI("html")
}
 */
var __FORM_WRAPPER__ = '.form-group';
var __FORM_ELEMENT_ADDON__ = '.input-group-addon';
var __FORM_ELEMENT_HELP__ = '.help-block';
var __ERROR_STATE__ = 'has-error';
var __WARNING_STATE__ = 'has-warning';
var __SUCCESS_STATE__ = 'has-success';
var __INFO_STATE__ = 'has-info';
var __SUCCESS_ICON__ = 'mdi-action-thumb-up';
var __SUCCESS_ICON_FONT__ = 'font-green-jungle';
var __ERROR_ICON__ = 'mdi-alert-warning';
var __ERROR_ICON_FONT__ = 'font-red-thunderbird';

function startLoading(){
	$('.__common_loading_element__').removeClass('hidden');
}
function startLoadingElement(element){///ELEMENT is jquery object
	var loader = 
	'\
	<div class="common-custom-loader" style="position:absolute;background:#8282826b;top:0px;left:0px;width:100%;height:100%;z-index:99999">\
	<div class="lds-ring absolute-center" style=""><div></div><div></div><div></div><div></div></div>\
	</div>\
	';
	$(element).prepend(loader);
}
function stopLoadingElement(element){///ELEMENT is jquery object
	$(element).find('.common-custom-loader').remove();
}


function stopLoading(){
	$('.__common_loading_element__').addClass('hidden');
}


function showSweetSuccess(title,message){
    swal({
    title: title,
    text: message,
    type: 'success',
    allowOutsideClick: false,
    showConfirmButton: true,
    showCancelButton: false,
    confirmButtonClass: 'btn green'
    });    
}

function showSweetError(title,message){
    swal({
    title: title,
    text: message,
    type: 'error',
    allowOutsideClick: false,
    showConfirmButton: true,
    showCancelButton: false,
    confirmButtonClass: 'btn red'
    });    
}

if ($.fn.toastr) {
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
}

		
				
function showMessage(errType,message,title){
	toastr[errType](message,title);	
}

function getUniqueID(prefix) {
	return 'prefix_' + Math.floor(Math.random() * (new Date()).getTime());
}			 



function displayInfoInContainer(message,element,type,close,closeInSeconds){
    if(typeof close === undefined) close = true;
    if(typeof close === undefined) close = 1000;
    if(typeof type === undefined) type = 'warning';

    App.alert({
        container: $('#'+element+''), // alerts parent container 
        place: 'prepend', // append or prepent in container 
        type: type, // alert's type 
        message: message, // alert's message
        close: close, // make alert closable 
        reset: false, // close all previouse alerts first 
        focus: false, // auto scroll to the alert after shown 
        closeInSeconds: closeInSeconds, // auto close after defined seconds 
        icon:'fa fa-warning' // put icon class before the message
    });    
}
	 
function displayError(message){
	alert({
		container: formWrapper, // alerts parent container(by default placed after the page breadcrumbs)
		place: 'prepend', // append or prepent in container 
		type: 'danger',  // alert's type
		message: message,  // alert's message
		close: true, // make alert closable
		reset: false, // close all previouse alerts first
		focus: true, // auto scroll to the alert after shown
		closeInSeconds: 10, // auto close after defined seconds
		icon: 'check' // put icon before the message
	});

}

function showErrorOnMtTab(elem,msg){
	$('#'+elem+'').addClass('error');		
	$('#'+elem+'').find('.mt-step-icon').addClass('hidden');
	$('#'+elem+'').find('.mt-step-error-icon').removeClass('hidden');	
}

function resetMtTabState(elem){ 
	$('#'+elem+'').removeClass('error');		
	$('#'+elem+'').find('.mt-step-icon').removeClass('hidden');
	$('#'+elem+'').find('.mt-step-error-icon').addClass('hidden');	
}

function showErrorOnElement(elem,msg){
    resetElementState(elem);
	$('#'+elem+'').addClass('edited');
	$('#'+elem+'').addClass(__ERROR_STATE__);
	if($('#'+elem+'').closest(__FORM_WRAPPER__).length){
		$('#'+elem+'').closest(__FORM_WRAPPER__).addClass('is-focused');
		$('#'+elem+'').closest(__FORM_WRAPPER__).addClass(__ERROR_STATE__);
		$('#'+elem+'').closest(__FORM_WRAPPER__).find('.form-label').addClass(__ERROR_STATE__);
		$('#'+elem+'').closest(__FORM_WRAPPER__).find('.control-label').addClass(__ERROR_STATE__);
		$('#'+elem+'').closest(__FORM_WRAPPER__).find(__FORM_ELEMENT_ADDON__).html('<i class="'+__ERROR_ICON__+' '+__ERROR_ICON_FONT__+' "></i>');
		$('#'+elem+'').closest(__FORM_WRAPPER__).find(__FORM_ELEMENT_HELP__).css('opacity','1');
		$('#'+elem+'').closest(__FORM_WRAPPER__).find(__FORM_ELEMENT_HELP__).html(msg);
		$('#'+elem+'').closest(__FORM_WRAPPER__).find(__FORM_ELEMENT_HELP__).addClass('highlight-element');
	}
	else{
		showErrorPopoversOnElements(elem,msg);
	}
	//console.log($('#'+elem+'').attr('data-showpopovererror'));
	//console.log($('#'+elem+'').attr('id'));
	if($('#'+elem+'').attr('data-showpopovererror')){
		showErrorPopoversOnElements(elem,msg);
	}
    //showErrorPopoversOnElements(elem,msg);

}

function showSuccessOnElement(elem,msg){
    resetElementState(elem);
    var iconn = '';
    if (tools.hasOwnProperty('icons') && tools['icons'].hasOwnProperty(elem)) {
        iconn = tools['icons'][elem];
    }
    var helpp = '';	
    if (tools.hasOwnProperty('help') && tools['help'].hasOwnProperty(elem)) {
        helpp = tools['help'][elem];
    }
    if(msg == '' || typeof msg === 'undefined') msg = helpp;
	$('#'+elem+'').closest(__FORM_WRAPPER__).addClass(__SUCCESS_STATE__);				
	$('#'+elem+'').closest(__FORM_WRAPPER__).find(__FORM_ELEMENT_ADDON__).html('<i class="'+iconn+' '+__SUCCESS_ICON_FONT__+' "></i><i class="'+__SUCCESS_ICON__+' '+__SUCCESS_ICON_FONT__+'"></i>');
	$('#'+elem+'').closest(__FORM_WRAPPER__).find(__FORM_ELEMENT_HELP__).css('opacity','1');		
	$('#'+elem+'').closest(__FORM_WRAPPER__).find(__FORM_ELEMENT_HELP__).html(msg);
	$('#'+elem+'').closest(__FORM_WRAPPER__).find(__FORM_ELEMENT_HELP__).addClass('highlight-element');
	$('#'+elem+'').addClass('edited');
	if(BOOTSTRAP_VERSION == '4'){
		try{$('#'+elem+'-holder').popover('dispose');}catch(ex){}
		try{$('#'+elem+'').popover('dispose');}catch(ex){}
	}
	else{
		try{$('#'+elem+'-holder').popover('destroy');}catch(ex){}
		try{$('#'+elem+'').popover('destroy');}catch(ex){}
	}
}


function resetElementState(elem){
    var iconn = '';	
    if (tools.hasOwnProperty('icons') && tools['icons'].hasOwnProperty(elem)) {
        iconn = tools['icons'][elem];
    }		

    var titt = '';	
    if (tools.hasOwnProperty('titles') && tools['titles'].hasOwnProperty(elem)) {
        titt = tools['titles'][elem];
    }
    var helpp = '';	
    if (tools.hasOwnProperty('help') && tools['help'].hasOwnProperty(elem)) {
        helpp = tools['help'][elem];
    }    
	$('#'+elem+'').closest(__FORM_WRAPPER__).removeClass(''+__SUCCESS_STATE__+' '+__ERROR_STATE__+' '+__WARNING_STATE__+' '+__INFO_STATE__+'');				
	$('#'+elem+'').closest(__FORM_WRAPPER__).find(__FORM_ELEMENT_ADDON__).html('<i class="'+iconn+'"></i>');
	$('#'+elem+'').closest(__FORM_WRAPPER__).find(__FORM_ELEMENT_HELP__).css('opacity','1');		
	$('#'+elem+'').closest(__FORM_WRAPPER__).find(__FORM_ELEMENT_HELP__).html(helpp);
	$('#'+elem+'').closest(__FORM_WRAPPER__).find(__FORM_ELEMENT_HELP__).removeClass('highlight-element');
	//$('#'+elem+'').removeClass('edited');

	if(BOOTSTRAP_VERSION == '4'){
		try{$('#'+elem+'-holder').popover('dispose');}catch(ex){}
		try{$('#'+elem+'').popover('dispose');}catch(ex){}
	}
	else{
		try{$('#'+elem+'-holder').popover('destroy');}catch(ex){}
		try{$('#'+elem+'').popover('destroy');}catch(ex){}
	}
	
}

function AppendAndSubmitForm(e, url){
	startLoading();
	var val = $(e).closest('td').siblings(':first-child').text();
	var form = $('<form action="' + url + '" method="post">' +
	  '<input type="text" name="caseid" value="'+val+'" />' +
	  '</form>');
	$('body').append(form);
	form.submit();	
}

function ShowBsModal(id){    
    $('#'+id+'').modal('show');     

}
$('.modal').on('show.bs.modal', function(e) {
    var inAnimation = $(e.target).attr('data-in-animation');  
    if( inAnimation != null){
        $(e.target).addClass(inAnimation);
    }
});
function CloseBsModal(id,$this){
    if(id != ''){
        var $modal = $('#'+id+'');
    }
    else{
        var $modal = $($this).closest('.modal');
    }
    var inAnimation = $modal.attr('data-in-animation');
    var outAnimation = $modal.attr('data-out-animation');
    if( inAnimation != null){
        $modal.removeClass(inAnimation);
    }

    var delay = $modal.attr('data-delay') || 700;
	$modal.addClass(outAnimation);
	window.setTimeout(function() {
		$modal.modal('hide');
		//$modal.find('.modal-dialog').remove();
		$modal.removeClass(outAnimation);
		$modal.addClass(inAnimation);
		//if(id != '')$modal.remove();
	}, delay);
}
function RemoveBsModal(id,$this){
    if(id != ''){
        var $modal = $('#'+id+'');
    }
    else{
        var $modal = $($this).closest('.modal');
    }
    var inAnimation = $modal.attr('data-in-animation');
    var outAnimation = $modal.attr('data-out-animation');
    if( inAnimation != null){
        $modal.removeClass(inAnimation);
    }

    var delay = $modal.attr('data-delay') || 700;
	$modal.addClass(outAnimation);
	window.setTimeout(function() {
		$modal.modal('hide');
		$modal.find('.modal-dialog').remove();
		$modal.removeClass(outAnimation);
		$modal.addClass(inAnimation);
		$modal.remove();
	}, delay);
}

function EnableEditing(fields, e){
		$(e).hide();
		jQuery.each(fields, function(i, elem){
			$('#'+elem+'').attr("readonly", false); 
			$('#'+elem+'').prop("disabled", false); 				
		});
}

var animations = ['bounce','flash','pulse','rubberBand','shake','headShake','swing','tada','wobble','jello','bounceIn','bounceInDown','bounceInLeft','bounceInRight','bounceInUp','bounceOut','bounceOutDown','bounceOutLeft','bounceOutRight','bounceOutUp','fadeIn','fadeInDown','fadeInDownBig','fadeInLeft','fadeInLeftBig','fadeInRight','fadeInRightBig','fadeInUp','fadeInUpBig','fadeOut','fadeOutDown','fadeOutDownBig','fadeOutLeft','fadeOutLeftBig','fadeOutRight','fadeOutRightBig','fadeOutUp','fadeOutUpBig','flipInX','flipInY','flipOutX','flipOutY','lightSpeedIn','lightSpeedOut','rotateIn','rotateInDownLeft','rotateInDownRight','rotateInUpLeft','rotateInUpRight','rotateOut','rotateOutDownLeft','rotateOutDownRight','rotateOutUpLeft','rotateOutUpRight','hinge','rollIn','rollOut','zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp','zoomOut','zoomOutDown','zoomOutLeft','zoomOutRight','zoomOutUp','slideInDown','slideInLeft','slideInRight','slideInUp','slideOutDown','slideOutLeft','slideOutRight','slideOutUp'];

function Validate(fields, messages){
	var input, isReady = true;
	//var anim = animations[Math.floor(Math.random() * animations.length)];
	var anim = 'headShake';
	var anim_icon = 'bounceInDown';
	jQuery.each(fields, function(i, elem){
	var field = $('#'+elem+'');
		if($(field).val().trim() == ''){
			isReady = false;
			$(field).closest(__FORM_WRAPPER__).addClass(''+__ERROR_STATE__+' animated '+anim+'');
			$(field).closest(__FORM_WRAPPER__).find(__FORM_ELEMENT_ADDON__).html('<i class="fa fa-warning font-red animated '+anim_icon+'"></i>');
			$(field).closest(__FORM_WRAPPER__).find(__FORM_ELEMENT_HELP__).html(messages[i]);
			$(field).addClass('edited');
		}
	});	
	setTimeout(function(){ 
		jQuery.each(validation_fields, function(i, elem){
			var field = $('#'+elem+'');
			$(field).closest(__FORM_WRAPPER__).removeClass(''+anim+'');
			$(field).closest(__FORM_WRAPPER__).find(''+__FORM_ELEMENT_ADDON__+' .fa-warning').removeClass(''+anim_icon+'');
		});	
	}, 2000);	
	return isReady;
}

function SearchByDateRange(){
	startLoading();			
			$.ajax({
				type:'POST',
				url: '',
				data: {'search':'search','start':startDateRange,'end':endDateRange},
				success: function(val){				
					var value = JSON.parse(val);

					stopLoading();										
					if(value.ajaxresult == true) {
						$('#table-pagination').html(value['table_pagination']);
						$('#table-pagination-bottom').html(value['table_pagination']);	
						$('#rows_to_display_container').html(value['rows_to_display']);								
						tbody.html(value['table_body']);
						
					}
					else {
						stopLoading();								
					}
					stopLoading();						
				}
			});	
}


function fetchData(form,action,url,exFunc,elem1,elem2,elem3,elem4,elem5){
	startLoading();	
	var formData = $('#'+form+'').serializeArray();
		if(action != '') {
		formData.push({name:'action', value:action});		
		}
		$.ajax({
		type:'POST',
		data: formData,		
		url: url,
		success: function(val){
			var value = JSON.parse(val);

			if(value.ajaxresult == 'success' || value.ajaxresult == true) {
				showMessage('success','Updated the details Succesfully', 'Done');	
				if(elem1 != '' && value.hasOwnProperty('elem1') ) {
					$('#'+elem1+'').append(value.elem1);
				}
				if(elem2 != '' && value.hasOwnProperty('elem2') ) {
					$('#'+elem2+'').append(value.elem2);
				}	
				if(elem3 != '' && value.hasOwnProperty('elem3') ) {
					$('#'+elem3+'').append(value.elem3);
				}	
				if(elem4 != '' && value.hasOwnProperty('elem4') ) {
					$('#'+elem4+'').append(value.elem4);
				}	
				if(elem5 != '' && value.hasOwnProperty('elem5') ) {
					$('#'+elem5+'').append(value.elem5);
				}
				if(exFunc != '' ) {
				window[exFunc]();
				}				

			}
			else {
				showMessage('error','Not Updated the details', 'Please try again');				
			}
		stopLoading();					
		}
	});
	
}

	function ReportRangeInit(elem){
		$("#"+elem+"").daterangepicker({
					opens: App.isRTL() ? "left" : "right",
					startDate: moment().subtract("days", 29),
					endDate: moment(),
					minDate: "01/01/2016",
					maxDate: "12/31/2040",
					dateLimit: {
						days: 10000
					},
					showDropdowns: !0,
					showWeekNumbers: !0,
					timePicker: !1,
					timePickerIncrement: 1,
					timePicker12Hour: !0,
					ranges: {
						"One Week": [moment(), moment().add("days", 6)],
						"One Month": [moment(), moment().add("month", 1)],
						"Three Months": [moment(), moment().add("month", 2)],
						"Six Months": [moment(), moment().add("month", 5)],
						"One Year": [moment(), moment().add("month", 11)],
						"This Month": [moment().startOf("month"), moment().endOf("month")]
					},
					buttonClasses: ["btn"],
					applyClass: "green",
					cancelClass: "default",
					format: "MM/DD/YYYY",
					separator: " to ",
					locale: {
						applyLabel: "Apply",
						fromLabel: "From",
						toLabel: "To",
						customRangeLabel: "Custom Range",
						daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
						monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
						firstDay: 1
					}
				}, function(start, end) {
					$("#"+elem+" span").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
					$('#startdate').val(start.format('YYYY-MM-DD'));
					$('#enddate').val(end.format('YYYY-MM-DD'));						
				})	
}

function HighLightRequired(arr){
	if(typeof(arr) === 'undefined')
	{
		arr = validation_fields;
	}
	$.each(arr, function(i,v){
		$.each(v, function(i2,v2){
			$('#'+v2+'').closest(__FORM_WRAPPER__).find('label').prepend('<span class="font-red">* </span>');	
		});
	});
}

function SwapElementsInContainer(main,newElementHtml,anim1,anim2){
    $('#'+main+'').addClass('animated '+anim1+''); 
    window.setTimeout(function() { 
        $('#'+main+'').removeClass(''+anim1+'');                 
        $('#'+main+'').html('');                 
        $('#'+main+'').html(newElementHtml);                 
        $('#'+main+'').addClass(''+anim2+'');               
    }, 500);
    $('#'+main+'').removeClass(''+anim2+'');      
}

function explode(seperator,str) {
	var arr = [];
	arr = str.split(seperator);	
	return arr;
}

function isset(variable){
	var returnVar = false;
	if(typeof(variable) != "undefined" && variable !== null){
		returnVar = true;
	}
	return returnVar;
}

function str_replace(replace,replace_with,replace_in){
	var regex = new RegExp(""+replace+"", "g");
	replace_in = replace_in.replace(regex, replace_with);	
	return replace_in;
}

function getDefault($isset, $default) {
    if(typeof($isset) != "undefined" && $isset !== null){
		return $isset;
	}
	else{
		return $default;
	}
}

function reloadPage(data){
	window.location.reload();
}

function redirectToUrl(url){
	window.location = url;
}

function doNothing(data){
	console.log('nothing');
}