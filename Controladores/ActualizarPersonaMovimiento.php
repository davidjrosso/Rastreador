<?php
require_once 'Conexion.php';
require_once '../Modelo/Persona.php';

try {
	$flag = set_time_limit(1000);
	$id_calle = (isset($_REQUEST["id_calle"])) ? $_REQUEST["id_calle"] : null;
	$id_barrio = (isset($_REQUEST["id_barrio"])) ? $_REQUEST["id_barrio"] : null;
	$id_motivo = (isset($_REQUEST["motivo"])) ? $_REQUEST["motivo"] : null;
	$georeferencia = (isset($_REQUEST["georeferencia"])) ? $_REQUEST["georeferencia"] : null;


    if (isset($_REQUEST["fecha_desde"])) {
        $lista = explode("/", $_REQUEST["fecha_desde"]);
        $fecha_desde = implode("-", array_reverse($lista));
      } else {
        $fecha_desde = null;
      }
      if (isset($_REQUEST["fecha_hasta"])) {
        $fecha_hasta = implode("-", array_reverse(explode("/", $_REQUEST["fecha_hasta"])));
      } else {
        $fecha_hasta = null;
      }

	$Con = new Conexion();
	$Con->OpenConexion();

    if ($id_barrio || $id_calle ) {
        $consulta_persona = "(SELECT *
				  FROM persona
                  where estado = 1". (($id_barrio) ? " AND id_barrio = $id_barrio" : "")  .
                  (($id_calle) ? " AND id_calle = $id_calle" : "")  .
                  ") AS P";
    }

    if ($id_motivo || $fecha_desde || $fecha ) {
        $consulta_movimiento = "(SELECT *
				  FROM movimiento
                  where estado = 1". (($id_motivo) ? " AND motivo_1 = $id_motivo" : "")  .
                  (($fecha_desde && $fecha_hasta) ? " AND fecha between '$fecha_desde' and '$fecha_hasta'" : "")  .
                  ") as MOV";
    } else {
        $consulta_movimiento = "movimiento as MOV";
    }

	$consultar = "SELECT *
				  FROM $consulta_persona INNER JOIN $consulta_movimiento
                  ON P.id_persona = MOV.id_persona";

	if(!$ejecutar_consultar = mysqli_query($Con->Conexion, $consultar)){
		throw new Exception("Problemas al intentar Consultar Registros de Personas", 0);
	}
	$lista_personas = [];
	$lista_personas["cantidad"] = mysqli_num_rows($ejecutar_consultar);
	while ($ret = mysqli_fetch_assoc($ejecutar_consultar)) {
		$persona = new Persona(ID_Persona  : $ret["id_persona"]);
		$row_persona["id_persona"] = $ret["id_persona"];
		$row_persona["calle"] = $ret["calle"];
		$row_persona["nro"] = $ret["nro"];
		$persona->setDomicilio();
		$persona->update_geo();
		$row_persona["georeferencia"] = is_null($persona->getGeoreferencia());
        $lista_personas[] = $row_persona;
	}
	$mensaje = "Se georeferenciacion las personas correctamente";
	$Con->CloseConexion();
	$lista_personas["mensaje"] = $mensaje;
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($lista_personas);
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
