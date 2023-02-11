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
function updateUserSettings($username, $properties) {
	global $db_conx;
	  $query = "UPDATE `user_settings` SET ";
      $c = count($properties);//a small optimization :)
	        $i=1;
      foreach($properties AS $k => $v) {

        $query .= '`'.$k."` = '".$v."'".(($i++ < $c) ? ', ' : ' ');
      }
      $query .= "WHERE `username` = '$username'";

	$res = mysqli_query($db_conx, $query);
	return $res;
}

function updateUser($username, $properties) {
	global $db_conx;	
	  $query = "UPDATE `users` SET ";
      $c = count($properties);//a small optimization :)
	        $i=1;
      foreach($properties AS $k => $v) {

        $query .= '`'.$k."` = '".$v."'".(($i++ < $c) ? ', ' : ' ');
      }
      $query .= "WHERE `username` = '$username'";

	$res = mysqli_query($db_conx, $query);
	return $res;	
}

class User
{
  /*Settings*/
  /**
   * The database that we will use
   * var string
   */
  private $dbName = 'jubilant';
  /**
   * The database host
   * var string
   */
  private $dbHost = 'localhost';
  /**
   * The database port
   * var int
   */
  private $dbPort = 3306;
  /**
   * The database user
   * var string
   */
  private $dbUser = 'root';
  /**
   * The database password
   * var string
   */
  private $dbPass = '';
  /**
   * The database table that holds all the information
   * var string
   */
  private $dbTable  = 'users';
  private $dbTableSettings  = 'users_settings';
  /**
   * The session variable ($_SESSION[$sessionVariable]) which will hold the data while the user is logged on
   * var string
   */
  private $sessionVariable = 'userSessionValueCrm';
  
  private $session = [];		///external function from config.php

			
	/**
   * When user wants the system to remember him/her, how much time to keep the cookie? (seconds)
   * var int
   */
  private $remTime = 2592000;//One month
  /**
   * The name of the cookie which we will use if user wants to be remembered by the system
   * var string
   */
  private $remCookieName = '';

  /**
   * The cookie domain
   * var string
   */
  private $remCookieDomain = '';				
  /**
   * Those are the fields that our table uses in order to fetch the needed data. The structure is 'fieldType' => 'fieldName'
   * var array
   */
  private $tbFields = array(
  	'userID'=> 'id', 
  	'login' => 'username',
  	'pass'  => 'password',
  	'email' => 'email',
  	'active'=> 'activated'
  );

  /**
   * The method used to encrypt the password. It can be sha1, md5 or nothing (no encryption)
   * var string
   */
  private $passMethod = 'md5';
  /**
   * Display errors? Set this to true if you are going to seek for help, or have troubles with the script
   * var bool
   */

  /*Do not edit after this line*/
  public $userID;
  public $dbConn;
  public $userData = array();
  public $anotherUserData = array();
  public $anotherUserSettings = array();
  public $DEVELOPMENT_MODE = false;
  private $displayErrors = false;
  public $user_ok = false;
  public $errorLog;
  /**
   * Class Constructure
   * 
   * @param string $dbConn
   * @param array $settings
   * @return void  /// Priyanshu changed the return value to boolean
   */
  public function User($dbConn = '', $settings = '') {

			$this->session = get_session_variables();
			if ( is_array($settings) ){
				foreach ( $settings as $k => $v ){
						if ( !isset( $this->{$k} ) ) die('Property '.$k.' does not exists. Check your settings.');
						$this->{$k} = $v;
				}
			}
            
			$this->remCookieDomain = get_cookie_domain();
			$this->remCookieName = get_cookie_name();
            //print_r($this->remCookieDomain);
            //print_r($_COOKIE);
            
			$this->dbConn = ($dbConn=='')? mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass , $this->dbName):$dbConn;
			if (mysqli_connect_errno()) die(mysqli_connect_error());

