<?php
class Solicitud_Unificacion{
//DECLARACION DE VARIABLES
private $ID_Solicitud;
private $Fecha;
private $ID_Registro_1;
private $ID_Registro_2;
private $ID_Usuario;
private $Estado;
private $TipoUnif;

//METODOS SET
public function setID_Solicitud($xID_Solicitud){
    $this->ID_Solicitud = $xID_Solicitud;
}

public function setFecha($xFecha){
    $this->Fecha = $xFecha;
}

public function setID_Registro_1($xID_Registro_1){
    $this->ID_Registro_1 = $xID_Registro_1;
}

public function setID_Registro_2($xID_Registro_2){
    $this->setID_Registro_2 = $xID_Registro_2;
}

public function setID_Usuario($xID_Usuario){
    $this->ID_Usuario = $xID_Usuario;
}

public function setEstado($xEstado){
    $this->Estado = $xEstado;
}

public function setTipoUnif($xTipoUnif){
    $this->TipoUnif = $xTipoUnif;
}

//METODOS GET
public function getID_Solicitud(){
    return $this->ID_Solicitud;
}

public function getFecha(){
    return $this->Fecha;
}

public function getID_Registro_1(){
    return $this->ID_Registro_1;
}

public function getID_Registro_2(){
    return $this->ID_Registro_2;
}

public function getID_Usuario(){
    return $this->ID_Usuario;
}

public function getEstado(){
    return $this->Estado;
}

public function getTipoUnif(){
    return $this->TipoUnif;
}

//METODOS CONSTRUCTORES
public function __construct($xID_Solicitud,$xFecha,$xID_Registro_1,$xID_Registro_2,$xID_Usuario,$xEstado,$xTipoUnif){
    $this->ID_Solicitud = $xID_Solicitud;
    $this->Fecha = $xFecha;
    $this->ID_Registro_1 = $xID_Registro_1;
    $this->ID_Registro_2 = $xID_Registro_2;
    $this->ID_Usuario = $xID_Usuario;
    $this->Estado = $xEstado;
    $this->TipoUnif = $xTipoUnif;
}


}
?>