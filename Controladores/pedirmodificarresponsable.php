<?php
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
session_start(); 
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Responsable.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/SolicitudModificacion.php");


$id_usuario = $_SESSION["Usuario"];

$id_responsable = $_REQUEST["ID"];
$responsable_nombre = trim($_REQUEST["Responsable"]);

$fecha = date("Y-m-d");
$estado = 1;

if($id_responsable > 0){
	$con = new Conexion();
	$con->OpenConexion();


    $existe_nombre = Responsable::is_registered_name_with_id_responsable(
                                                                         coneccion_base: $con,
                                                                         nombre: $responsable_nombre,
                                                                         id_responsable: $id_responsable
                                                                        );
    $existe_responsable = Responsable::existe_id_responsable(
                                                             coneccion_base: $con, 
                                                             id_responsable: $id_responsable
                                                            );
	if($existe_nombre){
		$con->CloseConexion();
		$mensaje = "Ya existe un Responsable con ese Nombre";
		header('Location: ../view_modresponsables.php?ID=' . $id_responsable.'&MensajeError=' . $mensaje);
    } else if ($existe_responsable) {
        $solicitud_modificacion = new SolicitudModificacion(
            coneccion_base: $con,
            id_usuario: $id_usuario,
            id_registro: $id_responsable,
            id_tipo: 1,
            valor: $responsable_nombre,
            fecha: $fecha
        );
        $solicitud_modificacion->save();
    
        $con->CloseConexion();
        $mensaje = "La solicitud de modificacion de responsable se envió a los administradores para ser confirmada.";
        header('Location: ../view_responsables.php?Mensaje=' . $mensaje);
    }



} else {
	$mensaje_error = "Debe seleccionar una Responsable";
	header('Location: ../view_modresponsables.php?ID=' . $ID . '&MensajeError=' . $mensaje_error);
}

?>