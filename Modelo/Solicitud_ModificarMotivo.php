<?php 
class Solicitud_ModificarMotivo{
//DECLARACION DE VARIABLES
private $ID;
private $Fecha;
private $Motivo;
private $Codigo;
private $Cod_Categoria;
private $Num_Motivo;
private $Estado;
private $ID_Usuario;
private $ID_Motivo;

//METODOS SET
public function setID($xID){
    $this->ID = $xID;
}

public function setFecha($xFecha){
    $this->Fecha = $xFecha;
}

public function setMotivo($xMotivo){
    $this->Motivo = $xMotivo;
}

public function setCodigo($xCodigo){
    $this->Codigo = $xCodigo;
}

public function setCod_Categoria($xCod_Categoria){
    $this->Cod_Categoria = $xCod_Categoria;
}

public function setNum_Motivo($xNum_Motivo){
    $this->Num_Motivo = $xNum_Motivo;
}

public function setEstado($xEstado){
    $this->Estado = $xEstado;
}

public function setID_Usuario($xID_Usuario){
    $this->ID_Usuario = $xID_Usuario;
}

public function setID_Motivo($xID_Motivo){
    $this->ID_Motivo = $xID_Motivo;
}

//METODOS GET
public function getID(){
    return $this->ID;
}

public function getFecha(){
    return $this->Fecha;
}

public function getMotivo(){
    return $this->Motivo;
}

public function getCodigo(){
    return $this->Codigo;
}

public function getCod_Categoria(){
    return $this->Cod_Categoria;
}

public function getNum_Motivo(){
    return $this->Num_Motivo;
}

public function getEstado(){
    return $this->Estado;
}

public function getID_Usuario(){
    return $this->ID_Usuario;
}

public function getID_Motivo(){
    return $this->ID_Motivo;
}

//METODO CONSTRUCTOR
public function __construct($xID,$xFecha,$xMotivo,$xCodigo,$xCod_Categoria,$xNum_Motivo,$xEstado,$xID_Usuario,$xID_Motivo){
    $this->ID = $xID;
    $this->Fecha = $xFecha;
    $this->Motivo = $xMotivo;
    $this->Codigo = $xCodigo;
    $this->Cod_Categoria = $xCod_Categoria;
    $this->Num_Motivo = $xNum_Motivo;
    $this->Estado = $xEstado;
    $this->ID_Usuario = $xID_Usuario;
    $this->ID_Motivo = $xID_Motivo;
}


}
?>