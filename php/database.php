<html>
<head></head>
<body>
<?php
require('util.php');
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
//// WARNING: THIS CODE IS NOT FINISHED AND COMPLETELY UNTESTED. ////
//// I KNOW THIS IS NOT COMPATIBLE WITH THE CURRENT DATABASE.    ////
//// DO NOT RUN THIS CODE IN ITS CURRENT STATE!                  ////
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////

try{
	$db = new PDO('mysql:host=localhost;dbname=rick_hondsrug;charset=utf8', 'site', 'site');
}
catch(PDOException $ex){
	die($ex->getMessage());
}


class User{

	/*
	Constructor function to create a new user using the specified parameters.
	*/
	function __construct($name,$dept,$tel,$address,$role,$pw=null,$id=null){
		$this->id = $id; //id of the user, should be null for new users
		$this->name = $name; //string containing username
		$this->dept = $dept; //string naming the user's department
		$this->tel = $tel; //string containing the user's telephone number
		$this->address = $address; //string containing the user's address
		$this->role = $role; //TBD: object describing the person's role
		if ($pw!=null) {
			$this->setPassword($pw);
		}
	}

	/*
	This function sets the $pw variable to a hash made using the supplied password.
	*/
	public function setPassword($pw){
		$hash = password_hash($pw, PASSWORD_BCRYPT);
		if($hash!=false){
			$this->pw = $hash;
		}
	}

	/*
	Various getters and setters for user data
	*/
	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getDept(){
		return $this->dept;
	}

	public function setDept($dept){
		$this->dept = $dept;
	}

	public function getTelephone(){
		return $this->tel;
	}

	public function setTelephone($tel){
		$this->tel = $tel;
	}

	public function getAddress(){
		return $this->address;
	}

	public function setAddress($address){
		$this->address = $address;
	}

	public function getRole(){
		return $this->role;
	}

	public function setRole($role){
		$this->role = $role;
	}

	/*
	This function returns true if the supplied password matches the set password, false otherwise.
	*/
	public function verifyPassword($pw){
		return password_verify($pw,$this->pw);
	}

	/*
	This function inserts a row for the user if the user is new.
	*/
	public function save(){
		global $db;
		if($this->id==null){
			$stmt = $db -> prepare("INSERT INTO gebruikers (naam,afdeling,telefoon,adres,wachtwoord,rol_id) VALUES (:name,:dept,:tel,:address,:pw,:role)");
			$stmt -> bindValue(':name', $this->name, PDO::PARAM_STR);
			$stmt -> bindValue(':dept', $this->dept, PDO::PARAM_STR);
			$stmt -> bindValue(':tel', $this->tel, PDO::PARAM_STR);
			$stmt -> bindValue(':address', $this->address, PDO::PARAM_STR);
			$stmt -> bindValue(':pw', $this->pw, PDO::PARAM_STR);
			$stmt -> bindValue(':role', $this->role->getId(), PDO::PARAM_INT);
			$stmt->execute();
		}
		else{

		}
	}

	/*
	This function finds a user from the database.
	*/
	public static function fromName($name){
		global $db;
		$stmt = $db -> prepare("SELECT * FROM gebruikers WHERE naam = :name");
		$stmt -> bindValue(':name', $name, PDO::PARAM_STR);
		$stmt->execute();
		$row = $stmt -> fetch();
		$role = null;	//needs improvement
		return new User($row['naam'],$row['afdeling'],$row['telefoon'],$row['adres'],$role,$row['wachtwoord'],$row['gebruiker_id']);
	}

}

//TEST CODE; REMOVE LATER
$user = User::fromName("Gebruiker1");
print lines($user -> getName(),$user -> getDept(),$user -> getAddress(),$user -> getTelephone());

?>
</body>
</html>