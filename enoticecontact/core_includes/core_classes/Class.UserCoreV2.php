<?php
/**
 * Flexible Access - The main class
 * 
 * @param string $dbName
 * @param string $dbHost 
 * @param string $dbUser
 * @param string $dbPass
 * @param string $dbTable
 */
require_once DIR_CORE_INCLUDES.'mailer/PHPMailerAutoload.php';
class UserCore extends SingleDBCoreV2
{
    /*Do not edit after this line*/
    public $dbTable = 'backend_users';
    public $common_title = 'Users';
    public $tbFields = array(
            'userID'=> 'id', 
            'login' => array('username','email','phone'),
            'pass'  => 'password',
            'email' => 'email',
            'user_level' => 'userlevel',
            'active'=> 'activated'
            );
    public $userData = array();
    public $user_ok = false; 
    public $userID = '';
    public $remCookieDomain = '';
    public $remCookieName = '';    
    public $session = [];
    public $config = array(
          'id'=>'id',
          'name'=>'firstname'		
      );
      
    public $password_type = 'password_hash';
    public $has_user_levels = true;
	public $selectList = array();

	///// FORM FIELDS
	public $common_form_fields = array(
		'fields' => array('firstname','lastname','email','phone','username','password','password2','userlevel','status'),
		'required' => array('firstname','lastname','email','phone','username','password','password2','userlevel'),
		'validation' => array(
							'email'=>array('type'=>'email'),
							'username'=>array('type'=>'alphanumeric'),
							'password'=>array('type'=>'all','min_len'=>6,'message'=>'Password should be atleast 6 characters'),
							'password2'=>array('type'=>'all','min_len'=>6,'message'=>'Password should be atleast 6 characters'),
						),
		'duplicate' => array('username','email','phone'),
	);

	public function __construct($dbConn = '') {
		if($dbConn != ''){
			$this->dbConn = $dbConn;
		}
		$this->session = get_session_variables();  
		$this->sensitiveFields = array('id','password');
		$this->selectList = $this->getSelectOptions("SELECT users.id,CONCAT(users.firstname,' ',users.lastname,'-',users.email) as name FROM $this->dbTable  as users WHERE users.status='active'");
		
	}

	public function attempCookieLogin(){
		$this->remCookieDomain = get_cookie_domain();
		$this->remCookieName = get_cookie_name();
		if(!isset( $_SESSION )) session_start();
		if (!empty($_SESSION[$this->session['id']]))
			 $this->user_ok = $this->login( $_SESSION[$this->session['username']], $_SESSION[$this->session['password']] );
		//Maybe there is a cookie?
		if ( isset($_COOKIE[$this->remCookieName]) && !$this->is_loaded()) {
		  //echo 'I know you<br />';
		   $u = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
		   $this->user_ok = $this->login($u['uname'], $u['password']);
		}
		
		return $this->user_ok;        
	}
	
