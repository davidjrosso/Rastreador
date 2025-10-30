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

require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
header('Content-Type: application/json;');

$consultaBusqueda = (!isset($_REQUEST['calle']) ? null : $_REQUEST['calle']);

//Filtro anti-XSS
$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
$caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);
$consultaBusqueda = strtolower($consultaBusqueda);
$resultados = [];

if (!is_null($consultaBusqueda)) {

	$con = new Conexion();
	$con->OpenConexion();
    $consultaBusqueda = trim(strtolower($consultaBusqueda));
	$consultaCalles = "SELECT id_calle, calle_nombre
					   FROM calle
					   WHERE estado = 1
						 AND ((LOWER(calle_nombre) REGEXP '[a-z]* " . $consultaBusqueda . "[a-z]*')
						 	   or (LOWER(calle_nombre) REGEXP '^" .  $consultaBusqueda . "[a-z]*'))
					   order by calle_nombre ASC";
	$consulta = mysqli_query($con->Conexion, $consultaCalles);

	$filas = mysqli_num_rows($consulta);

	if ($filas) $resultados = mysqli_fetch_all($consulta,  MYSQLI_ASSOC);

	$con->CloseConexion();
}

$json_mensaje = json_encode($resultados);
echo $json_mensaje;