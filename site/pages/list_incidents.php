<?php
$connect			=new mysqli('localhost', 'root', '', 'rick_hondsrug');
$db =mysql_select_db( "rick_hondsrug");
$_session_start();
$user = $_SESSION["user"] ->getID();
//$user_id= 10;
$incidents_query = "select * from incidenten where gebruiker_id = ".$user_id."";
$incidents_result = mysqli_query($connect,$incidents_query );


echo "<table border=1>";
echo "<tr>";
	echo "<td>";
		echo "Gebruiker";
	echo "</td>";
	echo "<td>";
		echo "Behandelaar";
	echo "</td>";
	echo "<td>";
		echo "Incident code";
	echo "</td>";
	echo "<td>";
		echo "Start incident";
	echo "</td>";
	echo "<td>";
		echo "Eind incident";
	echo "</td>";
	echo "<td>";
		echo "omschrijving";
	echo "</td>";
	echo "<td>";
		echo "Workaround";
	echo "</td>";

while($incidents_row = mysqli_fetch_assoc($incidents_result )	)
{
	echo "<tr>";
		echo "<td>";
			$user_name_query 	="select naam from gebruikers where gebruiker_id = ".$incidents_row["gebruiker_id"]."";
			$user_name_result 	=mysqli_query($connect, $user_name_query);
			$user_name_row		=mysqli_fetch_assoc($user_name_result);
			echo $user_name_row["naam"];	
		echo "</td>";
		echo "<td>";
			$practitoner_name_query	="select naam from gebruikers where gebruiker_id =".$incidents_row["medewerker_id"]."";
			$practitoner_name_result=mysqli_query($connect, $practitoner_name_query);
			$practitoner_row		=mysqli_fetch_assoc($practitoner_name_result);
			echo $practitoner_row["naam"];
		echo "</td>";
		echo "<td>";
			echo "<a href=existing_incident.php?".$incidents_row["inc_id"].">";
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