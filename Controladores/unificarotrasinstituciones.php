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

$ID_Institucion_1 = $_REQUEST["ID_Institucion_1"];
$ID_Institucion_2 = $_REQUEST["ID_Institucion_2"];

if($ID_Institucion_1 > 0 && $ID_Institucion_2 > 0){
	$Con = new Conexion();
	$Con->OpenConexion();

	$ConsultarInstituciones = "select * from movimiento where id_otrainstitucion = $ID_Institucion_2 and estado = 1";
	$MensajeErrorConsultarInstituciones = "No se pudieron consultar los casos de igualdad en la Institucion 1";

	$EjecutarConsultarInstituciones = mysqli_query($Con->Conexion, $ConsultarInstituciones) or die($MensajeErrorConsultarInstituciones);
	while($RetInstituciones = mysqli_fetch_assoc($EjecutarConsultarInstituciones)){
		$ID_Movimiento = $RetInstituciones["id_movimiento"];
		$CambiarInstituciones = "update movimiento set id_otrainstitucion = $ID_Institucion_1 where id_movimiento = $ID_Movimiento";
		$MensajeErrorCambiarInstituciones = "No se pudieron cambiar las instituciones 1";
		mysqli_query($Con->Conexion, $CambiarInstituciones) or die($MensajeErrorCambiarInstituciones);
	}

	$ConsultaBajaInstitucion = "update otras_instituciones set Estado = 0 where ID_OtraInstitucion = $ID_Institucion_2";
	$MensajeErrorBajaInstitucion = "No se pudo dar de baja la Institucion";

	mysqli_query($Con->Conexion,$ConsultaBajaInstitucion) or die($MensajeErrorBajaInstitucion);

	$Con->CloseConexion();
	$Mensaje = "Los datos se unificaron Correctamente";
	header('Location: ../view_unifotrasinstituciones.php?Mensaje='.$Mensaje);
}else{
	$MensajeError = "Debe seleccionar Primer Barrio y Segundo Barrio";
	header('Location: ../view_unifotrasinstituciones.php?MensajeError='.$MensajeError);
}

?>