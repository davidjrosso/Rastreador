<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Movimiento.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/MovimientoMotivo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/MovimientoObservacion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/TipoObservacion.php';
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/MovimientoResponsable.php");

require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Accion.php';

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

$ID_Usuario = $_SESSION["Usuario"];

if(empty($_REQUEST["Fecha"])){
	$Fecha =  date("Y-m-d");
}else{
	$Fecha = implode("-", array_reverse(explode("/", $_REQUEST["Fecha"])));
}
$ID_Persona = $_REQUEST["ID_Persona"];

$Arr_ID_Responsable = $_REQUEST["ID_Responsable"];

$ID_Responsable_1 = (!empty($Arr_ID_Responsable[0])) ? $Arr_ID_Responsable[0] : 64;
$ID_Responsable_2 = (isset($Arr_ID_Responsable[1])) ? $Arr_ID_Responsable[1] : 64;
$ID_Responsable_3 = (isset($Arr_ID_Responsable[2])) ? $Arr_ID_Responsable[2] : 64;
$ID_Responsable_4 = (isset($Arr_ID_Responsable[3])) ? $Arr_ID_Responsable[3] : 64;

$observacion_general = $_REQUEST["observacion-general"];
$observacion_medicina = $_REQUEST["observacion-medicina"];

$ID_Centro = (!empty($_REQUEST["ID_Centro"])) ? $_REQUEST["ID_Centro"] : 7;
$ID_OtraInstitucion = (!empty($_REQUEST["ID_OtraInstitucion"])) ? $_REQUEST["ID_OtraInstitucion"] : 1;
$Estado = 1;

$Con = new Conexion();
$Con->OpenConexion();

$id_tipo_observacion = TipoObservacion::exist_tipo_observacion_con_descripcion(
																coneccion: $Con,
																descripcion: "observacion_medicina"
																);

$ID_Motivo[] = (!empty($_REQUEST["ID_Motivo_1"])) ? $_REQUEST["ID_Motivo_1"] : 1;
$ID_Motivo[] = (!empty($_REQUEST["ID_Motivo_2"])) ? $_REQUEST["ID_Motivo_2"] : 1;
$ID_Motivo[] = (!empty($_REQUEST["ID_Motivo_3"])) ? $_REQUEST["ID_Motivo_3"] : 1;
$ID_Motivo[] = (!empty($_REQUEST["ID_Motivo_4"])) ? $_REQUEST["ID_Motivo_4"] : 1;
$ID_Motivo[] = (!empty($_REQUEST["ID_Motivo_5"])) ? $_REQUEST["ID_Motivo_5"] : 1;

if ($ID_Responsable_1 != 64) $_SESSION["UltResponsable"] = $ID_Responsable_1;
if($ID_Centro != 7) $_SESSION["UltCentro"] = $ID_Centro;

if($ID_OtraInstitucion > 1) $_SESSION["UltOtraInstitucion"] = $ID_OtraInstitucion;

$Fecha_Accion = date("Y-m-d");
$ID_TipoAccion = 1;

try {
	$movimiento = new Movimiento(
					coneccion_base: $Con,
							xFecha: $Fecha,
					Fecha_Creacion: $Fecha_Accion,
					xID_Persona: $ID_Persona,
					xID_Motivo_1: $ID_Motivo[0],
					xID_Motivo_2: $ID_Motivo[1],
					xID_Motivo_3: $ID_Motivo[2],
					xID_Motivo_4: $ID_Motivo[3],
					xID_Motivo_5: $ID_Motivo[4],
					xObservaciones: $observacion_general,
				xID_Responsable: $ID_Responsable_1,
				xID_Responsable_2: $ID_Responsable_2,
				xID_Responsable_3: $ID_Responsable_3,
				xID_Responsable_4: $ID_Responsable_4,
						xID_Centro: $ID_Centro,
			xID_OtraInstitucion: $ID_OtraInstitucion,
						xEstado: $Estado
	);
	$movimiento->save();
	$id_movimiento = $movimiento->getID_Movimiento();



	foreach ($Arr_ID_Responsable as $id_res) {
		$res = MovimientoResponsable::exist_movimiento_responsable(
			connection: $Con,
			movimiento: $id_movimiento,
			id_responsable: $id_res
		);
		if (!$res && $id_res != 64) {
			$mov = new MovimientoResponsable(
												connection: $Con,
												id_movimiento: $id_movimiento,
												id_responsable: $id_res
												);
			$mov->save();
		}

	}

	$mensaje_motivo = "";
	foreach($ID_Motivo as $id) {
		$motivo = MovimientoMotivo::exist_movimiento_motivo(
			connection: $Con,
			movimiento: $id_movimiento,
			motivo: $id
		);
		$mensaje_motivo .= "- $id";
		if (!$motivo && $id > 1) {
			$movimiento_motivo = new MovimientoMotivo(
														connection: $Con,
													id_movimiento: $id_movimiento,
														id_motivo: $id,
															estado: 1
			);
			$movimiento_motivo->save();
		}
			
	}

	if ($id_tipo_observacion && $observacion_medicina) {
		$tipo_observacion = new TipoObservacion(
									coneccion: $Con,
									id_tipo_observacion: $id_tipo_observacion
									);
		$observacion_medicina = new MovimientoObservacion(
											coneccion: $Con,
											movimiento: $movimiento,
											observacion: $observacion_medicina,
											tipo_observacion: $tipo_observacion,
											estado: 1
											);
		$observacion_medicina->save();
	}


	$detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Movimiento. Datos: Fecha: $Fecha_Accion - Persona: $ID_Persona - Motivo 1:" .  $ID_Motivo[0] . " - Motivo 2:" .  $ID_Motivo[1] . " - Motivo 3:" .  $ID_Motivo[2] . " - Responsable:" . $ID_Responsable_1 . " - Centro Salud: $ID_Centro - Otra Institución: $ID_OtraInstitucion";

	$accion = new Accion(
		xaccountid: $ID_Usuario,
		xFecha: $Fecha_Accion,
		xDetalles: $detalles,
		xID_TipoAccion: $ID_TipoAccion
	);
	$accion->save();

} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}

$Con->CloseConexion();

$Mensaje = "El Movimiento se ha cargado correctamente";

header('Location: ../view_newmovimientos.php?Mensaje='.$Mensaje);
