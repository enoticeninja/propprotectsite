            $(function() {
				var $ps_container 	= $('#ps_container');
				var $ps_overlay 	= $('#ps_overlay');
				var $ps_close		= $('#ps_close');
				var $thumb_container	= $('#thumb_container');
				/**
				* when we click on an album,
				* we load with AJAX the list of pictures for that album.
				* we randomly rotate them except the last one, which is
				* the one the User sees first. We also resize and center each image.
				*/
				$(document).on('click','.gallery-button',function(){
					var $elem = $(this).closest('tr');
					//var album_name 	= 'album' + parseInt($elem.index() + 1);
					var album_name 	= $(this).attr('data-reference');
                    console.log(album_name);
					var $loading 	= $('<div />',{className:'loading'});
					$elem.append($loading);
                    startLoading();
					$ps_container.find('img').remove();
					$.post('', {'reference_number':album_name} , function(val) {
                        stopLoading();
                        
                        var value = val;
                        var data = value['files'];
                        console.log(data);
                        var thumbs = value['thumbs'];
                        console.log(thumbs);
						var items_count	= data.length;
                        var $li = '';
						for(var i = 0; i < items_count; ++i){
							var item_source = data[i];
                            $li+= '<li><img src="'+thumbs[i]+'"></li>';
							var cnt = 0;
							$('<img />').load(function(){
								var $image = $(this);
								++cnt;
								resizeCenterImage($image);
								$ps_container.append($image);
								var r		= Math.floor(Math.random()*41)-20;
/* 								if(cnt < items_count){
									$image.css({
										'-moz-transform'	:'rotate('+r+'deg)',
										'-webkit-transform'	:'rotate('+r+'deg)',
										'transform'			:'rotate('+r+'deg)'
									});
								} */
								if(cnt == items_count){
									$loading.remove();
									$ps_container.show();
									$ps_close.show();
									$ps_overlay.show();
								}
							}).attr('src',item_source);
						}
                      $thumb_container.html($li);  
					},'json');
				});
				
				/**
				* when hovering each one of the images, 
				* we show the button to navigate through them
				*/
/* 				$ps_container.on('mouseenter',function(){
					$('#ps_next_photo').show();
					$('#ps_prev_photo').show();
				}).on('mouseleave',function(){
					$('#ps_next_photo').hide();
					$('#ps_prev_photo').hide();
				}); */
				
				/**
				* navigate through the images: 
				* the last one (the visible one) becomes the first one.
				* we also rotate 0 degrees the new visible picture 
				*/
				$('#ps_next_photo').on('click',function(){
					var $current 	= $ps_container.find('img:last');
					var r			= Math.floor(Math.random()*41)-20;
					
					var currentPositions = {
						marginLeft	: $current.css('margin-left'),
						marginTop	: $current.css('margin-top')
					}
					var $new_current = $current.prev();
					
					$current.animate({
						'marginLeft':'250px',
						'marginTop':'-385px'
					},250,function(){
						$(this).insertBefore($ps_container.find('img:first'))
/* 							   .css({
									'-moz-transform'	:'rotate('+r+'deg)',
									'-webkit-transform'	:'rotate('+r+'deg)',
									'transform'			:'rotate('+r+'deg)'
								}) */
							   .animate({
									'marginLeft':currentPositions.marginLeft,
									'marginTop'	:currentPositions.marginTop
									},250,function(){
/* 										$new_current.css({
											'-moz-transform'	:'rotate(0deg)',
											'-webkit-transform'	:'rotate(0deg)',
											'transform'			:'rotate(0deg)'
										}); */
							   });
					});
				});
								
				/**
				* navigate through the images: 
				* the last one (the visible one) becomes the first one.
				* we also rotate 0 degrees the new visible picture 
				*/
				$('#ps_prev_photo').on('click',function(){
					var $current 	= $ps_container.find('img:first');
					var r			= Math.floor(Math.random()*41)-20;
					
					var currentPositions = {
						marginLeft	: $current.css('margin-left'),
						marginTop	: $current.css('margin-top')
					}
					var $new_current = $current.prev();
					
					$current.animate({
						'marginLeft':'250px',
						'marginTop':'-385px'
					},250,function(){
						$(this).insertBefore($ps_container.find('img:last'))
/* 							   .css({
									'-moz-transform'	:'rotate('+r+'deg)',
									'-webkit-transform'	:'rotate('+r+'deg)',
									'transform'			:'rotate('+r+'deg)'
								}) */
							   .animate({
									'marginLeft':currentPositions.marginLeft,
									'marginTop'	:currentPositions.marginTop
									},250,function(){
/* 										$new_current.css({
											'-moz-transform'	:'rotate(0deg)',
											'-webkit-transform'	:'rotate(0deg)',
											'transform'			:'rotate(0deg)'
										}); */
							   });
					});
				});
				
				/**
				* close the images view, and go back to albums
				*/
				$('#ps_close').on('click',function(){
					$ps_container.hide();
					$ps_close.hide();
					$ps_overlay.fadeOut(400);
				});
				
				/**
				* resize and center the images
				*/
				function resizeCenterImage($image){
					var theImage 	= new Image();
					theImage.src 	= $image.attr("src");
					var imgwidth 	= theImage.width;
					var imgheight 	= theImage.height;
					
					var containerwidth  = 900;
					var containerheight = 800;
					
					if(imgwidth	> containerwidth){
						var newwidth = containerwidth;
						var ratio = imgwidth / containerwidth;
						var newheight = imgheight / ratio;
						if(newheight > containerheight){
							var newnewheight = containerheight;
							var newratio = newheight/containerheight;
							var newnewwidth =newwidth/newratio;
							theImage.width = newnewwidth;
							theImage.height= newnewheight;
						}
						else{
							theImage.width = newwidth;
							theImage.height= newheight;
						}
					}
					else if(imgheight > containerheight){
						var newheight = containerheight;
						var ratio = imgheight / containerheight;
						var newwidth = imgwidth / ratio;
						if(newwidth > containerwidth){
							var newnewwidth = containerwidth;
							var newratio = newwidth/containerwidth;
							var newnewheight =newheight/newratio;
							theImage.height = newnewheight;
							theImage.width= newnewwidth;
						}
						else{
							theImage.width = newwidth;
							theImage.height= newheight;
						}
					}
					$image.css({
						'width'			:theImage.width,
						'height'		:theImage.height,
						'margin-top'	:-(theImage.height/2)-10+'px',
						'margin-left'	:-(theImage.width/2)-10+'px'	
					});
				}
            });