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

$ID_Categoria = $_REQUEST["ID_Categoria"];
$Codigo = strtoupper($_REQUEST["Codigo"]);
$Categoria = $_REQUEST["Categoria"];
$ID_Forma = $_REQUEST["ID_Forma"];
$NuevoColor = base64_decode($_REQUEST["CodigoColor"]);

$Fecha = date("Y-m-d");
$ID_TipoAccion = 2;

try {
	$Con = new Conexion();
	$Con->OpenConexion();


	// $ConsultarRegistrosIguales = "select * from categoria where cod_categoria = '$Codigo' and id_Categoria = '$ID_Categoria' and estado = 1";
	// if(!$RetIguales = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
	// 	throw new Exception("Problemas al consultar registros iguales. Consulta: ".$ConsultarRegistrosIguales, 0);		
	// }
	// $Resultado = mysqli_num_rows($RetIguales);	
	// if($Resultado > 0){
	// 	mysqli_free_result($RetIguales);
	// 	$Con->CloseConexion();
	// 	$Mensaje = "Ya existe una categoria con ese codigo por favor ingrese otro".$ConsultarRegistrosIguales;
	// 	header('Location: ../view_modcategorias.php?ID='.$ID_Categoria.'&Mensaje='.$Mensaje);
	// }else{
	// 	$ConsultarDatosViejos = "select * from categoria where id_categoria = $ID_Categoria and estado = 1";
	// 	$ErrorDatosViejos = "No se pudieron consultar los datos";
	// 	if(!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)){
	// 		throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
	// 	}		
	// 	$TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
	// 	$Cod_CategoriaViejo = $TomarDatosViejos["cod_categoria"];
	// 	$CategoriaViejo = $TomarDatosViejos["categoria"];
	// 	$ID_FormaViejo = $TomarDatosViejos["ID_Forma"];
	// 	$ColorViejo = $TomarDatosViejos["color"];

	// 	$CodigoColorEsc = mysqli_real_escape_string($Con->Conexion, $NuevoColor);
	// 	$Consulta = "update categoria set cod_categoria = '$Codigo', categoria = '$Categoria', ID_Forma = $ID_Forma, color = '$CodigoColorEsc' where id_categoria = $ID_Categoria and estado = 1";
		
	// 	if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
	// 		throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
	// 	}

	// 	$Detalles = "El usuario con ID: $ID_Usuario ha modificado una Categoria. Datos: Dato Anterior: $Cod_CategoriaViejo , Dato Nuevo: $Codigo - Dato Anterior: $CategoriaViejo , Dato Nuevo: $Categoria - Dato Anterior: $ID_FormaViejo , Dato Nuevo: $ID_Forma - Dato Anterior: $ColorViejo , Dato Nuevo: $NuevoColor";
	// 	$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
	// 	if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
	// 		throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
	// 	}

	// 	$ConsultaSolicitud = "update solicitudes_modificarcategorias set estado = 0 where Codigo = '$Codigo'";
	// 	if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
	// 		throw new Exception("Problemas en la consulta. Consulta: ".$ConsultaSolicitud, 3);			
	// 	}

	// 	$Con->CloseConexion();
	// 	$Mensaje = "La categoria se modifico Correctamente";
	// 	header('Location: ../view_inicio.php?ID='.$ID_Categoria.'&Mensaje='.$Mensaje);
	// }

	$ConsultarDatosViejos = "select * from categoria where id_categoria = $ID_Categoria and estado = 1";
	$ErrorDatosViejos = "No se pudieron consultar los datos";
	if(!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)){
		throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
	}		
	$TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
	$Cod_CategoriaViejo = $TomarDatosViejos["cod_categoria"];
	$CategoriaViejo = $TomarDatosViejos["categoria"];
	$ID_FormaViejo = $TomarDatosViejos["ID_Forma"];
	$ColorViejo = $TomarDatosViejos["color"];

	$Consulta = "update motivos 
				 set cod_categoria = '$Codigo' 
				 where cod_categoria = '$Cod_CategoriaViejo' 
				   and estado = 1";

	$CodigoColorEsc = mysqli_real_escape_string($Con->Conexion, $NuevoColor);
	$Consulta = "update categoria 
				 set cod_categoria = '$Codigo', 
					 categoria = '$Categoria', 
					 ID_Forma = $ID_Forma, 
					 color = '$CodigoColorEsc' 
				 where id_categoria = $ID_Categoria 
				   and estado = 1";
	
	if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
		throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
	}

	$Detalles = "El usuario con ID: $ID_Usuario ha modificado una Categoria. Datos: Dato Anterior: $Cod_CategoriaViejo , Dato Nuevo: $Codigo - Dato Anterior: $CategoriaViejo , Dato Nuevo: $Categoria - Dato Anterior: $ID_FormaViejo , Dato Nuevo: $ID_Forma - Dato Anterior: $ColorViejo , Dato Nuevo: $NuevoColor";
	$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
	if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
		throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
	}

	$ConsultaSolicitud = "update solicitudes_modificarcategorias set estado = 0 where Codigo = '$Codigo'";
	if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
		throw new Exception("Problemas en la consulta. Consulta: ".$ConsultaSolicitud, 3);			
	}

	$Con->CloseConexion();
	$Mensaje = "La categoria se modifico Correctamente";
	header('Location: ../view_inicio.php?ID='.$ID_Categoria.'&Mensaje='.$Mensaje);
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}

?>