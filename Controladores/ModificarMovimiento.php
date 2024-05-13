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

 $Arr_ID_Responsable = $_REQUEST["ID_Responsable"];

$ID_Usuario = $_SESSION["Usuario"];

$ID_Movimiento = $_REQUEST["ID"];
$Fecha = implode("-", array_reverse(explode("/",$_REQUEST["Fecha"])));
$Fecha_Creacion = null;
$ID_Persona = $_REQUEST["ID_Persona"];
$ID_Motivo_1 = $_REQUEST["ID_Motivo_1"];
$ID_Motivo_2 = $_REQUEST["ID_Motivo_2"];
$ID_Motivo_3 = $_REQUEST["ID_Motivo_3"];
$ID_Motivo_4 = $_REQUEST["ID_Motivo_4"];
$ID_Motivo_5 = $_REQUEST["ID_Motivo_5"];
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
}
if($ID_Motivo_3 == null){
	$ID_Motivo_3 = 1;
}
if($ID_Motivo_4 == null){
	$ID_Motivo_4 = 1;
}
if($ID_Motivo_5 == null){
	$ID_Motivo_5 = 1;
}

if(empty($ID_Responsable[0])){
	$ID_Responsable = 'null';
}

if(empty($ID_Centro)){
	$ID_Centro = 'null';
}


$Movimiento = new Movimiento($ID_Movimiento,$Fecha,$Fecha_Creacion,$ID_Persona,$ID_Motivo_1,$ID_Motivo_2,$ID_Motivo_3,$ID_Motivo_4,$ID_Motivo_5,$Observaciones,$ID_Responsable,$ID_Responsable_2,$ID_Responsable_3,$ID_Responsable_4,$ID_Centro,$ID_OtraInstitucion,$Estado);
$Con = new Conexion();
$Con->OpenConexion();

$ConsultarMovimientoViejo = "select * 
							 from movimiento 
							 where id_movimiento = $ID_Movimiento 
							   and estado = 1";

$MensajeErrorConsultarMovimientoViejo = "No se pudieron consultar los datos del movimiento anterior";

$RetMovimientoViejo = mysqli_query($Con->Conexion,$ConsultarMovimientoViejo) or die($MensajeErrorConsultarMovimientoViejo);
$TomarMovimientoViejo = mysqli_fetch_assoc($RetMovimientoViejo);
$ID_Movimiento_Viejo = $TomarMovimientoViejo["id_movimiento"];
$Fecha_Viejo = $TomarMovimientoViejo["fecha"];
$Fecha_Creacion_Viejo = $TomarMovimientoViejo["fecha_creacion"];
$ID_Persona_Viejo = $TomarMovimientoViejo["id_persona"];
$ID_Motivo_1_Viejo = $TomarMovimientoViejo["motivo_1"];
$ID_Motivo_2_Viejo = $TomarMovimientoViejo["motivo_2"];
$ID_Motivo_3_Viejo = $TomarMovimientoViejo["motivo_3"];
$ID_Motivo_4_Viejo = $TomarMovimientoViejo["motivo_4"];
$ID_Motivo_5_Viejo = $TomarMovimientoViejo["motivo_5"];
$Observaciones_Viejo = $TomarMovimientoViejo["observaciones"];
$ID_Responsable_Viejo = $TomarMovimientoViejo["id_resp"];
$ID_Responsable_2_Viejo = $TomarMovimientoViejo["id_resp_2"];
$ID_Responsable_3_Viejo = $TomarMovimientoViejo["id_resp_3"];
$ID_Responsable_4_Viejo = $TomarMovimientoViejo["id_resp_4"];
$ID_Centro_Viejo = $TomarMovimientoViejo["id_centro"];
$ID_OtraInstitucion_Viejo = $TomarMovimientoViejo["id_otrainstitucion"];

