<?php
include_once("../bootstrap.php");
include_once("../common.php");
include 'ImageManipulator.php';
$uploads_path = DIR_PRODUCT_UPLOADS;
$undo_path = DIR_IMAGE_UNDO;
$site_uploads_path = $site_path.'uploads/creatives/';
$date_created = date("Y-m-d");


function DeleteDirectoryAndContents($path)
{
    if (is_dir($path) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file)
        {
            if (in_array($file->getBasename(), array('.', '..')) !== true)
            {
                if ($file->isDir() === true)
                {
                    rmdir($file->getPathName());
                }

                else if (($file->isFile() === true) || ($file->isLink() === true))
                {
                    unlink($file->getPathname());
                }
            }
        }

        return rmdir($path);
    }

    else if ((is_file($path) === true) || (is_link($path) === true))
    {
        return unlink($path);
    }

    return false;
}


if ($_POST['action'] == 'upload'){
	
		$pic_name = ''.$_FILES['file']['name'].'';

		$iman = new ImageManipulator();

		$temp = '';
		$img_name_only = $iman->file_name($pic_name);
		$img_type_only = $iman->file_extension($pic_name);			
		$ic = 0;
		while (file_exists($uploads_path."$img_name_only.$img_type_only")) {
			$img_name_only = $img_name_only.$ic;	
			$ic++;
		}
		move_uploaded_file($_FILES['file']['tmp_name'], $uploads_path."$img_name_only.$img_type_only");	
		$timestamp = time();
		mkdir($undo_path."/$timestamp");
		//copy($uploads_path."$img_name_only.$img_type_only", $undo_path."/$timestamp/$img_name_only-$timestamp.$img_type_only");
		
		$_SESSION[get_session_values('image-undo')] = array();  //RESET THE UNDO SESSION VARIABLES
		$_SESSION[get_session_values('image-undo')][$timestamp] = array();  //RESET THE UNDO SESSION VARIABLES
		$_SESSION[get_session_values('image-undo-count')] = array();	//RESET THE UNDO SESSION COUNT		
		///POPULATE THE IMAGE UNDO SESSION VARIABLE WITH NEW IMAGE	
		//$_SESSION[get_session_values('image-undo')][$timestamp][] = "$img_name_only-$timestamp.$img_type_only"; 
		$_SESSION[get_session_values('image-undo-count')][$timestamp] = 0; //START UNDO SESSION COUNT FROM 1


		$return['imagename'] = "$img_name_only.$img_type_only";
		$return['123'] = $_SESSION;
		$return['guid'] = $timestamp;
		echo json_encode($return);
	    exit();
}

else if ($_POST['action'] == 'crop')
{
	////// TODO: implement saving to the database 
	////// TODO: implement saving in different sizes and cropping


		//$return['post'] = $_POST;
		//$return['files'] = $_FILES;
		//print_r($_POST);
		$iman = new ImageManipulator();
		$image = $_POST['image'];
		$guid = $_POST['guid'];
		$x = intval($_POST['x']);
		$y = intval($_POST['y']);
		$dst_w = intval($_POST['w']);
		$dst_h = intval($_POST['h']);
		$temp = '';

		$timestamp = time();
		$img_name_only = $iman->file_name($image);
		$img_type_only = $iman->file_extension($image);			
		copy($uploads_path."$image", $undo_path."/$guid/$img_name_only-$timestamp.$img_type_only");
		$_SESSION[get_session_values('image-undo')][$guid][] = "$img_name_only-$timestamp.$img_type_only";
		$_SESSION[get_session_values('image-undo-count')][$guid]++;
		
		$iman->image_resize("$uploads_path$image", "$uploads_path$image", intval($x),intval($y), $dst_w,$dst_h, intval($dst_w),intval($dst_h), 2);
		list($widthNew, $heightNew) = getimagesize($uploads_path.$image);
		


		
		$return['width'] = $widthNew;
		$return['height'] = $heightNew;
		$return['imagename'] = "$image";
		$return['result'] = 'success';
		echo json_encode($return);
	   exit();
}

