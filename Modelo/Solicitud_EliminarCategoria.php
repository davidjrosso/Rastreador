<?php 
class Solicitud_EliminarCategoria{
//DECLARACION DE VARIABLES
private $ID;
private $Fecha;
private $Categoria;
private $Cod_Categoria;
private $Estado;
private $ID_Usuario;
private $ID_Categoria;

//METODOS SET
public function setID($xID){
    $this->ID = $xID;
}

public function setFecha($xFecha){
    $this->Fecha = $xFecha;
}

public function setCategoria($xCategoria){
    $this->Categoria = $xCategoria;
}

public function setCod_Categoria($xCod_Categoria){
    $this->Cod_Categoria = $xCod_Categoria;
}

public function setEstado($xEstado){
    $this->Estado = $xEstado;
}

public function setID_Usuario($xID_Usuario){
    $this->ID_Usuario = $xID_Usuario;
}

public function setID_Categoria($xID_Categoria){
    $this->ID_Categoria = $xID_Categoria;
}

//METODOS GET
public function getID(){
    return $this->ID;
}

public function getFecha(){
    return $this->Fecha;
}

public function getCategoria(){
    return $this->Categoria;
}

public function getCod_Categoria(){
    return $this->Cod_Categoria;
}

public function getEstado(){
    return $this->Estado;
}

public function getID_Usuario(){
    return $this->ID_Usuario;
}

public function getID_Categoria(){
    return $this->ID_Categoria;
}


//METODO CONSTRUCTOR
public function __construct($xID,$xFecha,$xCategoria,$xCod_Categoria,$xEstado,$xID_Usuario,$xID_Categoria){
    $this->ID = $xID;
    $this->Fecha = $xFecha;
    $this->Categoria = $xCategoria;
    $this->Cod_Categoria = $xCod_Categoria;
    $this->Estado = $xEstado;
    $this->ID_Usuario = $xID_Usuario;
    $this->ID_Categoria = $xID_Categoria;
}


}
?>