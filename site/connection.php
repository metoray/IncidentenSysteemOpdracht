<?php
/*deze pagina moet per computer laten zien wat er geinstalleerd is. Daarnaast moet je dat ook kunnen aanpassen.
Dit eerste deel is bedoeld om de hardware_id die bij de identficationcode hoort te achterhalen.

s= search = i=hardware_id s=select  */

include "connect.php";
$identification_code = "GRLR01"; // = mysql_escape_string($_GET["$identification_code"]);
	
	
echo "<br >";
echo $identification_code;
$sis_step1 = "select hardware_id,soort_id, locatie_id  from hardwarecomponenten where identificationcode = '".$identification_code."' ";

$sis_step2 = mysql_query($sis_step1);
$sis_step3 = mysql_fetch_row($sis_step2);  
// Er zou maar 1 resultaat van moeten komen. 

//Dit verwerkt een post als die er is. Het moet na de sis_set3 komen zodat de goede hardware_id kan worden gebruikt.
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST["verwijder_van_verbindingen"]))
	{
	 	$query = "delete from verbindingen where hardware_id1 = ".mysql_real_escape_string($sis_step3[0])." and hardware_id2 = ".mysql_real_escape_string($_POST["verwijder_van_verbindingen"])." ";
		mysql_query($query);
		$query = "delete from verbindingen where hardware_id1 = ".mysql_real_escape_string($_POST["verwijder_van_verbindingen"])." and hardware_id2 = ".mysql_real_escape_string($sis_step3[0])." ";
		mysql_query($query);
		
	}
	if(isset($_POST["toevoegen_aan_verbindgen"]))
	{
		$query = "insert into verbindingen VALUES(".mysql_real_escape_string($sis_step3[0])." , ".mysql_real_escape_string($_POST["toevoegen_aan_verbindgen"])." )";
		mysql_query($query);
		
	}
}; 

?>
	<!-- CSS zodat de select menu�n naast elkaar staan. -->
	<style>
	form
 	{
     float:left;
 	}  
	</style>
	
	<form action="connection.php" method="post" name="verbonden">
	<select size ="30"  name="verwijder_van_verbindingen">
        	
<?php	
	$connected = array();
	echo $sis_step3[0];
	echo "<br />";
		echo "<br />";
	$test1 = " test 1 ";
	$test2="test 2";
	$scq_step1 = "";
	
	//sis_step3[0] bevat de hardware_id van de component in kwestie.
	$component = "SELECT * FROM verbindingen RIGHT JOIN hardwarecomponenten ON hardware_id1 = hardware_id where hardware_id =  ".$sis_step3[0]." AND (
					hardware_id1 =".$sis_step3[0]." OR hardware_id2 =".$sis_step3[0].") order by hardware_id2 ";
			
	$component2 = mysql_query($component);

	while($row = mysql_fetch_assoc($component2)) 
	{ 
    	
        // scq = search connection query 
        //deze if statement controleert zowel hardware_id1 als hardware_id2. Vervolgens 
        if($row["hardware_id1"] != $sis_step3["hardware_id"])
        {
         	
        	$scq_step1 = "select * from hardwarecomponenten where hardware_id = ".$row["hardware_id2"]."";
        	
        }
		else
        {	
         	//Als de component niet in hardware_id1 staat moet het in hardware_id1 staan.
			$scq_step1 = "select * from hardwarecomponenten where hardware_id = ".$row["hardware_id1"]."";
			
		
		}
        
        //Vervolgens worden ze allemaal in de opties gezet van apparaten die verbonden zijn in de select menu staan.
        $scq_step2 = mysql_query($scq_step1);
		$scq_step3 = mysql_fetch_row($scq_step2);
			
        ?>
        <option value = <?php echo $scq_step3[0]; ?> > <?php echo $scq_step3[1]; ?>   </option>
        <?php
        array_push ($connected, $scq_step3[0]);
    };
    $number_connected = count($connected);
    echo "</select> ";  //sluit de select menu af
    echo "<br />"; 	
    
   
?>   
	<input type="submit" name="remove_connection" value = "'     >       '">
		</form>
<?php

 

/*	not connected but can connect = ncbc 
	$sis_step3[1] = soort hardware id */
	$ncbc_query = "	SELECT * FROM verbindingen con
					JOIN hardwarecomponenten hw
					ON (con.hardware_id1 = hw.hardware_id) OR (con.hardware_id2 = hw.hardware_id)
					WHERE 	NOT (con.hardware_id1 = ".$sis_step3[0]." OR con.hardware_id2 = ".$sis_step3[0].")
					AND hw.locatie_id = ".$sis_step3[2]."";
	$ncbc_result = mysql_query($ncbc_query); /*resultaat = elke hardwarecomponent die niet verbonden is met de huidige hardware. Dit gaat er wel van uit dat de hardwarecomponent verbinding kan maken 
	met meerdere apparaten */
	
?>
	
	<form action="connection.php" method="post" name="niet_verbonden">
	<select size ="30"  name="toevoegen_aan_verbindgen">