$Movimiento_Viejo = new Movimiento($ID_Movimiento_Viejo,$Fecha_Viejo,$Fecha_Creacion_Viejo,$ID_Persona_Viejo,$ID_Motivo_1_Viejo,$ID_Motivo_2_Viejo,$ID_Motivo_3_Viejo,$ID_Motivo_4_Viejo,$ID_Motivo_5_Viejo,$Observaciones_Viejo,$ID_Responsable_Viejo,$ID_Responsable_2_Viejo,$ID_Responsable_3_Viejo,$ID_Responsable_4_Viejo,$ID_Centro_Viejo,$ID_OtraInstitucion_Viejo,$Estado);

$Consulta = "update movimiento set fecha = '{$Movimiento->getFecha()}', id_persona = {$Movimiento->getID_Persona()}, motivo_1 = {$Movimiento->getID_Motivo_1()}, motivo_2 = {$Movimiento->getID_Motivo_2()}, motivo_3 = {$Movimiento->getID_Motivo_3()}, motivo_4 = {$Movimiento->getID_Motivo_4()}, motivo_5 = {$Movimiento->getID_Motivo_5()}, observaciones = '{$Movimiento->getObservaciones()}', id_resp = {$Movimiento->getID_Responsable()}, id_resp_2 = {$Movimiento->getID_Responsable_2()}, id_resp_3 = {$Movimiento->getID_Responsable_3()}, id_resp_4 = {$Movimiento->getID_Responsable_4()}, id_centro = {$Movimiento->getID_Centro()}, id_otrainstitucion = {$Movimiento->getID_OtraInstitucion()} where id_movimiento = {$Movimiento->getID_Movimiento()} and estado = 1";
echo $Consulta;
echo var_dump($Movimiento);
mysqli_query($Con->Conexion,$Consulta)or die("Problemas en la consulta. Consulta: ".$Consulta);

$FechaAccion = date("Y-m-d");
$ID_TipoAccion = 2;

$Detalles = "El usuario con ID: $ID_Usuario ha modificado un Movimiento. Datos: Dato Anterior: {$Movimiento_Viejo->getFecha()} , Dato Nuevo: {$Movimiento->getFecha()} - Dato Anterior: {$Movimiento_Viejo->getID_Persona()}, Dato Nuevo: {$Movimiento->getID_Persona()} - Dato Anterior: {$Movimiento_Viejo->getID_Motivo_1()}, Dato Nuevo: {$Movimiento->getID_Motivo_1()} - Dato Anterior: {$Movimiento_Viejo->getID_Motivo_2()}, Dato Nuevo: {$Movimiento->getID_Motivo_2()} - Dato Anterior: {$Movimiento_Viejo->getID_Motivo_3()}, Dato Nuvo: {$Movimiento->getID_Motivo_3()} - Dato Anterior: {$Movimiento_Viejo->getID_Motivo_4()}, Dato Nuvo: {$Movimiento->getID_Motivo_4()} - Dato Anterior: {$Movimiento_Viejo->getID_Motivo_5()}, Dato Nuvo: {$Movimiento->getID_Motivo_5()} - Dato Anterior: {$Movimiento_Viejo->getObservaciones()}, Dato Nuevo: {$Movimiento->getObservaciones()} - Dato Anterior: {$Movimiento_Viejo->getID_Responsable()}, Dato Nuevo: {$Movimiento->getID_Responsable()} - Dato Anterior: {$Movimiento_Viejo->getID_Centro()}, Dato Nuevo: {$Movimiento->getID_Centro()} - Dato Anterior: {$Movimiento_Viejo->getID_OtraInstitucion()}, Dato Nuevo: {$Movimiento->getID_OtraInstitucion()}";
$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$FechaAccion','$Detalles',$ID_TipoAccion)";
if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
	throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
}


$Con->CloseConexion();

$Mensaje = "El Movimiento se modifico correctamente";
header('Location: ../view_movimientos.php?Mensaje='.$Mensaje);
?>