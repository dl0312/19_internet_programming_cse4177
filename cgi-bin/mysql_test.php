<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

echo ("MySQL - PHP Connect Test <br/>");
$hostname = "localhost";
$username = "csjhnang";
$password = "dbpass";
$dbname = "dbjhnang";

$connect = mysqli_connect($hostname, $username, $password) or die("DB Connection Failed");
//$result = mysql_select_db($dbname,$connect);
 
if($connect) {
 echo("MySQL Server Connect Success!");
}
else {
 echo("MySQL Server Connect Failed!");
}
 
mysqli_close($connect);
 
?>

