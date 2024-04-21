<?php  
class Movimiento{
	//DECLARACION DE VARIABLES
	private $ID_Movimiento;
	private $Fecha;
	private $ID_Persona;
	private $ID_Motivo_1;
	private $ID_Motivo_2;
	private $ID_Motivo_3;
	private $ID_Motivo_4;
	private $ID_Motivo_5;
	private $Observaciones;
	private $ID_Responsable;
	private $ID_Responsable_2;
	private $ID_Responsable_3;
	private $ID_Responsable_4;
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

public function setID_Responsable_2($xID_Responsable_2){
	$this->ID_Responsable_2 = $xID_Responsable_2;
}

public function setID_Responsable_3($xID_Responsable_3){
	$this->ID_Responsable_3 = $xID_Responsable_3;
}

public function setID_Responsable_4($xID_Responsable_4){
	$this->ID_Responsable_4 = $xID_Responsable_4;
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

public function getID_Motivo_4(){
	return $this->ID_Motivo_4;
}

public function getID_Motivo_5(){
	return $this->ID_Motivo_5;
}

public function getObservaciones(){
	return $this->Observaciones;
}

public function getID_Responsable(){
	return $this->ID_Responsable;
}

public function getID_Responsable_2(){
	return $this->ID_Responsable_2;
}

public function getID_Responsable_3(){
	return $this->ID_Responsable_3;
}

public function getID_Responsable_4(){
	return $this->ID_Responsable_4;
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

public function __construct($xID_Movimiento,$xFecha,$xID_Persona,$xID_Motivo_1,$xID_Motivo_2,$xID_Motivo_3,$xID_Motivo_4,$xID_Motivo_5,$xObservaciones,$xID_Responsable,$xID_Responsable_2,$xID_Responsable_3,$xID_Responsable_4,$xID_Centro,$xID_OtraInstitucion,$xEstado){
	$this->ID_Movimiento = $xID_Movimiento;
	$this->Fecha = $xFecha;
	$this->ID_Persona = $xID_Persona;
	$this->ID_Motivo_1 = $xID_Motivo_1;
	$this->ID_Motivo_2 = $xID_Motivo_2;
	$this->ID_Motivo_3 = $xID_Motivo_3;
	$this->ID_Motivo_4 = $xID_Motivo_4;
	$this->ID_Motivo_5 = $xID_Motivo_5;
	$this->Observaciones = $xObservaciones;
	$this->ID_Responsable = $xID_Responsable;
	$this->ID_Responsable_2 = $xID_Responsable_2;
	$this->ID_Responsable_3 = $xID_Responsable_3;
	$this->ID_Responsable_4 = $xID_Responsable_4;
	$this->ID_Centro = $xID_Centro;
	$this->ID_OtraInstitucion = $xID_OtraInstitucion;
	$this->Estado = $xEstado;
}

/*public function insertMovimiento(){
	$Consulta = "insert into movimiento(fecha,id_persona,motivo_1,motivo_2,motivo_3,motivo_4,motivo_5,observaciones,id_resp,id_resp_2,id_resp_3,id_resp_4,id_centro,id_otrainstitucion,estado) 
			 values('".$Movimiento->getFecha()."',".$Movimiento->getID_Persona().",".$Movimiento->getID_Motivo_1().",".$Movimiento->getID_Motivo_2().",".$Movimiento->getID_Motivo_3().",'".$Movimiento->getID_Motivo_4().",'".$Movimiento->getID_Motivo_5().",'".$Movimiento->getObservaciones()."',".$Movimiento->getID_Responsable().",".$Movimiento->getID_Responsable_2().",".$Movimiento->getID_Responsable_3().",".$Movimiento->getID_Responsable_4().",".$Movimiento->getID_Centro().",".$Movimiento->getID_OtraInstitucion().",".$Movimiento->getEstado().")";
}*/





}



?>