    /**
    * Login function
    * @param string $uname
    * @param string $password
    * @param bool $loadUser
    * @return bool
    */
    public function login($uname, $password, $remember = true, $loadUser = true) {
        $uname    = $this->escape($uname);
        $password = $originalPassword = $this->escape($password);
		$sql = "SELECT * FROM `{$this->dbTable}` WHERE ";
		$tempArr = array();
		$is_ok = false;
		foreach($this->tbFields['login'] as $login_field){
			$is_ok = true;
			$tempArr[] = "`$login_field` = '$uname'";
		}
		if($is_ok){
			$sql .= implode('OR',$tempArr);
			$sql .= " LIMIT 1";
			$res = $this->query($sql,__LINE__);
			if(@mysqli_num_rows($res) == 0){
				return false;
			}
			else{
				$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
				$hash = $row[$this->tbFields['pass']];
				$is_verified = false;
				

				if($this->password_type == 'base64_encode'){
					
				   $is_verified = (base64_decode($password) == $hash);
				}
				else if($this->password_type == 'password_hash'){
					$is_verified = password_verify($password, $hash);
				} 
				
				if($is_verified) {
					if( $loadUser ) {
						$this->userData = $row;
						$this->userData['name_to_display'] = $row['name'];
						$this->userData['display_name'] = $row['name'];
						$this->userID = $this->userData[$this->tbFields['userID']];
						$_SESSION[$this->session['id']] = $this->userID;
						$_SESSION[$this->session['username']] = $this->userData[$this->tbFields['login'][0]];
						$_SESSION[$this->session['password']] = $password;
						if($this->has_user_levels)$_SESSION[$this->session['user_level']] = $this->userData[$this->tbFields['user_level']];
						if ( $remember ){
						  $cookie = base64_encode(serialize(array('uname'=>$uname,'password'=>$originalPassword)));
						  $a = setcookie($this->remCookieName,$cookie,strtotime( '+30 days' ), "/$this->remCookieDomain", "", "", TRUE);
						}
					}
					
	/* 				$data['username'] = $uname;
					$data['password'] = $hash;
					$this->sendActivationEmail($data);	 */			
				}
				else {
					return false;
				}			

			}
			
		}
		else{
			return false;
		}
 
        return true;
    }

    public function activateUser($uname, $password) {
        $uname    = $this->escape($uname);
        $password = $originalPassword = $this->escape($password);
		$sql = "SELECT * FROM `{$this->dbTable}` WHERE ";
		$tempArr = array();
		$is_ok = false;
		foreach($this->tbFields['login'] as $login_field){
			$is_ok = true;
			$tempArr[] = "`$login_field` = '$uname'";
		}		
		if($is_ok){
			$sql .= implode('OR',$tempArr);
			$sql .= " LIMIT 1";
			$res = $this->query($sql,__LINE__);
 
			if ( @mysqli_num_rows($res) == 0){
				return false;
			}
			else{
				$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
				$hash = $row[$this->tbFields['pass']];	
				$is_verified = true;
				print_r(strtotime('now') - strtotime($row['date_created']));
				if(strtotime('now') - strtotime($row['date_created']) > 300){
					$is_verified = false;
				}
	/*             if($this->password_type == 'base64_encode'){
				   $is_verified = (base64_decode($password) == $hash); 
				}
				else if($this->password_type == 'password_hash'){
					$is_verified = password_verify($password, $hash);
				}  */
				
				if($is_verified) {
					$sqlAct = "UPDATE `{$this->dbTable}` SET activated='1' WHERE ";
					$sqlAct .= implode ('OR',$tempArr);
					$res = $this->query($sqlAct,__LINE__);

				}
				else {
					return false;
				}			

			}
			
		}
		else{
			return false;
		}
		return true;
    }

  
    /**
    * Logout function
    * param string $redirectTo
    * @return bool
    */
    public function logout($redirectTo = '') {

        unset($_COOKIE[$this->remCookieName]);
        setcookie($this->remCookieName, "", time() - 3600, "/$this->remCookieDomain", "", "", TRUE);
        unset($_SESSION[$this->session['username']]);
        unset($_SESSION[$this->session['password']]);
        unset($_SESSION[$this->session['id']]);
        $_SESSION = [];
        $this->userData = '';
        if ( $redirectTo != '' && !headers_sent()){
            $u = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
           header('Location: '.$redirectTo );
           exit();//To ensure security
        }
    }

    
    /**
    * Get a property of a user. You should give here the name of the field that you seek from the user table
    * @param string $property
    * @return string
    */
    public function get_property($property) {
        if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
        if (!isset($this->userData[$property])) $this->error('Unknown property <b>'.$property.'</b>', __LINE__);
        return $this->userData[$property];
    }    
    /**
    * Is the user loaded?
    * @ return bool
    */
    public function is_loaded() {
        return empty($this->userID) ? false : true;
    }    
	
	
    function getFormConfiguration($form_type){
        $this->form_configuration['new'] = 'getNewForm';
        $this->form_configuration['new_ajax'] = 'getNewAjaxForm';
        $this->form_configuration['edit'] = 'getEditForm';
        $this->form_configuration['edit_password_single'] = 'getPasswordResetSingleForm';
        $this->form_configuration['edit_password_multiple'] = 'getPasswordResetMultipleForm';
		$this->form_configuration['login_form'] = 'getLoginForm';
		$this->form_configuration['get-otp-form'] = 'getOtpForm';
        
        return $this->form_configuration[$form_type];
    }
    
