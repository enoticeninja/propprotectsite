<?php 
function JSONify($csv){ /// Takes csv string without quotes and creates js object
    $arr = explode(',',$csv);
    $return = array();
    foreach($arr as $i=>$val){
        $pair = explode(':',$val);
        $return[$pair[0]] = $pair[1];
    }
    return htmlspecialchars(json_encode($return,true));
}

function replace_conditional_html($data,$string){
    
    $pattern = "#\s\[conditional\](.*?)\[/conditional\]#s";
    preg_match_all($pattern,$string,$m);
    //print_r($m);
    foreach($m[0] as $tempString){

        preg_match("#\s\[condition\](.*?)\[\/condition\]#s",$tempString,$condition);        
        preg_match("#\s\[true\](.*?)\[\/true\]#s",$tempString,$true);        
        preg_match("#\s\[false\](.*?)\[\/false\]#s",$tempString,$false);

        if(!isset($condition[1])) continue;
        if(!isset($true[1]) && !isset($false[1])) continue;
        $cond_result = eval('return '.$condition[1].';');
        //print_r($condition[1]);
        if($cond_result === true && isset($true[1])){
            $replacement = $true[1];
        }
        else if($cond_result === false && isset($false[1])){
            $replacement = $false[1];
        }
        else{
            $replacement = '';
        }
        $string = str_replace($tempString, $replacement, $string);
    }
    $string = replaceBetweenBraces($data,$string);
    return $string;
}

function replace_conditional_inline($data,$string){
    
    $pattern = "#\[INLINE\](.*?)\[/INLINE\]#s";
    preg_match_all($pattern,$string,$m);
    
    //print_r($m);
    $i = 0;
    foreach($m[1] as $key=>$tempString){
       
        $first_explode = explode('|||',$tempString);
        $condition_key = $first_explode[0];
        // print_r($condition_key);
        $first_string = $first_explode[1];
        $second_explode = explode('||',$first_string);
        $replacement = '';
        foreach($second_explode as $tempArr){
            $cond_value_arr = explode(',',trim($tempArr));
            if($data[trim($condition_key)] == trim($cond_value_arr[0])) {
                
                $replacement = trim($cond_value_arr[1]);
            }
        }
        $string = str_replace($m[0][$key],$replacement,$string);
        

    }
    //$string = replaceBetweenBraces($data,$string);
    //print_r($string);
    return $string;
}

function replaceBetweenBraces($dataArray, $string){
	
	$pattern = '/{(.*?)[\|\|.*?]?}/';						
	$replace = preg_replace_callback($pattern, function($match) use ($dataArray)
	{
        if(isset($dataArray[$match[1]])){
            return $dataArray[$match[1]];
        }
        else{
            return $match[0];
            }

	}, $string);
    //$backtrace = debug_backtrace();
     //print_r( $backtrace );
	return $replace;
} 
function replaceBetweenSquareBrackets($dataArray, $string, $keyword='VAL'){/////[VAL]sdfgsdfg[/VAL]
	
    $pattern = "#\[$keyword\](.*?)\[/$keyword\]#s";
    preg_match_all($pattern,$string,$m);
    $i = 0;
    foreach($m[1] as $key=>$tempString){
        $string = str_replace($m[0][$key],$dataArray[$tempString],$string);
    }
    return $string;
} 


function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}


function col($data,$md,$lg='',$xs='',$sm='',$class=''){
	$colmd = 'col-md-'.$md.'';
	$collg = ($lg != '')?'col-md-'.$lg.'' : '';
	$colxs = ($xs != '')?'col-md-'.$xs.'' : 'col-sm-12';
	$colsm = ($sm != '')?'col-md-'.$sm.'' : 'col-sm-12';
	$return = 
	'
	<div class="'.$colmd.' '.$collg.' '.$colsm.' '.$colxs.' '.$class.'">			
	'.$data.'
	</div>
	'	;	
	return $return;	
	
}
	 function row($data){
		$return = 
		'
			<div class="row">
			'.$data.'
			</div>
		'	;	
		return $return;
	}

	function col12($data,$class='',$id=''){
		$return = 
		'

				<div class="col-md-12 col-sm-12 '.$class.'" id="'.$id.'">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}

	function col11($data,$class='',$id=''){
		$return = 
		'

				<div class="col-md-11 col-sm-12 '.$class.'" id="'.$id.'">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}

	function col10($data,$class='',$id=''){
		$return = 
		'

				<div class="col-md-10 col-sm-12 '.$class.'" id="'.$id.'">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}

	function col9($data,$class='',$id=''){
		$return = 
		'

				<div class="col-md-9 col-sm-12 '.$class.'" id="'.$id.'">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}

	function col8($data,$class='',$id=''){
		$return = 
		'

				<div class="col-md-8 col-sm-12 '.$class.'" id="'.$id.'">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}

	function col7($data,$class='',$id=''){
		$return = 
		'

				<div class="col-md-7 col-sm-12 '.$class.'" id="'.$id.'">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}

	function col6($data,$class='',$id=''){
		$return = 
		'

				<div class="col-md-6 col-sm-12 '.$class.'" id="'.$id.'">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}

	function col5($data,$class='',$id=''){
		$return = 
		'

				<div class="col-md-5 col-sm-12 '.$class.'" id="'.$id.'">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}

	function col4($data,$class='',$id=''){
		$return = 
		'

				<div class="col-md-4 col-sm-12 '.$class.'" id="'.$id.'">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}

	function col3($data,$class='',$id=''){
		$return = 
		'

				<div class="col-md-3 col-sm-12 '.$class.'" id="'.$id.'">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}

	function col2($data,$class='',$id=''){
		$return = 
		'

				<div class="col-md-2 col-sm-12 '.$class.'" id="'.$id.'">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}

	function col1($data,$class='',$id=''){
		$return = 
		'

				<div class="col-md-1 col-sm-12 '.$class.'" id="'.$id.'">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}
?>