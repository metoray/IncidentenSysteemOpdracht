<?php
	include 'include/connect.php';

	//Query voor de gegevens van de incidenten tabel op te halen.
	

	
	//Zet de resultaten van query incidenten tabel in variabel.
	
	$user_id=$_SESSION["user"]->id;
	$rol_id_query 	= "select rol_id from gebruikers where ".$user_id."";
	$rol_id_result	= mysql_query($rol_id_query);
	$row_id_row		= mysql_fetch_row($rol_id_result);

	$rol_id = $row_id_row[0];
	
	//Controleert wat voor gebruiker op de pagina is.  Alles behalve 1 is een behandelaar
	if($rol_id != 1){		/* vooor bebehandelaar dus
		Query voor de gegevens van de incidenten tabel op te halen. */
		$query = "SELECT * ";
		$query .= "FROM incidenten";
		$query .= " WHERE medewerker_id = ".$_SESSION["user"]->id ;
		$result = mysqli_query($con, $query);
	//Zet de resultaten van de query in een associative array.
	
	
	while ($incidenten_query = mysqli_fetch_assoc($result)) {
		$incidenten[] = array('IncidentId' => intval($incidenten_query["inc_id"]), 'StartDatum' => $incidenten_query["start_incident"], 'Omschrijving' => $incidenten_query["Omschrijving"], 'Workaround' => $incidenten_query["Workaround"], 'GebruikerId' => $incidenten_query["gebruiker_id"], 'EindDatum' => $incidenten_query["eind_incident"], 'SoftwareComponent' => $incidenten_query["software_component"], 'Impact' => intval($incidenten_query["impact"]), 'Urgentie' => intval($incidenten_query["urgentie"]), 'Prioriteit' => intval($incidenten_query["prioriteit"]), 'HardwareId' => intval($incidenten_query["hardware_id"]), 'MedewerkerId' => intval($incidenten_query["medewerker_id"]),'status' => $incidenten_query["status"]);
	}

	//Tabel wordt gemaakt met kolomnamen.
	echo "<table border=1><tr><td><b>Gebruiker</b></td><td><b>Behandelaar</b></td><td><b>Omschrijving</b></td><td><b>Impact</b></td><td><b>Urgentie</b></td><td><b>Prioriteit</b></td><td><b>Status</b></td><td><b>Start datum + tijd</b></td><td><b>Eind datum + tijd</b></td></tr>";

	echo "<form action=\"#\" method=\"GET\">";
	foreach ($incidenten as $i) {

		
		if (!empty($i['GebruikerId'])) {
			//Query voor de naam van de gebruiker.
			$query = "SELECT naam ";
			$query .= "FROM gebruikers ";
			$query .= "WHERE gebruiker_id = ". $i['GebruikerId'];
			
			//Zet de resultaten van query.
			$result = mysqli_query($con, $query);

			//Zet de resultaten van de query in een associative array.
			while ($gebruikers_query = mysqli_fetch_assoc($result)) {
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
			$result = mysqli_query($con, $query);

			//Zet de resultaten van de query in een associative array.
			while ($gebruikers_query = mysqli_fetch_assoc($result)) {
				$Medewerkernaam = $gebruikers_query["naam"];
			}
		}
		else{
			$Medewerkernaam = "Geen medewerker gekoppeld!";
		}

		$test = $i['IncidentId'];

		echo "<tr><td>". "<a href=\"incidentinformation.php?IncidentId=". $i['IncidentId']. "\">" . $i['IncidentId']."</a>"."</td><td>". $Gebruikernaam. "</td><td>". $Medewerkernaam. "</td><td>". $i['Omschrijving']. "</td><td>". $i['Impact']. "</td><td>". $i['Urgentie']. "</td><td>". $i['Prioriteit']. "</td><td>". $i['status']. "</td><td>". $i['StartDatum']. "</td><td>". $i['EindDatum']. "</td></tr>";
	}
	}else{	//vooor gebruikers 
	 		$query = "SELECT * ";
			$query .= "FROM incidenten";
			$query .= " WHERE gebruiker_id = ".$_SESSION["user"]->id ;
	 		$result = mysqli_query($con, $query);
	 		
		//Zet de resultaten van de query in een associative array.
		while ($incidenten_query = mysqli_fetch_assoc($result)) {
		$incidenten[] = array('IncidentId' => intval($incidenten_query["inc_id"]), 'StartDatum' => $incidenten_query["start_incident"], 'Omschrijving' => $incidenten_query["Omschrijving"], 'Workaround' => $incidenten_query["Workaround"], 'GebruikerId' => $incidenten_query["gebruiker_id"], 'EindDatum' => $incidenten_query["eind_incident"], 'SoftwareComponent' => $incidenten_query["software_component"], 'Impact' => intval($incidenten_query["impact"]), 'Urgentie' => intval($incidenten_query["urgentie"]), 'Prioriteit' => intval($incidenten_query["prioriteit"]), 'HardwareId' => intval($incidenten_query["hardware_id"]), 'MedewerkerId' => intval($incidenten_query["medewerker_id"]),'status' => $incidenten_query["status"]);
	}

		//Tabel wordt gemaakt met kolomnamen.
		echo "<table border=1><tr><td><b>Gebruiker</b></td><td><b>Behandelaar</b></td><td><b>Omschrijving</b></td><td><b>Status</b></td><td><b>Start datum + tijd</b></td><td><b>Eind datum + tijd</b></td></tr>";

		
		foreach ($incidenten as $i) {

		
			if (!empty($i['GebruikerId'])) {
				//Query voor de naam van de gebruiker.
				$query = "SELECT naam ";
				$query .= "FROM gebruikers ";
				$query .= "WHERE gebruiker_id = ". $i['GebruikerId'];
			
				//Zet de resultaten van query.
				$result = mysqli_query($con, $query);

				//Zet de resultaten van de query in een associative array.
				while ($gebruikers_query = mysqli_fetch_assoc($result)) {
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
				$result = mysqli_query($con, $query);

				//Zet de resultaten van de query in een associative array.
				while ($gebruikers_query = mysqli_fetch_assoc($result)) {
					$Medewerkernaam = $gebruikers_query["naam"];
				}
			}
			else{
				$Medewerkernaam = "Geen medewerker gekoppeld!";
			}

			$test = $i['IncidentId'];

			echo "<tr><td>". "<a href=\"incidentinformation.php?IncidentId=". $i['IncidentId']. "\">" . $i['IncidentId']."</a>"."</td><td>". $Gebruikernaam. "</td><td>". $Medewerkernaam. "</td><td>". $i['Omschrijving']. "</td><td>". $i['status']. "</td><td>". $i['StartDatum']. "</td><td>". $i['EindDatum']. "</td></tr>";
		}
	}
?>