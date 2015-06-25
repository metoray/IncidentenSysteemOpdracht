<?php
if(isset($_POST['impact'],$_POST['urgency'],$_POST['priority'],$_POST['text'])){
	$imp = $_POST['impact'];
	$urg = $_POST['urgency'];
	$pri = $_POST['priority'];

	$imp = $imp?$imp:null;
	$urg = $urg?$urg:null;
	$pri = $pri?$pri:null;

	$text = $_POST['text'];

	$template = new IncidentTemplate($text,$imp,$urg,$pri);
	$template -> save();
}
header("Location: /cmdb/questions/templates");
?>