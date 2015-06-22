<?php
mysql_connect("localhost", "rick_groep1", "Welkom01");
$db_selected = mysql_select_db("rick_hondsrug");
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>