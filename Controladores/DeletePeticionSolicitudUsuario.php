<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Solicitud_Usuario.php';
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

$id_usuario = $_SESSION["Usuario"];

$id_peticion = $_REQUEST["ID"];

$fecha = date("Y-m-d");
$id_tipo_accion = 3;
$detalles = "El usuario con ID: $id_usuario ha dado de baja una Peticion. Datos: Peticion: $id_peticion";

try {
	$Con = new Conexion();
	$Con->OpenConexion();
    $usuario = new Solicitud_Usuario(id_solicitud: $id_peticion);
    $usuario->delete();

    $accion = new Accion(
        xaccountid: $id_usuario,
        xFecha: $fecha,
        xDetalles: $detalles,
        xID_TipoAccion: $id_tipo_accion
    );
    $accion->save();

	$Con->CloseConexion();
	$Mensaje = "La solicitud fue eliminada Correctamente";
	header('Location: ../view_inicio.php?Mensaje=' . $Mensaje);
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}
?>