<?php
$uploads_path = DIR_IMAGE_UPLOADS;
$undo_path = DIR_IMAGE_UNDO;
$site_uploads_path = $site_path.'uploads/';
$date_created = date("Y-m-d");
$return['ajaxresult'] = true;
if(!is_dir(DIR_IMAGE_UPLOADS.'undo')){
  mkdir(DIR_IMAGE_UPLOADS.'undo');
}

if(isset($_POST['action'])){

    if ($_POST['action'] == 'upload-preview'){
		$case_id = $_POST['case_id'];
		$bkg_id = $_POST['background_id'];
		define('UPLOAD_DIR', DIR_ROOT.$_POST['upload_path'].$case_id.'-'.$bkg_id.'/');
		if(!is_dir(UPLOAD_DIR)){
		  mkdir(UPLOAD_DIR);
		}
		//sleep(3);
		$img = $_POST['file'];
 		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = UPLOAD_DIR.$case_id.'-'.$bkg_id.'.png';
		$success = file_put_contents($file, $data);
		//print $success ? $file : 'Unable to save the file.';
		$return['ajaxresult'] = true;
		//$return['post'] = $_POST;
        echo json_encode($return);
        exit();
    }

    if ($_POST['action'] == 'upload-from-canvas'){
		define('UPLOAD_DIR', DIR_ROOT.$_POST['upload_path']);
		if(!is_dir(UPLOAD_DIR)){
		  mkdir(UPLOAD_DIR);
		}
		//sleep(3);
		$img = $_POST['file'];
 		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = UPLOAD_DIR . uniqid() . '.png'; 
		$success = file_put_contents($file, $data);
		//print $success ? $file : 'Unable to save the file.';
		$return['ajaxresult'] = true;
		//$return['post'] = $_POST;
        echo json_encode($return);
        exit();
    }

    if ($_POST['action'] == 'upload'){
        $uploads_path = $_POST['upload_path'];
        $path = $_POST['image_path'];
		$uploads_path = DIR_ROOT.$uploads_path;
        $pic_name = ''.$_FILES['file']['name'].'';
        $img_type_only = file_extension($pic_name);
        $unique_name = time().uniqid(rand());
        move_uploaded_file($_FILES['file']['tmp_name'], $uploads_path."$unique_name.$img_type_only");	
        $timestamp = time();
        mkdir($undo_path."/$timestamp",0777,true);
        $_SESSION[get_session_values('image-undo')] = array();  //RESET THE UNDO SESSION VARIABLES
        $_SESSION[get_session_values('image-undo')][$timestamp] = array();  //RESET THE UNDO SESSION VARIABLES
        $_SESSION[get_session_values('image-undo-count')] = array();	//RESET THE UNDO SESSION COUNT		
        ///POPULATE THE IMAGE UNDO SESSION VARIABLE WITH NEW IMAGE	
        $_SESSION[get_session_values('image-undo-count')][$timestamp] = 0; //START UNDO SESSION COUNT FROM 1


        $return['imagename'] = "$unique_name.$img_type_only";
        $return['imagenamess'] = $pic_name;
        $return['123'] = $_SESSION;
        $return['guid'] = $timestamp;
        $return['files'] = $_FILES['file'];
        echo json_encode($return);
        exit();
    }

    if ($_POST['action'] == 'upload_image_thumb'){
/* 		print_r($_POST);
		exit(); */
        $uploads_path = $_POST['upload_path'];
        $path = $_POST['image_path'];
		$uploads_path = DIR_ROOT.$uploads_path;
        $pic_name = ''.$_FILES['file']['name'].'';
        $img_type_only = file_extension($pic_name);
        $unique_name = time().uniqid(rand());
        move_uploaded_file($_FILES['file']['tmp_name'], $uploads_path."$unique_name.$img_type_only");	
        $timestamp = time();
        mkdir($undo_path."/$timestamp",0777,true);
        $_SESSION[get_session_values('image-undo')] = array();  //RESET THE UNDO SESSION VARIABLES
        $_SESSION[get_session_values('image-undo')][$timestamp] = array();  //RESET THE UNDO SESSION VARIABLES
        $_SESSION[get_session_values('image-undo-count')] = array();	//RESET THE UNDO SESSION COUNT		
        ///POPULATE THE IMAGE UNDO SESSION VARIABLE WITH NEW IMAGE	
        $_SESSION[get_session_values('image-undo-count')][$timestamp] = 0; //START UNDO SESSION COUNT FROM 1

/* 		$iman = new ImageManagerCore();
        ImageManagerCore::$dimensions = array();
        ImageManagerCore::$dimensions['100X100-'] = array('100','100');
        ImageManagerCore::$dimensions['200X200-'] = array('200','200');
		//print_r(ImageManagerCore::$dimensions);
		ImageManagerCore::resize($uploads_path.$unique_name.'.'.$img_type_only, $uploads_path, 50, 50, 'png',false,$unique_name.'.'.$img_type_only); */
        $return['imagename'] = "$unique_name.$img_type_only";
        $return['imagenamess'] = $pic_name;
        $return['123'] = $_SESSION;
        $return['guid'] = $timestamp;
        $return['files'] = $_FILES['file'];
        echo json_encode($return);
        exit();
    }

    if ($_POST['action'] == 'upload_single_and_resize'){
/* 		print_r($_POST);
		exit(); */
        $uploads_path = $_POST['upload_path'];
        $path = $_POST['image_path'];
		$uploads_path = DIR_ROOT.$uploads_path;
        $pic_name = ''.$_FILES['file']['name'].'';
        $img_type_only = file_extension($pic_name);
        $unique_name = time().uniqid(rand());
        move_uploaded_file($_FILES['file']['tmp_name'], $uploads_path."$unique_name.$img_type_only");	
        $timestamp = time();
        mkdir($undo_path."/$timestamp",0777,true);
        $_SESSION[get_session_values('image-undo')] = array();  //RESET THE UNDO SESSION VARIABLES
        $_SESSION[get_session_values('image-undo')][$timestamp] = array();  //RESET THE UNDO SESSION VARIABLES
        $_SESSION[get_session_values('image-undo-count')] = array();	//RESET THE UNDO SESSION COUNT		
        ///POPULATE THE IMAGE UNDO SESSION VARIABLE WITH NEW IMAGE	
        $_SESSION[get_session_values('image-undo-count')][$timestamp] = 0; //START UNDO SESSION COUNT FROM 1
		
/* 		$iman = new ImageManagerCore();
        ImageManagerCore::$dimensions = array();
        ImageManagerCore::$dimensions['100X100-'] = array('100','100');
        ImageManagerCore::$dimensions['200X200-'] = array('200','200');
		//print_r(ImageManagerCore::$dimensions);
		ImageManagerCore::resize($uploads_path.$unique_name.'.'.$img_type_only, $uploads_path, 50, 50, 'png',false,$unique_name.'.'.$img_type_only); */
        $return['imagename'] = "$unique_name.$img_type_only";
        $return['imagenamess'] = $pic_name;
        $return['123'] = $_SESSION;
        $return['guid'] = $timestamp;
        $return['files'] = $_FILES['file'];
        echo json_encode($return);
        exit();
    }

    if ($_POST['action'] == 'edit-existing'){
            $image_name = $_POST['image'];
            $timestamp = time();
			$img_name_only = file_name($image_name);
			$img_name_only_backslash = str_replace('/', '\\',$img_name_only);
            $img_type_only = file_extension($image_name);
			$base_name = basename($image_name);
			$base_name_only = file_name($base_name);
			$base_ext_only = file_extension($base_name);
			//print_r($base_name);
            mkdir($undo_path.DIRECTORY_SEPARATOR."$timestamp",0777,true);
			$back_slash_img = str_replace('/', '\\',$uploads_path."$image_name");
            copy($back_slash_img, $undo_path."$timestamp".DIRECTORY_SEPARATOR."$base_name_only-$timestamp.$img_type_only");
            $_SESSION[get_session_values('image-undo')] = array();  //RESET THE UNDO SESSION VARIABLES
            $_SESSION[get_session_values('image-undo')][$timestamp] = array();  //RESET THE UNDO SESSION VARIABLES
            $_SESSION[get_session_values('image-undo-count')] = array();	//RESET THE UNDO SESSION COUNT		
            ///POPULATE THE IMAGE UNDO SESSION VARIABLE WITH NEW IMAGE	
            $_SESSION[get_session_values('image-undo')][$timestamp][] = "$base_name_only-$timestamp.$img_type_only"; 
            $_SESSION[get_session_values('image-undo-count')][$timestamp] = 0; //START UNDO SESSION COUNT FROM 1

			//print_r($back_slash_img);
            $return['imagename'] = $image_name;
            $return['123'] = $_SESSION;
            $return['uploads_img'] = $back_slash_img;
            $return['guid'] = $timestamp;
            echo json_encode($return);
            exit();
    }
    
    if ($_POST['action'] == 'crop'){
            $iman = new ImageManipulator();
            $image = $_POST['image'];
            $path = $_POST['upload_path'];
            $guid = $_POST['guid'];
            $x = intval($_POST['x']);
            $y = intval($_POST['y']);
            $dst_w = intval($_POST['w']);
            $dst_h = intval($_POST['h']);
            $temp = '';
			$uploads_path = DIR_ROOT;
            $timestamp = time();
            $img_name_only = $iman->file_name($image);
            $img_type_only = $iman->file_extension($image);	   
			$base_name = basename($image);
			$base_name_only = file_name($base_name);
			$base_ext_only = file_extension($base_name);			
            copy($uploads_path."$path/$image", $undo_path."/$guid/$base_name_only-$timestamp.$img_type_only");
            $_SESSION[get_session_values('image-undo')][$guid][] = "$base_name_only-$timestamp.$img_type_only";
            $_SESSION[get_session_values('image-undo-count')][$guid]++;
            
            $iman->image_resize("$uploads_path$path/$image", "$uploads_path$path/$image", intval($x),intval($y), $dst_w,$dst_h, intval($dst_w),intval($dst_h), 2);
            list($widthNew, $heightNew) = getimagesize($uploads_path.$path.'/'.$image);
            


            
            $return['width'] = $widthNew;
            $return['height'] = $heightNew;
            $return['imagename'] = "$image";
            $return['result'] = 'success';
            echo json_encode($return);
           exit();
    }

     if ($_POST['action'] == 'resize'){
            $iman = new ImageManipulator();
            $image = $_POST['image'];
            $guid = $_POST['guid'];
            $path = $_POST['image_path'];
            //print_r($_POST);
            $timestamp = time();
            $img_name_only = $iman->file_name($image);
            $img_type_only = $iman->file_extension($image);		

			$base_name = basename($image);
			$base_name_only = file_name($base_name);
			$base_ext_only = file_extension($base_name);
            copy($uploads_path."$path/$image", $undo_path."/$guid/$base_name_only-$timestamp.$img_type_only");
            $_SESSION[get_session_values('image-undo')][$guid][] = "$base_name_only-$timestamp.$img_type_only";
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

            $imgresampled = $iman->smart_resize_image($uploads_path.$path.'/'.$image,null, $width, $height, $proportional, $uploads_path.$path.'/'.$image, false);
            list($widthNew, $heightNew) = getimagesize($uploads_path.$path.'/'.$image);
            $return['width'] = $widthNew;
            $return['height'] = $heightNew;
            $return['result'] = 'success';
            
        
            echo json_encode($return);
            exit();
    }

     if ($_POST['action'] == 'undo'){

            $iman = new ImageManipulator();
            $image = $_POST['image'];
            $guid = $_POST['guid'];
            $path = $_POST['image_path'];

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
            rename($undo_path."$guid/$previous_image", $uploads_path."$image");
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

     if ($_POST['action'] == 'cancel'){
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

     if($_POST['action'] == 'reset-all') {
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
            $return['data'] = $_POST;
            $return['ajaxresult'] = 'success';
            echo json_encode($return);
           exit();	
           
           
    exit();		

    }


}

else if(isset($_POST['fileToDelete'])) 
{

///TODO: implement deleting from database, if its implemented in the above upload code
	$return['post'] = $_POST;
	if (file_exists($_POST['fileToDelete'])) { unlink ($_POST['fileToDelete']); }
	echo json_encode($return);
	exit();
}

else{
    $return['ajaxresult'] = true;
    $return['post'] = $_POST;
    $return['files'] = $_FILES;
    $return['ajaxmessage'] = 'Something went wrong';
    echo json_encode($return);
    exit();
}
?>
