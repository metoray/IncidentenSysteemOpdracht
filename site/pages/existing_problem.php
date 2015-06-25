<?php
include "include/connect.php";
$current_problem = $_GET["problem_id"];
$search_current = "select * from probleem where id = ".$current_problem."";
$current_result = mysqli_query($con ,$search_current) or die(mysql_error());
$current_row = mysqli_fetch_row($current_result);
//0 = id 1 = status 2=beschrijving 3=oplossing 4=begindatum 5=einddatum 6=behanadelaar/medewerker

$search_practitoner = "select * from gebruikers where rol_id = 2 or rol_id = 4";
$practitoner_result = mysqli_query($con, $search_practitoner) or die(mysql_error());
?>
<html>
	<body>
		<form name="edit_problem" action= "/process/robert" method="POST" id="edit_problem">
		<br />
		Behandelaar:<select name="practitioner">
		
		<?php
			while($practitoner_row = mysqli_fetch_array($practitoner_result))
			{
			 	if($practitoner_row[0] == $current_row[6])
			 	{
				?>
					<option selected value = <?php echo $practitoner_row[0] ?> >	<?php echo $practitoner_row[1] ?> </option>
				<?php
				}
				else
				{
				?>
					<option value = <?php echo $practitoner_row[0] ?> >	<?php echo $practitoner_row[1] ?> </option>
				<?php
				}
			}
			$search_statussen_probleem = "select * from statussen_probleem";
			$search_statussen_probleem_result = mysqli_query($con, $search_statussen_probleem) or die(mysql_error());
		?>
		</select>
		<br />
		Status:
		
		<select name ="status">
		<?php
			while($search_statussen_probleem_row = mysqli_fetch_array($search_statussen_probleem_result))
			{
			 	if($search_statussen_probleem_row[0] == $current_row[1])
			 	{
					?>
						<option selected value = <?php echo $search_statussen_probleem_row[0] ?> >	<?php echo $search_statussen_probleem_row[1] ?> </option>
					<?php
				}
				else
				{
				 	?>
						<option value = <?php echo $search_statussen_probleem_row[0] ?> >	<?php echo $search_statussen_probleem_row[1] ?> </option>
					<?php
					
				}
			}
			echo "</select>";
			echo "<br />";
			echo "Begindatum:	";
			echo $current_row[4];
			echo "<br />";
			echo "Einddatum:		";
			echo $current_row[5];
			echo "<br />";
		?>
		Beschrijving:
		<br />
		<textarea rows="25" cols="100" name="description" form="edit_problem"  ><?php echo	$current_row[2] ?> </textarea>
		<br />
		Oplossing:
		<br />
		<textarea rows="25" cols="100" name="solution" form="edit_problem"  ><?php echo	$current_row[3] ?> </textarea>
		<br />
		<input type="hidden" name="problem_id" value=<?php echo $current_problem; ?>	>
		<input type="submit" name="edit_problem" value = "Verwerk">
		</form>

	</body>
</html>
<?php
		$search_related_inc = "select * from incidenten where problem_id = ".$current_problem." ";
		$search_inc= mysqli_query($con,$search_related_inc);
		echo "<form action=/process/robert name=toremove method=POST>";
		echo "<table  border=1> ";
		echo "<tr>";
				echo "<td>";
					echo "Incident code";
				echo "</td>";
		 		echo "<td>";
					echo "start date" ;
				echo "</td>";
				echo "<td>";
					echo "Status" ;
				echo "</td>";
				echo "<td>";
					echo "identificationcode hardware" ;
				echo "</td>";
				echo "<td>";
					echo "identificationcode software" ;
				echo "</td>";
				echo "<td>";
					echo "verwijderen ja/nee";
				echo "</td>";
		echo "</tr>";
		echo "gerelateerde incidenten:";
		while($inc_rows= mysqli_fetch_row($search_inc))
		{
		 	echo "<tr>";
		 		echo "<td>";
						echo "<a href=/incidents/existing?incidentid=".$inc_rows[0]."> ".$inc_rows[0]."	</a>";
				echo "</td>";
		 		echo "<td>";
					echo $inc_rows[1] ;
				echo "</td>";
				$search_status = "select status from statussen_incident where id = ".$inc_rows[7]." ";
				$search_status_result = mysqli_query($con,$search_status);
				$status = mysqli_fetch_row($search_status_result);
				echo "<td>";
					echo $status[0] ;
				echo "</td>";
				$search_hardware_identification = "select identificationcode from hardwarecomponenten where hardware_id = ".$inc_rows[11]." ";
				$search_hardware_result = mysqli_query($con,$search_hardware_identification);
				$hardware_identificationcode = mysqli_fetch_row($search_hardware_result);
				echo "<td>";
					echo "<a href=/cmdb/hardware/installation?identification_code=".$hardware_identificationcode[0]."> ".$hardware_identificationcode[0]."	</a>";
				echo "</td>";
				if(isset($inc_rows[6]))
				{
					$search_software_identification = "select identificatiecode from software where software_id = ".$inc_rows[6]." ";
					$search_software_result = mysqli_query($con,$search_software_identification);
					$software_identificationcode = mysqli_fetch_row($search_software_result);
					echo "<td>";
						echo $software_identificationcode[0] ;
					echo "</td>";
				}
				else
				{
					echo "<td>";
						echo "N.V.T" ;
					echo "</td>";
				}
				echo "<td>";
				echo "<input type=hidden name=problem_id value=".$current_problem.">";
			
					
					?> 	
						<input type="checkbox" name="remove[]" value= "<?php echo $inc_rows[0]; ?>" > 
						<?php	
				echo "</td>";
			echo "</tr>";
			echo "<br />";
		}
		echo "</table>";
		echo "<input type=submit name=remove_incidens value = Verwijder>";
		echo "</form>";
	
?>
