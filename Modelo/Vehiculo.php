<?php
class Vehiculo{
	private $IDVehiculo;
	private $Numero;
	private $Dominio;
	private $Detalle;
	private $Modelo;
	private $Area;
	private $TipoCombustible;

public function setIDVehiculo($_xIDVehiculo){
	$this->IDVehiculo = $_xIDVehiculo;
}

public function setNumero($_xNumero){
	$this->Numero = $_xNumero;
}

public function setDominio($_xDominio){
	$this->Dominio = $_xDominio;
}

public function setDetalle($_xDetalle){
	$this->Detalle = $_xDetalle;
}

public function setModelo($_xModelo){
	$this->Modelo = $_xModelo;
}

public function setArea($_xArea){
	$this->Area = $_xArea;
}

public function setTipoCombustible($_xTipoCombustible){
	$this->TipoCombustible = $_xTipoCombustible;
}

public function getIDVehiculo(){
	return $this->IDVehiculo;
}

public function getNumero(){
	return $this->Numero;
}

public function getDominio(){
	return $this->Dominio;
}

public function getDetalle(){
	return $this->Detalle;
}

public function getModelo(){
	return $this->Modelo;
}

public function getArea(){
	return $this->Area;
}

public function getTipoCombustible(){
	return $this->TipoCombustible;
}

public function __construct($xNumero,$xDominio,$xDetalle,$xModelo,$xArea,$xTipoCombustible){
	$this->Numero = $xNumero;
	$this->Dominio = $xDominio;
	$this->Detalle = $xDetalle;
	$this->Modelo = $xModelo;
	$this->Area = $xArea;
	$this->TipoCombustible = $xTipoCombustible;
}

}
?>