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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");
require_once($_SERVER['DOCUMENT_ROOT'] . '/Modelo/Solicitud_Usuario.php');
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");


class AccountController 
{

    public function listado_accounts($mensaje = null)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_usuarios.php");
        }
        exit();
    }

    public function mod_account(
                                $id_account,
                                $mensaje = null,
                                $mensaje_error = null
    ){
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            header("Content-Type: text/html;charset=utf-8");

            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $AccountID = $_REQUEST["account_id"];
            $usuario = new Account(account_id: $AccountID);
            $lastname = ucfirst($usuario->get_last_name());
            $firstname = ucwords($usuario->get_first_name());
            $initials = strtoupper($usuario->get_initials());
            $username = $usuario->get_user_name();
            $userpass = $usuario->get_password();
            $email = $usuario->get_email();
            $ID_Tipo = $usuario->get_id_tipo_usuario();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_modusuario.php");
        }
        exit();
    }

    public function datos_account($id_account)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_verusuarios.php");
        }
        exit();
    }

    public function dato_account_user($id_account = null, $mensaje = null)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");

        } else {
            header("Content-Type: text/html;charset=utf-8");
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $AccountID = $_REQUEST["account_id"];
            $account = new Account(account_id: $AccountID);
            $exist_user = Account::exist_account(account_id: $AccountID);
            $lastname = ucfirst($account->get_last_name());
            $firstname = ucwords($account->get_first_name());
            $initials = strtoupper($account->get_initials());
            $username = $account->get_user_name();
            $userpass = $account->get_password();
            $email = $account->get_email();
            $ID_Tipo = $account->get_id_tipo_usuario();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            $Element = new Elements();

            include("./Views/view_perfilusuario.php");
        }

        exit();
    }

    public function mod_account_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];
        $account_id = (isset($_REQUEST["account_id"])) ? ucfirst($_REQUEST["account_id"]) : null;
        $lastname = (isset($_REQUEST["lastname"])) ? ucfirst($_REQUEST["lastname"]) : null;
        $firstname = (isset($_REQUEST["firstname"])) ? ucwords($_REQUEST["firstname"]) : null;
        $initials = (isset($_REQUEST["initials"])) ? strtoupper($_REQUEST["initials"]) : null;
        $username = (isset($_REQUEST["username"])) ? $_REQUEST["username"]: null;
        $userpass = (isset($_REQUEST["userpass"])) ? $_REQUEST["userpass"] : null;
        $email = (isset($_REQUEST["email"])) ? $_REQUEST["email"] : null;
        $ID_TipoUsuario = (isset($_REQUEST["ID_TipoUsuario"])) ? $_REQUEST["ID_TipoUsuario"] : null;
        $id_solicitud = (isset($_REQUEST["id_solcitud"])) ? $_REQUEST["id_solcitud"] : null;

        try {
            if (!$id_solicitud) {
                $has8characters = (mb_strlen($userpass) == 8);
                $hasAlpha = preg_match('~[a-zA-Z]+~', $userpass);
                $hasNum = preg_match('~[0-9]+~', $userpass);
                $hasNonAlphaNum = preg_match('~[\!\@#$%\?&\*\(\)_\-\+=]+~', $userpass);
                if (!($has8characters && $hasAlpha && $hasNum && !$hasNonAlphaNum)) {
                    $mensaje = "La contraseña debe contener 8 caracteres, alfabeticos y numericos";
                    header("Location: /usuario/editar?account_id={$account_id}&MensajeError="  . $mensaje);
                    exit();
                }

                $existe = Account::exist_account($account_id);
                if (!$existe) {
                    $MensajeError = "No existe la cuenta indicada.";
                    throw new Exception($MensajeError, 0);	
                }
                $user = new Account(
                                    account_id: $account_id,
                                    last_name: $lastname,
                                    first_name: $firstname,
                                    initials: $initials,
                                    user_name: $username,
                                    password: $userpass,
                                        email: $email,
                            id_tipo_usuario: $ID_TipoUsuario
                );

                if (!$user->is_username_disponible($username)) {
                    $Mensaje = "Ya existe un usuario con ese Nombre";
                    header("Location: /usuario/editar?account_id=$account_id&MensajeError=" . $Mensaje);
                } else {
                    $user->update();
                    if ($userpass) {
                        $solicitud = new Solicitud_Usuario(
                            usuario: $ID_Usuario,
                            descripcion: "Modificacion de contraseña ",
                            password: md5($userpass),
                            estado: 1,
                            tipo: 1
                        );
                        $solicitud->save();
                        $Mensaje = "La peticion de modificacion de contaseña fue enviada la administrador";
                    } else {
                        $Mensaje = "El Usuario fue modificado Correctamente";
                    }
                    header("Location: /usuario/editar?account_id=$account_id&Mensaje=" . $Mensaje);
                }
            } else {
                $solicitud = new Solicitud_Usuario(
                    id_solicitud: $id_solicitud
                );
                $password = $solicitud->get_password();
                $account_id = $solicitud->get_usuario();
                $solicitud->delete();
                $user = new Account(
                    account_id: $account_id,
                    password: $password
                );
                $user->set_password($password);
                $user->update();
                $Mensaje = "El Usuario fue modificado Correctamente";
                header("Location: ../view_solicitud.php?Mensaje=" . $Mensaje);

            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        exit();
    }
}