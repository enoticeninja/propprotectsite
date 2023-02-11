
<?php

   $result = '<table border = "2"> <tr> <th colspan="2">Host</th> <th colspan="5">Utilization</th> </tr> <tr>';
   $result .= '<td  colspan="2">Dev[.88]</td> </td><td> '; 
   $data = explode (' ', file_get_contents('http://sit.enoticeninja.com/lawyer/host_space.php'));
   $result .= implode (' , ', $data);
   $result .= '</td> </tr> <tr> <td colspan="2">UAT  </td> <td colspan = "5">';
   $data = explode (' ',  exec('df -k .'));
   $result .= implode (' , ', $data);

   $result .= '</td> </tr> <tr> <td colspan="2">LIVE[.29]</td> <td colspan = "5">';
   $data = explode (' ',  file_get_contents('http://65.1.44.29/lawyer/host_space.php'));
   $result .= implode (' , ', $data);
   
   $result .= '</td> </tr> <table>';
   
   $data = file_get_contents('http://uat.enoticeninja.com:5000/');
   if ($data == 'Hello World') {
      $result .= '</br><table><tr><td> Today notices and notice count API server is :  ON </td></tr></table>';
   } else { 
      $result .= '</br><table><tr><td>  Today notices and notice count API Server is:   OFF,  immediate attention required on python process  </td></tr></table>';
   }
   

   echo $result;
?>
