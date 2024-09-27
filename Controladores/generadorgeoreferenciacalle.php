<?php 
require_once 'Conexion.php';
require_once '../Modelo/Calle.php';
try {
	$Con = new Conexion();
	$Con->OpenConexion();	
	$consultar = "select calle_nombre,
						 id_calle
				  from calle 
				  where estado = 1
				  order by calle_nombre ASC";
	if(!$ejecutar_consultar = mysqli_query($Con->Conexion, $consultar)){
		throw new Exception("Problemas al intentar Consultar Registros de Calles", 0);
	}
	$ch = curl_init();
	$mensaje = "";

	while ($ret = mysqli_fetch_assoc($ejecutar_consultar)) {
		$url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=+" . str_replace(" ", "+", $ret["calle_nombre"]) . ",%20Río%20Tercero,%20Cordoba&radius=500&location=-32.170177%20-64.117238&types=address&key=clave_cuenta_de_municipalidad_google";
		$direccion_mapa = 0;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$calle = new Calle(id_calle : $ret["id_calle"]);
		$response = curl_exec($ch);
		$arr_obj_json = json_decode($response);
		if ($arr_obj_json) {
			for ($i = 0; $i < count($arr_obj_json->predictions); $i++) {
				if (!is_null($arr_obj_json->predictions[$i]->description)) {
					if (str_contains($arr_obj_json->predictions[$i]->description, ", Río Tercero")) {
						$calle->set_calle_open($ret["calle_nombre"]);
						$calle->update();
						$direccion_mapa++;
						break;
					}
				}
			}
		}
		if ($direccion_mapa == 0) {
			curl_setopt($ch, CURLOPT_URL, "https://nominatim.openstreetmap.org/search?street=" . str_replace(" ", "+", $ret["calle_nombre"]) . "&city=rio+tercero&format=jsonv2&limit=1&email=martinmonnittola@gmail.com");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			$arr_obj_json = json_decode($response);
			if ($arr_obj_json) {
				if (!is_null($arr_obj_json[0]->lat) || !is_null($arr_obj_json[0]->lon)) {
					$calle->set_calle_open($ret["calle_nombre"]);
					$calle->update();
					$direccion_mapa++;
					continue;
				}
			} else {
				$array_palabras = explode(" ", $ret["calle_nombre"]);
				$cant = count($array_palabras);
				for($j = 0; $j < $cant; $j++) {
					$array_aternativo = array_slice($array_palabras, $j, $cant - $j);
					$direccion_alternativa = implode(" ", $array_aternativo);
					curl_setopt($ch, CURLOPT_URL, "https://nominatim.openstreetmap.org/search?street=" . str_replace(" ", "+", $direccion_alternativa) . "&city=rio+tercero&format=jsonv2&limit=1&email=martinmonnittola@gmail.com");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$response = curl_exec($ch);
					$arr_obj_json = json_decode($response);
					if ($arr_obj_json) {
						if (!is_null($arr_obj_json[0]->lat) || !is_null($arr_obj_json[0]->lon)) {
							$calle->set_calle_open($direccion_alternativa);
							$calle->update();
							$direccion_mapa++;
							break;
						}
					}
				}
			}
		}
	}
	curl_close($ch);
	$mensaje .= "Se actualizarion las direcciones correctamente";
	$Con->CloseConexion();
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode("mensaje: $mensaje");
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
