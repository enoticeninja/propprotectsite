
var trCount;
var checkedIds = [];
function uniqueid(){
    // always start with a letter (for DOM friendlyness)
    var idstr=String.fromCharCode(Math.floor((Math.random()*25)+65));
    do {                
        // between numbers and characters (48 is 0 and 90 is Z (42-48 = 90)
        var ascicode=Math.floor((Math.random()*42)+48);
        if (ascicode<58 || ascicode>64){
            // exclude all chars between : (58) and @ (64)
            idstr+=String.fromCharCode(ascicode);    
        }                
    } while (idstr.length<15);

    return (idstr);
}

function stringToDashedString(str){
	str = str.toLowerCase().replace(/ /g, '-');
	return str;
}

function CommonFunc(data,form){
	//calledFrom is where function is called from
    startLoading();
	if(typeof form !== 'undefined'){
        var data = $('#'+form+'').serializeArray();
        //jQuery.extend(data, data2[0]);        
    }

    $.ajax({
        type:'POST',
        url: '',
        data: data,
        success: function(val){	
            try {
                var value = JSON.parse(val);
            }
            catch(err) {
                console.log(val);
            } 			
            if(value.ajaxresult == true) {
                if (value.hasOwnProperty('jsFunction')) {
                     eval(value.jsFunction);
                }
                //showSweetSuccess('Success','Updated Succesfully');
                stopLoading();    
            }
            else {
                //showSweetError('Error','Could Not Update Details!!!');
                stopLoading();      
            }
        
        }
    });	
}

function ajax(data){
    $.ajax({
        type:'POST',
        url: '',
        data: data,
        success: function(val){	
            try {
                var value = JSON.parse(val);
            }
            catch(err) {
                console.log(val);
            } 			
            if(value.ajaxresult == true) {
                if (value.hasOwnProperty('jsFunction')) {
                     eval(value.jsFunction);
                }
                //showSweetSuccess('Success','Updated Succesfully');
                stopLoading();    
            }
            else {
                //showSweetError('Error','Could Not Update Details!!!');
                stopLoading();      
            }
        
        }
    });	
}

function CommonFunc2Btn(data,form,e,next_data,callback){
	$(e).addClass('ladda-button');
    var l = Ladda.create(e);
    l.start();
    tools = [];
    var checkedRequired = true;
    var checkedValidation = true;
	var validateFields = false;
	var checkedDuplicate = true;
	var unique_id = '';
	
	if(typeof e !== 'undefined' && $.trim(e) != ''){
		unique_id = $(e).data('unique_id');
		if(typeof(unique_id) != "undefined" && unique_id !== null){
			validateFields = true;
		}
	}
	if(typeof(data.unique_id) != "undefined" && data.unique_id !== null){
		unique_id = data.unique_id;
		validateFields = true;
	}
	
	if(validateFields){
		tools = (typeof toolsAll[unique_id] !== 'undefined') ? toolsAll[unique_id] : [];
		check = validateAndRequire(tools);
		checkedRequired = check['required'];
		checkedValidation = check['validated'];
		checkedDuplicate = check['duplicate'];
	}

	var formData = [];
	if(typeof form !== 'undefined' && $.trim(form) != ''){
        formData = $('#'+form+'').serializeArray();
    }
	if(typeof data !== 'undefined' && !$.isEmptyObject(data)){
		$.each(data,function(n,v){
			formData.push({name:n, value:v});
		});
	}	
	if(typeof next_data === 'undefined'){
		next_data = {};
    }
	formData['next_data'] = [];
	$.each(next_data,function(n,v){
		formData.push({name:n, value:v});
	});

	
    if(typeof e !== 'undefined' && $.trim(e) != '') formData.push({name:'button_clicked', value:$(e).attr('id')});
    if(checkedRequired && checkedValidation && checkedDuplicate){

        $.ajax({
            type:'POST',
            url: '',
            data: formData,
            success: function(val){
				
                try {
                    var value = JSON.parse(val);
                }
                catch(err) {
                    console.log(val);
                } 			
                if(value.ajaxresult == true) {
                    if (value.hasOwnProperty('jsFunction')) {
                         eval(value.jsFunction);
                    }
					if(typeof(callback) != 'undefined'){
						window[callback](value);
					}					
                }
                else { 
                
                }
            
            }
        }).always(function() { l.stop(); });
    }
    else{
        l.stop();
    }
}

