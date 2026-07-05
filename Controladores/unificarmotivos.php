<?php 
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
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';

$ID_Solicitud = $_REQUEST["ID_Solicitud"];
$ID_Motivo_1 = $_REQUEST["ID_Motivo_1"];
$ID_Motivo_2 = $_REQUEST["ID_Motivo_2"];

if($ID_Motivo_1 > 0 && $ID_Motivo_2 > 0){
	$Con = new Conexion();
	$Con->OpenConexion();

	$ConsultarMotivos = "update movimiento_motivo set id_motivo = $ID_Motivo_1 where id_motivo = $ID_Motivo_2 and estado = 1";
	$MensajeErrorConsultarMotivos = "No se pudieron consultar los casos de igualdad en el Motivo 1";

	mysqli_query($Con->Conexion, $ConsultarMotivos) or die($MensajeErrorConsultarMotivos);

	$ConsultaBajaMotivo = "update motivo set estado = 0 where id_motivo = $ID_Motivo_2";
	$MensajeErrorBajaMotivo = "No se pudo dar de baja el Motivo";

	mysqli_query($Con->Conexion,$ConsultaBajaMotivo) or die($MensajeErrorBajaMotivo);

	$ConsultaSolicitud = "update solicitudes_unificacion set Estado = 0 where ID_Solicitud_Unificacion = $ID_Solicitud";
	if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
		throw new Exception("Problemas en la consulta. Consulta: " . $ConsultaSolicitud, 3);			
	}

	$Con->CloseConexion();
	$Mensaje = "Los datos se unificaron Correctamente";
	header('Location: ../view_inicio.php?Mensaje=' . $Mensaje);
}else{
	$MensajeError = "Debe seleccionar Primer Motivo y Segundo Motivo";
	header('Location: ../view_inicio.php?MensajeError=' . $MensajeError);
}

?>