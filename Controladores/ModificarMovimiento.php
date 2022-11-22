<?php 
session_start();
require_once 'Conexion.php';
require_once '../Modelo/Movimiento.php';
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

$ID_Movimiento = $_REQUEST["ID"];
$Fecha = implode("-", array_reverse(explode("/",$_REQUEST["Fecha"])));
$ID_Persona = $_REQUEST["ID_Persona"];
$ID_Motivo_1 = $_REQUEST["ID_Motivo_1"];
$ID_Motivo_2 = $_REQUEST["ID_Motivo_2"];
$ID_Motivo_3 = $_REQUEST["ID_Motivo_3"];
$Observaciones = $_REQUEST["Observaciones"];
$ID_Responsable = $_REQUEST["ID_Responsable"];
$ID_Centro = $_REQUEST["ID_Centro"];
$ID_OtraInstitucion = $_REQUEST["ID_OtraInstitucion"];
$Estado = 1;

if($ID_Motivo_2 == null){
	$ID_Motivo_2 = 1;
}
if($ID_Motivo_3 == null){
	$ID_Motivo_3 = 1;
}

if(empty($ID_Responsable)){
	$ID_Responsable = 'null';
}

if(empty($ID_Centro)){
	$ID_Centro = 'null';
}


$Movimiento = new Movimiento($ID_Movimiento,$Fecha,$ID_Persona,$ID_Motivo_1,$ID_Motivo_2,$ID_Motivo_3,$Observaciones,$ID_Responsable,$ID_Centro,$ID_OtraInstitucion,$Estado);
$Con = new Conexion();
$Con->OpenConexion();

$ConsultarMovimientoViejo = "select * from movimiento where id_movimiento = $ID_Movimiento and estado = 1";
$MensajeErrorConsultarMovimientoViejo = "No se pudieron consultar los datos del movimiento anterior";

$RetMovimientoViejo = mysqli_query($Con->Conexion,$ConsultarMovimientoViejo) or die($MensajeErrorConsultarMovimientoViejo);
$TomarMovimientoViejo = mysqli_fetch_assoc($RetMovimientoViejo);
$ID_Movimiento_Viejo = $TomarMovimientoViejo["id_movimiento"];
$Fecha_Viejo = $TomarMovimientoViejo["fecha"];
$ID_Persona_Viejo = $TomarMovimientoViejo["id_persona"];
$ID_Motivo_1_Viejo = $TomarMovimientoViejo["motivo_1"];
$ID_Motivo_2_Viejo = $TomarMovimientoViejo["motivo_2"];
$ID_Motivo_3_Viejo = $TomarMovimientoViejo["motivo_3"];
$Observaciones_Viejo = $TomarMovimientoViejo["observaciones"];
$ID_Responsable_Viejo = $TomarMovimientoViejo["id_resp"];
$ID_Centro_Viejo = $TomarMovimientoViejo["id_centro"];
$ID_OtraInstitucion_Viejo = $TomarMovimientoViejo["ID_OtraInstitucion"];

$Movimiento_Viejo = new Movimiento($ID_Movimiento_Viejo,$Fecha_Viejo,$ID_Persona_Viejo,$ID_Motivo_1_Viejo,$ID_Motivo_2_Viejo,$ID_Motivo_3_Viejo,$Observaciones_Viejo,$ID_Responsable_Viejo,$ID_Centro_Viejo,$ID_OtraInstitucion_Viejo,$Estado);

$Consulta = "update movimiento set fecha = '{$Movimiento->getFecha()}', id_persona = {$Movimiento->getID_Persona()}, motivo_1 = {$Movimiento->getID_Motivo_1()}, motivo_2 = {$Movimiento->getID_Motivo_2()}, motivo_3 = {$Movimiento->getID_Motivo_3()}, observaciones = '{$Movimiento->getObservaciones()}', id_resp = {$Movimiento->getID_Responsable()}, id_centro = {$Movimiento->getID_Centro()}, id_otrainstitucion = {$Movimiento->getID_OtraInstitucion()} where id_movimiento = {$Movimiento->getID_Movimiento()} and estado = 1";

mysqli_query($Con->Conexion,$Consulta)or die("Problemas en la consulta. Consulta: ".$Consulta);

$FechaAccion = date("Y-m-d");
$ID_TipoAccion = 2;

$Detalles = "El usuario con ID: $ID_Usuario ha modificado un Movimiento. Datos: Dato Anterior: {$Movimiento_Viejo->getFecha()} , Dato Nuevo: {$Movimiento->getFecha()} - Dato Anterior: {$Movimiento_Viejo->getID_Persona()}, Dato Nuevo: {$Movimiento->getID_Persona()} - Dato Anterior: {$Movimiento_Viejo->getID_Motivo_1()}, Dato Nuevo: {$Movimiento->getID_Motivo_1()} - Dato Anterior: {$Movimiento_Viejo->getID_Motivo_2()}, Dato Nuevo: {$Movimiento->getID_Motivo_2()} - Dato Anterior: {$Movimiento_Viejo->getID_Motivo_3()}, Dato Nuvo: {$Movimiento->getID_Motivo_3()} - Dato Anterior: {$Movimiento_Viejo->getObservaciones()}, Dato Nuevo: {$Movimiento->getObservaciones()} - Dato Anterior: {$Movimiento_Viejo->getID_Responsable()}, Dato Nuevo: {$Movimiento->getID_Responsable()} - Dato Anterior: {$Movimiento_Viejo->getID_Centro()}, Dato Nuevo: {$Movimiento->getID_Centro()} - Dato Anterior: {$Movimiento_Viejo->getID_OtraInstitucion()}, Dato Nuevo: {$Movimiento->getID_OtraInstitucion()}";
$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$FechaAccion','$Detalles',$ID_TipoAccion)";
if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
	throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
}


$Con->CloseConexion();

$Mensaje = "El Movimiento se modifico correctamente";
header('Location: ../view_movimientos.php?Mensaje='.$Mensaje);
?>