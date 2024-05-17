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

$ArrPersonas = $_REQUEST["ArrPersonas"];
$NewDireccion = ucwords($_REQUEST["NewDireccion"]);

$ArrPersonas = explode(",", $ArrPersonas);


if($ArrPersonas[0] === '0'){
	$MensajeError = "Debe seleccionar los datos a modificar";
 	header('Location: ../view_unifdirecciones.php?MensajeError='.$MensajeError);
}else{
	$Con = new Conexion();
	$Con->OpenConexion();

	foreach ($ArrPersonas as $value) {
		$ConsultarDireccion = "select domicilio from persona where id_persona = $value";
		$MensajeErrorConsultar = "No se pudo consultar la direccion de la persona";

		$EjecutarConsultarDireccion = mysqli_query($Con->Conexion,$ConsultarDireccion) or die($MensajeErrorConsultar);
		$RetConsultarDireccion = mysqli_fetch_assoc($EjecutarConsultarDireccion);
		$DomActual = $RetConsultarDireccion["domicilio"];

		//$DomActual = preg_replace('/[0-9]+/','', $DomActual);
		$LongString = strlen($DomActual); 
		$StringDelimitado = chunk_split($DomActual,$LongString - 4,"-");
		$PartesDireccion = explode("-", $StringDelimitado);
		$DomActual = $PartesDireccion[0];
		
		if($DomActual == ""){
			$ModificarDireccion = "update persona set domicilio = '$NewDireccion' where id_persona = $value";
		}else{
			$ModificarDireccion = "update persona set domicilio = REPLACE(domicilio,'$DomActual','$NewDireccion ') where id_persona = $value";	
		}		
		
		$MensajeErrorModificar = "No se pudieron modificar las direcciones solicitadas";
		
		mysqli_query($Con->Conexion,$ModificarDireccion) or die($MensajeErrorModificar);
	}

	$Con->CloseConexion();
	$Mensaje = "Las direcciones se modificaron Correctamente";
	header('Location: ../view_unifdirecciones.php?Mensaje='.$Mensaje);
}

?>