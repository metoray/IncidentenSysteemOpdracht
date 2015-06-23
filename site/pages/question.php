<?php
$questionID = isset($_GET['q'])?$_GET['q']:1;
$question = Question::fromID($questionID);
$answers = '';

foreach ($question->getAnswers() as $answer) {
	$answers .=<<<ANSWER
					<li class="list-group-item"><button class="btn btn-primary btn-lg btn-block">{$answer->getText()}</button></li>
ANSWER;
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