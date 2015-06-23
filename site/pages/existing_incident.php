<?php
//include "connect.php";
$connect			=new mysqli('localhost', 'root', '', 'rick_hondsrug');
$db =mysql_select_db( "rick_hondsrug");
//$current_incident 	= $_GET["inc_id"];
$current_incident 	=3004;
$search_current 	= "select * from incidenten where inc_id = ".$current_incident."";
$current_result 	= mysqli_query($connect,$search_current) or die(mysql_error());
$current_row 		= mysqli_fetch_row($current_result);


?>
<html>
	<body>
		
		<?php
	
			$search_practitoner = "select naam from gebruikers where ".$current_row[12]."";
			$practitoner_result = mysqli_query($connect,$search_practitoner) or die(mysql_error());
			$practitoner = mysqli_fetch_row($practitoner_result);
			echo "<br />";
			echo "behandelaar:";
			echo $practitoner[0];
			echo "<br />";
			
		

			$search_statussen_incidenten = "select * from statussen_incident where id=".$current_row[7]."";
			$search_statussen_incidenten_result = mysqli_query($connect, $search_statussen_incidenten) or die(mysql_error());
			
			while($search_statussen_incidenten_row = mysqli_fetch_array($search_statussen_incidenten_result))
			{	
			 	echo "STATUS:"; 
				echo $search_statussen_incidenten_row[1];
			}
			echo "<br />";
			echo "Start incident:	";
			echo $current_row[1]; 
			echo "<br />";
			echo "Eind incident:	";
			echo $current_row[5];
		?>
	
		<br />
		
		Beschrijving:
		<br />
		<textarea rows="25" cols="100" name="description" form="edit_problem"  disabled><?php echo	$current_row[2] ?> </textarea>
		<br />
		Workaround:
		<br />
		<textarea rows="25" cols="100" name="solution" form="edit_problem"  disabled><?php echo	$current_row[3] ?> </textarea>
		<br />
	
	
		


