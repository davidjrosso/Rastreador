<?php 
require_once 'Conexion.php';
require_once '../Modelo/Usuario.php';
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

$Usuario = new Usuario(0,$firstname,$lastname,$initials,$username,$userpass,$email,$Estado,$ID_TipoUsuario);

try {
	$Con = new Conexion();
	$Con->OpenConexion();

	$ConsultarRegistrosIguales = "select * from accounts where username = '$username' and estado = 1";
	if(!$RetIguales = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
		throw new Exception("Problemas al consultar registros iguales. Consulta: ".$ConsultarRegistrosIguales, 0);		
	}
	$Resultado = mysqli_num_rows($RetIguales);
	if($Resultado > 0){
		mysqli_free_result($RetIguales);
		$Con->CloseConexion();
		$Mensaje = "Ya existe un usuario con ese Nombre";
		header('Location: ../view_newusuarios.php?MensajeError='.$Mensaje);
	}else{
		$Consulta = "insert into accounts(firstname,lastname,initials,username,password,email,estado,ID_TipoUsuario) values('".$Usuario->getFirstName()."','".$Usuario->getLastName()."','".$Usuario->getInitials()."','".$Usuario->getUserName()."','".$Usuario->getUserPass()."','".$Usuario->getEmail()."',".$Usuario->getEstado().",".$Usuario->getID_TipoUsuario().")";
		if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
			throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 1);		
		}	
		$Con->CloseConexion();
		$Mensaje = "El Usuario fue registrado Correctamente";
		header('Location: ../view_newusuarios.php?Mensaje='.$Mensaje);
	}	
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
?>