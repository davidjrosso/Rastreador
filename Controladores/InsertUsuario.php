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
$estado = 1;
$ID_TipoUsuario = $_REQUEST["ID_TipoUsuario"];

try {
	$has8characters = (mb_strlen($userpass) >= 8);
	$hasAlpha = preg_match('~[a-zA-Z]+~', $userpass);
	$hasNum = preg_match('~[0-9]+~', $userpass);
	$hasNonAlphaNum = preg_match('~[\!\@#$%\?&\*\(\)_\-\+=]+~', $userpass);

	if (!($has8characters && $hasAlpha && $hasNum && !$hasNonAlphaNum)) {
		$mensaje = "La contraseña debe contener 8 caracteres, alfabeticos y numericos";
		header('Location: ../view_newusuarios.php?MensajeError=' . $mensaje);
	}

	$resultado = Account::exist_user($username);
	if ($resultado > 0) {
		$mensaje = "Ya existe un usuario con ese Nombre";
		header('Location: ../view_newusuarios.php?MensajeError=' . $mensaje);
	} else {
		$usuario = new Account(
					first_name: $firstname,
					 last_name: $lastname,
					  initials: $initials,
					 user_name: $username,
					  password: $userpass,
						 email: $email,
						estado: $estado,
			   id_tipo_usuario: $ID_TipoUsuario
	   		   );
		$usuario->save();
		$mensaje = "El Usuario fue registrado Correctamente";
		header('Location: ../view_newusuarios.php?Mensaje=' . $mensaje);
	}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
