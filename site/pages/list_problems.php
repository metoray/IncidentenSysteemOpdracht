<?php
include "include/connect.php";
?>


<table class="table table-striped">
	<thead>
		<tr>
			<th>
				ID
			</th>
			<th>
				Behandelaar
			</th>
			<th>
				Status
			</th>
			<th>
				Beschrijving
			</th>
			<th>
				Oplossing
			</th>
			<th>
				Begin Datum
			</th>
			<th>
				Eind datum
			</th>
		</tr>
	</thead>

<?php
$problems_query = "select * from probleem";
$problems_result = mysqli_query($con,$problems_query );
while($problems_row = mysqli_fetch_assoc($problems_result )	)
{
	$practitioner_name_query	="select naam from gebruikers where gebruiker_id =".$problems_row["medewerker"]."";
	$practitioner_name_result=mysqli_query($con, $practitioner_name_query);
	$practitioner_row		=mysqli_fetch_assoc($practitioner_name_result);
	$status_query = "select status from statussen_probleem where id = ".$problems_row["status"]."";
	$status_result= mysqli_query($con, $status_query);
	$status_row = mysqli_fetch_assoc($status_result);

	echo <<<HTML
	<tr>
		<td>
			{$problems_row['id']}
		</td>
		<td>
			{$practitioner_row['naam']}
		</td>
		<td>
			{$status_row['status']}
		</td>
		<td>
			<a href=/problems/list/problem?problem_id="{$problems_row['id']}">
				{$problems_row['beschrijving']}
			</a>
		</td>
		<td>
			{$problems_row['oplossing']}
		</td>
		<td>
			{$problems_row['begindatum']}
		</td>
		<td>
			{$problems_row['einddatum']}
		</td>
	</tr>
HTML;
}
?>

</table>