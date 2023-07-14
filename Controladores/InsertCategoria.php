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

$Codigo = strtoupper($_REQUEST["Codigo"]);
$ID_Forma = $_REQUEST["ID_Forma"];
$Categoria = $_REQUEST["Categoria"];

$Fecha = date("Y-m-d");
$ID_TipoAccion = 1;
$Detalles = "El usuario con ID: $ID_Usuario ha registrado una nueva Categoria. Datos: $Codigo - $Categoria";

try {
	$Con = new Conexion();
	$Con->OpenConexion();

	$ConsultarRegistrosIguales = "select * from categoria where cod_categoria = '$Codigo' and estado = 1";
	if(!$RetIguales = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
		throw new Exception("Problemas al consultar registros iguales. Consulta: ".$ConsultarRegistrosIguales, 0);		
	}
	$Resultado = mysqli_num_rows($RetIguales);
	if($Resultado > 0){
		mysqli_free_result($RetIguales);
		$Con->CloseConexion();
		$Mensaje = "Ya existe una categoria con ese Codigo";
		header('Location: ../view_newcategorias.php?MensajeError='.$Mensaje);
	}else{
		$Consulta = "insert into categoria(cod_categoria,categoria,ID_Forma) values('".$Codigo."','".$Categoria."',".$ID_Forma.")";
		if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
			throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 1);		
		}

		$ConsultarID_Categoria = "select id_categoria from categoria where cod_categoria = '$Codigo' and categoria = '$Categoria' limit 1";
		if(!$RetID = mysqli_query($Con->Conexion,$ConsultarID_Categoria)){
			throw new Exception("No se pudo consultar el ID de la categoria cargada. Consulta: ".$ConsultarID_Categoria, 2);		
		}
		$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
		if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
			throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 2);
		}
		$TomarID_Categoria = mysqli_fetch_assoc($RetID);
		$RetID_Categoria = $TomarID_Categoria["id_categoria"];

		// CREANDO NOTIFICACION PARA EL USUARIO
		$DetalleNot = 'Se ha creado una nueva categoria: '.$Categoria.' , codigo: '.$Codigo;
		$Expira = date("Y-m-d", strtotime($Fecha." + 30 days"));
		
		$ConsultaNot = "insert into notificaciones(Detalle, Fecha, Expira, Estado) values('$DetalleNot','$Fecha', '$Expira',1)";
		if(!$RetNot = mysqli_query($Con->Conexion,$ConsultaNot)){
			throw new Exception("Error al intentar registrar Notificacion. Consulta: ".$ConsultaNot, 3);
		}

		$Con->CloseConexion();
		header('Location: ../view_colorcategoria.php?ID='.$RetID_Categoria.'&ID_Forma='.$ID_Forma);
	}

} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}






?>