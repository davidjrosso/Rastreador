<?php 
	session_start();
	header('Content-Type: application/json'); 
	require_once 'Conexion.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/sys_config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Movimiento.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/MovimientoMotivo.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Archivo.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Formulario.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Parametria.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Persona.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Responsable.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Barrio.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Motivo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/CentroSalud.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	use Google\Client;
	use Google\Service\Sheets\SpreadSheet;

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
			$client = new Google_Client();
			$client->setAuthConfig(array("type" => TYPE_ACCOUNT,
										 "client_id" => CLIENT_ID,
										 "client_email" => CLIENT_EMAIL,
										 "private_key" => $private_key, 
										 "signing_algorithm" => "HS256"));

			$client->addScope([Google_Service_Drive::DRIVE_READONLY]);
			$client->addScope([Google_Service_Sheets::SPREADSHEETS]);
			$service_sheets = new Google_Service_Sheets($client);

			$id_file = $archivo->get_id_file();
			$planilla = $archivo->get_planilla();
			if (($planilla == "11 Años") || ($planilla == "EMBARAZADAS")) {
				$fila = '!A3:';
				$com = 0;
			} else if ($planilla == "C. INDICE PEDIATRIA") {
				$fila = '!A2:';
				$com = 1;
			} else {
				$fila = '!A4:';
				$com = 0;
			}

			if ($planilla == "11 Años") {
				$col = 'E';
			} else if ($planilla == "C. INDICE PEDIATRIA") {
				$col = 'AH';
			} else if ($planilla == "C. INDICE ENFERMERIA") {
				$col = 'U';
			} else {
				$col = 'K';
			}

			$range = $planilla . $fila . $col;
			$result = $service_sheets->spreadsheets_values->get($id_file, $range);
			$highestRow = count($result->values) - 1;

			$Fecha =  date("Y-m-d");
			$Fecha_Accion = date("Y-m-d");
			$observacion = "";
			$lista_motivos = null;
			$response_json = [];
			$row_json = [];

			$responsable = "WOLYNIEC Jorge - Area Local";

			for ($row = $com; $row <= $highestRow; $row++) {
				$observacion = "";
				if (count($result->values[$row]) == 0) {
					continue;
				}
				$highestColumnIndex = count($result->values[$row]) - 1;
				if ($planilla == "EMBARAZADAS") {
					$responsable = "WOLYNIEC Jorge - Area Local";
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
				} else if ($planilla == "C. INDICE ENFERMERIA") {
					$responsable = "DELLAROSSA Mónica. ENFERMERA.";
					$lista_motivos = null;
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
				} else if ($planilla == "C. INDICE PEDIATRIA") {
					$responsable = "Constanza Bertone";
					$value = (!empty($result->values[0][33])) ? $result->values[0][33] : null;
					$fecha_movimiento = preg_match(
						"/([0-9][0-9]|[1-9]).([0-9][0-9]|[1-9]).[2-9][0-9][0-9][0-9]/",
								   $value,
								   $result_array
								  );
					$lista_motivos[0]["fecha"] = null;
					if (!empty($result_array[0])) {
						$lista_fecha = explode("/", $result_array[0]);
						$lista_fecha = array_reverse($lista_fecha);
						$val_fecha = implode( "-", $lista_fecha);
						$fecha_excel = strtotime($val_fecha);
						$fecha_movimiento  = date(format: 'Y-m-d',timestamp: $fecha_excel);
						$lista_motivos[0]["fecha"] = $fecha_movimiento;
					}
					$lista_motivos[0]["motivo"] = null;
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
								$telefono = $value;
								break;
							case 8:
								$obra_social = $value;
								break;
							case 9:
								$observacion .= " atencion : " . $value;
								break;
							case 10:
								$observacion .= " dx " . $value;
								break;
							case 11:
								$observacion .= " vacunas : " . $value;
								break;
							case 12:
								$observacion .= " - " . $value;
								break;
							case 13:
								$observacion .= " - " . $value;
								break;
							case 14:
								$observacion .= " - " . $value;
								break;
							case 33:
								$lista_motivos[0]["motivo"] = codigoExcelMotivo(trim($value));
								break;
							default :
								$observacion .= " - " . $value;
								break;
						}
					}
				} else if ($planilla == "11 Años") {
					$responsable = "DELLAROSSA Mónica. ENFERMERA.";
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
								$Fecha_Nacimiento  = date(format: 'Y-m-d',timestamp: $fecha_excel);
							case 4:
								$motivo = Motivo::get_id_by_name($con, $value);
								break;
							default :
								$observacion .= " - " . $value;
								break;
						}
					}
				}
				foreach ($lista_motivos as $key => $value) {
					if (!$value["motivo"] || !$value["fecha"]) {
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
													nombre: $responsable
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
																	  responsable: $responsable
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
						$persona->setNro($direccion);
						$persona->setDomicilio($direccion);
						$persona->save();
						$id_persona = $persona->getID_Persona();
					} else {
						$id_persona = Persona::get_id_persona_by_dni($dni);
						if (is_null($id_persona)) {
							continue;
						}
						$persona = new Persona(ID_Persona: $id_persona);
					}
	
					$row_json["persona"] = $persona->jsonSerialize();
					$detalles = "El usuario con ID: $ID_Usuario ha registrado un nueva persona. Datos: Persona: " . $persona->getID_Persona();
					$accion = new Accion(
						xaccountid: $ID_Usuario,
						xFecha: $Fecha,
						xDetalles: $detalles,
						xID_TipoAccion: $ID_TipoAccion
					);
					$accion->save();

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
					$response_json[]["formulario"] = $row_json;
				}

			}
			$con->CloseConexion();

			$mensaje = "El/Los formularios se ha cargado correctamente";
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