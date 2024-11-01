<?php  
session_start();
require_once("Conexion.php");
require_once("../Modelo/Persona.php");
require_once("../Modelo/Account.php");

$con = new Conexion();
$con->OpenConexion();
$consultar_datos_personas = "select * 
						   from persona 
						   where fecha_nac is not null
						   	 and fecha_nac <> 'null'
						   	 and estado = 1";
$mensaje_error_datos_personas = "No se pudieron consultar los datos de las personas registradas en el sistema";
$ejecutar_consultar_datos_personas = mysqli_query(
										$con->Conexion,
										$consultar_datos_personas
								  ) or die($mensaje_error_datos_personas);

while ($RetDatosPersonas = mysqli_fetch_assoc($ejecutar_consultar_datos_personas)) {
	$persona = new Persona(ID_Persona: $RetDatosPersonas['id_persona']);
	$persona->update_edad_meses();
}

$con->CloseConexion();

$user_name = $_REQUEST["UserName"];
$user_pass = md5($_REQUEST["UserPass"]);
if (isset($_SESSION["Usuario"])) {
	header("Location: ../view_inicio.php");
} else {
	$control = Account::control_user_password($user_name, $user_pass);
 	if ($control > 0) {
		$user = new Account(account_id: $control);
		if ($user->is_active()) {
			$_SESSION["Usuario"] = $control;
			header("Location: ../view_inicio.php");			
		} else {
			$mensaje_error = "Usuario incativo";
			header("Location: ../index.php?MensajeError=" . $mensaje_error);			
		}
 	} else {
		$mensaje_error = "Nombre de Usuario o Password incorrectos";
 		header("Location: ../index.php?MensajeError=" . $mensaje_error);
 	}
 	
}


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


?>