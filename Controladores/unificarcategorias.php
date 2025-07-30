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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Categoria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/CategoriaRol.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_unificacion.php");

$ID_Usuario = $_SESSION["Usuario"];
$id_solicitud = $_REQUEST["ID"];
$fecha = date("Y-m-d");
$ID_TipoAccion = 2;

try {
	$solicitud = new Solicitud_unificacion(xID_Solicitud: $id_solicitud);

	$id_categoria_unif = $solicitud->getID_Registro_1();
	$id_categoria_del = $solicitud->getID_Registro_2();

	if (!empty($id_categoria_unif) && !empty($id_categoria_del)) {
		$con = new Conexion();
		$con->OpenConexion();

		$categoria_unif = new Categoria(xID_Categoria: $id_categoria_unif, xConecction: $con);
		if (!Categoria::exist_categoria($con, $id_categoria_del)) {
			$mensaje = "La categoria a unificar ya a sido unificada o no existe.";
			header('Location: ../view_unifcategorias.php?MensajeError=' . $mensaje);
		} else {
			$categoria_del = new Categoria(xID_Categoria: $id_categoria_del, xConecction: $con);
			$cod_categoria_unif = $categoria_unif->getCod_Categoria();
			$cod_categoria_del = $categoria_del->getCod_Categoria();
			$Consulta = "update motivo 
						set cod_categoria = '$cod_categoria_unif' 
						where cod_categoria = '$cod_categoria_del' 
						and estado = 1";

			if(!$Ret = mysqli_query($con->Conexion,$Consulta)){
				throw new Exception("Problemas en la consulta. Consulta: " . $Consulta, 2);		
			}

			$categoria_del->delete();

			$Detalles = "El usuario con ID: $ID_Usuario ha unificado la Categoria: $id_categoria_del con la categoria $id_categoria_unif.";
			$accion = new Accion(
								xaccountid: $ID_Usuario,
								xDetalles: $Detalles,
								xFecha: $fecha,
								xID_TipoAccion: $ID_TipoAccion
								);
			$accion->save();
			$consulta_permisos = "select *
								from categorias_roles
								where id_categoria = $id_categoria_del";
			$message_error = "Problemas al consultar categorias roles";
			if (!$resultados = mysqli_query($con->Conexion,$consulta_permisos)) {
				throw new Exception($message_error . ". Consulta: " . $consulta_permisos, 2);
			}
			while ($RetPermisos = mysqli_fetch_array($resultados)) {
				$grupo_usuarios = $RetPermisos["id_tipousuario"];
				$id_categoria_rol = $RetPermisos["id_categoria_rol"];
				if (!CategoriaRol::exist_rol(connection: $con, 
											id_categoria: $id_categoria_unif,
											id_tipo_usuario: $grupo_usuarios)) {
					$categoria_rol = new CategoriaRol(id_categoria: $id_categoria_unif,
													id_tipo_usuario: $grupo_usuarios, 
													fecha: $fecha,
													conecction: $con,
													estado: 1
													);
					$categoria_rol->save();
				}
				$categoria_rol = new CategoriaRol(id_categoria_rol: $id_categoria_rol);
				$categoria_rol->delete();
			}

			$Con->CloseConexion();
			$Mensaje = "La categoria se unifico correctamente";
			header('Location: ../view_unifcategorias.php?Mensaje=' . $Mensaje);
		}
	} else {
		$Mensaje = "Elija las categorias a unificar";
		header('Location: ../view_unifcategorias.php?MensajeError=' . $Mensaje);
	}
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}
