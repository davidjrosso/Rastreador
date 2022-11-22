<?php 
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

$ID_Solicitud = $_REQUEST["ID_Solicitud"];
$ID_Escuela_1 = $_REQUEST["ID_Escuela_1"];
$ID_Escuela_2 = $_REQUEST["ID_Escuela_2"];

if($ID_Escuela_1 > 0 && $ID_Escuela_2 > 0){
	$Con = new Conexion();
	$Con->OpenConexion();

	$ConsultarEscuelas = "select * from persona where ID_Escuela = $ID_Escuela_2 and estado = 1";
	$MensajeErrorConsultarEscuelas = "No se pudieron consultar los casos de igualdad en la Escuela 1";

	$EjecutarConsultarEscuelas = mysqli_query($Con->Conexion, $ConsultarEscuelas) or die($MensajeErrorConsultarEscuelas);
	while($RetEscuelas = mysqli_fetch_assoc($EjecutarConsultarEscuelas)){
		$ID_PersonaEscuela = $RetEscuelas["id_persona"];
		$CambiarEscuelas = "update persona set ID_Escuela = $ID_Escuela_1 where id_persona = $ID_PersonaEscuela";
		$MensajeErrorCambiarEscuelas = "No se pudieron cambiar las Escuelas";
		mysqli_query($Con->Conexion, $CambiarEscuelas) or die($MensajeErrorCambiarEscuelas);
	}

	$ConsultaBajaEscuela = "update escuelas set estado = 0 where ID_Escuela = $ID_Escuela_2";
	$MensajeErrorBajaEscuela = "No se pudo dar de baja la Escuela";

	mysqli_query($Con->Conexion,$ConsultaBajaEscuela) or die($MensajeErrorBajaEscuela);

	$ConsultaSolicitud = "update solicitudes_unificacion set Estado = 0 where ID_Solicitud_Unificacion = $ID_Solicitud";
	if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
		throw new Exception("Problemas en la consulta. Consulta: ".$ConsultaSolicitud, 3);			
	}

	$Con->CloseConexion();
	$Mensaje = "Los datos se unificaron Correctamente";
	header('Location: ../view_inicio.php?Mensaje='.$Mensaje);
}else{
	$MensajeError = "Debe seleccionar Primer Escuela y Segunda Escuela";
	header('Location: ../view_inicio.php?MensajeError='.$MensajeError);
}

?>