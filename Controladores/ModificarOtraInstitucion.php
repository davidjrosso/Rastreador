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

$ID_OtraInstitucion = $_REQUEST["ID"];
$Nombre = $_REQUEST["Nombre"];
$Telefono = $_REQUEST["Telefono"];
$Mail = ucfirst($_REQUEST["Mail"]);
$Estado = 1;

$Institucion_Nueva = new OtraInstitucion($ID_OtraInstitucion,$Nombre,$Telefono,$Mail,$Estado);

$Fecha = date("Y-m-d");
$ID_TipoAccion = 2;

$Con = new Conexion();
$Con->OpenConexion();

try {
	$ConsultarRegistrosIguales = "select * from otras_instituciones where Nombre = '$Nombre' and ID_OtraInstitucion != $ID_OtraInstitucion and estado = 1";
	if(!$Ret = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
		throw new Exception("Error al consultar registros. Consulta: ".$ConsultarRegistrosIguales, 0);		
	}
	$Resultado = mysqli_num_rows($Ret);
	if($Resultado > 0){
		$Con->CloseConexion();
		$Mensaje = "Ya existe una Institucion con ese Nombre";
		header('Location: ../view_modotrasinstituciones.php?ID='.$ID_OtraInstitucion.'&MensajeError='.$Mensaje);
	}else{
		$ConsultarDatosViejos = "select * from otras_instituciones where ID_OtraInstitucion = $ID_OtraInstitucion and Estado = 1";
		$ErrorDatosViejos = "No se pudieron consultar los datos";
		if(!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)){
			throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
		}		
		$TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
		$ID_OtraInstitucion = $TomarDatosViejos["ID_OtraInstitucion"];
		$Nombre = $TomarDatosViejos["Nombre"];
		$Telefono = $TomarDatosViejos["Telefono"];
		$Mail = $TomarDatosViejos["Mail"];		
		$Estado = $TomarDatosViejos["Estado"];
		
		$Institucion_Vieja = new OtraInstitucion($ID_OtraInstitucion,$Nombre,$Telefono,$Mail,$Estado);
		

		$Consulta = "update otras_instituciones set Nombre = '{$Institucion_Nueva->getNombre()}', Telefono = '{$Institucion_Nueva->getTelefono()}', Mail = '{$Institucion_Nueva->getMail()}' where ID_OtraInstitucion = {$Institucion_Nueva->getID_OtraInstitucion()} and Estado = 1";
		if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
			throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarRegistrosIguales, 2);
		}

		$Detalles = "El usuario con ID: $ID_Usuario ha modificado una Institucion. Datos: Dato Anterior: {$Institucion_Vieja->getNombre()} , Dato Nuevo: {$Institucion_Nueva->getNombre()} - Dato Anterior: {$Institucion_Vieja->getTelefono()} , Dato Nuevo: {$Institucion_Nueva->getTelefono()} - Dato Anterior: {$Institucion_Vieja->getMail()} , Dato Nuevo: {$Institucion_Nueva->getMail()}.";
		$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
		if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
			throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
		}
		$Con->CloseConexion();
		$Mensaje = "La Institucion se modificó Correctamente";
		header('Location: ../view_modotrasinstituciones.php?ID='.$ID_OtraInstitucion.'&Mensaje='.$Mensaje);
	}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}

?>