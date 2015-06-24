<?php
include(dirname(dirname(__FILE__)).'/lib/password.php'); //ugh
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
try{
	list($user, $pw) = explode(':',file_get_contents(dirname(__FILE__)."/db.cfg"));
	$db = new PDO('mysql:host=localhost;dbname=rick_hondsrug;charset=utf8', $user, $pw, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
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
	public function getID(){
		return $this->id;
	}


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
			$id = (int)($this->roleID);
			$this->role = new Role($id);
		}
		return $this->role;
	}

	public function setRole($role){
		$this->role = $role;
	}

	public function hasRight($right){
		if(!$this->isAuthorized()){
			return false;
		}
		return $this->getRole()->hasRight($right);
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
				return null;
			}
			return new User($row['naam'],$row['afdeling'],$row['telefoon'],$row['adres'],$row['rol_id'],$row['wachtwoord'],$row['gebruiker_id']);
		}
		catch(PDOException $ex){
			error_log($ex);
			return null;
		}
	}

}

class Role{

	//list of roles in database, is added-to when needed
	static $roleList = array();
	//list of rights
	static $rightList = array();

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
				if(!$row) return null;

				$this->name = $row['naam'];

			}
			else{
				$this->name = $idorname;

				$stmt = $db -> prepare("SELECT id FROM rol WHERE naam = :name;");
				$stmt->bindValue(':id', $idorname, PDO::PARAM_INT);
				$stmt->execute();
				$row = $stmt -> fetch();
				if(!$row) return null;

				$this->id = $row['id'];
			}

			$stmt = $db -> prepare("SELECT recht.beschrijving FROM recht
									JOIN recht_bij_rol AS RBR ON (RBR.recht_id = recht.id)
									WHERE RBR.rol_id = :id;");
			$stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
			$stmt->execute();

			$this->rights = $stmt -> fetchAll(PDO::FETCH_COLUMN,0);
			array_merge(Role::$rightList,$this->rights);
		}
		catch(PDOException $ex){
			die($ex->getMessage());
		}
	}

	/*
	Get role name.
	*/
	public function getName(){
		return $this->name;
	}

	/*
	Check if the role contains a certain right.
	*/
	public function hasRight($right){
		return in_array($right,$this->rights);
	}

	/*
	Get role from id.
	*/
	public static function fromID($id){
		if(array_key_exists($id, Role::$roleList)){
			return Role::$roleList[$id]; //role is in array, return this
		}
		else{
			$role = new Role($id); //role is not in array, add to array and return
			Role::$roleList[$id] = $role;
			return $role;
		}
	}

}

class Hardware{

	public function __construct($arg){
		global $db;
		if(is_numeric($arg)){
			$stmt = $db -> prepare("SELECT * FROM hardwarecomponenten JOIN soort_hardware ON (hardwarecomponenten.soort_id = soort_hardware.soort_h_id) WHERE hardware_id = :id;");
			$stmt -> bindValue('id', $arg, PDO::PARAM_INT);
		}
		else{
			$stmt = $db -> prepare("SELECT * FROM hardwarecomponenten JOIN soort_hardware ON (hardwarecomponenten.soort_id = soort_hardware.soort_h_id) WHERE identificationcode = :id_code;");
			$stmt -> bindValue('id_code', $arg, PDO::PARAM_STR);
		}
		$stmt -> execute();
		$row = $stmt -> fetch();
		if(!$row) return null;

		$this->id = $row['hardware_id'];
		$this->id_code = $row['identificationcode'];
		$this->kind = $row['beschrijving'];
		$this->location_id = $row['locatie_id'];
		$this->brand_id = $row['merk_id'];
		$this->supplier_id = $row['leverancier_id'];
		$this->year = $row['jaar_van_aanschaf'];
	}

	public function install($software_id){
		$stmt = $db -> prepare("DELETE FROM installatie WHERE hardware_id = :hwid AND software_id = :soft_id");
		$stmt -> bindValue('hwid', $this->id, PDO::PARAM_INT);
		$stmt -> bindValue('soft_id',$software_id);
		$stmt -> execute();
	}

	public function uninstall($software_id){
		$stmt = $db -> prepare("INSERT INTO installatie VALUES (:hwid,:soft_id);");
		$stmt -> bindValue('hwid', $this->id, PDO::PARAM_INT);
		$stmt -> bindValue('soft_id',$software_id);
		$stmt -> execute();
	}

}

class Page{

	private $parent = null;

	public function __construct($id,$title,$file,$key,$right,$visible=true,$menu=true){
		$this->id = $id;
		$this->title = $title;
		$this->file = $file;
		$this->key = $key;
		$this->right = $right;
		$this->visible = (bool)$visible;
		$this->menu = (bool)$menu;
		$this->subPages = array();
	}

