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
			$domicilios_json = [];
			$georefencias_json = [];
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
								$lista_fecha = explode("/", $value);
								$lista_fecha = array_reverse($lista_fecha);
								$value = implode( "-", $lista_fecha);
								$fecha_excel = strtotime($value);
								$Fecha_Nacimiento  = date(format: 'Y-m-d',timestamp: $fecha_excel);
								break;
							case 3:
								$dni = $value;
								break;
							case 4:
								$value = (($value) ? $value : "sin datos");
								$observacion .= " apellido y nombre de madre : " . $value;
								break;
							case 5:
								$direccion = $value;
								break;
							case 6:
								$telefono = $value;
								break;
							case 7:
								$obra_social = $value;
								break;
							default :
								$result_array = null;
								$motivo = "";
								$fecha_movimiento = preg_match(
													"/([1-9][0-9]|[1-9]).([1-9][0-9]|[1-9]).[2-9][0-9]/",
																$value,
																$result_array
																);
								$movimientos_motivo = null;
								if (!empty($result_array[0])) {
									$lista_fecha = explode("-", $result_array[0]);
									$lista_fecha = array_reverse($lista_fecha);
									$val_fecha = implode( "-", $lista_fecha);
									$fecha_excel = strtotime($val_fecha);
									$fecha_movimiento  = date(format: 'Y-m-d',timestamp: $fecha_excel);
									$result_array = null;
									$motivo = preg_match(
												"/[aA-zZ]+/",
												$value,
												$result_array
												);
									if (empty($result_array[0])) {
										$movimientos_motivo["motivo"] = null;
										$movimientos_motivo["fecha"] = null;
									} else {
										$movimientos_motivo["motivo"] = codigoExcelMotivo($result_array[0]);
										$movimientos_motivo["fecha"] = $fecha_movimiento;
									}
									$lista_motivos[] = $movimientos_motivo;
								}
								break;
						}
					}
				} else if ($seccion == "C. INDICE PEDIATRIA") {
					for ($col = 0; $col <= $highestColumnIndex; $col++) {
						$value = (!empty($result->values[$row][$col])) ? $result->values[$row][$col] : null;
						switch ($col) {
							case 0:
								$lista = explode(" ", $value);
								$apellido = preg_replace("~,~", "", $lista[0]);
								$nombre = implode( " ",array_slice($lista, 1));
								break;
							case 1:
								$hc = $value;
								break;
							case 2:
								$lista_fecha = explode("/", $value);
								$lista_fecha = array_reverse($lista_fecha);
								$value = implode( "-", $lista_fecha);
								$fecha_excel = strtotime($value);
								$Fecha_Nacimiento  = date(format: 'Y-m-d',timestamp: $fecha_excel);
								break;
							case 3:
								$dni = $value;
								break;
							case 4:
								$observacion .= " apellido y nombre de madre : " . $value;
								break;
							case 5:
								$direccion = $value;
								break;
							case 6:
								$barrio = $value;
								$id_barrio = Barrio::get_id_by_name($con, "Castagnino");
								if(!empty($barrio)) {
									$id_barrio = Barrio::get_id_by_name($con, $barrio);
								}
								break;
							case 7:
								$departam = $value;
								$is_departament = preg_match(
									"~[0-9]+~",
									$departam,
								   $result_array
											);
								$departam = ($is_departament) ? $result_array[0] : null;
								break;
							case 8:
								$telefono = $value;
								break;
							case 9:
								$obra_social = $value;
								break;
							case 10:
								$observacion .= " atencion : " . $value;
								break;
							case 11:
								$observacion .= " dx " . $value;
								break;
							case 12:
								$observacion .= " vacunas : " . $value;
								break;
							case 13:
								$observacion .= " - " . $value;
								break;
							case 14:
								$observacion .= " - " . $value;
								break;
							case 15:
								$observacion .= " - " . $value;
								break;
							default :
								$valor_fecha = (!empty($result->values[1][$col])) ? $result->values[1][$col] : null;
								//$pattern = "/([0-9][0-9]|[1-9]).([0-9][0-9]|[1-9]).[2-9][0-9][0-9][0-9]/";
								$pattern = "/([0-9][0-9]).([0-9][0-9]).[2-9][0-9][0-9][0-9]/";
								$is_fecha = preg_match(
											  $pattern,
											  $valor_fecha,
											 $result_array
													  );
								if ($col >= 33 && $is_fecha) {
									$motivo_row["fecha"] = null;
									if (!empty($result_array[0])) {
										$lista_fecha = explode("/", $result_array[0]);
										$lista_fecha = array_reverse($lista_fecha);
										$valor_fecha = implode( "-", $lista_fecha);
										$fecha_excel = strtotime($valor_fecha);
										$fecha_movimiento  = date(format: 'Y-m-d',timestamp: $fecha_excel);
										$motivo_row["fecha"] = $fecha_movimiento;
									}
									$motivo_row["motivo"] = codigoExcelMotivo(trim($value));
									$lista_motivos[] = $motivo_row;
								}
								break;
						}
					}
				} else if ($seccion == "11 Años") {
					$responsable_nombre = "DELLAROSSA Mónica. ENFERMERA.";
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
							case 3:
								$lista_fecha = explode("/", $value);
								$lista_fecha = array_reverse($lista_fecha);
								$value = implode( "-", $lista_fecha);
								$fecha_excel = strtotime($value);
								$Fecha_Nacimiento  = date(format: 'Y-m-d', timestamp: $fecha_excel);
							case 4:
								$motivo = Motivo::get_id_by_name($con, $value);
								break;
							default :
								$observacion .= " - " . $value;
								break;
						}
					}
				}
				$consulta_osm = true;
				foreach ($lista_motivos as $key => $value) {
					if (!$value["motivo"] || !$value["fecha"]) {
						$id_persona = (empty($dni)) ? null : Persona::get_id_persona_by_dni($con,
																							$dni
																							);
						if (!is_null($id_persona) && is_numeric($id_persona)) {
							$persona = new Persona(ID_Persona: $id_persona);
							$georeferencia = $persona->getGeoreferencia();
							$modificacion = $persona->setCalleNro($direccion);
							$persona->setFamilia($departam);
							$calle = $persona->getNombre_Calle();
							if (($persona->getId_Calle() && !$modificacion)) {
								$calle_url = str_replace(" ", "+", $persona->getNombre_Calle());
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
					$estado = 1;
					$ID_TipoAccion = 1;
	
					if (!Responsable::is_registered(coneccion_base: $con, 
													nombre: $responsable_nombre
						)) {
						$responsable = new Responsable(
							coneccion_base: $con,
							responsable: $responsable,
							estado: $estado
						);
						$responsable->save();
					} else {
						$id_responsable = Responsable::get_id_responsable_by_name(
																   coneccion_base: $con,
																	  responsable: $responsable_nombre
						);
						if (is_null($id_responsable)) {
							continue;
						}
						$responsable = new Responsable(
										coneccion_base: $con,
										 id_responsable: $id_responsable
						);
					}

					$row_json["responsable"] = $responsable->jsonSerialize();
					$detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo responsable. Datos: responsable: " . $responsable->get_responsable();
					$accion = new Accion(
						xaccountid: $ID_Usuario,
						xFecha: $Fecha,
						xDetalles: $detalles,
						xID_TipoAccion: $ID_TipoAccion
					);
					$accion->save();

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
						$persona->setFamilia($departam);
						if ($persona->getId_Calle()) {
							$calle_url = str_replace(" ", "+", $persona->getNombre_Calle());
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
							$persona->setFamilia($departam);
							$persona->setBarrio($id_barrio);
							$calle = $persona->getNombre_Calle();
							if ($persona->getId_Calle()
								&& !$modificacion) {
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
						$id_movimiento = $movimiento->getID_Movimiento();
						$motivo_mov = MovimientoMotivo::exist_movimiento_motivo(
																   connection: $con,
																   movimiento: $id_movimiento,
																       motivo: $ID_Motivo_1
																			  );
						if ($motivo_mov) {
							continue;
						}
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
				}

			}

			$time_send = Parametria::get_value_by_code($con, 'TIME_SEND');
			$cant_request = count($request);
			$count = 0;
			$num_send = 0;
			$row = [];
			$geo_row = null;
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
						$json_progress["progreso"] = $num_send/$cant_request;
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
						}
					} else if ($arr_obj_json &&  $valor["server"] == 2) {
						if (!is_null($arr_obj_json->results[0]->lat) || !is_null($arr_obj_json->features[0]->lon)) {
							$point = "POINT(" . $arr_obj_json->results[0]->lat . ", " . $arr_obj_json->results[0]->lon . ")";
							$valor["persona"]->setGeoreferencia($point);
							$valor["persona"]->update_geo();
						} else {
							$valor["persona"]->setGeoreferencia(null);
							$geo_row["persona"] = $valor["persona"];
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
