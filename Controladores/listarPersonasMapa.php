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

$id_escuela = null;
$id_motivo = null;
$id_barrio = null;
$id_otra_institucion = null;

if (isset($_REQUEST["id_escuela"])) $id_escuela = $_REQUEST["id_escuela"];
if (isset($_REQUEST["id_motivo"])) $id_motivo = $_REQUEST["id_motivo"];
if (isset($_REQUEST["id_barrio"])) $id_barrio = $_REQUEST["id_barrio"];
if (isset($_REQUEST["id_otra_institucion"])) $id_otra_institucion = $_REQUEST["id_otra_institucion"];

try {
	$con = new Conexion();
	$con->OpenConexion();
    $json_response = [];
    $row = null;
    $rows = [];
    $consulta = "SELECT DISTINCT
                                 p.id_persona,
                                 p.apellido,
                                 p.nombre,
                                 ST_X(p.georeferencia) AS lat,
                                 ST_Y(p.georeferencia) AS lon
                 FROM persona p
                      LEFT JOIN movimiento vl ON (p.id_persona = vl.id_persona)
                      LEFT JOIN movimiento_motivo vt ON (vl.id_movimiento = vt.id_movimiento)
                      LEFT JOIN motivo vz ON (vt.id_motivo = vz.id_motivo)
                      INNER JOIN barrios r ON (p.ID_Barrio = r.ID_Barrio)
                      LEFT JOIN escuelas s ON (s.ID_Escuela = p.ID_Escuela)
                 WHERE p.estado = 1
                   AND vl.estado = 1
                   AND vt.estado = 1
                   AND vz.estado = 1
                   AND s.Estado = 1
                   AND r.estado = 1";
    $consulta .= ($id_escuela) ? " AND p.ID_Escuela = $id_escuela" : "";
    $consulta .= ($id_motivo) ? " AND vz.id_motivo = $id_motivo"  : "";
    $consulta .= ($id_barrio) ? " AND p.ID_Barrio = $id_barrio" : "";
    $consulta .= ($id_otra_institucion) ? " AND ID_OtraInstitucion = $id_otra_institucion" : "";
    $resultado = mysqli_query($con->Conexion,$consulta);
    if(!$resultado){
        throw new Exception("Problemas en la consulta. Consulta: " . $consulta, 1);		
    }
    while ($ret = mysqli_fetch_array($resultado)) {
        $row["id_persona"] = $ret["id_persona"];
        $row["apellido"] = $ret["apellido"];
        $row["nombre"] = $ret["nombre"];
        $row["lat"] = $ret["lat"];
        $row["lon"] = $ret["lon"];
        $row["color"] = "#FF9900";
        $row["caracter"] = "9635";
        $rows[] = $row;
    }
    $json_response["personas"] = $rows;

    echo json_encode($json_response);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}