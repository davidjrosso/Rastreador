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

$Codigo = (isset($_REQUEST["Codigo"]))?strtoupper($_REQUEST["Codigo"]):null;
$Categoria = (isset($_REQUEST["Categoria"]))?$_REQUEST["Categoria"]:null;
$ID_Forma = $_REQUEST["ID_Forma"];
$ID = (isset($_REQUEST["ID"]))?$_REQUEST["ID"]:null;
$Color = (isset($_REQUEST["CodigoColor"]))?$_REQUEST["CodigoColor"]:null;

$Fecha = date("Y-m-d");

try {
	$Con = new Conexion();
	$Con->OpenConexion();
	if($Color != null && $Color != ""){
		$ConsultarRegistrosIguales = "select * from solicitudes_crearcategorias where ID = '$ID' and estado = 1";
		if(!$RetIguales = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
			throw new Exception("Problemas al consultar registros. Consulta: ".$ConsultarRegistrosIguales, 0);		
		}

		$ActualizarColor = "update solicitudes_crearcategorias set color = '$Color' where id = $ID and estado = 1";
		$MensajeErrorActualizarColor = "No se pudo actualizar el color";
		mysqli_query($Con->Conexion,$ActualizarColor) or die($MensajeErrorActualizarColor);

		$DetalleNot = 'Se ha creado una nueva categoría: '.$Categoria.' , codigo: '.$Codigo;
		$Expira = date("Y-m-d", strtotime($Fecha." + 30 days"));

		$ConsultaNot = "insert into notificaciones(Detalle, Fecha, Expira, Estado) values('$DetalleNot','$Fecha', '$Expira',1)";
		if(!$RetNot = mysqli_query($Con->Conexion,$ConsultaNot)){
			throw new Exception("Error al intentar registrar Notificacion. Consulta: ".$ConsultaNot, 3);
		}

		$Mensaje = "La solicitud de creacion de categoria se envió a los administradores para ser confirmada.";
		header('Location: ../view_newcategorias.php?Mensaje='.$Mensaje);
	} else {
		$Insert_Solicitud = "insert into solicitudes_crearcategorias(Fecha,Codigo,Categoria,ID_Forma,Color,Estado,ID_Usuario) values('{$Fecha}','{$Codigo}','{$Categoria}',{$ID_Forma},'{$Color}',1,{$ID_Usuario})";
		$MensajeError = "No se pudo enviar la solicitud";

		mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError." ".$Solicitud);

		$ConsultarID = "select id from solicitudes_crearcategorias where codigo = '$Codigo' and categoria = '$Categoria' limit 1";
		if(!$RetID = mysqli_query($Con->Conexion,$ConsultarID)){
			throw new Exception("No se pudo consultar el ID de la categoria cargada. Consulta: ".$ConsultarID, 2);		
		}
		$Ret = mysqli_fetch_assoc($RetID);
		$Mensaje = "La solicitud de creacion de categoria se envió a los administradores para ser confirmada.";	
		header('Location: ../view_colorcategoria.php?ID='.$Ret["id"].'&ID_Forma='.$ID_Forma);
		$Con->CloseConexion();
	}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}