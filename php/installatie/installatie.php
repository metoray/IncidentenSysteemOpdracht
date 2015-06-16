<?php
/*deze pagina moet per computer laten zien wat er geinstalleerd is. Daarnaast moet je dat ook kunnen aanpassen.
Dit eerste deel is bedoeld om de hardware_id die bij de identficationcode hoort te achterhalen.

s= search = i=hardware_id s=select  */

include "connect.php";

$identification_code = "BRG003";
$sis_step1 = "select hardware_id from hardwarecomponenten where identificationcode = '".$identification_code."' ";

$sis_step2 = mysql_query($sis_step1);
$sis_step3 = mysql_fetch_row($sis_step2);
// Er zou maar 1 resultaat van moeten komen. 


/* Nu gaan we alle rows achterhalen waar er iets is geinstalleerd op deze hardwarecomponent.
s=search a= all hardware s=and software*/
echo "<br />";
$sas_step1 = "select * from installatie where hardware_id = ".$sis_step3[0]." ";

$sas_step2 = mysql_query($sas_step1);
echo "<br />";

		?>
        	<style>
			label,a 
			{
				font-family : Arial, Helvetica, sans-serif;
				font-size : 12px; 
			}
		
			</style>
			<form action="installatie.php" method="post" name="installatie">
			<select size ="30"  name="geinstalleerd">
        	
			<?php	

while($row = mysql_fetch_assoc($sas_step2)) 
		{
		 
        	//Nu moeten we zoeken naar de naam van het programma.
        	// spn = search program name 
        	$spn_step1 = "select identificatiecode, beschrijving from software stw , soort_software ssd where software_id = ".$row["software_id"]." and stw.soort_id = ssd.soort_s_id ";
        	$spn_step2 = mysql_query($spn_step1);
			$spn_step3 = mysql_fetch_row($spn_step2);
			
        	?>
        	<option value = "program 1" > 
			<?php echo $spn_step3[0]; ?>  Beschrijving: <?php echo $spn_step3[1]; ?>   
			</option>
        	<?php
        };
        echo "</select> ";
       	echo "<br />";
       	?> 
       
		<input type="submit" name="remove_program" value = "'     >       '">
		