	public function getID(){
		return $this->id;
	}

	public function getTitle(){
		return $this->title;
	}

	public function getFile(){
		return $this->file;
	}

	public function usesMenu(){
		return $this->menu;
	}

	public function addSubpage($key,$page){
		$this->subPages[$key] = $page;
		$page->setParent($this);
	}

	public function hasSubpages(){
		return !empty($this->subPages);
	}

	public function hasVisibleSubpages(){
		foreach ($this->subPages as $key => $page) {
			if($page->isVisible()){
				return true;
			}
		}
		return false;
	}

	public function getSubpages(){
		return $this->subPages;
	}

	private function setParent($parent){
		$this->parent = $parent;
	}

	public function getFullPath(){
		if($this->parent==null){
			return array();
		}
		else{
			$path = $this -> parent -> getFullPath();
			if($this->key){
				$path[] = $this -> key;
			}
			return $path;
		}
	}

	public function isActive($activePath){
		$path = $this -> getFullPath();
		if($path===$activePath) return true;
		if($this->hasSubpages()){
			foreach ($this->subPages as $key => $page) {
				if($page->isActive($activePath) && (!$page -> isVisible())){
					return true;
				}
			}
		}
		return false;
	}

	public function getSubpage($key){;
		if(array_key_exists($key, $this->subPages)){
			return $this->subPages[$key];
		}
		return null;
	}

	public function hasAccess($user){
		if($user==null) return false;
		if($this->right==null){
			if(!$this->hasSubpages()){
				return true;
			}
			foreach ($this->subPages as $key => $page) {
				if($page->hasAccess($user)){
					return true;
				}
			}
			return false;
		}
		return $user -> hasRight($this->right);
	}

	public function isVisible(){
		return $this->visible;
	}

	public function find($path){
		if(empty($path)){
			return $this;
		}
		$key = array_shift($path);
		$subPage = $this-> getSubpage($key);
		if($subPage){
			return $subPage->find($path);
		}
		return $subPage;
	}

	public function getFullPathString(){
		return '/'.implode('/',$this->getFullPath());
	}

	public static function getPageStructure(){
		global $db;

		$pageList = array(); //an assoc array of assoc arrays indexed by super key and key
		$flat = array(); //an assoc array of pages
		$query =
		"SELECT 
    		page.id,
		    page.titel,
		    page.file,
		    page.sleutel,
		    page.zichtbaar,
		    page.menu,
		    super.sleutel AS super,
		    recht.beschrijving AS recht
		FROM
		    pagina page
		        LEFT JOIN
		    pagina super ON super.id = page.bovenliggende_pagina_id
		        LEFT JOIN
		    recht ON page.recht_id = recht.id
		ORDER BY page.volgorde;";

		foreach ($db->query($query) as $row) {
			$superKey = $row['super'];
			if(!array_key_exists($superKey, $pageList)){
				$pageList[$superKey] = array();
			}
			$page = new Page($row['id'],$row['titel'],$row['file'],$row['sleutel'],$row['recht'],$row['zichtbaar'],$row['menu']);
			$pageList[$superKey][$row['sleutel']] = $page;
			$flat[$row['sleutel']] = $page;
    	}
    	$root = new Page(null,null,null,null,null,1);
    	if(!array_key_exists(null, $pageList)){
    		return $root;		//no root tag present
    	}
    	foreach ($pageList[null] as $key => $page) {
    		$root -> addSubpage($key,$page);
    	}
    	unset($pageList[null]);
    	foreach ($pageList as $superKey => $pages) {
    		if(array_key_exists($superKey, $flat)){
    			foreach ($pages as $key => $page) {
    				$flat[$superKey] -> addSubpage($key,$page);
    			}
    		}
    	}
    	return $root;
	}

}

class Question{

	public function __construct($text,$id=null){
		$this->id = $id;
		$this->text = $text;
	}

	public function getID(){
		return $this->id;
	}

	public function setText($text){
		$this->text = $text;
	}

	public function getText(){
		return $this->text;
	}

