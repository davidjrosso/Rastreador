<?php
session_start();
require_once ($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");

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
header('content-type: application/json;');

try {
	$Con = new Conexion();
	$Con->OpenConexion();
    $valor = null;
    if (isset($_REQUEST['valor'])) $valor = $_REQUEST['valor'];
    $parametria = new Parametria(coneccion_base: $Con, codigo: "TITUL_INSTIT");
    $parametria->set_valor($valor);
    $parametria->update();
    
    $mensaje["status"] = true;
    echo json_encode($mensaje);
} catch (Exception $e) {
    $mensaje["status"] = false;
	echo json_encode($mensaje);
}
?>