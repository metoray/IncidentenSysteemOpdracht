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
	elseif(isset($_POST["send_incident_user"]))
	{
	 	
			$user			=$_POST["user"];
			$discription 	=$_POST["description"];	
			$software 		=$_POST["software"];
			$hardware 		=$_POST["hardware"];
			$id_query 		="select max(inc_id) from incidenten";
			$id_result		=mysql_query($id_query);
			$id_row			=mysql_fetch_row($id_result);
			$id 			=$id_row[0];
			$id++;	
			$insert_incident = "insert into incidenten
			(inc_id				, omschrijving			, gebruiker_id	,software_component	,status		,impact		,urgentie		,prioriteit		,hardware_id	,medewerker_id)
			VALUES 
			(".$id."			, '".$discription."', ".$user."		,".$software."		,1			,NULL,NULL	,NULL,".$hardware."	,NULL )";
			mysql_query($insert_incident);
			$location= "Location: edit_incident.php?inc_id=".$id." ";
			header($location);
	} 
	elseif(isset($_POST["send_incident_practioner"]))
	{
			
			$discription 	=$_POST["description"];
			$user			=$_POST["user"];
			$software 		=$_POST["software"];
			$hardware 		=$_POST["hardware"];
			$impact 		=$_POST["impact"];
			$urgentie		=$_POST["urgentie"];
			$prioriteit 	=$_POST["prioriteit"];
			$employee 		=$_POST["practioner"];
			$id_query 		="select max(inc_id) from incidenten";
			$id_result		=mysql_query($id_query);
			$id_row			=mysql_fetch_row($id_result);
			$id 			=$id_row[0];
			$id++;
			$insert_incident = "insert into incidenten
			(inc_id			, omschrijving		, gebruiker_id	,software_component	,status		,impact		,urgentie		,prioriteit		,hardware_id	,medewerker_id)
			VALUES 
			(".$id."		, '".$discription."', ".$user."		,".$software."		,2			,".$impact.",".$urgentie."	,".$prioriteit.",".$hardware."	,".$employee." )";
			mysql_query($insert_incident);
			$location= "Location: edit_incident.php?inc_id=".$id." ";
			header($location);
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