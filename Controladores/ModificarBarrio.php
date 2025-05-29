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
require_once($_SERVER["DOCUMENT_ROOT"] . '/Controladores/Conexion.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/Modelo/Barrio.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/Modelo/Accion.php');


$ID_Usuario = $_SESSION["Usuario"];

$ID_Barrio = $_REQUEST["ID"];
$barrio_nombre = ucwords($_REQUEST["Barrio"]);

$georeferencia_point = null;

if (!empty($_REQUEST["lat"])) {
	$lat_point = $_REQUEST["lat"];
	$georeferencia_point = "POINT(" . $lat_point;

	if (!empty($_REQUEST["lon"])){
		$lon_point = $_REQUEST["lon"];
		$georeferencia_point .= "," . $lon_point . ")";
	} else {
		$georeferencia_point = null;
	}
}

$Fecha = date("Y-m-d");
$ID_TipoAccion = 2;

$Con = new Conexion();
$Con->OpenConexion();

try {
	$Con = new Conexion();
	$Con->OpenConexion();
	$existe = Barrio::existe_barrio(coneccion: $Con, name: $barrio_nombre);
	$id_barrio_control = Barrio::get_id_by_name(coneccion: $Con, name: $barrio_nombre);
	if ($existe > 0 && $id_barrio_control != $ID_Barrio) {
		$Con->CloseConexion();
		$Mensaje = "Ya existe un Barrio con ese Nombre";
		header('Location: ../view_modbarrios.php?ID=' . $ID_Barrio . '&MensajeError=' . $Mensaje);
	} else {
		$barrio = new Barrio(coneccion: $Con, id_barrio: $ID_Barrio);

		$barrio_viejo = $barrio->get_barrio();

		$barrio->set_barrio($barrio_nombre);
		$barrio->set_georeferencia($georeferencia_point);
		$barrio->update($Con);

		$Detalles = "El usuario con ID: $ID_Usuario ha modificado un Barrio. Datos: Dato Anterior: $barrio_viejo , Dato Nuevo: $barrio_nombre";
		$accion = new Accion(
			xaccountid: $ID_Usuario,
			xFecha : $Fecha,
			xDetalles: $Detalles,
			xID_TipoAccion: $ID_TipoAccion	 
		);
		$accion->save();
		
		$Con->CloseConexion();
		$Mensaje = "El Barrio se modificó Correctamente";
		header('Location: ../view_modbarrios.php?ID=' . $ID_Barrio . '&Mensaje=' . $Mensaje);
	}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}

?>