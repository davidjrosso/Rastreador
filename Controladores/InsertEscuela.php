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

$Codigo = $_REQUEST["Codigo"];
$Escuela = ucfirst($_REQUEST["Escuela"]);
$CUE = $_REQUEST["CUE"];
$Localidad = ucwords($_REQUEST["Localidad"]);
$Departamento = ucwords($_REQUEST["Departamento"]);
$Directora = ucwords($_REQUEST["Directora"]);
$Telefono = $_REQUEST["Telefono"];
$Mail = ucfirst($_REQUEST["Mail"]);
$Estado = 1;

if($_REQUEST["ID_Nivel"] > 0){
	$ID_Nivel = $_REQUEST["ID_Nivel"];
}else{
	$ID_Nivel = 'null';
}

if(empty($CUE)){
	$CUE = 'null';
}

if(empty($Localidad)){
	$Localidad = 'null';
}

if(empty($Departamento)){
	$Departamento = 'null';
}

if(empty($Directora)){
	$Directora = 'null';
}

if(empty($Telefono)){
	$Telefono = 'null';
}

if(empty($Mail)){
	$Mail = 'null';
}

$Fecha = date("Y-m-d");
$ID_TipoAccion = 1;
$Detalles = "El usuario con ID: $ID_Usuario ha registrado una nueva Escuela. Datos: Codigo: $Codigo - Escuela: $Escuela - CUE: $CUE - Localidad: $Localidad - Departamento: $Departamento - Directora: $Directora - Telefono: $Telefono - Mail: $Mail - Nivel: $ID_Nivel";

$Con = new Conexion();
$Con->OpenConexion();

try {
	$ConsultarResponsablesIguales = "select * from escuelas where Escuela = '$Escuela' and Estado = 1";
	if(!$Ret = mysqli_query($Con->Conexion,$ConsultarResponsablesIguales)){
		throw new Exception("Error al consultar registros. Consulta: ".$ConsultarResponsablesIguales, 0);		
	}
	$Resultado = mysqli_num_rows($Ret);
	if($Resultado > 0){
		$Con->CloseConexion();
		$Mensaje = "Ya existe una Escuela con ese Nombre";
		header('Location: ../view_newescuelas.php?MensajeError='.$Mensaje);
	}else{
		$InstEscuela = new Escuela(0,$Codigo,$Escuela,$CUE,$Localidad,$Departamento,$Directora,$Telefono,$Mail,$ID_Nivel,$Estado);
		$Consulta = "insert into escuelas(Codigo,Escuela,CUE,Localidad,Departamento,Directora,Telefono,Mail,ID_Nivel,Estado) values('{$InstEscuela->getCodigo()}','{$InstEscuela->getEscuela()}','{$InstEscuela->getCUE()}','{$InstEscuela->getLocalidad()}','{$InstEscuela->getDepartamento()}','{$InstEscuela->getDirectora()}','{$InstEscuela->getTelefono()}','{$InstEscuela->getMail()}',{$InstEscuela->getID_Nivel()},{$InstEscuela->getEstado()})";
		if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
			throw new Exception("Error al intentar registrar. Consulta: ".$Consulta, 1);
		}	
		$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
		if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
			throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 2);
		}
		$Con->CloseConexion();
		$Mensaje = "La Escuela se registro Correctamente";
		header('Location: ../view_newescuelas.php?Mensaje='.$Mensaje);
	}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
?>