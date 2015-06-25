<?php
	include 'include/connect.php';

	//Met deze query wordt de gebruiker_id opgehaald. Doormiddel van deze query wordt er gekenken of er een gebruiker gekoppeld is aan het incident.
	$query = "SELECT gebruiker_id ";
	$query .= "FROM incidenten ";
	$query .= "WHERE inc_id = ". $_GET["incidentid"]. " ";
	
	//Zet de resultaten van query in variabel.
	$result = mysqli_query($con, $query);

	//Wordt gekeken of query werkt.
	if (!$result) {
		die("Query werkt niet");
	}

	//Zet de resultaten van de query in een associative array.
	while ($gebruikerid_query = mysqli_fetch_assoc($result)) {
		$gebruikerjaofnee = $gebruikerid_query["gebruiker_id"];
	}

	//Er dient een ander query worden uitgevoerd als het incident geen gebruiker gekoppeld heeft.
	if (empty($gebruikerjaofnee)) {
		//Met deze query worden de gegevens opgehaald van de tabel incident die gelijk staat aan $_GET["incidentid"]. Er worden geen gegevens van de gebruiker opgehaald aangezien er geen gebruiker is gekoppeld aan het incident.
		$query = "SELECT * ";
		$query .= "FROM incidenten ";
		$query .= "WHERE inc_id = ". $_GET["incidentid"]. " ";
		
		//Zet de resultaten van query in variabel.
		$result = mysqli_query($con, $query);

		//Wordt gekeken of query werkt.
		if (!$result) {
			die("Query werkt niet");
		}

		//Zet de resultaten van de query in een associative array.
		while ($incidenten_query = mysqli_fetch_assoc($result)) {
			$incidenten = array('IncidentId' => intval($incidenten_query["inc_id"]), 'StartDatum' => $incidenten_query["start_incident"], 'Omschrijving' => $incidenten_query["Omschrijving"], 'Workaround' => $incidenten_query["Workaround"], 'GebruikerId' => 0, 'EindDatum' => $incidenten_query["eind_incident"], 'SoftwareComponent' => intval($incidenten_query["software_component"]), 'Status' => $incidenten_query["status"], 'Impact' => intval($incidenten_query["impact"]), 'Urgentie' => intval($incidenten_query["urgentie"]), 'Prioriteit' => intval($incidenten_query["prioriteit"]), 'HardwareId' => intval($incidenten_query["hardware_id"]), 'MedewerkerId' => intval($incidenten_query["medewerker_id"]), 'ProbleemId' => intval($incidenten_query['problem_id']), 'GebruikerNaam' => "Geen gebruiker gekoppeld", 'Afdeling' => "Geen afdeling", 'Adres' => "Geen adres", 'RolId' => 0, 'Telefoon' => "Geen telefoon");
		}
	}
	else{
		//Met deze query worden de gegevens opgehaald van de tabel incident die gelijk staat aan $_GET["incidentid"].
		$query = "SELECT * ";
		$query .= "FROM incidenten ";
		$query .= "JOIN gebruikers ON(incidenten.gebruiker_id = gebruikers.gebruiker_id) ";
		$query .= "WHERE inc_id = ". $_GET["incidentid"]. " ";
		
		//Zet de resultaten van query in variabel.
		$result = mysqli_query($con, $query);

		//Wordt gekeken of query werkt.
		if (!$result) {
			die("Query werkt niet");
		}

		//Zet de resultaten van de query in een associative array.
		while ($incidenten_query = mysqli_fetch_assoc($result)) {
			$incidenten = array('IncidentId' => intval($incidenten_query["inc_id"]), 'StartDatum' => $incidenten_query["start_incident"], 'Omschrijving' => $incidenten_query["Omschrijving"], 'Workaround' => $incidenten_query["Workaround"], 'GebruikerId' => intval($incidenten_query["gebruiker_id"]), 'EindDatum' => $incidenten_query["eind_incident"], 'SoftwareComponent' => intval($incidenten_query["software_component"]), 'Status' => $incidenten_query["status"], 'Impact' => intval($incidenten_query["impact"]), 'Urgentie' => intval($incidenten_query["urgentie"]), 'Prioriteit' => intval($incidenten_query["prioriteit"]), 'HardwareId' => intval($incidenten_query["hardware_id"]), 'MedewerkerId' => intval($incidenten_query["medewerker_id"]), 'ProbleemId' => intval($incidenten_query['problem_id']), 'GebruikerNaam' => $incidenten_query["naam"], 'Afdeling' => $incidenten_query["afdeling"], 'Adres' => $incidenten_query["adres"], 'RolId' => $incidenten_query["rol_id"], 'Telefoon' => $incidenten_query["telefoon"]);
		}
	}

	if (empty($incidenten['MedewerkerId'])) {
		$Behandelaar = "Geen behandelaar!";
		$BehandelaarId = NULL;
	}
	else{
		//Met deze query worden de gegevens opgehaald van de tabel gebruikers die gelijk staat aan $incidenten['MedewerkerId'].
		$query = "SELECT * ";
		$query .= "FROM gebruikers ";
		$query .= "WHERE gebruiker_id = ". $incidenten['MedewerkerId']. " ";
		
		//Zet de resultaten van query in variabel.
		$result = mysqli_query($con, $query);

		//Wordt gekeken of query werkt.
		if (!$result) {
			die("Query werkt niet");
		}

		//Zet de resultaten van de query in een associative array.
		while ($behandelaar_query = mysqli_fetch_assoc($result)) {
			$Behandelaar = $behandelaar_query["naam"];
			$BehandelaarId = $behandelaar_query["gebruiker_id"];
		}
	}

	//Haalt alle gebruikers op die niet de role consumer hebben.
	$query = "SELECT * ";
	$query .= "FROM gebruikers ";
	$query .= "WHERE rol_id != 1 ";
	
	//Zet de resultaten van query in variabel.
	$result = mysqli_query($con, $query);

	//Wordt gekeken of query werkt.
	if (!$result) {
		die("Query werkt niet");
	}

	//Zet de resultaten van de query in een associative array.
	while ($behandelaars_query = mysqli_fetch_assoc($result)) {
		$Behandelaars[] = array('BehandelaarId' => intval($behandelaars_query['gebruiker_id']), 'BehandelaarNaam' => $behandelaars_query["naam"], 'Afdeling' => $behandelaars_query["afdeling"], 'Adres' => $behandelaars_query["adres"], 'RolId' => intval($behandelaars_query['rol_id']), 'Telefoon' => $behandelaars_query['telefoon']);
	}


	if (empty($incidenten['Status'])) {
		$StatusHuidig = 1;
	}
	else{
		//Met deze query worden de gegevens opgehaald van de tabel statussen_incident die gelijk staat aan $incidenten['Status'].
		$query = "SELECT * ";
		$query .= "FROM statussen_incident ";
		$query .= "WHERE id = ". $incidenten['Status']. " ";
		
		//Zet de resultaten van query in variabel.
		$result = mysqli_query($con, $query);

		//Wordt gekeken of query werkt.
		if (!$result) {
			die("Query werkt niet");
		}

		//Zet de resultaten van de query in een associative array.
		while ($statussen_query = mysqli_fetch_assoc($result)) {
			$StatusHuidig = $statussen_query["status"];
			$StatusHuidigId = $statussen_query["id"];
		}
	}


	//Query voor de gegevens van de statussen_incident tabel op te halen.
	$query = "SELECT * ";
	$query .= "FROM statussen_incident ";

	//Zet de resultaten van query in variabel.
	$result = mysqli_query($con, $query);

	//Wordt gekeken of query werkt.
	if (!$result) {
		die("Query werkt niet");
	}

	//Zet de resultaten van de query in een associative array.
	while ($statussen_query = mysqli_fetch_assoc($result)) {
		$Statussen[] = array('StatusId' => intval($statussen_query["id"]), 'Naam' => $statussen_query["status"]);
	}

	//Wordt gekeken of er een hardware component is gekoppeld aan het incident.
	if (empty($incidenten['HardwareId'])) {
		$Hardware = array('Identificationcode' => "Geen gerelateerde hardware.");
		$hardwareid = "NULL";
	}
	else{
		//Query voor de gegevens van de hardware tabel op te halen waar hardware_id = $incidenten['HardwareId'].
		$query = "SELECT * ";
		$query .= "FROM hardwarecomponenten ";
		$query .= "WHERE hardware_id = ". $incidenten['HardwareId']. " ";

		//Zet de resultaten van query in variabel.
		$result = mysqli_query($con, $query);

		//Wordt gekeken of query werkt.
		if (!$result) {
			die("Query werkt niet");
		}

		//Zet de resultaten van de query in een associative array.
		while ($hardware_query = mysqli_fetch_assoc($result)) {
			$Hardware = array('HardwareId' => intval($hardware_query["hardware_id"]), 'Identificationcode' => $hardware_query["identificationcode"], 'SoortId' => intval($hardware_query["soort_id"]), 'LocatieId' => intval($hardware_query["locatie_id"]), 'MerkId' => intval($hardware_query["merk_id"]), 'LeverancierId' => intval($hardware_query["leverancier_id"]), 'JaarVanAanschaf' => $hardware_query["jaar_van_aanschaf"]);
		}

		$hardwareid = $Hardware['HardwareId'];
	}
	
	//Wordt gekeken of er een software component is gekoppeld aan het incident.
	if (empty($incidenten['SoftwareComponent'])) {
		$Software = array('Identificatiecode' => "Geen gerelateerde software.");
		$softwareid = "NULL";
	}
	else{
		//Query voor de gegevens van de software tabel op te halen waar software_id = $incidenten['SoftwareComponent'].
		$query = "SELECT * ";
		$query .= "FROM software ";
		$query .= "WHERE software_component = ". $incidenten['SoftwareComponent']. " ";

		//Zet de resultaten van query in variabel.
		$result = mysqli_query($con, $query);

		//Wordt gekeken of query werkt.
		if (!$result) {
			die("Query werkt niet");
		}

		//Zet de resultaten van de query in een associative array.
		while ($software_query = mysqli_fetch_assoc($result)) {
			$Software = array('SoftwareId' => intval($software_query["software_id"]), 'Identificatiecode' => $software_query["identificatiecode"], 'SoortId' => intval($software_component["soort_id"]), 'ProducentId' => intval($software_component["producent_id"]), 'LeverancierId' => intval($software_component["lev_id"]), 'ServerLicentie' => $software_component["server_licentie"], 'AantalServerLicenties' => intval($software_component["serverlicenties"]), 'GebruikerLicenties' => intval($software_component['gebruiker_licenties']));
		}

		$softwareid = $Software['SoftwareId'];
	}


	//Haalt alle gegevens op van de tabel probleem.
	$query = "SELECT * ";
	$query .= "FROM probleem ";

	//Zet de resultaten van query in variabel.
	$result = mysqli_query($con, $query);

	//Wordt gekeken of query werkt.
	if (!$result) {
		die("Query werkt niet");
	}

	//Zet de resultaten van de query in een associative array.
	while ($problemen_query = mysqli_fetch_assoc($result)) {
		$Problemen[] = array('ProbleemId' => intval($problemen_query["id"]), 'StatusId' => intval($problemen_query["status"]), 'Beschrijving' => $problemen_query["beschrijving"], 'Oplossing' => $problemen_query["oplossing"], 'BeginDatum' => $problemen_query["begindatum"], 'EindDatum' => $problemen_query["einddatum"], 'MedewerkerId' => intval($problemen_query["medewerker"]));
	}


	//Alle antwoorden met het id van huidig incident worden opgehaald.
	$query = "SELECT * ";
	$query .= "FROM gebruikers_antwoorden ";
	$query .= "WHERE inc_id = ". $_GET["incidentid"]. " ";
	
	//Zet de resultaten van query in variabel.
	$result = mysqli_query($con, $query);

	//Wordt gekeken of query werkt.
	if (!$result) {
		die("Query werkt niet");
	}

	//Zet de resultaten van de query in een associative array.
	while ($antwoorden_query = mysqli_fetch_assoc($result)) {
		$antwoorden[] = array('Id' => intval($antwoorden_query["id"]), 'AntwoordId' => intval($antwoorden_query["antwoord_id"]), 'IncidentId' => intval($antwoorden_query["inc_id"]), 'ReeksNummer' => intval($antwoorden_query["reeks_nummer"]));
	}


	//Controleert waar gegevens niet van beschikbaar zijn.
	if (empty($incidenten['GebruikerNaam'])) {
		$incidenten['GebruikerNaam'] = "Geen gebruiker.";
	}
	if (empty($incidenten['Impact'])) {
		$incidenten['Impact'] = 1;
	}
	if (empty($incidenten['Urgentie'])) {
		$incidenten['Urgentie'] = 1;
	}
	if (empty($incidenten['StartDatum'])) {
		$incidenten['StartDatum'] = "Geen start datum.";
	}
	if (empty($incidenten['EindDatum'])) {
		$incidenten['EindDatum'] = "Geen eind datum.";
	}
	if (empty($incidenten['Omschrijving'])) {
		$incidenten['Omschrijving'] = "Geen omschrijving.";
	}
	if (empty($incidenten['Workaround'])) {
		$incidenten['Workaround'] = "Geen workaround.";
	}

	//Prioriteit wordt bepaald door de Impact en Urgentie bij elkaar op te tellen dan delen door 2. De uitkomst van de deling wordt daarna afgerond.
	$incidenten['Prioriteit'] = round(($incidenten['Impact'] + $incidenten['Urgentie'])/2);

	//Alle gegevens worden weergegeven in een tabel. Veel gegevens kunnen worden aangepast.
	echo "<form method=\"POST\">";
	echo "<table>";

	//Gebruiker
	echo "<tr><td><b>Gebruiker:</b></td><td>". $incidenten['GebruikerNaam']. "</td><td><b>Behandelaar:</b></td>";
	
	//Behandelaar
	echo "<td><select name=\"behandelaar\">";
	foreach ($Behandelaars as $i) {
		echo "<option value=" .$i['BehandelaarId']."";
		if (empty($BehandelaarId)) {		
		}
		else{
			if($BehandelaarId == $i['BehandelaarId']){
				echo 'selected="selected"';
			}
		}
		echo ">". $i['BehandelaarNaam']. "</option>";
	}
	echo "</selected>";
	
	//Impact
	echo "</tr>";
	echo "<tr><td><b>Impact:</b></td>";
	echo "<td>";