else if ($_POST['action'] == 'resize')
{

		$iman = new ImageManipulator();
		$image = $_POST['image'];
		$guid = $_POST['guid'];
		//print_r($_POST);
		$timestamp = time();
		$img_name_only = $iman->file_name($image);
		$img_type_only = $iman->file_extension($image);			
		copy($uploads_path."$image", $undo_path."/$guid/$img_name_only-$timestamp.$img_type_only");
		$_SESSION[get_session_values('image-undo')][$guid][] = "$img_name_only-$timestamp.$img_type_only";
		$_SESSION[get_session_values('image-undo-count')][$guid]++;	

		
		if($_POST['__radio_by'] == 'percent'){
		$width = intval($_POST['__image_resize_width_post']);
		$height = intval($_POST['__image_resize_height_post']);	
		} else {
		$width = intval($_POST['__image_resize_width']);
		$height = intval($_POST['__image_resize_height']);				
		}
		$proportional = isset($_POST['__cons_prop']) ? true : false ;
		$temp = '';

		$imgresampled = $iman->smart_resize_image($uploads_path.$image,null, $width, $height, $proportional, $uploads_path.$image, false);
		list($widthNew, $heightNew) = getimagesize($uploads_path.$image);
		$return['width'] = $widthNew;
		$return['height'] = $heightNew;
		$return['result'] = 'success';
		
	
		echo json_encode($return);
	    exit();
}

else if ($_POST['action'] == 'undo')
{

		$iman = new ImageManipulator();
		$image = $_POST['image'];
		$guid = $_POST['guid'];

		$temp = '';

		//// Copy the last image in session to the products folder
		//// Remove that image from session and decrement the count 
		//// Send the width and height and result
		//// ????? What if there is another session or another new product page opened ???? session variables will clash ????
		//// Give those sessions a sesion id and save that id in javascript
		//// ??????????? Save the session undo in database instead of session variable ???????
		$timestamp = time();
		$img_name_only = $iman->file_name($image);
		$img_type_only = $iman->file_extension($image);	
		$current_session_count = $_SESSION[get_session_values('image-undo-count')][$guid]-1;	
		$previous_image = $_SESSION[get_session_values('image-undo')][$guid][$current_session_count];		
		rename($undo_path."/$guid/$previous_image", $uploads_path."$image");
		list($widthNew, $heightNew) = getimagesize($uploads_path.$image);
		$return['width'] = $widthNew;
		$return['height'] = $heightNew;
		$return['height'] = $heightNew;
		$return['undo_check'] = $current_session_count;
		$_SESSION[get_session_values('image-undo-count')][$guid]--;
		unset($_SESSION[get_session_values('image-undo')][$guid][$current_session_count]);	
		echo json_encode($return);
	    exit();
}

else if(isset($_POST['fileToDelete'])) 
{

///TODO: implement deleting from database, if its implemented in the above upload code
	$return['post'] = $_POST;
	if (file_exists($_POST['fileToDelete'])) { unlink ($_POST['fileToDelete']); }
	echo json_encode($return);
	exit();
}

else if ($_POST['action'] == 'cancel')
{
	$guid = $_POST['guid'];	
	$image = $_POST['imagename'];
		if(isset($_SESSION[get_session_values('image-undo')][$guid])){
			unset($_SESSION[get_session_values('image-undo')][$guid]);  //RESET THE UNDO SESSION VARIABLES
		}
		if(isset($_SESSION[get_session_values('image-undo-count')][$guid])){		
			unset($_SESSION[get_session_values('image-undo-count')][$guid]);	//RESET THE UNDO SESSION COUNT	
		}
		if($guid != ''){		
			if(file_exists($undo_path.$guid)){		
				DeleteDirectoryAndContents($undo_path.$guid);
			}
		}
}


