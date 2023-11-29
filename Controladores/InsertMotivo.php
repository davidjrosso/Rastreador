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
$ID_Categoria = $_REQUEST["ID_Categoria"];
$Estado = 1;

$Fecha = date("Y-m-d");
$ID_TipoAccion = 1;
$Detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Motivo. Datos: Motivo: $Motivo - Categoría : $ID_Categoria";

try {
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
		$Mensaje = "Ya hay un Motivo con ese Dato";
		header('Location: ../view_newmotivos.php?MensajeError='.$Mensaje);
	}else{
		$ConsultarCod_Categoria = "select cod_categoria from categoria where id_categoria = $ID_Categoria";
		if(!$RetCod = mysqli_query($Con->Conexion, $ConsultarCod_Categoria)){
			throw new Exception("No se pudo consultar el código de la categoría seleccionada. Consulta: ".$ConsultarCod_Categoria, 1);			
		}
		$TomarCod_Categoria = mysqli_fetch_assoc($RetCod);
		$Cod_Categoria = $TomarCod_Categoria["cod_categoria"];

		$Consulta = "insert into motivo(motivo,codigo,cod_categoria,estado) values('".$Motivo."','".$Codigo."','".$Cod_Categoria."',$Estado)";

		if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
			throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);			
		}
		$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
		if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
			throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
		}
		$Mensaje = "El Motivo se registro Correctamente";

		// CREANDO NOTIFICACION PARA EL USUARIO
		$DetalleNot = 'Se ha creado un nuevo motivo: '.$Motivo.' , código: '.$Codigo;
		$Expira = date("Y-m-d", strtotime($Fecha." + 3 days"));
		
		$ConsultaNot = "insert into notificaciones(Detalle, Fecha, Expira, Estado) values('$DetalleNot','$Fecha', '$Expira',1)";
		if(!$RetNot = mysqli_query($Con->Conexion,$ConsultaNot)){
			throw new Exception("Error al intentar registrar Notificacion. Consulta: ".$ConsultaNot, 3);
		}

		$Con->CloseConexion();
		header('Location: ../view_newmotivos.php?Mensaje='.$Mensaje);
	}

} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
?>