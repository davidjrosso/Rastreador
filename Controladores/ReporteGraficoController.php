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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Motivo.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Categoria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/CategoriaRol.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/sys_config.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/dompdf/autoload.inc.php");


class ReporteGraficoController 
{

    public function filtro_movimientos()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("view_general_new.php");
        }
        exit();
    }

    public function reporte()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {

            $ID_Usuario = $_SESSION["Usuario"];
            $_SESSION["reporte_grafico"] = true;
            session_write_close();


            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();
            $width_dispay = (isset($_REQUEST["width-display"])) ? $_REQUEST["width-display"] : null;


            if (isset($_REQUEST["Fecha_Desde"])) {
            $lista_animacion = explode("/", $_REQUEST["Fecha_Desde"]);
            $Fecha_Inicio = implode("-", array_reverse($lista_animacion));
            $fecha_init_animacion = $Fecha_Inicio;
            $anio_animacion = $lista_animacion[2];
            $mes_animacion = $lista_animacion[1];
            $dia_animacion = $lista_animacion[0];
            } else {
            $Fecha_Inicio = null;
            }
            if (isset($_REQUEST["Fecha_Hasta"])) {
            $Fecha_Fin = implode("-", array_reverse(explode("/", $_REQUEST["Fecha_Hasta"])));
            $fecha_end_animacion = $Fecha_Fin;
            } else {
            $Fecha_Fin = null;
            }

            $Element = new Elements();
            include("view_rep_general_new.php");
        }
        exit();
    }
}