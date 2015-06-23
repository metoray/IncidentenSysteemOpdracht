<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th></th>
			<th>Vraag</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$questions = Question::getAll();
foreach ($questions as $question) {
	$id = $question->getID();
	$text = htmlentities($question->getText());
	echo <<<HTML
		<tr>
			<td class="shrink">{$question->getID()}</td>
			<td class="shrink text-right">
				<div class="input-group">
					<a class="btn btn-danger btn-xs" href="/process/delete_question?id={$id}"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a>
				</div>
			</td>
			<td class="expand"><a href="/cmdb/questions/edit?id={$id}">{$text}</a></td>
		</tr>
HTML;
}
?>
		<tr>
			<form action="/post.php" method="post">
				<td class="shrink"></td>
				<td class="shrink text-right">
					<button type="submit" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
				</td>
				<td class="expand"><input type="text" name="text" placeholder="Vraag" /></td>
			</form>
		</tr>
	</tbody>
</table>