function nestedObjectToFormData(){
	
}
function CommonFunc2(data,form,e,next_data,callback){
	return new Promise(function (resolve, reject) {	
		if(typeof(data.dont_load) == "undefined"){
			startLoading();
		}
		var checkedRequired = true; 
		var checkedValidation = true;
		var checkedDuplicate = true;
		var validateFields = false;
		var unique_id = '';
		
		if(typeof e !== 'undefined' && $.trim(e) != ''){
			unique_id = $(e).data('unique_id');
			if(typeof(unique_id) != "undefined" && unique_id !== null){
				validateFields = true;
			}
		}
		if(typeof(data.unique_id) != "undefined" && data.unique_id !== null){
			unique_id = data.unique_id;
			//validateFields = true;
		}

		var formData = [];
		if(typeof form !== 'undefined' && $.trim(form) != ''){
			formData = $('#'+form+'').serializeArray();
			if(unique_id && unique_id != '')validateFields = true;validateFields = true;
		}
		
		if(validateFields){
			tools = (typeof toolsAll[unique_id] !== 'undefined') ? toolsAll[unique_id] : [];
			check = validateAndRequire(tools);
			checkedRequired = check['required'];
			checkedValidation = check['validated'];
			checkedDuplicate = check['duplicate'];
		}
		if(typeof data !== 'undefined' && !$.isEmptyObject(data)){
			$.each(data,function(n,v){
				formData.push({name:n, value:v});
			});
		}	
		if(typeof next_data === 'undefined'){
			next_data = {};
		}
		formData['next_data'] = [];
		$.each(next_data,function(n,v){
			formData.push({name:n, value:v});
		});

		
		if(typeof e !== 'undefined' && $.trim(e) != '') formData.push({name:'button_clicked', value:$(e).attr('id')});

		if(checkedRequired && checkedValidation && checkedDuplicate){
			$.ajax({
				type:'POST',
				url: '',
				data: formData,
				success: function(val){
					try {
						var value = JSON.parse(val);
						if(value.ajaxresult == true) {
							if (value.hasOwnProperty('jsFunction')) {
								 eval(value.jsFunction);
							}
							
							if(callback && $.trim(callback) != ''){
								window[callback](value);
							}
							stopLoading();
						}
						else {
							stopLoading();
						}
						resolve(value);
					}
					catch(err) {
						console.log(err);
						console.log(val);
						resolve(val);
						stopLoading();
					}
				}
			});	
		}
		else{
			console.log('Not Validated');
			stopLoading();
		}
	});
}

function CommonFunc2Confirmation(data,form,e,next_actions,callback){
	return new Promise(function (resolve, reject) {
		
		$(e).confirmation({
			container: 'body',
			placement: 'left',
			singleton: false,
			popout: false,
			btnOkClass: 'btn btn-lg bg-less-glossy-info',
			btnCancelClass: 'btn btn-lg bg-less-glossy-error',
			onConfirm: function(){
				CommonFunc2(data,form,e,next_actions)
				.then(function (value) {
					//console.log('Ajax Success');
					if(callback && $.trim(callback) != ''){
						window[callback](value);
					}
					resolve(value);
				});					
			},
			onCancel: function () {
				$(this).confirmation( 'destroy' );
			}
		}); 
		
		$(e).confirmation( 'show' );
		
/*     var error_template = '<div class="popover has-error" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>';;
	$(e).popover({
		title: '<h6 class="font-white"><span class="fa fa-warning font-white"></span> Error</h6>',
		content : 'asdasdfasdasdasfd',
		trigger:'manual click',
		html: true,
		container: $(e).closest('div'),
		placement: 'bottom',
		template: error_template
	}).addClass('has-warning'); 
	$(e).popover('show');	*/
	});  
}

