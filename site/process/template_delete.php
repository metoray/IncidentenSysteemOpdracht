<?php
if(isset($_GET['id'])){
	$template = IncidentTemplate::fromID($_GET['id']);
	if($template) $template -> delete();
}
header("Location: /cmdb/questions/templates");
?>