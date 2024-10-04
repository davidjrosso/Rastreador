<?php
require_once 'Conexion.php';
require_once '../Modelo/Persona.php';

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
															'([1-9]+( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*)'
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
						where estado = 1) p left join calle c on  (lower(calle_nombre) like REGEXP_REPLACE( 
																											REGEXP_REPLACE(
																												REGEXP_SUBSTR(
																														lower(domicilio), 
																														'([1-9]+( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*)'
																														),
																														'( )+',
																														'%'
																											),
																														'(\\\\.)',
																														''
																											))
				  where p.estado = 1
				  and c.estado = 1
				  and domicilio <> ''
				  and georeferencia is null";
	if(!$ejecutar_consultar = mysqli_query($Con->Conexion, $consultar)){
		throw new Exception("Problemas al intentar Consultar Registros de Personas", 0);
	}
	$lista_personas = [];
	$lista_personas["consulta"] = $consultar;
	$lista_personas["cantidad"] = mysqli_num_rows($ejecutar_consultar);
	while ($ret = mysqli_fetch_assoc($ejecutar_consultar)) {
		$Persona = new Persona(ID_Persona  : $ret["id_persona"]);
		$lista_personas[]["id_persona"] = $ret["id_persona"];
		$Persona->setCalle($ret["id_calle"]);
		$lista_personas[]["id_calle"] = $ret["id_calle"];
		$Persona->setNro($ret["nro"]);
		$lista_personas[]["id_calle"] = $ret["id_calle"];
		$lista_personas[]["calle"] = $ret["calle"];
		$Persona->setDomicilio();
		$Persona->update_calle();
		$Persona->update_nro();
		$Persona->update_geo();
		$lista_personas[]["georeferencia"] = is_null($Persona->getGeoreferencia());
	}

	$mensaje = "Se actualizarion las direcciones correctamente";
	$Con->CloseConexion();
	$lista_personas["mensaje"] = $mensaje;
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($lista_personas);
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
