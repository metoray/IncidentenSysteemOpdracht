<?php
	if(!isset($username)){
		$username = "";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Inloggen</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>
	<?php
	if(isset($error))
		print "	<div class=\"alert alert-danger\" role=\"alert\">
					$error
				</div>";
	?>
	<div class="col-md-2 col-md-offset-5">
		<div class="well well-md" style="margin-top: 100px;">
			<form action="/process/login.php" method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="username" value="<?php echo $username;?>" placeholder="Gebruikersnaam"/>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="password" placeholder="Wachtwoord"/>
				</div>
				<div class="text-right">
					<button type="submit" class="btn btn-default">
						Inloggen<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
					</button>
				</div>
			</form>
		</div>
	</div>
</body>
</html>