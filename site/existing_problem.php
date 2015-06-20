<?php
include "connect.php";
// $current_problem = $_GET["problem"];
$current_problem = 1;
$search_current = "select * from probleem where id = ".$current_problem."";
$current_result = mysql_query($search_current) or die(mysql_error());
$current_row = mysql_fetch_row($current_result);
//0 = id 1 = status 2=beschrijving 3=oplossing 4=begindatum 5=einddatum 6=behanadelaar/medewerker

$search_practitoner = "select * from gebruikers where rol_id = 2 or rol_id = 4";
$practitoner_result = mysql_query($search_practitoner) or die(mysql_error());
?>
<html>
	<body>
		<form name="edit_problem" action= "process.php" method="POST" id="edit_problem">
		<br />
		Behandelaar:<select name="practitioner">
		
		<?php
			while($practitoner_row = mysql_fetch_array($practitoner_result))
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
			$search_statussen_probleem_result = mysql_query($search_statussen_probleem) or die(mysql_error());
		?>
		</select>
		<select name ="status">
		<?php
			while($search_statussen_probleem_row = mysql_fetch_array($search_statussen_probleem_result))
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
		?>
		</select>
		<br />
		
		Beschrijving:
		<br />
		<textarea rows="25" cols="100" name="description" form="edit_problem"  ><?php echo	$current_row[2] ?> </textarea>
		<br />
		Oplossing:
		<br />
		<textarea rows="25" cols="100" name="solution" form="edit_problem"  ><?php echo	$current_row[3] ?> </textarea>
		<br />
		<input type="submit" name="edit_problem" value = "Verwerk">
		</form>

	</body>
</html>
