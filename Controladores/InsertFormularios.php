<?php 
	session_start();
	header('Content-Type: application/json'); 
	require_once 'Conexion.php';
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
			$client = new Google_Client();
			$client->useApplicationDefaultCredentials();
			$client->addScope(['https://www.googleapis.com/auth/drive.readonly']);
			$service = new Google_Service_Drive($client);
			$driveService = new Drive($client);
			$fileId = "1lUM5ZS8WBuN29IdL09cKoGZPuLoK2Dlh";

			$file = $service->files->get($fileId, ['alt' => 'media']);
			$fileContent = $file->getBody()->getContents();
			$file_name = "archivo_temporal_formulario.xlsx";
			$fd = fopen($file_name,"w+");
			$cont = fwrite($fd, $fileContent);
			$cont = fclose($fd);

			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($file_name);
			$worksheet = $spreadsheet->getActiveSheet();

			$highestRow = $worksheet->getHighestDataRow();
			$highestColumn = $worksheet->getHighestDataColumn();
			$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

			$observacion = "";

			for ($row = 1; $row <= $highestRow; ++$row) {
				for ($col = 1; $col <= $highestColumnIndex; ++$col) {
					$value = $worksheet->getCell([$col, $row])->getValue();
					switch ($col) {
						case 1:
							$Fecha_Accion = $value;
							break;
						case 2:
							$email = $value;
							break;
						case 3:
							$responsable = $value;
							break;
						case 4:
							$apellido_nombre = $value;
							break;
						case 5:
							$dni = $value;
							break;
						case 6:
							$Fecha_Nacimiento = $value;
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
							break;
						default :
							break;
					}
				}
			}

			$ID_Usuario = 100;
			$Fecha =  date("Y-m-d");
			$ID_Motivo_1 = 1;
			$ID_Motivo_2 = 2;
			$estado = 1;
			$ID_TipoAccion = 1;

			$con = new Conexion();
			$con->OpenConexion();

			$responsable = new Responsable(
				coneccion_base: $con,
				responsable: $responsable,
				estado: $estado
			);

			$detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo responsable. Datos: responsable: " . $responsable->get_id_responsable();
			$accion = new Accion(
				xaccountid: $ID_Usuario,
				xFecha: $Fecha,
				xDetalles: $detalles,
				xID_TipoAccion: $ID_TipoAccion
			);
			$accion->save();

			$persona = new Persona(
				xApellido : $apellido_nombre,
				xBarrio :  Barrio::get_id_by_name($con, $barrio),
				xDNI : $dni,
				xEstado : $estado,
				xFecha_Nacimiento: $Fecha_Nacimiento,
				xTelefono : $telefono,
				xMail:$email
			);
			$persona->setDomicilio($direccion);
			$persona->save();

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
					  xID_Motivo_2: $ID_Motivo_2,
					xObservaciones: $observacion,
				   xID_Responsable: $responsable->get_id_responsable(),
						   xEstado: $estado
					);
			$movimiento->save();

			$detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Movimiento. Datos: Fecha: $Fecha_Accion - Persona: " . $persona->getID_Persona() . " - Motivo 1: $ID_Motivo_1 - Motivo 2: $ID_Motivo_2 - Observaciones: $observacion - Responsable: " . $responsable->get_id_responsable();
			$accion = new Accion(
				xaccountid: $ID_Usuario,
				xFecha: $Fecha,
				xDetalles: $detalles,
				xID_TipoAccion: $ID_TipoAccion
			);
			$accion->save();

			$con->CloseConexion();
			$Mensaje = "El/Los formularios se ha cargado correctamente";
			echo $Mensaje;
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