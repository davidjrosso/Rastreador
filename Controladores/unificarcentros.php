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
$ID_Centro_1 = $_REQUEST["ID_Centro_1"];
$ID_Centro_2 = $_REQUEST["ID_Centro_2"];

if($ID_Centro_1 > 0 && $ID_Centro_2 > 0){
	$Con = new Conexion();
	$Con->OpenConexion();

	$ConsultarCentros = "select * from movimiento where id_centro = $ID_Centro_2 and estado = 1";
	$MensajeErrorConsultarCentros = "No se pudieron consultar los casos de igualdad en el Centro 1";

	$EjecutarConsultarCentros = mysqli_query($Con->Conexion, $ConsultarCentros) or die($MensajeErrorConsultarCentros);
	while($RetCentros = mysqli_fetch_assoc($EjecutarConsultarCentros)){
		$ID_MovimientoCentro = $RetCentros["id_movimiento"];
		$CambiarCentros = "update movimiento set id_centro = $ID_Centro_1 where id_movimiento = $ID_MovimientoCentro";
		$MensajeErrorCambiarCentros = "No se pudieron cambiar los centros";
		mysqli_query($Con->Conexion, $CambiarCentros) or die($MensajeErrorCambiarCentros);
	}

	$ConsultaBajaCentro = "update centros_salud set estado = 0 where id_centro = $ID_Centro_2";
	$MensajeErrorBajaCentro = "No se pudo dar de baja el Centro de Salud";

	mysqli_query($Con->Conexion,$ConsultaBajaCentro) or die($MensajeErrorBajaCentro);
	
	$ConsultaSolicitud = "update solicitudes_unificacion set Estado = 0 where ID_Solicitud_Unificacion = $ID_Solicitud";
	if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
		throw new Exception("Problemas en la consulta. Consulta: ".$ConsultaSolicitud, 3);			
	}

	$Con->CloseConexion();
	$Mensaje = "Los datos se unificaron Correctamente";
	header('Location: ../view_inicio.php?Mensaje='.$Mensaje);
}else{
	$MensajeError = "Debe seleccionar Primer Centro y Segundo Centro";
	header('Location: ../view_inicio.php?MensajeError='.$MensajeError);
}

?>