<?php
include_once 'common_bootstrap.php';
include_once 'bootstrap.php';
include_once("common.php");
$username = $_SESSION[get_session_values('username')];
$return = array();
$form = new Form();
function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}
///////////////AJAX MODAL FORMS ///////////////////////
//////////////// NEW CASE FORM    /////////////////////////
if($_POST['type'] == 'all') {
/*     $sql = "SELECT COUNT(users.id) AS num
            FROM users
            WHERE users.status='active'";
    $query = mysqli_query($db_conx,$sql);
    $row = mysqli_fetch_array($query,MYSQLI_ASSOC);
    $total_doctors = $row['num'];
	
    $sql = "SELECT COUNT(users.id) AS num
            FROM users
            WHERE users.status='active'";
    $query = mysqli_query($db_conx,$sql);
    $row = mysqli_fetch_array($query,MYSQLI_ASSOC);
    $total_users = $row['num'];

    $sql = "SELECT COUNT(users.id) AS num
            FROM users
            WHERE users.status='active'";
    $query = mysqli_query($db_conx,$sql);
    $row = mysqli_fetch_array($query,MYSQLI_ASSOC);
    $total_products = $row['num'];

    $sql = "SELECT COUNT(users.id) AS num
            FROM users
            WHERE users.status='active'";
    $query = mysqli_query($db_conx,$sql);
    $row = mysqli_fetch_array($query,MYSQLI_ASSOC); */
    $total_cities = mt_rand(10,100);
    $total_doctors = mt_rand(10,100);
    $total_users = mt_rand(10,100);
    $total_products = mt_rand(10,100);
    
    $return['piestats'][] = array('affiliate'=>'Monday','label'=>'Total Time','count'=>$total_doctors,'color'=>random_color());    
    $return['piestats'][] = array('affiliate'=>'Tuesday','label'=>'Total Time','count'=>$total_users,'color'=>random_color());    
    $return['piestats'][] = array('affiliate'=>'Wednesday','label'=>'Total Time','count'=>$total_cities,'color'=>random_color());    
    $return['piestats'][] = array('affiliate'=>'Thursday','label'=>'Total Time','count'=>$total_products,'color'=>random_color());
    $return['piestats'][] = array('affiliate'=>'Friday','label'=>'Total Time','count'=>mt_rand(10,100),'color'=>random_color());
    $return['piestats'][] = array('affiliate'=>'Saturday','label'=>'Total Time','count'=>mt_rand(10,100),'color'=>random_color());
    $return['piestats'][] = array('affiliate'=>'Sunday','label'=>'Total Time','count'=>mt_rand(10,100),'color'=>random_color());
    
    $return['donutstats'][] = array('affiliate'=>'Apple','label'=>'Total Time','count'=>$total_doctors,'color'=>random_color());    
    $return['donutstats'][] = array('affiliate'=>'Microsoft','label'=>'Total Time','count'=>$total_users,'color'=>random_color());    
    $return['donutstats'][] = array('affiliate'=>'Nokia','label'=>'Total Time','count'=>$total_cities,'color'=>random_color());    
    $return['donutstats'][] = array('affiliate'=>'Samsung','label'=>'Total Time','count'=>$total_products,'color'=>random_color());			
    $return['donutstats'][] = array('affiliate'=>'Amazon','label'=>'Total Time','count'=>mt_rand(10,100),'color'=>random_color());
    $return['donutstats'][] = array('affiliate'=>'Flipkart','label'=>'Total Time','count'=>mt_rand(10,100),'color'=>random_color());
    $return['donutstats'][] = array('affiliate'=>'Google','label'=>'Total Time','count'=>mt_rand(10,100),'color'=>random_color());

			
					
	$return['ajaxmessage'] = 'success';			
}

echo json_encode($return);
?>
