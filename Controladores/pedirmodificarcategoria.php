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

	$Solicitud = new Solicitud_ModificarCategoria(0,$Fecha,$Codigo,$Categoria,$ID_Forma,$NuevoColor,$Estado,$ID_Usuario);
	$Insert_Solicitud = "insert into solicitudes_modificarcategorias(Fecha,Codigo,Categoria,ID_Forma,NuevoColor,Estado,ID_Usuario) values('{$Solicitud->getFecha()}','{$Solicitud->getCodigo()}','{$Solicitud->getCategoria()}',{$Solicitud->getID_Forma()},'{$Solicitud->getNuevoColor()}',{$Solicitud->getEstado()},{$Solicitud->getID_Usuario()})";
	$MensajeError = "No se pudo enviar la solicitud";

	mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError);

	$Con->CloseConexion();
	$Mensaje = "La solicitud de modificación se envió a los administradores para ser confirmada.";
	header('Location: ../view_categorias.php?Mensaje='.$Mensaje);
}else{
	$MensajeError = "Debe seleccionar una Categoria";
	header('Location: ../view_modcategorias.php?ID='.$ID_Categoria.'&MensajeError='.$MensajeError);
}

?>