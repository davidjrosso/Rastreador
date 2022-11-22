<?php
class General{
	private $ID;
	private $Fecha;
	private $Prov;
	private $Vehiculo;
	private $Agente;
	private $Combustible;
	private $Precio;
	private $NroRemito;
	private $NroCompromiso;
	private $LitrosCombustible;

public function setID($_xID){
	$this->ID = $_xID;
}

public function setFecha($_xFecha){
	$this->Fecha = $_xFecha;
}

public function setProv($_xProv){
	$this->Prov = $_xProv;
}

public function setVehiculo($_xVehiculo){
	$this->Vehiculo = $_xVehiculo;
}

public function setAgente($_xAgente){
	$this->Agente = $_xAgente;
}

public function setCombustible($_xCombustible){
	$this->Combustible = $_xCombustible;
}

public function setPrecio($_xPrecio){
	$this->Precio = $_xPrecio;
}

public function setNroRemito($_xNroRemito){
	$this->NroRemito = $_xNroRemito;
}

public function setNroCompromiso($_xNroCompromiso){
	$this->NroCompromiso = $_xNroCompromiso;
}

public function setLitrosComsubtible($_xLitrosCombustible){
	$this->LitrosCombustible = $_xLitrosCombustible;
}

public function getID(){
	return $this->ID;
}

public function getFecha(){
	return $this->Fecha;
}

public function getProv(){
	return $this->Prov;
}

public function getVehiculo(){
	return $this->Vehiculo;
}

public function getAgente(){
	return $this->Agente;
}

public function getCombustible(){
	return $this->Combustible;
}

public function getPrecio(){
	return $this->Precio;
}

public function getNroRemito(){
	return $this->NroRemito;
}

public function getNroCompromiso(){
	return $this->NroCompromiso;
}

public function getLitrosCombustible(){
	return $this->LitrosCombustible;
}

public function __construct($xID,$xFecha,$xProv,$xVehiculo,$xAgente,$xCombustible,$xPrecio,$xNroRemito,$xNroCompromiso,$xLitrosCombustible){
	$this->ID = $xID;
	$this->Fecha = $xFecha;
	$this->Prov = $xProv;
	$this->Vehiculo = $xVehiculo;
	$this->Agente = $xAgente;
	$this->Combustible = $xCombustible;
	$this->Precio = $xPrecio;
	$this->NroRemito = $xNroRemito;
	$this->NroCompromiso = $xNroCompromiso;
	$this->LitrosCombustible = $xLitrosCombustible;
}

}
?>