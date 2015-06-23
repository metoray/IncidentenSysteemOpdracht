<?php
function av($arr,$key){
	if(isset($arr) and isset($arr[$key])){
		$val = $arr[$key];
		if(is_string($val)){
			$val = trim($val);
		}
		return $val;
	}
	die("Error: 3735928559 ".(base64_encode($key)));
}

$questionID = av($_POST,'id');
$question = Question::fromID($questionID);

$text = av($_POST,'text');
$answers = av($_POST,'answers');

$question -> setText($text);
$question -> save();

foreach ($answers as $key => $answer) {
	$text = av($answer,'text');
	$next = av($answer,'next');
	$temp = av($answer,'template');
	if($key=='new'){
		if($text!=''){
			$answer = new Answer($text,$next,$temp,$questionID);
			$answer -> save();
		}
		continue;
	}
	else{
		$next = ($next==0) ? null : $next;
		$temp = ($temp==0) ? null : $temp;
		$answer = Answer::fromID($key);
		if($answer){
			$answer -> setText($text);
			$answer -> setNext($next);
			$answer -> setIncidentTemplate($temp);
			$answer -> save();
		}
	}
}
header("Location: /cmdb/questions/edit?id={$questionID}");
?>