<?php
	include 'include/connect.php';

	//Query voor de gegevens van de software, leverancier, soort_software, producent tabelen op te halen.
	$query = "SELECT * ";
	$query .= "FROM software ";
	$query .= "JOIN leverancier ON (software.lev_id = leverancier.lev_id) ";
	$query .= "JOIN soort_software ON (software.soort_id = soort_software.soort_s_id) ";
	$query .= "JOIN producent ON (software.producent_id = producent.prod_id)";

	
	//Zet de resultaten van query variabel zetten.
	$result = mysqli_query($con,$query);

	$software = array();
	//Zet de resultaten van de query in een associative array.
	while ($software_query = mysqli_fetch_assoc($result)) {
		$software[] = array('SoftwareId' => intval($software_query["software_id"]), 'Identificatiecode' => $software_query["identificatiecode"], 'SoortId' => intval($software_query["soort_id"]), 'ProducentId' => intval($software_query["producent_id"]), 'LeverancierId' => intval($software_query["lev_id"]), 'Serverlicentie' => $software_query["server_licentie"], 'ServerLicentieAantal' => intval($software_query["serverlicenties"]), 'GebruikerLicenties' => intval($software_query["gebruiker_licenties"]), 'LeverancierNaam' => $software_query["naam"], 'SoortSoftware' => $software_query["beschrijving"], 'Producent' => $software_query["prod_naam"]);
	}


	//Tabel wordt gemaakt met kolomnamen.
	echo '<table class="table table-striped"><thead><tr><th>Identificatiecode</td><th>Soort software</td><th>Serverlicentie</td><th>Aantal server licenties</td></td><th>Gebruiker licenties</td></td><th>Producent</td></td><th>Leverancier</td></thead></tr>';

	foreach ($software as $i) {
		echo "<tr><td>". $i['Identificatiecode']. "</td><td>". $i['SoortSoftware']. "</td><td>". $i['Serverlicentie']. "</td><td>". $i['ServerLicentieAantal']. "</td></td><td>". $i['GebruikerLicenties']. "</td></td><td>". $i['Producent']. "</td></td><td>". $i['LeverancierNaam']. "</td></tr>";
	}

	echo "</table>";
?>