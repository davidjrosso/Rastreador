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

	$ConsultarMotivos_1 = "select * from movimiento where motivo_1 = $ID_Motivo_2 and estado = 1";
	$MensajeErrorConsultarMotivos_1 = "No se pudieron consultar los casos de igualdad en el Motivo 1";

	$EjecutarConsultarMotivos_1 = mysqli_query($Con->Conexion, $ConsultarMotivos_1) or die($MensajeErrorConsultarMotivos_1);
	while($RetMotivos_1 = mysqli_fetch_assoc($EjecutarConsultarMotivos_1)){
		$ID_MovimientoMotivos_1 = $RetMotivos_1["id_movimiento"];
		$CambiarMotivos_1 = "update movimiento set motivo_1 = $ID_Motivo_1 where id_movimiento = $ID_MovimientoMotivos_1";
		$MensajeErrorCambiarMotivos_1 = "No se pudieron cambiar los motivos 1";
		mysqli_query($Con->Conexion, $CambiarMotivos_1) or die($MensajeErrorCambiarMotivos_1);
	}

	$ConsultarMotivos_2 = "select * from movimiento where motivo_2 = $ID_Motivo_2 and estado = 1";
	$MensajeErrorConsultarMotivos_2 = "No se pudieron consultar los casos de igualdad en el Motivo 2";

	$EjecutarConsultarMotivos_2 = mysqli_query($Con->Conexion, $ConsultarMotivos_2) or die($MensajeErrorConsultarMotivos_2);
	while($RetMotivos_2 = mysqli_fetch_assoc($EjecutarConsultarMotivos_2)){
		$ID_MovimientoMotivos_2 = $RetMotivos_2["id_movimiento"];
		$CambiarMotivos_2 = "update movimiento set motivo_2 = $ID_Motivo_1 where id_movimiento = $ID_MovimientoMotivos_2";
		$MensajeErrorCambiarMotivos_2= "No se pudieron cambiar los motivos 2";
		mysqli_query($Con->Conexion, $CambiarMotivos_2) or die($MensajeErrorCambiarMotivos_2);
	}

	$ConsultarMotivos_3 = "select * from movimiento where motivo_3 = $ID_Motivo_2 and estado = 1";
	$MensajeErrorConsultarMotivos_3 = "No se pudieron consultar los casos de igualdad en el Motivo 3";

	$EjecutarConsultarMotivos_3 = mysqli_query($Con->Conexion, $ConsultarMotivos_3) or die($MensajeErrorConsultarMotivos_3);
	while($RetMotivos_3 = mysqli_fetch_assoc($EjecutarConsultarMotivos_3)){
		$ID_MovimientoMotivos_3 = $RetMotivos_3["id_movimiento"];
		$CambiarMotivos_3 = "update movimiento set motivo_3 = $ID_Motivo_1 where id_movimiento = $ID_MovimientoMotivos_3";
		$MensajeErrorCambiarMotivos_3 = "No se pudieron cambiar los motivos 3";
		mysqli_query($Con->Conexion, $CambiarMotivos_3) or die($MensajeErrorCambiarMotivos_3);
	}

	$ConsultaBajaMotivo = "update motivo set estado = 0 where id_motivo = $ID_Motivo_2";
	$MensajeErrorBajaMotivo = "No se pudo dar de baja el Motivo";

	mysqli_query($Con->Conexion,$ConsultaBajaMotivo) or die($MensajeErrorBajaMotivo);

	$ConsultaSolicitud = "update solicitudes_unificacion set Estado = 0 where ID_Solicitud_Unificacion = $ID_Solicitud";
	if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
		throw new Exception("Problemas en la consulta. Consulta: ".$ConsultaSolicitud, 3);			
	}

	$Con->CloseConexion();
	$Mensaje = "Los datos se unificaron Correctamente";
	header('Location: ../view_inicio.php?Mensaje='.$Mensaje);
}else{
	$MensajeError = "Debe seleccionar Primer Motivo y Segundo Motivo";
	header('Location: ../view_inicio.php?MensajeError='.$MensajeError);
}

?>