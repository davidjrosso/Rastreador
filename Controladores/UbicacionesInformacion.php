<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Barrio.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Calle.php';
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
    $barrio = Barrio::get_id_by_subpalabra($con, $barrio_nombre);
    if ($barrio) {
        $json_response["id_barrio"] = $barrio;
    }

    $calle = Calle::get_id_by_nombre($calle_nombre);
    if ($calle) {
        $json_response["id_calle"] = $calle;
        $json_response["nombre_calle"] = $calle_nombre;
    }

    if ($nro) {
        $json_response["nro"] = $nro;
    }
    echo json_encode($json_response);
} catch (Exception $e) {
	echo $e->getMessage();
}
