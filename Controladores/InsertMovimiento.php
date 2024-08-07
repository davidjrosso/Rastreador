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
$Detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Movimiento. Datos: Fecha: $Fecha_Accion - Persona: $ID_Persona - Motivo 1: $ID_Motivo_1 - Motivo 2: $ID_Motivo_2 - Motivo 3: $ID_Motivo_3 - Observaciones: $Observaciones - Responsable: $ID_Responsable - Centro Salud: $ID_Centro - Otra Institución: $ID_OtraInstitucion";

$Movimiento = new Movimiento(0,$Fecha,$Fecha_Accion,$ID_Persona,$ID_Motivo_1,$ID_Motivo_2,$ID_Motivo_3,$ID_Motivo_4,$ID_Motivo_5,$Observaciones,$ID_Responsable,$ID_Responsable_2,$ID_Responsable_3,$ID_Responsable_4,$ID_Centro,$ID_OtraInstitucion,$Estado);
$Con = new Conexion();
$Con->OpenConexion();

$Consulta = "insert into movimiento(fecha,fecha_creacion,id_persona,motivo_1,motivo_2,motivo_3,motivo_4,motivo_5,observaciones,id_resp,id_resp_2,id_resp_3,id_resp_4,id_centro,id_otrainstitucion,estado) 
			 values('".$Movimiento->getFecha()."','".$Movimiento->getFecha_Creacion()."',".$Movimiento->getID_Persona().",".$Movimiento->getID_Motivo_1().",".$Movimiento->getID_Motivo_2().",".$Movimiento->getID_Motivo_3().",".$Movimiento->getID_Motivo_4().",".$Movimiento->getID_Motivo_5().",'".$Movimiento->getObservaciones()."',".$Movimiento->getID_Responsable().",".$Movimiento->getID_Responsable_2().",".$Movimiento->getID_Responsable_3().",".$Movimiento->getID_Responsable_4().",".$Movimiento->getID_Centro().",".$Movimiento->getID_OtraInstitucion().",".$Movimiento->getEstado().")";

$Ret = mysqli_query($Con->Conexion,$Consulta)or die("Problemas en la consulta"." - ".$Consulta);

try {
	$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
	if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
		throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
	}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}

$Con->CloseConexion();

$Mensaje = "El Movimiento se ha cargado correctamente";

header('Location: ../view_newmovimientos.php?Mensaje='.$Mensaje);
?>