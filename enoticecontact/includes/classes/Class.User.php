<?php
require_once DIR_OTHER_CLASSES.'Class.ManageBackendUser.php';
class User extends ManageBackendUser
{
  public function __construct($dbConn = '') {
        if($dbConn != ''){
            $this->dbConn = $dbConn;
        }
        $this->session = get_session_variables();  
        $this->sensitiveFields = array('id','password'); 
		
		$this->remCookieDomain = get_cookie_domain();
		$this->remCookieName = get_cookie_name();
        return $this->attempCookieLogin();
  }    
}
?>
