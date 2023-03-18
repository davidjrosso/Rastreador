<?php 
class Solicitud_ModificarCategoria{
//DECLARACION DE VARIABLES
private $ID;
private $Fecha;
private $Codigo;
private $Categoria;
private $ID_Forma;
private $NuevoColor;
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

public function setCodigo($xCodigo){
    $this->Codigo = $xCodigo;
}

public function setCategoria($xCategoria){
    $this->Categoria = $xCategoria;
}

public function setID_Forma($xID_Forma){
    $this->ID_Forma = $xID_Forma;
}

public function setNuevoColor($xNuevoColor){
    $this->NuevoColor = $xNuevoColor;
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

public function getCodigo(){
    return $this->Codigo;
}

public function getCategoria(){
    return $this->Categoria;
}

public function getID_Forma(){
    return $this->ID_Forma;
}

public function getNuevoColor(){
    return $this->NuevoColor;
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
public function __construct($xID,$xFecha,$xCodigo,$xCategoria,$xID_Forma,$xNuevoColor,$xEstado,$xID_Usuario,$xID_Categoria){
    $this->ID = $xID;
    $this->Fecha = $xFecha;
    $this->Codigo = $xCodigo;
    $this->Categoria = $xCategoria;
    $this->ID_Forma = $xID_Forma;
    $this->NuevoColor = $xNuevoColor;
    $this->Estado = $xEstado;
    $this->ID_Usuario = $xID_Usuario;
    $this->ID_Categoria = $xID_Categoria;
}


}
?>