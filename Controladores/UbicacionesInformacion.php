<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Barrio.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Calle.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/CalleBarrio.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Accion.php';
header('Content-Type: application/json');

try {
    $calle_nombre = (empty($_REQUEST["calle"])) ? null : $_REQUEST["calle"];
    $nro = (empty($_REQUEST["nro"])) ? null : $_REQUEST["nro"];

    $calle_nombre = trim($calle_nombre);
    $barrio_nombre = (empty($_REQUEST["barrio"])) ? null : $_REQUEST["barrio"];
    $barrio_nombre = str_replace("Barrio","", $barrio_nombre);
    $barrio_nombre = trim($barrio_nombre);

    $json_response = [];

    $con = new Conexion();
    $con->OpenConexion();
    $id_barrio = Barrio::get_id_by_name($con, $barrio_nombre);

    if ($id_barrio) {
        $barrio = new Barrio(
                             coneccion: $con,
                             id_barrio: $id_barrio
                            );
        $json_response["id_barrio"] = $id_barrio;
        $json_response["barrio"] = $barrio->get_barrio();
    }

    $calle_id = Calle::get_id_by_nombre($calle_nombre);
    $existe_geo = CalleBarrio::existe_georeferencia(
                                                    id_calle: $calle_id,
                                                    num_calle: $nro,
                                                    connection: $con
                                                   );
    if ($calle_id && $existe_geo) {
        $georeferencia = new CalleBarrio(
                                         connection: $con,
                                         id_geo: $existe_geo
                                        );
        $existe = Barrio::existe_barrio(
                                        coneccion: $con,
                                        name: $barrio_nombre
                                        );
        if ($georeferencia && $existe) {
            $id_barrio = $georeferencia->get_id_barrio();
        }
    }

    if ($id_barrio) {
        $barrio = new Barrio(
                                coneccion: $con,
                                id_barrio: $id_barrio
                            );
        $json_response["id_barrio"] = $id_barrio;
        $json_response["barrio"] = (!empty($barrio->get_barrio())) ? $barrio->get_barrio() : null;
    }

    if ($calle_id) {
        $json_response["id_calle"] = $calle_id;
        $json_response["nombre_calle"] = $calle_nombre;
    }

    if ($nro) {
        $json_response["nro"] = $nro;
    }

    echo json_encode($json_response);
} catch (Exception $e) {
	echo $e->getMessage();
}
