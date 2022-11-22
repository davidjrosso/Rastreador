<?php
class Agente{
	private $IDAgente;
	private $Apellido;
	private $Nombre;
	private $Leg;
	private $Area;

public function setIDAgente($_xID){
	$this->IDAgente = $_xID;
}

public function setApellido($_xApellido){
	$this->Apellido = $_xApellido;
}

public function setNombre($_xNombre){
	$this->Nombre = $_xNombre;
}

public function setLeg($_xLeg){
	$this->Leg = $_xLeg;
}

public function setArea($_xArea){
	$this->Area = $_xArea;
}

public function getIDAgente(){
	return $this->IDAgente;
}

public function getApellido(){
	return $this->Apellido;
}

public function getNombre(){
	return $this->Nombre;
}

public function getLeg(){
	return $this->Leg;
}

public function getArea(){
	return $this->Area;
}

/*
public function __construct($xIDAgente,$xApellido,$xNombre,$xLeg,$xArea){
	$this->IDAgente = $xIDAgente;
	$this->Apellido = $xApellido;
	$this->Nombre = $xNombre;
	$this->Leg = $xLeg;
	$this->Area = $xArea;
}*/

public function __construct($xApellido,$xNombre,$xLeg,$xArea){
	$this->Apellido = $xApellido;
	$this->Nombre = $xNombre;
	$this->Leg = $xLeg;
	$this->Area = $xArea;
}

}
?>