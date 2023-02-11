$(document).ready(function(){
var style = $('<style>#imageEditToolbar .btn, #imageEditToolbar .dropdown, #imageEditToolbar .md-checkbox, #imageEditToolbar .inline{display:inline;}#imageEditToolbar .input-group{width:140px;}</style>');
$('html > head').append(style);
});
var image_path = '';
var file = '';
var actualImageWidth = '';
var actualImageHeight = '';
var currentImage = '';
var isCropping = false;
var undoButton = $('#__btn_undo');
var uploadsPath = 'uploads/';
var imageProcessingUrl = site_path+'imageProcessing';
var imageGUID = '';
var holder = '';
var current_element_unique_id = '';
var ajax_image_upload_data = {};
/* $(document.body).on('click','.tile', function(e){
	$(e.target).closest('.tile').toggleClass('selected');
	UpdateOnTileChange(e);
}); */
$('#image-edit-error-messages').html('<h4 class="font-yellow-gold text-center">Please Note:</br>1. Maximum Image size Allowed is 5MB.</br>2.Minimum image dimensions allowed are 500X500 px.</h4>');
function refreshImageOnPage(){
    if(currentImageElement != ''){
        $('#'+currentImageElement+'').attr('src',''+frontend_site_path+''+uploadsPath+''+imagePath+'/'+currentImage+'?'+new Date().getTime()+'');
    }
}

function refreshImageOnModal(){
    if(currentImageElement != ''){
        $('#'+currentImageElement+'').attr('src',''+frontend_site_path+''+uploadsPath+''+imagePath+'/'+currentImage+'?'+new Date().getTime()+'');
    }
}
function setAjaxImageUploadData(data){
    //ajax_image_upload_data = data;
    $.extend(ajax_image_upload_data, data);
    console.log(ajax_image_upload_data);
}

function SetCurrentHolder(holderr) {
    holder = holderr;
    current_element_unique_id = holderr;
}
var currentImageElement = '';
function SetCurrentImageElement(holderr) {
    currentImageElement = holderr;
}
function SetCurrentHolder2(e) {
    holder = $(e).closest('.paired-img-btn-holder').find('.unique-id').val();
    current_element_unique_id = holder;
}
function GetCurrentImage(){
	return currentImage;
}

function InitEdit(){
    $('#imageEditToolbar').removeClass('hidden');					
	$('#__btn_crop').removeClass('hidden');				
	$('#__btn_resize').removeClass('hidden');				
	$('#__btn_delete').removeClass('hidden');		
	$('#__btn_save_use').removeClass('hidden');	
	$('#__btn_rotate_90').removeClass('hidden');	
	$('#__btn_rotate_270').removeClass('hidden');	

}

function DestroyEdit(){
					
	$('#__btn_crop').addClass('hidden');				
	$('#__btn_resize').addClass('hidden');				
	$('#__btn_delete').addClass('hidden');		
	$('#__btn_save_use').addClass('hidden');	
	$('#__btn_rotate_90').addClass('hidden');	
	$('#__btn_rotate_270').addClass('hidden');	
	
}

function ResetAll(){
	//startLoading();	
	image_path = '';
	file = '';
	actualImageWidth = '';
	actualImageHeight = '';
	currentImage = '';
	isCropping = false;	
	isEditing = false;	
	resetImageSize();
	DestroyEdit();
	undoButton.addClass('hidden');	
	$('#imageEditToolbar').addClass('hidden');	
	$('#image-holder').html('');
	$('#file').val('');

	var temp = GetCurrentImage();
	$.ajax({
		type: "POST",
		url:imageProcessingUrl,
		data: {'action':'reset-all','imagename':temp,'guid':imageGUID},
		success: function(val){
			var value = JSON.parse(val);			
			if(value.ajaxresult == 'success'){
				imageGUID = '';				
			}			
			stopLoading();
		}

	});		
	//stopLoading();	
}



var imgTile;
var imgInput;
var imgId;
var func;
var evalCode;


function SetCurrentImageFunction(imageFunc){
	//$('#imageEditToolbar').removeClass('hidden');
	func = 	imageFunc;
}

function SetCurrentEvalCode(code){
	//$('#imageEditToolbar').removeClass('hidden');
	evalCode = 	code;
}

function ExecuteFunction(funcn){
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

}

function EvalCode(code){
	if(code != '' && typeof cide !== "undefined") {
		eval(code);		
	}
}

/* $('#full').on('hidden.bs.modal', function () {
	$('#imageEditToolbar').toggleClass('hidden');
}); */

