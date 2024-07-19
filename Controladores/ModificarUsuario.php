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

$AccountID = $_REQUEST["account_id"];
$lastname = (isset($_REQUEST["lastname"]))?ucfirst($_REQUEST["lastname"]):null;
$firstname = (isset($_REQUEST["firstname"]))?ucwords($_REQUEST["firstname"]):null;
$initials = (isset($_REQUEST["initials"]))?strtoupper($_REQUEST["initials"]):null;
$username = $_REQUEST["username"];(isset($_REQUEST["username"]))?$_REQUEST["username"]:null;
$userpass = $_REQUEST["userpass"];(isset($_REQUEST["userpass"]))?$_REQUEST["userpass"]:null;
$email = $_REQUEST["email"];(isset($_REQUEST["email"]))?$_REQUEST["email"]:null;
$ID_TipoUsuario = $_REQUEST["ID_TipoUsuario"];(isset($_REQUEST["ID_TipoUsuario"]))?$_REQUEST["ID_TipoUsuario"]:null;

try {
	$Con = new Conexion();
	$Con->OpenConexion();
	$ConsultarRegistros = "select * 
						from accounts 
						where accountid = '$AccountID' 
							and estado = 1";
	if(!$Ret = mysqli_query($Con->Conexion,$ConsultarRegistros)){
		$MensajeError = "No existe la cuenta indicada. Consulta: ";
		throw new Exception($MensajeError.$ConsultarRegistros, 0);	
	}


	$ConsultarRegistrosIguales = "select * 
								  from accounts 
								  where username = '$username'
								  	and accountid <> '$AccountID' 
									and estado = 1";
	if(!$RetIguales = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
		$MensajeError = "Problemas al consultar registros iguales. Consulta: ";
		throw new Exception($MensajeError.$ConsultarRegistrosIguales, 0);	
	}
	$Resultado = mysqli_num_rows($RetIguales);
	if($Resultado == 1){
		mysqli_free_result($RetIguales);
		$Con->CloseConexion();
		$Mensaje = "Ya existe un usuario con ese Nombre";
		header("Location: ../view_modusuario.php?account_id={$AccountID}&MensajeError=".$Mensaje);
	}else{
		$Consulta = "update accounts
					 set firstname = '{$firstname}',
					 	 lastname = '{$lastname}',
						 initials = '{$initials}',
						 username = '{$username}',
						 ".(($userpass != null)? "password = '". md5($userpass)."',":"")."
						 ".(($email != null)? "email = '". $email."',":"")."
						 ID_TipoUsuario = {$ID_TipoUsuario}
						 where accountid = {$AccountID}";
		if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
			throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 1);		
		}	
		$Con->CloseConexion();
		$Mensaje = "El Usuario fue registrado Correctamente";
		header("Location: ../view_modusuario.php?account_id={$AccountID}&Mensaje=".$Mensaje);
	}	
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
?>