<?php
/*deze pagina moet per computer laten zien wat er geinstalleerd is. Daarnaast moet je dat ook kunnen aanpassen.
Dit eerste deel is bedoeld om de hardware_id die bij de identficationcode hoort te achterhalen.

s= search = i=hardware_id s=select  */
include "include/connect.php";
if(isset($_GET["identification_code"]))
{
	$identification_code = mysqli_real_escape_string($con,$_GET["identification_code"]);
}
else
{
	$identification_code = $_POST["installation"];
}

echo "<br >";

echo $identification_code;
echo "<br >";

echo "<a href =connection.php?identification_code=".$identification_code."> Verbindingen </a>";

$sis_step1 = "select hardware_id from hardwarecomponenten where identificationcode = '".$identification_code."' ";

$sis_step2 = mysqli_query($con,$sis_step1);
$sis_step3 = mysqli_fetch_row($sis_step2);
// Er zou maar 1 resultaat van moeten komen. 

//Dit verwerkt een post als die er is. Het moet na de sis_set3 komen zodat de goede hardware_id kan worden gebruikt.
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST["verwijder_van_installatie"]))
	{
	 	$query = "delete from installatie where hardware_id = '".mysqli_real_escape_string($con,$sis_step3[0])."' and software_id = ".mysqli_real_escape_string($con,$_POST["verwijder_van_installatie"])." ";
		mysqli_query($con, $query);
	
	}
	if(isset($_POST["voegtoe_aan_installatie"]))
	{
		$query = "insert into installatie(hardware_id, software_id) VALUES (".mysqli_real_escape_string($con,$sis_step3[0])." , ".mysqli_real_escape_string($con,$_POST["voegtoe_aan_installatie"])." )";
		mysqli_query($con, $query);
		
	}
};

?>
	<!-- CSS zodat de select menuën naast elkaar staan. -->
	<style>


	form
 	{

     float:left;
 	}  
		
	</style>
	
	<form action="http://database.rickvenema.nl/cmdb/hardware/installation" method="post" name="installatie">
	<select size ="30"  name="verwijder_van_installatie">
        	
<?php	
	
	$installed = array();//deze array gebruiken we voor het onthouden van wat er geinstalleerd is.

	
/* Nu gaan we alle rows achterhalen waar er iets is geinstalleerd op deze hardwarecomponent.
s=search a= all hardware s=and software*/
	$sas_step1 = "select * from installatie where hardware_id = ".$sis_step3[0]." ";
	$sas_step2 = mysqli_query($con, $sas_step1);
	while($row = mysqli_fetch_assoc($sas_step2)) 
	{ 
    	//Nu moeten we zoeken naar de naam van het programma.
        // spn = search program name 
        $spn_step1 = " select identificatiecode, beschrijving, software_id from software stw , soort_software ssd where software_id = ".$row["software_id"]." and stw.soort_id = ssd.soort_s_id ";
        $spn_step2 = mysqli_query($con, $spn_step1);
		$spn_step3 = mysqli_fetch_row($spn_step2);
			
        ?>
        <option value = <?php echo $spn_step3[2]; ?> > <?php echo $spn_step3[0]; ?>  Beschrijving: <?php echo $spn_step3[1]; ?>   </option>
        <?php
        //alle geinstalleerde programma komen in een array
        array_push($installed, $spn_step3[0]);
    };
    echo "</select> ";  //sluit de select menu af
    echo "<br />"; 	
?>  
	<input type="hidden" name="installation" value="<?php echo $identification_code; ?>"	>  
	<input type="submit" name="remove_program" value = "'     >       '">
		</form>
<?php
	//not installed query =nig
	$niq_step1 = "select identificatiecode,beschrijving,software_id from software stw , soort_software ssd where  stw.soort_id = ssd.soort_s_id";
	foreach($installed as $program)
	{
		$not = " and !(identificatiecode = '".$program."' )";
		$niq_step1 = $niq_step1.$not;
	};
	$nig_step2 = mysqli_query($con,$niq_step1);
?>
	
	<form action="http://database.rickvenema.nl/cmdb/hardware/installation" method="post" name="niet_geinstalleerd">
	<select size ="30"  name="voegtoe_aan_installatie">
<?php
	while($row2 = mysqli_fetch_assoc($nig_step2))
	{
	 	//spn for not installed = nispn
	 	$nispn_step1 = " select identificatiecode, beschrijving, software_id from software stw , soort_software ssd where software_id = ".$row2["software_id"]." and stw.soort_id = ssd.soort_s_id ";
	 
	 	$nispn_step2 = mysqli_query($con,$nispn_step1);
		$nispn_step3 = mysqli_fetch_row($nispn_step2);
	 	?>
        <option value = <?php echo $nispn_step3[2]; ?> >  <?php echo $nispn_step3[0]; ?>  Beschrijving: <?php echo $nispn_step3[1]; ?> </option>
        <?php
	};
	echo "</select> ";
    echo "<br />"; 	
?>
	<input type="hidden" name="installation" value="<?php echo $identification_code; ?>"	> 
	<input type="submit" name="add_program" value = "'     <       '">
		</form>
