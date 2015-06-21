<?php
	/*
	$_session_start();
	$_session["user"];
	$user = $_session["user"]
	
	$query_user = select * from users where id= ".$user.";
	$user_result = mysql_query($query_user );
	$user_row = mysql_fetch_row($user_result);
	if($user_row[6] == 1)
	{
		echo $user_row[1];
		echo "<form action=process.php methode=POST""
		
		$hardware_query = "select hardware_id, identificationcode from hardwarecomponenten where (`soort_id` =3 or `soort_id`=4 or `soort_id`=8 or `soort_id` = 9) order by locatie_id";
		$hardware_result= mysql_query($hardware_query)
		while($hardware_row = mysql_fetch_row($hardware_result))
		{
			echo "<option value=".$hardware_row[0]."> ".$hardware_row[1]."</option>";
		}
		?>
		</select>
		echo "Beschrijving";
		?>
			<textarea rows="25" cols="100" name="description" form="new_incident"  > </textarea>
			<select name="hardware">
			<option value="NULL"> </option>
		<?php
		<input type="submit" name="send_incident" value = "Ga verder">
		<?php
	}
	else
	{
		
	}
	*/
?>