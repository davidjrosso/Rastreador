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

require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Categoria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/CategoriaRol.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");


class ReporteListadoController 
{

    public function filtro_movimientos()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            header("Content-Type: text/html;charset=utf-8");

            $ID_Usuario = $_SESSION["Usuario"];
            $account = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $account->get_id_tipo_usuario();

            $datosNav = (isset($_SESSION["datosNav"])) ? $_SESSION["datosNav"]: [];
            $Element = new Elements();

            include("view_listados.php");
        }
        exit();
    }

    public function reporte()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            header("Content-Type: text/html;charset=utf-8");

            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $_SESSION["reporte_listado"] = true;
            $_SESSION["reporte_grafico"] = false;
            $ID_Config = $_REQUEST["ID_Config"];
            $Element = new Elements();

            include("view_vermovlistados.php");
        }
        exit();
    }
}