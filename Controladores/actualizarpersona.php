<?php
require_once 'Conexion.php';
require_once '../Modelo/Persona.php';
header("Content-Type: text/html;charset=utf-8");

try {
	$flag = set_time_limit(1000);
	$Con = new Conexion();
	$Con->OpenConexion();
	$consultar = "SELECT p.*, c.*
				  FROM (select id_persona,
				  			   nombre,
							   apellido,
                        	   domicilio,
							   REGEXP_REPLACE(
											  REGEXP_SUBSTR(
											     			lower(domicilio), 
															'([1-9]+( )+[a-zA-Z]+( )+[a-zA-Z]+)|([a-zA-Z]+( )+[a-zA-Z]+( )+[a-zA-Z]+)|([a-zA-Z]+( )+[a-zA-Z]+)|([a-zA-Z]+)'
															),
											  '( )+',
											  ' '
											  ) as calle,
							    REGEXP_SUBSTR(
											  lower(domicilio), 
											  '([0-9]+)$'
											  ) as nro,
							   estado,
							   georeferencia
						from persona
						where estado = 1) p inner join calle c on  (calle = lower(calle_nombre))
				  where p.estado = 1
				  and c.estado = 1
				  and domicilio <> '';";
	if(!$ejecutar_consultar = mysqli_query($Con->Conexion, $consultar)){
		throw new Exception("Problemas al intentar Consultar Registros de Personas", 0);
	}
	while ($ret = mysqli_fetch_assoc($ejecutar_consultar)) {
		$Persona = new Persona(ID_Persona  : $ret["id_persona"]);
		$Persona->setCalle($ret["id_calle"]);
		$Persona->setNro($ret["nro"]);
		$Persona->setDomicilio();
		$Persona->update_calle();
		$Persona->update_nro();
		$Persona->update_geo();
	}
	$mensaje = "Se actualizarion las direcciones correctamente";
	$Con->CloseConexion();
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode("mensaje: $mensaje");
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
