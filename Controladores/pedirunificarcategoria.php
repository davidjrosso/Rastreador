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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Categoria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");


$id_usuario = $_SESSION["Usuario"];
$id_categoria_unif = $_REQUEST["ID_Categoria_unif"];
$id_categoria_del = $_REQUEST["ID_Categoria_del"];

$fecha = date("Y-m-d");
$ID_TipoAccion = 2;

try {
	$con = new Conexion();
	$con->OpenConexion();

    if (!empty($id_categoria_unif) && !empty($id_categoria_del)) {
        $con = new Conexion();
        $con->OpenConexion();

        $existe_categoria_unif = Categoria::exist_categoria(
                                                    connection: $con,
                                                    id_categoria: $id_categoria_unif
                                                   );
        $existe_categoria_del = Categoria::exist_categoria(
                                                    connection: $con,
                                                    id_categoria: $id_categoria_del
                                                   );

        if(!$existe_categoria_unif || !$existe_categoria_del){
            $con->CloseConexion();
            $mensaje = "No existen la categoria a unificar";
            header('Location: ../view_unifcategorias.php?MensajeError=' . $mensaje);
        } else {
            $solicitud_unificacion = new Solicitud_Unificacion(
                coneccion: $con,
                xID_Usuario : $id_usuario,
                xID_Registro_1 : $id_categoria_unif,
                xTipoUnif: 6,
                xID_Registro_2 : $id_categoria_del,
                xFecha: $fecha
            );
            $solicitud_unificacion->save();
        
            $con->CloseConexion();
            $mensaje = "La solicitud de unificacion de categoria se envió a los administradores para ser confirmada.";
            header('Location: ../view_unifcategorias.php?Mensaje=' . $mensaje);
        }

    } else {
        $mensaje_error = "Debe seleccionar una categoria";
        header('Location: ../view_unifcategorias.php?MensajeError=' . $mensaje_error);
    }

} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}