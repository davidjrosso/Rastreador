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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_CrearCategoria.php");

class SolicitudController 
{

    public function listado_solicitud($mensaje = null, $id_filtro = null, $filtro = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {

            $id_usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $id_usuario);
            $tipo_usuario = $usuario->get_id_tipo_usuario();

            $Element = new Elements();
            $dt_general = new CtrGeneral();

            $Filtro = null;
            $ID_Filtro = null;
            if (isset($_REQUEST["Filtro"])) $Filtro = $_REQUEST["Filtro"];
            if (isset($_REQUEST["ID_Filtro"])) $ID_Filtro = $_REQUEST["ID_Filtro"];

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_solicitud.php");
        }
        exit();
    }

    public function listado_solicitud_control($filtro_nombre = null, $filtro_id = null)
    {
        $filtro = $_REQUEST["filtro"];
        $id_filtro = $_REQUEST["ID_Filtro"];
        header("Location: /solicitud?filtro=" . $filtro_nombre . "&ID_Filtro=" . $filtro_id);
        exit();
    }

    public function del_new_categoria()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Peticion = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Peticion. Datos: Peticion: $ID_Peticion";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $cat = new Solicitud_CrearCategoria(coneccion_base: $Con , xID: $ID_Peticion);
            $cat->delete();
            $accion = new Accion(xaccountid: $ID_Usuario,
                                 xFecha: $Fecha,
                                 xDetalles: $Detalles, 
                                 xID_TipoAccion: $ID_TipoAccion);
            $accion->save();
            $Con->CloseConexion();
            $Mensaje = "La solicitud fue eliminada Correctamente";
            header('Location: ../home?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function del_new_m()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Peticion = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Peticion. Datos: Peticion: $ID_Peticion";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $cat = new Solicitud_CrearMotivo(xID: $ID_Peticion);
            $cat->delete();
            $accion = new Accion(xaccountid: $ID_Usuario,
                                 xFecha: $Fecha,
                                 xDetalles: $Detalles, 
                                 xID_TipoAccion: $ID_TipoAccion);
            $accion->save();
            $Con->CloseConexion();
            $Mensaje = "La solicitud fue eliminada Correctamente";
            header('Location: ../home?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function del_su_categoria()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Peticion = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Peticion. Datos: Peticion: $ID_Peticion";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $cat = new Solicitud_EliminarCategoria(xID: $ID_Peticion);
            $cat->delete();
            $accion = new Accion(xaccountid: $ID_Usuario,
                                 xFecha: $Fecha,
                                 xDetalles: $Detalles, 
                                 xID_TipoAccion: $ID_TipoAccion);
            $accion->save();
            $Con->CloseConexion();
            $Mensaje = "La solicitud fue eliminada Correctamente";
            header('Location: ../home?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function del_mod_categoria()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Peticion = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Peticion. Datos: Peticion: $ID_Peticion";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $cat = new Solicitud_ModificarCategoria(xID: $ID_Peticion);
            $cat->delete();
            $accion = new Accion(xaccountid: $ID_Usuario,
                                 xFecha: $Fecha,
                                 xDetalles: $Detalles, 
                                 xID_TipoAccion: $ID_TipoAccion);
            $accion->save();
            $Con->CloseConexion();
            $Mensaje = "La solicitud fue eliminada Correctamente";
            header('Location: ../home?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function del_mod_motivo()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Peticion = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Peticion. Datos: Peticion: $ID_Peticion";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $cat = new Solicitud_ModificarMotivo(xID: $ID_Peticion);
            $cat->delete();
            $accion = new Accion(xaccountid: $ID_Usuario,
                                 xFecha: $Fecha,
                                 xDetalles: $Detalles, 
                                 xID_TipoAccion: $ID_TipoAccion);
            $accion->save();
            $Con->CloseConexion();
            $Mensaje = "La solicitud fue eliminada Correctamente";
            header('Location: ../home?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function del_su_m()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Peticion = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Peticion. Datos: Peticion: $ID_Peticion";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $cat = new Solicitud_EliminarMotivo(xID: $ID_Peticion);
            $cat->delete();
            $accion = new Accion(xaccountid: $ID_Usuario,
                                 xFecha: $Fecha,
                                 xDetalles: $Detalles, 
                                 xID_TipoAccion: $ID_TipoAccion);
            $accion->save();
            $Con->CloseConexion();
            $Mensaje = "La solicitud fue eliminada Correctamente";
            header('Location: ../home?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }    

    public function delete_modificacion()
    {
        $id_usuario = $_SESSION["Usuario"];

        $id_solicitud = $_REQUEST["ID"];

        $fecha = date("Y-m-d");
        $id_tipo_accion = 3;
        $detalles = "El usuario con ID: $id_usuario ha dado de baja una Peticion. Datos: Peticion: $id_solicitud";

        try {
            $con = new Conexion();
            $con->OpenConexion();

            $solicitud = new SolicitudModificacion(
                                                    coneccion_base: $con,
                                                    id_solicitud: $id_solicitud
                                                );
            $solicitud->delete();

            $accion = new Accion(
                xaccountid: $id_usuario,
                xFecha: $fecha,
                xDetalles: $detalles,
                xID_TipoAccion: $id_tipo_accion
            );
            $accion->save();

            $con->CloseConexion();
            $Mensaje = "La solicitud fue eliminada Correctamente";
            header('Location: ../view_inicio.php?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function delete_mod_usuario()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Peticion = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 2;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Peticion. Datos: Peticion: $ID_Peticion";

        try {
            $solicitud = new Solicitud_Usuario(
                id_solicitud: $ID_Peticion
            );
            $solicitud->delete();
            $accion = new Accion(
                xaccountid: $ID_Usuario,
                xFecha: $Fecha,
                xDetalles: $Detalles,
                xID_TipoAccion: $ID_TipoAccion
            );
            $accion->save();
            $Mensaje = "La solicitud fue eliminada Correctamente";
            header('Location: ../view_solicitud.php?Mensaje='.$Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function del_unif()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Peticion = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Peticion. Datos: Peticion: $ID_Peticion";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $cat = new Solicitud_Unificacion(coneccion: $Con, xID_Solicitud: $ID_Peticion);
            $cat->delete();
            $accion = new Accion(xaccountid: $ID_Usuario,
                                 xFecha: $Fecha,
                                 xDetalles: $Detalles, 
                                 xID_TipoAccion: $ID_TipoAccion);
            $accion->save();
            $Con->CloseConexion();
            $Mensaje = "La solicitud fue eliminada Correctamente";
            header('Location: ../home?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function del_notificacion()
    {
        $ID = $_REQUEST["ID"];

        $ID_Usuario = $_SESSION["Usuario"];
        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Notificacion. Datos: Notificacion: $ID";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $rev = new Notificacion(coneccion_base: $Con, id_notificacion: $ID);
            $rev->delete();

            $accion = new Accion(
                xaccountid: $ID_Usuario,
                xFecha : $Fecha,
                xDetalles: $Detalles,
                xID_TipoAccion: $ID_TipoAccion	 
            );
            $accion->save();

            $Con->CloseConexion();
            $Mensaje = "La notificación fue eliminada correctamente";
            header('Location: /home?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}