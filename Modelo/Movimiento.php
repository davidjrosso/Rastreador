<?php  
class Movimiento{
	//DECLARACION DE VARIABLES
	private $ID_Movimiento;
	private $Fecha;
	private $ID_Persona;
	private $ID_Motivo_1;
	private $ID_Motivo_2;
	private $ID_Motivo_3;
	private $Observaciones;
	private $ID_Responsable;
	private $ID_Centro;
	private $ID_OtraInstitucion;
	private $Estado;


// METODOS SET
public function setID_Movimiento($xID_Movimiento){
	$this->ID_Movimiento = $xID_Movimiento;
}

public function setFecha($xFecha){
	$this->Fecha = $xFecha;
}

public function setID_Persona($xID_Persona){
	$this->ID_Persona = $xID_Persona;
}

public function setID_Motivo_1($xID_Motivo_1){
	$this->ID_Motivo_1 = $xID_Motivo_1;
}

public function setID_Motivo_2($xID_Motivo_2){
	$this->ID_Motivo_2 = $xID_Motivo_2;
}

public function setID_Motivo_3($xID_Motivo_3){
	$this->ID_Motivo_3 = $xID_Motivo_3;
}

public function setObservaciones($xObservaciones){
	$this->Observaciones = $xObservaciones;
}

public function setID_Responsable($xID_Responsable){
	$this->ID_Responsable = $xID_Responsable;
}

public function setID_Centro($xID_Centro){
	$this->ID_Centro = $xID_Centro;
}

public function setID_OtraInstitucion($xID_OtraInstitucion){
	$this->ID_OtraInstitucion = $xID_OtraInstitucion;
}

public function setEstado($xEstado){
	$this->Estado = $xEstado;
}

//METODOS GET
public function getID_Movimiento(){
	return $this->ID_Movimiento;
}

public function getFecha(){
	return $this->Fecha;
}

public function getID_Persona(){
	return $this->ID_Persona;
}

public function getID_Motivo_1(){
	return $this->ID_Motivo_1;
}

public function getID_Motivo_2(){
	return $this->ID_Motivo_2;
}

public function getID_Motivo_3(){
	return $this->ID_Motivo_3;
}

public function getObservaciones(){
	return $this->Observaciones;
}

public function getID_Responsable(){
	return $this->ID_Responsable;
}

public function getID_Centro(){
	return $this->ID_Centro;
}

public function getID_OtraInstitucion(){
	return $this->ID_OtraInstitucion;
}

public function getEstado(){
	return $this->Estado;
}

public function __construct($xID_Movimiento,$xFecha,$xID_Persona,$xID_Motivo_1,$xID_Motivo_2,$xID_Motivo_3,$xObservaciones,$xID_Respoonsable,$xID_Centro,$xID_OtraInstitucion,$xEstado){
	$this->ID_Movimiento = $xID_Movimiento;
	$this->Fecha = $xFecha;
	$this->ID_Persona = $xID_Persona;
	$this->ID_Motivo_1 = $xID_Motivo_1;
	$this->ID_Motivo_2 = $xID_Motivo_2;
	$this->ID_Motivo_3 = $xID_Motivo_3;
	$this->Observaciones = $xObservaciones;
	$this->ID_Responsable = $xID_Respoonsable;
	$this->ID_Centro = $xID_Centro;
	$this->ID_OtraInstitucion = $xID_OtraInstitucion;
	$this->Estado = $xEstado;
}








}



?>