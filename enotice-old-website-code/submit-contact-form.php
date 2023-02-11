<?php
include_once 'common_bootstrap.php';
include_once 'config.php';
include_once 'includes/db.php';

if (isset($_POST['action'])) {
	/* print_r($_POST);
	exit(); */

 	{
		//echo '<h2>Thanks for posting comment.</h2>';

		if ($_POST['action'] == 'submit-contact-form') {
			if (isset($_POST['g-recaptcha-response']))
				$captcha = $_POST['g-recaptcha-response'];

			if (!$captcha) {
				//echo '<h2>Please check the the captcha form.</h2>';
				//exit;
			}

			$SECRET_CAPTCHA = '6LcMOtsZAAAAALqjbpNjAcmYIaE9I_i2I47K8s3a';
			$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$SECRET_CAPTCHA&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
			//print_r($response);
			if ($response['success'] == false) {
				//echo '<h2>You are spammer ! Get the @$%K out</h2>';
				exit();
			}
			$return['result'] = false;
			$return['message'] = 'Something wrong with the message.';
		/* 	echo $_POST['en-fname'];
			echo '</br>';
			echo '</br>';
			echo $_POST['en-email'];
			echo '</br>';
			echo '</br>';
			echo $_POST['en-message'];
			echo '</br>';
			echo '</br>'; */
			
			
			
			if (!empty($_POST['en-fname']) && !empty($_POST['en-email']) && !empty($_POST['en-message'])) {
				
				//echo "I am in::::";	

			    $name = $_POST['en-fname'];
				$email = $_POST['en-email'];
				$message = $_POST['en-message'];
				$sql = "INSERT INTO tbl_feedback (user_name,email,feedback,date) VALUES ('$name','$email','$message',NOW())";
				$result_S = mysqli_query($conn,$sql);
				/*

				$query = mysqli_query($db_conx, $sql);
				print_r(mysqli_error($db_conx));
				if ($query) {
					$return['result'] = true;
					$return['message'] = 'Message saved succesfully, we will get back to you shortly';
				} 
				*/

				$dataCurl['email'] = $_POST['en-email'];
				$dataCurl['user_name'] = $_POST['en-fname'];
				$dataCurl['feedback'] = $_POST['en-message'];
				/* 
				$body = 'HI,
				A new enquirey was made on the site.</br>

				Email Address: '.$_POST['en-email']. ';</br>
				Name: '. $_POST['en-fname']. ';</br>
				Message:</br> '. $_POST['en-message']. ';</br>
				';
											 
				$from =$_POST['en-email'];
				$subject = "New Enquire from Website";
				$server=$_SERVER['HTTP_HOST'];
				$headers = "From: User<>\r\nContent-type: text/html; charset=iso-8859-1\r\nMIME-Version: 1.0\r\n";
				$to ='';
				$send_email = mail($to, $subject, $body, $headers); */
				
				
				$to = "support@enoticeninja.com";
				$subject = "New Enquire from Website";
				$txt = "HI,
				A new enquirey was made on the site.</br>

				Email Address: '".$_POST['en-email']."'</br>
				Name: '". $_POST['en-fname']."'</br>
				Message:</br> '". $_POST['en-message']."';</br>";
				$headers = "From: web" . "\r\n";

				 mail($to,$subject,$txt,$headers);
				
				/* print_r($send_email);
				echo $send_email; */
				
				
		//		$return = callApiJson($dataCurl,API_SITE_PATH.'help_feedback.php');
/* 	 echo "response::::";			
print_r($return);
	echo "End response";	
print_r($dataCurl);  */

				
				$emailData['email'] = 'support@enoticeninja.com';
				$emailData['subject'] = 'New Enquire from Website';
				$emailData['content'] = '
				HI,
				A new enquirey was made on the site.</br>

				Email Address: '.$_POST['en-email']. ';</br>
				Name: '. $_POST['en-fname']. ';</br>
				Message:</br> '. $_POST['en-message']. ';</br>

				';
				$emailData['template'] = 'general';
				$isMailSent = sendEmailCommonPhpMailer($emailData);
			} else {
				$return['message'] = 'Please enter all the values.';
			}
			echo json_encode($return);
			exit();
		}
	}
}