var jcrop_api;

function updateActualSize(width, height){
	actualImageHeight = height;
	actualImageWidth = width;
}

function updateImageSize(width, height) {
	$('#__image_resize_width').val(width);
	$('#__image_resize_height').val(height);
	$('#__image_resize_width_label').val(width+'Px');
	$('#__image_resize_height_label').val(height+'Px');
	//$('#__image_resize_height_post').val(tempH);	
	//$('#__image_resize_width_post').val(tempW);	
	$('#__image_width').val(width);
	$('#__image_height').val(height);

}

function resetImageSize(){
	$('#__image_resize_width').val('');
	$('#__image_resize_height').val('');
	//$('#__image_resize_height_post').val('');	
	//$('#__image_resize_width_post').val('');	
	$('#__image_width').val('');
	$('#__image_height').val('');
		actualImageHeight = '';
		actualImageWidth = '';	
}

$('#__radio_pixel').on('change',function(){
        if ($(this).is(':checked')) {
			$("__image_resize_width").attr({"max" : actualImageWidth,"min" : 1});			
			$("__image_resize_height").attr({"max" : actualImageHeight,"min" : 1});				
            $('.resize_by_addon_height').html('Px');
            $('.resize_by_addon_width').html('Px');
			 if(isCropping) {
				$('#__image_resize_width').val($('#__image_width').val());
				$('#__image_resize_height').val($('#__image_height').val());					 
			 }
			 else {
				$('#__image_resize_width').val(actualImageWidth);
				$('#__image_resize_height').val(actualImageHeight);					 
			 }
        }
    });

$('#__radio_percent').on('change',function(){
	if ($(this).is(':checked')) {
		$("__image_resize_width").attr({"max" : 10,"min" : 1});			
		$("__image_resize_height").attr({"max" : 10,"min" : 1});			
		$('.resize_by_addon_width').html('%');			
		$('.resize_by_addon_height').html('%');
		$('#__image_resize_width').val('100');
		$('#__image_resize_height').val('100');	
	}
});

$('#__cons_prop').on('change',function(){
        if ($('#__radio_pixel').is(':checked')) {
			$('#__image_resize_height').val(actualImageHeight);
			$('#__image_resize_width').val(actualImageWidth);
        }
		else if($('#__radio_percent').is(':checked')) {	
			$('#__image_resize_height').val(100);
			$('#__image_resize_width').val(100);		
		}
});

	
$('#__image_resize_height').on('keyup',function(){
        if ($('#__radio_pixel').is(':checked')) {
				if($('#__cons_prop').is(':checked')) {
					var tempHeight = $('#__image_resize_height').val();
					var prop = actualImageHeight / tempHeight;
					var tempWidth = Math.floor(actualImageWidth / prop);
					$('#__image_resize_width').val(tempWidth);
				}

        }
		else if($('#__radio_percent').is(':checked')) {
				if($('#__cons_prop').is(':checked')) {	
					var tempHeight = $('#__image_resize_height').val();				
					var prop = 100 / tempHeight;
					var tempWidth = Math.floor(100 / prop);
					$('#__image_resize_width').val(tempWidth);	
					var tempH =  Math.floor((actualImageHeight * tempHeight) / 100);
					var tempW = Math.floor((actualImageWidth * tempWidth) / 100);
					$('#__image_resize_height_post').val(tempH);	
					$('#__image_resize_width_post').val(tempW);
					$('#__image_resize_width_label').html(tempW+'Px');
					$('#__image_resize_height_label').html(tempH+'Px');					
				}	
			else{
					var tempHeight = $('#__image_resize_height').val();					
					var tempWidth = $('#__image_resize_width').val();					
					var tempH =  Math.floor((actualImageHeight * tempHeight) / 100);
					var tempW = Math.floor((actualImageWidth * tempWidth) / 100);
					$('#__image_resize_height_post').val(tempH);	
					$('#__image_resize_width_post').val(tempW);
					$('#__image_resize_width_label').html(tempW+'Px');
					$('#__image_resize_height_label').html(tempH+'Px');							

			}			
		}
    });
	
