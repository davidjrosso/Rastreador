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
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/MovimientoMotivo.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Persona.php");

$Arr_ID_Responsable = $_REQUEST["ID_Responsable"];

$ID_Usuario = $_SESSION["Usuario"];

$ID_Movimiento = $_REQUEST["ID"];
$Fecha = implode("-", array_reverse(explode("/",$_REQUEST["Fecha"])));
$Fecha_Creacion = null;
$ID_Persona = $_REQUEST["ID_Persona"];
$ID_Motivo_1 = $_REQUEST["ID_Motivo_1"];
$ID_Motivo_2 = (!empty($_REQUEST["ID_Motivo_2"]) ? $_REQUEST["ID_Motivo_2"] : null);
$ID_Motivo_3 = (!empty($_REQUEST["ID_Motivo_3"]) ? $_REQUEST["ID_Motivo_3"] : null);
$ID_Motivo_4 = (!empty($_REQUEST["ID_Motivo_4"]) ? $_REQUEST["ID_Motivo_4"] : null);
$ID_Motivo_5 = (!empty($_REQUEST["ID_Motivo_5"]) ? $_REQUEST["ID_Motivo_5"] : null);
$lista_motivos = array($ID_Motivo_1);
$Observaciones = $_REQUEST["Observaciones"];
$ID_Responsable = $Arr_ID_Responsable[0];
$ID_Centro = $_REQUEST["ID_Centro"];
$ID_OtraInstitucion = $_REQUEST["ID_OtraInstitucion"];
$Estado = 1;
$ID_Responsable_2 = (isset($Arr_ID_Responsable[1])) ? $Arr_ID_Responsable[1] : 'null';
$ID_Responsable_3 = (isset($Arr_ID_Responsable[2])) ? $Arr_ID_Responsable[2] : 'null';
$ID_Responsable_4 = (isset($Arr_ID_Responsable[3])) ? $Arr_ID_Responsable[3] : 'null';

if($ID_Motivo_2 == null){
	$ID_Motivo_2 = 1; 
} else {
	$lista_motivos[] = $ID_Motivo_2;
}
if($ID_Motivo_3 == null){
	$ID_Motivo_3 = 1;
} else {
	$lista_motivos[] = $ID_Motivo_3;
}
if($ID_Motivo_4 == null){
	$ID_Motivo_4 = 1;
} else {
	$lista_motivos[] = $ID_Motivo_4;
}
if($ID_Motivo_5 == null){
	$ID_Motivo_5 = 1;
} else {
	$lista_motivos[] = $ID_Motivo_5;
}

if(empty($ID_Responsable[0])){
	$ID_Responsable = 'null';
}

if(empty($ID_Centro)){
	$ID_Centro = 'null';
}

$con = new Conexion();
$con->OpenConexion();

if (Persona::is_exist($con, $ID_Persona)) {
	$persona = new Persona($ID_Persona);
}

if (Movimiento::is_exist($con, $ID_Movimiento)) {
	$movimiento_sin_modificar = new Movimiento(
								coneccion_base: $con, 
								xID_Movimiento: $ID_Movimiento
	);
	$fecha_previa = $movimiento_sin_modificar->getFecha();
	$id_persona_previa = $movimiento_sin_modificar->getID_Persona();
	$persona = new Persona(ID_Persona: $id_persona_previa);

	$movimiento = new Movimiento(
		coneccion_base: $con, 
		xFecha: $Fecha,
		Fecha_Creacion: $Fecha_Creacion,
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
	$movimiento->setID_Movimiento($ID_Movimiento);
	$movimiento->udpate();

	$consulta = "SELECT * 
				 FROM movimiento_motivo
				 WHERE id_movimiento = $ID_Movimiento
				   AND estado = 1";
	$rs = mysqli_query($con->Conexion,$consulta) or die("Problemas al consultar las acciones.");

	while ($ret = mysqli_fetch_assoc($rs)) {
		if (!in_array($ret["id_motivo"], $lista_motivos)) {
			$movimiento_motivo = new MovimientoMotivo(
													connection: $con,
													id_movimiento: $ID_Movimiento,
													id_motivo: $ret
			);
			$movimiento_motivo->delete();
		}
	}

	foreach ($lista_motivos as $value) {
		$motivo = MovimientoMotivo::exist_movimiento_motivo(
			connection: $con,
			movimiento: $ID_Movimiento,
			motivo: $value
		);
		if (!$motivo) {
			$movimiento_motivo = new MovimientoMotivo(
													connection: $con,
													id_movimiento: $ID_Movimiento,
													id_motivo: $value
			);
			$movimiento_motivo->save();
		}
	}
	
	$fecha_accion = date("Y-m-d");
	$ID_TipoAccion = 2;
	$detalles = "El usuario con ID: $ID_Usuario ha modificado un Movimiento. Datos: id_movimiento: " . $movimiento->getID_Movimiento();
	$accion = new Accion(
		xaccountid: $ID_Usuario,
		xFecha: $Fecha,
		xDetalles: $detalles,
		xID_TipoAccion: $ID_TipoAccion
	);
	$accion->save();

}

$apellido = $persona->getApellido();
$nombre = $persona->getNombre();
$dni = $persona->getDNI();

// CREANDO NOTIFICACION PARA EL USUARIO
$detalle_not = 'Se modifico el movimiento vinculado a : '. $apellido . ', '. $nombre . ' fecha: '. $fecha_previa;
$expira = date("Y-m-d", strtotime($fecha_accion . " + 15 days"));

$consulta_not = "insert into notificaciones(Detalle, Fecha, Expira, Estado) values('$detalle_not','$Fecha', '$expira',1)";
if(!$RetNot = mysqli_query($con->Conexion,$consulta_not)){
	throw new Exception("Error al intentar registrar Notificacion. Consulta: " . $ConsultaNot, 3);
}

$con->CloseConexion();

$Mensaje = "El Movimiento se modifico correctamente";
header('Location: ../view_movimientos.php?Mensaje=' . $Mensaje);
