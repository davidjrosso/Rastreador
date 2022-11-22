<?php
class Proveedor{
	private $IDProv;
	private $NombreProv;

public function setIDProv($_xID){
	$this->IDProv = $_xID;
}

public function setNombreProv($_xNombreProv){
	$this->NombreProv = $_xNombreProv;
}

public function getIDProv(){
	return $this->IDProv;
}

public function getNombreProv(){
	return $this->NombreProv;
}


/*
public function __construct($xIDAgente,$xApellido,$xNombre,$xLeg,$xArea){
	$this->IDAgente = $xIDAgente;
	$this->Apellido = $xApellido;
	$this->Nombre = $xNombre;
	$this->Leg = $xLeg;
	$this->Area = $xArea;
}*/

public function __construct($xNombreProv){
	$this->NombreProv = $xNombreProv;
}

}
?>