var CanvasPreviewGenerator = function(options){
	var $this = this;
    this.options = {
		'imageProcessingUrl':site_path+'imageProcessing'
	};
	var EDITING_TEXT = false;
	var USER_IMAGE, PHONE_IMAGE, BACKGROUND_IMAGE, SHAPE_IMAGE, USER_TEXT;
	var canvasOriginalWidth,
	canvasOriginalHeight,
	imgWidth,
	imgHeight,
	bgImage,
	canvasScale = 1,
	photoUrlPortrait,
	canvasFullSize,
	photoUpload;
	var backgroundImage;
	var SHAPE_IMAGE_URL;
	var imageProcessingUrl = site_path+'imageProcessing';
	var canvasOffset = $("#canvas-main").offset();
	var canvas3 = document.getElementById("canvas-step-2");
	var destCanvas2 = document.getElementById("canvas-step-3");
	var destCanvasContext2 = destCanvas2.getContext('2d');
	var sourceCanvasMain = document.getElementById("canvas-main");
	var sourceCanvasBkg = document.getElementById("canvas-background-only");
	var sourceCanvasTxt = document.getElementById("canvas-text-only");
	var destCanvasPreview = document.getElementById("canvas-step-2");
	var destCanvasPreviewContext = destCanvasPreview.getContext('2d');	
	var canvas = window._canvas = new fabric.Canvas('canvas-main');
	var canvasTxt = new fabric.Canvas('canvas-text-only');
	var canvasTxtElement = $('#canvas-text-only-wrapper');
	var canvasElement = $('#canvas-main');
	var canvasWidth = 0;
	var canvasHeight = 0;
	var canvasBkg = new fabric.Canvas('canvas-background-only');
	var canvasBkgElement = $('#canvas-background-only-wrapper');
	var canvasFullSize = new fabric.Canvas('canvas-step-3');
	canvasBkgElementOffset = canvasBkgElement.offset();
	canvasTxtElementOffset = canvasTxtElement.offset();
	canvasBkgElement.css('top',(canvasOffset.top - canvasBkgElementOffset.top)+'px');
	canvasTxtElement.css('top',(canvasOffset.top - canvasTxtElementOffset.top)+'px');
	var shapeData = {};

	var current_file_index_ajax = 0; //// KEEP TRACK OF THE INDEX OF THE FILE IN THE FILES OBJECT BEING UPLOADED, It WILL BE USED IN EACH AJAX FUNCTION TO GATHER FILE DETAILS FROM THE all_files_object, IT WILL BE INCREMENTED AFTER EVERY AJAX CALL COMPLETES
	var total_files_ajax = 0; ////TOTAL NUMBER OF FILES BEING UPLOADED
	var all_files_object = {}; //// OBJECT THAT HOLDS ALL THE FILE DETAILS ONCE FILES UPLOAD IS STARTED
	
	this.construct = function(options){
		this.options = $.extend(this.options,options);
		backgroundImage = options.backgroundImage;
		photoUrlPortrait = options.photoUrlPortrait;
		shapeData = options.shapeData;
		//photoUrlPortrait = options.photoUrlPortrait;
		photoUpload = options.photoUpload; ////USER IMAGE ie. grey image with upload your picture text
		//backgroundImage = options.backgroundImage;

		appendProgressHolder();
		$('head').append('<link rel="stylesheet" type="text/css" href="'+core_theme_path+'css/imageCropJs.css">');
		all_files_object = this.options.bkgData;
		total_files_ajax = all_files_object.length; //// SET THE VALUE OF TOTAL NUMBER OF FILES, SO IT COULD BE USED EACH TOME ajaxPriyanshu is CALLED


		showPopoversOnElements('btn-generate-previews','Generate Previews',''+total_files_ajax+' previews need to be generated','orange','left');

		$('#btn-upload-test').click(function(){
			Upload();
		});
		$('#btn-generate-previews').click(function(){
			$(this).popover('hide');
			//initSize();
			if($this.options.module == 'mobile_cover' || $this.options.module == 'Case' || $this.options.module == 'case'){
				photoUrlPortrait = options.staticImage;
				//addBackgroundImageTest();
			}
			else if($this.options.module == 'background' || $this.options.module == 'Background'){
				backgroundImage = options.staticImage;
				//addBackgroundImageTest();
			}
			generatePreviewOneByOne();
		});
	}
	
	function initSize(){

		addUserImage(canvas);
		addText();
		addBackgroundImageTest();
	}


	function addOverlayImage(canvas){
		fabric.Image.fromURL(photoUrlPortrait, function (img) {	
			
			////// SET WIDTH OF ALL ELEMENTS
			canvasOriginalWidth = img.width;
			canvasOriginalHeight = img.height;
			SHAPE_IMAGE_URL = this.options.SHAPE_IMAGE_URL;
			canvasScale = 1;
			canvasWidth = 200;
			canvasScale = canvasWidth / canvasOriginalWidth;
			canvasHeight = canvasOriginalHeight * canvasScale;
			canvasOffset = $('#canvas-main').offset();

			setCanvasZoom(canvas);
			setCanvasZoom(canvasTxt);
			setCanvasZoom(canvasBkg);

			canvas3.width = canvasWidth;
			canvas3.height = canvasHeight;
			destCanvas2.width = canvasWidth;
			destCanvas2.height = canvasHeight;
			//initSize();
			////// END SET WIDTH OF ALL ELEMENTS
			
			PHONE_IMAGE = img;		
			img.set({
				scaleX: canvasScale,
				scaleY: canvasScale,
				originX: 'center',
				originY: 'center'
			});
			img.set({
				opacity: 1
			});
			//canvas.setOverlayImage(img);
			var group = new fabric.Group([img, img, img], { left: 0, top: 0 });
			canvas.setOverlayImage(group);
			canvas.renderAll();
			addBackgroundImageTest();
		});
	}
	
	function addBackgroundImageTest(){
         fabric.Image.fromURL(backgroundImage, function(img) {
			 //console.log(canvasBkg.width);
			 //console.log(canvasBkg.height);
            canvasBkg.setBackgroundImage(img, canvasBkg.renderAll.bind(canvasBkg), {
               scaleX: canvasWidth / img.width,
               scaleY: canvasHeight / img.height
            });
			//resolve(true);
			addShape(canvas);
			//console.log('addBackgroundImageTest');
         });	
	}
 		////// PNG BUBBLE
	function addShape(canvas,replacing_shape){

		var size = Object.keys(shapeData).length;
		var randKey = Math.floor(Math.random() * size) + 0;
		//console.log(SHAPE_IMAGE_URL);
		//fabric.Image.fromURL('img/thought_bubble.png', function(img) {
		if(SHAPE_IMAGE)canvas.remove(SHAPE_IMAGE);
		SHAPE_IMAGE_URL = FRONTEND_SITE_PATH+shapeData[randKey]['image_path']+'200X200-'+shapeData[randKey]['image'];
		fabric.Image.fromURL(SHAPE_IMAGE_URL, function(img) {
			var canvasAspect1 = canvasWidth / canvasHeight;
			var imgAspect1 = img.width / img.height;
			var left1, top1, scaleFactor1;
			if (canvasAspect1 >= imgAspect1) {
				var scaleFactor1 = canvasWidth / img.width;
				left1 = 0;
				top1 = -((img.height * scaleFactor1) - canvasHeight) / 2;
			} 
			else {
				var scaleFactor1 = canvasHeight / img.height;
				top1 = 0;
				left1 = -((img.width * scaleFactor1) - canvasWidth) / 2;

			}
			var imgScale = 1;
			if(img.width > canvasWidth){
				imgScale = (canvasWidth)/img.width;
			}

			img.set({
/* 				top: top1,
				left: left1,
				originX: 'left',
				originY: 'top',
				scaleX: imgScale,
				scaleY: imgScale, */
				streach: true,
			});
			//img.selectable = true;
			//img.scaleToHeight(canvas.getHeight());
			canvas.add(img);
			img.center();
			//canvas.bringToFront(img);
			//canvas.renderAll();
			SHAPE_IMAGE = img;
			canvas.bringToFront(USER_IMAGE);
			addUserImage(canvas);
			//console.log('addShape');
			//return true;
		});	
	}
	
		///// user image
	function addUserImage(canvas){
		fabric.Image.fromURL(photoUpload, function(img) {
			resizeUserImage(img);
			//return true;
			//console.log('addUserImage');
		});								
	}		///// user image

	function resizeUserImage(img){
			var canvasAspect1 = canvasWidth / canvasHeight;
			var imgAspect1 = img.width / img.height;
			var left1, top1, scaleFactor1;

			if (canvasAspect1 >= imgAspect1) {
				var scaleFactor1 = canvasWidth / img.width;
				left1 = 0;
				top1 = -((img.height * scaleFactor1) - canvasHeight) / 2;
			} 
			else {
				var scaleFactor1 = canvasHeight / img.height;
				top1 = 0;
				left1 = -((img.width * scaleFactor1) - canvasWidth) / 2;

			}
			var imgScale = 1;
			if(img.width > canvasWidth-80){
				imgScale = (canvasWidth-80)/img.width;
			}
			//console.log(imgScale);			
			img.set({
				streach: true,
/* 				top: top1,
				left: left1,
				originX: 'left',
				originY: 'top',
				scaleX: imgScale,
				scaleY: imgScale, */
/* 				borderColor: 'blue',
				cornerColor: 'red',
				cornerSize: 20,
				borderOpacityWhenMoving: 1,
				cornerStyle: 'circle',
				transparentCorners: false */
			});											
			//img.scaleToHeight(canvas.getHeight());
			img.selectable = false;
			canvas.add(img);
			//imgObjects['<?php echo $row['id'] ?>'].selectable = false;
			//canvas.sendBackwards(img);
			canvas.bringToFront(img);
			img.globalCompositeOperation = 'source-atop';
			USER_IMAGE = img;
			USER_IMAGE.center();
			//canvas.setActiveObject(USER_IMAGE);
			canvas.renderAll();
			addText();
			//initMouseWheelZoom(img);		
	}

	function setCanvasSize(canvasSizeObject) {
		//console.log(canvasSizeObject);
		canvasSizeObject.canvas.setWidth(canvasSizeObject.width);
		canvasSizeObject.canvas.setHeight(canvasSizeObject.height);
		canvasSizeObject.canvas.renderAll();
	}

	function addText(){
/* 		var fromTop = canvasHeight - 30 - 20;
		$('#input-user-text').val('Step Up Creations');
		if(USER_TEXT)canvasTxt.remove(USER_TEXT);
		USER_TEXT = new fabric.Textbox('Add Text', {
			top: fromTop,
			left: (canvasWidth - 220) / 2,
			fontFamily: 'arial',
			fill: '#fff',
			fontStyle: 'normal',
			fontWeight: 'normal',
			fill: '#fff',
			backgroundColor: 'rgba(255, 255, 255, 0)',
			fontSize: 20
		});
		//USER_TEXT.selectable = false;
		//text.globalCompositeOperation = 'hard-light';
		canvasTxt.add(USER_TEXT);
		USER_TEXT.width = 220;
		//USER_TEXT.center();
		canvasTxt.bringToFront(USER_TEXT); */
		populatePreviewCanvasBkgOnly();
	}
	
	function setCanvasZoom(canvas) {
		//console.log(canvas.lowerCanvasEl.width);
		//var canvasWidth = canvasOriginalWidth * canvasScale;
		//var canvasHeight = canvasOriginalHeight * canvasScale;
		//canvas.lowerCanvasEl.width = 0;
		//canvas.lowerCanvasEl.height = 0;
		//console.log(canvas.lowerCanvasEl.width);	
		canvas.setWidth(canvasWidth);
		canvas.setHeight(canvasHeight);
		$('#'+canvas.lowerCanvasEl.id).attr('width',canvasWidth);
		$('#'+canvas.lowerCanvasEl.id).attr('height',canvasHeight);	
		canvas.renderAll();
	}
	
	function generatePreviewOneByOne(){
		canvas.discardActiveObject().renderAll();
		canvasTxt.discardActiveObject().renderAll();
		//console.log(backgroundImage);
		if(current_file_index_ajax <= total_files_ajax-1){
			//Upload();
			var image_path = all_files_object[current_file_index_ajax]['image_path'];
			var image_name = all_files_object[current_file_index_ajax]['image'];
			//backgroundImage = frontend_site_path+image_path+image_name;
			//$this.options.background_id = all_files_object[current_file_index_ajax]['id'];
			var promise4;
			if($this.options.module == 'mobile_cover' || $this.options.module == 'Case' || $this.options.module == 'case'){//// If case module change the background image
				backgroundImage = frontend_site_path+image_path+image_name;
				promise4 = new Promise(function(resolve, reject) {
					//addBackgroundImageTest();
				});				
			}
			else if($this.options.module == 'background' || $this.options.module == 'Background'){//// if background module change the case image

				photoUrlPortrait = frontend_site_path+image_path+image_name;
				promise4 = new Promise(function(resolve, reject) {
					//addOverlayImage(canvas);
					//populatePreviewCanvasBkgOnly();
				});
			}
			addOverlayImage(canvas);

		
		}
		else{
			document.getElementById('backdrop').classList.add('hidden');
			//window.location.reload();
		}

	}	

	function populatePreviewCanvasBkgOnly(){
		destCanvasPreviewContext.clearRect(0, 0, destCanvasPreview.width, destCanvasPreview.height);
		destCanvasPreviewContext.drawImage(sourceCanvasBkg,0,0,destCanvasPreview.width,destCanvasPreview.height);
		//console.log('populatePreviewCanvasBkgOnly');
		populatePreviewCanvas();
	}

	function populatePreviewCanvas(){
		////// POPULATE PREVIEW CANVAS
		destCanvasPreviewContext.drawImage(sourceCanvasMain,0,0);
		destCanvasPreviewContext.drawImage(sourceCanvasTxt,0,0);
		//destCanvasPreviewContext.drawImage(photoUrlPortrait,0,0);
/* 		base_image = new Image();
		base_image.src = photoUrlPortrait;
		base_image.onload = function(){
			destCanvasPreviewContext.drawImage(base_image, 0, 0,destCanvasPreview.width,destCanvasPreview.height);
			destCanvasPreviewContext.drawImage(base_image, 0, 0,destCanvasPreview.width,destCanvasPreview.height);
			Upload()
		} */
		//Upload();
		Upload();
		//console.log('populatePreviewCanvas');
	}
	
	function generatePreviewImage(){
		canvas.discardActiveObject().renderAll();
		canvasTxt.discardActiveObject().renderAll();

		////// POPULATE PREVIEW CANVAS
		destCanvasPreviewContext.clearRect(0, 0, destCanvasPreview.width, destCanvasPreview.height);
		destCanvasPreviewContext.drawImage(sourceCanvasBkg,0,0,destCanvasPreview.width,destCanvasPreview.height);
		destCanvasPreviewContext.drawImage(sourceCanvasMain,0,0);
		destCanvasPreviewContext.drawImage(sourceCanvasTxt,0,0);
		//destCanvasPreviewContext.drawImage(photoUrlPortrait,0,0);
		//generatePreviewOneByOne();		
/* 		base_image = new Image();
		base_image.src = photoUrlPortrait;
		base_image.onload = function(){
			destCanvasPreviewContext.drawImage(base_image, 0, 0,destCanvasPreview.width,destCanvasPreview.height);
			destCanvasPreviewContext.drawImage(base_image, 0, 0,destCanvasPreview.width,destCanvasPreview.height);
			//generatePreviewOneByOne();
		} */
	}
	
	
    var Upload = function (){		
		//console.log(current_file_index_ajax);
		isEditing = true;
        var formData = new FormData();
        formData.append("action", $this.options.upload_action);
		if($this.options.module == 'mobile_cover' || $this.options.module == 'Case' || $this.options.module == 'case'){
			formData.append("case_id", $this.options.module_id);
			formData.append("background_id", all_files_object[current_file_index_ajax]['id']);
		}
		else if($this.options.module == 'background' || $this.options.module == 'Background'){
			formData.append("background_id", $this.options.module_id);
			formData.append("case_id", all_files_object[current_file_index_ajax]['id']);
		}		
        formData.append("module", $this.options.module);
        //formData.append("case_id", $this.options.case_id);
        //formData.append("background_id", $this.options.background_id);
        formData.append("image_path", '');

		{///// Progress Bar Code
		//var file = destCanvasPreview.toDataURL();
		var file = destCanvasPreview.toDataURL("image/png", 1.0);
		//console.log(file);
		var file_name = file['name'];
		var file_size = file['size'];
		formData.append("file", file);
		formData.append('upload_path','uploads/previews/bulktest/');
		/////INITIATE A PROGRESSBAR, WHICH RETURNS THE PROGRESSBAR'S ELEMENT ID, WE WILL USE THIS ID TO UPDATE THE PROGRESS BAR'S PROGRESS
		var elem_id = initProgressBar();

		}//// END PROGRESS BAR CODE

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
			url:$this.options.imageProcessingUrl,
			data: formData,
			//enctype: 'multipart/form-data',
			//cache: false,
			contentType: false,
			processData: false,
			success: function(val){
				try{
					//console.log(current_file_index_ajax);
					value = JSON.parse(val);
					if(value.ajaxresult){
						//console.log(current_file_index_ajax);
						//console.log(photoUrlPortrait);
						//if(+current_file_index_ajax <= 1){
							current_file_index_ajax++;
							generatePreviewOneByOne();
						//}
					}
					else{
						//current_file_index_ajax++;
					}
				}
				catch(error){
					console.log(val);
					console.log(current_file_index_ajax);
					console.log('Error: '+error);
				}
				document.getElementById(elem_id).remove();
				if(current_file_index_ajax > total_files_ajax){
					document.getElementById('backdrop').classList.add('hidden');
					//window.location.reload();
				}
				document.getElementById('ajax_upload_error_messages').innerHTML = '';
			}
		});	
	
    };

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


	function updateProgressBar(id,percentComplete){
		if(true)document.getElementById('backdrop').classList.remove('hidden');
		document.getElementById(id).querySelectorAll(".percentage_done")[0].style.width = ''+percentComplete+'%';
		document.getElementById(id).querySelectorAll(".percentage_done_text")[0].textContent = percentComplete+'%';
		document.getElementById(id).querySelectorAll(".percentage_done_text")[0].style.left = ''+percentComplete+'%';
		if (percentComplete === 100) {	
			document.getElementById(id).querySelectorAll(".__after_multiple_uploads__")[0].classList.remove('hidden');
			
		}	
	}


	function showPopoversOnElements(elementID,title,message,color,placement){
		if(!color) color = 'green';
		if(!placement) placement = 'bottom';
		var $popover_template = 
		'\
		 <div class="popover" role="tooltip" data-background-color="'+color+'" style="top:1000px;left:100px">\
			 <div class="arrow"></div>\
			 <h3 class="popover-title" data-background-color="'+color+'"></h3>\
			 <div class="popover-content">\
			 \
			 </div>\
		  </div>\
		';

		$element = $('#'+elementID);
		$element.popover({
			title: '<h6 class="font-white"><span class="font-white"></span> '+title+'</h6>',
			content : '<h6 class="font-white"><span class="font-white"></span> '+message+'</h6>',
			trigger:'hover',
			html: true,
			container: 'body',
			placement: 'bottom',
			template: $popover_template
		}).addClass('');
		//console.log(element);
		//console.log($element);
		$element.popover('show'); 
		
	}
		
	this.construct(options);
}
	//// END CLASS