function removeTableRowsAfterBulkDelete(data){
	
}
function Delete(module,id,e) {
    CommonFunc2Confirmation({'action':'delete','id':id,'module':module},'',e)
	.then(function (value) {
		if(value.ajaxresult == true) {
			showMessage('success','Deleted Succesfully', 'Success!');
		}
		else {
			showMessage('error','Something went wrong', 'Error!');
			stopLoading();
		}
    });
}
function BulkDelete(e,module) {
	//startLoading();
	var boxeschecked = $('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked');
	var ids = [];
	$(boxeschecked).each(function() {
		var temp = $(this).val();
		ids.push(temp);
	});

    CommonFunc2Confirmation({'action':'bulk-delete','ids':ids,'module':module},'',e)
	.then(function (value) {
		$.each(ids,function(k,id){
			$('.tr-'+id).remove();
		});
		HideBulkUpdateRow();
    });
}
 ///Fetches a new row
function BulkUpdate(e) {
	$(e).confirmation({
		container: 'body',
		placement: 'top',
		html: true,
		singleton: false,
		popout: false,
		title: '<h3 class="font-red"> Please make sure you have chosen right values for all the selected rows.</h3>',
		btnOkClass: 'btn btn-sm green btn-success',
		btnCancelClass: 'btn btn-sm btn-danger',
		onConfirm: function(){
			CommonFunc2({action:'bulk-update',module:module},'table-form-wrapper',e).then(function (value) {
				HideBulkUpdateRow();
			});					
		},
		onCancel: function () {
			$(this).confirmation('destroy');
		}
	});
	$(e).confirmation('show');
/*     CommonFunc2Confirmation({action:'bulk-update',module:module},'table-form-wrapper',e)
	.then(function (value) {
		HideBulkUpdateRow();
    });	 */
}

$(document).on('click change','#table-master-check',function(e){
	$('#table-master-check').closest('.md-checkbox').removeClass('partial-check');		
    var offset = $(this).offset();
	var	posY = e.pageY - $(window).scrollTop();
	var set = $('tbody > tr > td:nth-child(1) input[type="checkbox"]');
	
	var checked = $(this).is(":checked");
	$(set).each(function() {
		$(this).prop("checked", checked); 
	});
	//$.uniform.update(set);
	countSelectedRecords(offset,posY);
});

function countSelectedRecords(offset,posY) {
	trCount = tbody.children('tr').length;
	var $this = $('.fixed-action-btn');	
	var selected = $('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked').length ;
	var boxeschecked = $('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked');
    checkedIds = [];
    $(boxeschecked).each(function() {
        var temp = $(this).val();
        checkedIds.push(temp);
    });

	 if (selected < 1) {

		$('#fixed-edit, #fixed-refresh').removeClass('hidden');	
		HideFixedActionButtons($this);
		HideBulkUpdateButton();
		HideBulkUpdateRow();
		$('#status-message').text("");
		$('#table-master-check').closest('.md-checkbox').removeClass('partial-check');		
		$('#table-master-check').removeAttr("checked", false);	
	}
	 else if(selected > 1){
		if(trCount > selected){
			if (selected == 1) {

				$('#fixed-edit, #fixed-refresh').removeClass('hidden');	
				ShowFixedActionButtons(offset,posY,$this);		  
				ShowBulkUpdateButton();;		  
			} 
			else if (selected > 1) {
				$('#fixed-edit, #fixed-refresh').addClass('hidden');	
				ShowFixedActionButtons(offset,posY,$this);		  
				ShowBulkUpdateButton();		  
		
			}
			$('#table-master-check').closest('.md-checkbox').addClass('partial-check');			
			
		}
		if(trCount == selected){
			$('#fixed-edit, #fixed-refresh').addClass('hidden');	
			ShowFixedActionButtons(offset,posY,$this);				
			ShowBulkUpdateButton();				
		}
		else{
			$('#table-master-check').prop("checked", true);				
		}

		//toastr['success'](selected+' rows selected', "");		

	}
	 else if(selected == 1){
		if(trCount == selected){
			$('#fixed-edit, #fixed-refresh').removeClass('hidden');	
			ShowFixedActionButtons(offset,posY,$this);	
			//ShowBulkUpdateRow();	
			ShowBulkUpdateButton();
		}	
		else{
			$('#fixed-edit, #fixed-refresh').removeClass('hidden');	
			$('#table-master-check').closest('.md-checkbox').addClass('partial-check');				
			ShowFixedActionButtons(offset,posY,$this);			
			ShowBulkUpdateButton();		
		}
	}

}

