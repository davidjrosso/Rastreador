<?php 
require_once 'Conexion.php';
require_once '../Modelo/Proveedor.php';
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

$NombreProveedor = $_REQUEST["NombreProv"];



$Proveedor = new Proveedor($NombreProveedor);
$Con = new Conexion();
$Con->OpenConexion();
$Consulta = "insert into prov(NombProv) values('".$Proveedor->getNombreProv()."')";
$Ret = mysqli_query($Con->Conexion,$Consulta)or die("Problemas en la consulta");
$Con->CloseConexion();

echo "Se inserto";

?>