$('#__image_resize_width').on('keyup',function(){

        if ($('#__radio_pixel').is(':checked')) {
				if($('#__cons_prop').is(':checked')) {
					var tempWidth = $('#__image_resize_width').val();
					var prop = actualImageWidth / tempWidth;
					var tempHeight = Math.floor(actualImageHeight / prop);
					$('#__image_resize_height').val(tempHeight);
					
				}

        }
		else if($('#__radio_percent').is(':checked')) {
				if($('#__cons_prop').is(':checked')) {	
					var tempWidth = $('#__image_resize_width').val();				
					var prop = 100 / tempWidth;
					var tempHeight = Math.floor(100 / prop);
					$('#__image_resize_height').val(tempHeight);
					var tempH =  Math.floor((actualImageHeight * tempHeight) / 100);
					var tempW = Math.floor((actualImageWidth * tempWidth) / 100);
					$('#__image_resize_height_post').val(tempH);	
					$('#__image_resize_width_post').val(tempW);	
					$('#__image_resize_width_label').html(tempW+'Px');
					$('#__image_resize_height_label').html(tempH+'Px');							
				}
			else{
					var tempHeight = $('#__image_resize_height').val();					
					var tempWidth = $('#__image_resize_width').val();					
					var tempH =  Math.floor((actualImageHeight * tempHeight) / 100);
					var tempW = Math.floor((actualImageWidth * tempWidth) / 100);
					$('#__image_resize_height_post').val(tempH);	
					$('#__image_resize_width_post').val(tempW);	
					$('#__image_resize_width_label').html(tempW+'Px');
					$('#__image_resize_height_label').html(tempH+'Px');							

			}				
		}
    });	
	


function InitCrop(){

	if(isCropping){
		jcrop_api.destroy();	
		isCropping = false;
		$('#__btn_crop_use').addClass('hidden');
		$('#__btn_crop').text('CROP');
		$('#__image_width').val(actualImageWidth);
		$('#__image_height').val(actualImageHeight);		
		$('#__image_resize_width').val(actualImageWidth);
		$('#__image_resize_height').val(actualImageHeight);				
	}
	else{
		Crop(actualImageWidth, actualImageHeight);	
		isCropping = true;	
		$('#__btn_crop_use').removeClass('hidden');	
		$('#__btn_crop').text('CANCEL CROP');		
	}

}	

function Crop(trueImageWidth, trueImageHeight) {		
		var size = [500,500];
		var cropWidth = 500;
		var cropHeight = 500;		
        $('#image-crop').Jcrop({
		allowResize:true,
		allowSelect:false,
		trueSize: [trueImageWidth, trueImageHeight],
		onChange: showCoords,
        onSelect: updateCoords
        },function(){
			jcrop_api = this;
		});
jcrop_api.animateTo([ 0,0,cropWidth,cropHeight]);		
}

function updateCoords(c){
	
	$('#crop_x').val(c.x);
	$('#crop_y').val(c.y);
	$('#crop_w').val(c.w);
	$('#crop_h').val(c.h);
  }

function showCoords(c){
	$('#__image_width').val(c.w);
	$('#__image_height').val(c.h);
	$('#__image_resize_width').val(c.w);
	$('#__image_resize_height').val(c.h);		
}

$('#featuredImageForm').submit(function(){
	if (parseInt($('#crop_w').val())) return true;
	alert('Please select a crop region then press submit.');
	return false;
});

function Delete() {
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
}


function Choose() {
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
}

