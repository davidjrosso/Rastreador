<?php 
require_once 'Conexion.php';
require_once '../Modelo/Persona.php';
header("Content-Type: text/html;charset=utf-8");

try {
	$Con = new Conexion();
	$Con->OpenConexion();	
	$consultar = "select id_persona
				  from persona 
				  where georeferencia is null 
				    and domicilio is not null 
					and domicilio <> ''
				    and estado = 1";
	if(!$ejecutar_consultar = mysqli_query($Con->Conexion, $consultar)){
		throw new Exception("Problemas al intentar Consultar Registros de Personas", 0);
	}
	while ($ret = mysqli_fetch_assoc($ejecutar_consultar)) {
		$Persona = new Persona(ID_Persona  : $ret["id_persona"]);
		$Persona->setDomicilio($Persona->getDomicilio());
		$Persona->update_geo();
	}
	$mensaje = "Se actualizarion las direcciones correctamente";
	$Con->CloseConexion();
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode("mensaje: $mensaje");
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
