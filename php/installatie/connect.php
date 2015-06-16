<?php
mysql_connect("localhost", "root", "");
$db_selected = mysql_select_db("rick_hondsrug");
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>