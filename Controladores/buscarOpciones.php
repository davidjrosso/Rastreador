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

header("content-type: application/json");

try {
	$con = new Conexion();
	$con->OpenConexion();
    $json_response = [];
    $row = null;
    $rows = [];
    $consulta = "select * 
                 from motivo 
                 where estado = 1";
    $resultado = mysqli_query($con->Conexion,$consulta);
    if(!$resultado){
        throw new Exception("Problemas en la consulta. Consulta: " . $consulta, 1);		
    }
    while ($ret = mysqli_fetch_array($resultado)) {
        $row["id_motivo"] = $ret["id_motivo"];
        $row["motivo"] = $ret["motivo"];
        $rows[] = $row;
    }
    $json_response["motivos"] = $rows;
    $rows = [];
    $row = [];

    $consulta = "select * 
                 from barrios 
                 where estado = 1";
    $resultado = mysqli_query($con->Conexion,$consulta);
    if(!$resultado){
        throw new Exception("Problemas en la consulta. Consulta: " . $consulta, 1);		
    }
    while ($ret = mysqli_fetch_array($resultado)) {
        $row["id_barrio"] = $ret["ID_Barrio"];
        $row["barrio"] = $ret["Barrio"];
        $rows[] = $row;
    }
    $json_response["barrios"] = $rows;
    $rows = [];
    $row = [];

    $consulta = "select * 
                 from escuelas 
                 where Estado = 1";
    $resultado = mysqli_query($con->Conexion,$consulta);
    if(!$resultado){
        throw new Exception("Problemas en la consulta. Consulta: " . $consulta, 1);		
    }
    while ($ret = mysqli_fetch_array($resultado)) {
        $row["id_escuela"] = $ret["ID_Escuela"];
        $row["escuela"] = $ret["Escuela"];
        $rows[] = $row;
    }
    $json_response["escuelas"] = $rows;
    $rows = [];
    $row = [];
    
    $consulta = "select * 
                 from otras_instituciones 
                 where Estado = 1";
    $resultado = mysqli_query($con->Conexion,$consulta);
    if(!$resultado){
        throw new Exception("Problemas en la consulta. Consulta: " . $consulta, 1);		
    }
    while ($ret = mysqli_fetch_array($resultado)) {
        $row["id_otra_institucion"] = $ret["ID_OtraInstitucion"];
        $row["otra_institucion"] = $ret["Nombre"];
        $rows[] = $row;
    }
    $json_response["otras_instituciones"] = $rows;

    echo json_encode($json_response);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}