?>
	<select name="impact">
	<option value="1" <?php if($incidenten['Impact'] == "1") echo 'selected="selected"'; ?> >1</option>
	<option value="2" <?php if($incidenten['Impact'] == "2") echo 'selected="selected"'; ?> >2</option>
	<option value="3" <?php if($incidenten['Impact'] == "3") echo 'selected="selected"'; ?> >3</option>
	<option value="4" <?php if($incidenten['Impact'] == "4") echo 'selected="selected"'; ?> >4</option>
	<option value="5" <?php if($incidenten['Impact'] == "5") echo 'selected="selected"'; ?> >5</option>
	</select>
<?php
	echo "</td>";

	//Status
	echo "<td><b>Status:</b></td>";
	echo "<td><select name=\"status\">";
	foreach ($Statussen as $i) {
		echo "<option value=\"" .$i['StatusId']."\"";
		if (empty($StatusHuidigId)) {
		}
		else{
			if($StatusHuidigId == $i['StatusId']){
				echo 'selected="selected"';
			}
		}
		echo ">". $i['Naam']. "</option>";
	}
	echo "</selected>";
	echo "</td></tr>";

	//Urgentie
	echo "<tr><td><b>Urgentie:</b></td><td>";
?>
	<select name="urgentie">
	<option value="1" <?php if($incidenten['Urgentie'] == "1") echo 'selected="selected"'; ?> >1</option>
	<option value="2" <?php if($incidenten['Urgentie'] == "2") echo 'selected="selected"'; ?> >2</option>
	<option value="3" <?php if($incidenten['Urgentie'] == "3") echo 'selected="selected"'; ?> >3</option>
	<option value="4" <?php if($incidenten['Urgentie'] == "4") echo 'selected="selected"'; ?> >4</option>
	<option value="5" <?php if($incidenten['Urgentie'] == "5") echo 'selected="selected"'; ?> >5</option>
	</select>
