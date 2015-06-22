<?php
	error_reporting(0);
	include 'include/connect.php';

	//Query voor de gegevens van de incidenten tabel op te halen.
	$query = "SELECT * ";
	$query .= "FROM incidenten";
	
	//Zet de resultaten van query incidenten tabel in variabel.
	$result = mysql_query($query);

	//Zet de resultaten van de query in een associative array.
	while ($incidenten_query = mysql_fetch_assoc($result)) {
		$incidenten[] = array('IncidentId' => intval($incidenten_query["inc_id"]), 'StartDatum' => $incidenten_query["start_incident"], 'Omschrijving' => $incidenten_query["Omschrijving"], 'Workaround' => $incidenten_query["Workaround"], 'GebruikerId' => $incidenten_query["gebruiker_id"], 'EindDatum' => $incidenten_query["eind_incident"], 'SoftwareComponent' => $incidenten_query["software_component"], 'IncidentTypeId' => intval($incidenten_query["incident_type_id"]), 'Impact' => intval($incidenten_query["impact"]), 'Urgentie' => intval($incidenten_query["urgentie"]), 'Prioriteit' => intval($incidenten_query["prioriteit"]), 'HardwareId' => intval($incidenten_query["hardware_id"]), 'MedewerkerId' => intval($incidenten_query["medewerker_id"]));
	}

	//Tabel wordt gemaakt met kolomnamen.
	echo "<table border=1><tr><td><b>Gebruiker</b></td><td><b>Behandelaar</b></td><td><b>Omschrijving</b></td><td><b>Impact</b></td><td><b>Urgentie</b></td><td><b>Prioriteit</b></td><td><b>Status</b></td><td><b>Start datum + tijd</b></td><td><b>Eind datum + tijd</b></td><td><b>Toevoegen aan probleem</b></td></tr>";

	echo "<form action=\"#\" method=\"GET\">";
	foreach ($incidenten as $i) {

		
		if (!empty($i['GebruikerId'])) {
			//Query voor de naam van de gebruiker.
			$query = "SELECT naam ";
			$query .= "FROM gebruikers ";
			$query .= "WHERE gebruiker_id = ". $i['GebruikerId'];
			
			//Zet de resultaten van query.
			$result = mysql_query($query);

			//Zet de resultaten van de query in een associative array.
			while ($gebruikers_query = mysql_fetch_assoc($result)) {
				$Gebruikernaam = $gebruikers_query["naam"];
			}
		}
		else{
			$Gebruikernaam = "Geen gebruiker gekoppeld!";
		}

		if (!empty($i['MedewerkerId'])) {
			//Query voor de naam van de medewerker.
			$query = "SELECT naam ";
			$query .= "FROM gebruikers ";
			$query .= "WHERE gebruiker_id = ". $i['MedewerkerId'];
			
			//Zet de resultaten van query.
			$result = mysql_query($query);

			//Zet de resultaten van de query in een associative array.
			while ($gebruikers_query = mysql_fetch_assoc($result)) {
				$Medewerkernaam = $gebruikers_query["naam"];
			}
		}
		else{
			$Medewerkernaam = "Geen medewerker gekoppeld!";
		}

		$test = $i['IncidentId'];

		echo "<tr><td>". $Gebruikernaam. "</td><td>". $Medewerkernaam. "</td><td>". $i['Omschrijving']. "</td><td>". $i['Impact']. "</td><td>". $i['Urgentie']. "</td><td>". $i['Prioriteit']. "</td><td>". $i['Status']. "</td><td>". $i['StartDatum']. "</td><td>". $i['EindDatum']. "</td><td><input type=\"checkbox\" name=\"" . $i['IncidentId'] . "\" value=\"Escaleer\" />Escaleer</td></tr>";
	}

	echo "</table>";
	echo "<input type=\"submit\" value=\"Toevoegen aan problemen\" name=\"Toevoegen\">";
	echo "</from>";
?>