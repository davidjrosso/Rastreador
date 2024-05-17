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

$Motivo = $_REQUEST["Motivo"];
$Codigo = $_REQUEST["Codigo"];
$ID = $_REQUEST["ID"];
$Cod_Categoria = $_REQUEST["Cod_Categoria"];
$Estado = 1;

$Fecha = date("Y-m-d");
$ID_TipoAccion = 1;
$Detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Motivo. Datos: Motivo: $Motivo - Categoría : $ID_Categoria";

try	 {
	$Con = new Conexion();
	$Con->OpenConexion();

	$ConsultarRegistrosIguales = "select * from motivo where motivo = '$Motivo' and estado = 1";

	if(!$RetIguales = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
		throw new Exception("Problemas al consultar Registros Iguales. Consulta: ".$ConsultarRegistrosIguales, 0);
	}
	$Registros = mysqli_num_rows($RetIguales);
	if($Registros > 0){
		mysqli_free_result($RetIguales);
		$Con->CloseConexion();
		$Mensaje = "Ya hay un Motivo con los datos ingresados";
		header('Location: ../view_inicio.php?MensajeError='.$Mensaje);
	}else{
		$Consulta = "insert into motivo(motivo,codigo,cod_categoria,estado) values('".$Motivo."','".$Codigo."','".$Cod_Categoria."',1)";

		if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
			throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);			
		}

		$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
		if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
			throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
		}

		$ActualizarSolicitud = "update solicitudes_crearmotivos set Estado = 0 where id = '$ID'";
		$EjecutarConsultar = mysqli_query($Con->Conexion,$ActualizarSolicitud) or die($MensajeErrorDatos);

		$Mensaje = "El Motivo se registro Correctamente";
		$Con->CloseConexion();
		header('Location: ../view_inicio.php?Mensaje='.$Mensaje);
	}

} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
?>