    function setFormConfiguration($data){  
        $func_to_call = $this->getFormConfiguration($data['form_type']);
        if($func_to_call){
            $return = call_user_func_array(array($this, $func_to_call), array($data));
            return $return;
        }
        else{
            return false;
        }        
        
    } 
    
    function getNewForm($data){
        $unique_id = uniqid();
        $unique_id .= rand(10,100);  
        $jsFunction = "matchPasswords('__UNIQUE_ID__-password','__UNIQUE_ID__-password2');";
            /////Form Configuration //////////////      
        $return = $this->getFormConfigurationAll('new');
        $return['unique_id'] = $unique_id;
        $return['override_function'] = array(
                                        'enabled'=>false,
                                        'function'=>''		
                                    );
        $return['fetch_data'] = array('enabled'=>false);                                    
        $return['portlet_title'] = 'Add New';
        $return['portlet_icon'] = '';
        $return['submit_id'] = 'submit_id';
        $return['call_type'] = isset($data['call_type']) ? $data['call_type'] : 'ajax';
        $return['return_form_as'] = isset($data['return_form_as']) ? $data['return_form_as'] : 'rows';
        $return['parent'] = $parent = $unique_id;
        $return['module'] = $module = $this->dbTable;
        $return['return_after_save'] = array();
        $return['hidden'] = array(
                            'enabled'=>false,
                            'html'=>''
                                );
        $return['on_submit'] = 'insert';
        $return['default_insert'] = array(
                                    'enabled'=>false,
                                    'date_created'=>date('Y-m-d H-i-s'),
                                    'created_by'=>isset($_SESSION[get_session_values('id')])?$_SESSION[get_session_values('id')]:0
                                    );
        $return['on_submit_error'] = '';
        $return['name_prefix'] = 'data';
        $return['id_prefix'] = true;
        $return['fields'] = $this->common_form_fields['fields'];
        $return['required'] = $this->common_form_fields['required'];
        $return['validation'] = $this->common_form_fields['validation'];
        $return['dependency'] = $this->dependency;
        $return['duplicate'] =  $this->common_form_fields['duplicate'];
        $return['popover_error'] = true;
        $return['non_db_fileds'] =  array();
        $return['jsFunction'] =  $jsFunction;
        $return['jsFunctionLast'] = "";
        $return['jsModalClose'] =  "";
        return $return;
    
    }
    
    function getEditForm($data){
        $tempData = $data;
        $tempData['form_type'] = 'new';
        $return = $this->getNewForm($tempData);
        $return['fetch_data'] = array('enabled'=>true);
        $return['on_submit'] = 'update';
        $return['portlet_title'] = 'Edit';
        $return['fields'][] = 'id';
		
			$fields = array_flip($return['fields']);
			unset($fields['password']);
			unset($fields['password2']);
			$return['fields'] = array_flip($fields);
			
			$fields = array_flip($return['required']);
			unset($fields['password']);
			unset($fields['password2']);
			$return['required'] = array_flip($fields);	

			unset($return['validation']['password']);
			unset($return['validation']['password2']);

        $return['override_fields'] = array(
                                    'id'  => array(
                                            'type'=>'hidden',
                                            'value'=>getDefault($data['id'],'')
                                            )
									);
        return $return;
    }
    
    function getNewAjaxForm($data){
        $tempData = $data;
        $tempData['form_type'] = 'new';
        $return = $this->getNewForm($tempData);          
        $return['return_form_as'] = 'modal';  
        return $return;        
    }
    
