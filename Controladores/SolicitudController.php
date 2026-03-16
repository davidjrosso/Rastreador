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
}