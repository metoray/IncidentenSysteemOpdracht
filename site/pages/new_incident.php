<?php
	$user = $_SESSION["user"] ->getID();
		
	include "include/connect.php";
	
	$query_user = "select * from gebruikers where gebruiker_id= ".$user."";

	$user_result = mysqli_query($con,$query_user) or die(mysql_error());
	$user_row =  mysqli_fetch_row($user_result);

	$text = "";
	$imp = null;
	$urg = null;
	$pri = null;
	if(isset($_SESSION['answers'])){
		$list = AnswerList::fromArray(0,$_SESSION['answers']);
		$template = $list -> getTemplate();
		if($template){
			$text = $template -> getText();
			$imp = $template -> getImpact();
			$urg = $template -> getUrgency();
			$pri = $template -> getPriority();
		}
		echo $list -> render();
	}

	 	if($user_row[5] == 1)
	 	{
			echo "Gebruiker : ".$user_row[1];
			?>
			<form action="/process/robert" method="post" id="send_incident_user">
			<?php
			$hardware_query = "select hardware_id, identificationcode from hardwarecomponenten where (soort_id =3 or soort_id=4 or soort_id=8 or soort_id = 9) order by locatie_id ";
			$hardware_result= mysqli_query($con,$hardware_query);
			echo "Hardware";
			echo "<select name=hardware>";
			while($hardware_row = mysqli_fetch_row($hardware_result))
			{
				echo "<option value=".$hardware_row[0]."> ".$hardware_row[1]."</option>";
			}
			
			echo "</select>";
			$software_query = "select software_id, identificatiecode from software ";
			$software_result= mysqli_query($con,$software_query);
			echo "<br />";
			echo "Software";
			echo "<select name=software>";
			echo "<option value=NULL>Hardware probleem </option>";
			while($software_row = mysqli_fetch_row($software_result))
			{
				echo "<option value=".$software_row[0]."> ".$software_row[1]."</option>";
			}
			echo "</select>";
			echo "<br />";
			echo "Beschrijving";
			echo "<br />";
			?>
			<textarea rows="25" cols="100" name="description" form="send_incident_user"  ><?php echo $text; ?></textarea>
			<?php
			echo "<br />";
			?>
		
			<input type="submit" name="send_incident_user" value = "Verwerk">
			</form>
			<?php
		}
		else
		{
			
			?>
			<form action="/process/robert" method="post" id="send_incident_practioner">
			
			<?php
			echo "Gebruiker			";
			$user_query = "select * from gebruikers";
			$user_result=mysqLi_query($con,$user_query);
			echo "<select name=user>";
			$test;
			while($user_row = mysqli_fetch_row($user_result))
			{
				echo "<option value=".$user_row[0]."> ".$user_row[1]."</option>";
				$test = $user_row[0];
			}
			echo "</select>"; 	
			echo "<br />";
			echo "Behandelaar";
			$practioner_query = "select * from gebruikers where not rol_id =1 ";
			$practioner_result=mysqli_query($con,$practioner_query);
			echo "<select name=practioner>";
			while($practioner_row = mysqli_fetch_row($practioner_result))
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
			$hardware_result= mysqli_query($con,$hardware_query);
			echo "Hardware			";
			echo "<select name=hardware>";
			while($hardware_row = mysqli_fetch_row($hardware_result))
			{
				echo "<option value=".$hardware_row[0]."> ".$hardware_row[1]."</option>";
			}
			echo "</select>";
			$software_query = "select software_id, identificatiecode from software ";
			$software_result= mysqli_query($con,$software_query);
			echo "<br />";
			echo "Software			";
			echo "<select name=software>";
			echo "<option value=NULL>Hardware probleem </option>";
			while($software_row = mysqli_fetch_row($con,$software_result))
			{
				echo "<option value=".$software_row[0]."> ".$software_row[1]."</option>";
			}
			echo "</select>";
			echo "<br />";
			echo "<br />";

			foreach (array('impact'=>$imp,'urgentie'=>$urg,'prioriteit'=>$pri) as $aspect => $default) {
				echo ucfirst($aspect).":";
				echo "<select name=\"{$aspect}\">";
				for($x = 1; $x<4; $x++){
					$selected = ($x==$default)?' selected':'';
					echo "<option value =".$x."{$selected}> ".$x."</option>";
				}
				echo "</select>";	
			}

			echo "<br />";
			echo "Beschrijving";
			echo "<br />";
			echo "<br />";
			?>
				<textarea rows="25" cols="100" name="description" form="send_incident_practioner"  ><?php echo $text; ?></textarea>
			<?php
			echo "<br />";
			?>
		
			<input type="submit" name="send_incident_practioner" value = "Verwerk">
			</form>
			<?php
		}
?>
