$(document).on('click','.master-check', function(e){
	var set = $(e.target).closest('.check-group-container').find('.check-group');
	var checkboxes = set.find('.child-check');
	var checked = $(this).is(':checked');
	if(checked){
		set.removeClass('hidden');
		$(this).closest('.check-group-container').find('.check-group').find('input[type=checkbox]').each(function () {
			//$(this).removeAttr('disabled');   // NOT NEEDED IN FLAT THEME
		});						
	}
	else{
		set.addClass('hidden');
		$(this).closest('.check-group-container').find('.check-group').find('input[type=checkbox]').each(function () {
			//$(this).attr('disabled',true);   // NOT NEEDED IN FLAT THEME
			$(this).attr('checked', checked); 							
		});								
	}
	//$.uniform.update(set); //NBOT NEEDED IN FLAT THEME
	 
});
									
$(document).on('click','.check-all-boxes', function(e){
	var set = $(e.target).closest('.check-group');
	var checkboxes = set.find('.child-check');
	var checked = $(this).is(':checked');
	console.log(checked);
	if(checked){
		checkboxes.each(function () {
			$(this).prop('checked', checked); 
			$(this).attr('checked', checked); 							
		});
	}
	else{
		$(this).closest('.check-group').find('.child-check').each(function () {
			$(this).prop('checked', checked); 
			$(this).attr('checked', checked); 							
		});								
	}

	 //$.uniform.update(set); //NBOT NEEDED IN FLAT THEME
});	

$(document).on('click','.grant-all-master-check', function(e){
	var set = $(e.target).closest('form');
	var checkboxes = set.find('.md-check');
	var checked = $(this).is(':checked');
	set.find('.check-group-container').each(function(f,v){
		if(checked) $(v).find('.check-group').removeClass('hidden');
		if(!checked) $(v).find('.check-group').addClass('hidden');
		$(v).find('input[type=checkbox]').each(function () {
			//$(this).attr('disabled',true);
			$(this).prop('checked',checked); 								
		});	
	});
	$(this).closest('form').find('.check-group').find('input[type=checkbox]').each(function () {
		$(this).removeAttr('disabled');
		$(this).prop('checked',checked);
	});		
});	
        