	public function getAnswers(){
		$answers = array();
		global $db;
		$stmt = $db -> prepare('SELECT * FROM antwoord WHERE vraag_id=:id');
		$stmt -> bindValue('id', $this->id, PDO::PARAM_INT);
		$stmt -> execute();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$answers[] = new Answer($row['tekst'],$row['vervolg_vraag_id'],$row['default_ticket_id'],$this->id,$row['id']);
		}
		return $answers;
	}

	public function delete(){
		global $db;
		$stmt = $db -> prepare("UPDATE antwoord SET vervolg_vraag_id=NULL WHERE vervolg_vraag_id=:id;");
		$stmt -> bindValue('id',$this->id,PDO::PARAM_INT);
		$stmt -> execute();
		foreach ($this->getAnswers() as $answer) {
			$answer -> delete();
		}
		$stmt = $db -> prepare('DELETE FROM vraag WHERE id=:id');
		$stmt -> bindValue('id', $this->id, PDO::PARAM_INT);
		$stmt -> execute();
		$this -> id = null;
	}

	public function save(){
		global $db;
		if($this->id === null){
			$stmt = $db -> prepare('INSERT INTO vraag (tekst) VALUES (:text)');
			$stmt -> bindValue('text', $this->text, PDO::PARAM_STR);
			$stmt -> execute();
			$this->id = $db -> lastInsertId();
		}
		else{
			$stmt = $db -> prepare('UPDATE vraag SET tekst=:text WHERE id=:id;');
			$stmt -> bindValue('text', $this->text, PDO::PARAM_STR);
			$stmt -> bindValue('id', $this->id, PDO::PARAM_INT);
			$stmt -> execute();
		}
	}

	public static function fromID($id){
		global $db;
		$stmt = $db -> prepare('SELECT id,tekst FROM vraag WHERE id=:id');	//order of columns is known
		$stmt -> bindValue('id', $id, PDO::PARAM_INT);
		$stmt -> execute();
		list($id,$text) = $stmt -> fetch();
		return new Question($text,$id);
	}

	public static function getAll(){
		global $db;
		$questions = array();
		$stmt = $db -> prepare('SELECT id,tekst FROM vraag;');	//order of columns is known
		$stmt -> execute();
		while($row = $stmt -> fetch()){
			list($id,$text) = $row;
			$questions[] = new Question($text,$id);
		}
		return $questions;
	}

}

class Answer{

	public function __construct($text,$next,$template,$qid,$id=null){
		$this->qid = $qid;	//question id
		$this->text = $text;
		$this->next = (!$next)?null:$next;
		$this->template = (!$template)?null:$template;
		$this->id = $id;
	}

	public function getText(){
		return $this->text;
	}

	public function getNext(){
		return $this->next;
	}

	public function getIncidentTemplate(){
		return $this->template;
	}

	public function setText($txt){
		$this->text = $txt;
	}

	public function setNext($nxt){
		$this->next = $nxt;
	}

	public function setIncidentTemplate($tmp){
		$this->template = $tmp;
	}

	public function getQuestion(){
		return Question::fromID($this->qid);
	}

	public function delete(){
		global $db;
		$stmt = $db -> prepare('DELETE FROM antwoord WHERE id=:id');
		$stmt -> bindValue('id', $this->id, PDO::PARAM_INT);
		$stmt -> execute();
		$this -> id = null;
	}

	public function save(){
		global $db;
		if($this->id === null){
			$stmt = $db -> prepare('INSERT INTO antwoord (tekst,vervolg_vraag_id,default_ticket_id,vraag_id) VALUES (:text,:next,:temp,:question)');
			$stmt -> bindValue('text', $this->text, PDO::PARAM_STR);
			$stmt -> bindValue('next', $this->next, PDO::PARAM_INT);
			$stmt -> bindValue('temp', $this->template, PDO::PARAM_INT);
			$stmt -> bindValue('question', $this->qid, PDO::PARAM_INT);
			$stmt -> execute();
			$this->id = $db -> lastInsertId();
		}
		else{
			$stmt = $db -> prepare('UPDATE antwoord SET tekst=:text, vervolg_vraag_id=:next, default_ticket_id=:temp, vraag_id=:question WHERE id=:id;');
			$stmt -> bindValue('id', $this->id, PDO::PARAM_INT);
			$stmt -> bindValue('text', $this->text, PDO::PARAM_STR);
			$stmt -> bindValue('next', $this->next, PDO::PARAM_INT);
			$stmt -> bindValue('temp', $this->template, PDO::PARAM_INT);
			$stmt -> bindValue('question', $this->qid, PDO::PARAM_INT);
			$stmt -> execute();
		}
	}

	public function getID(){
		return $this->id;
	}

	public static function fromID($id){
		global $db;
		$stmt = $db -> prepare('SELECT * FROM antwoord WHERE id=:id;');
		$stmt -> bindValue('id', $id, PDO::PARAM_INT);
		$stmt -> execute();
		$row = $stmt -> fetch(PDO::FETCH_ASSOC);
		if(!$row) return null;
		return new Answer($row['tekst'],$row['vervolg_vraag_id'],$row['default_ticket_id'],$row['vraag_id'],$id);
	}

}

class IncidentTemplate{

	public function __construct($desc,$impact,$urgency,$priority,$id=null){
		$this->id = $id;
		$this->desc = $desc;
		$this->impact = $impact;
		$this->urgency = $urgency;
		$this->priority = $priority;
	}

