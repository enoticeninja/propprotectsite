<?php

	function get_permission_action_types($key=''){

		$arr['view'] = 'View';
		$arr['edit'] = 'Edit';
		$arr['delete'] = 'Delete';
		if($key == ''){
			$temp = $arr;
		}
		else{
			$temp = $arr[$key];
		}
		return $temp;	
	}
	
	function get_permission_types($key=''){

		$arr['menu'] = 'Menu';
		$arr['module'] = 'Module';
		if($key == ''){
			$temp = $arr;
		}
		else{
			$temp = $arr[$key];
		}
		return $temp;	
	}

	function get_permission_groups($key=''){
		global $db_conx;
		$temp = array();
		$arr = array();
		$sql = "SELECT * FROM perm_groups WHERE status='active'";
		$query = mysqli_query($db_conx, $sql);
		while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
				$arr[$row['id']] = $row['name'];
			
		}
		if($key == ''){
			$temp = $arr;
		}
		else{
			$temp = $arr[$key];
		}	
		return $temp;
	}

		function get_permission_modules($key=''){
		global $db_conx;
		$temp = array();
		$arr = array();
		$sql = "SELECT * FROM perm_modules WHERE status='active'";
		$query = mysqli_query($db_conx, $sql);
		while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
				$arr[$row['id']] = $row['name'];
			
		}
		if($key == ''){
			$temp = $arr;
		}
		else{
			$temp = $arr[$key];
		}	
		return $temp;
	}

?>