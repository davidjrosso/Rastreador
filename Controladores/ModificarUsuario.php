<?php 
require_once '../Modelo/Account.php';
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

$AccountID = $_REQUEST["account_id"];
$lastname = (isset($_REQUEST["lastname"]))?ucfirst($_REQUEST["lastname"]):null;
$firstname = (isset($_REQUEST["firstname"]))?ucwords($_REQUEST["firstname"]):null;
$initials = (isset($_REQUEST["initials"]))?strtoupper($_REQUEST["initials"]):null;
$username = (isset($_REQUEST["username"]))?$_REQUEST["username"]:null;
$userpass = (isset($_REQUEST["userpass"]))?$_REQUEST["userpass"]:null;
$email = (isset($_REQUEST["email"]))?$_REQUEST["email"]:null;
$ID_TipoUsuario = (isset($_REQUEST["ID_TipoUsuario"]))?$_REQUEST["ID_TipoUsuario"]:null;

try {
	$existe = Account::exist_account($AccountID);
	if (!$existe) {
		$MensajeError = "No existe la cuenta indicada.";
		throw new Exception($MensajeError, 0);	
	}
	$user = new Account(
						account_id: $AccountID,
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
		header("Location: ../view_modusuario.php?account_id={$AccountID}&MensajeError=" . $Mensaje);
	} else {

		$user->update();
		$Mensaje = "El Usuario fue modificado Correctamente";
		header("Location: ../view_perfilusuario.php?account_id={$AccountID}&Mensaje=" . $Mensaje);
	}	
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}