//////IF IS CALLED FROM EDIT PAGE
else if($_POST['action'] == 'newImageforPageEdit') {

		$image = $_POST['imagename'];
		$guid = $_POST['guid'];
		$thumbnail = $image;
		$iman = new ImageManipulator();		
		$iman->smart_resize_image($uploads_path.$image,null, 100, 100, false, $uploads_path.'100x100/'.$image, false);		
		$iman->smart_resize_image($uploads_path.$image,null, 50, 50, false, $uploads_path.'50x50/'.$image, false);		
		$sql = "INSERT INTO images 
		(image,thumbnail,date_created)
		VALUES('$image','$thumbnail','$date_created')";
		$return['sql'] =  $sql;
		$query = mysqli_query($db_conx, $sql);
		//$image_id = mysqli_insert_id($db_conx);
		if ($query) {

			$return['sqlmessage'] =  "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($db_conx);
			$return['sqlmessage'] =  "Error: " . $sql . "<br>" . mysqli_error($db_conx);
		}		
		$imageid = mysqli_insert_id($db_conx);	



		$return['html'] = 
		'
											<tr id="tr-'.$imageid.'">
												<td>
													<a href="'.$site_uploads_path.$image.'" class="fancybox-button" data-rel="fancybox-button">
													<img class="" src="'.$site_uploads_path.'100x100/'.$image.'" alt="">
													</a>
													<input type="hidden" id="image-'.$imageid.'" name="neww[images][id][]" value="'.$imageid.'">
												</td>
												<td>
													<input type="text" class="form-control" name="neww[images][label][]" value="">
												</td>

												<td>
													<div class="md-radio has-warning">
														<input type="radio" id="featured-image-'.$imageid.'" name="product[images][featuredimage][]" class="md-radiobtn" value="'.$imageid.'">
														<label for="featured-image-'.$imageid.'">
														<span class="inc"></span>
														<span class="check"></span>
														<span class="box"></span>
														Set Featured Image </label>
													</div>
												</td>
												<td>
													<div class="md-radio has-warning">
														<input type="radio" id="featured-thumb-'.$imageid.'" name="product[images][featuredthumb][]" class="md-radiobtn" value="'.$imageid.'">
														<label for="featured-thumb-'.$imageid.'">
														<span class="inc"></span>
														<span class="check"></span>
														<span class="box"></span>
														Set Featured Thumbnail </label>
													</div>
												</td>
												<td>
													<a href="javascript:;" onclick="removeImage(this)" class="btn default btn-sm">
													<i class="fa fa-times"></i> Remove </a>
												</td>
											</tr>
											
										
		';

		if(isset($_SESSION[get_session_values('image-undo')][$guid])){
			unset($_SESSION[get_session_values('image-undo')][$guid]);  //RESET THE UNDO SESSION VARIABLES
		}
		if(isset($_SESSION[get_session_values('image-undo-count')][$guid])){		
			unset($_SESSION[get_session_values('image-undo-count')][$guid]);	//RESET THE UNDO SESSION COUNT	
		}
		if($guid != ''){		
			if(file_exists($undo_path.$guid)){		
				DeleteDirectoryAndContents($undo_path.$guid);
			}
		}
		$return['path'] = $image;
		echo json_encode($return);
	   exit();	
	   
	   
exit();		

}


else if($_POST['action'] == 'reset-all') {

		$image = $_POST['imagename'];
		$guid = $_POST['guid'];

		if(isset($_SESSION[get_session_values('image-undo')][$guid])){
			unset($_SESSION[get_session_values('image-undo')][$guid]);  //RESET THE UNDO SESSION VARIABLES
		}
		if(isset($_SESSION[get_session_values('image-undo-count')][$guid])){		
			unset($_SESSION[get_session_values('image-undo-count')][$guid]);	//RESET THE UNDO SESSION COUNT	
		}
		if($guid != ''){		
			if(file_exists($undo_path.$guid)){		
				DeleteDirectoryAndContents($undo_path.$guid);
			}
		}
		$return['path'] = $image;
		$return['ajaxresult'] = 'success';
		echo json_encode($return);
	   exit();	
	   
	   
exit();		

}

