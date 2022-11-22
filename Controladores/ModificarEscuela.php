<?php 
session_start();
require_once 'Conexion.php';
require_once '../Modelo/Escuela.php';
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

$ID_Escuela = $_REQUEST["ID"];
$Codigo = $_REQUEST["Codigo"];
$Escuela = ucfirst($_REQUEST["Escuela"]);
$CUE = $_REQUEST["CUE"];
$Localidad = ucwords($_REQUEST["Localidad"]);
$Departamento = ucwords($_REQUEST["Departamento"]);
$Directora = ucwords($_REQUEST["Directora"]);
$Telefono = $_REQUEST["Telefono"];
$Mail = ucfirst($_REQUEST["Mail"]);
$ID_Nivel = $_REQUEST["ID_Nivel"];
$Estado = 1;

$Escuela_Nueva = new Escuela($ID_Escuela,$Codigo,$Escuela,$CUE,$Localidad,$Departamento,$Directora,$Telefono,$Mail,$ID_Nivel,$Estado);

$Fecha = date("Y-m-d");
$ID_TipoAccion = 2;

$Con = new Conexion();
$Con->OpenConexion();

try {
	$ConsultarRegistrosIguales = "select * from escuelas where Escuela = '$Escuela' and ID_Escuela != $ID_Escuela and estado = 1";
	if(!$Ret = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
		throw new Exception("Error al consultar registros. Consulta: ".$ConsultarRegistrosIguales, 0);		
	}
	$Resultado = mysqli_num_rows($Ret);
	if($Resultado > 0){
		$Con->CloseConexion();
		$Mensaje = "Ya existe una Escuela con ese Nombre";
		header('Location: ../view_modescuelas.php?ID='.$ID_Escuela.'&MensajeError='.$Mensaje);
	}else{
		$ConsultarDatosViejos = "select * from escuelas where ID_Escuela = $ID_Escuela and Estado = 1";
		$ErrorDatosViejos = "No se pudieron consultar los datos";
		if(!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)){
			throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
		}		
		$TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
		$ID_Escuela = $TomarDatosViejos["ID_Escuela"];
		$Codigo = $TomarDatosViejos["Codigo"];
		$Escuela = $TomarDatosViejos["Escuela"];
		$CUE = $TomarDatosViejos["CUE"];
		$Localidad = $TomarDatosViejos["Localidad"];
		$Departamento = $TomarDatosViejos["Departamento"];
		$Directora = $TomarDatosViejos["Directora"];
		$Telefono = $TomarDatosViejos["Telefono"];
		$Mail = $TomarDatosViejos["Mail"];
		$ID_Nivel = $TomarDatosViejos["ID_Nivel"];
		$Estado = $TomarDatosViejos["Estado"];
		
		$Escuela_Vieja = new Escuela($ID_Escuela,$Codigo,$Escuela,$CUE,$Localidad,$Departamento,$Directora,$Telefono,$Mail,$ID_Nivel,$Estado);
		

		$Consulta = "update escuelas set Codigo = '{$Escuela_Nueva->getCodigo()}', Escuela = '{$Escuela_Nueva->getEscuela()}', CUE = '{$Escuela_Nueva->getCUE()}', Localidad = '{$Escuela_Nueva->getLocalidad()}', Departamento = '{$Escuela_Nueva->getDepartamento()}', Directora = '{$Escuela_Nueva->getDirectora()}', Telefono = '{$Escuela_Nueva->getTelefono()}', Mail = '{$Escuela_Nueva->getMail()}', ID_Nivel = {$Escuela_Nueva->getID_Nivel()} where ID_Escuela = {$Escuela_Nueva->getID_Escuela()} and Estado = 1";
		if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
			throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarRegistrosIguales, 2);
		}

		$Detalles = "El usuario con ID: $ID_Usuario ha modificado una Escuela. Datos: Dato Anterior: {$Escuela_Vieja->getCodigo()} , Dato Nuevo: {$Escuela_Nueva->getCodigo()} - Dato Anterior: {$Escuela_Vieja->getEscuela()} , Dato Nuevo: {$Escuela_Nueva->getEscuela()} - Dato Anterior: {$Escuela_Vieja->getCUE()} , Dato Nuevo: {$Escuela_Nueva->getCUE()} - Dato Anterior: {$Escuela_Vieja->getLocalidad()} , Dato Nuevo: {$Escuela_Nueva->getLocalidad()} - Dato Anterior: {$Escuela_Vieja->getDepartamento()} , Dato Nuevo: {$Escuela_Nueva->getDepartamento()} - Dato Anterior: {$Escuela_Vieja->getDirectora()} , Dato Nuevo: {$Escuela_Nueva->getDirectora()} - Dato Anterior: {$Escuela_Vieja->getTelefono()} , Dato Nuevo: {$Escuela_Nueva->getTelefono()} - Dato Anterior: {$Escuela_Vieja->getMail()} , Dato Nuevo: {$Escuela_Nueva->getMail()} - Dato Anterior: {$Escuela_Vieja->getID_Nivel()} , Dato Nuevo: {$Escuela_Nueva->getID_Nivel()}.";
		$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
		if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
			throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
		}
		$Con->CloseConexion();
		$Mensaje = "La Escuela se modificó Correctamente";
		header('Location: ../view_modescuelas.php?ID='.$ID_Escuela.'&Mensaje='.$Mensaje);
	}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}

?>