	function confirmOtp($data){
		$unique_id = $data['unique_id'];
		$id = get_user_id();
		$otp = $data['data']['otp'];
		$return['ajaxresult'] = true;
		if(isset($_SESSION['otp']) && $otp == $_SESSION['otp']){
			unset($_SESSION['otp']);
			unset($_SESSION['otp_time']);
			$sql = "UPDATE `$this->dbTable` SET otp_verified='true' WHERE id='$id'";
			$query = mysqli_query($this->dbConn,$sql);
			$return['verified'] = true;
			$return['jsFunction'] = 
			"
				CloseBsModal('$unique_id-main-container');
				$('#$unique_id-main-container').on('hidden.bs.modal', function () {
					$('#$unique_id-main-container').remove();
				});
				showSweetSuccess('Verified ! Congrats','Your mobile number has been verified. Happy Shopping');
			";
			
			if(isset($data['next_action']) && $data['next_action'] == 'reload'){
				$return['jsFunction'] = 
				"
					CloseBsModal('$unique_id-main-container');
					$('#$unique_id-main-container').on('hidden.bs.modal', function () {
						$('#$unique_id-main-container').remove();
					});
					showSweetSuccess('Verified ! Congrats','Your mobile number has been verified. Happy Shopping');
					location.reload();
				";				
			}
		}
		else{
			$return['verified'] = false;
			$return['jsFunction'] =
			"
				showErrorOnElement('$unique_id-otp','OTP Doesnt Match Please check the OTP and try again');
			";			
		}
		
		echo json_encode($return);
		exit();
	}
	
	function resendOtp(){
		$id = get_user_id();
		$sql = "SELECT mobile FROM `$this->dbTable` WHERE id='$id' LIMIT 1";
		$query = mysqli_query($this->dbConn,$sql);
		$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
		$otp = mt_rand(100000, 999999);
		$msgData['mobile'] = $row['mobile'];
		$msgData['message'] = ' Thank you for registering with '.COMPANY_NAME.'. Your OTP is '.$otp.'. ';
		sendSMS($msgData);
		$_SESSION['otp'] = $otp;
		$_SESSION['otp_time'] = date('Y-m-d H:i:s');
		$return['ajaxresult'] = true;
		$return['jsFunction'] = "showMessage('success','An OTP has been sent to your registered mobile number', 'Success');";
		echo json_encode($return);
		exit();
		
	}
	
    function getOtpForm($data){
		if(isset($_SESSION['otp']) && isset($_SESSION['otp_time']) && (strtotime('now') - strtotime($_SESSION['otp_time']) < 86400)){

		}
		else{
			$id = get_user_id();
			$sql = "SELECT mobile FROM `$this->dbTable` WHERE id='$id' LIMIT 1";
			$query = mysqli_query($this->dbConn,$sql);
			$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
			$otp = mt_rand(100000, 999999);
			$msgData['mobile'] = $row['mobile'];
			$msgData['message'] = ' Thank you for registering with '.COMPANY_NAME.'. Your OTP is '.$otp.'. ';
			sendSMS($msgData);
			$_SESSION['otp'] = $otp;
			$_SESSION['otp_time'] = date('Y-m-d H:i:s');			
		}
		
        $tempData = $data;
        $tempData['form_type'] = 'new';
        $return = $this->getNewForm($tempData);
        $parent = $return['parent'];
        $return['fetch_data'] = array('enabled'=>false);
        $return['portlet_title'] = 'Confirm OTP';
        $return['buttons'] = array(
                    'enabled'=>true,
                    'html'=>
                    '
                    <button type="button" id="__UNIQUE_ID__-btnResend" class="btn btn-outline yellow" onClick="CommonFunc2('.JSONify('module:__MODULE__,action:resend-otp,next_action:reload').',\'\',this)">Resend OTP</button>
                    <button type="button" id="__UNIQUE_ID__-btnSave" class="btn btn-outline green" onClick="CommonFunc2('.JSONify('action:confirm-otp,next_action:reload').',\'__FORM_ID__\',this)" data-unique_id="__UNIQUE_ID__">Confirm OTP</button>
                    '
                    );
        $return['on_submit'] = 'update';
        $return['modal_width'] = '400px';
        $return['has_custom_fields'] = true;
        $return['fields'] = array('otp');
        $return['validation'] = array(
                        'otp'=>array('type'=>'number','min_len'=>6,'max_len'=>6,'message'=>'')
        );  
        $return['required'] = array('otp');
        $return['custom_fields'] = array(
                            'otp'=> array(
                                    'type'=>'input',
                                    'width'=>'8',
                                    'max_length'=>'6',
                                    'left_offset'=>'2',
                                    'label'=>'Confirm OTP',
                                    'code_begin'=>'
									<h3 class="font-red text-center">Please verify your Moblie Number </h3>
									<h4 class="font-green text-center">An OTP has been sent to your mobile number </h4>
									',
                                    )
                            );
        $return['jsFunction'] = "";
        $return['jsModalClose'] = "location.reload();";

        return $return;        
    }
    
