<?php
session_start(); 
require_once 'Conexion.php';
require_once '../Modelo/Solicitud_ModificarCategoria.php';
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
$Grupo_Usuarios = $_REQUEST["Tipo_Usuario"];
$ID_Categoria = $_REQUEST["ID"];
$Codigo = strtoupper($_REQUEST["Codigo"]);
$Categoria = $_REQUEST["Categoria"];
$ID_Forma = $_REQUEST["ID_Forma"];
$NuevoColor = $_REQUEST["CodigoColor"];

$Fecha = date("Y-m-d");
$Estado = 1;

if($ID_Categoria > 0){
	$Con = new Conexion();
	$Con->OpenConexion();

	$Solicitud = new Solicitud_ModificarCategoria(0,$Fecha,$Codigo,$Categoria,$ID_Forma,$NuevoColor,$Estado,$ID_Usuario,$ID_Categoria);
	$Insert_Solicitud = "insert into solicitudes_modificarcategorias(Fecha,Codigo,Categoria,ID_Forma,NuevoColor,Estado,ID_Usuario,ID_Categoria) values('{$Solicitud->getFecha()}','{$Solicitud->getCodigo()}','{$Solicitud->getCategoria()}',{$Solicitud->getID_Forma()},'{$Solicitud->getNuevoColor()}',{$Solicitud->getEstado()},{$Solicitud->getID_Usuario()},{$Solicitud->getID_Categoria()})";
	$MensajeError = "No se pudo enviar la solicitud";

	mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError." ".$Solicitud);

	$ConsultarID = "select id 
					from solicitudes_modificarcategorias 
					where codigo = '$Codigo' 
					  and categoria = '$Categoria' 
					  and estado = 1 
					limit 1";
	if(!$RetID = mysqli_query($Con->Conexion,$ConsultarID)){
		throw new Exception("No se pudo consultar el ID de la categoría modificada. Consulta: ".$ConsultarID, 2);
	}
	$Ret = mysqli_fetch_array($RetID);

	foreach ($Grupo_Usuarios as $key => $value) {
		$Insert_Solicitud = "insert into solicitudes_permisos(ID, ID_TipoUsuario, Fecha, estado) values('{$Ret["id"]}','{$value}','{$Fecha}', 1)";
		$MensajeError = "No se pudo insertar la solicitud de modificacion de permisos";
		if(!$RetID = mysqli_query($Con->Conexion,$Insert_Solicitud)){
			throw new Exception($MensajeError. " . Consulta :".$Insert_Solicitud, 2);
		}
	}

	$Con->CloseConexion();
	$Mensaje = "La solicitud de modificación se envió a los administradores para ser confirmada.";
	header('Location: ../view_categorias.php?Mensaje='.$Mensaje);
}else{
	$MensajeError = "Debe seleccionar una Categoria";
	header('Location: ../view_modcategorias.php?ID='.$ID_Categoria.'&MensajeError='.$MensajeError);
}

?>