<?php
	echo "</td>";

	//Hardware
	echo "<td><b>Hardware in kwestie:</b></td><td><input type=\"text\" name=\"hardware\" value=\"". $Hardware['Identificationcode']. "\"</td></tr>";

	//Prioriteit
	echo "<tr><td><b>Prioriteit:</b></td><td>". $incidenten['Prioriteit']. "</td><td><b>Software in kwestie:</b></td>";

	//Software
	echo "<td><input type=\"text\" name=\"software\" value=\"". $Software['Identificatiecode']. "\"></td></tr>";

	//Toevoegen aan probleem
	echo "<tr><td><b>Toevoegen aan probleem:</b></td>";
	echo "<td><select name=\"probleem\">";
	echo "<option value=\"NULL\">Niet gekoppeld.</option>";
	foreach ($Problemen as $i) {
		echo "<option value=\"". $i['ProbleemId']. "\"";
		if ($incidenten['ProbleemId'] == 0) {
		}
		else{
			if($incidenten['ProbleemId'] == $i['ProbleemId']){
				echo 'selected="selected"';
			}
		}
		echo ">". $i['ProbleemId']. "</option>";
	}
	echo "</td>";

	//Start Datum + tijd
	echo "<td><b>Start datum: + tijd</b></td><td>". $incidenten['StartDatum']. "</td></tr>";

	//Eind Datum + tijd
	echo "<tr><td></td><td></td><td><b>Eind datum + tijd:</b></td><td><input type=\"text\" name=\"einddatum\" value=\"". $incidenten['EindDatum']. "\"></td>";
	echo "</table>";

	echo "</br>";
	echo "</br>";

	//Tabel voor omschrijving en de workaround van de incident.
	echo "<table><tr><td><b>Omschrijving:</b></td><td><b>Workaround:</b></td></tr>";

	//Omschrijving
	echo "<tr><td><input type=\"text\" name=\"omschrijving\" value=\"". $incidenten['Omschrijving']. "\"></td>";

	//Workaround
	echo "<td><input type=\"text\" name=\"workaround\" value=\"". $incidenten['Workaround']. "\"></td></tr>";

	echo "</table>";
	echo "</br>";

	$answers = AnswerList::fromIncident($_GET["incidentid"]);
	$aText = $answers -> render();

	if($aText){
		echo "<b>Vragen:</b><br/>";
		echo $aText;
	}

	echo "</br>";

	//Submit knop
	echo "<input type=\"submit\" value=\"Wijzigingen opslaan\" name=\"opslaan\">";

	echo "</form>";

	//Data van $_POST doorvoeren in incidenten tabel.
	if (empty($_POST)) {
	}
	else{
		//
		$query = "UPDATE incidenten ";
		$query .= "SET Omschrijving='". $_POST["omschrijving"]. "', Workaround='". $_POST["workaround"]. "', eind_incident='". $_POST["einddatum"]. "', software_component=". $softwareid. ", `status`=". $_POST["status"]. ", impact=". $_POST["impact"]. ", urgentie=". $_POST["urgentie"]. ", prioriteit=". $incidenten['Prioriteit']. ", hardware_id=". $hardwareid. ", medewerker_id=". $BehandelaarId. ", problem_id=". $_POST["probleem"]. " ";
		$query .= "WHERE inc_id = ". $_GET["incidentid"]. " ";

		$result = mysqli_query($con, $query);

		//Wordt gekeken of query werkt.
		if (!$result) {
			die("Query werkt niet");
		}
	}
?>