    function getPasswordResetSingleForm($data){
        $tempData = $data;
        $tempData['form_type'] = 'new';
        $return = $this->getNewForm($tempData);           
        $parent = $return['parent'];         
        $return['fetch_data'] = array('enabled'=>false);
        $return['portlet_title'] = 'Reset Password';  
        $return['buttons'] = array(
                    'enabled'=>true,
                    'html'=>
                    '
                    <button type="button" id="__UNIQUE_ID__-btnClose" class="btn btn-outline red" onClick="CloseBsModal(\'__UNIQUE_ID__-main-container\',this)">Cancel</button>
                    <button type="button" id="__UNIQUE_ID__-btnSave" class="btn btn-outline green" onClick="Save('.JSONify('action:reset-password').',\'__FORM_ID__\',\'__UNIQUE_ID__\')">Update Password</button>                                                            
                    '
                    );
        $return['on_submit'] = 'update';        
        $return['modal_width'] = '400px';        
        $return['fields'] = array('id','password','password2');
        $return['validation'] = array(
                        'password'=>array('type'=>'alphanumericspaceundash','min_len'=>6,'message'=>'Password should be atleast 6 characters'),
                        'password2'=>array('type'=>'alphanumericspaceundash','min_len'=>6,'message'=>'Password should be atleast 6 characters'),
        );          
        $return['required'] = array('id','password','password2');
        $return['override_fields'] = array(
                            'id'  => array(
                                    'type'=>'hidden',		
                                    'value'=>getDefault($data['id'],'')		
                                    ),
                            'password'  => array(
                                    'width'=>'12',		
                                    ),
                            'password2'  => array(
                                    'width'=>'12',		
                                    ),
                            );
        $return['jsFunction'] = "matchPasswords('__UNIQUE_ID__-password','__UNIQUE_ID__-password2');";  

        return $return;        
    }
    
