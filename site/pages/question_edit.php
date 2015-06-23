<?php
if(isset($_GET['id'])){
	$questionID = $_GET['id'];
	$question = Question::fromID($questionID);
	$text = $question -> getText();
}
elseif(isset($_POST['text'])){
	$text = $_POST['text'];
	$question = new Question($text);
	$question -> save();
	$questionID = $question -> getID();
}

$title = "Vraag $questionID";

if(!(isset($question)&&$question!=null)){
	header("Refresh: 3; URL=/cmdb/questions");
	echo "Geen vraag gevonden! Je browser gaat nu terug naar de <a href=\"/cmdb/questions\">lijst met vragen</a>!";
	die(); 
}

$allQuestions = Question::getAll();
$incidentTemplates = IncidentTemplate::getAll();

/*
$questionOptions = '<option value="0">[Geen volgende vraag]</option>';
foreach ($allQuestions as $q) {
	$questionOptions .= '<option value="'.$q->getID().'">'.$q->getText().'</option>';
}
$templateOptions = '<option value="0">[Geen standaardincident]</option>';
foreach ($incidentTemplates as $template) {
	$templateOptions .= '<option value="'.$template->getID().'">'.$template->getText().'</option>';
}
*/

function displayList($list,$current,$default) {
	$options = '<option value="0">['.$default.']</option>';
	foreach ($list as $item) {
		$active = ($item->getID()==$current)?'selected="selected"':'';
		$options .= '<option value="'.$item->getID().'" '.$active.'>'.$item->getText().'</option>';
	}
	return $options;
}

$answers = '';
foreach ($question->getAnswers() as $answer) {
	$id = $answer->getID();
	$currentQuestion = $answer->getNext();
	$questionOptions = displayList($allQuestions,$currentQuestion,'Geen volgende vraag');
	$currentTemplate = $answer->getIncidentTemplate();
	$templateOptions = displayList($incidentTemplates,$currentTemplate,'Geen standaardincident');
	$answers .=<<<HTML
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-3">
				<div class="input-group">
					<span class="input-group-addon" id="text-desc">Antwoord</span>
					<input type="text" class="form-control" name="answers[{$id}][text]" placeholder="Tekst" value="{$answer->getText()}" />
				</div>
			</div>
			<div class="col-md-4">
				<select class="form-control" name="answers[{$id}][next]">
					{$questionOptions}
				</select>
			</div>
			<div class="col-md-4">
				<select class="form-control" name="answers[{$id}][template]">
					{$templateOptions}
				</select>
			</div>
			<div class="col-md-1">
				<a class="btn btn-danger btn-xs" href="/process/delete_answer?id={$id}&q={$questionID}"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a>
			</div>
		</div>
	</div>
HTML;
}
$questionOptions = displayList($allQuestions,null,'Geen volgende vraag');
$templateOptions = displayList($incidentTemplates,null,'Geen standaardincident');
echo <<<HTML
<form action="/process/question" method="post">
	<input type="hidden" name="id" value={$questionID} />
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="input-group">
				<span class="input-group-addon" id="text-desc">Vraag tekst</span>
				<input type="text" class="form-control" placeholder="Tekst" name="text" value="{$text}" />
			</div>
		</div>
	</div>
	{$answers}
	<div class="panel panel-success">
		<div class="panel-heading">
			Nieuw Antwoord
		</div>
		<div class="panel-body">
			<div class="col-md-3">
				<div class="input-group">
					<span class="input-group-addon" id="text-desc">Antwoord</span>
					<input type="text" class="form-control" name="answers[new][text]" placeholder="Tekst (leeg laten om geen nieuwe vraag toe te voegen)" />
				</div>
			</div>
			<div class="col-md-4">
				<select class="form-control" name="answers[new][next]">
				{$questionOptions}
				</select>
			</div>
			<div class="col-md-4">
				<select class="form-control" name="answers[new][template]">
					{$templateOptions}
				</select>
			</div>
		</div>
	</div>
	<div class="text-right">
		<button type="submit" class="btn btn-primary" type="button">Opslaan <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button>
	</div>
</form>
HTML;
?>