	public function getID(){
		return $this->id;
	}

	public function getText(){
		return $this->desc;
	}

	public function setText($text){
		$this->desc = $text;
	}

	public function getImpact(){
		return $this->impact;
	}

	public function setImpact($impact){
		return $this->impact;
	}

	public function getUrgency(){
		return $this->impact;
	}

	public function setUrgency($urgency){
		return $this->urgency;
	}

	public function getPriority(){
		return $this->priority;
	}

	public function setPriority($priority){
		return $this->priority;
	}

	public function delete(){
		global $db;
		$stmt = $db -> prepare("UPDATE antwoord SET default_ticket_id=NULL WHERE default_ticket_id=:id");
		$stmt -> bindValue('id', $this->id, PDO::PARAM_INT);
		$stmt -> execute();
		$stmt = $db -> prepare('DELETE FROM standaard_incident WHERE id=:id');
		$stmt -> bindValue('id', $this->id, PDO::PARAM_INT);
		$stmt -> execute();
		$this -> id = null;
	}

	public function save(){
		global $db;
		if($this->id === null){
			$stmt = $db -> prepare('INSERT INTO standaard_incident (omschrijving,impact,urgentie,prioriteit) VALUES (:desc,:impact,:urgency,:priority)');
			$stmt -> bindValue('desc', $this->desc, PDO::PARAM_STR);
			$stmt -> bindValue('impact', $this->impact, PDO::PARAM_INT);
			$stmt -> bindValue('urgency', $this->urgency, PDO::PARAM_INT);
			$stmt -> bindValue('priority', $this->priority, PDO::PARAM_INT);
			$stmt -> execute();
			$this->id = $db -> lastInsertId();
		}
		else{
			$stmt = $db -> prepare('UPDATE standaard_incident SET omschrijving=:desc, impact=:impact, urgentie=:urgency, prioriteit=:priority WHERE id=:id;');
			$stmt -> bindValue('id', $this->id, PDO::PARAM_INT);
			$stmt -> bindValue('desc', $this->desc, PDO::PARAM_STR);
			$stmt -> bindValue('impact', $this->impact, PDO::PARAM_INT);
			$stmt -> bindValue('urgency', $this->urgency, PDO::PARAM_INT);
			$stmt -> bindValue('priority', $this->priority, PDO::PARAM_INT);
			$stmt -> execute();
		}
	}

	public static function fromID($id){
		global $db;
		$stmt = $db -> prepare('SELECT * FROM standaard_incident WHERE id=:id;');
		$stmt -> bindValue('id', $id, PDO::PARAM_INT);
		$stmt -> execute();
		$row = $stmt -> fetch(PDO::FETCH_ASSOC);
		return new IncidentTemplate($row['omschrijving'],$row['impact'],$row['urgentie'],$row['prioriteit'],$row['id']);
	}

	public static function getAll(){
		global $db;
		$questions = array();
		$stmt = $db -> prepare('SELECT * FROM standaard_incident;');
		$stmt -> execute();
		while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
			$questions[] = new IncidentTemplate($row['omschrijving'],$row['impact'],$row['urgentie'],$row['prioriteit'],$row['id']);
		}
		return $questions;
	}

}

class AnswerList {

	public function __construct($incidentID){
		$this -> incidentID = $incidentID;
		$this -> answers = array();
	}

	public function save(){
		$stmt = $db -> prepare("INSERT INTO gebruikers_antwoorden (antwoord_id,inc_id,reeks_nummer) VALUES (:aid,:iid,:n)");
		$i = 1;
		foreach ($answers as $answer) {
			$stmt -> bindValue('aid',$answer->getID(),PDO::PARAM_INT);
			$stmt -> bindValue('iid',$this->incidentID,PDO::PARAM_INT);
			$stmt -> bindValue('n',$i,PDO::PARAM_INT);
			$i++;
		}
	}

	public function addAnswer($answer){
		$this -> answers[] = $answer;
	}

	public function render(){	//this code is implemented here because code quality varies throughout this project
		$html = "<dl>";
		foreach ($this->answers as $answer) {
			$questionText = $answer->getQuestion()->getText();
			$answerText = $answer->getText();
			$html.= "<dt>{$questionText}</dt><dd>{$answerText}</dd>";
		}
		$html.= "</dl>";
		return $html;
	}

	public function getTemplate(){
		$last = $this->answers[count($this->answers)-1];
		$tmpID = $last -> getIncidentTemplate();
		if($tmpID){
			return IncidentTemplate::fromID($tmpID);
		}
		return null;
	}

	public static function fromArray($incidentID,$arr){
		$list = new AnswerList($incidentID);
		foreach ($arr as $answer) {
			$list -> addAnswer($answer);
		}
		return $list;
	}

}

?>