    function getPasswordResetMultipleForm($data){
        $tempData = $data;
        $tempData['form_type'] = 'edit_password_single';      
        $return = $this->getPasswordResetSingleForm($tempData);           
        $parent = $return['parent'];     
        $return['fields'] = array('id','password','password2');
        $return['validation'] = array(
                'password'=>array('type'=>'alphanumericspaceundash','min_len'=>6,'message'=>'Password should be atleast 6 characters'),
                'password2'=>array('type'=>'alphanumericspaceundash','min_len'=>6,'message'=>'Password should be atleast 6 characters'),
        );         
        $return['required'] = array('id','password','password2');
        $return['override_fields'] = array(
                'id'=> array(
                        'width'=>'12',		
                        'type'=>'select',
                        'select2'=>true,
                        'options'=>$this->selectList,
                        ),
                'password'  => array(
                        'width'=>'12',		
                        ),
                'password2'  => array(
                        'width'=>'12',		
                        ),
                );  
        return $return;        
    }

    
    public function insertEx($data, $db_conx='', $isManual = false, $form_config = array()){
        if($db_conx != ''){
            $this->dbConn = $db_conx;
        }
        $data = $data['data'];
        $getFields['default-insert'] = $this->getDefaultForInsert();        
        $pass1 = $data['password'];
        $pass2 = $data['password2'];
        $return = array();
        if (!is_array($data)) $this->error('Data is not an array', __LINE__);	
        //$firstname = addslashes(preg_replace('#[^a-z0-9]#i', '', $data['firstname']));
        //$lastname = addslashes(preg_replace('#[^a-z0-9]#i', '', $data['lastname']));
        $email = mysqli_real_escape_string($this->dbConn, $data['email']);

/*         if($firstname == "" || $lastname == "" || $email == "" ){
            $return['error'] = "The form submission is missing values.";
            exit();
        } */
        if($email == "" ){
            $return['error'] = "The form submission is missing values.";
            exit();
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $return['error'] = "Please Enter a valid Email address";
            exit();		
        }
        else if($pass1 != $pass2){
            $return['error'] = "Passwords Dont Match";
            exit();			
        }

        unset($data['password2']);
        if($this->password_type == 'base64_encode'){
           $p_hash = base64_encode($data['password']);
        }
        else if($this->password_type == 'password_hash'){
            $p_hash = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        $data['password'] = $p_hash;
        
        if(!$isManual && isset($getFields['default-insert'])){
            foreach($getFields['default-insert'] as $defF=>$defV){
                
                //$data[$defF] = replaceBetweenBraces($data,$defV);
                $data[$defF] = $defV;
            }
        }        
        foreach ($data as $k => $v ) $data[$k] = "'".$this->escape($v)."'";

        $this->query("INSERT INTO $this->dbTable (`".implode('`, `', array_keys($data))."`) VALUES (".implode(", ", $data).")");
        $return['id'] = mysqli_insert_id($this->dbConn);
        $return['sql'] = '';
        $return['db_error'] = $this->errorLog;
        $return['message'] = "New Entry Saved";

        return $return;
    }

	function sendActivationEmail($data){
		global $site_path;
		$ip = getenv('HTTP_CLIENT_IP')?:
		getenv('HTTP_X_FORWARDED_FOR')?:
		getenv('HTTP_X_FORWARDED')?:
		getenv('HTTP_FORWARDED_FOR')?:
		getenv('HTTP_FORWARDED')?:
		getenv('REMOTE_ADDR');			
		$transaction_str = $data['username'].$data['password'].time().$ip;
		$transaction_id = md5($transaction_str);
		$datt = date('Y-m-d H-i-s');		
		$this->query("UPDATE $this->dbTable SET activation_hash='$transaction_id', date_created='$datt' WHERE username='$data[username]'");
		
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';
		//Set the hostname of the mail server
		$mail->Host = SMTP_HOST;
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port = SMTP_POST;
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication
		$mail->Username = SMTP_USERNAME;
		//Password to use for SMTP authentication
		$mail->Password = SMTP_PASSWORD;
		
		$mail->smtpConnect(
			array(
				"ssl" => array(
					"verify_peer" => false,
					"verify_peer_name" => false,
					"allow_self_signed" => true
				)
			)
		);		
		//Set who the message is to be sent from
		$mail->setFrom('rayansh.golu@gmail.com', 'You Activation Mail');
		//Set an alternative reply-to address
		$mail->addReplyTo('rayansh.golu@gmail.com', 'Your Activation Email');
		//Set who the message is to be sent to
		$mail->addAddress('dubey.priyanshu@gmail.com', 'Activation');
		//Set the subject line
		$mail->Subject = 'Please Activate your Account';
		
		$body_html = '
		<a href="'.$site_path.'ActivateUser?username='.$data['username'].'&activation_hash='.$transaction_id.'" class="" style="
			border-radius: 2rem;
			transition: .5s;
			background-image: -webkit-linear-gradient(50deg,#28BCFD 20%,#1D78FF 51%,#28BCFD 90%);
			background-image: -moz-linear-gradient(50deg,#28BCFD 20%,#1D78FF 51%,#28BCFD 90%);
			background-image: -o-linear-gradient(50deg,#28BCFD 20%,#1D78FF 51%,#28BCFD 90%);
			background-image: linear-gradient(40deg,#28BCFD 20%,#1D78FF 51%,#28BCFD 90%);
			background-position: left center;
			background-size: 200% auto;
			border: 0;
			-webkit-box-shadow: 0 0 12px 0 #1F87FF;
			box-shadow: 0 0 12px 0 #1F87FF;
			padding: 1rem 3rem;
			font-size: 1.125rem;
			line-height: 1;
			border-radius: .3rem;
			display: inline-block;
			text-align: center;
			vertical-align: middle;
			color: #FFF;">Activate Account</a>		
		';
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($body_html);
		//Replace the plain text body with one created manually
		$mail->AltBody = 'Please Activate Your Account';
		//Attach an image file
		//$mail->addAttachment('images/phpmailer_mini.png');

		//send the message, check for errors
		if (!$mail->send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			//echo "Message sent!";
		}  		
	}

	
    function getAffectedTables(){
        $arr = array();
        
        return $arr;
            
    }
     
    function getLabels(){
        {$arr['label'] = array(
          'id' => 'User Id',
          'username' => 'Username',
          'firstname' => 'First Name',
          'lastname' => 'Last Name',
          'email' => 'Email',
          'phone' => 'Phone',
          'password' => 'Password',
          'password2' => 'Confirm Password',
          'fullname' => 'Full Name',
          'joiningdate' => 'Joining Date',
          'gender' => 'Gender',
          'address' => 'Address',
          'city' => 'City',
          'state' => 'State',
          'country' => 'Country',
          'lastlogin' => 'Last Logged In',
          'ipaddress' => 'IP Address',
          'userlevel' => 'User Level',
          'dob' => 'Date Of Birth',
          'usertype' => 'User Type',
          'avatar' => 'Avatar',
          'status' => 'Status',
          'active' => 'Is Active',
            );
        } 
        return $arr['label'];        
    }
    
    function getDefaultForInsert(){
        $arr['default-insert'] = array();    
        return $arr['default-insert'];            
    }
    
    function getForeignKeys(){
        $arr = array(
/*                 'usergroup'=>array(
                        'relationship'=>'one-to-many',
                        'table'=>'perm_groups',
                        'join'=>'LEFT JOIN',
                        'as'=>'sg',
                        'id'=>'sg.id',		
                        'name'=>'sg.name'		
                    ) */
            );
        return $arr;            
    }

    function getIcons(){
        {$arr['icons']['firstname'] = 'fa fa-user';
        $arr['icons']['lastname'] = 'mdi-action-account-circle';
        $arr['icons']['username'] = 'mdi-action-account-circle';
        $arr['icons']['phone'] = 'mdi-communication-call';
        $arr['icons']['email'] = 'mdi-content-mail';
        $arr['icons']['password'] = 'mdi-communication-vpn-key';
        $arr['icons']['password2'] = 'mdi-communication-vpn-key';
        } 
        return $arr['icons'];        
    }
   
    function getAllFields(){
        {$arr['fields'] = array(
                'id'=>array(
                        'type'=>'select',
                        'options'=>$this->getSelectOptions("SELECT id, CONCAT(firstname,' ',lastname) as name FROM $this->dbTable"),
                        'width'=>'3'
                        ),	
                'firstname'=>array(
                        'type'=>'input',
                        'width'=>'3'
                        ),	
	            'lastname'=>array(
                        'type'=>'input',
                        'width'=>'3'
                        ),	
                'email'=>array(
                        'type'=>'input',
                        'width'=>'3',
                        ),		
                'phone'=>array(
                        'type'=>'input',
                        'width'=>'3'
                        ),	
                'username'=>array(
                        'type'=>'input',
                        'width'=>'4'
                        ),
                'password'=>array(
                        'type'=>'input',
                        'inputType'=>'password',
                        'width'=>'4'
                        ),	
                'password2'=>array(
                        'type'=>'input',
                        'inputType'=>'password',
                        'width'=>'4'
                        ),  
                'userlevel'=>array(
                        'type'=>'select',
                        'width'=>'4',
                        'options'=>get_user_level_array()
                        ),  
                'status'=>array(
                        'type'=>'select',
                        'width'=>'4',
                        'options'=>get_status_list()
                        )
            );
        }
         $arr['required'] = array('firstname','lastname','email','phone','username','password','password2');
        $arr['validation'] = array(
                'firstname'=>array('type'=>'alphanumericspaceundash'),
                'username'=>array('type'=>'alphanumericspaceundash'),
                'password'=>array('type'=>'alphanumericspaceundash','min_len'=>6,'max_len'=>30),
                'password2'=>array('type'=>'alphanumericspaceundash'),
                'lastname'=>array('type'=>'alphanumericspaceundash')
            );   
        $arr['no_duplicate'] = array('username','email','phone');      
        return $arr;	
        
    }

    function getTableFields(){
        $this->table = $this->getCommonTableFields();
        $sql = $this->generateSqlPullData();
        $this->table['sql'] = $sql;
        $this->table['typeahead'] = true;
        $this->table['typeahead_fields'] = array('firstname','lastname'); 
        $this->table['typeahead_html'] = '<span ><div><strong>{{firstname}}</strong> - {{lastname}} </div></span>';
        $this->table['search'] = false;
        $this->table['search_fields'] = array('main.firstname','main.lastname');
        ////Specify all the fields here and override them in custom-cols later
        $this->table['cols'] = array('firstname','lastname','username','email');
        $this->table['has_checkbox'] = true;
        $this->table['has_tr_buttons'] = true;

        $this->table['tr_buttons']['delete'] = '<a href="#" type="button" class="btn btn-outline red btnDelete" data-toggle="confirmation" data-placement="bottom" id="btnDelete" onClick="Delete(\'{id}\',this)">Delete >> __NAME_FIELD__</a>';
        $this->table['tr_buttons']['reset-password'] = '<a href="javascript:;" type="button" class="btn btn-outline red-flamingo" onClick="CommonFunc2({\'extended_action\':\'get\',\'form_type\':\'edit_password_single\',\'id\':\'__ID__\'},\'\',this)">Reset Password</a>';
        
        $this->table['th_buttons'] = array(
                            'refresh'=>'<a href="#" type="button" class="btn btn-outline red" onClick="CommonFunc({\'action\':\'refresh\'})"><i class="icon-refresh" ></i>Refresh</a>'	
                            );
        $this->table['portlet_actions']['new_ajax'] =   
        '
        <a href="javascript:;" type="button" class="btn btn-circle bg-color-primary-custom ladda-button"  data-style="zoom-out" data-size="l" onClick="CommonFunc2Btn({\'extended_action\':\'get\',\'form_type\':\'new_ajax\'},\'\',this)"><i class="fa fa-plus"></i> Add </a>
        ';
        $this->table['portlet_actions']['edit_password_multiple'] =   
        '
        <a href="javascript:;" type="button" class="btn btn-outline red-flamingo ladda-button"  data-style="zoom-out" data-size="l"  onClick="CommonFunc2Btn({\'extended_action\':\'get\',\'form_type\':\'edit_password_multiple\'},\'\',this)">Reset Password</a> 
        ';

        $this->table['bulk_update_fields']['status']['field_type'] = 'select';
        $this->table['bulk_update_fields']['status']['options'] = get_status_list(); 
        $this->table['bulk_update_fields']['userlevel']['field_type'] = 'select';
        $this->table['bulk_update_fields']['userlevel']['options'] = get_user_level_array(); 
        
        return $this->table;	
    }

    function extendAjax($data){
        if(isset($data['type'])){
            $this->errorAjax($data);
        }

        if(isset($data['action'])){
            $this->errorAjax($data);
        }
        else{
            $this->errorAjax($data);
        }        
        
    }
}
?>
