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

$ID_Solicitud = $_REQUEST["ID"];
$ID_Motivo = $_REQUEST["ID_Motivo"];
$Motivo = $_REQUEST["Motivo"];
$Codigo = $_REQUEST["Codigo"];

$Fecha = date("Y-m-d");
$ID_TipoAccion = 2;

try {
	$Con = new Conexion();
	$Con->OpenConexion();
	$ConsultarRegistrosIguales = "select * from motivo where motivo = '$Motivo' and id_motivo != $ID_Motivo and estado = 1";

	if(!$RetIguales = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
		throw new Exception("Problemas al consultar registros iguales. Consulta: ".$ConsultarRegistrosIguales, 0);		
	}
	$Resultado = mysqli_num_rows($RetIguales);
	if($Resultado > 0){
		mysqli_free_result($RetIguales);
		$Con->CloseConexion();
		$Mensaje = "Ya existe un motivo con ese Dato ingrese otro valor";
		header('Location: ../view_modmotivos.php?ID='.$ID_Motivo.'&MensajeError='.$Mensaje);
	}else{
		$ConsultarDatosViejos = "select * from motivo where id_motivo = $ID_Motivo and estado = 1";
		$ErrorDatosViejos = "No se pudieron consultar los datos";
		if(!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)){
			throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
		}		
		$TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
		$MotivoViejo = $TomarDatosViejos["Motivo"];
		$Cod_Viejo = $TomarDatosViejos["Codigo"];		

		// $ConsultarCod_Categoria = "select cod_categoria from categoria where id_categoria = $ID_Categoria";
		// if(!$RetCod = mysqli_query($Con->Conexion,$ConsultarCod_Categoria)){
		// 	throw new Exception("Problemas al consultar cod_categoria. Consulta: ".$ConsultarCod_Categoria, 1);			
		// }
		// $TomarCod = mysqli_fetch_assoc($RetCod);
		// $Cod_Categoria = $TomarCod["cod_categoria"];

		$Consulta = "update motivo set motivo = '$Motivo', codigo = '$Codigo' where id_motivo = $ID_Motivo and estado = 1";
		if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
			throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);			
		}
		
		$ConsultaSolicitud = "update solicitudes_modificarmotivos set estado = 0 where ID = $ID_Solicitud";
		if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
			throw new Exception("Problemas en la consulta. Consulta: ".$ConsultaSolicitud, 3);			
		}

		$Detalles = "El usuario con ID: $ID_Usuario ha modificado un Motivo. Datos: Dato Anterior: $MotivoViejo , Dato Nuevo: $Motivo - Dato Anterior: $Cod_Viejo , Dato Nuevo: $Codigo";
		$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
		if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
			throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 4);
		}	
		$Con->CloseConexion();
		$Mensaje = "El Motivo se modifico Correctamente";
		header('Location: ../view_inicio.php?ID='.$ID_Motivo.'&Mensaje='.$Mensaje);
	}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
?>