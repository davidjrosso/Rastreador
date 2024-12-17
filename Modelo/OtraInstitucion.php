<?php
class OtraInstitucion{
//DECLARACION DE VARIABLES
private $ID_OtraInstitucion;
private $Nombre;
private $Telefono;
private $Mail;
private $Estado;

public static function get_id_by_name($coneccion, $name){
    $consulta = "select * 
                 from otras_instituciones 
                 where lower(Nombre) like lower('%$name%') 
                   and estado = 1";
    $mensaje_error = "Hubo un problema al consultar los registros";
    $ret = mysqli_query(
                $coneccion->Conexion,
                $consulta
    ) or die(
        $mensaje_error
    );
    $row = mysqli_fetch_assoc($ret);
    $id = (empty($row["ID_OtraInstitucion"])) ? 1 : $row["ID_OtraInstitucion"];
    return $id;
}

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