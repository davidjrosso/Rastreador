<?php 
session_start();
require_once 'Conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Movimiento.php';
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
	$Fecha = implode("-", array_reverse(explode("/",$_REQUEST["Fecha"])));
}

$Arr_ID_Responsable = $_REQUEST["ID_Responsable"];

$ID_Responsable_2 = (isset($Arr_ID_Responsable[1])) ? $Arr_ID_Responsable[1] : 64;
$ID_Responsable_3 = (isset($Arr_ID_Responsable[2])) ? $Arr_ID_Responsable[2] : 64;
$ID_Responsable_4 = (isset($Arr_ID_Responsable[3])) ? $Arr_ID_Responsable[3] : 64;

$ID_Persona = $_REQUEST["ID_Persona"];
$ID_Motivo_1 = $_REQUEST["ID_Motivo_1"];
$ID_Motivo_2 = $_REQUEST["ID_Motivo_2"];
$ID_Motivo_3 = $_REQUEST["ID_Motivo_3"];
$ID_Motivo_4 = (isset($_REQUEST["ID_Motivo_4"])) ? $_REQUEST["ID_Motivo_4"]:0;
$ID_Motivo_5 = (isset($_REQUEST["ID_Motivo_5"])) ? $_REQUEST["ID_Motivo_5"]:0;
$Observaciones = $_REQUEST["Observaciones"];
$ID_Responsable = $Arr_ID_Responsable[0];
$ID_Centro = (isset($_REQUEST["ID_Centro"])) ? $_REQUEST["ID_Centro"]:0;
$ID_OtraInstitucion = (isset($_REQUEST["ID_OtraInstitucion"])) ? $_REQUEST["ID_OtraInstitucion"]:0;
$Estado = 1;

if($ID_Motivo_1 == 0){
	$ID_Motivo_1 = 1;
}
if($ID_Motivo_2 == 0){
	$ID_Motivo_2 = 1;
}
if($ID_Motivo_3 == 0){
	$ID_Motivo_3 = 1;
}

if($ID_Motivo_4 == 0){
	$ID_Motivo_4 = 1;
}
if($ID_Motivo_5 == 0){
	$ID_Motivo_5 = 1;
}


if(empty($ID_Responsable[0])){
	$ID_Responsable = 64;
}else{
	$_SESSION["UltResponsable"] = $ID_Responsable[0];
}

if(empty($ID_Centro)){
	$ID_Centro = 7;
}else{
	$_SESSION["UltCentro"] = $ID_Centro;
}

if(empty($ID_OtraInstitucion)){
	$ID_OtraInstitucion = 1;
}else{
	$_SESSION["UltOtraInstitucion"] = $ID_OtraInstitucion;
}


$Fecha_Accion = date("Y-m-d");
$ID_TipoAccion = 1;

$Con = new Conexion();
$Con->OpenConexion();
$Movimiento = new Movimiento(
				coneccion_base: $Con,
						xFecha: $Fecha,
				Fecha_Creacion: $Fecha_Accion,
				   xID_Persona: $ID_Persona,
				  xID_Motivo_1: $ID_Motivo_1,
				  xID_Motivo_2: $ID_Motivo_2,
				  xID_Motivo_3: $ID_Motivo_3,
				  xID_Motivo_4: $ID_Motivo_4,
				  xID_Motivo_5: $ID_Motivo_5,
				xObservaciones: $Observaciones,
			   xID_Responsable: $ID_Responsable,
			 xID_Responsable_2: $ID_Responsable_2,
			 xID_Responsable_3: $ID_Responsable_3,
			 xID_Responsable_4: $ID_Responsable_4,
					xID_Centro: $ID_Centro,
		   xID_OtraInstitucion: $ID_OtraInstitucion,
					   xEstado: $Estado
);

$detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Movimiento. Datos: Fecha: $Fecha_Accion - Persona: $ID_Persona - Motivo 1: $ID_Motivo_1 - Motivo 2: $ID_Motivo_2 - Motivo 3: $ID_Motivo_3 - Observaciones: $Observaciones - Responsable: $ID_Responsable - Centro Salud: $ID_Centro - Otra Institución: $ID_OtraInstitucion";

try {
	$accion = new Accion(
		xaccountid: $ID_Usuario,
		xFecha: $Fecha,
		xDetalles: $detalles,
		xID_TipoAccion: $ID_TipoAccion
	);
	$accion->save();

} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}

$Con->CloseConexion();

$Mensaje = "El Movimiento se ha cargado correctamente";

header('Location: ../view_newmovimientos.php?Mensaje='.$Mensaje);
?>