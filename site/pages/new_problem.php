<?php
include "include/connect.php";
$search_practitoner = "select * from gebruikers where rol_id = 2";
$practitoner_result = mysqli_query($con,$search_practitoner) or die(mysql_error());
?>
<html>
	<body>
		<form class="form-horizontal" name="new_problem" action= "/process/robert" method="POST" id="new_problem">
			<div class="form-group">
				<label for="employee" class="col-sm-2 control-label">Behandelaar:</label>
				<div class="col-sm-5">
					<select class="form-control" name="practitioner" id="employee">
					
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
				</div>
				<div class="col-sm-5">
					<select class="form-control" name ="status">
					<?php
						while($search_statussen_probleem_row = mysqli_fetch_array($search_statussen_probleem_result))
						{
							?>
							
								<option value = <?php echo $search_statussen_probleem_row[0] ?> >	<?php echo $search_statussen_probleem_row[1] ?> </option>
							<?php
						}
					?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<textarea class="form-control" rows="25" cols="150" name="description" form="new_problem"  >Schrijf een beschrijving van het probleem hier</textarea>
			</div>
			<div class="form-group">
				<input class="btn btn-primary" type="submit" name="new_problem" value = "Ga verder">
			</div>
		</form>

	</body>
</html>