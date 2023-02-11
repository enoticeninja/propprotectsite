<?php 
Class Common
{

    public $handleAjaxCall = 
        array (
          'compare-dates' => 'compareDates'
        );
    function __construct(){

    }
    function getFunctionMappings($func_key=''){
        
        if($func_key == ''){
            return $this->handleAjaxCall;  
        }
        else{
            if(isset($this->handleAjaxCall[$func_key])){
                return $this->handleAjaxCall[$func_key];  
            }
            else{
                return false;  
            } 
        }        
              
    }
    
    function handleAjaxCall($data){
        if(!isset($data['next_data'])) $data['next_data'] = array();
        global $table,$db,$sql;
        $return = array();
        $return['debug'] = false;	
        $return['ajaxresult'] = false;	
        //print_r($data);
        $data['call_type'] = 'ajax';
        if (isset($data['action'])){
            $func_to_call = $this->getFunctionMappings($data['action']);
            
            if($func_to_call){
                $return = call_user_func_array(array($this, $func_to_call), array($data));
            }
            else{
                $this->extendAjax($data);    
            }
            $return['ajaxresult'] = true;            
            echo json_encode($return);
            exit();

        }      
    }	

	function compareDates($data){
		$start_date = $data['start_date'];
		$end_date = $data['end_date'];
		//$start_date = strtotime($start_date);
		$start_date = date('Y-m-d H:i:s',strtotime($start_date));
		$end_date = date('Y-m-d H:i:s',strtotime($end_date));
		//$end_date = strtotime($end_date);
		if($end_date > $start_date){
		   $return['ajaxresult'] = true;
		   $return['checkResult'] = true;

		}
		else{
		$return['ajaxresult'] = true;
		   $return['checkResult'] = false;
		}
		$return['start_date'] = $start_date;
		$return['end_date'] = $end_date;
	   $return['jsFunction'] = 
	   "
	   vCheck['result'] = value.checkResult;
	   console.log(value); 
	   console.log(vCheck); 
	   ";                
		echo json_encode($return);
		exit();
	}
	
    function extendAjax($data){
        $this->errorAjax($data);      
    }	

    function errorAjax($data){
        
        var_export($data);
        $return['ajaxmessage'] = 'This Action is Not Defined For This Page';				
        $return['post'] = $data;
        $return['table'] = $this->dbTable;
        $return['jsFunction'] = 
        "
        console.log(value.ajaxmessage);		
        ";			
        echo json_encode($return);
        exit();        
    }

}


?>