<?php  
class Accion{
// DECLARACION DE VARIABLES
private $ID_Accion;
private $accountid;
private $Fecha;
private $ip;
private $Detalles;
private $ID_TipoAccion;


// METODOS SET
public function setID_Accion($xID_Accion){
	$this->ID_Accion = $xID_Accion;
}

public function setaccountid($xaccountid){
	$this->accountid = $xaccountid;
}

public function setFecha($xFecha){
	$this->Fecha = $xFecha;
}

public function setip($xip){
	$this->ip = $xip;
}

public function setDetalles($xDetalles){
	$this->Detalles = $xDetalles;
}

public function setID_TipoAccion($xID_TipoAccion){
	$this->ID_TipoAccion = $xID_TipoAccion;
}

// METODOS GET
public function getID_Accion(){
	return $ID_Accion;
}

public function getaccountid(){
	return $accountid;
}

public function getFecha(){
	return $Fecha;
}

public function getip(){
	return $ip;
}

public function getDetalles(){
	return $Detalles;
}

public function getID_TipoAccion(){
	return $ID_TipoAccion;
}

public function __construct($xID_Accion, $xaccountid, $xFecha, $xip, $xDetalles, $xID_TipoAccion){
	$this->ID_Accion = $xID_Accion;
	$this->accountid = $xaccountid;
	$this->Fecha = $xFecha;
	$this->ip = $xip;
	$this->Detalles = $xDetalles;
	$this->ID_TipoAccion = $xID_TipoAccion;
}



}

?>