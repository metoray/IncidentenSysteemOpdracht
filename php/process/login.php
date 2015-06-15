<?php
require_once '../include/database.php';

$username = $_POST['username'];
$password = $_POST['password'];

$user = User::fromName($username);
if($user){
	if(!$user -> authorize($password)){
		$error = "Dit wachtwoord klopt niet voor dit account!";
	}
	else{
		$_SESSION['user'] = $user;
	}
}
else{
	$error = "Deze gebruikersnaam bestaat niet!";
}

if(!isset($error)){
	header("Location: /home.php");
}
else{
	include '../login.php';
}
?>