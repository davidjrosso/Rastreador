<?php  
session_start();
require_once 'Conexion.php';
require_once '../Modelo/OtraInstitucion.php';
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

$Nombre = $_REQUEST["Nombre"];
$Telefono = ucfirst($_REQUEST["Telefono"]);
$Mail = $_REQUEST["Mail"];
$Estado = 1;

if(empty($Telefono)){
	$Telefono = 'null';
}

if(empty($Mail)){
	$Mail = 'null';
}

$Fecha = date("Y-m-d");
$ID_TipoAccion = 1;
$Detalles = "El usuario con ID: $ID_Usuario ha registrado una nueva Institución. Datos: Nombre: $Nombre - Telefono: $Telefono - Mail: $Mail";

$Con = new Conexion();
$Con->OpenConexion();

try {
	$ConsultarInstitucionesIguales = "select * from otras_instituciones where Nombre = '$Nombre' and Estado = 1";
	if(!$Ret = mysqli_query($Con->Conexion,$ConsultarInstitucionesIguales)){
		throw new Exception("Error al consultar registros. Consulta: ".$ConsultarInstitucionesIguales, 0);		
	}
	$Resultado = mysqli_num_rows($Ret);
	if($Resultado > 0){
		$Con->CloseConexion();
		$Mensaje = "Ya existe una Institución con ese Nombre";
		header('Location: ../view_newotrasinstituciones.php?MensajeError='.$Mensaje);
	}else{
		$InstInstitucion = new OtraInstitucion(0,$Nombre,$Telefono,$Mail,$Estado);
		$Consulta = "insert into otras_instituciones(Nombre,Telefono,Mail,Estado) values('{$InstInstitucion->getNombre()}','{$InstInstitucion->getTelefono()}','{$InstInstitucion->getMail()}',{$InstInstitucion->getEstado()})";
		if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
			throw new Exception("Error al intentar registrar. Consulta: ".$Consulta, 1);
		}	
		$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
		if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
			throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 2);
		}
		$Con->CloseConexion();
		$Mensaje = "La Institución se registro Correctamente";
		header('Location: ../view_newotrasinstituciones.php?Mensaje='.$Mensaje);
	}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
?>