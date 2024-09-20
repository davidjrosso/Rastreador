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
$ID_Solicitud = $_REQUEST["ID"];
$Codigo = strtoupper($_REQUEST["Codigo"]);
$ID_Forma = $_REQUEST["ID_Forma"];
$Categoria = $_REQUEST["Categoria"];
$Color = base64_decode($_REQUEST["Color"]);
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
	if ($Resultado > 0) {
		mysqli_free_result($RetIguales);
		$Con->CloseConexion();
		$Mensaje = "Ya existe una categoria con ese Codigo";
		header('Location: ../view_newcategorias.php?MensajeError='.$Mensaje);
	} else {
		$Consulta = "insert into categoria(cod_categoria,categoria,ID_Forma,color,estado) values('".$Codigo."','".$Categoria."',".$ID_Forma.",'".$Color."',1)";
		if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
			throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 1);		
		}

		$ConsultarID_Categoria = "select id_categoria from categoria where cod_categoria = '$Codigo' and categoria = '$Categoria' limit 1";
		if (!$RetID = mysqli_query($Con->Conexion,$ConsultarID_Categoria)) {
			throw new Exception("No se pudo consultar el ID de la categoria cargada. Consulta: ".$ConsultarID_Categoria, 2);		
		}

		$TomarID_Categoria = mysqli_fetch_assoc($RetID);
		$RetID_Categoria = $TomarID_Categoria["id_categoria"];

		$ConsultaPermisos = "select ID_TipoUsuario
							 from solicitudes_permisos
							 where ID = {$ID_Solicitud}
							 and estado = 1";
		$MessageError = "Problemas al consultar mostrar Solicitudes Permisos";
		if (!$Resultados = mysqli_query($Con->Conexion,$ConsultaPermisos)) {
			throw new Exception("No se pudo insertar el conjunto de permisos. Consulta: ".$ConsultaPermisos, 2);
		}

		while ($RetPermisos = mysqli_fetch_array($Resultados)) {
			$GrupoUsuarios = $RetPermisos["ID_TipoUsuario"];
			$Insert_Permiso = "insert into categorias_roles(id_categoria, fecha, ID_TipoUsuario, estado) values('{$RetID_Categoria }', '{$Fecha}','{$GrupoUsuarios}', 1)";
			$updatePermisos = "update solicitudes_permisos
							   set estado = 0
							   where ID = {$ID_Solicitud}
							   and ID_TipoUsuario = {$GrupoUsuarios} 
							   and estado = 1";
			$MensajeError = "No se pudo dar de baja el permiso categoria {$RetID_Categoria } rol {$GrupoUsuarios}";
			$ResultadosUpdate = mysqli_query($Con->Conexion,$updatePermisos) or die($MessageError);
			if(!$RetID = mysqli_query($Con->Conexion,$Insert_Permiso)){
				throw new Exception("No se pudo actualizar el permisos. Consulta: ".$Insert_Permiso, 2);
			}
		}

		$Consulta = "select * 
					 from formas_categorias 
					 where id_forma = $ID_Forma";
		$consulta_resultado = mysqli_query($Con->Conexion,$Consulta);
		if(!$consulta_resultado){
		throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 1);		
		}
		$forma_categoria_row = mysqli_fetch_assoc($consulta_resultado);

		$forma_categoria = $forma_categoria_row["Forma_Categoria"];
		if (strlen($forma_categoria) > 1) {
			$forma_categoria = substr($forma_categoria, 2);
			$forma_categoria = substr($forma_categoria, 0, -1);
		} elseif (strlen($forma_categoria) == 1) {
			$forma_categoria = ord($forma_categoria);
		}
		list($r, $g, $b) = sscanf($Color, "#%02x%02x%02x");
		$color_icono = substr($Color, 1);
		$file_path = "../images/icons/motivos/" . $forma_categoria . "_" . $color_icono . ".png";
		$file_path_common = "../images/icons/motivos/" . $forma_categoria . ".png";

		if (!file_exists($file_path)) {
			$imagen = imagecreatefrompng($file_path_common);
			$is_filter = imagefilter($imagen, IMG_FILTER_COLORIZE, $r, $g, $b);
			$negro = imagecolorallocate($imagen, 0, 0, 0);
			imagecolortransparent($imagen, $negro);
			if (!$is_filter) {
				throw new Exception("Error al intentar filtrar el color del icono.", 2);
			}
			$fd = imagepng($imagen, $file_path);
			imagedestroy($imagen);
		}

		$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
		if (!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)) {
			throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 2);
		}

		$ActualizarSolicitud = "update solicitudes_crearcategorias set Estado = 0 where codigo = '$Codigo' and categoria = '$Categoria'";
		$EjecutarConsultar = mysqli_query($Con->Conexion,$ActualizarSolicitud) or die($MensajeErrorDatos);

		// CREANDO NOTIFICACION PARA EL USUARIO
		/*
		$DetalleNot = 'Se ha creado una nueva categoría: '.$Categoria.' , codigo: '.$Codigo;
		$Expira = date("Y-m-d", strtotime($Fecha." + 30 days"));
		
		$ConsultaNot = "insert into notificaciones(Detalle, Fecha, Expira, Estado) values('$DetalleNot','$Fecha', '$Expira',1)";
		if(!$RetNot = mysqli_query($Con->Conexion,$ConsultaNot)){
			throw new Exception("Error al intentar registrar Notificacion. Consulta: ".$ConsultaNot, 3);
		}*/

		$Con->CloseConexion();
		$Mensaje = "La categoria se creo Correctamente";
		header('Location: ../view_inicio.php?Mensaje='.$Mensaje);
	}

} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}