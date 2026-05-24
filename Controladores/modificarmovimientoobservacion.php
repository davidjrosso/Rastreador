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
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Movimiento.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Persona.php");

$ID_Usuario = $_SESSION["Usuario"];

$req = json_decode(file_get_contents("php://input"), true);

$ID_Movimiento = $req["ID"];
$data = $req["data"];

$_SERVER;
$con = new Conexion();
$con->OpenConexion();

if (Movimiento::is_exist($con, $ID_Movimiento)) {
	$mv = new Movimiento(
								coneccion_base: $con, 
								xID_Movimiento: $ID_Movimiento
	);

	$mv->setObservaciones($data);
	$mv->udpate();
	
	$fecha_accion = date("Y-m-d");
	$ID_TipoAccion = 2;
	$detalles = "El usuario con ID: $ID_Usuario ha modificado un Movimiento. Datos: id_movimiento: " . $mv->getID_Movimiento();
	$accion = new Accion(
		xaccountid: $ID_Usuario,
		xFecha: $fecha_accion,
		xDetalles: $detalles,
		xID_TipoAccion: $ID_TipoAccion
	);
	$accion->save();

}

$con->CloseConexion();

$Mensaje = "El Movimiento se modifico correctamente";
header('Location: ../view_movimientos.php?Mensaje=' . $Mensaje);