function ShowBulkUpdateButton(){
	$('#btn-bulk-update-modal').removeClass('hidden');
	var $popover_template = 
	'\
	 <div class="popover animated swing infinite" role="tooltip" data-background-color="orange" style="top:1000px;left:100px">\
		 <div class="arrow"></div>\
		 <div class="popover-content">\
		 \
		 </div>\
	  </div>\
	';
	if(BOOTSTRAP_VERSION == '4'){
		$popover_template = 
		'\
		 <div class="popover  animated" role="tooltip" data-background-color="orange" style="top:1000px;left:100px">\
			 <div class="arrow"></div>\
			 <div class="popover-body">\
			 \
			 </div>\
		  </div>\
		';		
	}
	$element = $('#btn-bulk-update-modal');
	$element.popover({
		title: '<h6 class="font-white"><span class="fa fa-warning font-white"></span> Bulk Update</h6>',
		content : '<h3 class="font-white"><span class="fa fa-info font-white"></span> Bulk Update</h3>',
		trigger:'hover',
		html: true,
		container: 'body',
		placement: 'bottom',
		template: $popover_template
	}).addClass('swing infinite');
	//console.log(element);
	//console.log($element);
	$element.popover('show'); 
	
}

function HideBulkUpdateButton(){
	$('#btn-bulk-update-modal').addClass('hidden');
	$('#btn-bulk-update-modal').popover('hide');
}

function ShowBulkUpdateRow(){
    ShowBsModal('bulk-update-row');
    $('.modal-backdrop').removeClass("modal-backdrop");
    $('.modal-open').removeClass("modal-open");
}

function HideBulkUpdateRow(){
    var $modal = $('#bulk-update-row');  
    var inAnimation = $modal.attr('data-in-animation');  
    var outAnimation = $modal.attr('data-out-animation');  
    if( inAnimation != null){
        $modal.removeClass(inAnimation);        
    }       

    var delay = $modal.attr('data-delay');  
    if( delay == null)delay = 700;
	$modal.addClass(outAnimation);         
	window.setTimeout(function() { 
		$modal.modal('hide');
		$modal.removeClass(outAnimation);
		$modal.addClass(inAnimation);            
	}, delay); 
        
}

function ShowMyElement(elem){
    $('#main-div').addClass('animated slideOutRight'); 

    window.setTimeout(function() {              
        $('#main-div').html('');
        $('#main-div').removeClass('slideOutRight');
        $('#'+elem+'').removeClass('hidden');
        $('#'+elem+'').removeClass('zoomOut');
        $('#'+elem+'').addClass('zoomIn');        
    }, 500);     
}

function HideMyElement(elem){
    $('#'+elem+'').removeClass('zoomIn');
    $('#'+elem+'').addClass('zoomOut');
    $('#'+elem+'').addClass('hidden');    
}

function HideFixedActionButtons($this){
/* 	$.fn.reverse = [].reverse;
	$this.css('top','');
	$this.css('left','');		  
	$this.css('bottom','');
	$this.css('right','');
	var time = 0;
	$this.find('ul .btn-mat-floating').velocity("stop", true);
	$this.find('ul .btn-mat-floating').velocity(
	{ opacity: "0", scaleX: ".4", scaleY: ".4"},
	{ duration: 80 });	 */		
}

