<?php
if(isset($_GET['id'])){
	$id = $_GET['id'];
	$question = Question::fromID($id);
	$text = $question -> getText();
}
elseif(isset($_POST['text'])){
	$text = $_POST['text'];
	$question = new Question($text);
	$question -> save();
	$id = $question -> getID();
}

$title = "Vraag $id";

if(!(isset($question)&&$question!=null)){
	header("Refresh: 3; URL=/cmdb/questions");
	echo "Geen vraag gevonden! Je browser gaat nu terug naar de <a href=\"/cmdb/questions\">lijst met vragen</a>!";
	die(); 
}
echo <<<HTML
<div class="col-md-6">
	<form>
		<div class="input-group">
			<span class="input-group-addon" id="text-desc">Vraag tekst</span>
			<input type="text" class="form-control" placeholder="Tekst" aria-describedby="text-desc" value="{$text}">
		</div>
	</form>
</div>
HTML;
?>