function deleteBtnClickRepeatForm(data){ 
     $('body').on( 'click', '.btnDelete'+data['unique_id']+'', function () {
            $(this).confirmation({
                container: 'body',
                placement: 'left',
                singleton: true,
                popout: true,
                btnOkClass: 'btn btn-sm btn-success',
                btnCancelClass: 'btn btn-sm btn-danger',
                onConfirm: function () {
                    var tool_index = $(this).attr('data-tools-index');
                    var unique_id = $(this).data('unique_id');                    
                    var removeElem = $(this).closest('.remove-after-delete');
                    if($.trim($(this).data('id')) == '' || $.trim($(this).data('id')) == '0' || $.trim($(this).data('id')) == '__DB_ID__'){
                        $(this).confirmation( 'destroy' );
                        removeElem.remove();
						//toolsAll[parent]['rows_count']--;  ///Cant do that eg if two entries and we delete the first one, when we add next bot the entries/elements will have same ids
                        delete toolsAll[unique_id].tools[tool_index];
                    }
                    else{
                        var formData = [];
                        formData.push({name: 'action', value: 'delete'});
                        formData.push({name: 'module', value: $(this).data('module')});            
                        formData.push({name: 'id', value: $(this).data('id')});
                        $(this).confirmation( 'destroy' );

                        $.when(CommonFunc2(formData)).then(function() {
                            removeElem.remove();
							//toolsAll[parent]['rows_count']--;  ///Cant do that eg if two entries and we delete the first one, when we add next bot the entries/elements will have same ids
                            delete toolsAll[unique_id].tools[tool_index];                    
                        });                         
                    }
               
                },
                onCancel: function () {
                    $(this).confirmation( 'hide' );
                }            
            }); 
          $(this).confirmation( 'show' );
        });

}        


function anotherBtnClickRepeatForm(data){

	$('body').on('click','#'+data['unique_id']+'btnAddAnother',function(){
		tools = toolsAll[data['unique_id']];
		data['tools'] = tools;  
		var new_html2 = '';
		var $temp_child_html = '';
		var current_count = tools.rows_count;
		var row_count = tools.rows_count + 1; 
		var new_tools = repeatTools[data['module']];
		var string_new_tools = JSON.stringify(new_tools);
		var $originalParent = data['unique_id'];  /// needed for populating toolsAll and repeat-holder
/* 		if(new_tools['nested_form'] != 'last_child'){
			var replaceParent = data['parent']+'_'+row_count;
		} */
		var newParent = data['unique_id']+'_'+row_count; //// needed for generating html/js, and for passing the parent to child processing
		var newParent = data['unique_id']; //// needed for generating html/js, and for passing the parent to child processing
		data['unique_id'] = newParent;
		data['row_count'] = row_count;
		var js_uniqueid = uniqueid();
		var js_name_prefix = '';
		if(new_tools['form_depth'] == 1 || new_tools['nested_form'] == 'last_child'){
			js_name_prefix = tools['name_prefix']+'[new]['+js_uniqueid+']';
		}
		else{
			js_name_prefix = tools['name_prefix']+'[new]['+js_uniqueid+']';
		}
		string_new_tools = string_new_tools.replace(/__ROW_COUNT__/g, row_count);		
		string_new_tools = string_new_tools.replace(/__UNIQUE_ID__/g, newParent);		
		string_new_tools = string_new_tools.replace(/__JS_NAME_PREFIX__/g, js_name_prefix);		
		new_tools = JSON.parse(string_new_tools);

		toolsAll[$originalParent]['tools'][row_count] = new_tools.tools;
		toolsAll[$originalParent]['rows_count'] = row_count;	 
		////THIS IS A COMPLETELY NEW SET OF TOOLS
		data['name_prefix'] = js_name_prefix;
		data['loop_unique_id'] = js_uniqueid;
		var childResult = createChildRepeatForm(new_tools,data);
		new_tools.repeat_form = new_tools.repeat_form.replace(/__CHILD_HTML__/g, childResult['html']);
		new_tools.repeat_js += childResult['js'];
		$('#'+$originalParent+'-repeat-holder').append(new_tools.repeat_form);   
		
		eval(new_tools.repeat_js);
		if(new_tools.max_multiple_forms && row_count >= new_tools.max_multiple_forms){
			$(this).addClass('hidden');
		}

	});
} 


