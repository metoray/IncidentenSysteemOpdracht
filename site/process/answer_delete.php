<?php
if(isset($_GET['id'])){
	$answer = Answer::fromID($_GET['id']);
	if($answer) $answer -> delete();
}
if(isset($_GET['q'])){
	header("Location: /cmdb/questions/edit?id={$_GET['q']}");
}
?>