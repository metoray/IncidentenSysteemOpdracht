<?php
	error_reporting(0);
	include 'Robert/connect.php';

	//Vraag toevoegen knop.
	echo "<form name=\"Vraag toevoegen\" action=\"vragentoevoegen.php\" method=\"get\">";
	echo "<input type=\"submit\" value=\"Vraag toevoegen\" name=\"Vraag toevoegen\">";
	echo "</form>";

	echo "<br />";


	//Query voor de gegevens van de vraag tabel op te halen.
	$query = "SELECT id, tekst ";
	$query .= "FROM vraag";
	
	//Zet de resultaten van query vraag tabel in variabel.
	$result = mysql_query($query);

	//Zet de resultaten van de query in een associative array.
	while ($vragen_query = mysql_fetch_assoc($result)) {
		$vragen[] = array('Vraag_Id' => intval($vragen_query["id"]), 'Vraag_Tekst' => $vragen_query["tekst"],);
	}


	//Query voor de gegevens van de vraag antwoord op te halen.
	$query = "SELECT vraag_id, tekst ";
	$query .= "FROM antwoord";

	//Zet de resultaten van query antwoord tabel in variabel.
	$result = mysql_query($query);

	//Zet de resultaten van de query in een associative array.
	while ($antwoorden_query = mysql_fetch_assoc($result)) {
		$antwoorden[] = array('Vraag_Id' => intval($antwoorden_query["vraag_id"]), 'Antwoord_Tekst' => $antwoorden_query["tekst"],);
	}


	//Tabel wordt gemaakt met kolomnamen.
	echo "<table border=1><tr><td><b>Vraag ID</b></td><td><b>Vraag</b></td><td><b>Antwoorden</b></td>";

	//Loop om alle vragen en aantwoorden te tonen in de tabel.
	foreach ($vragen as $i) {
		echo "<tr><td>". $i['Vraag_Id']. "</td><td>". $i['Vraag_Tekst']. "</td>";
		
		$counter = 0;

		foreach ($antwoorden as $j) {

			if ($i['Vraag_Id'] == $j['Vraag_Id']) {

				if ($counter > 0) {
					echo "<td></td><td></td>";
				}
				
				echo "<td>". $j['Antwoord_Tekst']. "</td></tr>";
				$counter ++;
			}

		}

	}
	echo "</table>";
?>