function createChildRepeatForm(new_tools,data){

	var $return = {};
	$return['js'] = '';
	$return['html'] = '';	
	if(new_tools['nested_form'] == 'has_child'){
		var i2 = row_count = 1;
		var $active = 'active';
		var $temp_child_li = '';
		var $temp_child_tab_pane = '';
		var new_html2 = '';
		var $temp_child_html = '';
		$.each(new_tools.child_modules,function($keyChild,$childModule){
			var data2 = {};
			var child_portlet_title = repeatTools[$childModule]['portlet_title'];
			data2['unique_id'] = data['unique_id']+'_'+$childModule+'_'+($keyChild+1);
			data2['unique_id'] = data['unique_id']+'_'+data['row_count'];
			data2['module'] = $childModule;
			//data2['parent'] = data['parent']+'_'+$childModule;
			data2['parent_name_prefix'] = data['name_prefix'];
			data2['parent_loop_unique_id'] = data['loop_unique_id'];
			var new_tools2 = createRepeatForm(data2);
			new_html2 = 
			'\
			'+new_tools2.custom_wrapper_begin+'\
			'+new_tools2.repeat_form+'\
			'+new_tools2.custom_wrapper_end+'\
			';	
			
			if(Object.keys(new_tools.child_modules).length > 1){	
				if(i2 != 1) $active = '';
/* 				$temp_child_li += 
				'\
				<li style="" class="wizard-li '+$active+'" id="li_'+data2['parent']+'_'+$keyChild+'"><a href="#tab_pane_'+data2['parent']+'_'+i2+'_'+$keyChild+'" data-toggle="tab" aria-expanded="true">'+child_portlet_title+'</a></li>\
				';	
				$temp_child_tab_pane += 
				'\
				<div class="tab-pane '+$active+' " id="tab_pane_'+data2['parent']+'_'+i2+'_'+$keyChild+'">\
					'+new_html2+'\
				</div>\
				'; */

				$temp_child_li += 
				'\
				<li style="" class="wizard-li '+$active+'" id="li_'+data2['unique_id']+'_'+$keyChild+'"><a href="#tab_pane_'+data2['unique_id']+'_'+i2+'_'+$keyChild+'" data-toggle="tab" aria-expanded="true">'+child_portlet_title+'</a></li>\
				';	
				$temp_child_tab_pane += 
				'\
				<div class="tab-pane '+$active+' " id="tab_pane_'+data2['unique_id']+'_'+i2+'_'+$keyChild+'">	\
					'+new_html2+'\
				</div>\
				';	
			}				
			$return['js'] += new_tools2.repeat_js;
			i2++;				
		});
		
		if(Object.keys(new_tools.child_modules).length > 1){
/* 			$return['html'] = 
			'\
					<div class="tabbable-custom nav-justified " style="margin-top:20px;">\
						<ul class="nav nav-tabs nav-justified">\
							  '+$temp_child_li+'\
						</ul>\
						<div class="tab-content">\
							'+$temp_child_tab_pane+'\
						</div>\
					</div>\
			'; */
			$return['html'] = 
			'\
			<div class="card wizard-container" >\
				<div class=" wizard-card creative-wizard-tab" data-color="blue" id="'+data['unique_id']+'_'+data['row_count']+'-wizard-card" style="margin-left:5%;margin-right:5%">\
				<div class="wizard-navigation">\
					<ul class="nav nav-pills nav-wizard">\
						  '+$temp_child_li+'\
					</ul>\
				</div>\
					<div class="tab-content">\
						'+$temp_child_tab_pane+'\
					</div>\
				</div>\
			</div>\
			';
			$return['js'] += "\
			init_creative_wizard($('#"+data['unique_id']+"_"+data['row_count']+"-wizard-card'));\
			";
		}
		else{
			$return['html'] = new_html2;
		}	

	}
	else{
		
	}
	return $return;
	
}


