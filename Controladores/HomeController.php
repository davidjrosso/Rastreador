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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/UserToken.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");


class HomeController 
{

    public function index()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");

        } else {
            $con = new Conexion();
            $con->OpenConexion();
            $fecha_actual = new DateTime(date("Y-m-d"));
            $value = new Parametria(coneccion_base: $con, codigo: "UPDATE_FECHA_PERSONA");
            $fecha_update = new DateTime($value->get_valor());
            if ($fecha_actual > $fecha_update) {
                $consultar_datos_personas = "UPDATE personas p
                                             SET edad = IF(fecha_nac >= CURDATE() , 0, TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE())),
                                                 meses = IF(fecha_nac >= CURDATE(), 0, MOD(TIMESTAMPDIFF(MONTH, fecha_nac, CURDATE()), 12))
                                             WHERE id_persona in (select id_persona
                                                                  from personas 
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

            $id_usuario = $_SESSION["Usuario"];
            $account = new Account(account_id: $id_usuario);
            $tipo_usuario = $account->get_id_tipo_usuario();

            $CtrGeneral = new CtrGeneral();
            $Element = new Elements();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_inicio.php");
        }
        exit();
    }

    public function error_session() 
    {
        if (isset($_SESSION["Usuario"])) {
            include("./Views/view_inicio.php");
        }
    }

    public function login($mensaje = null) 
    {
        header("Content-Type: text/html;charset=utf-8");

        $mensaje_error = $mensaje;
        $mensaje_succes = null;

        if (isset($_SESSION["Usuario"])) {
            header("Location: /home");
        } else {
            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_login.php");
        }
        exit();
    }

    public function password_peticion()
    {
        try {
            $con = new Conexion();
            $con->OpenConexion();

            if (empty($_SERVER["HTTP_REFERER"])) {
                $url = $_SERVER["REQUEST_URI"];
                $opciones = explode("/", $url);
                $token = $opciones[3];
                if (!UserToken::is_token_valid(coneccion: $con, token: $token)) {
                    $con->CloseConexion();
                    $mensaje = "El enlace de recuperacion de contraseña expiro o es invalido, por favor solicite un nuevo enlace.";
                    header('Location: /password_peticion?MensajeError=' . $mensaje);
                } else {
                    $con->CloseConexion();
                    header('Location: /modificar_password?token=' . $token);
                }
            } else if (preg_match("~view_modpassword~", $_SERVER["HTTP_REFERER"])) {
                $userpass = (isset($_REQUEST["password"])) ? $_REQUEST["password"] : null;
                $token = (isset($_REQUEST["token"])) ? $_REQUEST["token"] : null;
                if (!UserToken::is_token_valid(coneccion: $con, token: $token)) {
                    $con->CloseConexion();
                    $mensaje = "El enlace de recuperacion de contraseña expiro o es invalido, por favor solicite un nuevo enlace.";
                    header('Location: ../../view_modpassword.php?MensajeError=' . $mensaje);
                } else {
                    $user_token = new UserToken(coneccion_base: $con, token: $token);
                    $account_id = $user_token->get_account_id();
                    $has8characters = (mb_strlen($userpass) >= 8);
                    $hasAlpha = preg_match('~[a-zA-Z]+~', $userpass);
                    $hasNum = preg_match('~[0-9]+~', $userpass);
                    $hasNonAlphaNum = preg_match('~[\!\@#$%\?&\*\(\)_\-\+=]+~', $userpass);
                    if (!($has8characters && $hasAlpha && $hasNum && !$hasNonAlphaNum)) {
                        $mensaje = "La contraseña debe contener 8 caracteres, alfabeticos y numericos";
                        header("Location: ../../view_modpassword.php?MensajeError=" . $mensaje);
                    } else {
                        $account = new Account(account_id: $account_id);
                        $account->set_password($userpass);
                        $account->update();
                        $user_token = new UserToken(coneccion_base: $con, token: $token);
                        $user_token->set_estado(0);
                        $user_token->update();
                        $con->CloseConexion();
                        $mensaje = "Contraseña modificada";
                        header("Location: ../index.php?Mensaje=" . $mensaje);
                    }
                }
            }
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        
    }

    public function modificar_password()
    {

    }

    public function login_control() 
    {
        header("content-type: application/json");
        $con = new Conexion();
        $con->OpenConexion();

        $user_name = $_REQUEST["UserName"];
        $user_pass = md5($_REQUEST["UserPass"]);
        if (isset($_SESSION["Usuario"])) {
           $mensaje["redirect"] = true;
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
                    $mensaje["redirect"] = true;
                } else {
                    $mensaje_error = "Usuario incativo";
                    $mensaje["MensajeError"] = $mensaje_error;
                }
            } else {
                $mensaje_error = "Nombre de Usuario o Password incorrectos";
                $mensaje["MensajeError"] = $mensaje_error;
            }
        }
        echo json_encode($mensaje) ;
    }

    public function logout_control()
    {
        if (isset($_SESSION["Usuario"])) {
            session_destroy();
        }
        header("Location: /login");
        exit();
    }

    public function not_found() {
        header("Content-Type: text/html;charset=utf-8");
        header("HTTP/1.0 404 Not Found");
        require("./Views/view_not_found_404.php");
    }

    public function metodo_no_aceptado() {
        header("Content-Type: text/html;charset=utf-8");
        header("HTTP/1.0 405 Method Not Allowed");
        require("./Views/view_not_found_404.php");
    }

}