<?php
//mysql_connect("localhost", "rick_groep1", "Welkom01");
//$db_selected = mysql_select_db("rick_hondsrug");

$dbhost = "localhost";  
$dbuser = "rick_groep1";  
$dbpass = "Welkom01";  
$dbname = "rick_hondsrug";  

$err_level = error_reporting(0);  
$con=mysqli_connect("$dbhost","$dbuser","$dbpass","$dbname");
error_reporting($err_level); 

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>