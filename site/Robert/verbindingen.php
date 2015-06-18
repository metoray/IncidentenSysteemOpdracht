<?php
/*deze pagina moet per computer laten zien wat er geinstalleerd is. Daarnaast moet je dat ook kunnen aanpassen.
Dit eerste deel is bedoeld om de hardware_id die bij de identficationcode hoort te achterhalen.

s= search = i=hardware_id s=select  */

include "connect.php";
$identification_code = "GRL004";
	
	
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
	 	$query = "delete from verbindingen where hardware_id1 = ".$sis_step3[0]." and hardware_id2 = ".$_POST["verwijder_van_verbindingen"]." ";
		mysql_query($query);
		$query = "delete from verbindingen where hardware_id1 = ".$_POST["verwijder_van_verbindingen"]." and hardware_id2 = ".$sis_step3[0]." ";
		mysql_query($query);
		
	}
	if(isset($_POST["toevoegen_aan_verbindgen"]))
	{
		$query = "";
		mysql_query($query);
		
	}
}; 

?>
	<style>
	form
 	{

     float:left;
 	}  
		
	</style>
	
	<form action="verbindingen.php" method="post" name="verbonden">
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
					hardware_id1 =".$sis_step3[0]." OR hardware_id2 =".$sis_step3[0].") ";
			
	$component2 = mysql_query($component);

	while($row = mysql_fetch_assoc($component2)) 
	{ 
    	
        // scq = search connection query 
        if($row["hardware_id1"] != $sis_step3["hardware_id"])
        {
         	//als de component in hardware_id1 staat moet de andere component in hardware2 staan.
        	$scq_step1 = "select * from hardwarecomponenten where hardware_id = ".$row["hardware_id2"]."";
        	$test1 = $scq_step1;
        }
		else
        {	
         	//Als de component niet in hardware_id1 staat moet het in hardware_id1 staan.
			$scq_step1 = "select * from hardwarecomponenten where hardware_id = ".$row["hardware_id1"]."";
			
			$test2 = $scq_step1;
		}
        
        
        $scq_step2 = mysql_query($scq_step1);
		$scq_step3 = mysql_fetch_row($scq_step2);
			
        ?>
        <option value = <?php echo $scq_step3[0]; ?> > <?php echo $scq_step3[1]; ?>   </option>
        <?php
        array_push ($connected, $scq_step3[0]);
    };
    echo "</select> ";  //sluit de select menu af
    echo "<br />"; 	
    
   
    //start HTML  
?>   
	<input type="submit" name="remove_connection" value = "'     >       '">
		</form>
<?php

 

/*Eind html
	not connected but can connect = ncbc 
	$sis_step3[1] = soort hardware id */
	$i = 0;
	$not_connected = " ";
	$not = "";
	$not_connected = "select soort_id, locatie_id, hardware_id, identificationcode from hardwarecomponenten where locatie_id = ".$sis_step3[2]."  ";
	foreach($connected as $connect)
	{
		
			$not = " and !(hardware_id = ".$connect.")";
		
		$not_connected  = $not_connected.$not;
	} 
	//basis query = select * from hardwarecomponenten where location = X and !(hardware_id = X)
	echo $not_connected;
	$ncbc_query = $not_connected;
	$ncbc_result = mysql_query($ncbc_query);
	
?>
	
	<form action="verbindingen.php" method="post" name="niet_verbonden">
	<select size ="30"  name="toevoegen_aan_verbindgen">
<?php

	while($ncbc_rows = mysql_fetch_assoc($ncbc_result))
	{
	 
	 	
	 	?>
        <option value = <?php echo $ncbc_rows[2]; ?> >  
		<?php echo $ncbc_rows[3]; ?>  </option>
        <?php
	};
	echo "</select> ";
    echo "<br />"; 	
?><input type="submit" name="add_connected_device" value = "'     <       '">
		</form>
		
	
