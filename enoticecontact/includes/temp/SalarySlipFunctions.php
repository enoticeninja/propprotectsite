<?php
include_once("php_includes/db_conx.php");


function Arrears($username, $month, $year)
{
global $db_conx;
$sqldept = "SELECT department FROM users WHERE username='$username'";
$querydept = mysqli_query($db_conx, $sqldept);
$rowdept = mysqli_fetch_array($querydept, MYSQLI_ASSOC);
$dept = $rowdept['department']; 

if ($dept == 'Tech' || $dept == 'cs') {
$monthwanted = date('m', strtotime(date(''.$year.'-'.$month.'')." -1 month"));
$yearwanted = date('Y', strtotime(date(''.$year.'-'.$month.'')." -1 month"));
}
else {
$monthwanted = date('m', strtotime(date(''.$year.'-'.$month.'')." -2 month"));
$yearwanted = date('Y', strtotime(date(''.$year.'-'.$month.'')." -2 month"));
}
$sql = "SELECT arrears FROM incentives WHERE username='$username' AND month='$month' AND year='$year'";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
$arrears = $row['arrears'];

return $arrears;
}

function CashIncentive($username, $month, $year)
{
global $db_conx;
$sqldept = "SELECT department FROM users WHERE username='$username'";
$querydept = mysqli_query($db_conx, $sqldept);
$rowdept = mysqli_fetch_array($querydept, MYSQLI_ASSOC);
$dept = $rowdept['department']; 

if ($dept == 'Tech' || $dept == 'cs') {
$monthwanted = date('m', strtotime(date(''.$year.'-'.$month.'')." -1 month"));
$yearwanted = date('Y', strtotime(date(''.$year.'-'.$month.'')." -1 month"));
}
else {
$monthwanted = date('m', strtotime(date(''.$year.'-'.$month.'')." -2 month"));
$yearwanted = date('Y', strtotime(date(''.$year.'-'.$month.'')." -2 month"));
}
$sql = "SELECT cash FROM incentives WHERE username='$username' AND month='$month' AND year='$year'";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
$cashincentive = $row['cash'];

return $cashincentive;
}

function MonthlyIncentive($username, $month, $year)
{
global $db_conx;

$sqldept = "SELECT department FROM users WHERE username='$username'";
$querydept = mysqli_query($db_conx, $sqldept);
$rowdept = mysqli_fetch_array($querydept, MYSQLI_ASSOC);
$dept = $rowdept['department']; 

if ($dept == 'Tech' || $dept == 'cs') {
$monthwanted = date('m', strtotime(date(''.$year.'-'.$month.'')." -1 month"));
$yearwanted = date('Y', strtotime(date(''.$year.'-'.$month.'')." -1 month"));
}
else {
$monthwanted = date('m', strtotime(date(''.$year.'-'.$month.'')." -2 month"));
$yearwanted = date('Y', strtotime(date(''.$year.'-'.$month.'')." -2 month"));
}

$sql = "SELECT monthly FROM incentives WHERE username='$username' AND month='$month' AND year='$year'";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
$monthlyincentive = $row['monthly'];


return $monthlyincentive;
}

function AdvanceLoan($username, $month, $year)
{
global $db_conx;
$monthwanted = date('m', strtotime(date(''.$year.'-'.$month.'')." -1 month"));
$yearwanted = date('Y', strtotime(date(''.$year.'-'.$month.'')." -1 month"));
$sql = "SELECT loan FROM incentives WHERE username='$username' AND month='$month' AND year='$year'";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
$loan = $row['loan'];


return $loan;
}

function OtDays($username, $month, $year)
{
global $db_conx;
$sql = "SELECT otdays FROM incentives WHERE username='$username' AND month='$month' AND year='$year'";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
$otdays = $row['otdays'];


return $otdays;
}

function CashedLeaves($username, $month, $year)
{
global $db_conx;
$sql = "SELECT cashedleaves FROM incentives WHERE username='$username' AND month='$month' AND year='$year'";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
$cashedleaves = $row['cashedleaves'];
return $cashedleaves;
}

function Tax($username, $month, $year)
{
global $db_conx;
$sql = "SELECT tax FROM incentives WHERE username='$username' AND month='$month' AND year='$year'";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
$tax = $row['tax'];
return $tax;
}
?>