function ShowFixedActionButtons(offset,posY,$this){
/* 	$.fn.reverse = [].reverse;		
	$this.css('top',posY-100);
	$this.css('left',offset.left - 50);
	$this.css('bottom','');
	$this.css('right','');	
	$this.find('ul .btn-mat-floating').velocity(
	{ scaleY: ".4", scaleX: ".4"},
	{ duration: 0 });

	var time = 0;
	$this.find('ul .btn-mat-floating').reverse().each(function () {
	$(this).velocity(
	  { opacity: "1", scaleX: "1", scaleY: "1"},
	  { duration: 80, delay: time });
	time += 40;
	});			
 */
}

$(document).on('click change', 'tbody > tr > td:nth-child(1) input[type="checkbox"]', function(e) {
	$('#table-master-check').closest('.md-checkbox').removeClass('partial-check');		
	var offset = $(this).offset();
	var	posY = e.pageY - $(window).scrollTop();
	
	countSelectedRecords(offset,posY);
});

var it = 0;


$("#search").on("keydown",function(e){
    if ( e.which == 13 ) // Enter key = keycode 13
    {
		e.preventDefault();		
		SearchOffers();
	}	
	else{
		return true;		
	}
	
});

function SearchTable(pageNo){
	startLoading();	
	if (typeof(pageNo)==='undefined') pageNo = 1;	
	var rows = $('#rows_to_display').val();
	var query = $('#table-simple-search').val();
	currentPagination = pageNo;	
			$.ajax({
				type:'POST',
				url: '',
				data: {'action':'searchwithpaginate','rows':rows,'currentPagination': currentPagination,'query': query},
				success: function(val){			
					var value = JSON.parse(val);
					stopLoading();										
					if(value.ajaxresult == true) {
						$('#table-pagination').html(value['table_pagination']);
						$('#table-pagination-bottom').html(value['table_pagination']);	
						$('#rows_to_display_container').html(value['rows_to_display']);								
						tbody.html(value['table_body']);
                        if (value.hasOwnProperty('jsFunction')) {
                             eval(value.jsFunction);
                        }                        
						
					}
					else {
						stopLoading();								
					}
					stopLoading();						
				}
			});	
}




function GetSelectedValues(sel) {
	var SelectedOptions = document.getElementById(sel);
	var users = [];
	var cnt = 0;
	for (var i=0;i<SelectedOptions.length;i++) {
	if (SelectedOptions.options[i].selected) {
		cnt++;
		users.push(SelectedOptions.options[i].value);
	}
	}
	if (users <= 0) {
	users = '';
	}
	return users;
}


