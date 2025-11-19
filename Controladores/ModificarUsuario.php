<?php 
session_start();
require_once '../Modelo/Account.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Solicitud_Usuario.php';
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

$ID_Usuario = $_SESSION["Usuario"];
$account_id = (isset($_REQUEST["account_id"])) ? ucfirst($_REQUEST["account_id"]) : null;
$lastname = (isset($_REQUEST["lastname"])) ? ucfirst($_REQUEST["lastname"]) : null;
$firstname = (isset($_REQUEST["firstname"])) ? ucwords($_REQUEST["firstname"]) : null;
$initials = (isset($_REQUEST["initials"])) ? strtoupper($_REQUEST["initials"]) : null;
$username = (isset($_REQUEST["username"])) ? $_REQUEST["username"]: null;
$userpass = (!empty($_REQUEST["userpass"])) ? $_REQUEST["userpass"] : null;
$email = (isset($_REQUEST["email"])) ? $_REQUEST["email"] : null;
$ID_TipoUsuario = (isset($_REQUEST["ID_TipoUsuario"])) ? $_REQUEST["ID_TipoUsuario"] : null;
$id_solicitud = (isset($_REQUEST["id_solcitud"])) ? $_REQUEST["id_solcitud"] : null;

try {
	if (!$id_solicitud) {

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
							email: $email,
							id_tipo_usuario: $ID_TipoUsuario
		);

		if ($ID_Usuario == $account_id) {
			$has8characters = (mb_strlen($userpass) == 8);
			$hasAlpha = preg_match('~[a-zA-Z]+~', $userpass);
			$hasNum = preg_match('~[0-9]+~', $userpass);
			$hasNonAlphaNum = preg_match('~[\!\@#$%\?&\*\(\)_\-\+=]+~', $userpass);
			if (!($has8characters && $hasAlpha && $hasNum && !$hasNonAlphaNum)) {
				$mensaje = "La contraseña debe contener 8 caracteres, alfabeticos y numericos";
				header("Location: ../view_modusuario.php?account_id={$account_id}&MensajeError="  . $mensaje);
				exit();
			}
			$user->set_password($userpass);
		}

		if (!$user->is_username_disponible($username)) {
			$Mensaje = "Ya existe un usuario con ese Nombre";
			header("Location: ../view_modusuario.php?account_id={$account_id}&MensajeError=" . $Mensaje);
		} else {
			$user->update_sin_password();
			if ($userpass) {
				$solicitud = new Solicitud_Usuario(
					usuario: $account_id,
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
			header("Location: ../view_modusuario.php?account_id={$account_id}&Mensaje=" . $Mensaje);
		}
	} else {
		$solicitud = new Solicitud_Usuario(
			id_solicitud: $id_solicitud
		);
		$password = $solicitud->get_password();
		$account_id = $solicitud->get_usuario();
		$user = new Account(
			account_id: $account_id,
			password: $password
		);
		$user->set_password($password);
		$user->update();
		$solicitud->delete();
		$Mensaje = "El Usuario fue modificado Correctamente";
		header("Location: ../view_solicitud.php?Mensaje=" . $Mensaje);

	}
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}
