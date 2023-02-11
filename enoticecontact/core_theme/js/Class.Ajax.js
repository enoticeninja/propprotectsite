var Ajax = function(options){
	var $this = this;
	this.functionsListBefore = [];
	this.functionsListSuccess = [];

	this.options = {};
	this.options['data'] = {};
	this.options['form'] = '';
	this.options['next_data'] = [];
	this.options['load'] = true;
	this.options['e'] = '';
	this.options['unique_id'] = '';
    this.construct = function($data){
		this.options = $.extend(true,this.options,$data);
    };	
	
	this.ajax = function($data){
		if($this.options.load){
			startLoading();
		}
		var form = $this.options.form;
		var data = $this.options.data;
		var next_data = $this.options.next_data;
		var callback = $this.options.callback;
		var e = $this.options.e;
		var formData = [];

		var checkedRequired = false;
		var checkedValidation = false;
		var checkedDuplicate = false;
		var validateFields = true;
		var unique_id = $this.options.unique_id;

		if(validateFields){
			tools = (typeof toolsAll[unique_id] !== 'undefined') ? toolsAll[unique_id] : [];
			check = validateAndRequire(tools);
			checkedRequired = check['required'];
			checkedValidation = check['validated'];
			checkedDuplicate = check['duplicate'];
		}

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

						if(typeof(callback) != 'undefined' && callback != ''){
							window[callback](value);
						}
						for (i = 0; i < $this.functionsListSuccess.length; i++){
							$this.functionsListSuccess[i]();
						}						
						stopLoading();
					}
					else {
						stopLoading();      
					}
				
				}
			});	
		}
		else{
			stopLoading();
		}
	}

	$this.construct(options);
	
}