function Upload(){
	startLoading();
    $('#image-edit-error-messages').html('');
	var formData = new FormData($('#imageForm')[0]);
    formData.append("action", "upload");
    console.log(ajax_image_upload_data);
    $.each(ajax_image_upload_data,function(key,value){
        //console.log(value);
        formData.append(key, value);
    });
      console.log(formData);
	var value = '';
		$.ajax({
              xhr: function() {
                var xhr = new window.XMLHttpRequest();

                xhr.upload.addEventListener("progress", function(evt) {
                  if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total;
                    percentComplete = parseInt(percentComplete * 100);
                    console.log(percentComplete);

                    if (percentComplete === 100) {

                    }

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
                console.log(val);
				value = JSON.parse(val);
                if (value.hasOwnProperty('jsFunction')) {
                     eval(value.jsFunction);
                }                
                if(value.ajaxresult){
                    currentImage = value.imagename;
                    imageGUID = value.guid;
                    $('#__btn_upload').addClass('hidden'); 
                    InitEdit();
                    stopLoading(); 
                }
                else{
                    $.each(value.message,function(num,msg){
                        displayInfoInContainer(msg,'image-edit-error-messages','danger');
                    });
                    ResetAll();
                }

			}
		});	
	
}


function UploadMultiple(){
	startLoading();
	var imageholder = $('#image-holder');	
	var formData = new FormData($('#multipleImageForm')[0]);
    //formData.push({name: 'action', value: 'multiple-upload'});
	//console.log('Current Image:'+currentImage);
    //formData.push({name: 'image', value: currentImage});
    //formData.push({name: 'guid', value: imageGUID});	
        var value = '';
		$.ajax({
              xhr: function() {
                var xhr = new window.XMLHttpRequest();

                xhr.upload.addEventListener("progress", function(evt) {
                  if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total;
                    percentComplete = parseInt(percentComplete * 100);
                    console.log(percentComplete);

                    if (percentComplete === 100) {

                    }

                  }
                }, false);

                return xhr;
              },              
			type: "POST",
			enctype: 'multipart/form-data',
			cache: false,
			contentType: false,
			processData: false,
			url:imageProcessingUrl,
			data: formData,
			success: function(val){
				value = JSON.parse(val);
                $('#ajaxRetable').prepend(value['html']);	
                ResetAll();							
				//window[func](value);
				stopLoading();
			}

	  });

}

function UploadMultipleRouterX(data,form){
	startLoading();
	var imageholder = $('#image-holder');	
	var formData = new FormData($('#multipleImageForm')[0]);
    $.each(data,function(n,v){
        formData.append(n, v)
    });	
        var value = '';
		$.ajax({
              xhr: function() {
                var xhr = new window.XMLHttpRequest();

                xhr.upload.addEventListener("progress", function(evt) {
                  if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total;
                    percentComplete = parseInt(percentComplete * 100);
                    console.log(percentComplete);

                    if (percentComplete === 100) {

                    }
                  }
                }, false);

                return xhr;
              },              
			type: "POST",
			enctype: 'multipart/form-data',
			cache: false,
			contentType: false,
			processData: false,
			url:'',
			data: formData,
			success: function(val){
				value = JSON.parse(val);
                $('#ajaxRetable').prepend(value['html']);	
                ResetAll();							
				//window[func](value);
				stopLoading();
			}

	  });

}

function CropAndUpload(){   ///CROP AND USE
	startLoading();
	var imageholder = $('#image-holder');
	var formData = $('#cropForm').serializeArray();	
    formData.push({name: 'action', value: 'crop'});
    formData.push({name: 'image', value: currentImage});
    formData.push({name: 'guid', value: imageGUID});
    formData.push({name: 'path', value: image_path});
	var value = '';
		$.ajax({
			type: "POST",
			url:imageProcessingUrl,
			data: formData,
			success: function(val){
                console.log(val);
				value = JSON.parse(val);

				imageholder.html('<img src="'+frontend_site_path+''+uploadsPath+''+image_path+'/'+currentImage+'?'+new Date().getTime()+'" class="center-block img-responsive" style="margin:0 auto;" id="image-crop"/>');
                if(typeof imagePath === 'undefined') imagePath = '';
                refreshImageOnPage();
				updateImageSize(value.width, value.height);
				updateActualSize(value.width, value.height);
				undoButton.removeClass('hidden');
				InitCrop();			
				//window[func](value);
				stopLoading();
			}

	  });
}

function Rotate(degrees){   ///CROP AND USE
	startLoading();
	var imageholder = $('#image-holder');
	var formData = [];	
    formData.push({name: 'action', value: 'rotate'});
    formData.push({name: 'image', value: currentImage});
    formData.push({name: 'guid', value: imageGUID});
    formData.push({name: 'path', value: image_path});
    formData.push({name: 'degrees', value: degrees});
	var value = '';
		$.ajax({
			type: "POST",
			url:imageProcessingUrl,
			data: formData,
			success: function(val){
                console.log(val);
				value = JSON.parse(val);

				imageholder.html('<img src="'+frontend_site_path+''+uploadsPath+''+image_path+'/'+currentImage+'?'+new Date().getTime()+'" class="center-block img-responsive" style="margin:0 auto;" id="image-crop"/>');
                if(typeof imagePath === 'undefined') imagePath = '';
                refreshImageOnPage();
				updateImageSize(value.width, value.height);
				updateActualSize(value.width, value.height);
				undoButton.removeClass('hidden');		
				//window[func](value);
				stopLoading();
			}

	  });
}