else if($_POST['action']){
	
	if($_POST['action'] == 'publishImage'){

		exit();
	}

	else if ($_POST['action'] == 'newImage' || $_POST['action'] == 'changeImage') {


		$image = $_POST['imagename'];
		$guid = $_POST['guid'];
		$thumbnail = $image;
		$iman = new ImageManipulator();		
		$iman->smart_resize_image($uploads_path.$image,null, 100, 100, false, $uploads_path.'100x100/'.$image, false);		
		$iman->smart_resize_image($uploads_path.$image,null, 50, 50, false, $uploads_path.'50x50/'.$image, false);		
		$sql = "INSERT INTO images 
		(image,thumbnail,date_created)
		VALUES('$image','$thumbnail','$date_created')";
		$return['sql'] =  $sql;
		$query = mysqli_query($db_conx, $sql);
		//$image_id = mysqli_insert_id($db_conx);
		if ($query) {
			$return['ajaxresult'] = 'success';
			$return['sqlmessage'] =  "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($db_conx);
			$return['ajaxresult'] = 'error';			
			$return['sqlmessage'] =  "Error: " . $sql . "<br>" . mysqli_error($db_conx);
		}		
		$imageid = mysqli_insert_id($db_conx);
		$return['html'] = 
		'
											<tr id="tr-'.$imageid.'">
												<td>
													<a href="'.$site_uploads_path.$image.'" class="fancybox-button" data-rel="fancybox-button">
													<img class="" src="'.$site_uploads_path.'100x100/'.$image.'" alt="">
													</a>
													<input type="hidden" id="image-'.$imageid.'" name="product[images][id][]" value="'.$imageid.'">
												</td>
												<td>
													<input type="text" class="form-control" name="product[images][label][]" value="">
												</td>

												<td>
													<div class="md-radio has-warning">
														<input type="radio" id="featured-image-'.$imageid.'" name="product[images][featuredimage][]" class="md-radiobtn" value="'.$imageid.'">
														<label for="featured-image-'.$imageid.'">
														<span class="inc"></span>
														<span class="check"></span>
														<span class="box"></span>
														Set Featured Image </label>
													</div>
												</td>
												<td>
													<div class="md-radio has-warning">
														<input type="radio" id="featured-thumb-'.$imageid.'" name="product[images][featuredthumb][]" class="md-radiobtn" value="'.$imageid.'">
														<label for="featured-thumb-'.$imageid.'">
														<span class="inc"></span>
														<span class="check"></span>
														<span class="box"></span>
														Set Featured Thumbnail </label>
													</div>
												</td>
												<td>
													<a href="javascript:;" onclick="removeImage(this)" class="btn default btn-sm">
													<i class="fa fa-times"></i> Remove </a>
												</td>
											</tr>
											
										
		';

		if(isset($_SESSION[get_session_values('image-undo')][$guid])){
			unset($_SESSION[get_session_values('image-undo')][$guid]);  //RESET THE UNDO SESSION VARIABLES
		}
		if(isset($_SESSION[get_session_values('image-undo-count')][$guid])){		
			unset($_SESSION[get_session_values('image-undo-count')][$guid]);	//RESET THE UNDO SESSION COUNT	
		}
		if($guid != ''){		
			if(file_exists($undo_path.$guid)){		
				DeleteDirectoryAndContents($undo_path.$guid);
			}
		}
		$return['path'] = $image;
		$return['imageid'] = $imageid;
		echo json_encode($return);
	   exit();		
		
	}

	else if ($_POST['action'] == 'new-creative') {


		$image = $_POST['imagename'];
		$guid = $_POST['guid'];
		$thumbnail = $image;
		$iman = new ImageManipulator();		
		$iman->smart_resize_image($uploads_path.$image,null, 100, 100, false, $uploads_path.'100x100/'.$image, false);		
		$iman->smart_resize_image($uploads_path.$image,null, 50, 50, false, $uploads_path.'50x50/'.$image, false);		
		$sql = "INSERT INTO images 
		(image,thumbnail,date_created)
		VALUES('$image','$thumbnail','$date_created')";
		$return['sql'] =  $sql;
		$query = mysqli_query($db_conx, $sql);
		//$image_id = mysqli_insert_id($db_conx);
		if ($query) {
			$return['ajaxresult'] = 'success';
			$return['sqlmessage'] =  "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($db_conx);
			$return['ajaxresult'] = 'error';			
			$return['sqlmessage'] =  "Error: " . $sql . "<br>" . mysqli_error($db_conx);
		}		
		$imageid = mysqli_insert_id($db_conx);
		$return['html'] = 
		'
											<tr id="tr-'.$imageid.'">
												<td>
													<a href="'.$site_uploads_path.$image.'" class="fancybox-button" data-rel="fancybox-button">
													<img class="" src="'.$site_uploads_path.'100x100/'.$image.'" alt="">
													</a>
												</td>
												
												<td>
													<a href="javascript:;" onclick="removeImage(\''.$imageid.'\')" class="btn default btn-sm">
													<i class="fa fa-times"></i> Remove </a>
												</td>
											</tr>
											
										
		';

		if(isset($_SESSION[get_session_values('image-undo')][$guid])){
			unset($_SESSION[get_session_values('image-undo')][$guid]);  //RESET THE UNDO SESSION VARIABLES
		}
		if(isset($_SESSION[get_session_values('image-undo-count')][$guid])){		
			unset($_SESSION[get_session_values('image-undo-count')][$guid]);	//RESET THE UNDO SESSION COUNT	
		}
		if($guid != ''){		
			if(file_exists($undo_path.$guid)){		
				DeleteDirectoryAndContents($undo_path.$guid);
			}
		}
		$return['path'] = $image;
		$return['imageid'] = $imageid;
		echo json_encode($return);
	   exit();		
		
	}
	
	
	//////IF IS CALLED FROM NEW PAGE
	else if ($_POST['action'] == 'newImageforPage') {


	$image = $_POST['imagename'];
	$thumbnail = $site_uploads_path."100x100/$image";
	$iman = new ImageManipulator();		
	$iman->smart_resize_image($uploads_path.$image,null, 100, 100, false, $uploads_path.'100x100/'.$image, false);		
	$iman->smart_resize_image($uploads_path.$image,null, 50, 50, false, $uploads_path.'50x50/'.$image, false);		
	$sql = "INSERT INTO images 
	(image,thumbnail,date_created)
	VALUES('$image','$thumbnail','$date_created')";
	$return['sql'] =  $sql;
	//$query = mysqli_query($db_conx, $sql);
	if (mysqli_query($db_conx, $sql)) {

		$return['sqlmessage'] =  "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($db_conx);
		$return['sqlmessage'] =  "Error: " . $sql . "<br>" . mysqli_error($db_conx);
	}		
	$imageid = mysqli_insert_id($db_conx);
	$return['html'] = 
	'
								<li>
									<img src="'.$site_uploads_path.$image.'" alt="" class="img-responsive img100">
									<input type="hidden" name="images[]" vlaue="'.$imageid.'">
									<div class="task-config">
										<div class="task-config-btn">
											<a class="btn btn-xs default btnDeleteRow" href="javascript:;" ><i class="fa fa-trash-o"></i> Delete </a>
											<a class="btn btn-xs default btnImgMinimize" href="javascript:;" ><i class="fa fa-minus"></i> Minimize </a>
											<a class="btn btn-xs default btnImgMaximize" href="javascript:;" ><i class="fa fa-plus"></i> Maximize </a>
										</div>
									</div>											
								</li>										
									
	';
	$return['path'] = $image;
	echo json_encode($return);
   exit();		
	
}

	} 

else{
	$thumbs = '';
	$sql = "SELECT * FROM posts WHERE type='image'";
	$query = mysqli_query($db_conx, $sql);
	
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
		$selected = '';
		if($row['published']) $selected = 'selected';
		$thumbs .= 
		'
								<div class="wid135">							
									<div id="sliderImg'.$row['id'].'" class="tile image '.$selected.' bg-green-turquoise">
										<div class="tile-body">
											<img src="'.$row['image'].'" alt="">
										</div>
										<div class="tile-object">

										</div>
									</div>
									<input type="hidden" id="sliderImagePath'.$row['id'].'" name="sliderImagePath'.$row['id'].'" value="'.$row['image'].'">
									<input type="hidden" class="imageId" id="id" name="id" value="'.$row['id'].'">
									<button type="button" class="btn btn-circle green-jungle" data-toggle="modal" href="#full" onClick="SetCurrentImageFunction(\'sliderImg'.$row['id'].'\',\'sliderImagePath'.$row['id'].'\')" value="U">CHANGE</button>									
								</div>	
										
		';	
	}
}
?>
