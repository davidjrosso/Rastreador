<?php  
class Escuela{
//DECLARACION DE VARIABLES
private $ID_Escuela;
private $Codigo;
private $Escuela;
private $CUE;
private $Localidad;
private $Departamento;
private $Directora;
private $Telefono;
private $Mail;
private $ID_Nivel;
private $Estado;

//METODOS SET
public function setID_Escuela($xID_Escuela){
	$this->ID_Escuela = $xID_Escuela;
}

public function setCodigo($xCodigo){
	$this->Codigo = $xCodigo;
}

public function setEscuela($xEscuela){
	$this->Escuela = $xEscuela;
}

public function setCUE($xCUE){
	$this->CUE = $xCUE;
}

public function setLocalidad($xLocalidad){
	$this->Localidad = $xLocalidad;
}

public function setDepartamento($xDepartamento){
	$this->Departamento = $xDepartamento;
}

public function setDirectora($xDirectora){
	$this->Directora = $xDirectora;
}

public function setTelefono($xTelefono){
	$this->Telefono = $xTelefono;
}

public function setMail($xMail){
	$this->Mail = $xMail;
}

public function setID_Nivel($xID_Nivel){
	$this->ID_Nivel = $xID_Nivel;
}

public function setEstado($xEstado){
	$this->Estado = $xEstado;
}

//METODOS GET
public function getID_Escuela(){
	return $this->ID_Escuela;
}

public function getCodigo(){
	return $this->Codigo;
}

public function getEscuela(){
	return $this->Escuela;
}

public function getCUE(){
	return $this->CUE;
}

public function getLocalidad(){
	return $this->Localidad;
}

public function getDepartamento(){
	return $this->Departamento;
}

public function getDirectora(){
	return $this->Directora;
}

public function getTelefono(){
	return $this->Telefono;
}

public function getMail(){
	return $this->Mail;
}

public function getID_Nivel(){
	return $this->ID_Nivel;
}

public function getEstado(){
	return $this->Estado;
}

//METODO CONSTRUCTOR
public function __construct($xID_Escuela,$xCodigo,$xEscuela,$xCUE,$xLocalidad,$xDepartamento,$xDirectora,$xTelefono,$xMail,$xID_Nivel,$xEstado){
	$this->ID_Escuela = $xID_Escuela;
	$this->Codigo = $xCodigo;
	$this->Escuela = $xEscuela;
	$this->CUE = $xCUE;
	$this->Localidad = $xLocalidad;
	$this->Departamento = $xDepartamento;
	$this->Directora = $xDirectora;
	$this->Telefono = $xTelefono;
	$this->Mail = $xMail;
	$this->ID_Nivel = $xID_Nivel;
	$this->Estado = $xEstado;
}



}
?>