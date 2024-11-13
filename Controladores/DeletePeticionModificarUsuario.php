<?php 
session_start();
require_once 'Conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . 'Modelo/Accion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . 'Modelo/Solicitud_Usuario.php';


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

$ID_Peticion = $_REQUEST["ID"];

$Fecha = date("Y-m-d");
$ID_TipoAccion = 2;
$Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Peticion. Datos: Peticion: $ID_Peticion";

try {
	$solicitud = new Solicitud_Usuario(
		id_solicitud: $ID_Peticion
	);
	$solicitud->delete();
	$accion = new Accion(
		xaccountid: $ID_Usuario,
		xFecha: $Fecha,
		xDetalles: $Detalles,
		xID_TipoAccion: $ID_TipoAccion
	);
	$accion->save();
	$Mensaje = "La solicitud fue eliminada Correctamente";
	header('Location: ../view_solicitud.php?Mensaje='.$Mensaje);
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
?>