<?php
include "include/connect.php";
$search_practitoner = "select * from gebruikers where rol_id = 2";
$practitoner_result = mysqli_query($con,$search_practitoner) or die(mysql_error());
?>
<html>
	<body>
		<form name="/process/robert" action= "process.php" method="POST" id="new_problem">
		<br />
		Behandelaar:<select name="practitioner">
		
		<?php
			while($practitoner_row = mysqli_fetch_array($practitoner_result))
			{
				?>
				
					<option value = <?php echo $practitoner_row[0] ?> >	<?php echo $practitoner_row[1] ?> </option>
				<?php
			}
			$search_statussen_probleem = "select * from statussen_probleem";
			$search_statussen_probleem_result = mysqli_query($con,$search_statussen_probleem) or die(mysql_error());
		?>
		</select>
		<select name ="status">
		<?php
			while($search_statussen_probleem_row = mysqli_fetch_array($search_statussen_probleem_result))
			{
				?>
				
					<option value = <?php echo $search_statussen_probleem_row[0] ?> >	<?php echo $search_statussen_probleem_row[1] ?> </option>
				<?php
			}
		?>
		</select>
		<br />
		<br />
	
		<textarea rows="25" cols="150" name="description" form="new_problem"  >Schrijf een beschrijving van het probleem hier</textarea>
		<br />
		<input type="submit" name="new_problem" value = "Ga verder">
		</form>

	</body>
</html>