<?php
session_start(); 
require_once 'Conexion.php';
require_once '../Modelo/Solicitud_EliminarCategoria.php';
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

$ID_Categoria= $_REQUEST["ID"];
$Fecha = date("Y-m-d");
$Estado = 1;

$Con = new Conexion();
$Con->OpenConexion();

$ConsultarCategoria = "select id_categoria, categoria, cod_categoria from categoria where id_categoria = $ID_Categoria";

if(!$RetCategoria = mysqli_query($Con->Conexion,$ConsultarCategoria)){
	throw new Exception("Problemas al consultar datos de categoria. Consulta: ".$ConsultarCategoria, 1);			
}
$TomarCategoria = mysqli_fetch_assoc($RetCategoria);
$Categoria = $TomarCategoria['categoria'];
$Cod_Categoria = $TomarCategoria["cod_categoria"];

$Solicitud = new Solicitud_EliminarCategoria(0,$Fecha,$Categoria,$Cod_Categoria,$Estado,$ID_Usuario,$ID_Categoria);
$Insert_Solicitud = "insert into solicitudes_eliminarcategorias(Fecha,Categoria,Cod_Categoria,Estado,ID_Usuario,ID_Categoria) values('{$Solicitud->getFecha()}','{$Solicitud->getCategoria()}','{$Solicitud->getCod_Categoria()}',{$Solicitud->getEstado()},{$Solicitud->getID_Usuario()},{$Solicitud->getID_Categoria()})";
$MensajeError = "No se pudo enviar la solicitud";

mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError." - ".$Insert_Solicitud);

$Con->CloseConexion();
$Mensaje = "La solicitud de eliminación se envió a los administradores para ser confirmada.";
header('Location: ../view_categorias.php?Mensaje='.$Mensaje);
?>