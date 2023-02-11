
function SetValidationFields(val_fields,val_messages,field_icons,duplicate_checks){
	validation_fields = val_fields;
	validation_messages = val_messages;
	icons = field_icons;
	duplicates = duplicate_checks;
}

function SetTools(toolss){
	tools = toolss;
}

function GetForm(type,id,module,ajaxCont){
	startLoading();
	ajaxElement = $('#'+ajaxCont+'');
	ajaxElement.html('');	
	$.ajax({
		type:'POST',
		data: {'type': type,'id':id,'module':module},		
		url: '',
		success: function(val){
            try {
                var value = JSON.parse(val);
            }
            catch(err) {
                console.log(val);
            } 
			console.log(value);
			if(value.debug){
				console.log(val);					
			}
       
			if(value.ajaxresult) {
                SetTools(value.tools);
				ajaxElement.html(value['html']);

				if (value.hasOwnProperty('jsFunction')) {
					 eval(value.jsFunction);
				}              
								
			}
			else {
				showMessage('error','Cant get the Form', 'Please try again');						
			}			  
			stopLoading();	
		}
	});

}


function GetNonModalForm(type,id,module,ajaxCont){
	startLoading();
	ajaxElement = $('#'+ajaxCont+'');
	ajaxElement.html('');	
	$.ajax({
		type:'POST',
		data: {'type': type,'id':id,'module':module},		
		url: '',
		success: function(val){
            try {
                var value = JSON.parse(val);
            }
            catch(err) {
                console.log(val);
            }  
			if(value.debug){
				console.log(val);					
			}
       
			if(value.ajaxresult) {
				
/* 				$('form :input').on('keydown change',function(e){
					$(e.target).closest('.form-group').removeClass('has-error');
					$(e.target).closest('.form-group').find('.input-group-addon').html('');
					$(e.target).closest('.form-group').find('.help-block').html('');
					$(e.target).removeClass('edited');
				});	 */
				if (value.hasOwnProperty('jsFunction')) {
					 eval(value.jsFunction);
				}

                //////WHATEVER OCE YOU WANT TO EXECUTE SHOULD BE DONE AFTER 500 ms               

                //console.log(value.tools);
				SetTools(value.tools);				
			}
			else {
				showMessage('error','Cant get the Form', 'Please try again');						
			}			  
			stopLoading();	
		}
	});

}

function SubmitForm(e){
	$(e).closest('form').submit();
}
