<?php 
session_start();
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

$ID_Usuario = $_SESSION["Usuario"];

$ID_Movimiento = $_REQUEST["ID"];

$Fecha = date("Y-m-d");
$ID_TipoAccion = 3;
$Detalles = "El usuario con ID: $ID_Usuario ha dado de baja un Movimiento. Datos: Movimiento: $ID_Movimiento";

try {
	$Con = new Conexion();
	$Con->OpenConexion();

	$Consulta = "update movimiento set estado = 0 where id_movimiento = $ID_Movimiento";
	if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
		throw new Exception("Problemas en la consulta", 0);		
	}	
	$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
	if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
		throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 1);
	}	

	$ConsultarDatos = "select * 
					   from persona p inner join movimiento q on (p.id_persona = q.id_persona)
					   where id_movimiento = $ID_Movimiento";
	$ErrorDatos = "No se pudieron consultar los datos :";
	if(!$RetDatos = mysqli_query($Con->Conexion,$ConsultarDatos)){
		throw new Exception($ErrorDatos.$ConsultarDatos, 1);
	}

	$TomarDatos = mysqli_fetch_assoc($RetDatos);
	$Apellido = $TomarDatos["apellido"];
	$Nombre = $TomarDatos["nombre"];
	$Fecha =  $TomarDatos["fecha"];
	$DNI = $TomarDatos["documento"];

	// CREANDO NOTIFICACION PARA EL USUARIO
	$DetalleNot = 'Se elimino el movimiento vinculado a : '.$Apellido. ', '.$Nombre. (($Fecha == null)?'':' fecha: '. $Fecha);
	$Expira = date("Y-m-d", strtotime($Fecha . " + 15 days"));

	$ConsultaNot = "insert into notificaciones(Detalle, Fecha, Expira, Estado) values('$DetalleNot','$Fecha', '$Expira',1)";
	if(!$RetNot = mysqli_query($Con->Conexion,$ConsultaNot)){
		throw new Exception("Error al intentar registrar Notificacion. Consulta: ".$ConsultaNot, 3);
	}

	$Con->CloseConexion();
	$Mensaje = "El movimiento se elimino Correctamente";
	header('Location: ../view_movimientos.php?Mensaje='.$Mensaje);
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
?>