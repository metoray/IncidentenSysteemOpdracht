<?php
require_once 'include/database.php';
require_once 'include/util.php';

class Test {

	public function __construct($name,$function,$expected){
		$this->name = $name;
		$this->func = $function;
		$this->expected = $expected;
	}

	public function getResults(){
		$result = call_user_func_array($this->func, array());
		$success = $result === $this->expected;
		return array($this->name,$result,$this->expected,$success);
	}
}

$tests = array();

$tests[] = new Test("Create user",
function(){
	$user = new User("test-create-user","test-dept","(06)1234567","testlaan 1337",1);
	$user -> setPassword("password");
	$error = $user -> save();
	$user = User::fromName("test-create-user");
	$result = $user -> getName();
	$user -> delete();
	if($error){
		return $error;
	}
	return $result;
},"test-create-user"
);

$tests[] = new Test("Auth. with correct pw",
function(){
	$user = new User("test-auth-user","test-dept","(06)1234567","testlaan 1337",1);
	$user -> setPassword("password");
	$user -> save();
	$user = User::fromName("test-auth-user");
	$result = $user -> authorize("password");
	$user -> delete();
	return $result;
},true
);

$tests[] = new Test("Auth. with wrong pw",
function(){
	$user = new User("test-auth-user","test-dept","(06)1234567","testlaan 1337",1);
	$user -> setPassword("password");
	$user -> save();
	$user = User::fromName("test-auth-user");
	$result = $user -> authorize("wrong-password");
	$user -> delete();
	return $result;
},false
);

$tests[] = new Test("Retrieve role from DB",
function(){
	$role = Role::fromID(2);
	return $role -> getName();
},"IncidentManager"
);

$tests[] = new Test("Role has right",
function(){
	$role = Role::fromID(2);
	return $role -> hasRight("PROCESS_TICKET");
},true
);

$tests[] = new Test("Role from user",
function(){
	$user = new User("test-role-user","test-dept","(06)1234567","testlaan 1337",1);
	$user -> setPassword("password");
	$user -> save();
	$user = User::fromName("test-role-user");
	$result = $user -> getRole() -> getName();
	$user -> delete();
	return $result;
},"Customer"
);

$tests[] = new Test("Unauthorized user has no rights",
function(){
	$user = new User("test-right-user","test-dept","(06)1234567","testlaan 1337",1);
	$user -> setPassword("password");
	$user -> save();
	$user = User::fromName("test-right-user");
	$result = $user -> hasRight("CREATE_TICKET");
	$user -> delete();
	return $result;
},false
);

$tests[] = new Test("Unauthorized user has rights",
function(){
	$user = new User("test-right-user","test-dept","(06)1234567","testlaan 1337",1);
	$user -> setPassword("password");
	$user -> save();
	$user = User::fromName("test-right-user");
	$user -> authorize("password");
	$result = $user -> hasRight("CREATE_TICKET");
	$user -> delete();
	return $result;
},true
);
?>

<html>
	<head>
		<title>Tests</title>
		<style type="text/css">
			td {
				border: double black;
			}
		</style>
	</head>
	<body>
		<table>
			<thead>
				<tr>
					<td>
						Name
					</td>
					<td>
						Result
					</td>
					<td>
						Expected
					</td>
				</tr>
			</thead>
			<tbody>
				<?php
foreach($tests as $test){
	$results = $test->getResults();
	$color = $results[3]?"green":"red";
	$result = str($results[1]);
	$expected = str($results[2]);
	echo "<tr style=\"background: {$color};\"><td>{$results[0]}</td><td>{$result}</td><td>{$expected}</td></tr>";
}
				?>
				<tr><td colspan=3>ALL TESTS COMPLETED</td></tr>
			</tbody>
		</table>
	</body>
</html>