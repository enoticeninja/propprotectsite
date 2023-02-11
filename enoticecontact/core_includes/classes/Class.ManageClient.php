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
class ManageClient extends Ajax
{
    /*Do not edit after this line*/
    public $dbTable = 'client';
    public $common_title = 'Client';
    public $login_fields = array('username','email','phone');
    private $tbFields = array(
            'userID'=> 'id', 
            'login' => 'email',
            'login2' => 'username',
            'login3' => 'phone',
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

    public $dependency = array();
    public $password_type = 'password_hash';
    public $has_user_levels = false;
    public $rowsCountDisplay = 10;
   /**
   * Class Constructure
   * 
   * @param string $dbConn
   * @param array $settings
   * @return void  /// Priyanshu changed the return value to boolean
   */
  public function __construct($dbConn = '') {
        if($dbConn != ''){
            $this->dbConn = $dbConn;
        }
        $this->remCookieDomain = get_cookie_domain();
        $this->remCookieName = get_cookie_name();		
        $this->setFunctionMappings();
		$this->module = $this->dbTable;
        $this->session = get_session_variables();  
        $this->sensitiveFields = array('id','password');  
		//$this->dependency['status']['1']['hide'] = array('email');
  }
    function setFunctionMappings(){
        $this->functionMappings['new'] = 'getNewForm';
        $this->functionMappings['new_ajax'] = 'getNewAjaxForm';
        $this->functionMappings['edit'] = 'getEditForm';
        $this->functionMappings['edit_password_single'] = 'getPasswordResetSingleForm';
        $this->functionMappings['edit_password_multiple'] = 'getPasswordResetMultipleForm';
        $this->functionMappings['login_form'] = 'getLoginForm';
        $this->functionMappings['getEditFormNoImage'] = 'getEditFormNoImage';
        $this->actionMappings = array_flip($this->functionMappings);

        return $this->functionMappings;
    }  
    public function attempCookieLogin(){
        $this->remCookieDomain = get_cookie_domain();
        $this->remCookieName = get_cookie_name();

        if( !isset( $_SESSION ) ) session_start();
        if ( !empty($_SESSION[$this->session['id']]) )
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
        $res = $this->query("SELECT * FROM `{$this->dbTable}` 
                            WHERE `{$this->tbFields['login']}` = '$uname' OR `{$this->tbFields['login2']}` = '$uname' OR `{$this->tbFields['login3']}` = '$uname' LIMIT 1",__LINE__);
 
        if ( @mysqli_num_rows($res) == 0){
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
                    $this->userID = $this->userData[$this->tbFields['userID']];
                    $_SESSION[$this->session['id']] = $this->userID;
                    $_SESSION[$this->session['username']] = $this->userData[$this->tbFields['login']];
                    $_SESSION[$this->session['password']] = $this->userData[$this->tbFields['pass']];
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
        return true;
    }

    public function activateUser($uname, $password) {
        $uname    = $this->escape($uname);
        $password = $originalPassword = $this->escape($password);
        $res = $this->query("SELECT * FROM `{$this->dbTable}` 
                            WHERE (`{$this->tbFields['login']}` = '$uname' OR `{$this->tbFields['login2']}` = '$uname' OR `{$this->tbFields['login3']}` = '$uname') AND activation_hash='$password' LIMIT 1",__LINE__);
 
		print_r("SELECT * FROM `{$this->dbTable}` 
                            WHERE `{$this->tbFields['login']}` = '$uname' OR `{$this->tbFields['login2']}` = '$uname' OR `{$this->tbFields['login3']}` = '$uname' AND activation_hash='$password' LIMIT 1");
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
				$res = $this->query("UPDATE `{$this->dbTable}` SET activated='1' WHERE user_name='$uname'",__LINE__);				

            }
            else {
                return false;
            }			

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
    
    function getNewForm($data){
        $unique_id = uniqid();
        $unique_id .= rand(10,100);  
        $jsFunction = "";
        $return['form_type'] = $this->actionMappings[__FUNCTION__];
        /* $jsFunction = "
		matchPasswords('__UNIQUE_ID__-password','__UNIQUE_ID__-password2');
			$(document).on('change','#__UNIQUE_ID__-name', function(){
				var test1= $('#__UNIQUE_ID__-name').val();
				var nameArr = test1.split(' ');
				var test = nameArr[0];
				test = test.toLowerCase().replace(/ /g, '-');
				$('#__UNIQUE_ID__-user_name').val(test);
				$('#__UNIQUE_ID__-email').val(''+test+'@gmail.com');


			}); 
				$('#__UNIQUE_ID__-password').val('asdfasdf');
				$('#__UNIQUE_ID__-password2').val('asdfasdf');
				var phone = Math.random().toString().slice(2,12);
				$('#__UNIQUE_ID__-phone').val(phone);
				$('#__UNIQUE_ID__-employee_code').val(phone);
				$('#__UNIQUE_ID__-status').val('1');
				//$('#usergroup').val('17');
		"; */
            /////Form Configuration //////////////
        $return['request_data'] = $data;
        $return['unique_id'] = $unique_id;
        $return['override_function'] = array(
                                        'enabled'=>false,
                                        'function'=>''		
                                    );
        $return['fetch_data'] = array('enabled'=>false);                                    
        $return['portlet_title'] = 'Add New Client';
        $return['portlet_icon'] = '';
        $return['submit_id'] = 'submit_id';
        $return['call_type'] = isset($data['call_type']) ? $data['call_type'] : 'ajax';
        $return['return_form_as'] = isset($data['return_form_as']) ? $data['return_form_as'] : 'modal';
        $return['parent'] = $parent = $unique_id;
        $return['module'] = $module = $this->dbTable;
        $return['after_save'] = 'afterInsert';
        $return['hidden'] = array(
                            'enabled'=>false,
                            'html'=>''
                                );
        /*$return['buttons'] = array(
                        'enabled'=>false,
                        'html'=>''
                        ); */
        $return['on_submit'] = 'insert';
        $return['default_insert'] = array(
                                    'enabled'=>true,
                                    'fields'=>array('created_by'=>get_user_id(),
                                    'date_created'=>date('Y-m-d H-i-s')
                                    ));
        $return['on_submit_error'] = '';
        $return['name_prefix'] = 'data';
        $return['id_prefix'] = true;
        $return['fields'] = array('name','firstname','lastname','email','phone','username','status');
        $return['required'] = array('name','firstname','lastname','email','phone','username');
        $return['validation'] = array(
                                'email'=>array('type'=>'email'),
                                'username'=>array('type'=>'alphanumeric','min_len'=> 3,'max_len'=> 25),
								'phone'=>array('type'=>'number','min_len'=> 10,'max_len'=> 10,'min_val'=>'','max_val'=>'','message'=>'Phone Number Should be 10 digit'),
								/* 'password'=>array('type'=>'all','min_len'=>6,'message'=>'Password should be atleast 6 characters'),
								'password2'=>array('type'=>'all','min_len'=>6,'message'=>'Password should be atleast 6 characters'), */
                            );
        $return['dependency'] = $this->dependency;
        $return['duplicate'] =  array('name','username','email','phone');
		//$return['required']['firstname']['conditional'] = array('field'=>'status','condition'=>array('1'),'type'=>'select');
        $return['popover_error'] = true;
        $return['non_db_fields'] =  array();
        $return['jsFunction'] =  $jsFunction;
		$return['labels'] = $this->getLabels();
		$return['icons'] = $this->getIcons();
		$return['all_fields'] = $this->getAllFields();
        $return['jsFunctionLast'] = "";
        $return['jsModalClose'] =  "";
        return $return;
    
    }
    
    function getEditForm($data){
        $tempData = $data;
        $return = $this->getNewForm($tempData);
        $return['form_type'] = $this->actionMappings[__FUNCTION__];
        $return['fetch_data'] = array('enabled'=>true);
        $return['on_submit'] = 'update';
        $return['buttons']['array']['save']['actions']['action'] = 'update';
        $return['buttons']['array']['save']['actions']['form_type'] = 'edit';
        $return['fields'] = array('id','name','firstname','lastname','email','phone','username','status','image');
        $return['required'] = array('id','name','firstname','lastname','email','phone','username');
        $return['validation'] = array(
                                'email'=>array('type'=>'email'),
                                'username'=>array('type'=>'alphanumeric','min_len'=> 3,'max_len'=> 25),
								'phone'=>array('type'=>'number','min_len'=> 10,'max_len'=> 10,'min_val'=>'','max_val'=>'','message'=>'Phone Number Should be 10 digit'),
                            );
        $return['jsFunction'] = '';
        if (isset($data['id'])) {
            $return['has_data'] = $this->loadFromDB($data['id']);
            $return['override_fields'] = array(
                'id' => array(
                    'type' => 'hidden',
                    'value' => getDefault($data['id'], ''),
                ),
            );

            foreach ($this->getForeignKeys() as $key => $value) {
                if (isset($return['all_fields']['fields'][$key]['select2']) && isset($return['all_fields']['fields'][$key]['select2']['ajax'])) {
                    $foreign_value = $return['has_data'][$key];
                    $db = $value['table'];
                    $return['override_fields'][$key] = array(
                        'type' => 'select',
                        'options' => $this->getSelectOptions("SELECT id,name FROM $db WHERE id='$foreign_value'"),
                        'width' => '4',
                    );
                }
            }
        }
        return $return;
    }
    
    function getEditFormNoImage($data){
        $tempData = $data;
        $return = $this->getNewForm($tempData);
        $return['fetch_data'] = array('enabled'=>true);
        $return['on_submit'] = 'update';
        $return['buttons']['array']['save']['actions']['action'] = 'update';
        $return['buttons']['array']['save']['actions']['form_type'] = 'edit';
        $return['fields'] = array('id','name','firstname','lastname','email','phone','username');
        $return['required'] = array('id','name','firstname','lastname','email','phone','username');
        $return['validation'] = array(
                                'email'=>array('type'=>'email'),
                                'username'=>array('type'=>'alphanumeric','min_len'=> 3,'max_len'=> 25),
								'phone'=>array('type'=>'number','min_len'=> 10,'max_len'=> 10,'min_val'=>'','max_val'=>'','message'=>'Phone Number Should be 10 digit'),
                            );
        $return['jsFunction'] = '';
        if (isset($data['id'])) {
            $return['has_data'] = $this->loadFromDB($data['id']);
            $return['override_fields'] = array(
                'id' => array(
                    'type' => 'hidden',
                    'value' => getDefault($data['id'], ''),
                ),
            );
        }
        return $return;
    }
    
    function getNewAjaxForm($data){
        $tempData = $data;
        $tempData['form_type'] = 'new';
        $return = $this->getNewForm($tempData);          
        $return['return_form_as'] = 'modal';  
        return $return;        
    }
    function getLoginForm($data){
        $unique_id = uniqid();
        $unique_id .= rand(10,100);
        $tempData = $data;
        $return['unique_id'] = $unique_id;
        $return['override_function'] = array(
                                        'enabled'=>false,
                                        'function'=>''		
                                    );
        $return['fetch_data'] = array('enabled'=>false);                                    
        $return['portlet_title'] = 'Employee Login';
        $return['portlet_icon'] = '';
        $return['submit_id'] = 'submit_id';
        $return['name_prefix'] = 'data';
        $return['call_type'] = isset($data['call_type']) ? $data['call_type'] : 'ajax';
        $return['return_form_as'] = isset($data['return_form_as']) ? $data['return_form_as'] : 'rows';
        $return['parent'] = $parent = $unique_id;
        $return['module'] = $module = $this->dbTable;     
        $return['hidden'] = array(
                            'enabled'=>true,
                            'html'=>'<input type="hidden" name="on_page_action" value="login">'
                                );
        $return['fields'] = array('username','password');
        $return['override_fields'] = array(
                                    'username'=>array(
                                            'width'=>'12',		
                                            'label'=>'Username/Email',		
                                            'js'=>'$("#__ELEMENT_ID__").addClass("edited");'		
                                        ),
                                    'password'=>array(
                                            'width'=>'12',		
                                            'js'=>'$("#__ELEMENT_ID__").addClass("edited");'			
                                        ),
                                    );
        $return['required'] = array('user_name','password');
        $return['default_insert'] = array('enabled'=>false);
        $return['validation'] = array();         
        $return['jsFunction'] = "";         
        return $return;        
    }    
    function getPasswordResetSingleForm($data){
        $tempData = $data;
        $tempData['form_type'] = 'new';
        $return = $this->getNewForm($tempData);           
        $parent = $return['parent'];         
        $return['fetch_data'] = array('enabled'=>false);
        $return['portlet_title'] = 'Reset Employee Password';
        $return['buttons'] = array(
                        'enabled'=>true,
                        'html'=>'',
						'array'=> array(
							'save'=>array(
								'actions'=>array(
									'form_type'=>'edit_password_single',
									'action'=>'reset-password'
								),
								'next_actions'=>array(),
								'class'=>'btn-outline green',
								'func'=>'',
								'title'=>'Reset Password',
								'extra'=>'',
							)
						)		
		);

        $return['on_submit'] = 'update';        
        $return['modal_width'] = '600px';        
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
        $return['buttons']['array']['save']['actions']['form_type'] = 'edit_password_multiple';
        $return['buttons']['array']['save']['title'] = 'Reset Password';
        $return['validation'] = array(
                'password'=>array('type'=>'alphanumericspaceundash','min_len'=>6,'message'=>'Password should be atleast 6 characters'),
                'password2'=>array('type'=>'alphanumericspaceundash','min_len'=>6,'message'=>'Password should be atleast 6 characters'),
        );         
        $return['required'] = array('id','password','password2');
        $return['override_fields'] = array(
                'id'=> array(
                        'width'=>'12',		
                        'type'=>'select',
                        'options'=>$this->getSelectOptions("SELECT users.id,users.name as name FROM $this->dbTable  as users"),
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
		$mail->Host = "smtp.gmail.com";
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port = 25;
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication
		$mail->Username = "rayansh.golu@gmail.com";
		//Password to use for SMTP authentication
		$mail->Password = "Kemosabe@1010";
		
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
			  'id' => 'ID',
			  'name' => 'Company Name',
			  'username' => 'User Name',
			  'password' => 'Password',
			  'password2' => 'Confirm Password',
			  'firstname' => 'First Name',
			  'lastname' => 'Last Name',
			  'email' => 'Email',
			  'phone' => 'Phone',
			  'mobile' => 'Mobile',
			  'image' => 'Logo',
			  'type' => 'Type',
			  'date_created' => 'Date Created',
			  'created_by' => 'Created By',
			  'status' => 'Status',
			  'notes' => 'Notes',
			  'last_login' => 'Last Login',
            );
        } 
        return $arr['label'];        
    }
    
    function getDefaultForInsert(){
        $arr['default-insert'] = array(
                'date_created'=>date('Y-m-d H-i-s'),
                'created_by'=>$_SESSION[get_session_values('id')]
            );    
        return $arr['default-insert'];            
    }
    
    function getForeignKeys(){
        $arr = array(
                /*'usergroup'=>array(
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
        {$arr['fields'] = 
					array (
					  'id' => array (
						'type' => 'input',
						'width' => '4',
					  ),
					  'name' => array (
						'type' => 'input',
						'width' => '4',
					  ),
					  'username' => array (
						'type' => 'input',
						'width' => '4',
					  ),
					  'password' => array (
						'type' => 'input',
                        'inputType'=>'password',
						'width' => '4',
					  ),
					  'password2' => array (
						'type' => 'input',
                        'inputType'=>'password',
						'width' => '4',
					  ),
					  'firstname' => array (
						'type' => 'input',
						'width' => '4',
					  ),
					  'lastname' => array (
						'type' => 'input',
						'width' => '4',
					  ),
					  'email' => array (
						'type' => 'input',
						'width' => '4',
					  ),
					  'phone' => array (
						'type' => 'input',
						'width' => '4',
					  ),
					  'mobile' => array (
						'type' => 'input',
						'width' => '4',
					  ),
                      'image' => array(
                        'type' => 'upload_single_image',
                        'image_options' => array(
                            'upload_path' => 'uploads/clients/',
                            'upload_action' => 'upload_image_thumb',
                            'default_image' => 'core_theme/images/upload-image.png',
                        ),
                        'width' => '4',
                    ),
                    'image_path' => array(
                        'type' => 'input',
                        'width' => '4',
                    ),
					  'type' => array (
						'type' => 'input',
						'width' => '4',
					  ),
					  'date_created' => array (
						'type' => 'input',
						'width' => '4',
					  ),
					  'created_by' => array (
						'type' => 'input',
						'width' => '4',
					  ),
					  'status' => array (
						'type' => 'select',
                        'options' => get_numeric_status_list(),
						'width' => '4',
						'value' => '1',
					  ),
					  'notes' => array (
						'type' => 'input',
						'width' => '4',
					  ),
					  'last_login' => array (
						'type' => 'input',
						'width' => '4',
					  ),
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
        $arr['no_duplicate'] = array('user_name','email','phone');

        if (DEV_MODE) {
            //$arr['fields']['password']['value'] = 'asdfasdf';
            //$arr['fields']['password2']['value'] = 'asdfasdf';
            $arr['fields']['phone']['value'] = generateRandomNumber(10);

            $fake_num = file_get_contents(DIR_CORE_INCLUDES . 'countnames.txt');
            include DIR_CORE_INCLUDES . 'fake_names.php';
            $fake = $fake_names[$fake_num];
            $arr['fields']['email']['value'] = $fake['email'];
            $arr['fields']['lastname']['value'] = $fake['lastname'];
            $arr['fields']['firstname']['value'] = $fake['firstname'];
            $arr['fields']['name']['value'] = $fake['firstname'] . ' ' . $fake['lastname'] . ' Advertising Company';
            $arr['fields']['username']['value'] = strtolower($fake['firstname'] . '_' . $fake['lastname']);
        }        
        return $arr;	
        
    }


    function getTableFields($data){
        if(isset($data['pull_data']) && $data['pull_data']){
            $sql = $this->generateSqlPullData();
            $this->table['module'] = $this->module;
            $this->table['rowsCountDisplay'] = $tempRowsNum = $this->rowsCountDisplay;
            $this->table['sql'] = $sql;
            $sqlNum = "$sql
            ORDER BY main.{$this->config['id']} DESC
            LIMIT $tempRowsNum		
            ";
            $this->table['foreignKeys'] = $this->getForeignKeys();
            $this->table['tableData'] = $this->getDataForTable($sqlNum);
            $this->table['totalRowsInDb'] = $this->numRows($this->table['sql']);
            $this->table['labels'] = $this->getLabels();
            $this->table['config'] = $this->config;
        }
        $this->table['common_title'] = $this->common_title;
        $this->table['typeahead'] = true;
        $this->table['typeahead_fields'] = array('firstname','lastname','username','email','phone'); 
        $this->table['typeahead_html'] = '<span ><div><strong>{{name}}</strong></div></span>';      
        $this->table['search'] = true;
        $this->table['search_fields'] = array('firstname','lastname','username','email','phone');
        ////Specify all the fields here and override them in custom-cols later
        $this->table['cols'] = array('name','firstname','lastname','username','email','phone');
        $this->table['has_tr_buttons'] = true;
        $this->table['hasDataTable'] = true;
        $this->table['status_field'] = 'status';
		//$this->table['tr_buttons'] = array();
		
		$newJsFunc3 = 
		"
		let data = {};
		data['data'] = {'action':'edit_password_single','type':'edit_password_single','module':'{$this->module}','id':'__ID__'};
		data['isAjax'] = true;
		data['e'] = this;
		new FormGenerator(data);
		";		
        //$this->table['tr_buttons']['reset-password'] = '<a href="javascript:;" type="button" class="btn btn-outline orange" onClick="'.$newJsFunc3.'">Reset Password</a>';
        
        $this->table['th_buttons'] = array(
                            'refresh'=>'<a href="#" type="button" class="btn btn-outline red" onClick="CommonFunc({\'action\':\'refresh\'})"><i class="icon-refresh" ></i>Refresh</a>'	
                            );

		
		$newJsFunc2 = 
		"
		let data = {};
		data['data'] = {'action':'edit_password_multiple','edit_password_multiple':'new_ajax','module':'{$this->module}'};
		data['isAjax'] = true;
		data['e'] = this;
		new FormGenerator(data);
		";		
        /* $this->table['portlet_actions']['edit_password_multiple'] =   
        '
        <a href="javascript:;" type="button" class="btn btn-outline red"  data-style="zoom-out" data-size="l"  onClick="'.($newJsFunc2).'">Reset Password</a>
        '; */

        $this->table['bulk_update_fields']['status']['field_type'] = 'select';
        $this->table['bulk_update_fields']['status']['options'] = get_numeric_status_list(); 
        
        return $this->table;	
    }

    public function delete($data){
		$id = $data['id'];
        $sql = "SELECT image,image_path FROM $this->dbTable WHERE id='$id'";
        $query = mysqli_query($this->dbConn, $sql);
		$quotient = (int)($id/500);///FOR NEW FOLDER
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            if(!empty($row['image']) ){
				if($row['image_path'] != '' && $row['image_path'] != 'uploads'){
                    DeleteDirectoryAndContents(DIR_ROOT.$row['image_path']);
                }
			}
        }
		
        $return = array();
        $sql = "DELETE FROM `$this->dbTable` WHERE {$this->config['id']}='$id'";
        $query = mysqli_query($this->dbConn,$sql);
        $return['query'][] = $query;
        $return['sql'][] = $sql;
        foreach($this->delete_dependency as $del => $fk){
            $sql = "DELETE FROM $del WHERE $fk='$id'";
            $query1 = mysqli_query($this->dbConn,$sql); 
            $return['query'][] = $query1;
            $return['sql'][] = $sql;            
        } 
       return $return;
    }

    public function deleteBulk($ids){
        $idsString= implode(',',$ids);
        $sql = "SELECT id,image,image_path FROM $this->dbTable WHERE id IN ($idsString)";
        $query = mysqli_query($this->dbConn, $sql);
		$return['mysqli_error'][] = mysqli_error($this->dbConn);
		//$quotient = (int)($id/500);///FOR NEW FOLDER
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            if(!empty($row['image']) ){
				if($row['image_path'] != '' && $row['image_path'] != 'uploads'){
                    DeleteDirectoryAndContents(DIR_ROOT.$row['image_path']);
                }
			}
        }
		$sql = "DELETE FROM $this->dbTable WHERE id IN ($idsString)";
		$query = mysqli_query($this->dbConn,$sql);
		$return['query'][] = $query;
		$return['sql'][] = $sql;
		$return['mysqli_error'][] = mysqli_error($this->dbConn);
        $return = array();
		foreach($this->delete_dependency as $del => $fk){
			$sql = "DELETE FROM $del WHERE $fk IN ($idsString)";
			$query1 = mysqli_query($this->dbConn,$sql);
			$return['query'][] = $query1;
			$return['sql'][] = $sql;  
			$return['mysqli_error'][] = mysqli_error($this->dbConn);
		}

       return $return;
    }
	
	public function deleteImage($data){
        $id = $data['id'];
        $unique_id = $data['unique_id'];
        $sql = "SELECT image,image_path FROM $this->dbTable WHERE id='$id'";
        $query = mysqli_query($this->dbConn, $sql);
		$quotient = (int)($id/500);///FOR NEW FOLDER
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            if(!empty($row['image']) ){
				if($row['image_path'] != '' && $row['image_path'] != 'uploads'){
                    DeleteDirectoryAndContents(DIR_ROOT.$row['image_path']);
                }
			}
        }
        
        $sql = "UPDATE $this->dbTable SET image='',image_path='' WHERE id='$id'";
        $query = mysqli_query($this->dbConn, $sql);
        
        $image_site_path_link = FRONTEND_SITE_PATH.'core_theme/images/upload-image.png';        
        $return['ajaxresult'] = true;
        $return['jsFunction'] = 
        "
        $('#{$unique_id}_image').addClass('hidden');
        $('#{$unique_id}_image_actions').addClass('hidden');
        $('#{$unique_id}_new_img_btn').removeClass('hidden');
        $('#{$unique_id}_image').attr('src','$image_site_path_link');
        $('#{$unique_id}_image').removeClass('hidden');
        ";
        echo json_encode($return);;
        exit();
    }


	public function afterInsert($data,$unique_id,$id){
        if (DEV_MODE) {
            $fake_num = file_get_contents(DIR_CORE_INCLUDES . 'countnames.txt');
            //print_r($fake_num);
            //print_r($fake_num);
            file_put_contents(DIR_CORE_INCLUDES . 'countnames.txt', $fake_num + 1);
        }
		//$this->insertImage($data,$unique_id,$id);
    }
    
	public function insertImage($data,$unique_id,$id){
		if(empty($_FILES[$unique_id]['name'][0])) return array();
		include_once(DIR_OTHER_CLASSES."Class.Configuration.php");
		include_once(DIR_OTHER_CLASSES."Class.ImageManagerCore.php");				
		$uploads_path = $this->getAllFields()['fields']['image']['image_options']['upload_path'];
		//print_r($_FILES['data']['name']);
		$pic_name = $_FILES[$unique_id]['name']['image'];
		$img_name_only = file_name($pic_name);
		$filename_for_db = preg_replace("/[\-_]/", " ", $img_name_only);
		$img_type_only = file_extension($pic_name);
		$image_path = '';
		$image = $id.'.'.$img_type_only;
		$imageName = $uploads_path.$image_path.$image;
		$quotient = (int)($id/500);///FOR NEW FOLDER
		$fullPath = DIR_ROOT.$uploads_path.$quotient.'/'.$id.'/';
		$newRelPath = $uploads_path.$quotient.'/'.$id.'/';///Path without root
		if(!is_dir($fullPath)){
			$temp = mkdir($fullPath, 0777, true);
		}
		//rename(DIR_ROOT.$imageName, $newRelPath.'/'.$image);
		move_uploaded_file($_FILES[$unique_id]['tmp_name']['image'], $fullPath.$image);
		
		$sql = "UPDATE {$this->dbTable} SET image='$image',image_path='$newRelPath' WHERE id='$id'";
		$query = mysqli_query($this->dbConn, $sql);
		
		$iman = new ImageManagerCore();
		ImageManagerCore::$dimensions = array();
		ImageManagerCore::$dimensions['100X100-'] = array('100','100');
		ImageManagerCore::$dimensions['200X200-'] = array('200','200');
		//print_r(DIR_ROOT.$newRelPath.$image);
		ImageManagerCore::resize($fullPath.$image, $fullPath, 50, 50, 'png',false,$image);		
		return array();
    }
    function uploadImage($data){
        include_once(DIR_OTHER_CLASSES."Class.Configuration.php");
        include_once(DIR_OTHER_CLASSES."Class.ImageManagerCore.php");
        $uploads_path = $data['upload_path'];
        $unique_id = $data['unique_id'];
        $image_path = $data['image_path'];
        $originalName = $data['image'];
        $id = $data['id'];
        $base_name = basename($originalName);
        $image_ext = file_extension($base_name);
        $newName = $id.'.'.$image_ext;
        $originalNameWithRelPath = $uploads_path.$image_path.$originalName;
        $quotient = (int)($id/500);///FOR NEW FOLDER
        $fullPath = DIR_ROOT.$uploads_path.$quotient.'/'.$id.'/';
        $newRelPath = $uploads_path.$quotient.'/'.$id.'/';///Path without root
        if(!is_dir($fullPath)){
          mkdir($fullPath, 0777, true);
        }
        rename(DIR_ROOT.$originalNameWithRelPath, $fullPath.$newName);
        $sql = "UPDATE {$this->dbTable} SET image='$newName',image_path='$newRelPath' WHERE id='$id'";
        $query = mysqli_query($this->dbConn, $sql);
        
        $iman = new ImageManagerCore();
        ImageManagerCore::$dimensions = array();
        ImageManagerCore::$dimensions['100X100-'] = array('100','100');
        ImageManagerCore::$dimensions['200X200-'] = array('200','200');
        //print_r(DIR_ROOT.$newRelPath.$newName);
        ImageManagerCore::resize(DIR_ROOT.$newRelPath.$newName, DIR_ROOT.$newRelPath, 50, 50, 'png',false,$newName);


        $image_site_path_link = FRONTEND_SITE_PATH.$newRelPath.$newName;
        $return['jsFunction'] = 
        "
        $('#{$unique_id}_image_actions').removeClass('hidden');
        $('#{$unique_id}_new_img_btn').addClass('hidden');
        $('#{$unique_id}_default_image').addClass('hidden');
        $('#{$unique_id}_image').attr('src','$image_site_path_link');
        $('#{$unique_id}_image').removeClass('hidden');
        ";				
        $return['ajaxresult'] = true;
        $return['ajaxmessage'] = 'Success';    
        echo json_encode($return);
        exit();
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
