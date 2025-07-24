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

session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Responsable.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");

$id_usuario = $_SESSION["Usuario"];

$id_solicitud = $_REQUEST["ID"];

$fecha = date("Y-m-d");
$id_tipo_accion = 2;

$con = new Conexion();
$con->OpenConexion();

try {
	if($id_solicitud > 0){
		$solicitud = new Solicitud_Unificacion(
											   coneccion: $con,
											   xID_Solicitud : $id_solicitud
											  );
		$id_responsable_unif = $solicitud->getID_Registro_1();
		$id_responsable_del = $solicitud->getID_Registro_1();
		$solicitud->delete();

		$responsable_unif = new Responsable(
											coneccion_base: $con,
											id_responsable: $id_responsable_unif
										   );
		$responsable_del = new Responsable(
										   coneccion_base: $con,
										   id_responsable: $id_responsable_del
										  );
		$resp_unif_nombre = $responsable_unif->get_responsable();
		$resp_del_nombre = $responsable_del->get_responsable();

		$Consulta = "UPDATE movimiento 
					SET id_resp = $id_responsable_unif
					WHERE id_resp = $id_responsable_del
					AND estado = 1";
		
		if(!$Ret = mysqli_query($con->Conexion,$Consulta)){
			throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
		}

		$Consulta = "UPDATE movimiento 
					SET id_resp_2 = $id_responsable_unif
					WHERE id_resp_2 = $id_responsable_del
					AND estado = 1";
		
		if(!$Ret = mysqli_query($con->Conexion,$Consulta)){
			throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
		}

		$Consulta = "UPDATE movimiento 
					SET id_resp_3 = $id_responsable_unif
					WHERE id_resp_3 = $id_responsable_del
					AND estado = 1";
		
		if(!$Ret = mysqli_query($con->Conexion,$Consulta)){
			throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
		}

		$Consulta = "UPDATE movimiento 
					SET id_resp_4 = $id_responsable_unif
					WHERE id_resp_4 = $id_responsable_del
					AND estado = 1";
		
		if(!$Ret = mysqli_query($con->Conexion,$Consulta)){
			throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
		}

		$Consulta = "UPDATE archivos 
					SET responsable = $id_responsable_unif
					WHERE responsable = '$id_responsable_del' 
					AND estado = 1";
		
		if(!$Ret = mysqli_query($con->Conexion,$Consulta)){
			throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
		}

		$responsable_del->delete();
		$detalles = "El usuario con ID: $id_usuario ha unificado a Responsables. Datos: Dato responsable unif: $resp_unif_nombre , Dato responsable del : $resp_del_nombre";

		$accion = new Accion(
			xaccountid: $id_usuario,
			xFecha : $fecha,
			xDetalles: $detalles,
			xID_TipoAccion: $id_tipo_accion	 
		);		

		$accion->save();

		$mensaje = "El Responsable se modificó Correctamente";
		header('Location: ../view_inicio.php?&Mensaje=' . $mensaje);
	}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
