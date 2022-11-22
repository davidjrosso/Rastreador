<?php  
class Usuario{
	private $AccountID;
	private $FirstName;
	private $LastName;
	private $Initials;
	private $UserName;
	private $UserPass;
	private $Email;
	private $Estado;
	private $ID_TipoUsuario;


	public function setAccountID($xAccountID){
		$this->AccountID = $xAccountID;
	}

	public function setFirstName($xFirstName){
		$this->FirstName = $xFirstName;
	}

	public function setLastName($xLastName){
		$this->LastName = $xLastName;
	}

	public function setInitials($xInitials){
		$this->Initials = $xInitials;
	}

	public function setUserName($xUserName){
		$this->UserName = $xUserName;
	}

	public function setUserPass($xUserPass){
		$this->UserPass = $xUserPass;
	}

	public function setEmail($xEmail){
		$this->Email = $xEmail;
	}

	public function setEstado($xEstado){
		$this->Estado = $xEstado;
	}

	public function setID_TipoUsuario($xID_TipoUsuario){
		$this->ID_TipoUsuario = $xID_TipoUsuario;
	}

	public function getAccountID(){
		return $this->AccountID;
	}

	public function getFirstName(){
		return $this->FirstName;
	}

	public function getLastName(){
		return $this->LastName;
	}

	public function getInitials(){
		return $this->Initials;
	}

	public function getUserName(){
		return $this->UserName;
	}

	public function getUserPass(){
		return $this->UserPass;
	}

	public function getEmail(){
		return $this->Email;
	}

	public function getEstado(){
		return $this->Estado;
	}

	public function getID_TipoUsuario(){
		return $this->ID_TipoUsuario;
	}

	public function __construct($xAccountID,$xFirstName,$xLastName,$xInitials,$xUserName,$xUserPass,$xEmail,$xEstado,$xID_TipoUsuario){
		$this->AccountID = $xAccountID;
		$this->FirstName = $xFirstName;
		$this->LastName = $xLastName;
		$this->Initials = $xInitials;
		$this->UserName = $xUserName;
		$this->UserPass = $xUserPass;
		$this->Email = $xEmail;
		$this->Estado = $xEstado;
		$this->ID_TipoUsuario = $xID_TipoUsuario;
	}








}




?>