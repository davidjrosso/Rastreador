<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Persona.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Calle.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Barrio.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/CalleBarrio.php';
header("Content-Type: application/json;charset=utf-8");

$ID_Usuario = $_SESSION["Usuario"];

$calle_id = trim($_REQUEST["calle"]);
$numero = trim($_REQUEST["nro"]);

try {
	$con = new Conexion();
	$con->OpenConexion();
    $resp_json = [];
    $existe_geo = CalleBarrio::existe_georeferencia(
                                                    id_calle: $calle_id, 
                                                    num_calle: $numero, 
                                                    connection: $con
                                                  );
    $existe_calle = Calle::existe_id_calle(
                                         id_calle: $calle_id, 
                                         connection: $con
                                        );
    if ($existe_geo && $existe_calle) {
        $georeferencia = new CalleBarrio(
                                         connection: $con,
                                         id_geo: $existe_geo
                                        );
        $georeferencia->set_pendiente_by_min_max_punto();
        $id_barrio = $georeferencia->get_id_barrio();
        $barrio = new Barrio(
                             coneccion: $con,
                             id_barrio: $id_barrio
                            );
        $resp_json["lat"] = $georeferencia->geo_lat_by_number($numero);
        $resp_json["lon"] = $georeferencia->geo_lon_by_number($numero);
        $resp_json["barrio"] = $barrio->get_barrio();

    } else if (!$existe_geo && $existe_calle) {
        $calle = new Calle(id_calle: $calle_id);
        $calle_nombre = $calle->get_calle_nombre();
        $ch = curl_init();
        $array_replace = ['á','é','í','ó','ú','ñ', ' '];
        $array = ['a','e','i','o','u','n', '+'];
        $calle_url = str_replace($array_replace, $array, $calle_nombre);
        $url = "https://nominatim.openstreetmap.org/search?street=" . $calle_url . "+" . $numero . "&city=rio+tercero&format=jsonv2&limit=1&addressdetails=1&email=martinmonnittola@gmail.com";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $arr_obj_json = json_decode($response);

        if (!empty($arr_obj_json[0])
            && (!is_null($arr_obj_json[0]->lat)
            || !is_null($arr_obj_json[0]->lon))) {
            $resp_json["lat"] = $arr_obj_json[0]->lat;
            $resp_json["lon"] = $arr_obj_json[0]->lon;
            $address = $arr_obj_json[0]->address;
            $resp_json["barrio"] = (isset($address->neighbourhood)) ? $address->neighbourhood : null;
        }

    }

    $con->CloseConexion();
    $con = null;

    $resp_json = json_encode($resp_json);
    echo $resp_json;

} catch (Exception $e) {
    if ($con) {
        $con->CloseConexion();
    }
    echo "Error Message: " . $e;
}