///// This function is for declaring onclick on new children and to return back the html 
function createRepeatForm(data){  
	var new_tools = repeatTools[data['module']];
	var new_html2 = '';
	var $temp_child_html = '';
	var row_count = 1; //// Previous number of rows	
	var string_new_tools = JSON.stringify(new_tools);
	var $originalParent = data['unique_id'];
	var newParent = data['unique_id']+'_'+data['module'];
	var $originalParent = data['unique_id']+'_'+data['module'];
	//data['parent'] = newParent;
	
/*
 		$element['name'] = ($form_config['name_prefix']) ? '__NAME_PREFIX__[__LOOP_UNIQUE_ID__][form_data]['.$field.']' : '__LOOP_UNIQUE_ID__[form_data]['.$field.']'; 
$tertiary_fields['name_prefix'] = $form_config['name_prefix'].'['.$loop_unique_id.']'.'['.$tertiary_fields['module'].']';
*/	

	var js_name_prefix = '';
	var parent_loop_unique_id = uniqueid();
	js_name_prefix = data['parent_name_prefix']+'['+data['module']+'][new]['+data['parent_loop_unique_id']+']';
	string_new_tools = string_new_tools.replace(/__ROW_COUNT__/g, row_count);		
	string_new_tools = string_new_tools.replace(/__UNIQUE_ID__/g, newParent);		
	string_new_tools = string_new_tools.replace(/__JS_NAME_PREFIX__/g, js_name_prefix);				
	new_tools = JSON.parse(string_new_tools);	
	
	///////Since this is a completely new element which is multiple form we have to create a new entry in toolsAll using the original parent without the _rowcount
	toolsAll[$originalParent] = {};
	toolsAll[$originalParent]['nested_form'] = new_tools.nested_form;
	toolsAll[$originalParent]['name_prefix'] = data['parent_name_prefix']+'['+data['module']+']';
	toolsAll[$originalParent]['rows_count'] = 1;
	toolsAll[$originalParent]['is_multiple_form'] = true;
	toolsAll[$originalParent]['rows_count_const'] = 1;
	toolsAll[$originalParent]['tools'] = {};
	//data['parent'] = data['parent']+'_'+data['module'];
	var childData = {};
	childData['row_count'] = 1;
	childData['unique_id'] = $originalParent;
	childData['module'] = data['module'];
	childData['name_prefix'] = js_name_prefix;
	childData['loop_unique_id'] = parent_loop_unique_id;
	var childResult = createChildRepeatForm(new_tools,childData);
	

	anotherBtnClickRepeatForm(childData);	
	deleteBtnClickRepeatForm(childData);	
	new_tools.repeat_form = new_tools.repeat_form.replace(/__CHILD_HTML__/g, childResult['html']);
	new_tools.repeat_js += childResult['js'];
		
		
	toolsAll[$originalParent]['tools'][row_count] = new_tools.tools;
	return new_tools; 	
}


/*         $( '#{$form_config['form_id']}' ).sortable({
            placeholder: 'portlet-sortable-placeholder3 col-md-12',
            forcePlaceholderSize: true,            
            tolerance: 'pointer',            
            handle: '+sortableBtn'          
        });   */     

