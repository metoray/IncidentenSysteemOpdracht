<html>
<head></head>
<body>
<?php
require('util.php');

try{
	$db = new PDO('mysql:host=localhost;dbname=rick_hondsrug;charset=utf8', 'site', 'site');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $ex){
	die($ex->getMessage());
}


class User{

	private $authorized = false;

	/*
	Constructor function to create a new user using the specified parameters.
	*/
	function __construct($name,$dept,$tel,$address,$roleID,$pw=null,$id=null){
		$this->id = $id; //id of the user, should be null for new users
		$this->name = $name; //string containing username
		$this->dept = $dept; //string naming the user's department
		$this->tel = $tel; //string containing the user's telephone number
		$this->address = $address; //string containing the user's address
		$this->roleID = $roleID;
		$this->pw = $pw; //the hashed password of the user

		$this->role = null;
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
		if($this->role == null){
			$this->role = new Role($this->roleID);
		}
		return $this->role;
	}

	public function setRole($role){
		$this->role = $role;
	}

	/*
	This function returns true if the user is logged in.
	*/
	public function isAuthorized(){
		return $this->authorized;
	}

	/*
	This function returns true if the supplied password matches the set password, false otherwise.
	If the password matches this will authorize the user.
	*/
	public function authorize($pw){
		$this->authorized = password_verify($pw,$this->pw);
		return $this->isAuthorized();
	}

	/*
	This function inserts a row for the user if the user is new.
	If the user is not new this function will change the user's data.
	*/
	public function save(){
		global $db;
		try{
			if($this->id==null){
				$stmt = $db -> prepare("INSERT INTO gebruikers (naam,afdeling,telefoon,adres,wachtwoord,rol_id) VALUES (:name,:dept,:tel,:address,:pw,:role);");
			}
			else{
				$stmt = $db -> prepare("UPDATE gebruikers SET naam=:name, afdeling=:dept, telefoon=:tel,adres=:address,wachtwoord=:pw,rol_id=:role WHERE id=:id;");
			}
			$stmt -> bindValue(':name', $this->name, PDO::PARAM_STR);
			$stmt -> bindValue(':dept', $this->dept, PDO::PARAM_STR);
			$stmt -> bindValue(':tel', $this->tel, PDO::PARAM_STR);
			$stmt -> bindValue(':address', $this->address, PDO::PARAM_STR);
			$stmt -> bindValue(':pw', $this->pw, PDO::PARAM_STR);

			if($this->role == null){
				$stmt -> bindValue(':role', $this->roleID, PDO::PARAM_INT);
			}
			else{
				$stmt -> bindValue(':role', $this->role -> getID(), PDO::PARAM_INT);
			}

			$stmt -> execute();

			if($this->id == null){
				$this->id = $db -> lastInsertId();
			}

		}
		catch(PDOException $ex){
			return $ex -> getMessage();
		}
		return false;
	}

	/*
	Function to delete this user.
	WARNING: THIS IS NOT RECOVERABLE
	*/
	public function delete(){
		global $db;
		try{
			$stmt = $db -> prepare("DELETE FROM gebruikers WHERE gebruiker_id = :id;");
			$stmt -> bindValue(':id', $this->id, PDO::PARAM_INT);
			$stmt -> execute();
		}
		catch(PDOException $ex){
			die($ex->getMessage());
		}
	}

	/*
	This function finds a user from the database.
	*/
	public static function fromName($name){
		global $db;
		try{
			$stmt = $db -> prepare("SELECT * FROM gebruikers WHERE naam = :name");
			$stmt -> bindValue(':name', $name, PDO::PARAM_STR);
			$stmt->execute();
			$row = $stmt -> fetch();
			if(!$row){
				return "USER DOES NOT EXIST";
			}
			return new User($row['naam'],$row['afdeling'],$row['telefoon'],$row['adres'],$row['rol'],$row['wachtwoord'],$row['gebruiker_id']);
		}
		catch(PDOException $ex){
			return $ex->getMessage();
		}
	}

}

class Role{

	//list of roles in database, is added-to when needed
	static $roles = array();

	/*
	constructor for role, required data is retrieved from the database.
	*/
	public function __construct($idorname){
		global $db;
		try{
			if(is_int($idorname)){
				$this->id = $idorname;

				$stmt = $db -> prepare("SELECT naam FROM rol WHERE id = :id;");
				$stmt->bindValue(':id', $idorname, PDO::PARAM_INT);
				$stmt->execute();
				$row = $stmt -> fetch();

				$this->name = $row['naam'];

				$stmt = $db -> prepare("SELECT recht.beschrijving FROM recht
										JOIN recht_bij_rol AS RBR ON (RBR.recht_id = recht.id)
										WHERE RBR.rol_id = :id;");
				$stmt->bindValue(':id', $idorname, PDO::PARAM_INT);
				$stmt->execute();

				$this->rights = $stmt -> fetchAll(PDO::FETCH_COLUMN,0);
			}
			else{
				$this->name = $idorname;

				$stmt = $db -> prepare("SELECT id FROM rol WHERE naam = :name;");
				$stmt->bindValue(':id', $idorname, PDO::PARAM_INT);
				$stmt->execute();
				$row = $stmt -> fetch();

				$this->id = $row['id'];

				$stmt = $db -> prepare("SELECT recht.beschrijving FROM recht
										JOIN recht_bij_rol AS RBR ON (RBR.recht_id = recht.id)
										JOIN rol ON (rol.id = RBR.rol_id)
										WHERE rol.naam = :name;");
				$stmt->bindValue(':name', $idorname, PDO::PARAM_STR);
				$stmt->execute();

				$this->rights = $stmt -> fetchAll(PDO::FETCH_COLUMN,0);
			}
		}
		catch(PDOException $ex){
			die($ex->getMessage());
		}
	}

	/*
	Get role from id.
	*/
	public static function fromID($id){
		if(array_key_exists($id, $roles)){
			return $roles[$id]; //role is in array, return this
		}
		else{
			$role = new Role($id); //role is not in array, add to array and return
			$roles[$id] = $role;
			return $role;
		}
	}

}
/*
//TEST CODE; REMOVE LATER
print "<br>====START ROLE TEST====<br>";
$role = new Role(2);
print $role->name;
print "<br>";
print_r($role->rights);
print "<br>====END ROLE TEST====<br>";
*/

?>
</body>
</html>
