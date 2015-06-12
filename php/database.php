<?php
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
//// WARNING: THIS CODE IS NOT FINISHED AND COMPLETELY UNTESTED. ////
//// I KNOW THIS IS NOT COMPATIBLE WITH THE CURRENT DATABASE.    ////
//// DO NOT RUN THIS CODE IN ITS CURRENT STATE!                  ////
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////

$db = new PDO('mysql:host=localhost;dbname=rick_hondsrug;charset=utf8', 'site', 'site_pw');

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
	This function returns true if the supplied password matches the set password, false otherwise.
	*/
	public function verifyPassword($pw){
		return password_verify($pw,$this->pw);
	}

	/*
	This function inserts a row for the user if the user is new.
	*/
	public function save(){
		if($this->id==null){
			$stmt = $db -> prepare("INSERT INTO gebruikers (naam,afdeling,telefoon,adres,wachtwoord,rol_id) VALUES (:name,:dept,:tel,:address,:pw,:role)");
			$stmt -> bindValue(':name', $this->name, PDO::PARAM_STR)
			$stmt -> bindValue(':dept', $this->dept, PDO::PARAM_STR)
			$stmt -> bindValue(':tel', $this->tel, PDO::PARAM_STR)
			$stmt -> bindValue(':address', $this->address, PDO::PARAM_STR)
			$stmt -> bindValue(':pw', $this->pw, PDO::PARAM_STR)
			$stmt -> bindValue(':role', $this->role->getId(), PDO::PARAM_INT)
			$stmt->execute();
		}
		else{

		}
	}

	/*
	This function finds a user from the database.
	*/
	public static function fromName($name){
		$stmt = $db -> prepare("SELECT * FROM gebruikers WHERE naam = :name");
		$stmt -> bindValue(':name', $name, PDO::PARAM_STR);
		$stmt->execute();
	}

}

?>