<?php 
session_start();
require_once 'Conexion.php';
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
$ID_TipoAccion = 3;
$Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Peticion. Datos: Peticion: $ID_Peticion";

try {
	$Con = new Conexion();
	$Con->OpenConexion();

	$Consulta = "update solicitudes_eliminarmotivos set Estado = 0 where ID = $ID_Peticion";
	if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
		throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 0);		
	}
	$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
	if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
		throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 1);
	}	
	$Con->CloseConexion();
	$Mensaje = "La solicitud fue eliminada Correctamente";
	header('Location: ../view_inicio.php?Mensaje='.$Mensaje);
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
?>