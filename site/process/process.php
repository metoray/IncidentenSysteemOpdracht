<?php
include "include/connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
 	if(isset($_POST["new_problem"]))
 	{	
	
 	
 		
		$description = mysqli_real_escape_string($con,$_POST["description"]);
		$start_date = date("Y-m-d") ;
		$employee = mysqli_real_escape_string($con,$_POST["practitioner"]);
		$status = mysqli_real_escape_string($con,$_POST["status"]);
		$insert_new_problem = "insert into probleem(status, beschrijving, begindatum, medewerker) VALUES(".$status." ,'".$description."'	, '".$start_date."', ".$employee." )";
		mysqli_query($con, $insert_new_problem ) or die(mysql_error()); 
		
		
		//Dit zoek de meest recente (deze probleem dus) op en zet het in problem_id zo komen we automatisch weer op de goede pagina.
		$get_max_id = "select max(id) from probleem ";
 		$get_max_id_result =mysqli_query($con,$get_max_id);
 		$ID =mysqli_fetch_row($get_max_id_result);
 		$ID = $ID[0];
 		
		$location = "Location: /problems/list/problem?problem_id=".$ID."";
		header($location);
	
	}
	elseif(isset($_POST["edit_problem"]))
	{	
	 	$description = mysqli_real_escape_string($con,	$_POST["description"]);
	 	$employee = mysqli_real_escape_string($con,		$_POST["practitioner"]);
		$status = mysqli_real_escape_string($con,		$_POST["status"]);
		$solution = mysqli_real_escape_string($con,		$_POST["solution"]);
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
		mysqli_query($con,$alter_problem) or die(mysql_error());
		$location = "Location: /problems/list/problem?problem_id=".$_POST["problem_id"]."";
		header($location);
	
	}
	elseif(isset($_POST["remove_incidens"]))
	{
	 		foreach($_POST["remove"] as $remove_this)
	 		{
			
				$remove_query = "update incidenten set problem_id = null where inc_id= ".$remove_this." ";
				mysqli_query($con,$remove_query);
			}
			$location = "Location: /problems/list/problem?problem_id=".$_POST["problem_id"]."";
			header($location);
	}
	elseif(isset($_POST["send_incident_user"]))
	{
			$impact = "NULL";
	 		$urgency = "NULL";
	 		$priority = "NULL";

	 		if(isset($_SESSION['answers'])){
	 			$list = AnswerList::fromArray(0,$_SESSION['answers']);
				$template = $list -> getTemplate();
				if($template){
					$impact = $template -> getImpact();
					$urgency = $template -> getUrgency();
					$priority = $template -> getPriority();
				}
	 		}

	 		$impact = mysqli_real_escape_string($con,$impact);
	 		$urgency = mysqli_real_escape_string($con,$urgency);
	 		$priority = mysqli_real_escape_string($con,$priority);
	 	
			$user			=mysqli_real_escape_string($con,$_POST["user"]);
			$discription 	=mysqli_real_escape_string($con,$_POST["description"]);	
			$software 		=mysqli_real_escape_string($con,$_POST["software"]);
			$hardware 		=mysqli_real_escape_string($con,$_POST["hardware"]);
			$start_inc		=date("Y-m-d H:i:s") ;
			
			$insert_incident = "insert into incidenten
			(		 omschrijving, start_incident			, gebruiker_id	,software_component	,status		,impact		,urgentie		,prioriteit		,hardware_id	,medewerker_id)
			VALUES 
			(			 '".$discription."','".$start_inc."', ".$user."		,".$software."		,1			,{$impact},{$urgency}	,{$priority},".$hardware."	,NULL )";
			mysqli_query($con,$insert_incident);
			$id_query 		="select max(inc_id) from incidenten";
			$id_result		=mysqli_query($con,$id_query);
			$id_row			=mysqli_fetch_row($id_result);
		//	$id 			=mysqli_insert_id($con);
			$id				=$id_row[0];

			if(isset($_SESSION['answers'])){
	 			$list = AnswerList::fromArray($id,$_SESSION['answers']);
	 			$list -> save();
	 		}

			$location= "Location:  /incidents/existing?inc_id=".$id." ";
			header($location);
	} 
	elseif(isset($_POST["send_incident_practioner"]))
	{
			
			$discription 	=mysqli_real_escape_string($con,$_POST["description"]);
			$user_id		=mysqli_real_escape_string($con,$_POST["user"]);
			$software 		=mysqli_real_escape_string($con,$_POST["software"]);
			$hardware 		=mysqli_real_escape_string($con,$_POST["hardware"]);
			$impact 		=mysqli_real_escape_string($con,$_POST["impact"]);
			$urgentie		=mysqli_real_escape_string($con,$_POST["urgentie"]);
			$prioriteit 	=mysqli_real_escape_string($con,$_POST["prioriteit"]);
			$employee 		=mysqli_real_escape_string($con,$_POST["practioner"]);
			$start_inc		=date("Y-m-d H:i:s") ;
		
			$insert_incident = "insert into incidenten
			( omschrijving		,start_incident, gebruiker_id	,software_component	,status		,impact		,urgentie		,prioriteit		,hardware_id	,medewerker_id)
			VALUES 
			(	 '".$discription."' , '".$start_inc."' ,".$user_id."		,".$software."		,2			,".$impact.",".$urgentie."	,".$prioriteit.",".$hardware."	,".$employee." )";
			echo $insert_incident;
			mysqli_query($con,$insert_incident) or die(mysqli_error());
		/*	$id_query 		="select max(inc_id) from incidenten";
			$id_result		=mysqli_query($con,$id_query);
			$id_row			=mysqli_fetch_row($con,$id_result); */
			$id 			=mysqli_insert_id($con);
		//	$id				=$id_row[0];

			if(isset($_SESSION['answers'])){
	 			$list = AnswerList::fromArray($id,$_SESSION['answers']);
	 			$list -> save();
	 		}
			
			$location= "Location: /incidents/existing?inc_id=".$id." ";
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