<?php
session_start(); 
require_once 'Conexion.php';
require_once '../Modelo/Solicitud_EliminarMotivo.php';
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

$ID_Motivo = $_REQUEST["ID"];
$Fecha = date("Y-m-d");
$Estado = 1;

$Con = new Conexion();
$Con->OpenConexion();

$ConsultarMotivo = "select id_motivo, motivo, cod_categoria from motivo where id_motivo = $ID_Motivo";

if(!$RetMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo)){
	throw new Exception("Problemas al consultar datos de motivo. Consulta: ".$ConsultarMotivo, 1);			
}
$TomarMotivo = mysqli_fetch_assoc($RetMotivo);
$Motivo = $TomarMotivo['motivo'];
$Cod_Categoria = $TomarMotivo["cod_categoria"];
$Num_Motivo = 0;

$Solicitud = new Solicitud_EliminarMotivo(0,$Fecha,$Motivo,$Cod_Categoria,$Num_Motivo,$Estado,$ID_Usuario,$ID_Motivo);
$Insert_Solicitud = "insert into solicitudes_eliminarmotivos(Fecha,Motivo,Cod_Categoria,Num_Motivo,Estado,ID_Usuario,ID_Motivo) values('{$Solicitud->getFecha()}','{$Solicitud->getMotivo()}','{$Solicitud->getCod_Categoria()}',{$Solicitud->getNum_Motivo()},{$Solicitud->getEstado()},{$Solicitud->getID_Usuario()},{$Solicitud->getID_Motivo()})";
$MensajeError = "No se pudo enviar la solicitud";

mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError);

$Con->CloseConexion();
$Mensaje = "La solicitud de eliminación se envió a los administradores para ser confirmada.";
header('Location: ../view_motivos.php?Mensaje='.$Mensaje);
?>