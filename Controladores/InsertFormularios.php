<?php 
	session_start();
	header('Content-Type: application/json'); 
	require_once 'Conexion.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/sys_config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Movimiento.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Formulario.php';	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Parametria.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Persona.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Responsable.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Barrio.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Motivo.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Parametria.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/MovimientoMotivo.php';

	use Google\Client;
	use Google\Service\Drive;
	use Google\Service\Sheets\SpreadSheet;

    try {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$con = new Conexion();
			$con->OpenConexion();
			$private_key = Parametria::get_value_by_code($con, 'SECRET_KEY');
			$file_id = Parametria::get_value_by_code($con, "FILE_DENGUE");

			$client = new Google_Client();
			$client->setAuthConfig(array("type" => TYPE_ACCOUNT,
										 "client_id" => CLIENT_ID,
										 "client_email" => CLIENT_EMAIL,
										 "private_key" => $private_key, 
										 "signing_algorithm" => "HS256"));

			$client->addScope([Google_Service_Drive::DRIVE_READONLY]);
			$client->addScope([Google_Service_Sheets::SPREADSHEETS]);
			$service_sheets = new Google_Service_Sheets($client);
			$range = 'A1:P';
			$result = $service_sheets->spreadsheets_values->get($file_id, $range);

			$Fecha =  date("Y-m-d");
			$observacion = "";
			$response_json = [];
			$row_json = [];
			$highestColumnIndex = count($result->values[0]);
			$highestRow = count($result->values) - 1;
			for ($row = 1; $row <= $highestRow; $row++) {
				$observacion = "";
				for ($col = 0; $col <= $highestColumnIndex; $col++) {
					$value = (!empty($result->values[$row][$col])) ? $result->values[$row][$col] : null;
					switch ($col) {
						case 0:
							if (!is_null($value)) {
								$value = str_replace("/", "-", $value);
								$fecha_excel = strtotime($value);
								$Fecha_Accion = date(format: 'Y-m-d',timestamp: $fecha_excel);
							} else {
								$Fecha_Accion = $Fecha;
							}
							break;
						case 1:
							$email = $value;
							break;
						case 2:
							$responsable = $value;
							break;
						case 3:
							$nombre = $value;
							break;
						case 4:
							$apellido = $value;
							break;
						case 5:
							$dni = $value;
							break;
						case 6:
							$value = str_replace("/", "-", $value);
							$fecha_excel = strtotime($value);
							$Fecha_Nacimiento = date(format: 'Y-m-d',timestamp: $fecha_excel);
							break;
						case 7:
							$direccion = $value;
							break;
						/*
						case 8:
							$localidad = $value;
							break;
						*/
						case 8:
							$barrio = $value;
							if (!is_null($barrio)) {
								$barrio = trim($barrio);
								$id_barrio = Barrio::get_id_by_name($con, $barrio);
								
							} else {
								$id_barrio = null;
							}
							break;
						case 9:
							$telefono = $value;
							break;
						case 10:
							$observacion .= " Fecha inicio de los Sintomas : " . $value;
							break;
						case 11:
							$internacion = (($value == "INTERNACION") ? true : false);
							$observacion .= " INTERNACION/ AMBULATORIO : " . $value;
							break;
						case 12:
							$observacion .= " El paciente fue vacunado para dengue? : " . $value;
							break;
						case 13:
							$observacion .= " Si fue vacunado, indique la fecha 1era Dosis : " . $value;
							break;
						case 14:
							$observacion .= " Si fue vacunado, indique la fecha 2da Dosis : " . $value;
							break;
						case 15:
							$observacion .= " Antígeno AgNS1 : " . $value;
							break;
						case 16:
							$observacion .= " Anticuerpos IgM para Dengue : " . $value;
							break;
						case 17:
							$observacion .= " Anticuerpos IgG para Dengue : " . $value;
							break;
						case 18:
							$observacion .= " PCR para dengue : " . $value;
							break;
						default :
							break;
					}
				}
				$ID_Usuario = 100;
				$ID_Motivo_1 = Motivo::get_id_by_codigo($con, "DEN");
				$ID_Motivo_2 = Motivo::get_id_by_codigo($con, "DEIN");
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
				if (!Persona::is_registered($dni)) {
					$persona = new Persona(
						xApellido : $apellido,
						xNombre : $nombre,
						xBarrio :  $id_barrio,
						xDNI : $dni,
						xEstado : $estado,
						xFecha_Nacimiento: $Fecha_Nacimiento,
						xTelefono : $telefono,
						xMail:$email,
						xID_Escuela: 2
					);
					$persona->setNro($direccion);
					$persona->setDomicilio($direccion);
					$persona->save();
				} else {
					$id_persona = Persona::get_id_persona_by_dni($dni);
					if (is_null($id_persona)) {
						continue;
					}
					$persona = new Persona(ID_Persona: $id_persona);
					$persona->setBarrio($id_barrio);
					$persona->setNro($direccion);
					$persona->setDomicilio($direccion);
					$persona->update();
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

				$formulario_cargado = Formulario::exist(
														coneccion: $con, 
														persona: $persona->getID_Persona(),
														responsable: $responsable->get_id_responsable()
														);
				if ($formulario_cargado) {
					continue;
				}

				$movimiento = new Movimiento(
						coneccion_base: $con, 
								xFecha: $Fecha_Accion,
						Fecha_Creacion: $Fecha_Accion,
						   xID_Persona: $persona->getID_Persona(),
						  xID_Motivo_1: $ID_Motivo_1,
						  xID_Motivo_3: $ID_Motivo_3,
						  xID_Motivo_4: $ID_Motivo_4,
						  xID_Motivo_5: $ID_Motivo_5,
						xObservaciones: $observacion,
					   xID_Responsable: $responsable->get_id_responsable(),
							xID_Centro: 7,
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
				if ($internacion) {
					$movimiento->setID_Motivo_2($ID_Motivo_2);
					$motivo_movimiento = new MovimientoMotivo(
													connection: $con, 
												 id_movimiento: $movimiento->getID_Movimiento(),
													 id_motivo: $ID_Motivo_1,
													nro_motivo: 1,
														estado: 1
															);
					$motivo_movimiento->save();
				} else {
					$ID_Motivo_2 = Motivo::get_id_by_name( $con, "Sin Motivo");
					$movimiento->setID_Motivo_2($ID_Motivo_2);
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
				$response_json[$row - 1]["formulario"] = $row_json;
				$response_json[$row - 1]["estado"] = 1;

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