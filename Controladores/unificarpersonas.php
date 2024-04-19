<?php 
session_start(); 
require_once 'Conexion.php';
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

$ID_Solicitud = isset($_REQUEST["ID_Solicitud"])? $_REQUEST["ID_Solicitud"] : null;
$ID_Persona_1 = $_REQUEST["ID_Persona_1"];
$ID_Persona_2 = $_REQUEST["ID_Persona_2"];
$ID_Usuario = isset($_SESSION["Usuario"])? $_SESSION["Usuario"]: null;
if($ID_Persona_1 > 0 && $ID_Persona_2 > 0){
	$Con = new Conexion();
	$Con->OpenConexion();

	if(($ID_Solicitud != null && $ID_Solicitud != "" )){
		$Consulta = "update movimiento set id_persona = $ID_Persona_1 where id_persona = $ID_Persona_2";

		mysqli_query($Con->Conexion,$Consulta)or die("Problemas en la consulta");


		$ConsultaBajaPersona = "update persona set estado = 0 where id_persona = $ID_Persona_2";
		$MensajeErrorBajaPersona = "No se pudo dar de baja la persona";

		mysqli_query($Con->Conexion,$ConsultaBajaPersona) or die($MensajeErrorBajaPersona);

		$ConsultaSolicitud = "update solicitudes_unificacion set Estado = 0 where ID_Solicitud_Unificacion = $ID_Solicitud";
		if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
			throw new Exception("Problemas en la consulta. Consulta: ".$ConsultaSolicitud, 3);			
		}

		$Con->CloseConexion();
		$Mensaje = "Los datos se unificaron Correctamente";
		header('Location: ../view_inicio.php?Mensaje='.$Mensaje);
	} else {
		$Consulta = "update movimiento set id_persona = $ID_Persona_1 where id_persona = $ID_Persona_2";
		mysqli_query($Con->Conexion,$Consulta)or die("Problemas en la consulta");

		$ConsultaBajaPersona = "update persona set estado = 0 where id_persona = $ID_Persona_2";
		$MensajeErrorBajaPersona = "No se pudo dar de baja la persona";

		mysqli_query($Con->Conexion,$ConsultaBajaPersona) or die($MensajeErrorBajaPersona);
	}
}else{
	$MensajeError = "Debe seleccionar Primera Persona y Segunda Persona";
	header('Location: ../view_inicio.php?MensajeError='.$MensajeError);
}



?>