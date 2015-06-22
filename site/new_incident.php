<?php
	/*
	$_session_start();
	$_session["user"];
	$user = $_session["user"]
		*/
	include "connect.php";
	$user= 203;
	$query_user = "select * from gebruikers where gebruiker_id= ".$user."";

	$user_result = mysql_query($query_user) or die(mysql_error());
	$user_row =  mysql_fetch_row($user_result);
	
	 	if($user_row[5] == 1)
	 	{
			echo "Gebruiker : ".$user_row[1];
			?>
			<form action="process.php" method="post" id="send_incident_user">
			<?php
			$hardware_query = "select hardware_id, identificationcode from hardwarecomponenten where (soort_id =3 or soort_id=4 or soort_id=8 or soort_id = 9) order by locatie_id ";
			$hardware_result= mysql_query($hardware_query);
			echo "Hardware";
			echo "<select name=hardware>";
			while($hardware_row = mysql_fetch_row($hardware_result))
			{
				echo "<option value=".$hardware_row[0]."> ".$hardware_row[1]."</option>";
			}
			
			echo "</select>";
			$software_query = "select software_id, identificatiecode from software ";
			$software_result= mysql_query($software_query);
			echo "<br />";
			echo "Software";
			echo "<select name=software>";
			echo "<option value=NULL>Hardware probleem </option>";
			while($software_row = mysql_fetch_row($software_result))
			{
				echo "<option value=".$software_row[0]."> ".$software_row[1]."</option>";
			}
			echo "</select>";
			echo "<br />";
			echo "Beschrijving";
			echo "<br />";
			?>
			<textarea rows="25" cols="100" name="description" form="send_incident_user"  ></textarea>
			<?php
			echo "<br />";
			?>
			<input type="hidden" name="user" value=<?php echo $user_row[0]; ?>	> 
			<input type="submit" name="send_incident_user" value = "Verwerk">
			</form>
			<?php
		}
		else
		{
			
			?>
			<form action="process.php" method="post" id="send_incident_practioner">
			
			<?php
			echo "Gebruiker			";
			$user_query = "select * from gebruikers";
			$user_result=mysqL_query($user_query);
			echo "<select name=user>";
			while($user_row = mysql_fetch_row($user_result))
			{
				echo "<option value=".$user_row[0]."> ".$user_row[1]."</option>";
			}
			echo "</select>"; 	
			echo "<br />";
			echo "Behandelaar";
			$practioner_query = "select * from gebruikers where not rol_id =1 ";
			$practioner_result=mysqL_query($practioner_query);
			echo "<select name=practioner>";
			while($practioner_row = mysql_fetch_row($practioner_result))
			{
			
			 	if($practioner_row[0] == $user )
			 	{
					echo "<option selected value=".$practioner_row[0]."  > ".$practioner_row[1]."</option>";
				}
				else
				{
					echo "<option value=".$practioner_row[0]."> ".$practioner_row[1]."</option>";
				}
			}
			echo "</select>"; 	
			echo "<br />";
			
			$hardware_query = "select hardware_id, identificationcode from hardwarecomponenten where (soort_id =3 or soort_id=4 or soort_id=8 or soort_id = 9) order by locatie_id ";
			$hardware_result= mysql_query($hardware_query);
			echo "Hardware			";
			echo "<select name=hardware>";
			while($hardware_row = mysql_fetch_row($hardware_result))
			{
				echo "<option value=".$hardware_row[0]."> ".$hardware_row[1]."</option>";
			}
			echo "</select>";
			$software_query = "select software_id, identificatiecode from software ";
			$software_result= mysql_query($software_query);
			echo "<br />";
			echo "Software			";
			echo "<select name=software>";
			echo "<option value=NULL>Hardware probleem </option>";
			while($software_row = mysql_fetch_row($software_result))
			{
				echo "<option value=".$software_row[0]."> ".$software_row[1]."</option>";
			}
			echo "</select>";
			echo "<br />";
			echo "<br />";
			echo "Impact:";
			echo "<select name=impact>";
			for($i = 1; $i<6; $i++)
			{
				echo "<option value =".$i."> ".$i."</option>";
				
			}
			echo "</select>";
			echo "Urgentie:";
			echo "<select name=urgentie>";
			for($j = 1; $j<6; $j++)
			{
				echo "<option value =".$j."> ".$j."</option>";
				
			}
			echo "</select>";
			echo "Prioriteit:";
			echo "<select name=prioriteit>";
			for($x = 1; $x<6; $x++)
			{
				echo "<option value =".$x."> ".$x."</option>";
				
			}
			echo "</select>";
			echo "<br />";
			echo "Beschrijving";
			echo "<br />";
			echo "<br />";
			?>
				<textarea rows="25" cols="100" name="description" form="send_incident_practioner"  ></textarea>
			<?php
			echo "<br />";
			?>
			<input type="hidden" name="user" value=<?php echo $user_row[0]; ?>	> 
			<input type="submit" name="send_incident_practioner" value = "Verwerk">
			</form>
			<?php
		}
		
		
	

?>