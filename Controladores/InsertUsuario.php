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

$lastname = ucfirst($_REQUEST["lastname"]);
$firstname = ucwords($_REQUEST["firstname"]);
$initials = strtoupper($_REQUEST["initials"]);
$username = $_REQUEST["username"];
$userpass = $_REQUEST["userpass"];
$email = $_REQUEST["email"];
$Estado = 1;
$ID_TipoUsuario = $_REQUEST["ID_TipoUsuario"];
$userpass = md5($userpass);

try {
	$resultado = Account::exist_user($username);
	if ($Resultado > 0) {
		$Mensaje = "Ya existe un usuario con ese Nombre";
		header('Location: ../view_newusuarios.php?MensajeError='.$Mensaje);
	} else {
		$usuario = new Account(
					account_id: $firstname,
					 last_name: $lastname,
					  initials: $initials,
					 user_name: $username,
					  password: $userpass,
						 email: $email,
						estado: $Estado,
			   id_tipo_usuario: $ID_TipoUsuario
	   		   );
		$usuario->save();
		$Mensaje = "El Usuario fue registrado Correctamente";
		header('Location: ../view_newusuarios.php?Mensaje='.$Mensaje);
	}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
