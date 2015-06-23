<?php
if(isset($_GET['id'])){
	$question = Question::fromID($_GET['id']);
	if($question) $question -> delete();
}
header("Location: /cmdb/questions");
?>