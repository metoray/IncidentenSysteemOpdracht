<?php
include 'database.php';

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
	$user -> setPassword("hunter2");
	$user -> save();
	$user = User::fromName("auth-test");
	return $user -> authorize("hunter2");
},true
);
?>

<html>
	<head>
		<title>Tests</title>
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
	echo "<tr style=\"background: {$color};\"><td>{$results[0]}</td><td>{$results[1]}</td><td>{$results[2]}</td></tr>";
}
				?>
			</tbody>
		</table>
	</body>
</html>