<?php  
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

$ID_Categoria = $_REQUEST["ID_Categoria"];
$CodigoColor = $_REQUEST["CodigoColor"];
$ID_Forma = $_REQUEST["ID_Forma"];


$Con = new Conexion();
$Con->OpenConexion();

try {
	$CodigoColorEsc = mysqli_real_escape_string($Con->Conexion, $CodigoColor);

	$ConsultarColor = "select * from categoria where color = '$CodigoColorEsc' and ID_Forma = $ID_Forma";
	$ErrorConsultarColor = "No se pudo consultar color";

	$RetColor = mysqli_query($Con->Conexion,$ConsultarColor) or die($ErrorConsultarColor.$ConsultarColor);
	$Validar = mysqli_num_rows($RetColor);

	if($Validar > 0){
		$MensajeError = "Ya existe una categoria con ese color y esa forma por favor seleccione otro.";
		$Con->CloseConexion();	
		header('Location: '.getenv('HTTP_REFERER').'&MensajeError='.$MensajeError);
	}else{
		$Consulta = "update categoria set color = '$CodigoColorEsc', estado = 1 where id_categoria = $ID_Categoria";
		$ErrorConsulta = "No se pudo asignar el color a la categoria creada";
		$Ret = mysqli_query($Con->Conexion,$Consulta)or die($ErrorConsulta);
		$Mensaje = "La categoria se registro Correctamente";
		$Con->CloseConexion();	
		header('Location: ../view_categorias.php?Mensaje='.$Mensaje);
	}
} catch (Exception $e) {
	echo $e->getMessage();
}


?>