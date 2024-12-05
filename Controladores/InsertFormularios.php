<?php 
	session_start();
	header('Content-Type: application/json'); 
	require_once 'Conexion.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/sys_config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Movimiento.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Formulario.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Persona.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Responsable.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Barrio.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	use Google\Client;
	use Google\Service\Drive;
	use Google\Service\Sheets\SpreadSheet;

    try {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$con = new Conexion();
			$con->OpenConexion();

			$client = new Google_Client();
			$consulta = "select * 
						 from parametrias 
						 where codigo = 'SECRET_KEY' 
						   and estado = 1";
			$ret = mysqli_query($con->Conexion,$consulta);
			$resultado = mysqli_fetch_assoc($ret);
			$private_key = $resultado["valor"];

			if(!$ret){
				throw new Exception("Problemas al consultar el sercret key. Consulta: " . $consultar, 0);
			}

			$client->setAuthConfig(array("type" => TYPE_ACCOUNT,
										 "client_id" => CLIENT_ID,
										 "client_email" => CLIENT_EMAIL,
										 "private_key" => $private_key, 
										 "signing_algorithm" => "HS256"));

			$client->addScope([Google_Service_Drive::DRIVE_READONLY]);
			$client->addScope([Google_Service_Sheets::SPREADSHEETS]);
			$service_sheets = new Google_Service_Sheets($client);
			$range = 'A1:S';
			$result = $service_sheets->spreadsheets_values->get(FILE_ID, $range);
			$con = new Conexion();
			$con->OpenConexion();
			$Fecha =  date("Y-m-d");
			$observacion = "";
			$response_json = [];
			$row_json = [];
			$highestColumnIndex = 19;
			$highestRow = 4;
			for ($row = 1; $row <= $highestRow; $row++) {
				for ($col = 0; $col <= $highestColumnIndex; $col++) {
					$value = (!empty($result->values[$row][$col])) ? $result->values[$row][$col] : null;
					switch ($col) {
						case 0:
							if (!is_null($value)) {
								//$fecha_excel = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
								//$Fecha_Accion = $fecha_excel->format("Y-m-d");
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
							$apellido_nombre = $value;
							break;
						case 4:
							$apellido_nombre = $value;
							break;
						case 5:
							$dni = $value;
							break;
						case 6:
							//$fecha_excel = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
							//$Fecha_Nacimiento = $fecha_excel->format("Y-m-d");
							$fecha_excel = strtotime($value);
							$Fecha_Nacimiento = date(format: 'Y-m-d',timestamp: $fecha_excel);
							break;
						case 7:
							$direccion = $value;
							break;
						case 8:
							$localidad = $value;
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
						case 19:
							$barrio = $value;
							if (!is_null($barrio)) {
								$id_barrio = Barrio::get_id_by_name($con, $barrio);
							} else {
								$id_barrio = null;
							}
							break;
						default :
							break;
					}
				}

				$ID_Usuario = 100;
				$ID_Motivo_1 = 100;
				$ID_Motivo_2 = 101;
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
						xApellido : $apellido_nombre,
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
				$movimiento = new Movimiento(
						coneccion_base: $con, 
								xFecha: $Fecha_Accion,
						Fecha_Creacion: $Fecha_Accion,
						   xID_Persona: $persona->getID_Persona(),
						  xID_Motivo_1: $ID_Motivo_1,
						xObservaciones: $observacion,
					   xID_Responsable: $responsable->get_id_responsable(),
							xID_Centro: 7,
				   xID_OtraInstitucion: 1,
							   xEstado: $estado
						);
				if ($internacion) {
					$movimiento->setID_Motivo_2($ID_Motivo_2);
				}
				$movimiento->save();
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