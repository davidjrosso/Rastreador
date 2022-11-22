<?php  
class DtoMovimiento{
	//DECLARACION DE VARIABLES
	private $ID_Movimiento;
	private $Fecha;
	private $Apellido;
	private $Nombre;
	private $Motivo_1;
	private $Motivo_2;
	private $Motivo_3;
	private $Observaciones;
	private $Responsable;
	private $CentroSalud;
	private $OtraInstitucion;


// METODOS SET
public function setID_Movimiento($xID_Movimiento){
	$this->ID_Movimiento = $xID_Movimiento;
}

public function setFecha($xFecha){
	$this->Fecha = $xFecha;
}

public function setApellido($xApellido){
	$this->Apellido = $xApellido;
}

public function setNombre($xNombre){
	$this->Nombre = $xNombre;
}

public function setMotivo_1($xMotivo_1){
	$this->Motivo_1 = $xMotivo_1;
}

public function setMotivo_2($xMotivo_2){
	$this->Motivo_2 = $xMotivo_2;
}

public function setMotivo_3($xMotivo_3){
	$this->Motivo_3 = $xMotivo_3;
}

public function setObservaciones($xObservaciones){
	$this->Observaciones = $xObservaciones;
}

public function setResponsable($xResponsable){
	$this->Responsable = $xResponsable;
}

public function setCentroSalud($xCentroSalud){
	$this->CentroSalud = $xCentroSalud;
}

public function setOtraInstitucion($xOtraInstitucion){
	$this->OtraInstitucion = $xOtraInstitucion;
}

//METODOS GET
public function getID_Movimiento(){
	return $this->ID_Movimiento;
}

public function getFecha(){
	return $this->Fecha;
}

public function getApellido(){
	return $this->Apellido;
}

public function getNombre(){
	return $this->Nombre;
}

public function getMotivo_1(){
	return $this->Motivo_1;
}

public function getMotivo_2(){
	return $this->Motivo_2;
}

public function getMotivo_3(){
	return $this->Motivo_3;
}

public function getObservaciones(){
	return $this->Observaciones;
}

public function getResponsable(){
	return $this->Responsable;
}

public function getCentroSalud(){
	return $this->CentroSalud;
}

public function getOtraInstitucion(){
	return $this->OtraInstitucion;
}

public function __construct($xID_Movimiento,$xFecha,$xApellido,$xNombre,$xMotivo_1,$xMotivo_2,$xMotivo_3,$xObservaciones,$xResponsable,$xCentroSalud,$xOtraInstitucion){
	$this->ID_Movimiento = $xID_Movimiento;
	$this->Fecha = $xFecha;
	$this->Apellido = $xApellido;
	$this->Nombre = $xNombre;
	$this->Motivo_1 = $xMotivo_1;
	$this->Motivo_2 = $xMotivo_2;
	$this->Motivo_3 = $xMotivo_3;
	$this->Observaciones = $xObservaciones;
	$this->Responsable = $xResponsable;
	$this->CentroSalud = $xCentroSalud;
	$this->OtraInstitucion = $xOtraInstitucion;
}








}



?>