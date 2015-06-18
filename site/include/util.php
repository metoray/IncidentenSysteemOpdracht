<?php
function str($var){
	if(is_bool($var)){
		return $var?'true':'false';
	}
	return $var;
}
?>
