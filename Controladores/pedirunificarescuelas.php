<?php
session_start(); 
require_once 'Conexion.php';
require_once '../Modelo/Solicitud_Unificacion.php';
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

$Fecha = Date("Y-m-d");
$ID_Registro_1 = $_REQUEST["ID_Escuela_1"];
$ID_Registro_2 = $_REQUEST["ID_Escuela_2"];
$ID_Usuario = $_SESSION["Usuario"];
$Estado = 1;
$TipoUnif = 4;

if($ID_Registro_1 > 0 && $ID_Registro_2 > 0){
	$Con = new Conexion();
	$Con->OpenConexion();

	$Solicitud = new Solicitud_Unificacion(0,$Fecha,$ID_Registro_1,$ID_Registro_2,$ID_Usuario,$Estado,$TipoUnif);
	$Insert_Solicitud = "insert into solicitudes_unificacion(Fecha,ID_Registro_1,ID_Registro_2,ID_Usuario,Estado,ID_TipoUnif) values('{$Solicitud->getFecha()}',{$Solicitud->getID_Registro_1()},{$Solicitud->getID_Registro_2()},{$Solicitud->getID_Usuario()},{$Solicitud->getEstado()},{$Solicitud->getTipoUnif()})";
	$MensajeError = "No se pudo enviar la solicitud";

	mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError);

	$Con->CloseConexion();
	$Mensaje = "La solicitud de unificación se envió a los administradores para ser confirmada.";
	header('Location: ../view_unifescuelas.php?Mensaje='.$Mensaje);
}else{
	$MensajeError = "Debe seleccionar Primer Centro y Segundo Centro";
	header('Location: ../view_unifescuelas.php?MensajeError='.$MensajeError);
}

?>