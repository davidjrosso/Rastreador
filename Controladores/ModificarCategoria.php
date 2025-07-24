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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");

$ID_Usuario = $_SESSION["Usuario"];
$ID_Categoria = $_REQUEST["ID_Categoria"];
$ID_Solicitud = $_REQUEST["ID"];
$Codigo = strtoupper($_REQUEST["Codigo"]);
$Categoria = $_REQUEST["Categoria"];
$ID_Forma = $_REQUEST["ID_Forma"];
$NuevoColor = base64_decode($_REQUEST["CodigoColor"]);

$Fecha = date("Y-m-d");
$ID_TipoAccion = 2;

try {
	$Con = new Conexion();
	$Con->OpenConexion();

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

	$Detalles = "El usuario con ID: $ID_Usuario ha modificado la Categoria: $ID_Categoria. Datos: Dato Anterior: $Cod_CategoriaViejo , Dato Nuevo: $Codigo - Dato Anterior: $CategoriaViejo , Dato Nuevo: $Categoria - Dato Anterior: $ID_FormaViejo , Dato Nuevo: $ID_Forma - Dato Anterior: $ColorViejo , Dato Nuevo: $NuevoColor con id solicitud : $ID_Solicitud";
	$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
	if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
		throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
	}

	$ConsultaPermisos = "select tip.ID_TipoUsuario, IF(slp.ID_TipoUsuario IS NULL, 'si', 'no') as disable
						 from (SELECT * FROM solicitudes_permisos WHERE ID = {$ID_Solicitud} and estado = 1) slp right join Tipo_Usuarios tip ON slp.ID_TipoUsuario = tip.ID_TipoUsuario";
	$MessageError = "Problemas al consultar Solicitudes Permisos";
	if(!$Resultados = mysqli_query($Con->Conexion,$ConsultaPermisos)){
		throw new Exception("No se pudo insertar el conjunto de permisos. Consulta: ".$ConsultaPermisos, 2);
	}
	while ($RetPermisos = mysqli_fetch_array($Resultados)) {
		$GrupoUsuarios = $RetPermisos["ID_TipoUsuario"];

		$ConsultaPermisosCategoria = "select *
							 		  from categorias_roles
							 		  where id_categoria = {$ID_Categoria}
							   			and id_tipousuario = {$GrupoUsuarios} 
							   			and estado = 1";

		$MessageError = "Problemas al consultar Categorias Permisos";
		if(!$ResultadosPermisosCategorias = mysqli_query($Con->Conexion,$ConsultaPermisosCategoria)){
			throw new Exception("No se pudo consultar conjunto de permisos sobre la categoria. Consulta: ".$ConsultaPermisosCategoria, 2);
		}

		if(mysqli_num_rows($ResultadosPermisosCategorias) == 0){
			if( $RetPermisos["disable"] == "no"){
				$Insert_Permiso = "insert into categorias_roles(id_categoria, fecha, ID_TipoUsuario, estado) values('{$ID_Categoria}', '{$Fecha}','{$GrupoUsuarios}', 1)";
				if(!$RetID = mysqli_query($Con->Conexion,$Insert_Permiso)){
					throw new Exception("No se pudo actualizar el permisos. Consulta: ".$Insert_Permiso, 2);
				}
				$updatePermisos = "update solicitudes_permisos
								   set estado = 0
								   where ID = {$ID_Solicitud}
									 and ID_TipoUsuario = {$GrupoUsuarios} 
									 and estado = 1";
				$MensajeError = "No se pudo dar de baja el permiso categoria {$ID_Categoria} rol {$GrupoUsuarios}";
				$ResultadosUpdate = mysqli_query($Con->Conexion,$updatePermisos) or die($MessageError);
			}
		} else {
			if( $RetPermisos["disable"] == "si"){
				$updatePermisos = "update categorias_roles
								   set estado = 0
								   where id_categoria = {$ID_Categoria}
				  				   and ID_TipoUsuario = {$GrupoUsuarios} 
				  				   and estado = 1";
				$MensajeError = "No se pudo dar de baja el permiso categoria {$ID_Categoria} rol {$GrupoUsuarios}";
				if(!$RetID = mysqli_query($Con->Conexion,$updatePermisos)){
					throw new Exception($MensajeError . ". Consulta: ".$updatePermisos, 2);
				}
			} else {
				$updatePermisos = "update solicitudes_permisos
								   set estado = 0
								   where ID = {$ID_Solicitud}
				  				   and ID_TipoUsuario = {$GrupoUsuarios} 
				  				   and estado = 1";
				$MensajeError = "No se pudo dar de baja el permiso categoria {$ID_Categoria} rol {$GrupoUsuarios}";
				$ResultadosUpdate = mysqli_query($Con->Conexion,$updatePermisos) or die($MessageError);
			}
		}
	}

	$ConsultaSolicitud = "update solicitudes_modificarcategorias set estado = 0 where Codigo = '$Codigo' and estado = 1";
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