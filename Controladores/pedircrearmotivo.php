<?php
session_start(); 
require_once 'Conexion.php';
require_once '../Modelo/Solicitud_ModificarMotivo.php';
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
$Codigo = strtoupper($_REQUEST["Codigo"]);
$ID_Categoria = $_REQUEST["ID_Categoria"];
$Num_Motivo = 0;

$Fecha = date("Y-m-d");
$Estado = 1;

if($ID_Categoria > 0){
	$Con = new Conexion();
	$Con->OpenConexion();

	$ConsultarCod_Categoria = "select cod_categoria from categoria where id_categoria = $ID_Categoria";
	if(!$RetCod = mysqli_query($Con->Conexion,$ConsultarCod_Categoria)){
		throw new Exception("Problemas al consultar cod_categoria. Consulta: ".$ConsultarCod_Categoria, 1);			
	}
	$TomarCod = mysqli_fetch_assoc($RetCod);
	$Cod_Categoria = $TomarCod["cod_categoria"];

	$Insert_Solicitud = "insert into solicitudes_crearmotivos(Fecha,Motivo,Codigo,Cod_Categoria,Num_Motivo,Estado,ID_Usuario) 
						 values('{$Fecha}','{$Motivo}','{$Codigo}','{$Cod_Categoria}',{$Num_Motivo},{$Estado},{$ID_Usuario})";
	$MensajeError = "No se pudo enviar la solicitud";
	mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError);

	$DetalleNot = "Se ha creado un nuevo motivo: ".$Motivo." , codigo: ".$Codigo."";
	$Expira = date("Y-m-d", strtotime($Fecha." + 30 days"));

	$ConsultaNot = "insert into notificaciones(Detalle, Fecha, Expira, Estado) values('".$DetalleNot."','".$Fecha."', '".$Expira."',1)";
	if(!$RetNot = mysqli_query($Con->Conexion,$ConsultaNot)){
		throw new Exception("Error al intentar registrar Notificacion. Consulta: ".$ConsultaNot, 3);
	}

	$Con->CloseConexion();
	$Mensaje = "La solicitud de creación de motivo se envió a los administradores para ser confirmada.";
	header('Location: ../view_motivos.php?Mensaje='.$Mensaje);
}else{
	$MensajeError = "Debe seleccionar una Categoria";
	header('Location: ../view_modmotivos.php?ID='.$ID_Motivo.'&MensajeError='.$MensajeError);
}

?>