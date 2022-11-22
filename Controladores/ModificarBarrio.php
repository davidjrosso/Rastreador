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

$ID_Barrio = $_REQUEST["ID"];
$Barrio = ucwords($_REQUEST["Barrio"]);

$Fecha = date("Y-m-d");
$ID_TipoAccion = 2;

$Con = new Conexion();
$Con->OpenConexion();

try {
	$ConsultarRegistrosIguales = "select * from barrios where Barrio = '$Barrio' and ID_Barrio != $ID_Barrio and estado = 1";
	if(!$Ret = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
		throw new Exception("Error al consultar registros. Consulta: ".$ConsultarRegistrosIguales, 0);		
	}
	$Resultado = mysqli_num_rows($Ret);
	if($Resultado > 0){
		$Con->CloseConexion();
		$Mensaje = "Ya existe un Barrio con ese Nombre";
		header('Location: ../view_modbarrios.php?ID='.$ID_Barrio.'&MensajeError='.$Mensaje);
	}else{
		$ConsultarDatosViejos = "select * from barrios where ID_Barrio = $ID_Barrio and estado = 1";
		$ErrorDatosViejos = "No se pudieron consultar los datos";
		if(!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)){
			throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
		}		
		$TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
		$BarrioViejo = $TomarDatosViejos["Barrio"];
		

		$Consulta = "update barrios set Barrio = '$Barrio' where ID_Barrio = $ID_Barrio and estado = 1";
		if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
			throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarRegistrosIguales, 2);
		}

		$Detalles = "El usuario con ID: $ID_Usuario ha modificado un Barrio. Datos: Dato Anterior: $BarrioViejo , Dato Nuevo: $Barrio";
		$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
		if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
			throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
		}
		$Con->CloseConexion();
		$Mensaje = "El Barrio se modificó Correctamente";
		header('Location: ../view_modbarrios.php?ID='.$ID_Barrio.'&Mensaje='.$Mensaje);
	}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}

?>