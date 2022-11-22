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

$Responsable = ucfirst($_REQUEST["Responsable"]);
$Estado = 1;

$Fecha = date("Y-m-d");
$ID_TipoAccion = 1;
$Detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Responsable. Datos: $Responsable";

$Con = new Conexion();
$Con->OpenConexion();

try {
	$ConsultarResponsablesIguales = "select * from responsable where responsable = '$Responsable' and estado = 1";
	if(!$Ret = mysqli_query($Con->Conexion,$ConsultarResponsablesIguales)){
		throw new Exception("Error al consultar registros. Consulta: ".$ConsultarResponsablesIguales, 0);		
	}
	$Resultado = mysqli_num_rows($Ret);
	if($Resultado > 0){
		$Con->CloseConexion();
		$Mensaje = "Ya existe un Responsable con ese Nombre";
		header('Location: ../view_newresponsables.php?MensajeError='.$Mensaje);
	}else{
		$Consulta = "insert into responsable(responsable,estado) values('$Responsable',$Estado)";
		if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
			throw new Exception("Error al intentar registrar. Consulta: ".$Consulta, 1);
		}	
		$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
		if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
			throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 2);
		}
		$Con->CloseConexion();
		$Mensaje = "El Responsable se registro Correctamente";
		header('Location: ../view_newresponsables.php?Mensaje='.$Mensaje);
	}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
?>