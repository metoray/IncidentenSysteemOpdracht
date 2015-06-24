<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th></th>
			<th>Impact</th>
			<th>Urgentie</th>
			<th>Prioriteit</th>
			<th>Beschrijving</th>
		</tr>
	</thead>
	<tbody>
<?php
$templates = IncidentTemplate::getAll();
$hilo = array("[Geen]","Laag","Normaal","Hoog");

function getHiloOptions($current){
	global $hilo;
	$hiloOptions = '';
	$current = $current?$current:0;
	foreach ($hilo as $idx => $txt) {
		$selected = ($idx==$current)?' selected="selected"':'';
		$hiloOptions .= '<option value="'.$idx.'"'.$selected.'>'.$txt.'</option>';
	}
	return $hiloOptions;
}

foreach ($templates as $template) {
	$id = $template->getID();
	$text = htmlentities($template->getText());
	$text = $text?$text:"<i>Geen beschrijving</i>";
	$imp = $template->getImpact();
	$urg = $template->getUrgency();
	$pri = $template->getPriority();

	$imp = $imp?$imp:0;
	$urg = $urg?$urg:0;
	$pri = $pri?$pri:0;

	$imp = $hilo[$imp];
	$urg = $hilo[$urg];
	$pri = $hilo[$pri];

	echo <<<HTML
		<tr>
			<td>{$template->getID()}</td>
			<td>
				<div class="input-group">
					<a class="btn btn-danger btn-xs" href="/process/template_delete?id={$id}"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a>
				</div>
			</td>
			<td>{$imp}</td>
			<td>{$urg}</td>
			<td>{$pri}</td>
			<td  style="max-width: 100%;">{$text}</td>
		</tr>
HTML;
}
$defaultHilo = getHiloOptions(-1);
echo <<<HTML
		<tr>
			<form action="/process/template_new" method="post">
				<td></td>
				<td>
					<button type="submit" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
				</td>
				<td>
					<select name="impact">{$defaultHilo}</select>
				</td>
				<td>
					<select name="urgency">{$defaultHilo}</select>
				</td>
				<td>
					<select name="priority">{$defaultHilo}</select>
				</td>
				<td style="max-width: 100%;"><input class="form-control" type="text" name="text" placeholder="Beschrijving" /></td>
			</form>
		</tr>
	</tbody>
</table>
HTML;
?>