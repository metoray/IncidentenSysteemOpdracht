<?php
	error_reporting(0);
	include 'Robert/connect.php';

	//Vraag toevoegen knop.
	echo "<form name=\"Vraag toevoegen\" action=\"vragentoevoegen.php\" method=\"get\">";
	echo "<input type=\"submit\" value=\"Vraag toevoegen\" name=\"Vraag toevoegen\">";
	echo "</form>";

	echo "<br />";


	//Query voor de gegevens van de vraag tabel op te halen.
	$query = "SELECT * ";
	$query .= "FROM vraag";
	
	//Zet de resultaten van query vraag tabel in variabel.
	$result = mysql_query($query);

	//Zet de resultaten van de query in een associative array.
	while ($vragen_query = mysql_fetch_assoc($result)) {
		$vragen[] = array('Vraag_Id' => intval($vragen_query["id"]), 'Vraag_Tekst' => $vragen_query["tekst"],);
	}


	//Query voor de gegevens van de vraag antwoord op te halen.
	$query = "SELECT * ";
	$query .= "FROM antwoord";

	//Zet de resultaten van query antwoord tabel in variabel.
	$result = mysql_query($query);

	//Zet de resultaten van de query in een associative array.
	while ($antwoorden_query = mysql_fetch_assoc($result)) {
		$antwoorden[] = array('Antwoord_Id' => intval($antwoorden_query["id"]), 'Vraag_Id' => intval($antwoorden_query["vraag_id"]), 'Antwoord_Tekst' => $antwoorden_query["tekst"], 'VervolgvraagId' => intval($antwoorden_query["vervolg_vraag_id"]), 'DefaultTicketId' => intval($antwoorden_query["default_ticket_id"]));
	}


	//Tabel wordt gemaakt met kolomnamen.
	echo "<table border=1><tr><td><b>Vraag ID</b></td><td><b>Vraag</b></td><td><b>Antwoorden</b></td>";

	//Loop om alle vragen en aantwoorden te tonen in de tabel. Ook kan er worden geklikt op de vragen en antwoorden. Er wordt dan naar de pagina Q&A verwezen met een GET met de waarde van id.
	foreach ($vragen as $i) {
		echo "<tr><td>". "<a href=\"Q&A.php?vraagid=". $i['Vraag_Id']. "\">". $i['Vraag_Id']. "</a>". "</td><td>". "<a href=\"Q&A.php?vraagid=". $i['Vraag_Id']. "\">". $i['Vraag_Tekst']. "</a>". "</td>";
		
		$counter = 0;

		foreach ($antwoorden as $j) {

			if ($i['Vraag_Id'] == $j['Vraag_Id']) {

				if ($counter > 0) {
					echo "<td></td><td></td>";
				}
				
				echo "<td>". "<a href=\"Q&A.php?antwoordid=". $j['Antwoord_Id']. "\">". $j['Antwoord_Tekst']. "</a>". "</td></tr>";
				$counter ++;
			}

		}

	}
	echo "</table>";
?>