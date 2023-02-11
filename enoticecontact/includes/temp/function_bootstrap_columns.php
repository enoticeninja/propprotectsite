<?php 
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

function col($data,$md,$lg='',$xs='',$sm=''){
	$colmd = 'col-md-'.$md.'';
	$collg = ($lg != '')?'col-md-'.$lg.'' : '';
	$colxs = ($xs != '')?'col-md-'.$xs.'' : 'col-sm-12';
	$colsm = ($sm != '')?'col-md-'.$sm.'' : 'col-sm-12';
	$return = 
	'
	<div class="'.$colmd.' '.$collg.' '.$colsm.' '.$colxs.'">			
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

	function col12($data){
		$return = 
		'

				<div class="col-md-12 col-sm-12">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}
	function col11($data){
		$return = 
		'

				<div class="col-md-11 col-sm-12">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}
	function col10($data){
		$return = 
		'

				<div class="col-md-10 col-sm-12">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}
	function col9($data){
		$return = 
		'

				<div class="col-md-9 col-sm-12">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}	
	function col8($data){
		$return = 
		'

				<div class="col-md-8 col-sm-12">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}
	
		 function col7($data){
		$return = 
		'

				<div class="col-md-7 col-sm-12">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}
	
	
	 function col6($data){
		$return = 
		'

				<div class="col-md-6 col-sm-12">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}


	 function col5($data){
		$return = 
		'

				<div class="col-md-5 col-sm-12">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}

	 function col4($data){
		$return = 
		'

				<div class="col-md-4 col-sm-12">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}

	 function col3($data){
		$return = 
		'

				<div class="col-md-3 col-sm-12">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}	

	 function col2($data){
		$return = 
		'

				<div class="col-md-2 col-sm-12">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}		

	 function col1($data){
		$return = 
		'

				<div class="col-md-1 col-sm-12">			
				'.$data.'
				</div>

		'	;	
		return $return;
	}	
?>