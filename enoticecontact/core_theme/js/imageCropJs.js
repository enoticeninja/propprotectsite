var ImageEditor = function(options){
	var $this = this;
    this.config = {
        site_path  : site_path,
        frontend_site_path  : FRONTEND_SITE_PATH,
        image_path  : '',
        parent  : '',
        file  : '',
        actualImageWidth  : '',
        actualImageHeight  : '',
        currentImage  : '',
        uploadsPath  : '',
        upload_path  : '',
        image_path  : '',
        imageProcessingUrl  : site_path+'imageProcessing',
        holder  : '',
        refresh_image_page  : '',///// Image element on page
        size_restriction  : false,
        min_width  : 10,
        min_height  : 10,
        max_width  : 10000,
        max_height  : 10000,
        max_file_size  : 50,
        allowResizeInCrop  : true,
        allowSelectInCrop  : false,
        cropHeight  : 500,
        cropWidth  : 500,
        current_element_unique_id  : '',
        ajax_image_upload_data  : {},
        ajax_image_edit_data  : {},
        unique_id  : '',
        modal_title  : 'Upload Image',
        upload_action  : 'upload',
        one_image  : false,
        multiple_image  : false,
        allow_crop  : true,
        strict_crop  : false,
        direct_crop_upload  : false,
        allow_resize  : true,
        allow_rotate  : false,
    };

    var image_path = '',file,actualImageWidth,actualImageHeight,currentImage,isCropping=false,undoButton,uploadsPath,imageProcessingUrl,imageGUID,holder, current_element_unique_id,ajax_image_upload_data={},ajax_image_edit_data={};
    
    var imageholder,image_height,image_width,image_resize_width,image_resize_height,upload_button,edit_toolbar,error_messages,currentImageElement,func,evalCode,jcrop_api,isEditing = false;    
    /*
     * Can access this.method
     * inside other methods using
     * root.method()
     */
    var root = this;
    var parent = '';
	this.beforeMultipleUploadCallback = [];
    /*
     * Constructor
     */

    this.construct = function(options){
        $.extend(true,$this.config,options);
        parent = $this.config['parent'];
        $this.config['unique_id'] = $this.config['parent'];
		this.initCommonVariables();
		appendProgressHolder();
		$('head').append('<link rel="stylesheet" type="text/css" href="'+core_theme_path+'css/imageCropJs.css">');
		if($this.config['one_image']){
			//$('head').append('<link rel="stylesheet" type="text/css" href="'+core_theme_path+'assets/global/plugins/jcrop/css/jquery.Jcrop.min.css">');
			//$('body').append('<script src="'+core_theme_path+'assets/global/plugins/jcrop/js/jquery.Jcrop.min.js" type="text/javascript"></script>');
			appendImageEditModal({'unique_id':$this.config.parent,'modal_title':$this.config.modal_title,'one_image':$this.config.one_image,'multiple_image':$this.config.multiple_image});
			var style = $('<style>.imageEditToolbar .btn, .imageEditToolbar .dropdown, .imageEditToolbar .md-checkbox, .imageEditToolbar .inline{display:inline;}.imageEditToolbar .input-group{width:140px;}</style>');
			$('html > head').append(style);
			$this.initElementVariables();
			$this.initOnClickHandlers();
			EditEventHandlers();
			$(document).on('click','#'+parent,function(){
				ResetAll();
			});
			

			$('#'+$this.config['file_field']).on('change',function(){
				ShowBsModal($this.config['parent']+'_image_edit_modal');
				$('#'+parent+'_error-messages').html('<h4 class="font-yellow-gold text-center">Please Note:</br>1. Maximum Image size Allowed is 5MB.</br>2.Minimum image dimensions allowed are '+$this.config['min_width']+'X'+$this.config['min_height']+' px.</br>2.Maximum image dimensions allowed are '+$this.config['max_width']+'X'+$this.config['max_height']+' px.</h4>');

				DisplaySelectedImage(this);
			});			

		}
		else if($this.config['multiple_image']){
			$this.beforeMultipleUploadCallback = $.trim(options.beforeMultipleUploadCallback);			
			$('#'+$this.config['file_field']).on('change',function(){
				if ($this.beforeMultipleUploadCallback != ''){
					console.log($this.beforeMultipleUploadCallback);
					window[$this.beforeMultipleUploadCallback]($this);
				}
				$this.UploadMultiple($this.config['file_field']);
			});
		}
    };

    this.initOnClickHandlers = function(){
        upload_button.click(function(){Upload()});
        //$('#'+parent+'_btn_save_use').click(function(){ExecuteFunction()});
        $('#'+parent+'_btn_save_use').click(function(){$this.ajaxSaveUse({})});
        $('#'+parent+'_btn_crop').click(function(){InitCrop()});
        $('#'+parent+'_btn_crop_use').click(function(){CropAndUpload()});
        $('#'+parent+'_btn_rotate_270').click(function(){Rotate(90)});
        $('#'+parent+'_btn_undo').click(function(){Undo()});
        $('#'+parent+'_btn_rotate_90').click(function(){Rotate(-90)});
        $('#'+parent+'_resize_btn').click(function(){Resize()});
        $('#'+parent+'_multiple-upload-button').click(function(){$this.UploadMultiple($this.config['parent']+'_file')});
        $('#'+parent+'_close_edit_image_modal').click(function(){ResetAll()});
    }
    
    this.initCommonVariables = function(){
        //site_path = $this.config['site_path'];
        uploadsPath = $this.config['upload_path'];
        imageProcessingUrl = site_path+$this.config['imageProcessingUrl'];
		if($this.config['imageProcessingUrl'] == '' || $this.config['imageProcessingUrl'] == 'self'){
			imageProcessingUrl = '';
		}
    };

    this.initElementVariables = function(){
        undoButton = $('#'+parent+'_btn_undo');
        imageholder = $('#'+parent+'_image-holder');
        image_height = $('#'+parent+'_image_height');
        image_width = $('#'+parent+'_image_width');
        image_resize_width = $('#'+parent+'_image_resize_width');
        image_resize_height = $('#'+parent+'_image_resize_height');
        upload_button = $('#'+parent+'_btn_upload');
        edit_toolbar = $('#'+parent+'_imageEditToolbar');
        error_messages = $('#'+parent+'_error-messages');
    }
    /*
     * Public method
     * Can be called outside class
     */
    this.myPublicMethod = function(){
        //console.log(options);
        myPrivateMethod();
    };

    /*
     * Private method
     * Can only be called inside class
     */
    var myPrivateMethod = function() {
        //console.log('accessed private method');
    };

    var refreshImageOnPage = function (){
        if($this.config['refresh_image_page'] != ''){
            $('#'+$this.config['refresh_image_page']+'').attr('src',''+FRONTEND_SITE_PATH+''+$this.config['upload_path']+''+$this.config['image_path']+'/'+currentImage+'?'+new Date().getTime()+'');
			return true;
        }
		return true;
    };
///////TODO : change the crrent image elemebt on modal and html page 
    this.refreshImageOnModal = function (){
        if(currentImageElement != ''){
            $('#'+currentImageElement+'').attr('src',''+FRONTEND_SITE_PATH+''+$this.config['upload_path']+''+$this.config['image_path']+'/'+currentImage+'?'+new Date().getTime()+'');
        }
    };

    this.setAjaxImageUploadData = function (data){
        //ajax_image_upload_data = data;
        $.extend(ajax_image_upload_data, data);
        //console.log(ajax_image_upload_data);
    };

    this.setOptions = function (additional_options){
        $.extend($this.config, additional_options);
        //console.log($this.config);
    };

    this.setAjaxImageEditData = function (data){
        //ajax_image_upload_data = data;
        $.extend(ajax_image_edit_data, data);
        //console.log(ajax_image_edit_data);
    };

    this.SetCurrentHolder = function (holderr){
        holder = holderr;
        current_element_unique_id = holderr;
    };

    this.SetCurrentImageElement = function (holderr){
        currentImageElement = holderr;
    };

    this.SetCurrentHolder2 = function (e){
        holder = $(e).closest('.paired-img-btn-holder').find('.unique-id').val();
        current_element_unique_id = holder;
    };

    this.GetCurrentImage = function (){
        return currentImage;
    };

    var InitEdit = function (){
		upload_button.addClass('hidden');
        $('#'+parent+'_imageEditToolbar').removeClass('hidden');
		if($this.config['allow_crop']){
			$('#'+parent+'_btn_crop').removeClass('hidden');
		}
        $('#'+parent+'_btn_delete').removeClass('hidden');
        $('#'+parent+'_btn_save_use').removeClass('hidden');
		if($this.config['allow_rotate']){
			$('#'+parent+'_btn_rotate_90').removeClass('hidden');
			$('#'+parent+'_btn_rotate_270').removeClass('hidden');
		}
		if($this.config['allow_resize']){
			$('#'+parent+'_btn_resize').removeClass('hidden');
			$('#'+parent+'_dropdown_resize').removeClass('hidden');
		}
    };

    var DestroyEdit = function (){
		if($this.config['allow_crop']){
			$('#'+parent+'_btn_crop').addClass('hidden');	
		}
        $('#'+parent+'_btn_delete').addClass('hidden');
        $('#'+parent+'_btn_save_use').addClass('hidden');
		if($this.config['allow_rotate']){
			$('#'+parent+'_btn_rotate_90').addClass('hidden');
			$('#'+parent+'_btn_rotate_270').addClass('hidden');
		}
		if($this.config['allow_resize']){
			$('#'+parent+'_btn_resize').addClass('hidden');
			$('#'+parent+'_dropdown_resize').addClass('hidden');
		}
    };

    var ResetAll = function (){
        //startLoading();	
        file = '';
        actualImageWidth = '';
        actualImageHeight = '';
        resetImageSize();
        DestroyEdit();
        undoButton.addClass('hidden');
        $('#imageEditToolbar').addClass('hidden');
        $('#image-holder').html('');
        $('#file').val('');
        var temp = root.GetCurrentImage();
		if(isEditing || isCropping){
			$.ajax({
				type: "POST",
				url:imageProcessingUrl,
				data: {'action':'reset-all','imagename':temp,'guid':imageGUID,'upload_path':$this.config['upload_path'],'image_path':$this.config['image_path'],'image':currentImage},
				success: function(val){
					if(jcrop_api)jcrop_api.destroy();
					isCropping = false;
					isEditing = false;
					$('#'+parent+'_image_edit_modal').modal('hide');
					//$('#'+$this.config['file_field']).val('');
					//$('#'+parent+'_image_edit_modal').remove();
					var value = JSON.parse(val);
					if(value.ajaxresult == 'success'){
						imageGUID = '';
						image_path = '';
						currentImage = '';
					}
					stopLoading();
				}

			});
		}
		else{
			$('#'+parent+'_image_edit_modal').modal('hide');
			//$('#'+parent+'_image_edit_modal').remove();
		}
        //stopLoading();	
    };

    var SetCurrentImageFunction = function (imageFunc){
        //$('#imageEditToolbar').removeClass('hidden');
        func = 	imageFunc;
    };

    var SetCurrentEvalCode = function (code){
        //$('#imageEditToolbar').removeClass('hidden');
        evalCode = 	code;
    };

    var ExecuteFunction = function (funcn){
        var tempFunc = '';
        if(funcn == '') {
            tempFunc = func;		
        }else{
            tempFunc = funcn;			
        }
        if(funcn == '' && func == ''){
            isEditing = false;
            
        }
        if(!isEditing){
            eval(tempFunc);	
        }
        else{
            isEditing = false;
            ResetAll();
        }
        ResetAll();

    };

    this.EvalCode = function (code){
        if(code != '' && typeof cide !== "undefined") {
            eval(code);		
        }
    };

    var updateActualSize = function (width, height){
        actualImageHeight = height;
        actualImageWidth = width;
    };

    var updateImageSize = function (width, height){
        $('#'+parent+'_image_resize_width').val(width);
        $('#'+parent+'_image_resize_height').val(height);
        $('#'+parent+'_image_resize_width_label').val(width+'Px');
        $('#'+parent+'_image_resize_height_label').val(height+'Px');
        //$('#__image_resize_height_post').val(tempH);	
        //$('#__image_resize_width_post').val(tempW);	
        $('#'+parent+'_image_width').val(width);
        $('#'+parent+'_image_height').val(height);

    };

    var resetImageSize = function (){
        $('#'+parent+'_image_resize_width').val('');
        $('#'+parent+'_image_resize_height').val('');
        //$('#__image_resize_height_post').val('');	
        //$('#__image_resize_width_post').val('');	
        $('#'+parent+'_image_width').val('');
        $('#'+parent+'_image_height').val('');
            actualImageHeight = '';
            actualImageWidth = '';	
    };

    var EditEventHandlers = function(){
        $('#'+parent+'_radio_pixel').on('change',function(){
                if ($(this).is(':checked')){
                    $('#'+$this.config['parent']+'_image_resize_width').attr({'max' : actualImageWidth,'min' : 1});			
                    $('#'+$this.config['parent']+'_image_resize_height').attr({'max' : actualImageWidth,'min' : 1});	
                    $('.'+$this.config['parent']+'_resize_by_addon_height').html('Px');
                    $('.'+$this.config['parent']+'_resize_by_addon_width').html('Px');
                     if(isCropping) {
                        $('#'+$this.config['parent']+'_image_resize_width').val($('#'+$this.config['parent']+'_image_width').val());
                        $('#'+$this.config['parent']+'_image_resize_height').val($('#'+$this.config['parent']+'_image_height').val());					 
                     }
                     else {
                        $('#'+$this.config['parent']+'_image_resize_width').val(actualImageWidth);
                        $('#'+$this.config['parent']+'_image_resize_height').val(actualImageHeight);					 
                     }
                }
            });

        $('#'+parent+'_radio_percent').on('change',function(){
            if ($(this).is(':checked')) {
                $('#'+$this.config['parent']+'_image_resize_width').attr({'max' : 10,'min' : 1});			
                $('#'+$this.config['parent']+'_image_resize_height').attr({'max' : 10,'min' : 1});			
                $('.'+$this.config['parent']+'_resize_by_addon_width').html('%');			
                $('.'+$this.config['parent']+'_resize_by_addon_height').html('%');
                $('#'+$this.config['parent']+'_image_resize_width').val('100');
                $('#'+$this.config['parent']+'_image_resize_height').val('100');	
            }
        });

        $('#'+parent+'_cons_prop').on('change',function(){
			
                if ($('#'+$this.config['parent']+'_radio_pixel').is(':checked')) {
                    $('#'+$this.config['parent']+'_image_resize_height').val(actualImageHeight);
                    $('#'+$this.config['parent']+'_image_resize_width').val(actualImageWidth);
                }
                else if($('#'+$this.config['parent']+'_radio_percent').is(':checked')) {	
                    $('#'+$this.config['parent']+'_image_resize_height').val(100);
                    $('#'+$this.config['parent']+'_image_resize_width').val(100);		
                }
        });
            
        $('#'+parent+'_image_resize_height').on('keyup',function(){
                if ($('#'+$this.config['parent']+'_radio_pixel').is(':checked')) {
                        if($('#'+$this.config['parent']+'_cons_prop').is(':checked')) {
                            var tempHeight = $('#'+$this.config['parent']+'_image_resize_height').val();
                            var prop = actualImageHeight / tempHeight;
                            var tempWidth = Math.floor(actualImageWidth / prop);
                            $('#'+$this.config['parent']+'_image_resize_width').val(tempWidth);
                        }

                }
                else if($('#'+$this.config['parent']+'_radio_percent').is(':checked')) {
                        if($('#'+$this.config['parent']+'_cons_prop').is(':checked')) {
                            var tempHeight = $('#'+$this.config['parent']+'_image_resize_height').val();				
                            var prop = 100 / tempHeight;
                            var tempWidth = Math.floor(100 / prop);
                            $('#'+$this.config['parent']+'_image_resize_width').val(tempWidth);	
                            var tempH =  Math.floor((actualImageHeight * tempHeight) / 100);
                            var tempW = Math.floor((actualImageWidth * tempWidth) / 100);
							if(tempH < $this.config['max_height'] && tempW < $this.config['max_width']){
								$('#'+$this.config['parent']+'_image_resize_height_post').val(tempH);
								$('#'+$this.config['parent']+'_image_resize_width_post').val(tempW);
								$('#'+$this.config['parent']+'_image_resize_width_label').html(tempW+'Px');
								$('#'+$this.config['parent']+'_image_resize_height_label').html(tempH+'Px');
								$('#'+$this.config['parent']+'_image_resize_messages').addClass('hidden');
							}
							else{
								$('#'+$this.config['parent']+'_image_resize_messages').html('Max Allowed Width is '+$this.config['max_width']+'</br>Max Allowed Height is '+$this.config['max_height']+'').removeClass('hidden');
							}

                        }	
                    else{
                            var tempHeight = $('#'+$this.config['parent']+'_image_resize_height').val();					
                            var tempWidth = $('#'+$this.config['parent']+'_image_resize_width').val();					
                            var tempH =  Math.floor((actualImageHeight * tempHeight) / 100);
                            var tempW = Math.floor((actualImageWidth * tempWidth) / 100);
							if(tempH < $this.config['max_height'] && tempW < $this.config['max_width']){
								$('#'+$this.config['parent']+'_image_resize_height_post').val(tempH);	
								$('#'+$this.config['parent']+'_image_resize_width_post').val(tempW);
								$('#'+$this.config['parent']+'_image_resize_width_label').html(tempW+'Px');
								$('#'+$this.config['parent']+'_image_resize_height_label').html(tempH+'Px');
								$('#'+$this.config['parent']+'_image_resize_messages').addClass('hidden');
							}
							else{
								$('#'+$this.config['parent']+'_image_resize_messages').html('Max Allowed Width is '+$this.config['max_width']+'</br>Max Allowed Height is '+$this.config['max_height']+'').removeClass('hidden');
							}

                    }			
                }
            });
            
        $('#'+parent+'_image_resize_width').on('keyup',function(){

                if ($('#'+$this.config['parent']+'_radio_pixel').is(':checked')) {
                        if($('#'+$this.config['parent']+'_cons_prop').is(':checked')) {
                            var tempWidth = $('#'+$this.config['parent']+'_image_resize_width').val();
                            var prop = actualImageWidth / tempWidth;
                            var tempHeight = Math.floor(actualImageHeight / prop);
                            $('#'+$this.config['parent']+'_image_resize_height').val(tempHeight);
                            
                        }

                }
                else if($('#'+$this.config['parent']+'_radio_percent').is(':checked')) {
                        if($('#'+$this.config['parent']+'_cons_prop').is(':checked')) {	
                            var tempWidth = $('#'+$this.config['parent']+'_image_resize_width').val();				
                            var prop = 100 / tempWidth;
                            var tempHeight = Math.floor(100 / prop);
                            $('#'+$this.config['parent']+'_image_resize_height').val(tempHeight);
                            var tempH =  Math.floor((actualImageHeight * tempHeight) / 100);
                            var tempW = Math.floor((actualImageWidth * tempWidth) / 100);
							if(tempH < $this.config['max_height'] && tempW < $this.config['max_width']){
								$('#'+$this.config['parent']+'_image_resize_height_post').val(tempH);	
								$('#'+$this.config['parent']+'_image_resize_width_post').val(tempW);	
								$('#'+$this.config['parent']+'_image_resize_width_label').html(tempW+'Px');
								$('#'+$this.config['parent']+'_image_resize_height_label').html(tempH+'Px');
								$('#'+$this.config['parent']+'_image_resize_messages').addClass('hidden');
							}
							else{
								$('#'+$this.config['parent']+'_image_resize_messages').html('Max Allowed Width is '+$this.config['max_width']+'</br>Max Allowed Height is '+$this.config['max_height']+'').removeClass('hidden');
							}
                        }
                    else{
                            var tempHeight = $('#'+$this.config['parent']+'_image_resize_height').val();					
                            var tempWidth = $('#'+$this.config['parent']+'_image_resize_width').val();					
                            var tempH =  Math.floor((actualImageHeight * tempHeight) / 100);
                            var tempW = Math.floor((actualImageWidth * tempWidth) / 100);
							if(tempH < $this.config['max_height'] && tempW < $this.config['max_width']){
								$('#'+$this.config['parent']+'_image_resize_height_post').val(tempH);	
								$('#'+$this.config['parent']+'_image_resize_width_post').val(tempW);	
								$('#'+$this.config['parent']+'_image_resize_width_label').html(tempW+'Px');
								$('#'+$this.config['parent']+'_image_resize_height_label').html(tempH+'Px');
								$('#'+$this.config['parent']+'_image_resize_messages').addClass('hidden');
							}
							else{
								$('#'+$this.config['parent']+'_image_resize_messages').html('Max Allowed Width is '+$this.config['max_width']+'</br>Max Allowed Height is '+$this.config['max_height']+'').removeClass('hidden');
							}

                    }				
                }
            });	
    };      

    var InitCrop = function (){
        if(isCropping){
            jcrop_api.destroy();
            isCropping = false;
            $('#'+parent+'_btn_crop_use').addClass('hidden');
            $('#'+parent+'_btn_crop').text('CROP');
            $('#'+parent+'_image_width').val(actualImageWidth);
            $('#'+parent+'_image_height').val(actualImageHeight);
            $('#'+parent+'_image_resize_width').val(actualImageWidth);
            $('#'+parent+'_image_resize_height').val(actualImageHeight);
        }
        else{
            Crop(actualImageWidth, actualImageHeight);	
            isCropping = true;
            $('#'+parent+'_btn_crop_use').removeClass('hidden');	
            $('#'+parent+'_btn_crop').text('CANCEL CROP');		
        }

    };	

    var Crop = function (trueImageWidth, trueImageHeight){
        var size = [500,500];
        var cropWidth = $this.config['cropWidth'];
        var cropHeight = $this.config['cropHeight'];
		
        if($this.config['strict_crop']){
			cropWidth = $this.config['max_width'];
			cropHeight = $this.config['max_height'];
			$this.config['allowResizeInCrop'] = false;
			$this.config['allowSelectInCrop'] = false;
		}
		//console.log(cropWidth);
		//console.log(cropHeight);
        $('#'+parent+'_image-crop').Jcrop({
            allowResize:$this.config['allowResizeInCrop'],
            allowSelect:$this.config['allowSelectInCrop'],
            trueSize: [trueImageWidth, trueImageHeight],
            onChange: showCoords,
            setSelect: [0, 0, cropWidth, cropHeight],
            onSelect: updateCoords
            },
            function(){
                jcrop_api = this;
        });
        jcrop_api.animateTo([0,0,cropWidth,cropHeight]);
    };

    var updateCoords = function (c){
        $('#'+parent+'_crop_x').val(c.x);
        $('#'+parent+'_crop_y').val(c.y);
        $('#'+parent+'_crop_w').val(c.w);
        $('#'+parent+'_crop_h').val(c.h);
    };

    var showCoords = function (c){
        $('#'+parent+'_image_width').val(c.w);
        $('#'+parent+'_image_height').val(c.h);
        $('#'+parent+'_image_resize_width').val(c.w);
        $('#'+parent+'_image_resize_height').val(c.h);		
    };

    var Delete = function (){
        var fileToDelete = $('#image_path').val();
        $('#file').val('');
        //$('#full').modal('hide');
        $('#image-holder').html('');
        if (fileToDelete != '') {
            $.ajax({
                type: "POST",
                url: "",
                data: {fileToDelete: fileToDelete},
                success: function(value){
                }
            });
        }
    };

    var Choose = function (){
        startLoading();
        var x  = $('#crop_x').val();
        var y = $('#crop_y').val();
        var w =	$('#crop_w').val();
        var h = $('#crop_h').val();
        var message= [];
        message['type'] = 'success';
        message['message'] = 'Whatever you did is successfull';
        ClosableMessage(message);
        $.ajax({
          type: "POST",
          url: "crop-demo.php",
          data: {'x':x, 'y':y,'w':w, 'h':h, 'file':file},
          enctype: 'multipart/form-data',
          processData: false,  // tell jQuery not to process the data
          success: function(value){
            stopLoading();
            }
        });
        stopLoading();
    };

    var Upload = function (){
        //startLoading();
		isEditing = true;
        error_messages.html('');
        //var formData = new FormData($('#'+parent+'_imageForm')[0]);
        var formData = new FormData();
        formData.append("action", $this.config['upload_action']);
        formData.append("upload_path", $this.config['upload_path']);
        formData.append("image_path", $this.config['image_path']);
        //console.log(ajax_image_upload_data);
        //$.extend(formData,ajax_image_upload_data);
		
		if($this.config['direct_crop_upload']){
			var cropData = jQuery(document.forms[''+parent+'_cropForm']).serializeArray();
			for (var i=0; i<cropData.length; i++){
				formData.append(cropData[i].name, cropData[i].value);
			}
		}
		
        $.each(ajax_image_upload_data,function(key,value){
            //console.log(value);
            formData.append(key, value);
        });

		{///// Progress Bar Code
		//console.log($('#'+$this.config['file_field']));
		var file = $('#'+$this.config['file_field']).get(0).files[0];
		//console.log(file);
		var file_name = file['name'];
		var file_size = file['size'];
		formData.append("file", file);
		formData.append('upload_path',$this.config['upload_path']);
		/////INITIATE A PROGRESSBAR, WHICH RETURNS THE PROGRESSBAR'S ELEMENT ID, WE WILL USE THIS ID TO UPDATE THE PROGRESS BAR'S PROGRESS
		var elem_id = initProgressBar();
		
		////// FILE SIZE CHECK , IF MORE THAN 50MB, IT WILL BE DISCARDED WITH A MESSAGE ABOVE THE PROGRESS BAR AND NEXT FILE WILL START UPLOADING
		if(file_size > 524288000000){
			var sizeInMB = (file_size / (1024*1024)).toFixed(2);
			//document.getElementById('ajax_upload_error_messages').appendChild('<li>'+file_name+' is '+sizeInMB+'MB, it exceeds maximum size limit (50MB). Discarding the file.</li>');
			document.getElementById(elem_id).remove();
			current_file_index_ajax++;		
			
			////IF ANOTHER FILE IN THE QUEUE START SENDING IT
			if(current_file_index_ajax <= total_files_ajax-1){
				UploadOneByOne();
				return;
			}
			else{ ///// IF NO MORE FILES RESET ALL THE VARIABLES, REMOVE THE PROGRESSBAR
				current_file_index_ajax = 0; 
				total_files_ajax = 0;
				all_files_object = {};
				document.getElementById('backdrop').classList.add('hidden');			
				//document.getElementById('ajax_upload_error_messages').innerHTML = '';
				//if (action.reload == true) location.reload(true); 
				return;
			}		
		}

		}//// END PROGRESS BAR CODE
		
/* 		for (var pair of formData.entries()) {
			console.log(pair[0]+ ', ' + pair[1]); 
		} */
        var value = '';
            $.ajax({
                  xhr: function(){
                    var xhr = new window.XMLHttpRequest();

					///// WHILE THE AJAX CALL IS GOING ON, GET THE SIZE OF DATA UPLOADED AND UPDATE THE PROGRESSBAR WITH IT
					xhr.upload.addEventListener("progress", function(evt) {
					  if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						percentComplete = parseInt(percentComplete * 100);
						updateProgressBar(elem_id,percentComplete);
					  }
					}, false);

                    return xhr;
                  },            
                type: "POST",
                url:imageProcessingUrl,
                data: formData,
                enctype: 'multipart/form-data',
                cache: false,
                contentType: false,
                processData: false,
                success: function(val){
					try{
						value = JSON.parse(val);

						if(value.ajaxresult){							
							currentImage = value.imagename;
							imageGUID = value.guid;
							if (value.hasOwnProperty('jsFunction')) {
								 eval(value.jsFunction);
							}
							$('#'+parent+'_btn_upload').addClass('hidden');
							if($this.config['direct_crop_upload']){
								$('#'+parent+'_image_edit_modal').modal('hide');
								//$('#'+parent+'_image_edit_modal').remove();
							}
							else{
								InitEdit();
							}
							//stopLoading(); 
						}
						else{
							$.each(value.message,function(num,msg){
								displayInfoInContainer(msg,'image-edit-error-messages','danger');
							});
							ResetAll();
						}
					}
					catch(error){
						console.log(val);
						console.log('Error: '+error);
						ResetAll();
					}
					document.getElementById(elem_id).remove();
					document.getElementById('backdrop').classList.add('hidden');
					document.getElementById('ajax_upload_error_messages').innerHTML = '';
                }
            });	
        
    };

	this.ajaxSaveUse = function($data){
		startLoading();
		var formData = {};
		formData['image'] = $this.GetCurrentImage();
		formData['module'] = $this.config['module'];
		formData['unique_id'] = $this.config['unique_id'];
		formData['upload_path'] = $this.config['upload_path'];
		formData['image_path'] = $this.config['image_path'];
		formData['action'] = $this.config['final_action'];
		formData['id'] = ajax_image_upload_data['id'];
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
					stopLoading();
				}
				if(value.ajaxresult == true) {
					if (value.hasOwnProperty('jsFunction')) {
						 eval(value.jsFunction);
					}
					ResetAll();
					stopLoading();
				}
				else {
					stopLoading();
				}
			
			}
		});
	}

	function appendProgressHolder(){
		var html =
		'\
		<div id="backdrop" class="hidden">\
			<div class="progressbar-container">\
				<ul style="font-size:20px;color:red;" id="ajax_upload_error_messages">\
					\
				</ul>\
			</div>\
		</div>\
		';
		$('body').prepend(html);
	}

	function initProgressBar(){
		document.getElementById('backdrop').classList.remove('hidden');
		var id = Math.random().toString(36).substring(3,9);
		if(total_files_ajax != 0){///// If Uploading Multiple Files
			var html = 
			'\
				<div class="progressbar-container" id="ui_'+id+'">\
					<div class="progressbar">\
					  <div class="percentage_done"></div>\
					</div>\
					<div class="percentage_done_text" style="font-size:20px;color:white;">1%</div>\
					<div class="upload_sub_text" style=""><span>Uploading '+all_files_object[current_file_index_ajax]['name']+' ('+(current_file_index_ajax+1)+' of '+total_files_ajax+')</span></div>\
					<div style="text-align:center" class="__after_multiple_uploads__ rings-holder hidden">\
						<div class="lds-ring"><div></div><div></div><div></div><div></div></div>\
						<div style="color:white;font-size:20px;">Files Uploaded succesfully ... Processing Files on the server</div>\
					</div>\
				</div>\
			';
		}
		else{
			var html = 
			'\
				<div class="progressbar-container" id="ui_'+id+'">\
					<div class="progressbar">\
					  <div class="percentage_done"></div>\
					</div>\
					<div class="percentage_done_text" style="font-size:20px;color:white;">1%</div>\
					<div class="upload_sub_text" style=""><span></span></div>\
					<div style="text-align:center" class="__after_multiple_uploads__ rings-holder hidden">\
						<div class="lds-ring"><div></div><div></div><div></div><div></div></div>\
						<div style="color:white;font-size:20px;">Files Uploaded succesfully ... Processing Files on the server</div>\
					</div>\
				</div>\
			';			
		}
		
		document.getElementById('backdrop').innerHTML += html;
		return 'ui_'+id;
	}

	var current_file_index_ajax = 0; //// KEEP TRACK OF THE INDEX OF THE FILE IN THE FILES OBJECT BEING UPLOADED, It WILL BE USED IN EACH AJAX FUNCTION TO GATHER FILE DETAILS FROM THE all_files_object, IT WILL BE INCREMENTED AFTER EVERY AJAX CALL COMPLETES
	var total_files_ajax = 0; ////TOTAL NUMBER OF FILES BEING UPLOADED
	var all_files_object = {}; //// OBJECT THAT HOLDS ALL THE FILE DETAILS ONCE FILES UPLOAD IS STARTED

	function updateProgressBar(id,percentComplete){
		if($this.config['one_image'])document.getElementById('backdrop').classList.remove('hidden');
		document.getElementById(id).querySelectorAll(".percentage_done")[0].style.width = ''+percentComplete+'%';
		document.getElementById(id).querySelectorAll(".percentage_done_text")[0].textContent = percentComplete+'%';
		document.getElementById(id).querySelectorAll(".percentage_done_text")[0].style.left = ''+percentComplete+'%';
		if (percentComplete === 100) {	
			document.getElementById(id).querySelectorAll(".__after_multiple_uploads__")[0].classList.remove('hidden');
			
		}	
	}

	this.UploadMultiple = function(file_field) {
		// Attach file
		all_files_object = document.getElementById(file_field).files;
		total_files_ajax = all_files_object.length; //// SET THE VALUE OF TOTAL NUMBER OF FILES, SO IT COULD BE USED EACH TOME ajaxPriyanshu is CALLED
		//// IF TOTAL ATLEAST ONE FILE IS SELECTED FOR UPLOADING, START SENDING THEM THROUGH AJAX CALL
		if(total_files_ajax >= 1){
			UploadOneByOne();
		}	

	}

	////// AJAX CALL FOR FILE UPLOADS
	function UploadOneByOne(){
		var xhttp = new XMLHttpRequest(); ////CREATE A NEW REQUEST
		var formData = new FormData(); ///// CREATE A FORMDATA OBJECT
		//formData.append('action', 'multiple_upload_async');
		//// FINALLY ADD JUST ONE IMAGE FROM all_files_object, USING CURRENT FILE INDEX
		formData.append("file", all_files_object[current_file_index_ajax]);
        formData.append("action", $this.config['upload_action']);
        formData.append("upload_path", $this.config['upload_path']);
        formData.append("image_path", $this.config['image_path']);		
        $.each(ajax_image_upload_data,function(key,value){
            formData.append(key, value);
        });
		var file_name = all_files_object[current_file_index_ajax]['name'];
		var file_size = all_files_object[current_file_index_ajax]['size'];
		
		/////INITIATE A PROGRESSBAR, WHICH RETURNS THE PROGRESSBAR'S ELEMENT ID, WE WILL USE THIS ID TO UPDATE THE PROGRESS BAR'S PROGRESS
		var elem_id = initProgressBar();
		
		////// FILE SIZE CHECK , IF MORE THAN 50MB, IT WILL BE DISCARDED WITH A MESSAGE ABOVE THE PROGRESS BAR AND NEXT FILE WILL START UPLOADING
		if(file_size > 524288000000){
			var sizeInMB = (file_size / (1024*1024)).toFixed(2);
			//document.getElementById('ajax_upload_error_messages').appendChild('<li>'+file_name+' is '+sizeInMB+'MB, it exceeds maximum size limit (50MB). Discarding the file.</li>');
			document.getElementById(elem_id).remove();
			current_file_index_ajax++;
			
			////IF ANOTHER FILE IN THE QUEUE START SENDING IT
			if(current_file_index_ajax <= total_files_ajax-1){
				UploadOneByOne();
				return;
			}
			else{ ///// IF NO MORE FILES RESET ALL THE VARIABLES, REMOVE THE PROGRESSBAR
				current_file_index_ajax = 0; 
				total_files_ajax = 0;
				all_files_object = {};
				document.getElementById('backdrop').classList.add('hidden');			
				//document.getElementById('ajax_upload_error_messages').innerHTML = '';
				//if (action.reload == true) location.reload(true); 
				return;
			}		
		}

		
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) { //// AFTER SUCCESFUL AJAX CALL SHOW MESSAGES
			try{
				var value = JSON.parse(this.responseText);
				document.getElementById(elem_id).remove();
				current_file_index_ajax++;/////INCREMENT THE CURRENT SSEQUENCE OF THE FILE PROCESSED, SO WE CAN USE IT AS INDEX TO GET THE NEXT FILE DETAILS
				
				if(value.ajaxresult == true) {
					if (value.hasOwnProperty('jsFunction')) {
						 eval(value.jsFunction);
					}
				}				
				///// IF THERE IS ANOTHER FILE IN THE QUEUE START SENDING IT
				if(current_file_index_ajax <= total_files_ajax-1){
					UploadOneByOne();
				}
				else{/////IF THERE ARE NO MORE FILES IN THE QUEUE, RESET ALL THE VARIABLES AND PROGRESSBAR
					current_file_index_ajax = 0; 
					total_files_ajax = 0; 
					all_files_object = {};	
					document.getElementById('backdrop').classList.add('hidden');			
					document.getElementById('ajax_upload_error_messages').innerHTML = '';	
					//if (action.reload == true) location.reload(true); 
				}
			}
			catch(error){
				console.log(error);
				console.log(this.responseText);
				document.getElementById(elem_id).remove();
				current_file_index_ajax++;/////INCREMENT THE CURRENT SSEQUENCE OF THE FILE PROCESSED, SO WE CAN USE IT AS INDEX TO GET THE NEXT FILE DETAILS			
				///// IF THERE IS ANOTHER FILE IN THE QUEUE START SENDING IT
				if(current_file_index_ajax <= total_files_ajax-1){
					UploadOneByOne();
				}
				else{/////IF THERE ARE NO MORE FILES IN THE QUEUE, RESET ALL THE VARIABLES AND PROGRESSBAR
					current_file_index_ajax = 0; 
					total_files_ajax = 0; 
					all_files_object = {};	
					document.getElementById('backdrop').classList.add('hidden');			
					document.getElementById('ajax_upload_error_messages').innerHTML = '';	
					//if (action.reload == true) location.reload(true); 
				}			
			}
			}
		};
		
		///// WHILE THE AJAX CALL IS GOING ON, GET THE SIZE OF DATA UPLOADED AND UPDATE THE PROGRESSBAR WITH IT
		xhttp.upload.addEventListener("progress", function(evt) {
		  if (evt.lengthComputable) {
			var percentComplete = evt.loaded / evt.total;
			percentComplete = parseInt(percentComplete * 100);
			updateProgressBar(elem_id,percentComplete);
		  }
		}, false);
		
		
		xhttp.open("POST", imageProcessingUrl, true);
		//xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send(formData); 		

	}
	
	////CROP AND USE
    var CropAndUpload = function (){
        startLoading();
        var formData = $('#'+parent+'_cropForm').serializeArray();	
        formData.push({name: 'action', value: 'crop'});
        formData.push({name: 'image', value: currentImage});
        formData.push({name: 'guid', value: imageGUID});
        formData.push({name: 'image_path', value: $this.config['image_path']});
        formData.push({name: 'upload_path', value: $this.config['upload_path']});
        var value = '';
        $.ajax({
            type: "POST",
            url:imageProcessingUrl,
            data: formData,
            success: function(val){
                value = JSON.parse(val);
                imageholder.html('<img src="'+FRONTEND_SITE_PATH+''+$this.config['upload_path']+''+image_path+'/'+currentImage+'?'+new Date().getTime()+'" class="center-block img-fluid img-responsive" style="margin:0 auto;" id="'+parent+'_image-crop"/>');
                refreshImageOnPage();
                updateImageSize(value.width, value.height);
                updateActualSize(value.width, value.height);
                undoButton.removeClass('hidden');
                InitCrop();			
                //window[func](value);
                stopLoading();
            }

        });
    };
	
	////CROP AND USE
    var Rotate = function (degrees){
        startLoading();
        var formData = [];	
        formData.push({name: 'action', value: 'rotate'});
        formData.push({name: 'image', value: currentImage});
        formData.push({name: 'guid', value: imageGUID});
        formData.push({name: 'image_path', value: image_path});
        formData.push({name: 'degrees', value: degrees});
        formData.push({name: 'upload_path', value: $this.config['upload_path']});
        var value = '';
        $.ajax({
            type: "POST",
            url:imageProcessingUrl,
            data: formData,
            success: function(val){
                value = JSON.parse(val);

                imageholder.html('<img src="'+FRONTEND_SITE_PATH+''+$this.config['upload_path']+''+image_path+'/'+currentImage+'?'+new Date().getTime()+'" class="center-block img-fluid" style="margin:0 auto;" id="'+parent+'_image-crop"/>');
                refreshImageOnPage();
                updateImageSize(value.width, value.height);
                updateActualSize(value.width, value.height);
                undoButton.removeClass('hidden');		
                //window[func](value);
                stopLoading();
            }

        });
    };
	
	//// WHEN A FILE IS SELECTED
    var DisplaySelectedImage = function (elem){  
        startLoading();
        error_messages.html('');
        var files = elem.files; // FileList object
        var f = files[0];
		isCropping = false;
        var reader = new FileReader();
        reader.onload = (function(theFile){
            return function(e) {
                var image = new Image();
                image.src = e.target.result;
                //$(window).on('load', function(){ ...});
                image.onload = function() {
                    // access image size here 
                    actualImageWidth = this.width;
                    actualImageHeight = this.height;
                    if($this.config['size_restriction'] && (actualImageWidth < $this.config['min_width'] || actualImageHeight < $this.config['min_height'])){
                       error_messages.html('<h4 class="font-red text-center">Too small! </br>Please Select an Image with dimensions more that 500'+$this.config['min_width']+'X'+$this.config['min_height']+' Pixels</h4>');
                    }
					else if($this.config['size_restriction'] && (actualImageWidth > $this.config['max_width'] || actualImageHeight > $this.config['max_height'])){
                       error_messages.html('<h4 class="font-red text-center">Too small! </br>Please Select an Image with dimensions more that 500'+$this.config['max_width']+'X'+$this.config['max_height']+' Pixels</h4>');
					}
                    else{
                        
                        imageholder.html('<img src="'+e.target.result+'" class="center-block img-fluid" style="margin:0 auto;" id="'+parent+'_image-crop"/>');
                        image_height.val(actualImageHeight);
                        image_width.val(actualImageWidth);
                        image_resize_width.val(actualImageWidth);
                        image_resize_height.val(actualImageHeight);	
                        upload_button.removeClass('hidden'); 
                        edit_toolbar.removeClass('hidden');                           
                    }
					if($this.config['direct_crop_upload']){
						InitCrop();
					}
                    stopLoading();	
                };

            }

            stopLoading();				
            })(f);
         reader.readAsDataURL(f);
    };

    var Resize = function (){
        startLoading();
        var formData = $('#'+parent+'_resize_form').serializeArray();
        formData.push({name: 'action', value: 'resize'});
        formData.push({name: 'image', value: currentImage});
        formData.push({name: 'guid', value: imageGUID});	
        formData.push({name: 'image_path', value: $this.config['image_path']});
        formData.push({name: 'upload_path', value: $this.config['upload_path']});	
        var value = '';
        $.ajax({
            type: "POST",
            url:imageProcessingUrl,
            data: formData,
            success: function(val){
                value = JSON.parse(val);
                imageholder.html('');			
                imageholder.html('<img src="'+FRONTEND_SITE_PATH+''+$this.config['upload_path']+''+image_path+'/'+currentImage+'?'+new Date().getTime()+'" class="center-block img-fluid" style="margin:0 auto;" id="'+parent+'_image-crop"/>');
                refreshImageOnPage();
                updateImageSize(value.width, value.height);
                updateActualSize(value.width, value.height);	
                undoButton.removeClass('hidden');				
                //window[func](value);
                stopLoading();
            }

        });
    };

    var Undo = function (){
        startLoading();
        var value = '';
            $.ajax({
                type: "POST",
                url:imageProcessingUrl,
                data: {'action':'undo','image':currentImage,'guid':imageGUID,'image_path':$this.config['image_path'],'upload_path':$this.config['upload_path']},
                success: function(val){
                    value = JSON.parse(val);
                    imageholder.html('');			
                    imageholder.html('<img src="'+FRONTEND_SITE_PATH+''+$this.config['upload_path']+''+$this.config['image_path']+'/'+currentImage+'?'+new Date().getTime()+'" class="center-block img-fluid" style="margin:0 auto;" id="'+parent+'_image-crop"/>');
                    refreshImageOnPage();
                    updateImageSize(value.width, value.height);
                    updateActualSize(value.width, value.height);	
                    if(value['undo_check'] == 0){
                        undoButton.addClass('hidden');		
                    }				
                    //window[func](value);
                    stopLoading();
                }

          });
    };

    this.EditImage = function (imageName,imagePath){
        error_messages.html('');
        startLoading();
        isEditing = true;
        if(typeof imagePath === 'undefined') imagePath = '';
        currentImage = imageName;	
        $this.config['image_path'] = imagePath;	
        //App.blockUI({target:"#image-modal-content"});
        imageholder.html('<img src="'+FRONTEND_SITE_PATH+''+$this.config['upload_path']+''+$this.config['image_path']+'/'+currentImage+'?'+new Date().getTime()+'" class="center-block img-fluid" style="margin:0 auto;" id="'+parent+'_image-crop"/>');
        var image = $('#'+parent+'_image-crop');
        image.on('load', function(){ 
            var actualImageWidth1 = image.prop("naturalWidth");
            var actualImageHeight1 = image.prop("naturalHeight");
            updateImageSize(actualImageWidth1,actualImageHeight1);	
            updateActualSize(actualImageWidth1,actualImageHeight1);	    
        });
        $.ajax({
            type: "POST",
            url:imageProcessingUrl,
            data: {'action':'edit-existing','image':imageName,'image_path':$this.config['image_path'],'upload_path':$this.config['upload_path']},
            success: function(val){
                value = JSON.parse(val);
                currentImage = value.imagename;
                $('#'+currentImageElement+'').attr('src',''+FRONTEND_SITE_PATH+''+$this.config['image_path']+''+$this.config['upload_path']+'/'+currentImage+'?'+new Date().getTime()+'');            
                imageGUID = value.guid;
                InitEdit();
                stopLoading();
            }
        });			
    };

	var appendImageEditModal = function($data){
		$data['parent'] = $data['unique_id'];
		$data['modal_title'] = getDefault($data['modal_title'],'');
		var $single_image_panel = '';
		var $multiple_image_panel = '';
		{ $single_image_panel = 
		'\
		<div class="row">\
			<span class="" id="'+$data['parent']+'_error-messages"></span>\
			<div class="col-md-12 text-center">\
			<!-- This is the form that our event handler fills -->\
			<form method="post" id="'+$data['parent']+'_imageForm" class="" onsubmit="return false" enctype="multipart/form-data">\
				<div class="form-group text-center hidden">\
					<label class="control-label">Choose Image<span class="required">* </span></label>\
					<div class="input-icon">\
						<i class="fa"></i>\
						<input type="file" class="btn green" style="margin:0 auto;" name="file" id="'+$data['parent']+'_file_single" >\
					</div>\
				</div>\
			</form>\
			<form method="post" id="'+$data['parent']+'_cropForm" class="" onsubmit="return false">\
				<div class="form-actions pull-right">\
					<input type="hidden" id="'+$data['parent']+'_crop_x" name="x"/>\
					<input type="hidden" id="'+$data['parent']+'_crop_y" name="y"/>\
					<input type="hidden" id="'+$data['parent']+'_crop_w" name="w"/>\
					<input type="hidden" id="'+$data['parent']+'_crop_h" name="h"/>\
					<input type="hidden" id="'+$data['parent']+'_image_path" name="image_path"/>\
				</div>\
			</form>	\
			</div>\
			<div class="col-md-12 _preview text-center"  id="'+$data['parent']+'_preview">	\
			<div class="text-center" style="padding-right:50px;padding-left:50px;margin:0 auto;" id="'+$data['parent']+'_image-holder">\
			<!-- This is the image were attaching Jcrop to -->\
			</div>\
			</div>\
	\
		<div id="'+$data['parent']+'_imageEditToolbar" style="position:absolute;top:-50px;z-index:999999;" class="col-md-11 text-center imageEditToolbar">\
			<div class="form-group inline">\
				<div class="input-inline">\
					<div class="input-group">\
						<span class="input-group-addon">\
						<i class="fa fa-arrows-h"></i>\
						</span>\
						<input type="text" name="__image_width" id="'+$data['parent']+'_image_width" class="form-control" placeholder="Width" disabled>\
					</div>\
				</div>\
			</div>\
	\
			<div class="form-group inline">\
				<div class="input-inline">\
					<div class="input-group">\
						<span class="input-group-addon">\
						<i class="fa fa-arrows-v"></i>\
						</span>\
						<input type="text" name="__image_height" id="'+$data['parent']+'_image_height" class="form-control" placeholder="Height" disabled>\
					</div>\
				</div>\
			</div>\
			<div class="form-group inline">\
				<div class="input-inline">\
					<div class="input-group">\
						<span class="input-group-addon">\
						<i class="fa fa-arrows-v"></i>\
						</span>\
						<input type="text" name="__image_size" id="'+$data['parent']+'_image_size" class="form-control" placeholder="Size" disabled>\
					</div>\
				</div>\
			</div>\
			\
		<button type="button" class="btn orange hidden" id="'+$data['parent']+'_btn_upload" >Upload</button>\
		<button class="btn red hidden" id="'+$data['parent']+'_btn_save_use" data-dismiss="modal" ">SAVE AND USE</button>\
		<button class="btn yellow hidden" id="'+$data['parent']+'_btn_crop"  >CROP</button>\
		<button class="btn blue hidden" id="'+$data['parent']+'_btn_crop_use" >CROP NOW</button>\
		<button class="btn btn-circle btn-icon-only yellow hidden" id="'+$data['parent']+'_btn_rotate_270" ><i class="fa fa-rotate-left"></i></button>\
		<button class="btn yellow hidden" id="'+$data['parent']+'_btn_undo"  >UNDO</button>\
		<button class="btn btn-circle btn-icon-only yellow hidden" id="'+$data['parent']+'_btn_rotate_90" ><i class="fa fa-rotate-right"></i></button>\
		<div class="dropdown hidden" id="'+$data['parent']+'_dropdown_resize">\
		<a href="javascript:;" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="true">\
		Resize <i class="fa fa-angle-down"></i>\
		</a>\
		<div class="dropdown-menu hold-on-click" style="width:250px; padding:20px;">\
		<form role="form" id="'+$data['parent']+'_resize_form" onsubmit="return false">\
		<div class="form-body">\
			<div class="alert alert-warning hidden" id="'+$data['parent']+'_image_resize_messages"></div>\
			<div class="form-group form-md-radios">\
				<label>Resize By</label>\
				<div class="md-radio-inline">\
					<div class="md-radio has-warning">\
						<input type="radio" id="'+$data['parent']+'_radio_pixel" name="__radio_by" class="md-radiobtn" value="pixel" checked>\
						<label for="'+$data['parent']+'_radio_pixel">\
						<span></span>\
						<span class="check"></span>\
						<span class="box"></span>\
						Px </label>\
					</div>\
					<div class="md-radio has-warning">\
						<input type="radio" id="'+$data['parent']+'_radio_percent" name="__radio_by" class="md-radiobtn" value="percent">\
						<label for="'+$data['parent']+'_radio_percent">\
						<span></span>\
						<span class="check"></span>\
						<span class="box"></span>\
						% </label>\
					</div>\
				</div>\
			</div>\
			<div class="form-group">\
				<div class="md-checkbox has-warning">\
					<input type="checkbox" id="'+$data['parent']+'_cons_prop" name="__cons_prop" class="md-check" >\
					<label for="'+$data['parent']+'_cons_prop">\
					<span></span>\
					<span class="check"></span>\
					<span class="box"></span>\
					Constraint Proportion </label>\
				</div>\
			</div>\
			\
			<div class="form-group">\
				<div class="input-inline">\
					<div class="input-group">\
						<span class="input-group-addon">\
						<i class="fa fa-arrows-h"></i>\
						</span>\
						<input type="number" name="__image_resize_width" id="'+$data['parent']+'_image_resize_width" class="form-control" placeholder="Width" style="width:90px;">\
						<input type="hidden" name="__image_resize_width_post" id="'+$data['parent']+'_image_resize_width_post">\
						<span class="input-group-addon resize_by_addon_width">\
						Pixels\
						</span>\
					</div>\
						<span class="help-block" id="'+$data['parent']+'_image_resize_width_label"></span>\
				</div>\
			</div>\
			\
			<div class="form-group">\
				<div class="input-inline">\
					<div class="input-group">\
						<span class="input-group-addon">\
						<i class="fa fa-arrows-v"></i>\
						</span>\
						<input type="number" name="__image_resize_height" id="'+$data['parent']+'_image_resize_height" class="form-control" placeholder="Height" style="width:90px;">\
						<input type="hidden" name="__image_resize_height_post" id="'+$data['parent']+'_image_resize_height_post">\
						<span class="input-group-addon resize_by_addon_height">\
						Pixels\
						</span>	\
					</div>\
						<span class="help-block" id="'+$data['parent']+'_image_resize_height_label"></span>\
				</div>\
			</div>\
			<button class="btn btn-warning dropdown-toggle" id="'+$data['parent']+'_resize_btn">Resize</button>\
			</div>\
			</form>\
			</div>\
			</div>\
			</div>\
		</div>\
		';
		}

		{ $multiple_image_panel = 
		'\
		<div class="row">\
			<div class="col-md-12  text-center">\
				<!-- This is the form that our event handler fills -->\
				<form method="post" id="'+$data['parent']+'_multipleImageForm" class="" onsubmit="return false" enctype="multipart/form-data">\
					<div class="form-group text-center">\
						<label class="control-label">Choose Images<span class="required">* </span></label>\
						<div class="input-icon">\
							<i class="fa"></i>\
							<input type="file" class="btn green" style="margin:0 auto;" name="file[]" id="'+$data['parent']+'_file" onchange="" multiple>\
						</div>\
					</div>\
					<button class="btn btn-lg green" id="'+$data['parent']+'_multiple-upload-button" >UPLOAD</button>\
				</form>\
			</div>\
		</div>\
		';
		}
		
		{ $nav_tabs = 
		'\
				<div class="tabbable-custom ">\
					<ul class="nav nav-tabs ">\
						<li class="active">\
							<a href="#'+$data['parent']+'_tab_1" data-toggle="tab" aria-expanded="tue">\
							UPLOAD AND EDIT </a>\
						</li>\
						<li class="">\
							<a href="#'+$data['parent']+'_tab_2" data-toggle="tab" aria-expanded="false">\
							UPLOAD MULTIPLE IMAGES </a>\
						</li>\
					</ul>\
					<div class="tab-content">\
						<div class="tab-pane active" id="'+$data['parent']+'_tab_1">  \
						'+$single_image_panel+'\
						</div>\
						<div class="tab-pane" id="'+$data['parent']+'_tab_2">  \
						'+$multiple_image_panel+'\
						</div>\
					</div>\
				</div>\
		';
		}

		var $modal_content = $nav_tabs;
		if($data['one_image']) {
			$modal_content = $single_image_panel;
		}
		if($data['multiple_image']){
			$modal_content = $multiple_image_panel;
		}
		if(1){var $modal = 
		'\
		<div class="modal" id="'+$data['parent']+'_image_edit_modal" tabindex="-1" role="dialog" aria-hidden="true" style="top:0px">\
			<div class="modal-dialog modal-full">\
				<div class="modal-content" id="'+$data['parent']+'_image_edit_modal_content">\
					<div class="modal-header">\
						<div class="row">\
							<div class="col-md-7">\
								<h4 class="modal-title" id="'+$data['parent']+'_image_modal_title">'+$data['modal_title']+'</h4>\
							</div>\
							<div class="col-md-5 text-right">\
							<button type="button" class="btn red" id="'+$data['parent']+'_close_edit_image_modal" >Close</button>\
							</div>\
						</div>\
					</div>\
					<div class="modal-body">\
						'+$modal_content+'\
					</div>\
					<div class="modal-footer">\
					</div>\
				</div>\
			</div>\
		</div>\
		'; 
        $modal = 
                '\
            <div class="modal  modal-scroll draggable-modal animated zoomIn " data-in-animation="zoomIn" data-out-animation="zoomOut" id="'+$data['parent']+'_image_edit_modal" data-backdrop="static" data-keyboard="true" style="margin:0 auto;">\
                <div class="modal-dialog modal-dialog-centered"   style="width:100%" tabindex="-1" role="dialog" aria-hidden="true">\
                    <div class="modal-content" id="'+$data['parent']+'_image_edit_modal_content">\
                        <div class="modal-header" style="padding:0px">\
                        <div class="modal-title fancy-modal-title" id="'+$data['parent']+'_image_modal_title">\
                            <div class="caption">\
                                <span class="bold uppercase">'+$data['modal_title']+'</span>\
                                \
                            </div>\
							<div class="modal-button-group pull-right">\
							<button type="button" class="btn red" id="'+$data['parent']+'_close_edit_image_modal" >Close</button>\
							</div>\
                        </div>\
                        \
                        </div>\
                        <div class="modal-body" id="'+$data['parent']+'-modal-body" style="padding:20px" >\
							'+$modal_content+'\
                        </div>\
                        <div class="modal-footer text-center justify-content-center">\
                            \
                        </div>\
                    </div>\
                    <!-- /.modal-content -->\
                </div>\
                </div>\
                ';
		}
		$('body').append($modal);
	};

    /*
     * Pass options when class instantiated
     */
    this.TestFunc = function(msg){
        //console.log($this.config);
    }
    this.construct(options);

};

/*
 * USAGE
 */
 
/*
 * Set variable myVar to new value
 */
////var newMyClass = new ImageEditor({ myVar : 'new Value' });
 
/*
 * Call myMethod inside myClass
 */
//newMyClass.myPublicMethod();
