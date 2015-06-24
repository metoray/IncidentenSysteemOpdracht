<?php
if(isset($_GET['a'])){
	$answer = Answer::fromID($_GET['a']);
	print_r($answer);
	if($answer){
		$_SESSION['answers'][] = $answer;
		if($answer->getNext()){
			$questionID = $answer -> getNext();
		}
		elseif($answer->getIncidentTemplate()){
			header("Location: /new_incident?template={$answer->getIncidentTemplate()}");
			die();
		}
	}
}
else {
	$questionID = 1;
	$_SESSION['answers'] = array();
}

if(!isset($questionID)){
	header("Refresh: 5; url=/new_incident");
	die("<strong>Voor dit antwoord zijn geen voorzieningen getroffen, je wordt doorverwezen naar het incidenten formulier.</strong>");
}

$question = Question::fromID($questionID);

$answers = '';

foreach ($question->getAnswers() as $answer) {
		$answers .= '<li class="list-group-item"><a href="/question?a='.$answer->getID().'" class="btn btn-primary btn-lg btn-block">'.$answer->getText().'</a></li>';
}

echo <<<PANEL
<div class="col-md-6">
	<div class="panel panel-default">
		<div class="panel-heading"><h1>{$question->getText()}</h1></div>
			<ul class="list-group">
				{$answers}
			</ul>
		</div>
	</div>
</div>
PANEL;
?>