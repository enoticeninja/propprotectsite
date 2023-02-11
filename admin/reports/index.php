<?php
  if ($handle = opendir('.')) {
    while (false !== ($file = readdir($handle))) {
      if ($file != "." && $file != "..") {
        if ($file != "index.php")
           $thelist .= '<li><a href="'.$file.'">'.$file.'</a></li>';
      }
    }
    closedir($handle);
  }
?>
<img src = "../../lawyer/website/images/512x512.png" height = "100px" width = "100px">
</br> <i>its "criminal offense" to access this website without authorization. If you are not authorized person, then leave this site immediately. </i></br></br>
Management Reports (file format includes UTC timezone  DD-MM-YYYY_HH-MM):</br>
<ul><?php echo $thelist; ?></ul>