<?php

	while($ncbc_rows = mysql_fetch_row($ncbc_result))
	{
	 	//deze switch kijk wat voor hardwarecomponent het huidige component is. Op basis van dit wordt een specifiek aantal instructies uitgevoerd.
	 	switch($sis_step3[1])
	 	{
	 	case 3: //printer
	 	case 4:	//werkstation
	 	case 5:	//server
	 	case 8:	//scanner
	 	case 9:	/*plotter
	 			mag alleen maar met switches, modems en routers verbinding maken */
	 		if(	$ncbc_rows[0] != 3 &&  $ncbc_rows[0] != 4 && $ncbc_rows[0] != 5 && $ncbc_rows[0] != 8 && $ncbc_rows[0] != 9 && $ncbc_rows[0] != 6)
			{
			 	if($number_connected == 0)
			 	{
					?>
        				<option value = <?php echo $ncbc_rows[2]; ?> > <?php echo $ncbc_rows[3]; ?> Type nummer: <?php echo $ncbc_rows[0]; ?>  </option>
					<?php
				}
			}
	 	break;
		case 1:	//switch
		case 2:	/*modem
		mag verbinding maken met alles op de zelfde locatie (mits dat met meer dan 1 component verbinding mag maken) behalve firewalls */
		
			if(	($ncbc_rows[0] == 3 ||$ncbc_rows[0] == 4 || $ncbc_rows[0] == 5 || $ncbc_rows[0] == 8 || $ncbc_rows[0] == 9) && $ncbc_rows[0] != 6)
			{
			 	//Deze if statement is om te controleren dat we niet een pc aan de switch aansluiten die met andere router/switch is verbonden  
				$pc_connect = "select * from verbindingen where hardware_id1 = ".$ncbc_rows[2]." or hardware_id2 = ".$ncbc_rows[2]." ";
				$pc_connect2= mysql_query($pc_connect);
				$num_rows = mysql_num_rows($result);
				if($num_rows == 0)
				{		
					?>
        				<option value = <?php echo $ncbc_rows[2]; ?> > <?php echo $ncbc_rows[3]; ?>  </option>
					<?php
				}
			}
			//Een aparte if omdat routers natuurlijk wel met meerdere aparaten verbonden kunnen worden.
			if($ncbc_rows[0] == 7)
			{
				?>
        			<option value = <?php echo $ncbc_rows[2]; ?> > <?php echo $ncbc_rows[3]; ?>  </option>
				<?php
			}
		
		break;
		case 7: /*router
				Mag met alles verbinding maken, ook met andere routers op andere locaties maar daar komen we later op */
			if(	$ncbc_rows[0] == 3 || $ncbc_rows[0] == 4 || $ncbc_rows[0] == 5 || $ncbc_rows[0] == 8 || $ncbc_rows[0] == 9 ) 
			{
			 	//Deze if statement is om te controleren dat we niet een pc aan de switch aansluiten die met andere router/switch is verbonden  
				$pc_connect = "select * from verbindingen where hardware_id1 = (".$ncbc_rows[2]." or hardware_id2 = ".$ncbc_rows[2].") ";
				$pc_connect2= mysql_query($pc_connect);
				$num_rows_router = mysql_num_rows($pc_connect2);
				if($num_rows_router != 0)
				{		
					?>
        				<option value = <?php echo $ncbc_rows[2]; ?> > <?php echo $ncbc_rows[3]; ?>  </option>
					<?php
				}
			}
			if($ncbc_rows[0] == 1 || $ncbc_rows[0] == 2 )
			{
				?>
        			<option value = <?php echo $ncbc_rows[2]; ?> > <?php echo $ncbc_rows[3]; ?>  </option>
				<?php
			}
	 	break;
	};
	}
	 /* 	EINDE CODE VOOR LOKALE HARDWARECOMPONENTEN
		 	START CODE VOOR ROUTERS EN FIREWALLS BUITEN LOCATIE		*/
	if($sis_step3[1] == 6 ||	$sis_step3[1] == 7)
	{
		//buiten locatie hardwarecomponenten = blh
		$blh_query="select * from hardwarecomponenten  where soort_id = 6 or soort_id = 7";
		foreach($connected as $connect)
		{
		
			$not = " and !(hardware_id = ".$connect.")";
		
			$blh_query  = $blh_query.$not;
		} 
		$blh_result= mysql_query($blh_query);
		while($blh_rows = mysql_fetch_row($blh_result))
		{
		 	$check_if_connected = "SELECT * FROM verbindingen RIGHT JOIN hardwarecomponenten ON hardware_id1 = hardware_id where hardware_id =  ".$sis_step3[0]." AND (
					hardware_id1 =".$blh_rows[0]." OR hardware_id2 =".$blh_rows[0].") order by hardware_id2";
			$check_if_connected_result = mysql_query($check_if_connected);
			$count_connections = mysql_num_rows($check_if_connected_result);
			if($count_connections == 0)
			{
			?>
				<option value = <?php echo $blh_rows[0]; ?> > <?php echo $blh_rows[1]; ?>  </option>
			<?php
			}
		
		};
	};
	echo "</select> ";
    echo "<br />"; 
?>
		<input type="submit" name="add_connected_device" value = "'     <       '">
		</form>	