<?php
require_once __DIR__ . '/mail-functions.php';
$mcrypt = new MCrypt();
function sendOtp($mobile_no){
	global $conn;
	$sqlQ = "SELECT * from tbl_user where mobile_no='" . $mobile_no . "'";
	$sqlQuery = mysqli_query($conn, $sqlQ);
	$row = $sqlQuery->fetch_array();
	$count = mysqli_num_rows($sqlQuery);
	if ($count > 0) {
		$verify_otp = $row["verify_otp"];

		if (empty($verify_otp) && $verify_otp == '' or $verify_otp == null && $verify_otp == 'null' or $verify_otp == false && $verify_otp == 'false') {
			//------------------------------- Send OTP MOBILE--------------------------------------------------//
			date_default_timezone_set('Asia/Kolkata');
			$date = date("Y-m-d H:i:s");
			$otp = rand(100000, 999999);
			$apiKey = 'g8EudPMPskU-wRdvR1IFmpz9XIEWK6DGRiSKxISWqW';
			$message = "" . $otp . " is the OTP for eNoticeNinja Application. Please do not share with anyone for security reasons - eNoticeNinja";
			//$numbers = $mobile_no; 
			//$numbers = $mobile_no ; 
			$sender = urlencode('ENOTIC');
			$message = rawurlencode($message);
			$numbers = $mobile_no;
			//$numbers = implode(',', $numbers);
			$data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
			$ch = curl_init('https://api.textlocal.in/send/');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//$response = curl_exec($ch);
			curl_close($ch);
			/* echo $response;
			die(); */
			$is_expired = "0";

			$sql = "INSERT INTO otp(otp,mobile_no,is_expired,create_at) VALUES ('" . $otp . "','" . $mobile_no . "','" . $is_expired . "','" . $date . "')";
			$result = mysqli_query($conn, $sql);

			if ($result) {
				$mobile_status = 'OTP send to register mobile number.';
			} else {
				$mobile_status = 'Error to send OTP!';
			}
		}
		echo json_encode(array('status' => 'true', 'message' => 'OTP send to register mobile number!'));
	} else {
		echo json_encode(array('status' => 'false', 'message' => 'Sorry,This mobile number is not registered with us!'));
	}	
}

function sendEmail($email){
	$blank = '.';
	global $conn;
	global $mcrypt;
	$sqlQ = "SELECT * from tbl_user where user_name='" . $email . "'";

	$sqlQuery = mysqli_query($conn, $sqlQ);
	$row = $sqlQuery->fetch_array();
	$count = mysqli_num_rows($sqlQuery);
	if ($count > 0) {
		$verify_email = $row["verify_email"];
		$firstname = $row["firstname"];
		$lastname = $row["lastname"];
		echo $verify_email;

		if (empty($verify_email) && $verify_email == '' or $verify_email == null && $verify_email == 'null' or $verify_email == false && $verify_email == 'false') {
			date_default_timezone_set('Asia/Kolkata');

			$base_url = SITE_PATH.'/lawyer/verify_email.php';
			$activation = md5($email . round(microtime(true) * 1000));
			$time = round(microtime(true) * 1000);

			$cu_time = date('Y-m-d h:i:s A', time());
			$new_date = $mcrypt->encrypt($cu_time);
			$id = $mcrypt->encrypt($email);
			/* 
						echo  $cu_time;
						die(); */

			$emailData['content'] = '<b>Verify your e-mail to finish signing up for eNoticeNinja</b>' . $blank . '<br/> <br/>  Hi, ' . $firstname . '<br/> <br/> Thank you for choosing eNoticeNinja' . $blank . '<br/> <br/>  Please confirm that ' . $email . ' is your e-mail address by use this link ' . $blank . '<br/> <br/> <a href="' . $base_url . '?key=' . $new_date . '&id=' . $id . '">' . $base_url . 'activation/' . $activation . 'key=' . $new_date . '</a> within 24 hours.';
			//$from = 'Lawyerapp@gmail.com';
			$emailData['subject'] = "Confirm your email address " . $email . "";
			$emailData['email'] = $row["user_name"];
			$emailData['name'] = $firstname.' '. $lastname;
			$emailData['url'] = $base_url . '?key=' . $new_date . '&id=' . $id;
			$emailData['template'] = 'verification';
			$isMailSent = sendEmailCommonPhpMailer($emailData);
			//$server = $_SERVER['HTTP_HOST'];
			//$headers = "From: eNoticeNinja<" . $from . ">\r\nContent-type: text/html; charset=iso-8859-1\r\nMIME-Version: 1.0\r\n";
			$to = $email;
			//$send_email = mail($to, $subject, $body, $headers);
			$email_send = "Thank You Kindly check your email to verify your email id";
			echo json_encode(array('status' => $isMailSent, 'message' => $email_send));
		}
	} else {
		echo json_encode(array('status' => 'false', 'message' => 'Sorry,This email id is not registered with us!'));
	}

}

