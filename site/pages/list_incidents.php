<?php
include "include/connect.php";
$user_id = $_SESSION["user"] -> getID(); 
$incidents_query = "select * from incidenten where gebruiker_id = ".$user_id."";
$incidents_result = mysqli_query($con,$incidents_query );


echo "<table class=\"table table-striped\">";
echo "<tr>";
	echo "<thead>";
		echo "<th>";
			echo "Gebruiker";
		echo "</th>";
		echo "<th>";
			echo "Behandelaar";
		echo "</th>";
		echo "<th>";
			echo "Incident code";
		echo "</th>";
		echo "<th>";
			echo "Start incident";
		echo "</th>";
		echo "<th>";
			echo "Eind incident";
		echo "</th>";
		echo "<th>";
			echo "omschrijving";
		echo "</th>";
		echo "<th>";
			echo "Workaround";
		echo "</th>";
	echo "</thead>";
echo "</tr>";

while($incidents_row = mysqli_fetch_assoc($incidents_result )	)
{
	echo "<tr>";
		echo "<td>";
			$user_name_query 	="select naam from gebruikers where gebruiker_id = ".$incidents_row["gebruiker_id"]."";
			$user_name_result 	=mysqli_query($con, $user_name_query);
			$user_name_row		=mysqli_fetch_assoc($user_name_result);
			echo $user_name_row["naam"];	
		echo "</td>";
		echo "<td>";
			$practitoner_name_query	="select naam from gebruikers where gebruiker_id =".$incidents_row["medewerker_id"]."";
			$practitoner_name_result=mysqli_query($con, $practitoner_name_query);
			$practitoner_row		=mysqli_fetch_assoc($practitoner_name_result);
			echo $practitoner_row["naam"];
		echo "</td>";
		echo "<td>";
			echo "<a href=/incidents/existing?inc_id=".$incidents_row["inc_id"].">";
			echo $incidents_row["inc_id"];
			echo "</a>";
		echo "</td>";
		echo "<td>";
			echo $incidents_row["start_incident"];
		echo "</td>";
		echo "<td>";
			echo $incidents_row["eind_incident"];
		echo "</td>";
		echo "<td>";
			echo $incidents_row["Omschrijving"];
		echo "</td>";
		echo "<td>";
			echo $incidents_row["Workaround"];
		echo "</td>";
	echo "</tr>";
}

echo "</tr>";
echo "</table>";
?>