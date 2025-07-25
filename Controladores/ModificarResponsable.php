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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/SolicitudModificacion.php");

$id_usuario = $_SESSION["Usuario"];

$id_solicitud = $_REQUEST["ID"];
$responsable = ucfirst($_REQUEST["Responsable"]);

$fecha = date("Y-m-d");
$id_tipo_accion = 2;

$con = new Conexion();
$con->OpenConexion();

try {
	if($id_solicitud > 0){
		$solicitud = new SolicitudModificacion(
												coneccion_base: $con,
												id_solicitud: $id_solicitud
		);
		$id_responsable = $solicitud->get_id_registro();
		$solicitud->delete();
		$ResponsableDatosViejos = new Responsable(
												coneccion_base: $con,
												id_responsable: $id_responsable

											   );		
		$ResponsableViejo = $ResponsableDatosViejos->get_responsable();

		$ResponsableDatosViejos->set_responsable($responsable);
		$ResponsableDatosViejos->update();

		$detalles = "El usuario con ID: $id_usuario ha modificado un Responsable. Datos: Dato Anterior: $ResponsableViejo , Dato Nuevo: $responsable";

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

?>