function verifyOtp($mobile_no, $otp){
	global $conn;
	if (!empty($mobile_no) && $mobile_no !== '') {
		if (!empty($otp) && $otp !== '') {

			$sqlQ = "SELECT * from tbl_user where mobile_no='" . $mobile_no . "'";
			$sqlQuery = mysqli_query($conn, $sqlQ);
			$row = $sqlQuery->fetch_array();
			$count = mysqli_num_rows($sqlQuery);
			if ($count > 0) {
				$sqlQ = "SELECT * from otp where otp='" . $otp . "' and mobile_no='" . $mobile_no . "'";
				$sqlQuery = mysqli_query($conn, $sqlQ);
				$row = $sqlQuery->fetch_array();
				$count = mysqli_num_rows($sqlQuery);
				if ($count > 0) {
					$sqlQ = "SELECT * from otp where mobile_no='" . $mobile_no . "'";
					$sqlQuery = mysqli_query($conn, $sqlQ);
					$row = $sqlQuery->fetch_array();
					$count = mysqli_num_rows($sqlQuery);
					if ($count > 0) {
						date_default_timezone_set('Asia/Kolkata');
						$date = date("Y-m-d H:i:s");
						$sql = "SELECT * FROM otp WHERE otp='" . $otp . "' AND mobile_no='" . $mobile_no . "' AND is_expired=0 AND  create_at > DATE_SUB(NOW(), INTERVAL 2 MINUTE)";
						
						$result = mysqli_query($conn, $sql);
						$count = mysqli_num_rows($result);
						if ($count > 0) {
							$rowOtp = mysqli_fetch_array($result,MYSQLI_ASSOC);
							$otp_id = $rowOtp['id'];
							////// DElete Otp 
							$sqlDelOtp = "DELETE FROM otp WHERE id='$otp_id'";
							$queryDelOtp = mysqli_query($conn, $sqlDelOtp);

							////// Update Verify Status to true
							$queryLogin = "UPDATE tbl_user set verify_otp='true' WHERE mobile_no='" . $mobile_no . "'";
							$sqlQueryL = mysqli_query($conn, $queryLogin);

							$data = array();
							$sqlN = "SELECT * from tbl_user where mobile_no='" . $mobile_no . "'";
							$sqlNQuery = mysqli_query($conn, $sqlN);
							while ($row = mysqli_fetch_assoc($sqlNQuery)) {
								$data["user_id"] = $row["user_id"];
								$data["firstname"] = $row["firstname"];
								$data["lastname"] = $row["lastname"];
								$data["mobile_no"] = $row["mobile_no"];
								$data["email"] = $row["user_name"];
								$data["city"] = $row["city"];
							}
							$finaldata['status'] = 'true';
							$finaldata['message'] = 'OTP Varified kindly login..';
							$finaldata['response'] = $data;

							echo json_encode($finaldata);
							//echo json_encode(array('status'=>'true','message'=>'OTP Varified kindly login..'));

						} else {
							echo json_encode(array('status' => 'false', 'message' => 'Sorry,OTP is expired!'));
						}

						//$sqlI ="DELETE FROM otp where otp='$otp' and mobile_no='$mobile_no'";
						//$result = mysqli_query($conn,$sqlI);
					}
				} else {
					echo json_encode(array('status' => 'false', 'message' => 'Invalid OTP!'));
				}
			} else {
				echo json_encode(array('status' => 'false', 'message' => 'Sorry,This mobile number is not registered with us!'));
			}
		} else {
			echo json_encode(array('status' => 'false', 'message' => 'OTP should not be blank!'));
		}
	} else {
		echo json_encode(array('status' => 'false', 'message' => 'Mobile Number should not be blank!'));
	}	
}
?>