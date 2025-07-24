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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Responsable.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");


$id_usuario = $_SESSION["Usuario"];
$id_responsable_unif = $_REQUEST["ID_Responsable_unif"];
$id_responsable_del = $_REQUEST["ID_Responsable_del"];

$fecha = date("Y-m-d");
$ID_TipoAccion = 2;

try {
	$con = new Conexion();
	$con->OpenConexion();

    if (!empty($id_responsable_unif) && !empty($id_responsable_del)) {
        $con = new Conexion();
        $con->OpenConexion();

        $existe_responsable_unif = Responsable::existe_id_responsable(
                                                    coneccion_base: $con,
                                                    id_responsable: $id_responsable_unif
                                                   );
        $existe_responsable_del = Responsable::existe_id_responsable(
                                                    coneccion_base: $con,
                                                    id_responsable: $id_responsable_del
                                                   );

        if(!$existe_responsable_unif || !$existe_responsable_del){
            $con->CloseConexion();
            $mensaje = "No existen el responsable a unificar";
            header('Location: ../view_unifresponsables.php?MensajeError=' . $mensaje);
        } else {
            $solicitud_unificacion = new Solicitud_Unificacion(
                coneccion: $con,
                xID_Usuario : $id_usuario,
                xID_Registro_1 : $id_responsable_unif,
                xTipoUnif: 7,
                xID_Registro_2 : $id_responsable_del,
                xFecha: $fecha
            );
            $solicitud_unificacion->save();
        
            $con->CloseConexion();
            $mensaje = "La solicitud de unificacion de responsable se envió a los administradores para ser confirmada.";
            header('Location: ../view_unifresponsables.php?Mensaje=' . $mensaje);
        }

    } else {
        $mensaje_error = "Debe seleccionar una responsable";
        header('Location: ../view_unifresponsables.php?MensajeError=' . $mensaje_error);
    }

} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}