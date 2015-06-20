<?php
include "connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{	echo "test 1";
	print_r($_POST);
	echo "<br />";

 	if(isset($_POST["new_problem"]))
 	{	
		echo "test 2";
 		$get_max_id = "select max(id) from probleem ";
 		$get_max_id_result =mysql_query($get_max_id);
 		$ID =mysql_fetch_row($get_max_id_result);
 		$ID = $ID[0];
 		
 		if($ID == "")
 		{ $ID = 1;}
		else {$ID++; }
		
		echo "test 3";
		$description = mysql_real_escape_string($_POST["description"]);
		$start_date = date("Y-m-d") ;
		$employee = mysql_real_escape_string($_POST["practitioner"]);
		$status = mysql_real_escape_string($_POST["status"]);
		$insert_new_problem = "insert into probleem(id,status, beschrijving, begindatum, medewerker) VALUES(".$ID.",".$status." ,'".$description."'	, '".$start_date."', ".$employee." )";
		//mysql_query($insert_new_problem ) or die(mysql_error());
		echo $insert_new_problem;
		echo "final test";
	}
	elseif(isset($_POST["edit_problem"]))
	{	
	 	$description = mysql_real_escape_string($_POST["description"]);
	 	$employee = mysql_real_escape_string($_POST["practitioner"]);
		$status = mysql_real_escape_string($_POST["status"]);
		$solution = mysql_real_escape_string($_POST["solution"]);
		$end_date = date("Y-m-d") ;
		if($status == 3)
		{
			$alter_problem ="update probleem set status = ".$status.", beschrijving = '".$description."' , oplossing = '".$solution."', eind_datum = '".$end_date."',  medewerker = ".$employee." ";
		}
		else
		{
			$alter_problem ="update probleem set beschrijving = '".$description."' , oplossing = '".$solution."', status = ".$status.", medewerker = ".$employee."
		";
		}
		mysql_query($alter_problem);
	}
	echo "test 4";
}else
{
	echo "Deze pagina is niet bedoelt voor u";
}

?>