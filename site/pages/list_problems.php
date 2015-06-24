<?php

include "include/connect.php";
/*$_session_start();
$user = $_SESSION["user"] ->getID(); */
$user_id= 10;
$problems_query = "select * from probleem";
$problems_result = mysqli_query($con,$problems_query );


echo "<table border=1>";
echo "<tr>";
	echo "<td>";
		echo "ID";
	echo "</td>";
	echo "<td>";
		echo "Behandelaar";
	echo "</td>";
	echo "<td>";
		echo "Status";
	echo "</td>";
	echo "<td>";
		echo "Beschrijving";
	echo "</td>";
	echo "<td>";
		echo "Oplossing";
	echo "</td>";
	echo "<td>";
		echo "Begin Datum";
	echo "</td>";
	echo "<td>";
		echo "Eind datum";
	echo "</td>";
echo "</tr>";	

while($problems_row = mysqli_fetch_assoc($problems_result )	)
{
	echo "<tr>";
		echo "<td>";
			echo "<a href=existing_problem.php?problem_id=".$problems_row["id"].">	";
			echo $problems_row["id"];	
			echo "</a>";
		echo "</td>";
		echo "<td>";
			$practitoner_name_query	="select naam from gebruikers where gebruiker_id =".$problems_row["medewerker"]."";
			$practitoner_name_result=mysqli_query($con, $practitoner_name_query);
			$practitoner_row		=mysqli_fetch_assoc($practitoner_name_result);
			echo $practitoner_row["naam"];
		echo "</td>";
		echo "<td>";
			$status_query = "select status from statussen_probleem where id = ".$problems_row["status"]."";
			$status_result= mysqli_query($con, $status_query);
			$status_row = mysqli_fetch_assoc($status_result);
			echo $status_row["status"];
		echo "</td>";
		echo "<td>";
			echo $problems_row["beschrijving"];
		echo "</td>";
		echo "<td>";
			echo $problems_row["oplossing"];
		echo "</td>";
		echo "<td>";
			echo $problems_row["begindatum"];
		echo "</td>";
		echo "<td>";
			echo $problems_row["einddatum"];
		echo "</td>";
	echo "</tr>";
}

echo "</table>";
?>