function DisplaySelectedImage(elem){    //// WHEN A FILE IS SELECTED
	startLoading();
    $('#image-edit-error-messages').html('');
	var imageholder = $('#image-holder');
	var files = elem.files; // FileList object
	var f = files[0];

	var reader = new FileReader();
	reader.onload = (function(theFile) {

			
			return function(e) {
				
				var image = new Image();
				image.src = e.target.result;
                //$(window).on('load', function(){ ...});
				image.onload = function() {
					// access image size here 
					actualImageWidth = this.width;
					actualImageHeight = this.height;
                    if(actualImageWidth < 500 || actualImageHeight < 500){
                        $('#image-edit-error-messages').html('<h4 class="font-red text-center">Too small! </br>Please Select an Image with dimensions more that 500X500 Pixels</h4>');
                    }
                    else{
                        imageholder.html('<img src="'+e.target.result+'" class="center-block img-responsive" style="margin:0 auto;" id="image-crop"/>');
                        $('#__image_height').val(actualImageHeight);
                        $('#__image_width').val(actualImageWidth);
                        $('#__image_resize_width').val(actualImageWidth);
                        $('#__image_resize_height').val(actualImageHeight);	
                        $('#__btn_upload').removeClass('hidden'); 
                        $('#imageEditToolbar').removeClass('hidden');                           
                    }
                
					stopLoading();	
				};

			}
		stopLoading();				
		})(f);
	 reader.readAsDataURL(f);
}

function Resize(){
	startLoading();
	var imageholder = $('#image-holder');	
	var formData = $('#__resize_form').serializeArray();

    formData.push({name: 'action', value: 'resize'});
    formData.push({name: 'image', value: currentImage});
    formData.push({name: 'guid', value: imageGUID});	
    formData.push({name: 'path', value: image_path});	
	var value = '';
		$.ajax({
			type: "POST",
			url:imageProcessingUrl,
			data: formData,
			success: function(val){
				value = JSON.parse(val);
				imageholder.html('');			
				imageholder.html('<img src="'+frontend_site_path+''+uploadsPath+''+image_path+'/'+currentImage+'?'+new Date().getTime()+'" class="center-block img-responsive" style="margin:0 auto;" id="image-crop"/>');
                if(typeof imagePath === 'undefined') imagePath = '';
                refreshImageOnPage();
				updateImageSize(value.width, value.height);
				updateActualSize(value.width, value.height);	
				undoButton.removeClass('hidden');				
				//window[func](value);
				stopLoading();
			}

	  });
}

function Undo(){
	startLoading();
	var imageholder = $('#image-holder');	
	var formData = $('#__resize_form').serializeArray();
    formData.push({name: 'path', value: image_path});	

	var value = '';
		$.ajax({
			type: "POST",
			url:imageProcessingUrl,
			data: {'action':'undo','image':currentImage,'guid':imageGUID,'path':image_path},
			success: function(val){
				value = JSON.parse(val);
				imageholder.html('');			
				imageholder.html('<img src="'+frontend_site_path+''+uploadsPath+''+image_path+'/'+currentImage+'?'+new Date().getTime()+'" class="center-block img-responsive" style="margin:0 auto;" id="image-crop"/>');
                if(typeof imagePath === 'undefined') imagePath = '';
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
}

var isEditing = false;

function EditImage(imageName,imagePath){
    $('#image-edit-error-messages').html('');
	startLoading();
    isEditing = true;
    if(typeof imagePath === 'undefined') imagePath = '';
	var imageholder = $('#image-holder');
	currentImage = imageName;	
	image_path = imagePath;	
    //App.blockUI({target:"#image-modal-content"});
	imageholder.html('<img src="'+frontend_site_path+''+uploadsPath+''+imagePath+'/'+currentImage+'?'+new Date().getTime()+'" class="center-block img-responsive" style="margin:0 auto;" id="image-crop"/>');
	var image = $('#image-crop');
    image.on('load', function(){ 
		var actualImageWidth1 = image.prop("naturalWidth");
		var actualImageHeight1 = image.prop("naturalHeight");
		updateImageSize(actualImageWidth1,actualImageHeight1);	
		updateActualSize(actualImageWidth1,actualImageHeight1);	    
    });

    
    $.ajax({
        type: "POST",
        url:imageProcessingUrl,
        data: {'action':'edit-existing','image':imageName,'path':imagePath},
        success: function(val){
            value = JSON.parse(val);
            currentImage = value.imagename;
            $('#'+currentImageElement+'').attr('src',''+frontend_site_path+''+uploadsPath+''+imagePath+'/'+currentImage+'?'+new Date().getTime()+'');            
            imageGUID = value.guid;
            InitEdit();
            stopLoading();
        }
    });			
}