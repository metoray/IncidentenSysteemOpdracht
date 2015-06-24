<?php
	error_reporting(0);
	include 'include/connect.php';

	//Query voor de gegevens van de hardware, leverancier, soort_hardware, merken, locaties tabelen op te halen.
	$query = "SELECT * ";
	$query .= "FROM hardwarecomponenten ";
	$query .= "JOIN leverancier ON (hardwarecomponenten.leverancier_id = leverancier.lev_id) ";
	$query .= "JOIN soort_hardware ON (hardwarecomponenten.soort_id = soort_hardware.soort_h_id) ";
	$query .= "JOIN merken ON (hardwarecomponenten.merk_id = merken.merk_id) ";
	$query .= "JOIN locaties ON (hardwarecomponenten.locatie_id = locaties.locatie_id)";
	echo $query;
	
	//Zet de resultaten van query variabel zetten.
	$result = mysqli_query($con,$query);

	//Zet de resultaten van de query in een associative array.
	while ($hardware_query = mysqli_fetch_assoc($result)) {
		$Hardware[] = array('HardwareId' => intval($hardware_query["hardware_id"]), 'Identificatiecode' => $hardware_query["identificationcode"], 'SoortId' => intval($hardware_query["soort_id"]), 'LocatieId' => intval($hardware_query["locatie_id"]), 'MerkId' => intval($hardware_query["merk_id"]), 'LeverancierId' => intval($hardware_query["leverancier_id"]), 'JaarVanAanschaf' => $hardware_query["jaar_van_aanschaf"], 'LeverancierNaam' => $hardware_query["naam"], 'SoortHardware' => $hardware_query["beschrijving"], 'MerkNaam' => $hardware_query["merk_naam"], 'Locatie' => $hardware_query["locatie_naam"]);
	}


	//Tabel wordt gemaakt met kolomnamen.
	echo "<table border=1><tr><td><b>Identificatiecode</b></td><td><b>Soort hardware</b></td><td><b>Locatie</b></td><td><b>Merk</b></td></td><td><b>Leverancier</b></td></td><td><b>Aanschaf datum</b></td></td></tr>";

	foreach ($Hardware as $i) {
		echo "<tr><td><a href=\"installation.php?=". $i['HardwareId']. "\">". $i['Identificatiecode']. "</a></td><td>". $i['SoortHardware']. "</td><td>". $i['Locatie']. "</td><td>". $i['MerkNaam']. "</td></td><td>". $i['LeverancierNaam']. "</td></td><td>". $i['JaarVanAanschaf']. "</td></td></tr>";
	}
	echo "</table>";
?>