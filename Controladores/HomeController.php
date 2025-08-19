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


require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");


class HomeController 
{

    public function index()
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            $con = new Conexion();
            $con->OpenConexion();
            $fecha_actual = new DateTime(date("Y-m-d"));
            $value = new Parametria(coneccion_base: $con, codigo: "UPDATE_FECHA_PERSONA");
            $fecha_update = new DateTime($value->get_valor());
            if ($fecha_actual > $fecha_update) {
                $consultar_datos_personas = "UPDATE persona p
                                             SET edad = IF(fecha_nac >= CURDATE() , 0, TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE())),
                                                 meses = IF(fecha_nac >= CURDATE(), 0, MOD(TIMESTAMPDIFF(MONTH, fecha_nac, CURDATE()), 12))
                                             WHERE id_persona in (select id_persona
                                                                  from persona 
                                                                  where fecha_nac is not null
                                                                    and fecha_nac <> 'null'
                                                                    and fecha_nac <> ''
                                                                    and estado = 1)";
                $mensaje_error_datos_personas = "No se pudieron consultar los datos de las personas registradas en el sistema";
                $ejecutar_consultar_datos_personas = mysqli_query(
                                    $con->Conexion,
                                    $consultar_datos_personas
                                    ) or die($mensaje_error_datos_personas);
                $value->set_valor(date("Y-m-d"));
                $value->update($con);
            }

            include("view_inicio.php");
        }
        exit();
    }

    public function error_session() 
    {
        if (isset($_SESSION["Usuario"])) {
            include("view_inicio.php");
        }
    }

    public function login() 
    {
        if (isset($_SESSION["Usuario"])) {
            include("view_inicio.php");
            exit();
        }

        include("view_login.php");

    }

    public function login_control() 
    {
        if (isset($_SESSION["Usuario"])) {
            include("view_inicio.php");
            exit();
        }

        $con = new Conexion();
        $con->OpenConexion();

        $user_name = $_REQUEST["UserName"];
        $user_pass = md5($_REQUEST["UserPass"]);
        if (isset($_SESSION["Usuario"])) {
            header("Location: /");
        } else {
            $control = Account::control_user_password(
                                                    con: $con,
                                                    user_name: $user_name, 
                                                    user_pass: $user_pass
                                                    );
            $con->CloseConexion();
            if ($control > 0) {
                $user = new Account(account_id: $control);
                if ($user->is_active()) {
                    $_SESSION["Usuario"] = $control;
                    header("Location: /");			
                } else {
                    $mensaje_error = "Usuario incativo";
                    header("Location: /login?MensajeError=" . $mensaje_error);			
                }
            } else {
                $mensaje_error = "Nombre de Usuario o Password incorrectos";
                header("Location: /login?MensajeError=" . $mensaje_error);
            }
            
        }
    }

    public function logout_control()
    {
        if (isset($_SESSION["Usuario"])) {
            session_destroy();
        }
        header("Location: /login");
    }

}