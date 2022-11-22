<?php
class Combustible{
	private $IDCombustible;
	private $TipoCombustible;
	private $PrecioxLitro;

public function setIDCombustible($_xID){
	$this->IDCombustible = $_xID;
}

public function setTipoCombustible($_xTipoCombustible){
	$this->TipoCombustible = $_xTipoCombustible;
}

public function setPrecioxLitro($_xPrecioxLitro){
	$this->PrecioxLitro = $_xPrecioxLitro;
}

public function getIDCombustible(){
	return $this->IDCombustible;
}

public function getTipoCombustible(){
	return $this->TipoCombustible;
}

public function getPrecioxLitro(){
	return $this->PrecioxLitro;
}


/*
public function __construct($xIDAgente,$xApellido,$xNombre,$xLeg,$xArea){
	$this->IDAgente = $xIDAgente;
	$this->Apellido = $xApellido;
	$this->Nombre = $xNombre;
	$this->Leg = $xLeg;
	$this->Area = $xArea;
}*/

public function __construct($xTipoCombustible,$xPrecioxLitro){
	$this->NombreProv = $xNombreProv;
	$this->PrecioxLitro = $xPrecioxLitro;
}

}
?>