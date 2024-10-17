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
	return $this->ID_Accion;
}

public function getaccountid(){
	return $this->accountid;
}

public function getFecha(){
	return $this->Fecha;
}

public function getip(){
	return $this->ip;
}

public function getDetalles(){
	return $this->Detalles;
}

public function getID_TipoAccion(){
	return $this->ID_TipoAccion;
}

public function save() {
	$con = new Conexion();
	$con->OpenConexion();
	$consulta_accion = "insert into Acciones(accountid, 
											Fecha, 
											Detalles, 
											ID_TipoAccion) 
								 values(" . ((!$this->accountid) ? "null" : $this->accountid) . ",'" 
								 		  . $this->Fecha . "','" 
										  . $this->Detalles . "',
										  	1)";
	if(!$RetAccion = mysqli_query($con->Conexion,$consulta_accion)){
		throw new Exception("Error al intentar registrar Accion. Consulta: ". $consulta_accion, 3);
	}
}

public function __construct(
							$xID_Accion = null,
							$xaccountid = null,
							$xFecha = null,
							$xip = null,
							$xDetalles = null,
							$xID_TipoAccion = null
							){
	$this->ID_Accion = $xID_Accion;
	$this->accountid = $xaccountid;
	$this->Fecha = $xFecha;
	$this->ip = $xip;
	$this->Detalles = $xDetalles;
	$this->ID_TipoAccion = $xID_TipoAccion;
}



}
