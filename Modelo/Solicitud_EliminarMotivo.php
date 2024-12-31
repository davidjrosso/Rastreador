<?php 
class Solicitud_EliminarMotivo{
//DECLARACION DE VARIABLES
private $ID;
private $Fecha;
private $Motivo;
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
public function __construct($xID,$xFecha,$xMotivo,$xCod_Categoria,$xNum_Motivo,$xEstado,$xID_Usuario,$xID_Motivo){
    $this->ID = $xID;
    $this->Fecha = $xFecha;
    $this->Motivo = $xMotivo;
    $this->Cod_Categoria = $xCod_Categoria;
    $this->Num_Motivo = $xNum_Motivo;
    $this->Estado = $xEstado;
    $this->ID_Usuario = $xID_Usuario;
    $this->ID_Motivo = $xID_Motivo;
}

public function save() {
    $Con = new Conexion();
    $Con->OpenConexion();
    $Insert_Solicitud = "insert into solicitudes_eliminarmotivos( 
                            Fecha,
                            Motivo,
                            Cod_Categoria,
                            Num_Motivo,
                            Estado,
                            ID_Usuario,
                            ID_Motivo
                            ) values(
                                '" . $this->getFecha() . "',
                                '" . $this->getMotivo() . "',
                                '" . $this->getCod_Categoria() . "',
                                " . $this->getNum_Motivo() . ",
                                " . $this->getEstado() . ",
                                " . $this->getID_Usuario() . ",
                                " . $this->getID_Motivo() . "
                            )";
    $MensajeError = "No se pudo enviar la solicitud";
    mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError);
    $Con->CloseConexion(); 
}


}
