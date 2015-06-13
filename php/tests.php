<?php
require_once 'database.php';
require_once 'util.php';

class Test {

	public function __construct($name,$function,$expected){
		$this->name = $name;
		$this->func = $function;
		$this->expected = $expected;
	}

	public function getResults(){
		$result = call_user_func_array($this->func, array());
		$success = $result == $this->expected;
		return array($this->name,$result,$this->expected,$success);
	}
}

$tests = array();

$tests[] = new Test("Check password",
function(){
	$user = new User("auth-test","test-dept","(06)1234567","testlaan 1337",null);
	$user -> setPassword("password");
	$user -> save();
	$user = User::fromName("auth-test");
	return $user -> authorize("password");
},true
);

$tests[] = new Test("Check wrong password",
function(){
	$user = new User("auth-test2","test-dept","(06)1234567","testlaan 1337",null);
	$user -> setPassword("password");
	$user -> save();
	$user = User::fromName("auth-test2");
	return $user -> authorize("wrong-password");
},false
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
			</tbody>
		</table>
	</body>
</html>