function initDateRangePickerCommon(data){
    var startDateRange = moment().format('YYYY-MM-DD');
    var endDateRange = moment().format('YYYY-MM-DD');
    var ranges = {
                    "One Week": [moment(), moment().add("days", 6)],
                    "One Month": [moment(), moment().add("month", 1)],
                    "Three Months": [moment(), moment().add("month", 2)],
                    "Six Months": [moment(), moment().add("month", 5)],
                    "One Year": [moment(), moment().add("month", 11)],
                    "This Month": [moment().startOf("month"), moment().endOf("month")]
                };
    if((typeof data.drops !== 'undefined') ){
        if(data.range == 'past'){
            ranges = {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            };            
        }
    }
	var start_val = moment().format('YYYY-MM-DD');
	var end_val = moment().format('YYYY-MM-DD');
	if(data.start_value){
		start_val = moment(data.start_value).format('YYYY-MM-DD');
	}
	if(data.end_value){
		end_val = moment(data.end_value).format('YYYY-MM-DD');
	}

    $picker = $('#'+data.element+'').daterangepicker({
          parent: 'body',
          startDateRange: start_val,
          endDateRange: end_val,
          minDate: '01/01/2015',
          maxDate: '12/31/2030',
          dateLimit: { days: 10000 },
          showDropdowns: true,
          showWeekNumbers: true,
          timePicker: false,
          timePickerIncrement: 1,
          timePicker12Hour: true,
          ranges: ranges,
          drops: (typeof data.drops !== 'undefined') ? data.drops :'down',
          opens: (typeof data.opens !== 'undefined') ? data.opens :'center',
          buttonClasses: ['btn '],
          applyClass: 'green',
          cancelClass: 'red',
          format: (typeof data.format !== 'undefined') ? data.format : 'DD/MM/YYYY',
          separator: ' to ',
          locale: {
              applyLabel: 'Submit',
              fromLabel: 'From',
              toLabel: 'To',
              customRangeLabel: 'Custom Range',
              daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
              monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
              firstDay: 1
          }
       },
       function(start, end) {
            $('#'+data.element+' span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
            startDateRange = start.format('YYYY-MM-DD');
            endDateRange = end.format('YYYY-MM-DD');
            $('#'+data.startdate+'').val(startDateRange);
            $('#'+data.enddate+'').val(endDateRange);
       }
    );
    //Set the initial state of the picker label
	$('#'+data.element+' span').html(moment(start_val).format('D MMMM YYYY') + ' - ' + moment(end_val).format('D MMMM YYYY'));
	$('#'+data.startdate+'').val(start_val);
	$('#'+data.enddate+'').val(end_val);
 
	$picker.on('show.daterangepicker', function (ev, picker) {
		if (picker.element.offset().top - $(window).scrollTop() + picker.container.outerHeight() > $(window).height()) {
			picker.drops = 'up';
		} else {
			picker.drops = 'down';
		}
		picker.move();
	});	
}
function initDateRangePicker(data){
    var startDateRange = moment().format('YYYY-MM-DD');
    var endDateRange = moment().format('YYYY-MM-DD');

    $('#'+data.element+'').daterangepicker({
          parentEl: 'body',
          startDateRange: moment().format('YYYY-MM-DD'),
          endDateRange: moment().format('YYYY-MM-DD'),
          minDate: '01/01/2015',
          maxDate: '12/31/2030',
          dateLimit: { days: 10000 },
          showDropdowns: true,
          showWeekNumbers: true,
          timePicker: false,
          timePickerIncrement: 1,
          timePicker12Hour: true,
          ranges: {
                    "One Week": [moment(), moment().add("days", 6)],
                    "One Month": [moment(), moment().add("month", 1)],
                    "Three Months": [moment(), moment().add("month", 2)],
                    "Six Months": [moment(), moment().add("month", 5)],
                    "One Year": [moment(), moment().add("month", 11)],
                    "This Month": [moment().startOf("month"), moment().endOf("month")]
          },
          opens: 'left',
          buttonClasses: ['btn btn-default'],
          applyClass: 'btn-small btn-primary',
          cancelClass: 'btn-small',
          format: 'DD/MM/YYYY',
          separator: ' to ',
          locale: {
              applyLabel: 'Submit',
              fromLabel: 'From',
              toLabel: 'To',
              customRangeLabel: 'Custom Range',
              daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
              monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
              firstDay: 1
          }
       },
       function(start, end) {
            $('#'+data.element+' span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
            startDateRange = start.format('YYYY-MM-DD');
            endDateRange = end.format('YYYY-MM-DD');
            $('#'+data.startdate+'').val(startDateRange);
            $('#'+data.enddate+'').val(endDateRange);
       }
    );
    //Set the initial state of the picker label
    $('#'+data.element+' span').html(moment().format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
      
}

$('body').on('mouseover','.delete-notification-hover',function(){
	var notification_id = $(this).data('notification_id');
	CommonFunc2({'dont_load':'dont_load','action':'delete_notification','module':'notification','notification_id':notification_id},'');
	
});


