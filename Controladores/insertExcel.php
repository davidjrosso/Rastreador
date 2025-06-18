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
	//header('Content-Type: application/json');
	header('Content-Type: text/event-stream');
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/sys_config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Movimiento.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/MovimientoMotivo.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Archivo.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Formulario.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Parametria.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Persona.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/CentroSalud.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Responsable.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Barrio.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Calle.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Motivo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/CentroSalud.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	use Google\Client;
	use Google\Service\Sheets\SpreadSheet;
	use Google\Service\Drive;


	function codigoExcelMotivo($codigo){
		switch ($codigo) {
			case 'CDAD':
				$result = "CDAD";
				break;
			case 'CDAT':
				$result = "CDAT";
				break;
			case 'CDOS':
				$result = "CDOS";
				break;
			case 'CDSO':
				$result = "CDSO";
				break;
			case 'EPAD':
				$result = "EPAD";
				break;
			case 'EPAT':
				$result = "EPAT";
				break;
			case 'EPOS':
				$result = "EPOS";
				break;
			case 'EPSO':
				$result = "EPSO";
				break;
			case 'VC':
				$result = "VC";
				break;
			case 'VI':
				$result = "VI";
				break;
			case 'VEC':
				$result = "VEC";
				break;
			case 'VSD':
				$result = "VSD";
				break;
			default:
				$result = "";
				break;
		}
		return $result;
	}

	function colExcel($col){
		switch ($col) {
			case 0:
				$result = "A1";
				break;
			case 1:
				$result = "B1";
				break;
			case 2:
				$result = "C1";
				break;
			case 3:
				$result = "D1";
				break;
			case 4:
				$result = "E1";
				break;
			case 5:
				$result = "F1";
				break;
			case 6:
				$result = "G1";
				break;
			case 7:
				$result = "H1";
				break;
			case 8:
				$result = "I1";
				break;
			case 9:
				$result = "J1";
				break;
			case 10:
				$result = "K1";
				break;
			case 11:
				$result = "L1";
				break;
			case 12:
				$result = "M1";
				break;
			case 13:
				$result = "N1";
				break;
			case 14:
				$result = "O1";
				break;
			case 15:
				$result = "P1";
				break;
			case 16:
				$result = "Q1";
				break;
			case 17:
				$result = "R1";
				break;
			case 18:
				$result = "S1";
				break;
			case 19:
				$result = "T1";
				break;
			case 20:
				$result = "U1";
				break;
			case 21:
				$result = "V1";
				break;
			case 22:
				$result = "W1";
				break;
			default:
				$result = "default";
				break;
		}
		return $result;
	}

	function objetoExcel($obj, $valor, $connection, $col_header=null){
		$datos = array();
		switch ($obj) {
			case "nombre_apellido":
				$lista = explode(" ", $valor);
				$datos["apellido"] = preg_replace("~,~", "", $lista[0]);
				$datos["nombre"] = implode( " ",array_slice($lista, 1));
				break;
			case "hc":
				$datos = $valor;
				break;
			case "fecha_nacimiento":
				$lista_fecha = explode("/", $valor);
				$lista_fecha = array_reverse($lista_fecha);
				$value = implode( "-", $lista_fecha);
				$fecha_excel = strtotime($value);
				$datos = date(format: 'Y-m-d',timestamp: $fecha_excel);
				break;
			case "dni":
				$dni = preg_replace("~[/.,]~", "", $valor);
				$datos = $dni;
				break;
			case "observacion":
				$observ = (!empty($valor)) ? $valor : "no hay datos"; 
				$datos = $col_header . " : " . $observ;
				break;
			case "direccion":
				$direccion_datos = explode("/", $valor);
				$datos["direccion"] = $direccion_datos[0];
				if (count($direccion_datos) > 1) {
					$result_array = [];
					$is_departament = preg_match(
						"~[0-9]+~",
						$direccion_datos[1],
						$result_array
								);
					$datos["departamento"] = ($is_departament) ? $result_array[0] : null;
				}
				break;
			case "barrio":
				$barrio = ($valor) ? $valor : null;
				if(!empty($barrio)) {
					$datos = Barrio::get_id_by_name($connection, $barrio);
				}
				break;
			case "departamento":
				$departam = $valor;
				$result_array = [];
				$is_departament = preg_match(
					"~[0-9]+~",
					$departam,
					$result_array
							);
				$datos = ($is_departament) ? $result_array[0] : null;
				break;
			case "telefono":
				$datos = $valor;
				break;
			case "obra_social":
				$datos = $valor;
				break;
			case "manzana":
				$datos = $valor;
				break;
			case "familia":
				$datos = $valor;
				break;
			default :
				$valor_fecha = (!empty($col_header)) ? $col_header : null;
				//$pattern = "/([0-9][0-9]|[1-9]).([0-9][0-9]|[1-9]).[2-9][0-9][0-9][0-9]/";
				$pattern = "/([0-9][0-9]).([0-9][0-9]).[2-9][0-9][0-9][0-9]/";
				$is_fecha = preg_match(
								$pattern,
								$valor_fecha,
								$result_array
										);
				$motivo = codigoExcelMotivo(trim($valor));;
				if ($is_fecha && $motivo) {
					$datos["fecha"] = null;
					if (!empty($result_array[0])) {
						$lista_fecha = explode("/", $result_array[0]);
						$lista_fecha = array_reverse($lista_fecha);
						$valor_fecha = implode( "-", $lista_fecha);
						$fecha_excel = strtotime($valor_fecha);
						$fecha_movimiento  = date(format: 'Y-m-d',timestamp: $fecha_excel);
						$datos["fecha"] = $fecha_movimiento;
					}
					$datos["motivo"]  = $motivo;
				} else {
					$observ = (!empty($valor)) ? $valor : "no hay datos"; 
					$datos = $col_header . " : " . $observ;
				}
				break;
		}
		return $datos;
	}

	function rows_persona($rows_excel, $config_datos, $connection) {
		$lista_personas = array();
		$highestRow = count(value: $rows_excel) - 1;
		$list_ignore_row = explode("-", $config_datos["ignore"]);
		$break_row = (isset($config_datos["break"])) ? $config_datos["break"] : null;
		$indixe_col_h = (isset($config_datos["col"])) ? $config_datos["col"] : null;
		$com = (isset($config_datos["row"])) ? $config_datos["row"] : null;

		for ($row = $com; $row <= $highestRow; $row++) {
			if (count($rows_excel->values[$row]) == 0 
				|| !$rows_excel->values[$row][0]
				|| in_array($rows_excel->values[$row][0], $list_ignore_row)) {
				continue;
			}

			if ($break_row && $rows_excel[$row][0] == $break_row) {
				break;
			}

			$lista_valores = null;
			$lista_valores["observacion"] = "";
			$highestColumnIndex = count($rows_excel[$row]) - 1;

			for ($col = 0; $col <= $highestColumnIndex; $col++) {
				$value = (!empty($rows_excel[$row][$col])) ? $rows_excel[$row][$col] : null;
				$col_excel = colExcel($col);
				$col_header = (!empty($rows_excel[$indixe_col_h][$col])) ? $rows_excel[$indixe_col_h][$col] : null;
				$col_config = (isset($config_datos[$col_excel])) ? $config_datos[$col_excel] : null;
				$valor = objetoExcel(
									obj: $col_config,
									valor: $value,
									connection: $connection,
									col_header: $col_header
									);
				if ($col_config == "observacion") {
						$lista_valores["observacion"] .= " - " . $valor;
				} else if ($col_config == "motivo") {
						$lista_valores["motivos"][] = $valor;
				} else if ($col_config == "default") {
					if (is_array($valor)) {
						$lista_valores["motivos"][] = $valor;
					} else {
						$lista_valores["observacion"] .= " - " . $valor;
					}
				} else {
					$lista_valores[$config_datos[$col_excel]] = $valor;
				}
			}
			$lista_personas[] = $lista_valores;
		}
		return $lista_personas;
	}

    try {
		ob_implicit_flush(true);
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
			$id_responsable = $archivo->get_responsable();
			$id_centro_salud = $archivo->get_centro_salud();
			$centro_salud = new CentroSalud(id_centro: $id_centro_salud, coneccion_base: $con);
			$id_barrio_centro = $centro_salud->get_id_barrio();
			$config_datos = $archivo->get_configuracion();
			$list_conf_datos = explode("|", $config_datos);
			$lista_datos = array();	
			foreach ($list_conf_datos as $value) {
				$row_data = explode("-", $value);
				$lista_datos[$row_data[0]] = $row_data[1];
			}

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
			} else if ($seccion == "C. INDICE PEDIATRIA") {
				$fila = '!A2:';
			} else {
				$fila = '!A4:';
			}

			if ($seccion == "11 Años") {
				$col = 'E';
				$range = $seccion . $fila . $col;
			} else if ($seccion == "C. INDICE ENFERMERIA") {
				$col = 'U';
				$range = $seccion . $fila . $col;
			} else {
				$range = $seccion;
			}

			$result = $service_sheets->spreadsheets_values->get($id_spreadsheet, $range);
			$service->files->delete($id_spreadsheet);

			$highestRow = count(value: $result) - 1;
			$Fecha =  date("Y-m-d");
			$Fecha_Accion = date("Y-m-d");
			$observacion = "";
			$domicilios_json = [];
			$georefencias_json = [];
			$row_json = [];
			$request = [];
			$row_request = [];
			$multi_request_ch = curl_multi_init();
			$progress = 0;
			$active = null;
			$server = 0;

			$lista_personas = rows_persona($result, $lista_datos, $con);

			foreach ($lista_personas as $row => $dato) {
				$consulta_osm = true;
				$lista_motivos = (!empty($dato["motivos"])) ? $dato["motivos"] : [];
				$dni = $dato["dni"];
				$nombre_apellido = $dato["nombre_apellido"];
				$nombre = $nombre_apellido["nombre"];
				$apellido = $nombre_apellido["apellido"];
				$Fecha_Nacimiento = $dato["fecha_nacimiento"];
				$direccion = (isset($dato["direccion"]["direccion"])) ? $dato["direccion"]["direccion"] : null;
				$departam = (isset($dato["departamento"])) ? $dato["departamento"] : null;
				$departam = (!empty($dato["direccion"]["departamento"])) ? $dato["direccion"]["departamento"] : $departam;
				$hc = $dato["hc"];
				$obra_social = (!empty($dato["obra_social"])) ? $dato["obra_social"] : null;
				$telefono = (!empty($dato["telefono"])) ? $dato["telefono"] : null;
				$id_barrio = (!empty($dato["barrio"])) ? $dato["barrio"] : $id_barrio_centro;
				$estado = 1;
				$ID_TipoAccion = 1;
				$email = null;
				$ID_Usuario = 100;

				if (!$lista_motivos) {
					$id_persona = (empty($dni)) ? null : Persona::get_id_persona_by_dni($con,
																						$dni
																						);
					if (!is_null($id_persona) && is_numeric($id_persona)) {
						$persona = new Persona(ID_Persona: $id_persona);
						$georeferencia = $persona->getGeoreferencia();
						$modificacion = $persona->setCalleNro($direccion);
						if (is_numeric($departam)) $persona->setFamilia($departam);
						$calle = $persona->getNombre_Calle();
						$nro_calle = $persona->getNro();

						if (($persona->getId_Calle() && !$modificacion)) {
							$calle_url = str_replace(" ", "+", $persona->getNombre_Calle());

							if ($server == 0 && $nro_calle >= 1000) $server++;

							if ($server == 0) {
								$url = "https://nominatim.openstreetmap.org/search?street=" . $calle_url . "+" . $persona->getNro() . "&city=rio+tercero&format=jsonv2&limit=1&email=desarrollo.automation.test@gmail.com";
								$row_request["server"] = $server;
								$server++;
							} else if ($server == 1) {
								$url = "https://api.tomtom.com/search/2/geocode/" . $calle_url . "+" . $persona->getNro() . "+,rio+tercero,Cordoba.json?storeResult=false&view=Unified&lat=-32.194998&lon=-64.1684546&radius=300000&key=Tj0CNZcoMipF9sVJ2GKE3LZ907yNogpt";
								$row_request["server"] = $server;
								$server++;
							} else {
								$url = "https://api.geoapify.com/v1/geocode/autocomplete?text=" . $calle_url . "+" . $persona->getNro() . "&city=rio+tercero&format=json&apiKey=b43e46b080e940b39d1bbee88b9cb320";
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
							$row_request["direccion"] = $direccion;
							$request[] = $row_request;
						}
						$persona->update_direccion();

						$row_json["calle_rastreador"] = Calle::existe_calle($direccion);
						$row_json["domicilio"] = (empty($direccion)) ? "" : $direccion;
						$row_json["form"]["persona"] = $persona->jsonSerialize();
						$domicilios_json[]["formulario"] = $row_json;
					} else {
						if (!Persona::is_registered($dni)) {
							$persona = new Persona(
								xApellido : $apellido,
								xNombre : $nombre,
								xBarrio :  $id_barrio,
								xDNI : $dni,
								xNro_Carpeta:$hc,
								xEstado : $estado,
								xObra_Social : $obra_social,
								xFecha_Nacimiento: $Fecha_Nacimiento,
								xTelefono : $telefono,
								xMail:$email,
								xID_Escuela: 2
							);
							$modificacion = $persona->setCalleNro($direccion);
							if (is_numeric($departam)) $persona->setFamilia($departam);
							$nro_calle = $persona->getNro();
							
							if ($persona->getId_Calle()) {
								$calle_url = str_replace(" ", "+", $persona->getNombre_Calle());

								if ($server == 0 && $nro_calle >= 1000) $server++;

								if ($server == 0) {
									$url = "https://nominatim.openstreetmap.org/search?street=" . $calle_url . "+" . $persona->getNro() . "&city=rio+tercero&format=jsonv2&limit=1&email=desarrollo.automation.test@gmail.com";
									$row_request["server"] = $server;
									$server++;
								} else if ($server == 1) {
									$url = "https://api.tomtom.com/search/2/geocode/" . $calle_url . "+" . $persona->getNro() . "+,rio+tercero,Cordoba.json?storeResult=false&view=Unified&lat=-32.194998&lon=-64.1684546&radius=300000&key=Tj0CNZcoMipF9sVJ2GKE3LZ907yNogpt";
									$row_request["server"] = $server;
									$server++;
								} else {
									$url = "https://api.geoapify.com/v1/geocode/autocomplete?text=" . $calle_url . "+" . $persona->getNro() . "&city=rio+tercero&format=json&apiKey=b43e46b080e940b39d1bbee88b9cb320";
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
								$row_request["direccion"] = $direccion;
								$request[] = $row_request;
							}
							$persona->save();
							$consulta_osm = false;
							$id_persona = $persona->getID_Persona();
							$detalles = "El usuario con ID: $ID_Usuario ha registrado un nueva persona. Datos: Persona: " . $persona->getID_Persona();
							$accion = new Accion(
								xaccountid: $ID_Usuario,
								xFecha: $Fecha,
								xDetalles: $detalles,
								xID_TipoAccion: $ID_TipoAccion
							);
							$accion->save();
							$row_json["form"]["persona"] = $persona->jsonSerialize();
							$domicilios_json[]["formulario"] = $row_json;
						} 
						$row_json["calle_rastreador"] = Calle::existe_calle($direccion);
						$row_json["domicilio"] = (empty($direccion)) ? "" : $direccion;
					}
					if ($row % 10 == 9) {
						$progress = $row / (2 * $highestRow);
						$json_progress["progreso"] = $progress;
						echo json_encode($json_progress) . ";";
						ob_flush();
					}
				}

				foreach ($lista_motivos as $key => $value) {
					if (!isset($value["motivo"]) || !isset($value["fecha"])) {
						$id_persona = (empty($dni)) ? null : Persona::get_id_persona_by_dni($con,
																							$dni
																							);
						if (!is_null($id_persona) && is_numeric($id_persona)) {
							$persona = new Persona(ID_Persona: $id_persona);
							$georeferencia = $persona->getGeoreferencia();
							$modificacion = $persona->setCalleNro($direccion);
							if (is_numeric($departam)) $persona->setFamilia($departam);
							$calle = $persona->getNombre_Calle();
							$nro_calle = $persona->getNro();

							if (($persona->getId_Calle() && !$modificacion)) {
								$calle_url = str_replace(" ", "+", $persona->getNombre_Calle());

								if ($server == 0 && $nro_calle >= 1000) $server++;

								if ($server == 0) {
									$url = "https://nominatim.openstreetmap.org/search?street=" . $calle_url . "+" . $persona->getNro() . "&city=rio+tercero&format=jsonv2&limit=1&email=desarrollo.automation.test@gmail.com";
									$row_request["server"] = $server;
									$server++;
								} else if ($server == 1) {
									$url = "https://api.tomtom.com/search/2/geocode/" . $calle_url . "+" . $persona->getNro() . "+,rio+tercero,Cordoba.json?storeResult=false&view=Unified&lat=-32.194998&lon=-64.1684546&radius=300000&key=Tj0CNZcoMipF9sVJ2GKE3LZ907yNogpt";
									$row_request["server"] = $server;
									$server++;
								} else {
									$url = "https://api.geoapify.com/v1/geocode/autocomplete?text=" . $calle_url . "+" . $persona->getNro() . "&city=rio+tercero&format=json&apiKey=b43e46b080e940b39d1bbee88b9cb320";
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
								$row_request["direccion"] = $direccion;
								$request[] = $row_request;
							}
							$persona->update_direccion();

							$row_json["calle_rastreador"] = Calle::existe_calle($direccion);
							$row_json["domicilio"] = $direccion;
							$row_json["form"]["persona"] = $persona->jsonSerialize();
							$domicilios_json[]["formulario"] = $row_json;
						}
						continue;
					}

					$email = null; 
					$ID_Usuario = 100;
					$ID_Motivo_1 = Motivo::get_id_by_codigo($con, $value["motivo"]);
					$ID_Motivo_2 = Motivo::get_id_by_codigo($con, "Sin Motivo");
					$ID_Motivo_3 = Motivo::get_id_by_name( $con, "Sin Motivo");
					$ID_Motivo_4 = Motivo::get_id_by_name($con, "Sin Motivo");
					$ID_Motivo_5 = Motivo::get_id_by_name($con, "Sin Motivo");
	
					$responsable = new Responsable(
									coneccion_base: $con,
										id_responsable: $id_responsable
					);

					$row_json["responsable"] = $responsable->jsonSerialize();

					$row_json["calle_rastreador"] = Calle::existe_calle($direccion);
					$row_json["domicilio"] = $direccion;

					$Fecha_Nacimiento = (empty($Fecha_Nacimiento)) ? null : $Fecha_Nacimiento;
					if (!Persona::is_registered($dni)) {
						$persona = new Persona(
							xApellido : $apellido,
							xNombre : $nombre,
							xBarrio :  $id_barrio,
							xDNI : $dni,
							xNro_Carpeta:$hc,
							xEstado : $estado,
							xObra_Social : $obra_social,
							xFecha_Nacimiento: $Fecha_Nacimiento,
							xTelefono : $telefono,
							xMail:$email,
							xID_Escuela: 2
						);
						$modificacion = $persona->setCalleNro($direccion);
						if (is_numeric($departam)) $persona->setFamilia($departam);
						$nro_calle = $persona->getNro();
						
						if ($persona->getId_Calle()) {
							$calle_url = str_replace(" ", "+", $persona->getNombre_Calle());

							if ($server == 0 && $nro_calle >= 1000) $server++;

							if ($server == 0) {
								$url = "https://nominatim.openstreetmap.org/search?street=" . $calle_url . "+" . $persona->getNro() . "&city=rio+tercero&format=jsonv2&limit=1&email=desarrollo.automation.test@gmail.com";
								$row_request["server"] = $server;
								$server++;
							} else if ($server == 1) {
								$url = "https://api.tomtom.com/search/2/geocode/" . $calle_url . "+" . $persona->getNro() . "+,rio+tercero,Cordoba.json?storeResult=false&view=Unified&lat=-32.194998&lon=-64.1684546&radius=300000&key=Tj0CNZcoMipF9sVJ2GKE3LZ907yNogpt";
                                $row_request["server"] = $server;
                                $server++;
							} else {
								$url = "https://api.geoapify.com/v1/geocode/autocomplete?text=" . $calle_url . "+" . $persona->getNro() . "&city=rio+tercero&format=json&apiKey=b43e46b080e940b39d1bbee88b9cb320";
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
							$row_request["direccion"] = $direccion;
							$request[] = $row_request;
						}
						$persona->save();
						$consulta_osm = false;
						$id_persona = $persona->getID_Persona();
						$detalles = "El usuario con ID: $ID_Usuario ha registrado un nueva persona. Datos: Persona: " . $persona->getID_Persona();
						$accion = new Accion(
							xaccountid: $ID_Usuario,
							xFecha: $Fecha,
							xDetalles: $detalles,
							xID_TipoAccion: $ID_TipoAccion
						);
						$accion->save();
					} else {
						$id_persona = Persona::get_id_persona_by_dni($con, $dni);
						if (is_null($id_persona)) {
							continue;
						}

						$persona = new Persona(ID_Persona: $id_persona);
						if ($consulta_osm) {
							$georeferencia = $persona->getGeoreferencia();
							$modificacion = $persona->setCalleNro($direccion);
							if (is_numeric($departam)) $persona->setFamilia($departam);
							$persona->setBarrio($id_barrio);
							$calle = $persona->getNombre_Calle();
							$nro_calle = $persona->getNro();

							if ($persona->getId_Calle()
								&& !$modificacion) {
								$calle_url = str_replace(" ", "+", $calle);

								if ($server == 0 && $nro_calle >= 1000) $server++;

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
								$row_request["direccion"] = $direccion;
								$request[] = $row_request;
							}
							$persona->update_direccion();
							$consulta_osm = false;
						}
					}

					$exist_mov = Movimiento::is_exist_movimiento_fecha(
																	   coneccion: $con,
																	   fecha: $value["fecha"],
																	   id_persona: $id_persona
																	  );
					if ($exist_mov) {
						$movimiento =  new Movimiento(
													  coneccion_base: $con,
													  xID_Movimiento: $exist_mov
													);
					} else {
						$movimiento = new Movimiento(
							coneccion_base: $con, 
									xFecha: $value["fecha"],
							Fecha_Creacion: $Fecha_Accion,
								xID_Persona: $persona->getID_Persona(),
								xID_Motivo_1: $ID_Motivo_1,
								xID_Motivo_2: $ID_Motivo_2,
								xID_Motivo_3: $ID_Motivo_3,
								xID_Motivo_4: $ID_Motivo_4,
								xID_Motivo_5: $ID_Motivo_5,
							xObservaciones: $observacion,
							xID_Responsable: $responsable->get_id_responsable(),
								xID_Centro: $id_centro,
						xID_OtraInstitucion: 1,
									xEstado: $estado
						);
						$movimiento->save();

						$motivo_movimiento = new MovimientoMotivo(
													  connection: $con, 
												   id_movimiento: $movimiento->getID_Movimiento(),
												   	   id_motivo: $ID_Motivo_1,
													  nro_motivo: 1,
													  	  estado: 1
																 );
						$motivo_movimiento->save();
					}
					$row_json["movimiento"] = $movimiento->jsonSerialize();
					$detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Movimiento. Datos: ID: " . $movimiento->getID_Movimiento() . " Fecha: $Fecha_Accion - Persona: " . $persona->getID_Persona() . " - Motivo 1: $ID_Motivo_1 - Motivo 2: $ID_Motivo_2 - Observaciones: $observacion - Responsable: " . $responsable->get_id_responsable();
					$accion = new Accion(
						xaccountid: $ID_Usuario,
						xFecha: $Fecha,
						xDetalles: $detalles,
						xID_TipoAccion: $ID_TipoAccion
					);
					$accion->save();
					$email = null;

					if (!$exist_mov) {
						$formulario = new Formulario(
								coneccion_base: $con,
											fecha: $Fecha_Accion,
											email: $email,
										persona: $persona->getID_Persona(),
									movimiento: $movimiento->getID_Movimiento(),
									responsable: $responsable->get_id_responsable(),
										estado: $estado
						);
						$formulario->save();
						$row_json["form"] = $formulario->jsonSerialize();
						$row_json["estado"] = 1;
						$domicilios_json[]["formulario"] = $row_json;
					} else {
						$row_json["form"]["persona"] = $persona->jsonSerialize();
						$domicilios_json[]["formulario"] = $row_json;
					}

					if ($row % 10 == 9) {
						$progress = $row / (2 * $highestRow);
						$json_progress["progreso"] = $progress;
						echo json_encode($json_progress) . ";";
						ob_flush();
					}
				}
			}

			$time_send = Parametria::get_value_by_code($con, 'TIME_SEND');
			$cant_request = count($request);
			$count = 0;
			$num_send = 0;
			$row = [];
			$geo_row = null;
			$row_exec = [];
			foreach ($request as $key => $value) {
				$row["ch"] = $value["channel"];
				$row["server"] = $value["server"];
				$row["persona"] = $value["persona"];
				$row["calle_url"] = $value["calle_url"];
				$row["direccion"] = $value["direccion"];
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
							if (!empty($arr_obj_json[0]) 
								&& (!is_null($arr_obj_json[0]->lat)
									|| !is_null($arr_obj_json[0]->lon))) {
								$point = "POINT(" . $arr_obj_json[0]->lat . ", " . $arr_obj_json[0]->lon . ")";
								$valor["persona"]->setGeoreferencia($point);
								$valor["persona"]->update_geo();
							} else {
								$valor["persona"]->setGeoreferencia(null);
								$geo_row["persona"] = $valor["persona"];
								$geo_row["direccion"] = $valor["direccion"];
							}
						} else if ($arr_obj_json &&  $valor["server"] == 1) {
							if (!empty($arr_obj_json->results) 
								&& (!is_null($arr_obj_json->results[0]->position->lat) 
									|| !is_null($arr_obj_json->features[0]->position->lon))) {
								$point = "POINT(" . $arr_obj_json->results[0]->position->lat . ", " . $arr_obj_json->results[0]->position->lon . ")";
								$valor["persona"]->setGeoreferencia($point);
								$valor["persona"]->update_geo();
							} else {
								$valor["persona"]->setGeoreferencia(null);
								$geo_row["persona"] = $valor["persona"];
								$geo_row["direccion"] = $valor["direccion"];
							}
						} else if ($arr_obj_json &&  $valor["server"] == 2) {
							if ( !empty($arr_obj_json->results) 
								 && (!is_null($arr_obj_json->results[0]->lat) 
									 || !is_null($arr_obj_json->features[0]->lon))) {
								$point = "POINT(" . $arr_obj_json->results[0]->lat . ", " . $arr_obj_json->results[0]->lon . ")";
								$valor["persona"]->setGeoreferencia($point);
								$valor["persona"]->update_geo();
							} else {
								$valor["persona"]->setGeoreferencia(null);
								$geo_row["persona"] = $valor["persona"];
								$geo_row["direccion"] = $valor["direccion"];
							}
						} else {
							$url = "https://api.tomtom.com/search/2/geocode/" . $valor["calle_url"] . "+" . $persona->getNro() . "+,rio+tercero,Cordoba.json?storeResult=false&view=Unified&key=Tj0CNZcoMipF9sVJ2GKE3LZ907yNogpt";
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_FAILONERROR, true);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							$response = curl_exec($ch);
							$error = curl_error($ch);
							$arr_obj_json = json_decode($response);

							if (!empty($arr_obj_json->results)
								&& (!is_null($arr_obj_json->results[0]->position->lat) 
									|| !is_null($arr_obj_json->features[0]->position->lon))) {
								$point = "POINT(" . $arr_obj_json->results[0]->position->lat . ", " . $arr_obj_json->results[0]->position->lon . ")";
								$valor["persona"]->setGeoreferencia($point);
								$valor["persona"]->update_geo();
							} else {
								$valor["persona"]->setGeoreferencia(null);
								$geo_row["persona"] = $valor["persona"];
								$geo_row["direccion"] = $valor["direccion"];
							}
						}
						curl_close($ch);
					}
					$row_exec = [];
					if ($num_send % 3 == 2) {
						$json_progress["progreso"] = $progress + ($num_send) / (2 * $cant_request);
						echo json_encode($json_progress) . ";";
						ob_flush();
					}
					usleep($time_send);
					$georefencias_json[] = $geo_row;
					$geo_row = null;
				}
				$num_send++;
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
							$geo_row["persona"] = $valor["persona"];
							$geo_row["direccion"] = $valor["direccion"];
						}
					} else if ($arr_obj_json &&  $valor["server"] == 1) {
						if (!empty($arr_obj_json->results)
							&& (!is_null($arr_obj_json->results[0]->position->lat) 
								|| !is_null($arr_obj_json->features[0]->position->lon))) {
							$point = "POINT(" . $arr_obj_json->results[0]->position->lat . ", " . $arr_obj_json->results[0]->position->lon . ")";
							$valor["persona"]->setGeoreferencia($point);
							$valor["persona"]->update_geo();
						} else {
							$valor["persona"]->setGeoreferencia(null);
							$geo_row["persona"] = $valor["persona"];
							$geo_row["direccion"] = $valor["direccion"];
						}
					} else if ($arr_obj_json &&  $valor["server"] == 2) {
						if (!is_null($arr_obj_json->results[0]->lat) || !is_null($arr_obj_json->features[0]->lon)) {
							$point = "POINT(" . $arr_obj_json->results[0]->lat . ", " . $arr_obj_json->results[0]->lon . ")";
							$valor["persona"]->setGeoreferencia($point);
							$valor["persona"]->update_geo();
						} else {
							$valor["persona"]->setGeoreferencia(null);
							$geo_row["persona"] = $valor["persona"];
							$geo_row["direccion"] = $valor["direccion"];
						}
					} else {
						$url = "https://api.tomtom.com/search/2/geocode/" . $valor["calle_url"] . "+" . $persona->getNro() . "+,rio+tercero,Cordoba.json?storeResult=false&view=Unified&key=Tj0CNZcoMipF9sVJ2GKE3LZ907yNogpt";
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_FAILONERROR, true);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$response = curl_exec($ch);
						$error = curl_error($ch);
						$arr_obj_json = json_decode($response);

						if (!empty($arr_obj_json->results)
							&& (!is_null($arr_obj_json->results[0]->position->lat) 
								|| !is_null($arr_obj_json->features[0]->position->lon))) {
							$point = "POINT(" . $arr_obj_json->results[0]->position->lat . ", " . $arr_obj_json->results[0]->position->lon . ")";
							$valor["persona"]->setGeoreferencia($point);
							$valor["persona"]->update_geo();
						} else {
							$valor["persona"]->setGeoreferencia(null);
							$geo_row["persona"] = $valor["persona"];
							$geo_row["direccion"] = $valor["direccion"];
						}
					}
					curl_close($ch);
					$georefencias_json[] = $geo_row;
					$geo_row = null;
				}
			}
			curl_multi_close($multi_request_ch);
			$json_body["domicilios"] = $domicilios_json;
			$json_body["georeferencias"] = $georefencias_json;
			echo json_encode($json_body);
		} else {
			$mensaje = "El metodo es incorrecto";
			header( 'HTTP/1.1 400 BAD REQUEST');
			echo $mensaje;
		}
		$con->CloseConexion();
		ob_end_flush();
	} catch(Exception $e) {
		$con->CloseConexion();
		ob_end_flush();
		echo "Error Message: " . $e;
  	}
