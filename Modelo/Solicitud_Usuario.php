<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Controladores/Conexion.php");

class Solicitud_Usuario{
//DECLARACION DE VARIABLES
private $id_solicitud;
private $fecha;
private $descripcion;
private $password;
private $tipo;
private $usuario;
private $estado;

//METODOS SET
public function set_id_solicitud($id_solicitud){
    $this->id_solicitud = $id_solicitud;
}

public function set_fecha($fecha){
    $this->fecha = $fecha;
}

public function set_descripcion($descripcion){
    $this->descripcion = $descripcion;
}

public function set_password($password){
    $this->password = $password;
}

public function set_usuario($usuario){
    $this->usuario = $usuario;
}
public function set_tipo($tipo){
    $this->tipo = $tipo;
}

public function set_estado($estado){
    $this->estado = $estado;
}

//METODOS GET
public function get_id_solicitud(){
    return $this->id_solicitud;
}

public function get_usuario(){
    return $this->usuario;
}
public function get_fecha(){
    return $this->fecha;
}

public function get_descripcion(){
    return $this->descripcion;
}
public function get_password(){
    return $this->password;
}
public function get_tipo(){
    return $this->tipo;
}

public function get_estado(){
    return $this->estado;
}

//METODO CONSTRUCTOR
public function __construct(
                            $id_solicitud=null, 
                            $fecha=null,
                            $descripcion=null,
                            $password=null,
                            $tipo=null,
                            $usuario=null,
                            $estado=null
                            ) {

    if (!$id_solicitud) {
        $this->fecha = $fecha;
        $this->descripcion = $descripcion;
        $this->password = $password;
        $this->tipo = $tipo;
        $this->usuario = $usuario;
        $this->estado = $estado;
	} else {
		$con = new Conexion();
        $con->OpenConexion();
		$consultar = "select *
							 from solicitudes_usuarios 
							 where id_solicitud = " . $id_solicitud . " 
							   and estado = 1";
		$ejecutar_consultar = mysqli_query(
			$con->Conexion, 
			$consultar) or die("Problemas al consultar filtro Usuario");
		$ret = mysqli_fetch_assoc($ejecutar_consultar);
		if (!is_null($ret)) {
			$id_solicitud = $ret["id_solicitud"];
			$fecha = $ret["fecha"];
			$descripcion = $ret["descripcion"];
			$tipo = $ret["tipo"];
            $usuario = $ret["usuario"];
			$estado = $ret["estado"];

			$this->id_solicitud = $id_solicitud;
			$this->fecha = $fecha;
			$this->descripcion = $descripcion;
			$this->tipo = $tipo;
			$this->usuario = $usuario;
			$this->estado = $estado;
		}
		$con->CloseConexion();
	}
}

public function save() {
    $Con = new Conexion();
    $Con->OpenConexion();
    $fecha = date(format: "Y-m-d");
    $Insert_Solicitud = "insert into solicitudes_usuarios( 
                            fecha,
                            descripcion,
                            password,
                            tipo,
                            usuario,
                            estado
                            ) values(
                                '" . (($this->get_fecha()) ? $this->get_fecha() : $fecha) . "',
                                '" . $this->get_descripcion() . "',
                                " . (($this->get_password()) ? "'" . $this->get_password() . "'" : 'null') . ",
                                " . $this->get_tipo() . ",
                                " . $this->get_usuario() . ",
                                " . $this->get_estado() . "
                            )";
    $MensajeError = "No se pudo enviar la solicitud";
    mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError);
    $Con->CloseConexion(); 
}

public function delete() {
    $Con = new Conexion();
    $Con->OpenConexion();
    $delete_solicitud = "update solicitudes_usuarios 
                         set estado = 0 
                         where id_solicitud = " . $this->get_id_solicitud();
    $MensajeError = "No se pudo enviar la solicitud";
    mysqli_query($Con->Conexion,$delete_solicitud) or die($MensajeError);
    $Con->CloseConexion(); 
}


}
