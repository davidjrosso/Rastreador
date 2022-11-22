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
$ID_Barrio_1 = $_REQUEST["ID_Barrio_1"];
$ID_Barrio_2 = $_REQUEST["ID_Barrio_2"];

if($ID_Barrio_1 > 0 && $ID_Barrio_2 > 0){
	$Con = new Conexion();
	$Con->OpenConexion();

	$ConsultarBarrios = "select * from persona where ID_Barrio = $ID_Barrio_2 and estado = 1";
	$MensajeErrorConsultarBarrios = "No se pudieron consultar los casos de igualdad en el Barrio 1";

	$EjecutarConsultarBarrios = mysqli_query($Con->Conexion, $ConsultarBarrios) or die($MensajeErrorConsultarBarrios);
	while($RetBarrios = mysqli_fetch_assoc($EjecutarConsultarBarrios)){
		$ID_PersonaBarrio = $RetBarrios["id_persona"];
		$CambiarBarrios = "update persona set ID_Barrio = $ID_Barrio_1 where id_persona = $ID_PersonaBarrio";
		$MensajeErrorCambiarBarrios = "No se pudieron cambiar los barrios";
		mysqli_query($Con->Conexion, $CambiarBarrios) or die($MensajeErrorCambiarBarrios);
	}

	$ConsultaBajaBarrio = "update barrios set estado = 0 where ID_Barrio = $ID_Barrio_2";
	$MensajeErrorBajaBarrio = "No se pudo dar de baja el Barrio";

	mysqli_query($Con->Conexion,$ConsultaBajaBarrio) or die($MensajeErrorBajaBarrio);

	$ConsultaSolicitud = "update solicitudes_unificacion set Estado = 0 where ID_Solicitud_Unificacion = $ID_Solicitud";
	if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
		throw new Exception("Problemas en la consulta. Consulta: ".$ConsultaSolicitud, 3);			
	}

	$Con->CloseConexion();
	$Mensaje = "Los datos se unificaron Correctamente";
	header('Location: ../view_inicio.php?Mensaje='.$Mensaje);
}else{
	$MensajeError = "Debe seleccionar Primer Barrio y Segundo Barrio";
	header('Location: ../view_inicio.php?MensajeError='.$MensajeError);
}

?>