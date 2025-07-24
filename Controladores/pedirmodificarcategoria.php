<?php
session_start(); 
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/SolicitudPermiso.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_ModificarCategoria.php");

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

if ($ID_Categoria > 0) {
	$con = new Conexion();
	$con->OpenConexion();

	$solicitud = new Solicitud_ModificarCategoria(
												  coneccion_base: $con,
												  xFecha: $Fecha,
												  xCodigo: $Codigo,
												  xCategoria: $Categoria,
												  xID_Forma: $ID_Forma,
												  xNuevoColor: $NuevoColor,
												  xEstado: $Estado,
												  xID_Usuario: $ID_Usuario,
												  xID_Categoria: $ID_Categoria
												);
    $solicitud->save();
	$id_solicitud = $solicitud->getID();
	foreach ($Grupo_Usuarios as $key => $value) {
		$solicitud_permiso = new SolicitudPermiso(
												  coneccion_base: $con,
												  id: $id_solicitud,
												  id_tipo_usuario: $value,
												  fecha: $Fecha
												 );
		$solicitud_permiso->save();
	}

	$con->CloseConexion();
	$Mensaje = "La solicitud de modificación se envió a los administradores para ser confirmada.";
	header('Location: ../view_categorias.php?Mensaje=' . $Mensaje);
} else {
	$MensajeError = "Debe seleccionar una Categoria";
	header('Location: ../view_modcategorias.php?ID=' . $ID_Categoria . '&MensajeError=' . $MensajeError);
}
