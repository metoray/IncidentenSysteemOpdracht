<?php
include "connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
 	if(isset($_POST["new_problem"]))
 	{	
	
 		$get_max_id = "select max(id) from probleem ";
 		$get_max_id_result =mysql_query($get_max_id);
 		$ID =mysql_fetch_row($get_max_id_result);
 		$ID = $ID[0];
 		
 		if($ID == "")
 		{ $ID = 1;}
		else {$ID++; }
		
	
		$description = mysql_real_escape_string($_POST["description"]);
		$start_date = date("Y-m-d") ;
		$employee = mysql_real_escape_string($_POST["practitioner"]);
		$status = mysql_real_escape_string($_POST["status"]);
		$insert_new_problem = "insert into probleem(id,status, beschrijving, begindatum, medewerker) VALUES(".$ID.",".$status." ,'".$description."'	, '".$start_date."', ".$employee." )";
		mysql_query($insert_new_problem ) or die(mysql_error()); 
		$location = "Location: existing_problem.php?problem_id=".$ID."";
		header($location);
	
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
			$alter_problem ="update probleem set status = ".$status.", beschrijving = '".$description."' , oplossing = '".$solution."', einddatum = '".$end_date."',  medewerker = ".$employee." where id=".$_POST["problem_id"]." ";
			
		}
		else
		{
			$alter_problem ="update probleem set beschrijving = '".$description."' , oplossing = '".$solution."', status = ".$status.", medewerker = ".$employee." where id=".$_POST["problem_id"]."";
			
		}
		echo $alter_problem;
		mysql_query($alter_problem) or die(mysql_error());
		$location = "Location: existing_problem.php?problem_id=".$_POST["problem_id"]."";
		header($location);
	
	}
	elseif(isset($_POST["remove_incidens"]))
	{
	 		foreach($_POST["remove"] as $remove_this)
	 		{
			
				$remove_query = "update incidenten set problem_id = null where inc_id= ".$remove_this." ";
				mysql_query($remove_query);
			}
			header('Location: existing_problem.php');
	}
	else
	{
		echo "fail";
	}
}else
{
	echo "Deze pagina is niet bedoelt voor u";
}

?>