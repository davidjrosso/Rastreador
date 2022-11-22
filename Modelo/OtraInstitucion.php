<?php
class OtraInstitucion{
//DECLARACION DE VARIABLES
private $ID_OtraInstitucion;
private $Nombre;
private $Telefono;
private $Mail;
private $Estado;

//METODOS SET
public function setID_OtraInstitucion($xID_OtraInstitucion){
    $this->ID_OtraInstitucion = $xID_OtraInstitucion;
}

public function setNombre($xNombre){
    $this->Nombre = $xNombre;
}

public function setTelefono($xTelefono){
    $this->Telefono = $xTelefono;
}

public function setMail($xMail){
    $this->Mail = $xMail;
}

public function setEstado($xEstado){
    $this->Estado = $xEstado;
}

//METODOS GET
public function getID_OtraInstitucion(){
    return $this->ID_OtraInstitucion;
}

public function getNombre(){
    return $this->Nombre;
}

public function getTelefono(){
    return $this->Telefono;
}

public function getMail(){
    return $this->Mail;
}

public function getEstado(){
    return $this->Estado;
}

//METODO CONSTRUCTOR
public function __construct($xID_OtraInstitucion,$xNombre,$xTelefono,$xMail,$xEstado){
    $this->ID_OtraInstitucion = $xID_OtraInstitucion;
    $this->Nombre = $xNombre;
    $this->Telefono = $xTelefono;
    $this->Mail = $xMail;
    $this->Estado = $xEstado;
}

}
?>