function hideDependentFields(data){
    var delay = 500;
	jQuery.each(data['dep'][0].hide, function(i, elem){
		var __elem = $('#'+data['unique_id']+'_'+elem+'_'+data['row_count']+''); 
			//console.log('#'+data['parent']+'-'+elem+'-'+data['row_count']+'');
		__elem.addClass('hidden');
		__elem.closest('.form-common-element-wrapper').addClass('animated'+delay+' zoomOut');
		   window.setTimeout(function() { 
				__elem.closest('.form-common-element-wrapper').addClass('hidden');
		   }, delay);
	});
}

function showDependentFields(data){
    var delay = 500;
	jQuery.each(data['dep'][0].hide, function(i, elem){
		//console.log('#'+data['parent']+'-'+elem+'-'+data['row_count']+'');
		var __elem = $('#'+data['unique_id']+'_'+elem+'_'+data['row_count']+'');
		__elem.removeClass('hidden');
	   __elem.closest('.form-common-element-wrapper').removeClass('animated'+delay+' zoomOut');
	   window.setTimeout(function() { 
			__elem.closest('.form-common-element-wrapper').removeClass('hidden');
	   }, delay);
	}); 
}
	
function manageDependentFields(data){
    var prevSelected = data['prevSelected'];
    var selected = data['selected'];
    var delay = 500;
	$this = data['this'];
	//console.log(data);
    ////PREVIOUS SELECTED 

    //// END PREVIOUS SELECTED
    if($this.is(':checkbox')){
		if (data['dep'].hasOwnProperty('0')) {
			if (data['dep'][0].hasOwnProperty('hide')) {
				var checked = $this.is(':checked');
				if(!checked){
					hideDependentFields(data);                                    
				}
				else{
					showDependentFields(data); 						
				}
			}
			if (data['dep'][0].hasOwnProperty('show')) {
				var checked = $this.is(':checked');
				if(!checked){
					showDependentFields(data);                                  
				}
			}			
		}
		else if (data['dep'].hasOwnProperty('1')) {
			if (data['dep'][1].hasOwnProperty('hide')) {
					var checked = $this.is(':checked'); 
					if(checked){
						hideDependentFields(data);
				}
			}
			if (data['dep'][1].hasOwnProperty('show')) {
					var checked = $this.is(':checked');
					if(checked){
						showDependentFields(data);                                    
					}
			}			
		}
	}
    else if (data['dep'].hasOwnProperty(selected)) {

		if(prevSelected != '' || prevSelected != 0){
			
			if (data['dep'][prevSelected].hasOwnProperty('hide')) {
				showDependentFields(data);
				//// RUN THE LOOP AGAIN TO TRIGGER CHANGE IF ANY FOR THE ELEMENT
				//// RUNNING LOOP SEPERATELY IS IMPORTNT SO THAT IT DOESNT CONFLICT WITH THE ELEMENTS OF THE ABOVE LOOP
				jQuery.each(data['dep'][prevSelected].hide, function(i, elem){
					var __elem = $('#'+data['unique_id']+'_'+elem+'_'+data['row_count']+'');
				   __elem.trigger('change');
				   __elem.addClass('edited');
				});	                                
			} 
			
			if (data['dep'][prevSelected].hasOwnProperty('show')) {
				hideDependentFields(data);
				//// RUN THE LOOP AGAIN TO TRIGGER CHANGE IF ANY FOR THE ELEMENT
				//// RUNNING LOOP SEPERATELY IS IMPORTNT SO THAT IT DOESNT CONFLICT WITH THE ELEMENTS OF THE ABOVE LOOP
				jQuery.each(data['dep'][prevSelected].show, function(i, elem){
					var __elem = $('#'+data['unique_id']+'_'+elem+'_'+data['row_count']+'');
				   __elem.trigger('change');
				   __elem.addClass('edited');
				});	                                
			}
		}
	
        if (data['dep'][selected].hasOwnProperty('hide')) {
			hideDependentFields(data);
        }
        
        if (data['dep'][selected].hasOwnProperty('show')) {
			showDependentFields(data);
        }
    }    
}        