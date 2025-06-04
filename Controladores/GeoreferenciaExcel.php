<?php 
	/*
	*
	* This file is part of Rastreador3.
	*
	* Rastreador3 is free software; you can redistribute it and/or modify
	* it under the terms of the GNU General Public License as published by
	* the Free Software Foundation; either version 2 of the License, or
	* (at your option) any later version.
	*
	* Rastreador3 is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	*
	* You should have received a copy of the GNU General Public License
	* along with Rastreador3; if not, write to the Free Software
	* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
	*/

	session_start();
	header('Content-Type: application/json'); 
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/sys_config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Movimiento.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/MovimientoMotivo.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Archivo.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Formulario.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Parametria.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Persona.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Responsable.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Barrio.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Calle.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Motivo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/CentroSalud.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	use Google\Client;
	use Google\Service\Sheets\SpreadSheet;
	use Google\Service\Drive;


    try {

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$con = new Conexion();
			$con->OpenConexion();
			$private_key = Parametria::get_value_by_code($con, 'SECRET_KEY');

            $id_archivo = $_POST["id_archivo"];
            $id_centro = $_POST["centro_salud"];

            $archivo = new Archivo(
				   coneccion_base: $con,
					   id_archivo: $id_archivo
					);
			$file_id = $archivo->get_id_file();
			$seccion = $archivo->get_seccion();
			$private_key = Parametria::get_value_by_code($con, 'SECRET_KEY');

			$client_drive = new Google_Client();
			$client_drive->setAuthConfig(array("type" => TYPE_ACCOUNT,
										 "client_id" => CLIENT_ID,
										 "client_email" => CLIENT_EMAIL,
										 "private_key" => $private_key, 
										 "signing_algorithm" => "HS256"));

			$client_drive->addScope([Google_Service_Drive::DRIVE]);
			$client_drive->addScope([Google_Service_Sheets::SPREADSHEETS]);
			$service = new Google_Service_Drive($client_drive);

			$file = new Google_Service_Drive_DriveFile();
			$file->setName('FILE_TEMPORAL.xlsx');
			$file->setMimeType('application/vnd.google-apps.spreadsheet');

			$createdFile = $service->files->copy($file_id, $file);
			$id_spreadsheet = $createdFile->getId();

			$service_sheets = new Google_Service_Sheets($client_drive);

			if (($seccion == "11 Años") || ($seccion == "EMBARAZADAS")) {
				$fila = '!A3:';
				$com = 0;
			} else if ($seccion == "C. INDICE PEDIATRIA") {
				$fila = '!A2:';
				$com = 1;
			} else {
				$fila = '!A4:';
				$com = 0;
			}

			if ($seccion == "11 Años") {
				$responsable_nombre = "WOLYNIEC Jorge - Area Local";
				$col = 'E';
				$range = $seccion . $fila . $col;
			} else if ($seccion == "C. INDICE PEDIATRIA") {
				//$col = 'AH';
				$responsable_nombre = "Constanza Bertone";
				$range = $seccion;
			} else if ($seccion == "C. INDICE ENFERMERIA") {
				$responsable_nombre = "DELLAROSSA Mónica. ENFERMERA.";
				$col = 'U';
				$range = $seccion . $fila . $col;
			} else {
				$responsable_nombre = "WOLYNIEC Jorge - Area Local";
				$col = 'K';
				$range = $seccion . $fila . $col;
			}

			$result = $service_sheets->spreadsheets_values->get($id_spreadsheet, $range);
			$service->files->delete($id_spreadsheet);

			$highestRow = count($result->values) - 1;

			$Fecha =  date("Y-m-d");
			$Fecha_Accion = date("Y-m-d");
			$observacion = "";
			$response_json = [];
			$row_json = [];
			$request = [];
			$row_request = [];
			$multi_request_ch = curl_multi_init();
			$active = null;
			$server = 0;

			for ($row = $com; $row <= $highestRow; $row++) {
				$observacion = "";
				$lista_motivos = [];
				if (count($result->values[$row]) == 0) {
					continue;
				}
				$highestColumnIndex = count($result->values[$row]) - 1;
				if ($seccion == "EMBARAZADAS") {
					$responsable_nombre= "Florencia Gil";
					for ($col = 0; $col <= $highestColumnIndex; $col++) {
						$value = (!empty($result->values[$row][$col])) ? $result->values[$row][$col] : null;
						$id_barrio = Barrio::get_id_by_name($con, "Castagnino");
						switch ($col) {
							case 0:
								$lista = explode(" ", $value);
								$apellido = $lista[0];
								$nombre = implode( " ",array_slice($lista, 1));
								break;
							case 1:
								$hc = $value;
								break;
							case 2:
								$dni = $value;
								break;
							case 3:
								$direccion = $value;
								break;
							case 4:
								$telefono = $value;
								break;
							case 5:
								$obra_social = $value;
								break;
							case 6:
								$observacion .= " FPP : " . $value;
								break;
							case 7:
								$observacion .= " vacunas : " . $value;
								break;
							case 8:
								$observacion .= "atiende : " . $value;
								break;
							default :
								$observacion .= "atiende : " . $value;
								break;
						}
					}
				} else if ($seccion == "C. INDICE ENFERMERIA") {
					for ($col = 0; $col <= $highestColumnIndex; $col++) {
                        $observacion = "";
						switch ($col) {
							case 3:
								$dni = $value;
								break;
							case 5:
								$direccion = $value;
								break;
							default :
								$observacion .= " " . $value;
								break;
						}
					}
				} else if ($seccion == "C. INDICE PEDIATRIA") {
                    $observacion = "";
					for ($col = 0; $col <= $highestColumnIndex; $col++) {
						$value = (!empty($result->values[$row][$col])) ? $result->values[$row][$col] : null;
						switch ($col) {
							case 3:
								$dni = $value;
								break;
							case 5:
								$direccion = $value;
								break;
							default :
                                $observacion .= " " . $value;
								break;
						}
					}
				} else if ($seccion == "11 Años") {
					$observacion = "";
					for ($col = 0; $col <= $highestColumnIndex; $col++) {
						$value = (!empty($result->values[$row][$col])) ? $result->values[$row][$col] : null;
						$id_barrio = Barrio::get_id_by_name($con, "Castagnino");
						switch ($col) {
							case 0:
								$lista = explode(" ", $value);
								$apellido = $lista[0];
								$nombre = implode( " ",array_slice($lista, 1));
								break;
							case 1:
								$lista = explode("/", $value);
								$barrio = $lista[0];
								$calle = $lista[1];
								break;
							case 2:
								$dni = $value;
								break;
							default :
								$observacion .= " " . $value;
								break;
						}
					}
				}

                $Fecha_Nacimiento = (empty($Fecha_Nacimiento)) ? null : $Fecha_Nacimiento;
                $id_persona = Persona::get_id_persona_by_dni(
                    coneccion: $con,
                    documento: $dni
                    );

                if (!$id_persona) {
                    continue;
                } else {
                    $persona = new Persona(ID_Persona: $id_persona);
                    $georeferencia = $persona->getGeoreferencia();
                    if (!$georeferencia) {
                        $modificacion = $persona->setCalleNro($direccion);
                        $calle = $persona->getNombre_Calle();
                        if ($persona->getId_Calle()) {
                            $calle_url = str_replace(" ", "+", $calle);
                            if ($server == 0) {
                                $url = "https://nominatim.openstreetmap.org/search?street=" . $calle_url . "+" . $persona->getNro() . "&city=rio+tercero&format=jsonv2&limit=1&email=desarrollo.automation.test@gmail.com";
                                $row_request["server"] = $server;
                                $server++;
                            } else if ($server == 1) {
								$url = "https://api.tomtom.com/search/2/geocode/" . $calle_url . "+" . $persona->getNro() . "+,rio+tercero,Cordoba.json?storeResult=false&view=Unified&lat=-32.194998&lon=-64.1684546&radius=300000&key=Tj0CNZcoMipF9sVJ2GKE3LZ907yNogpt";
                                $row_request["server"] = $server;
                                $server++;
                            } else {
                                $url = "https://api.geoapify.com/v1/geocode/autocomplete?text=" . $calle_url . "+" . $persona->getNro() . ",+Cordoba,+Rio+Tercero&city=rio+tercero&format=json&apiKey=b43e46b080e940b39d1bbee88b9cb320";
                                $row_request["server"] = $server;
                                $server = 0;
                            }
                            $row_request["url"] = $url;
                            $row_request["calle_url"] = $calle_url . "+" . $persona->getNro();
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $row_request["url"]);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $row_request["channel"] = $ch;
                            $row_request["persona"] = $persona;
                            $request[] = $row_request;
							$response_json[] = $row_request;
                        }
                        $persona->update_direccion();
                    }
                }
			}

			$time_send = Parametria::get_value_by_code($con, 'TIME_SEND');
			$count = 0;
			$row = [];
			foreach ($request as $key => $value) {
				$row["ch"] = $value["channel"];
				$row["server"] = $value["server"];
				$row["persona"] = $value["persona"];
				$row["calle_url"] = $value["calle_url"];
				if ($count < 2) {
					curl_multi_add_handle($multi_request_ch, $row["ch"]);
					$row_exec[] = $row;
					$count++;
				} else {
					curl_multi_add_handle($multi_request_ch, $row["ch"]);
					$row_exec[] = $row;
					do {
						$mrc = curl_multi_exec($multi_request_ch, $active);
					} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		
					while ($active && $mrc == CURLM_OK) {
						if (curl_multi_select($multi_request_ch) != -1) {
							do {
								$mrc = curl_multi_exec($multi_request_ch, $active);
							} while ($mrc == CURLM_CALL_MULTI_PERFORM);
						}
					}
					$count = 0;
					foreach ($row_exec as $indice => $valor) {
						$ch = $valor["ch"];
						$response_body = curl_multi_getcontent($ch );
						curl_multi_remove_handle($multi_request_ch, $ch);
						$arr_obj_json = json_decode($response_body);
						if ($arr_obj_json &&  $valor["server"] == 0) {
							if (!is_null($arr_obj_json[0]->lat) || !is_null($arr_obj_json[0]->lon)) {
								$point = "POINT(" . $arr_obj_json[0]->lat . ", " . $arr_obj_json[0]->lon . ")";
								$valor["persona"]->setGeoreferencia($point);
								$valor["persona"]->update_geo();
							} else {
								$valor["persona"]->setGeoreferencia(null);
							}
						} else if ($arr_obj_json &&  $valor["server"] == 1) {
							if (!is_null($arr_obj_json->results[0]->position->lat) || !is_null($arr_obj_json->features[0]->position->lon)) {
								$point = "POINT(" . $arr_obj_json->results[0]->position->lat . ", " . $arr_obj_json->results[0]->position->lon . ")";
								$valor["persona"]->setGeoreferencia($point);
								$valor["persona"]->update_geo();
							} else {
								$valor["persona"]->setGeoreferencia(null);
							}
						} else if ($arr_obj_json &&  $valor["server"] == 2) {
							if (!is_null($arr_obj_json->results[0])) {
								$point = "POINT(" . $arr_obj_json->results[0]->lat . ", " . $arr_obj_json->results[0]->lon . ")";
								$valor["persona"]->setGeoreferencia($point);
								$valor["persona"]->update_geo();
							} else {
								$valor["persona"]->setGeoreferencia(null);
							}
						} else {
							$url = "https://api.tomtom.com/search/2/geocode/" . $valor["calle_url"] . "+" . $persona->getNro() . "+,rio+tercero,Cordoba.json?storeResult=false&view=Unified&key=Tj0CNZcoMipF9sVJ2GKE3LZ907yNogpt";
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_FAILONERROR, true);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							$response = curl_exec($ch);
							$error = curl_error($ch);
							$arr_obj_json = json_decode($response);

							if (!is_null($arr_obj_json->results[0]->position->lat) || !is_null($arr_obj_json->features[0]->position->lon)) {
								$point = "POINT(" . $arr_obj_json->results[0]->position->lat . ", " . $arr_obj_json->results[0]->position->lon . ")";
								$valor["persona"]->setGeoreferencia($point);
								$valor["persona"]->update_geo();
							} else {
								$valor["persona"]->setGeoreferencia(null);
							}
						}
						curl_close($ch);
					}
					$row_exec = [];
					usleep($time_send);
				}
			}

			if ($row_exec) {
				do {
					$mrc = curl_multi_exec($multi_request_ch, $active);
				} while ($mrc == CURLM_CALL_MULTI_PERFORM);
	
				while ($active && $mrc == CURLM_OK) {
					if (curl_multi_select($multi_request_ch) != -1) {
						do {
							$mrc = curl_multi_exec($multi_request_ch, $active);
						} while ($mrc == CURLM_CALL_MULTI_PERFORM);
					}
				}
	
				foreach ($row_exec as $indice => $valor) {
					$ch = $valor["ch"];
					$response_body = curl_multi_getcontent($ch );
					curl_multi_remove_handle($multi_request_ch, $ch);
					$arr_obj_json = json_decode($response_body);
					if ($arr_obj_json &&  $valor["server"] == 0) {
						if (!is_null($arr_obj_json[0]->lat) || !is_null($arr_obj_json[0]->lon)) {
							$point = "POINT(" . $arr_obj_json[0]->lat . ", " . $arr_obj_json[0]->lon . ")";
							$valor["persona"]->setGeoreferencia($point);
							$valor["persona"]->update_geo();
						} else {
							$valor["persona"]->setGeoreferencia(null);
						}
					} else if ($arr_obj_json &&  $valor["server"] == 1) {
						if (!is_null($arr_obj_json->results[0]->position->lat) || !is_null($arr_obj_json->features[0]->position->lon)) {
							$point = "POINT(" . $arr_obj_json->results[0]->position->lat . ", " . $arr_obj_json->results[0]->position->lon . ")";
							$valor["persona"]->setGeoreferencia($point);
							$valor["persona"]->update_geo();
						} else {
							$valor["persona"]->setGeoreferencia(null);
						}
					} else if ($arr_obj_json &&  $valor["server"] == 2) {
						if (!is_null($arr_obj_json->results[0])) {
							$point = "POINT(" . $arr_obj_json->results[0]->lat . ", " . $arr_obj_json->results[0]->lon . ")";
							$valor["persona"]->setGeoreferencia($point);
							$valor["persona"]->update_geo();
						} else {
							$valor["persona"]->setGeoreferencia(null);
						}
					} else {
						$url = "https://api.tomtom.com/search/2/geocode/" . $valor["calle_url"] . "+" . $persona->getNro() . "+,rio+tercero,Cordoba.json?storeResult=false&view=Unified&key=Tj0CNZcoMipF9sVJ2GKE3LZ907yNogpt";
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_FAILONERROR, true);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$response = curl_exec($ch);
						$error = curl_error($ch);
						$arr_obj_json = json_decode($response);

						if (!is_null($arr_obj_json->results[0]->position->lat) || !is_null($arr_obj_json->features[0]->position->lon)) {
							$point = "POINT(" . $arr_obj_json->results[0]->position->lat . ", " . $arr_obj_json->results[0]->position->lon . ")";
							$valor["persona"]->setGeoreferencia($point);
							$valor["persona"]->update_geo();
						} else {
							$valor["persona"]->setGeoreferencia(null);
						}

					}
					curl_close($ch);
				}
			}
			curl_multi_close($multi_request_ch);
			$con->CloseConexion();

			$mensaje = "Los pacientes se han georeferenciado correctamente";
			echo json_encode($response_json);
		} else {
			$mensaje = "El metodo es incorrecto";
			header( 'HTTP/1.1 400 BAD REQUEST' );
			echo $mensaje;
		}
	} catch(Exception $e) {
		$con->CloseConexion();
		echo "Error Message: " . $e;
  	}