			if( !isset( $_SESSION ) ) session_start();
			if ( !empty($_SESSION[$this->session['id']]) )
				 $this->user_ok = $this->login( $_SESSION[$this->session['username']], $_SESSION[$this->session['password']] );
			//Maybe there is a cookie?
			if ( isset($_COOKIE[$this->remCookieName]) && !$this->is_loaded()) {
			  //echo 'I know you<br />';
			  $u = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
			   $this->user_ok = $this->login($u['uname'], $u['password']);
			}

  }

    public function attempCookieLogin(){
        $this->remCookieDomain = $this->remCookieDomain == '' ? $_SERVER['HTTP_HOST'] : $this->remCookieDomain;

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

		$res = $this->query("SELECT id,username,password FROM `{$this->dbTable}` 
							WHERE `{$this->tbFields['login']}` = '$uname' LIMIT 1",__LINE__);

		if ( @mysqli_num_rows($res) == 0){
			return false;
		}
		else{
			$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
			$hash = $row[$this->tbFields['pass']];				
			if (password_verify($password, $hash)) {
				if ( $loadUser ) {
					$res = $this->query("SELECT * FROM `{$this->dbTable}` 
							WHERE `{$this->tbFields['login']}` = '$uname' LIMIT 1",__LINE__);					
					$this->userData = mysqli_fetch_array($res, MYSQLI_ASSOC);
					$this->userID = $this->userData[$this->tbFields['userID']];
					$_SESSION[$this->session['id']] = $this->userID;
					$_SESSION[$this->session['username']] = $this->userData['username'];
					$_SESSION[$this->session['password']] = $this->userData['password'];
					if ( $remember ){
					  $cookie = base64_encode(serialize(array('uname'=>$uname,'password'=>$originalPassword)));
					  $a = setcookie($this->remCookieName,$cookie,strtotime( '+30 days' ), "/$this->remCookieDomain", "", "", TRUE);
					}
				}
			} else {
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
  	* Function to determine if a property is true or false
  	* param string $prop
  	* @return bool
  */
  public function is($prop){
  	return $this->get_property($prop)==1?true:false;
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
  	* Is the user an active user?
  	* @return bool
  */
  public function is_active() {
    return $this->userData[$this->tbFields['active']];
  }
  
  /**
   * Is the user loaded?
   * @ return bool
   */
  public function is_loaded() {
    return empty($this->userID) ? false : true;
  }
  /**
  	* Activates the user account
  	* @return bool
  */
  public function activate() {
    if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
    if ( $this->is_active()) $this->error('Allready active account', __LINE__);
    $res = $this->query("UPDATE `{$this->dbTable}` SET {$this->tbFields['active']} = 1 
	WHERE `{$this->tbFields['userID']}` = '".$this->escape($this->userID)."' LIMIT 1");
    if (@mysqli_affected_rows($this->dbConn) == 1) {
		$this->userData[$this->tbFields['active']] = true;
		return true;
	}
	return false;
  }
  /*
   * Creates a user account. The array should have the form 'database field' => 'value'
   * @param array $data
   * return int
   */  
  public function insertUser($data){
    if (!is_array($data)) $this->error('Data is not an array', __LINE__);
		//$data[$this->tbFields['pass']] = $this->encode_password($data[$this->tbFields['pass']]);
		//$data[$this->tbFields['pass']] = md5($data[$this->tbFields['pass']]);

    foreach ($data as $k => $v ) $data[$k] = "'".$this->escape($v)."'";

    $this->query("INSERT INTO `{$this->dbTable}` (`".implode('`, `', array_keys($data))."`) VALUES (".implode(", ", $data).")");
	$userid = mysqli_insert_id($this->dbConn);
	if(($this->dbTableSettings != '') && ($userid != 0)){
		$username = $data['username'];
		$this->query("INSERT INTO user_settings (`id`, `username`) VALUES($userid,$username)");		
	}
    return $userid;
  }
  
  /*
   * Updates a property. Data must be in the form 'property' => 'value'
   * @param array
   * @return bool
   */
  public function updateProperty($properties) {
    if(is_array($properties) && count($properties) > 0) {
      $i=1;
      $query = "UPDATE `".$this->dbTable."` SET ";
      $c = count($properties);//a small optimization :)
      foreach($properties AS $k => $v) {
        $v = ($k == $this->tbFields['pass']) ? $this->encode_password($v) : $v;
        $query .= '`'.$this->escape($k)."` = '".$this->escape($v)."'".(($i++ < $c) ? ', ' : ' ');
      }
      $query .= "WHERE `".$this->tbFields['userID']."` = '".$this->userID."'";
      return mysqli_query($this->dbConn, $query); 
    }
    return $this->error('$properties should be a non empty array', __LINE__);
  }
  
  /*
   * Updates User Settings. Data must be in the form 'property' => 'value'
   * @param array
   * @return bool
   */
  public function updateUserSettings($properties) {
    if(is_array($properties) && count($properties) > 0) {
      $i=1;
	  $username = $_POST['username'];
      $query = "UPDATE `user_settings` SET ";
      $c = count($properties);//a small optimization :)
      foreach($properties AS $k => $v) {

        $query .= '`'.$this->escape($k)."` = '".$this->escape($v)."'".(($i++ < $c) ? ', ' : ' ');
      }
      $query .= "WHERE `".$this->tbFields['username']."` = $username";
      return mysqli_query($this->dbConn, $query); 
    }
    return $this->error('$properties should be a non empty array', __LINE__);
  }  
  
  /*
   * Creates a random password. You can use it to create a password or a hash for user activation
   * param int $length
   * param string $chrs
   * return string
   */
  public function randomPass($length=10, $chrs = '1234567890qwertyuiopasdfghjklzxcvbnm'){
    for($i = 0; $i < $length; $i++) {
        $pwd .= $chrs{mt_rand(0, strlen($chrs)-1)};
    }
    return $pwd;
  }
  ////////////////////////////////////////////
  // PRIVATE FUNCTIONS
  ////////////////////////////////////////////
  
  /**
  	* SQL query function
  	* @access private
  	* @param string $sql
  	* @return string
  */
  private function query($sql, $line = 'Uknown') {
    //if ($this->DEVELOPMENT_MODE ) echo '<b>Query to execute: </b>'.$sql.'<br /><b>Line: </b>'.$line.'<br />';
    $this->errorLog .= '<b>Query to execute: </b>'.$sql.'<br /><b>Line: </b>'.$line.'<br />';
	$res = mysqli_query($this->dbConn, $sql);
	if ( !$res )
		$this->error(mysqli_error($this->dbConn), $line);
	return $res;
  }
  
  /**
  	* A function that is used to load one user's data
  	* @access private
  	* @param string $userID (originally)
	* Now takes  username, password
  	* @return bool
  */
  private function loadUser($username, $password) {
	$res = $this->query("SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['login']}` = '".$this->escape($username)."' LIMIT 1");
    if ( mysqli_num_rows($res) == 0 )
    	return false;
    $this->userData = mysqli_fetch_array($res, MYSQLI_ASSOC);
	$this->userID = $this->userData[$this->tbFields['userID']];
    $_SESSION[$this->session['id']] = $this->userID;
    $_SESSION[$this->session['username']] = $this->userData['username'];
    $_SESSION[$this->session['password']] = $this->userData['password'];
    return true;
  }

  
    /**
  	* A function that is used to load one user's data
  	* @access private
  	* @param string $userID (originally)
	* Now takes  username, password
  	* @return bool
  */
  public function loadAnotherUser($username) {
	$res = $this->query("SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['login']}` = '".$this->escape($username)."' LIMIT 1");
    if ( mysqli_num_rows($res) == 0 )
    	return false;
		$this->anotherUserData = mysqli_fetch_array($res, MYSQLI_ASSOC);
			$res = $this->query("SELECT * FROM user_settings WHERE `username` = '".$this->escape($username)."' LIMIT 1");
			    if ( mysqli_num_rows($res) == 0 ) return false;
				$this->anotherUserSettings = mysqli_fetch_array($res, MYSQLI_ASSOC);				
    return true;
  }
  
  /**
  	* Produces the result of addslashes() with more safety
  	* @access private
  	* @param string $str
  	* @return string
  */  
  private function escape($str) {
    $str = get_magic_quotes_gpc()?stripslashes($str):$str;
    $str = mysqli_real_escape_string($this->dbConn, $str);
    return $str;
  }
  
  /**
  	* Error holder for the class
  	* @access private
  	* @param string $error
  	* @param int $line
  	* @param bool $die
  	* @return bool
  */  
  private function error($error, $line = '', $die = false) {
    //if ( $this->displayErrors )
    	$this->errorLog .=  '<b>Error: </b>'.$error.'<br /><b>Line: </b>'.($line==''?'Unknown':$line).'<br />';
    if ($die) exit;
    return false;
  }
  
  private function encode_password($pass){
  	switch(strtolower($this->passMethod)){
	  case 'sha1':
	  	return SHA1($pass);
	  case 'md5' :
	  	return MD5($pass);
	  case 'nothing':
	  	return $pass;
	  case 'default':
	    return $this->error('Unknown password encoding method');
	}
  }
}
?>
